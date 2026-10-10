# Release Notes

## [Unreleased](https://github.com/knppy/ui/commits/master)

- Add a component registry: components are copied into your project from `registry/` (shadcn style) instead of being served from the package.
- Add `ui:install` to create `components.json` and the Tailwind v4 `resources/css/app.css` theme.
- Add `ui:add` to install components (`@namespace/name`, `--overwrite`, `--skip-packages`) along with their registry, Composer and npm dependencies.
- Add `ui:add --all` and an interactive component search when no names are given.
- Add `ui:list` to list the items a registry offers (reads `registry.json`).
- Add `registry:js` files, the `js` alias and per-component Alpine modules that register themselves through the `alpine` loader.
- Bundle the registry (`registry/r`) as an offline fallback for `@knppy`; disable with `UI_REGISTRY_FALLBACK=false`.
- Add `composer registry:build` to regenerate `registry/r` from `registry/`.
- Remove the `@uiScripts` directive, the `/ui/ui.js` route and the bundled `dist/ui.js`; import the generated `resources/js/ui.js` loader instead.
- Remove the `ui-lang` publish tag.
- Only publish the config file: the `ui` and `ui-config` tags remain.
