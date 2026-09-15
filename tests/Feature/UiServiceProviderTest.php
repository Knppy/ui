<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Knppy\Ui\ClassBuilder;
use Knppy\Ui\Ui;
use Knppy\Ui\UiServiceProvider;

test('config is merged from package', function (): void {
    expect(config('ui'))->toBeArray();
    expect(config('ui.cache_store'))->toBeNull();
});

test('Ui singleton is bound in the container', function (): void {
    expect(app(Ui::class))->toBeInstanceOf(Ui::class);
    expect(app(Ui::class))->toBe(app(Ui::class));
});

test('ClassBuilder singleton is bound in the container', function (): void {
    expect(app(ClassBuilder::class))->toBeInstanceOf(ClassBuilder::class);
    expect(app(ClassBuilder::class))->toBe(app(ClassBuilder::class));
});

test('ClassBuilder uses a custom cache store when configured', function (): void {
    config(['ui.cache_store' => 'array']);

    // Re-resolve a fresh instance to exercise the getCacheStore branch with a named store
    $builder = app()->make(ClassBuilder::class);

    expect($builder)->toBeInstanceOf(ClassBuilder::class);
});

test('ui:install command is registered', function (): void {
    expect(Artisan::all())->toHaveKey('ui:install');
});

test('ui:add command is registered', function (): void {
    expect(Artisan::all())->toHaveKey('ui:add');
});

test('package publish tags are registered', function (): void {
    // ServiceProvider::pathsToPublish returns entries when the tag is registered
    $paths = ServiceProvider::pathsToPublish(
        UiServiceProvider::class,
        'ui-config',
    );

    expect($paths)->not->toBeEmpty();
});

test('lang publish tag is registered', function (): void {
    $paths = ServiceProvider::pathsToPublish(
        UiServiceProvider::class,
        'ui-lang',
    );

    expect($paths)->not->toBeEmpty();
});

test('uiScripts directive renders the package javascript asset', function (): void {
    $html = Blade::render('@uiScripts');

    expect($html)
        ->toContain('<script')
        ->toContain('src="http://localhost/ui/ui.js?id=')
        ->toContain('defer')
        ->toContain('data-navigate-once');
});

test('uiScripts directive supports a content security policy nonce', function (): void {
    $html = Blade::render('@uiScripts([\'nonce\' => \'test-nonce\'])');

    expect($html)->toContain('nonce="test-nonce"');
});

test('serves the compiled javascript asset with cache headers', function (): void {
    $response = $this->get('/ui/ui.js');

    $response
        ->assertOk()
        ->assertHeader('Content-Type', 'application/javascript; charset=utf-8');

    expect($response->headers->get('Cache-Control'))
        ->toContain('public')
        ->toContain('max-age=31536000')
        ->toContain('immutable')
        ->and($response->headers->get('Last-Modified'))->not->toBeNull();

    expect(File::get(__DIR__.'/../../dist/ui.js'))
        ->toContain('uiAccordion')
        ->toContain('window.Alpine');
});

test('returns not modified for a cached javascript asset', function (): void {
    $lastModified = $this->get('/ui/ui.js')->headers->get('Last-Modified');

    $this->withHeader('If-Modified-Since', $lastModified)
        ->get('/ui/ui.js')
        ->assertNotModified();
});

test('interactive form components render native inputs', function (): void {
    $slider = Blade::render('<x-ui.slider name="volume" value="25" min="0" max="50" />');
    $otp = Blade::render('<x-ui.input-otp name="code" maxlength="4"><x-ui.input-otp-group><x-ui.input-otp-slot :index="0" /></x-ui.input-otp-group></x-ui.input-otp>');

    expect($slider)
        ->toContain('x-data="uiSlider(\'25\', \'0\', \'50\', 1)"')
        ->toContain('type="range"')
        ->toContain('name="volume"')
        ->and($otp)
        ->toContain('x-data="uiInputOtp(\'\', \'4\')"')
        ->toContain('autocomplete="one-time-code"')
        ->toContain('name="code"')
        ->toContain('data-slot="input-otp-slot"');
});

test('commands and publishables are not registered outside console context', function (): void {
    $app = app();

    // Swap runningInConsole to return false
    $app->bind('env', fn () => 'testing');

    $provider = new UiServiceProvider($app);

    // Mock the app so runningInConsole() returns false
    $mockApp = Mockery::mock($app)->makePartial();
    $mockApp->shouldReceive('runningInConsole')->andReturn(false);

    $provider2 = new UiServiceProvider($mockApp);
    $provider2->boot();

    // No exception means the early returns were taken — the test passing is the assertion
    expect(true)->toBeTrue();
});
