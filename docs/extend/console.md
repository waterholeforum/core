# Console

Waterhole allows access to the [Laravel Artisan](https://laravel.com/docs/master/artisan) command-line interface via the `waterhole` binary included in the skeleton:

```
php artisan
```

Extensions can register commands with the `Console` extender:

```php
new Extend\Console(MyCommand::class)
```

