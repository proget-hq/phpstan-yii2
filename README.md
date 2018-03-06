# Yii2 extension for PHPStan

## What does it do?

* Provides correct return type for `Yii::$container->get('service_id')` method,
* Ignore common problems with active record and request/response objects.

## Installation

```sh
composer require --dev proget-hq/phpstan-yii2
```

## Configuration

Put this into your `phpstan.neon` config:

```neon
includes:
	- vendor/proget-hq/phpstan-yii2/extension.neon
parameters:
    yii2:
        config_path: %rootDir%/../../../config/test.php
```

## Limitations

Container closures must have return types.

You have to provide a path to `config/test.php` or other yii2 config file.
