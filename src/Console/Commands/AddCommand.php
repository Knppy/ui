<?php

declare(strict_types=1);

namespace Knppy\Ui\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\multisearch;
use function Laravel\Prompts\search;

#[AsCommand(name: 'ui:add')]
class AddCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'ui:add {components?*} {--multiple} {--all} {--force}';

    /**
     * The command description.
     */
    protected $description = 'Add individual UI components.';

    /**
     * The available component groups. The array key is both the group name
     * and the stub subdirectory the group's components are published from,
     * e.g. `stubs/views/components/{group}`.
     *
     * Each group maps a component name to the components (from the same
     * group) it depends on.
     *
     * @var array<string, array<string, array<int, string>>>
     */
    protected array $componentGroups = [
        'ui' => [
            'accordion' => ['accordion', 'accordion-content', 'accordion-item', 'accordion-trigger'],
            'alert' => ['alert', 'alert-description', 'alert-title'],
            'alert-dialog' => ['alert-dialog', 'alert-dialog-action', 'alert-dialog-cancel', 'alert-dialog-content', 'alert-dialog-description', 'alert-dialog-footer', 'alert-dialog-header', 'alert-dialog-media', 'alert-dialog-overlay', 'alert-dialog-portal', 'alert-dialog-title', 'alert-dialog-trigger'],
            'aspect-ratio' => ['aspect-ratio'],
            'attachment' => ['attachment', 'attachment-action', 'attachment-actions', 'attachment-content', 'attachment-description', 'attachment-group', 'attachment-media', 'attachment-title', 'attachment-trigger'],
            'avatar' => ['avatar', 'avatar-badge', 'avatar-fallback', 'avatar-group', 'avatar-group-count', 'avatar-image'],
            'badge' => ['badge'],
            'breadcrumbs' => ['breadcrumb-ellipsis', 'breadcrumb-item', 'breadcrumb-link', 'breadcrumb-list', 'breadcrumb-page', 'breadcrumb-separator', 'breadcrumbs'],
            'bubble' => ['bubble', 'bubble-content', 'bubble-group', 'bubble-reactions'],
            'button' => ['button'],
            'button-group' => ['button-group', 'button-group-separator', 'button-group-text'],
            'calendar' => ['calendar'],
            'card' => ['card', 'card-action', 'card-content', 'card-description', 'card-footer', 'card-header', 'card-title'],
            'carousel' => ['carousel', 'carousel-content', 'carousel-item', 'carousel-next', 'carousel-previous'],
            'checkbox' => ['checkbox'],
            'collapsible' => ['collapsible', 'collapsible-content', 'collapsible-trigger'],
            'combobox' => ['combobox', 'combobox-chip', 'combobox-chips', 'combobox-chips-input', 'combobox-clear', 'combobox-collection', 'combobox-content', 'combobox-empty', 'combobox-group', 'combobox-input', 'combobox-item', 'combobox-label', 'combobox-list', 'combobox-separator', 'combobox-trigger', 'combobox-value'],
            'command' => ['command', 'command-dialog', 'command-empty', 'command-group', 'command-input', 'command-item', 'command-list', 'command-separator', 'command-shortcut'],
            'context-menu' => ['context-menu', 'context-menu-checkbox-item', 'context-menu-content', 'context-menu-group', 'context-menu-item', 'context-menu-label', 'context-menu-portal', 'context-menu-radio-group', 'context-menu-radio-item', 'context-menu-separator', 'context-menu-shortcut', 'context-menu-sub', 'context-menu-sub-content', 'context-menu-sub-trigger', 'context-menu-trigger'],
            'dialog' => ['dialog', 'dialog-close', 'dialog-content', 'dialog-description', 'dialog-footer', 'dialog-header', 'dialog-overlay', 'dialog-portal', 'dialog-title', 'dialog-trigger'],
            'direction-provider' => ['direction-provider'],
            'drawer' => ['drawer', 'drawer-close', 'drawer-content', 'drawer-description', 'drawer-footer', 'drawer-header', 'drawer-overlay', 'drawer-portal', 'drawer-title', 'drawer-trigger'],
            'dropdown-menu' => ['dropdown-menu', 'dropdown-menu-checkbox-item', 'dropdown-menu-content', 'dropdown-menu-group', 'dropdown-menu-item', 'dropdown-menu-label', 'dropdown-menu-portal', 'dropdown-menu-radio-group', 'dropdown-menu-radio-item', 'dropdown-menu-separator', 'dropdown-menu-shortcut', 'dropdown-menu-sub', 'dropdown-menu-sub-content', 'dropdown-menu-sub-trigger', 'dropdown-menu-trigger'],
            'empty' => ['empty', 'empty-content', 'empty-description', 'empty-header', 'empty-media', 'empty-title'],
            'field' => ['field', 'field-content', 'field-description', 'field-error', 'field-group', 'field-label', 'field-legend', 'field-separator', 'field-set', 'field-title'],
            'hover-card' => ['hover-card', 'hover-card-content', 'hover-card-trigger'],
            'input' => ['input'],
            'input-group' => ['input-group', 'input-group-addon', 'input-group-button', 'input-group-input', 'input-group-text', 'input-group-textarea'],
            'input-otp' => ['input-otp', 'input-otp-group', 'input-otp-separator', 'input-otp-slot'],
            'item' => ['item', 'item-actions', 'item-content', 'item-description', 'item-footer', 'item-group', 'item-header', 'item-media', 'item-separator', 'item-title'],
            'kbd' => ['kbd', 'kbd-group'],
            'label' => ['label'],
            'marker' => ['marker', 'marker-content', 'marker-icon'],
            'menubar' => ['menubar', 'menubar-checkbox-item', 'menubar-content', 'menubar-group', 'menubar-item', 'menubar-label', 'menubar-menu', 'menubar-portal', 'menubar-radio-group', 'menubar-radio-item', 'menubar-separator', 'menubar-shortcut', 'menubar-sub', 'menubar-sub-content', 'menubar-sub-trigger', 'menubar-trigger'],
            'message' => ['message', 'message-avatar', 'message-content', 'message-footer', 'message-group', 'message-header'],
            'message-scroller' => ['message-scroller', 'message-scroller-button', 'message-scroller-content', 'message-scroller-item', 'message-scroller-provider', 'message-scroller-viewport'],
            'native-select' => ['native-select', 'native-select-optgroup', 'native-select-option'],
            'navigation-menu' => ['navigation-menu', 'navigation-menu-content', 'navigation-menu-indicator', 'navigation-menu-item', 'navigation-menu-link', 'navigation-menu-list', 'navigation-menu-trigger', 'navigation-menu-viewport'],
            'pagination' => ['pagination', 'pagination-content', 'pagination-ellipsis', 'pagination-item', 'pagination-link', 'pagination-next', 'pagination-previous'],
            'popover' => ['popover', 'popover-anchor', 'popover-content', 'popover-description', 'popover-header', 'popover-title', 'popover-trigger'],
            'progress' => ['progress'],
            'radio-group' => ['radio-group', 'radio-group-item'],
            'resizable' => ['resizable-handle', 'resizable-panel', 'resizable-panel-group'],
            'scroll-area' => ['scroll-area', 'scroll-bar'],
            'select' => ['select', 'select-content', 'select-group', 'select-item', 'select-label', 'select-scroll-down-button', 'select-scroll-up-button', 'select-separator', 'select-trigger', 'select-value'],
            'separator' => ['separator'],
            'sheet' => ['sheet', 'sheet-close', 'sheet-content', 'sheet-description', 'sheet-footer', 'sheet-header', 'sheet-overlay', 'sheet-portal', 'sheet-title', 'sheet-trigger'],
            'sidebar' => ['sidebar', 'sidebar-content', 'sidebar-footer', 'sidebar-group', 'sidebar-group-action', 'sidebar-group-content', 'sidebar-group-label', 'sidebar-header', 'sidebar-input', 'sidebar-inset', 'sidebar-menu', 'sidebar-menu-action', 'sidebar-menu-badge', 'sidebar-menu-button', 'sidebar-menu-item', 'sidebar-menu-skeleton', 'sidebar-menu-sub', 'sidebar-menu-sub-button', 'sidebar-menu-sub-item', 'sidebar-provider', 'sidebar-rail', 'sidebar-separator', 'sidebar-trigger'],
            'skeleton' => ['skeleton'],
            'slider' => ['slider'],
            'sonner' => ['sonner'],
            'spinner' => ['spinner'],
            'switch' => ['switch'],
            'table' => ['table', 'table-body', 'table-caption', 'table-cell', 'table-footer', 'table-head', 'table-header', 'table-row'],
            'tabs' => ['tabs', 'tabs-content', 'tabs-list', 'tabs-trigger'],
            'textarea' => ['textarea'],
            'toggle' => ['toggle'],
            'toggle-group' => ['toggle-group', 'toggle-group-item'],
            'tooltip' => ['tooltip', 'tooltip-content', 'tooltip-provider', 'tooltip-trigger'],
        ],
        'block' => [

        ],
    ];

    public function __construct(private readonly Filesystem $filesystem)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $componentNames = $this->resolveComponentNames();

        $components = $this->availableComponents()
            ->only($componentNames)
            ->flatMap(fn (array $component) => $component['dependencies'])
            ->unique()
            ->all();

        if ($components === []) {
            return self::SUCCESS;
        }

        foreach ($components as $component) {
            $this->publishComponent($component);
        }

        return self::SUCCESS;
    }

    /**
     * Resolve the component names to publish based on the given input.
     *
     * @return array<int, string>
     */
    private function resolveComponentNames(): array
    {
        if ($this->option('all')) {
            return $this->availableComponents()->keys()->all();
        }

        if (count($this->argument('components')) > 0) {
            $requested = array_map(strtolower(...), $this->argument('components'));

            return $this->availableComponents()
                ->keys()
                ->filter(fn (string $component) => in_array(strtolower($component), $requested, true))
                ->values()
                ->all();
        }

        if ($this->option('multiple')) {
            return array_values(array_map(strval(...), multisearch(
                label: 'Which component would you like to publish?',
                options: fn (string $value) => $this->searchOptions($value),
            )));
        }

        return array_map(strval(...), (array) search(
            label: 'Which component would you like to publish?',
            options: fn (string $value) => $this->searchOptions($value),
        ));
    }

    private function publishComponent(string $component): void
    {
        $group = $this->componentFileGroups()->get($component);

        if ($group === null) {
            return;
        }

        $this->filesystem->ensureDirectoryExists(resource_path("views/components/{$group}"));

        $sourceAsDirectory = __DIR__."/../../../stubs/views/components/{$group}/{$component}";
        $sourceAsFile = __DIR__."/../../../stubs/views/components/{$group}/{$component}.blade.php";

        $destinationAsDirectory = resource_path("views/components/{$group}/{$component}");
        $destinationAsFile = resource_path("views/components/{$group}/{$component}.blade.php");

        $destination = $this->filesystem->isDirectory($sourceAsDirectory)
            ? $this->publishDirectory($component, $sourceAsDirectory, $destinationAsDirectory)
            : null;

        if ($destination) {
            $this->components->info("Published: {$destination}");
        }

        $destination = $this->filesystem->isFile($sourceAsFile)
            ? $this->publishFile($component, $sourceAsFile, $destinationAsFile)
            : null;

        if ($destination) {
            $this->components->info("Published: {$destination}");
        }
    }

    protected function publishDirectory(string $component, string $source, string $destination): ?string
    {
        if ($this->filesystem->isDirectory($destination) && ! $this->option('force')) {
            $this->components->warn("Skipping [{$component}]. Directory already exists: {$destination}");

            return null;
        }

        $this->filesystem->copyDirectory($source, $destination);

        return $destination;
    }

    protected function publishFile(string $component, string $source, string $destination): ?string
    {
        if ($this->filesystem->exists($destination) && ! $this->option('force')) {
            $this->components->warn("Skipping [{$component}]. File already exists: {$destination}");

            return null;
        }

        $this->filesystem->copy($source, $destination);

        return $destination;
    }

    /**
     * @return array<int, string>
     */
    protected function searchOptions(string $value): array
    {
        if ($value === '') {
            return $this->availableComponents()->keys()->toArray();
        }

        return $this->availableComponents()
            ->keys()
            ->filter(fn (string $component) => str_starts_with(strtolower($component), strtolower($value)))
            ->values()
            ->all();
    }

    /**
     * The known components across all groups, keyed by component name.
     *
     * @return Collection<string, array{group: string, dependencies: array<int, string>}>
     */
    protected function availableComponents(): Collection
    {
        return collect($this->componentGroups)
            ->flatMap(fn (array $components, string $group) => collect($components)
                ->map(fn (array $dependencies) => [
                    'group' => $group,
                    'dependencies' => $dependencies,
                ]))
            ->sortKeys();
    }

    /**
     * A reverse index mapping every publishable file, including a component's
     * dependencies, to the group it is published from.
     *
     * @return Collection<string, string>
     */
    protected function componentFileGroups(): Collection
    {
        return collect($this->componentGroups)
            ->flatMap(fn (array $components, string $group) => collect($components)
                ->flatten()
                ->mapWithKeys(fn (string $file) => [$file => $group]));
    }
}
