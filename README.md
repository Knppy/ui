<div align="center">
    <h1>Ui</h1>
</div>

<p align="center">
    <a href="https://packagist.org/packages/knppy/ui"><img src="https://img.shields.io/packagist/v/knppy/ui.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://packagist.org/packages/knppy/ui"><img src="https://img.shields.io/packagist/php-v/knppy/ui.svg?style=flat-square" alt="PHP from Packagist"></a>
    <a href="https://packagist.org/packages/knppy/ui"><img src="https://badge.laravel.cloud/badge/knppy/ui?style=flat" alt="Laravel versions"></a>
    <a href="https://github.com/knppy/ui/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/knppy/ui/tests.yml?branch=main&label=Tests&style=flat-square"></a>
    <a href="https://packagist.org/packages/knppy/ui"><img src="https://img.shields.io/packagist/dt/knppy/ui.svg?style=flat-square" alt="Total Downloads"></a>
</p>

Knppy UI is a robust Shadcn/ui port for Laravel applications.

## Installation

You can install the package via Composer:

```bash
composer require knppy/ui
```

You may publish all of the package's resources at once:

```bash
php artisan vendor:publish --tag="ui"
```

Or, you may publish each resource individually:

### Publishing the Configuration File

```bash
php artisan vendor:publish --tag="ui-config"
```

### Publishing and Running the Migrations

```bash
php artisan vendor:publish --tag="ui-migrations"
php artisan migrate
```

### Publishing the Translations

```bash
php artisan vendor:publish --tag="ui-lang"
```

## Usage

Install the Tailwind CSS entry point and choose a base color:

```bash
php artisan ui:install
```

Compile that CSS with your application's Vite build, then load Knppy UI's bundled Alpine runtime before the closing `body` tag:

```blade
@uiScripts
</body>
```

The directive includes and starts Alpine together with Knppy UI's required plugins. Do not load a second Alpine instance. Pass a CSP nonce when required with `@uiScripts(['nonce' => $nonce])`.

Components are available as anonymous Blade components under the `ui` namespace:

```blade
<x-ui.card>
    <x-ui.card-header>
        <x-ui.card-title>Account</x-ui.card-title>
        <x-ui.card-description>Manage your account details.</x-ui.card-description>
    </x-ui.card-header>
    <x-ui.card-content>
        <x-ui.input name="email" type="email" />
    </x-ui.card-content>
</x-ui.card>
```

The package includes the JavaScript-free Shadcn components: alert, aspect ratio, attachment, badge, breadcrumb, bubble, button, button group, card, direction provider, empty, field, input, item, keyboard key, label, marker, message, native select, pagination, progress, separator, skeleton, spinner, table, and textarea.

Interactive components use the Alpine.js runtime loaded by `@uiScripts`. The current set includes accordion, alert dialog, avatar, calendar, carousel, checkbox, collapsible, combobox, command, context menu, dialog, drawer, dropdown menu, hover card, input group, input OTP, menubar, message scroller, navigation menu, popover, radio group, resizable panels, scroll area, select, sheet, sidebar, slider, sonner, switch, tabs, toggle, toggle group, and tooltip. Values can be controlled from an Alpine parent with `x-model`:

```blade
<div x-data="{ tab: 'account' }">
    <x-ui.tabs x-model="tab" value="account">
        <x-ui.tabs-list>
            <x-ui.tabs-trigger value="account">Account</x-ui.tabs-trigger>
            <x-ui.tabs-trigger value="password">Password</x-ui.tabs-trigger>
        </x-ui.tabs-list>
        <x-ui.tabs-content value="account">Account settings</x-ui.tabs-content>
        <x-ui.tabs-content value="password">Password settings</x-ui.tabs-content>
    </x-ui.tabs>
</div>
```

Calendar supports single, multiple, and range selection, optional date bounds, and native form submission:

```blade
<x-ui.calendar
    name="stay"
    mode="range"
    :value="['from' => '2026-09-14', 'to' => '2026-09-18']"
    min="2026-09-01"
    max="2026-12-31"
/>
```

Set `captionLayout="dropdown"` to let users select both the displayed month and year. Use `dropdown-months` or `dropdown-years` to show only one selector, and constrain navigation with `startMonth` and `endMonth`:

```blade
<x-ui.calendar
    value="2026-09-14"
    captionLayout="dropdown"
    startMonth="2020-01"
    endMonth="2030-12"
/>
```

The custom select supports native form submission through its `name` attribute:

```blade
<x-ui.select name="timezone" value="utc">
    <x-ui.select-trigger>
        <x-ui.select-value placeholder="Select a timezone" />
    </x-ui.select-trigger>
    <x-ui.select-content>
        <x-ui.select-item value="utc">UTC</x-ui.select-item>
        <x-ui.select-item value="cet">CET</x-ui.select-item>
    </x-ui.select-content>
</x-ui.select>
```

Combobox items are filtered from their rendered labels. Set `textValue` when an item contains richer markup:

```blade
<x-ui.combobox name="framework">
    <x-ui.combobox-input placeholder="Select a framework" showClear />
    <x-ui.combobox-content>
        <x-ui.combobox-empty>No frameworks found.</x-ui.combobox-empty>
        <x-ui.combobox-list>
            <x-ui.combobox-item value="laravel">Laravel</x-ui.combobox-item>
            <x-ui.combobox-item value="livewire">Livewire</x-ui.combobox-item>
        </x-ui.combobox-list>
    </x-ui.combobox-content>
</x-ui.combobox>
```

Carousel uses native scroll snapping and supports horizontal or vertical orientation. Command provides client-side filtering, arrow-key navigation, and a dialog composition:

```blade
<x-ui.command>
    <x-ui.command-input placeholder="Search actions..." />
    <x-ui.command-list>
        <x-ui.command-empty>No actions found.</x-ui.command-empty>
        <x-ui.command-group heading="Navigation">
            <x-ui.command-item value="dashboard">Dashboard</x-ui.command-item>
            <x-ui.command-item value="settings">Settings</x-ui.command-item>
        </x-ui.command-group>
    </x-ui.command-list>
</x-ui.command>
```

Context menus open at the pointer and also support the keyboard Context Menu key or `Shift+F10`:

```blade
<x-ui.context-menu>
    <x-ui.context-menu-trigger class="rounded-md border p-8">
        Right click here
    </x-ui.context-menu-trigger>
    <x-ui.context-menu-content>
        <x-ui.context-menu-item>Back</x-ui.context-menu-item>
        <x-ui.context-menu-item>Reload</x-ui.context-menu-item>
    </x-ui.context-menu-content>
</x-ui.context-menu>
```

Navigation menus support direct links and expandable link collections. Set `:viewport="false"` to render content directly beneath each item instead of inside the shared viewport:

```blade
<x-ui.navigation-menu>
    <x-ui.navigation-menu-list>
        <x-ui.navigation-menu-item value="products">
            <x-ui.navigation-menu-trigger>Products</x-ui.navigation-menu-trigger>
            <x-ui.navigation-menu-content class="grid w-80 gap-1">
                <x-ui.navigation-menu-link href="/products/ui">UI</x-ui.navigation-menu-link>
                <x-ui.navigation-menu-link href="/products/forms">Forms</x-ui.navigation-menu-link>
            </x-ui.navigation-menu-content>
        </x-ui.navigation-menu-item>
        <x-ui.navigation-menu-item>
            <x-ui.navigation-menu-link href="/docs">Docs</x-ui.navigation-menu-link>
        </x-ui.navigation-menu-item>
    </x-ui.navigation-menu-list>
</x-ui.navigation-menu>
```

Drawers support `top`, `bottom`, `left`, and `right` directions. They close from the overlay, Escape key, close controls, or a swipe toward their nearest edge; set `:dismissible="false"` to require application-controlled dismissal:

```blade
<x-ui.drawer direction="bottom">
    <x-ui.drawer-trigger>
        <x-ui.button variant="outline">Open drawer</x-ui.button>
    </x-ui.drawer-trigger>
    <x-ui.drawer-content>
        <x-ui.drawer-header>
            <x-ui.drawer-title>Edit profile</x-ui.drawer-title>
            <x-ui.drawer-description>Update your public profile details.</x-ui.drawer-description>
        </x-ui.drawer-header>
        <x-ui.drawer-footer>
            <x-ui.drawer-close>
                <x-ui.button variant="outline">Cancel</x-ui.button>
            </x-ui.drawer-close>
        </x-ui.drawer-footer>
    </x-ui.drawer-content>
</x-ui.drawer>
```

Render `<x-ui.sonner />` once in your layout, then create notifications from any Alpine expression with `$toast()`. Typed helpers include `success`, `info`, `warning`, `error`, and `loading`:

```blade
<x-ui.button x-on:click="$toast.success('Profile saved')">Save</x-ui.button>
<x-ui.sonner position="bottom-right" closeButton />
```

Sidebar provides responsive off-canvas navigation, icon-only collapse, state persistence, and a `Ctrl/Cmd+B` shortcut:

```blade
<x-ui.sidebar-provider>
    <x-ui.sidebar collapsible="icon">
        <x-ui.sidebar-content>
            <x-ui.sidebar-menu>
                <x-ui.sidebar-menu-item>
                    <x-ui.sidebar-menu-button active tooltip="Dashboard">
                        <span>Dashboard</span>
                    </x-ui.sidebar-menu-button>
                </x-ui.sidebar-menu-item>
            </x-ui.sidebar-menu>
        </x-ui.sidebar-content>
    </x-ui.sidebar>
    <x-ui.sidebar-inset>
        <x-ui.sidebar-trigger />
    </x-ui.sidebar-inset>
</x-ui.sidebar-provider>
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to Ui! Please review our [contributing guide](.github/CONTRIBUTING.md) to get started.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Michael Beers](https://github.com/knppy)
- [All Contributors](../../contributors)

## License

Ui is open-sourced software licensed under the [MIT license](LICENSE.md).
