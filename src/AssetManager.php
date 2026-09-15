<?php

declare(strict_types=1);

namespace Knppy\Ui;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use SplFileInfo;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class AssetManager
{
    private const string SCRIPT_PATH = __DIR__.'/../dist/ui.js';

    public static function boot(): void
    {
        Blade::directive('uiScripts', fn (string $expression): string => sprintf(
            '<?php echo app(%s::class)->scripts(%s); ?>',
            Ui::class,
            $expression,
        ));

        Route::get('/ui/ui.js', [self::class, 'javascript'])
            ->name('__ui.script');
    }

    /**
     * @param  array{nonce?: string}  $options
     */
    public static function scripts(array $options = []): string
    {
        $url = url(route('__ui.script', absolute: false).'?id='.hash_file('xxh128', self::SCRIPT_PATH));
        $nonce = isset($options['nonce'])
            ? ' nonce="'.e($options['nonce']).'"'
            : '';

        return '<script src="'.e($url).'" defer data-navigate-once'.$nonce.'></script>';
    }

    public function javascript(Request $request): Response
    {
        $lastModified = (new SplFileInfo(self::SCRIPT_PATH))->getMTime();
        $headers = [
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'Content-Type' => 'application/javascript; charset=utf-8',
            'Last-Modified' => gmdate('D, d M Y H:i:s', $lastModified).' GMT',
            'X-Content-Type-Options' => 'nosniff',
        ];

        if (@strtotime((string) $request->header('If-Modified-Since')) === $lastModified) {
            return response('', Response::HTTP_NOT_MODIFIED, $headers);
        }

        return new BinaryFileResponse(self::SCRIPT_PATH, headers: $headers);
    }
}
