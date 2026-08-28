---
name: ui-development
description: Build ui's — Shadcn port for for Laravel Blade.
---

# UI Development

## When to use this skill

Use this skill when building or editing UI in a Laravel Blade app that has ui
installed (the `knppy/ui` package, with components in
`resources/views/components/ui/`). Prefer ui components over hand-written markup.

## Core model

UI is **copy-paste, own-the-code**: components are Blade files copied into
`resources/views/components/ui/` and used under the `x-ui.` namespace. They are styled
with Tailwind v4 tokens and use a little Alpine.js for interactivity. There is no runtime
component package — editing a component file is the supported way to customize it.

## Workflow

1. **Check before adding.** A component is usable only if its file exists in
   `resources/views/components/ui/`. Glob that directory first.
2. **Add missing components** (copies source + prints required composer/npm peers):
   ```shell
   php artisan ui:init                      # initialize the ui package
   php artisan ui:add <name> [<name> ...]   # add a component
   php artisan ui:list                      # all available families
   ```
3. **Use them** in Blade:
   ```blade
   <x-ui.button variant="default">Save</x-ui.button>
   <x-ui.input type="email" placeholder="you@example.com" />
   <x-ui.card>
       <x-ui.card-header>
           <x-ui.card-title>Title</x-ui.card-title>
           <x-ui.card-description>Subtitle</x-ui.card-description>
       </x-ui.card-header>
       <x-ui.card-content>...</x-ui.card-content>
   </x-ui.card>
   ```
4. **Verify wiring** once with `php artisan ui:init` (theme CSS, Alpine, imports).
