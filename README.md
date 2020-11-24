wmpage_cache_flysystem
======================

[![Latest Stable Version](https://poser.pugx.org/wieni/wmpage_cache_flysystem/v/stable)](https://packagist.org/packages/wieni/wmpage_cache_flysystem)
[![Total Downloads](https://poser.pugx.org/wieni/wmpage_cache_flysystem/downloads)](https://packagist.org/packages/wieni/wmpage_cache_flysystem)
[![License](https://poser.pugx.org/wieni/wmpage_cache_flysystem/license)](https://packagist.org/packages/wieni/wmpage_cache_flysystem)

> A [Flysystem](https://flysystem.thephpleague.com) cache storage for [wieni/wmpage_cache](https://github.com/wieni/wmpage_cache)

## Installation

This package requires PHP 7.1 and Drupal 8 or higher. It can be
installed using Composer:

```bash
 composer require wieni/wmpage_cache_flysystem
```

To enable this cache storage, change the following container parameters:
```yaml
parameters:
    wmpage_cache.storage: wmpage_cache.cache.storage.flysystem

    # Backend storage responsible for keeping track of tags and cache entries
    wmpage_cache_flysystem.backend_storage: wmpage_cache.cache.storage.mysql

    wmpage_cache_flysystem.scheme: wmpage_cachescheme
    wmpage_cache_flysystem.directory: wmpage_cache
```

Make sure to also set the flysystem scheme in `settings.php`.

```php
// settings.php

$settings['flysystem'] = [
    'wmpage_cachescheme' => [
        'driver' => 'local',
        'config' => [
            'root' => 'sites/default/cache',
            'public' => false,
        ],
        'serve_js' => true,
        'serve_css' => true,
    ],
];

// Or if you want to store your cache on S3
// This requires the drupal/flysystem_s3 module
$settings['flysystem'] = [
    'wmpage_cachescheme' => [
        'driver' => 's3',
        'config' => [
            'key' => $_ENV['S3_KEY'],
            'secret' => $_ENV['S3_SECRET'],
            'region' => $_ENV['S3_REGION'],
            'bucket' => $_ENV['S3_BUCKET'],
            'prefix' => $_ENV['S3_PREFIX'] ?? '',
            'cname' => $_ENV['S3_CNAME'] ?? '',
            'options' => [
                'ACL' => 'private',
            ],
            'protocol' => 'https',
            'public' => false,
        ],
        'cache' => false,
        'serve_js' => false,
        'serve_css' => false,
    ],
];
```

## Changelog
All notable changes to this project will be documented in the
[CHANGELOG](CHANGELOG.md) file.

## Security
If you discover any security-related issues, please email
[security@wieni.be](mailto:security@wieni.be) instead of using the issue
tracker.

## License
Distributed under the MIT License. See the [LICENSE](LICENSE) file
for more information.
