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

## Usage

Install the Tailwind CSS entry point and choose a base color:

```bash
php artisan ui:install
```

Compile that CSS with your application's Vite build, then load Knppy UI's bundled Alpine runtime before the closing
`body` tag:

```blade
@uiScripts
</body>
```

The directive includes and starts Alpine together with Knppy UI's required plugins. Do not load a second Alpine
instance. Pass a CSP nonce when required with `@uiScripts(['nonce' => $nonce])`.

Components are available as anonymous Blade components under the `ui` namespace.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to Ui! Please review our [contributing guide](.github/CONTRIBUTING.md) to get
started.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Michael Beers](https://github.com/knppy)
- [All Contributors](../../contributors)

## License

Ui is open-sourced software licensed under the [MIT license](LICENSE.md).
