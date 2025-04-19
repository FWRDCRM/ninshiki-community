# Ninshiki Web App; Inspiring Recognition: Celebrate Success

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ninshiki-project/ninshiki.svg?style=flat-square)](https://packagist.org/packages/ninshiki-project/ninshiki)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ninshiki-project/ninshiki/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ninshiki-project/ninshiki/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ninshiki-project/ninshiki.svg?style=flat-square)](https://packagist.org/packages/ninshiki-project/ninshiki)
[![](https://img.shields.io/badge/Laravel-v11.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](#)
[![](https://img.shields.io/badge/Vue.js-v3.x-4FC08D?style=flat-square&logo=vue.js&logoColor=white)](#)
[![](https://img.shields.io/badge/Tailwind_CSS-v4.x-06B6D4?style=flat-square&logo=tailwind-css&logoColor=white)](#)
[![](https://img.shields.io/badge/PrimeVue-v4.x-41B883?style=flat-square&logo=primevue&logoColor=white)](#)
[![](https://img.shields.io/badge/Inertia-v2.x-9553E9?style=flat-square&logo=inertia&logoColor=white)](#)

Ninshiki Web App; Inspiring Recognition: Celebrate Success

---

## Installation

You can install the package via composer:

```bash
composer require ninshiki-project/ninshiki
```

Install the ninshiki

```bash
php artisan ninshiki:install
```

You can publish the config and assets file with:

```bash
php artisan ninshiki:publish
```

Keeping Ninshiki’s Assets Updated

###### _To ensure Ninshiki’s assets are updated when a new version is downloaded, you may add a Composer hook inside your project’s composer.json file to automatically publish Ninshiki’s latest assets:_

```diff
"scripts": {
    "post-update-cmd": [
        "@php artisan vendor:publish --tag=laravel-assets --ansi --force",
+        "@php artisan ninshiki:publish --ansi"
    ]
}
```

# Known Issue 

---

#### Increasing the NGINX buffer size for Inertia requests
Because of a [known issue](https://github.com/inertiajs/inertia-laravel/issues/529) with Inertia.js and default NGINX configuration, you may need to increase the buffer size for NGINX to handle Inertia requests.
```diff
server {
    listen 80;
    listen [::]:80;
    server_name example.com;
    root /srv/example.com/public;
 
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
 
    index index.php;
 
    charset utf-8;
 
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
 
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
 
    error_page 404 /index.php;
 
    location ~ ^/index\.php(/|$) {
+       fastcgi_buffer_size 8k;    
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }
 
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```


## Testing

```bash
composer test
```

## Development

Always build the assets by running this command

```bash
 npm run build
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [MarJose123](https://github.com/ninshiki-project)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
