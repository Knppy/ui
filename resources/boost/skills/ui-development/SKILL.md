---
name: ui-development
description: >
    Build and update Laravel Blade interfaces with knppy/ui components. Use when creating forms, dialogs, menus,
    navigation, tables, notifications, or other UI; replacing hand-written controls with Knppy UI; or working with
    the package's Blade components, Alpine state, Tailwind theme, installation, and layout setup.
license: MIT
metadata:
    author: Michael Beers
---

# Knppy UI Development

## Primary Goal

Build accessible Laravel interfaces from `knppy/ui` components in the smallest correct composition. Reuse the
package's behavior and styling instead of recreating either with custom Blade markup or Alpine code.

## Workflow

1. Inspect the target Blade view, its layout, nearby component conventions, and existing Alpine state before editing.
2. Confirm `knppy/ui` is installed. For initial setup, run `composer require knppy/ui`, then `php artisan ui:install`.
   The install command creates or replaces `resources/css/app.css`; do not pass `--force` when existing CSS must be
   preserved. The currently supported base color is `neutral`.
3. Confirm the app compiles `resources/css/app.css` with Vite and renders `@uiScripts` once before `</body>` whenever
   interactive components are used. `@uiScripts` provides and starts Alpine with the required plugins, so do not
   install or start another Alpine instance. Use `@uiScripts(['nonce' => $nonce])` when CSP requires a nonce.
4. Select a component family from the catalog below. Inspect existing usages or the component source before using
   unfamiliar props; do not invent component names, slots, variants, or events.
5. Compose each family from its matching flat component names. For example, use `<x-ui.card>`,
   `<x-ui.card-header>`, and `<x-ui.card-title>`, not dotted names or hand-written replicas.
6. Preserve semantic HTML, labels, names, disabled state, validation attributes, keyboard behavior, and focus
   management. Add utility classes through `class`; the package merges them with component defaults.
7. Verify the result at mobile and desktop widths. Exercise keyboard navigation, focus, dismissal, bound state, and
   native form submission for interactive controls.

## Component Catalog

Static and layout families:

- `alert`, `aspect-ratio`, `attachment`, `badge`, `breadcrumbs`, `bubble`, `button`, `button-group`, `card`
- `direction-provider`, `empty`, `field`, `input`, `item`, `kbd`, `label`, `marker`, `message`, `native-select`
- `pagination`, `progress`, `separator`, `skeleton`, `spinner`, `table`, `textarea`

Interactive families requiring `@uiScripts`:

- `accordion`, `alert-dialog`, `avatar`, `calendar`, `carousel`, `checkbox`, `collapsible`, `combobox`, `command`
- `context-menu`, `dialog`, `drawer`, `dropdown-menu`, `hover-card`, `input-group`, `input-otp`, `menubar`
- `message-scroller`, `navigation-menu`, `popover`, `radio-group`, `resizable`, `scroll-area`, `select`, `sheet`
- `sidebar`, `slider`, `sonner`, `switch`, `tabs`, `toggle`, `toggle-group`, `tooltip`

Component families use flat kebab-case children such as `alert-description`, `dialog-trigger`, `dialog-content`,
`select-item`, `sidebar-menu-button`, and `table-row`. Treat the shipped component source and established app usages
as authoritative when choosing children and props.

## Composition Rules

- Put triggers and content inside their owning interactive root, such as `dialog`, `popover`, `select`, or
  `dropdown-menu`. Use the family's provided trigger, portal, overlay, close, header, title, description, and footer
  components where available.
- Use Blade-bound attributes for PHP values: `:checked="$enabled"`, `:value="$selected"`, or
  `:disabled="$locked"`. Use literal attributes for literal values.
- Use `x-model` on the family root when application Alpine state must control a component. Value-oriented families
  include `accordion`, `calendar`, `combobox`, `input-otp`, `radio-group`, `select`, `slider`, `tabs`, and
  `toggle-group`. Overlay and boolean controls expose open or checked state through `x-model`.
- Set `name` on form controls that must submit. Custom `select` emits a hidden input; checkbox and switch retain
  native checkbox inputs. Do not add duplicate hidden inputs unless unchecked-value handling explicitly requires it.
- Associate controls and validation text with semantic labels and IDs. Pass `aria-invalid="true"` when a control has
  a validation error and render the message with `field-error`.
- Use `button` variants `default`, `destructive`, `outline`, `secondary`, `ghost`, or `link`. Supported sizes are
  `default`, `xs`, `sm`, `lg`, `icon`, `icon-xs`, `icon-sm`, and `icon-lg`. Set `type="button"` for non-submit actions.
- Render `<x-ui.sonner />` once in the application layout. From Alpine expressions, create notifications with
  `$toast()`, `$toast.success()`, `$toast.info()`, `$toast.warning()`, `$toast.error()`, or `$toast.loading()`.
- Prefer component props and semantic structure over styling internal `data-slot` elements. Use Tailwind classes for
  page-specific layout and spacing, not to rebuild behavior already supplied by a component.

## Examples

### Validated Form

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

### Controlled Dialog

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

### Native Form Select

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

## Anti-Patterns

- Do not use a component name merely because it exists in shadcn/ui; use only families in this package's catalog.
- Do not guess React-style APIs such as `asChild`. Knppy UI components expose Blade props and attributes.
- Do not copy package component markup into application views merely to customize spacing or color; compose the
  component and pass classes first.
- Do not use interactive components without `@uiScripts`, and do not load a second Alpine instance.
- Do not put `@uiScripts` in repeated partials, loops, or individual components.
- Do not replace checkbox, radio-group, select, slider, or switch form behavior with click-only elements.
- Do not omit accessible titles or descriptions from dialogs, sheets, drawers, and alert dialogs.
- Do not use `ui:add` as an installation workflow; it is not currently an implemented component installer.
- Do not publish package resources unless customization requires it. Available publish tags are `ui`, `ui-config`,
  and `ui-lang`.
