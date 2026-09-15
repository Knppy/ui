---
name: ui-development
description: >
  Configure and apply the Ui package in Laravel applications.
license: MIT
metadata:
  author: Michael Beers
---

# Ui

Use this skill when a Laravel application needs to integrate the Ui package.

## Primary Goal

- apply the `knppy/ui` package's public API in the smallest correct way

## Workflow

### 1. Inspect the Laravel app context

- confirm the app is a Laravel project
- inspect the target code paths where the package should be applied

### 2. Apply the package's public API

- use the anonymous Blade components under the `ui` namespace for alerts, attachments, badges, breadcrumbs, bubbles, buttons, cards, direction providers, empty states, fields, form controls, items, labels, markers, messages, pagination, progress, separators, skeletons, spinners, and tables
- use `<x-ui.breadcrumbs>` as the root and nest list, item, link, separator, page, and ellipsis components as needed
- set `is` on `<x-ui.breadcrumb-link>` when the link must render as an element other than `<a>`
- compose component families with their matching flat names, such as `<x-ui.card-header>`, `<x-ui.item-content>`, and `<x-ui.pagination-link>`
- pass documented variants directly as Blade attributes, such as `variant="outline"`, `size="sm"`, `orientation="vertical"`, or `align="end"`
- run `php artisan ui:install` to install the Tailwind CSS entry point and select the application's base color
- place `@uiScripts` before the closing `body` tag to load Knppy UI's bundled Alpine runtime and plugins before using interactive components
- control interactive component values from a parent Alpine scope with `x-model`; tabs, accordion, calendar, combobox, context menu and dropdown menu radio groups, input OTP, radio group, select, slider, and toggle group expose `value`, while checkbox, context menu, dialog, drawer, dropdown menu, context menu and dropdown menu checkbox items, popover, sheet, and switch expose their open or checked state
- use `<x-ui.calendar>` for a single date, set `mode="multiple"` for an array, or set `mode="range"` for a `from` and `to` object; `name`, `min`, and `max` provide native form values and date bounds
- set calendar `captionLayout` to `dropdown`, `dropdown-months`, or `dropdown-years` for caption selectors; use `startMonth` and `endMonth` to constrain their navigation range
- set `name` on `<x-ui.select>` when its current value must be included in native form submission
- compose a searchable combobox from `<x-ui.combobox-input>`, content, list, and item components; add `multiple` with chips for multi-select behavior and set `textValue` on rich items to provide searchable text
- compose a carousel from content, item, previous, and next components; set `orientation="vertical"` when horizontal scrolling is not appropriate
- compose a command list from input, list, empty, group, item, separator, and shortcut components; use `<x-ui.command-dialog>` when it belongs in a modal palette
- compose a dropdown menu from trigger and content components; content supports items, checkbox and radio items, groups, labels, separators, shortcuts, and nested submenus
- compose a menubar from menu, trigger, and content components; it coordinates open state and keyboard navigation across top-level menus and supports the same item types as dropdown menus
- compose a context menu from trigger and content components; it supports the same item types and opens from right click, the Context Menu key, or `Shift+F10`
- compose a navigation menu from list, item, trigger, content, and link components; set `:viewport="false"` when each item's content should render directly below its trigger
- compose a drawer from trigger, content, header, title, description, footer, and close components; set its direction to `top`, `bottom`, `left`, or `right`, and use `:dismissible="false"` when only controlled state should close it
- compose responsive application navigation inside `<x-ui.sidebar-provider>` with sidebar, content, group, menu, trigger, and inset components; set `collapsible="icon"` for compact desktop navigation or `collapsible="none"` for a static sidebar
- render `<x-ui.sonner />` once in the application layout and call `$toast()`, `$toast.success()`, `$toast.info()`, `$toast.warning()`, `$toast.error()`, or `$toast.loading()` from Alpine expressions

## Rules, References, and Templates

Read before executing:

- no additional resource files for this skill

## Examples

```blade
<x-ui.card>
    <x-ui.card-header>
        <x-ui.card-title>Profile</x-ui.card-title>
        <x-ui.card-description>Update your contact details.</x-ui.card-description>
    </x-ui.card-header>
    <x-ui.card-content class="space-y-4">
        <x-ui.input name="email" type="email" />
        <x-ui.native-select name="timezone">
            <x-ui.native-select-option value="utc">UTC</x-ui.native-select-option>
        </x-ui.native-select>
    </x-ui.card-content>
    <x-ui.card-footer>
        <x-ui.button type="submit">Save</x-ui.button>
    </x-ui.card-footer>
</x-ui.card>
```

## Anti-patterns

- do not document package internals here; keep the skill focused on adoption in Laravel apps
- do not add separators manually when the default chevron is sufficient
- do not use interactive components without `@uiScripts`, and do not load a second Alpine instance
- do not replace checkbox, radio group, or switch internals with click-only elements; the package components retain native form inputs
