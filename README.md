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

### Publishing the Public Assets

```bash
php artisan vendor:publish --tag="ui-assets"
```

## Usage

<!-- Add a basic usage example here. -->

### Adding Components

Run `php artisan ui:install` once to create `components.json`, then add components from a registry:

```bash
php artisan ui:add button
php artisan ui:add @knppy/button @acme/card --overwrite
```

Files are written to the `aliases` paths in `components.json` (`aliases.ui` for `registry:ui` files, `aliases.components` otherwise). Existing files are skipped unless `--overwrite` is given. Registry dependencies are installed too; missing Composer and npm packages are installed automatically (`composer require` / `npm install` in the project root; npm only when a `package.json` exists). Pass `--skip-packages` to print the commands instead.

A bare name uses `default_registry`; `@namespace/name` picks a registry. Registries are configured in `config/ui.php` and can be extended per project in `components.json`:

```json
{
  "registries": {
    "@acme": "https://acme.test/r/{name}.json"
  }
}
```

A registry value is a URL template containing `{name}` or a local directory of `{name}.json` files. Each item is JSON with inline file `content` (shadcn style). If the remote `@knppy` registry is unavailable, the registry bundled with this package is used instead silently (pass `-v` to `ui:add` to see a notice; disable with `UI_REGISTRY_FALLBACK=false`); other registries never fall back.

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
