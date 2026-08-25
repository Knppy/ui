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

Shadcn for Laravel Blade

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

### Publishing the Translations

```bash
php artisan vendor:publish --tag="ui-lang"
```

## Usage

<!-- Add a basic usage example here. -->

## Supported component(s)

| Component name | Category     | Status |
|----------------|--------------|----|
| accordion      | disclosure   | ✅  |
| alert          | feedback     | ✅  |
| alert-dialog   | overlay      | ✅  |
| aspect-ratio   | layout       | ✅  |
| avatar         | ui           | ✅  |
| badge          | ui           | ✅  |
| breadcrumb     | navigation   | ✅  |
| bubble         | data-display | ✅  |
| button         | form         | ✅  |
| button-group   | form         | ✅  |
| calendar       | form         | ✅  |
| card           | layout       | ✅  |
| carousel       | data-display | ✅  |
| checkbox       | form         | ✅  |
| collapsible    | disclosure   | ✅  |
| combobox       | form         | ✅  |
| command        | overlay      | ✅  |
| context-menu   | overlay      | ✅  |
| dialog         | overlay      | ✅  |
| dropdown-menu  | overlay      | ✅  |
| empty          | feedback     | ✅  |
| field          | form         | ✅  |
| input          | form         | ✅  |
| input-group    | form         | ✅  |
| input-otp      | form         | ✅  |
| kbd            | data-display | ✅  |
| label          | form         | ✅  |
| progress       | ui           | ✅  |
| radio-group    | form         | ✅  |
| select         | form         | ✅  |
| separator      | layout       | ✅  |
| sheet          | overlay      | ✅  |
| sidebar        | layout       | ✅  |
| skeleton       | ui           | ✅  |
| slider         | form         | ✅  |
| spinner        | feedback     | ✅  |
| switch         | form         | ✅  |
| textarea       | form         | ✅  |
| toggle         | form         | ✅  |
| toggle-group   | form         | ✅  |


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
