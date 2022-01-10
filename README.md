# Yii2 extension for PHPStan

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)
[![Latest Stable Version](https://img.shields.io/packagist/v/proget-hq/phpstan-yii2.svg)](https://packagist.org/packages/proget-hq/phpstan-yii2)
[![Build Status](https://github.com/proget-hq/phpstan-yii2/workflows/build/badge.svg)](https://github.com/proget-hq/phpstan-yii2/actions?query=workflow%3Abuild)
[![Total Downloads](https://poser.pugx.org/proget-hq/phpstan-yii2/downloads.svg)](https://packagist.org/packages/proget-hq/phpstan-yii2)
[![License](https://poser.pugx.org/proget-hq/phpstan-yii2/license.svg)](https://packagist.org/packages/proget-hq/phpstan-yii2)

## What does it do?

* Provides correct return type for `Yii::$container->get('service_id')` method,
* Provides correct methods and properties for `Yii::$app->request`
* Ignore common problems with response objects (to be removed).

## Compatibility

| PHPStan version | Yii2 extension version |
|-----------------|------------------------|
| 1.x             | 0.8.x                  |
| 0.12            | 0.7.x                  |
| 0.11            | 0.5.x - 0.6.x          |
| 0.10.3          | 0.4.x                  |
| 0.10            | 0.3.0                  |
| 0.9.2           | 0.2.0                  |

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
