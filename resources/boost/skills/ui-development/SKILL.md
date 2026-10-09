---
name: ui-development
description: >
    Build and update Laravel Blade interfaces with knppy/ui components. Use when creating forms, dialogs, menus,
    navigation, tables, notifications, or other UI; replacing hand-written controls with Knppy UI; or installing,
    listing, and customizing components from the Knppy UI registry.
license: MIT
metadata:
    author: Michael Beers
---

# Knppy UI Development

## Primary Goal

Build accessible Laravel interfaces from `knppy/ui` components in the smallest correct composition. Components are
copied into the application (shadcn style), so install what is needed, then compose it instead of recreating
behavior or styling with custom Blade or Alpine code.

## Workflow

1. Inspect the target Blade view, its layout, nearby component conventions, and existing Alpine state before editing.
2. Confirm `knppy/ui` is installed. For initial setup run `composer require knppy/ui`, then `php artisan ui:install`.
   It creates `components.json` (it fails when one exists unless `--force` is given) and overwrites
   `resources/css/app.css` with the Tailwind v4 theme. Back up existing CSS first. The only base color is `neutral`
   (`--baseColor=neutral`).
3. Check which components exist: look in `resources/views/components/ui` (the `aliases.ui` path in `components.json`).
   Run `php artisan ui:list` to see what the registry offers.
4. Install missing components with `php artisan ui:add button dialog`. Registry dependencies are installed too, and
   missing Composer and npm packages are installed automatically (`--skip-packages` prints the commands instead).
   Existing files are skipped unless `--overwrite` is given, so locally edited components are safe. Use
   `@namespace/name` to pick a non-default registry configured in `config/ui.php` or `components.json`.
5. Interactive components depend on the `alpine` item, which installs `resources/js/ui.js` and `alpinejs`. Confirm
   `import './ui.js';` is in `resources/js/app.js` and that Vite compiles both `app.css` and `app.js`. The loader
   registers every module in `resources/js/ui/` and starts Alpine unless Livewire is present, so never start another
   Alpine instance.
6. Compose each family from its flat component names, for example `<x-ui.card>`, `<x-ui.card-header>`, and
   `<x-ui.card-title>`, not dotted names or hand-written replicas. Inspect the installed component source before
   using unfamiliar props; do not invent component names, slots, variants, or events.
7. Preserve semantic HTML, labels, names, disabled state, validation attributes, keyboard behavior, and focus
   management. Add utility classes through `class`; they are merged with the component defaults.
8. Verify at mobile and desktop widths. Exercise keyboard navigation, focus, dismissal, bound state, and native form
   submission for interactive controls.

## Component Catalog

Static and layout families:

- `alert`, `aspect-ratio`, `attachment`, `badge`, `breadcrumb` (`breadcrumbs`, `breadcrumb-item`, ...), `bubble`,
  `button`, `button-group`, `card`, `direction-provider`, `empty`, `field`, `input`, `item`, `kbd`, `label`, `marker`,
  `message`, `native-select`, `pagination`, `progress`, `separator`, `skeleton`, `spinner`, `table`, `textarea`

Interactive families (need the Alpine loader):

- `accordion`, `alert-dialog`, `avatar`, `calendar`, `carousel`, `checkbox`, `collapsible`, `combobox`, `command`
- `context-menu`, `dialog`, `drawer`, `dropdown-menu`, `hover-card`, `input-group`, `input-otp`, `menubar`
- `message-scroller`, `navigation-menu`, `popover`, `radio-group`, `resizable`, `scroll-area`, `select`, `sheet`
- `sidebar`, `slider`, `sonner`, `switch`, `tabs`, `toggle`, `toggle-group`, `tooltip`

Families use flat kebab-case children such as `alert-description`, `dialog-trigger`, `dialog-content`, `select-item`,
`sidebar-menu-button`, and `table-row`. The installed component source is authoritative for children and props.

## Composition Rules

- Put triggers and content inside their owning interactive root, such as `dialog`, `popover`, `select`, or
  `dropdown-menu`, using the family's trigger, overlay, close, header, title, description, and footer components.
- Use Blade-bound attributes for PHP values: `:checked="$enabled"`, `:value="$selected"`, `:disabled="$locked"`.
  Use literal attributes for literal values.
- Use `x-model` (or `wire:model` with Livewire) on the family root when application state must control a component.
  Components with `x-modelable` state include `accordion`, `calendar`, `checkbox`, `combobox`, `dialog`, `drawer`,
  `input-otp`, `popover`, `radio-group`, `select`, `sheet`, `slider`, `switch`, `tabs`, `toggle`, and `toggle-group`.
- Set `name` on form controls that must submit. Custom `select` emits a hidden input; checkbox and switch retain
  native checkbox inputs. Do not add duplicate hidden inputs unless unchecked-value handling requires it.
- Associate controls and validation text with labels and IDs. Pass `aria-invalid="true"` when a control has an error
  and render the message with `field-error`.
- Use `button` variants and sizes that exist in the installed `button.blade.php`; set `type="button"` for non-submit
  actions.
- Render `<x-ui.sonner />` once in the layout. From Alpine expressions, notify with `$toast()`, `$toast.success()`,
  `$toast.info()`, `$toast.warning()`, `$toast.error()`, or `$toast.loading()`. The loader adds `x-data` to `<body>`
  so these work anywhere on the page.
- Prefer component props and semantic structure over styling internal `data-slot` elements. Use Tailwind classes for
  page-specific layout, not to rebuild supplied behavior.
- In PHP, `Ui::classes('base')->add([...])` builds class strings and `$attributes->twMerge('base')` merges classes
  with Tailwind-aware conflict resolution.

## Examples

### Adding and using a dialog

```bash
php artisan ui:add dialog button
```

```blade
<div x-data="{ editing: false }">
    <x-ui.dialog x-model="editing">
        <x-ui.dialog-trigger>
            <x-ui.button type="button" variant="outline">Edit profile</x-ui.button>
        </x-ui.dialog-trigger>
        <x-ui.dialog-content>
            <x-ui.dialog-header>
                <x-ui.dialog-title>Edit profile</x-ui.dialog-title>
                <x-ui.dialog-description>Update your public details.</x-ui.dialog-description>
            </x-ui.dialog-header>
            <x-ui.dialog-footer>
                <x-ui.dialog-close>
                    <x-ui.button type="button" variant="outline">Cancel</x-ui.button>
                </x-ui.dialog-close>
                <x-ui.button type="submit" form="profile-form">Save</x-ui.button>
            </x-ui.dialog-footer>
        </x-ui.dialog-content>
    </x-ui.dialog>
</div>
```

### Validated form

```blade
<form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
    @csrf
    @method('PUT')

    <x-ui.field>
        <x-ui.field-label for="email">Email</x-ui.field-label>
        <x-ui.input
            id="email"
            name="email"
            type="email"
            value="{{ old('email', $user->email) }}"
            :aria-invalid="$errors->has('email')"
        />
        @error('email')
            <x-ui.field-error>{{ $message }}</x-ui.field-error>
        @enderror
    </x-ui.field>

    <x-ui.button type="submit">Save profile</x-ui.button>
</form>
```

### Form select

```blade
<x-ui.select name="timezone" :value="old('timezone', $user->timezone)">
    <x-ui.select-trigger>
        <x-ui.select-value placeholder="Select a timezone" />
    </x-ui.select-trigger>
    <x-ui.select-content>
        <x-ui.select-item value="utc">UTC</x-ui.select-item>
        <x-ui.select-item value="cet">CET</x-ui.select-item>
    </x-ui.select-content>
</x-ui.select>
```

## Anti-patterns

- Do not use a component name merely because it exists in shadcn/ui; only registry families exist. Run `ui:list`.
- Do not guess React-style APIs such as `asChild`. Components expose Blade props and attributes.
- Do not use `ui:add --overwrite` on components the application has customized unless asked.
- Do not re-run `ui:install --force` on a configured project; it replaces `components.json` and `app.css`.
- Do not use interactive components without importing the Alpine loader, and do not load a second Alpine instance.
- Do not replace checkbox, radio-group, select, slider, or switch form behavior with click-only elements.
- Do not omit accessible titles or descriptions from dialogs, sheets, drawers, and alert dialogs.
- Do not hand-copy component markup from the package; install it with `ui:add`.
- Publish package resources (`ui`, `ui-config`) only when the config needs customizing.
