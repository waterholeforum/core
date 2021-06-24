# Database

Waterhole makes heavy use of [Laravel's Database component](https://laravel.com/docs/database). You should familiarize yourself with it before proceeding, as it is assumed as prior knowledge in the following documentation.

## Migrations

Migrations are like version control for your database, allowing you to easily modify Waterhole's database schema in a safe way. Waterhole uses [Laravel's migration system](https://laravel.com/docs/migrations) – refer to the Laravel documentation for details on how to write migrations.

There is one key difference with Waterhole migrations: instead of defining the migration class in the global namespace, Waterhole migration files must *return an anonymous migration class*. This is to prevent crashes in the event that two extensions contain migrations with the same name. For example:

```php
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
    }

    public function down()
    {
    }
}
```

### Generating Migrations

You migrations should live inside a folder named `migrations` in your extension's directory. To generate a migration for your extension, run the following command:

```sh
php waterhole make:migration your_migration_name \
	--path path/to/your/extension/migrations
```

### Running Migrations

Waterhole will automatically run migrations for an extension when it is enabled. You can also manually run any outstanding migrations for enabled extensions with the `php waterhole migrate` command. When uninstalling an extension, administrators have the option to rollback the extension's migrations ("remove this extension's data").

## Models

Waterhole uses the [Eloquent ORM](https://laravel.com/docs/5.7/eloquent) to model database records – refer to the Laravel documentation for details about how Eloquent works.

Refer to the [Backend API Reference]() for information about available models and their methods.

### Adding New Models

If you've added a new database table, you'll want to set up a new model for it. Rather than extending the Eloquent `Model` class directly, you should extend `Waterhole\Database\Model` which provides a bit of extra functionality to allow your models to be extended by other extensions.

### Extending Models

If you've added columns to existing database tables, they will be accessible on their respective models without any modification. For example, you can grab any data from the `users` table via the `Waterhole\User` model.

If you need to define any attribute [accessors](https://laravel.com/docs/5.7/eloquent-mutators#defining-an-accessor), [mutators](https://laravel.com/docs/5.7/eloquent-mutators#defining-a-mutator), [casts](https://laravel.com/docs/5.7/eloquent-mutators#attribute-casting), [default values](https://laravel.com/docs/5.7/eloquent#default-attribute-values), or [relationships]() on an existing model, you can use the following extenders:

```php
// Set default attribute values
new Extend\ModelAttributes(User::class, [
    'is_alive' => true
]),

// Add an accessor
new Extend\ModelAccessor(User::class, 'first_name', function ($value) {
    return ucfirst($value);
}),

// Add a mutator
new Extend\ModelMutator(User::class, 'first_name', function ($value) {
    return strtolower($value);
}),

// Set attribute casts
new Extend\ModelCasts(User::class, [
    'dob' => 'datetime'
]),

// Add a relationship
new Extend\ModelRelationship(User::class, 'phone', function (Model $model) {
    return $model->hasOne(Phone::class);
}),
```
## Naming Conventions

Waterhole's database schema follows some naming conventions. You are encouraged to follow these within your extension too.

**Columns** should be named according to their data type:

- DATETIME or TIMESTAMP: `{verbed}_at` (eg. created_at, read_at) or `{verbed}_until` (eg. suspended_until)
- INT that is a count: `{noun}_count` (eg. comment_count, word_count)
- Foreign key: `{verbed}_{entity}_id` (eg. hidden_user_id)
  - Verb can be omitted for primary relationship (eg. post author is just `user_id`)
- BOOL: `is_{adjective}` (eg. is_locked)

**Tables** should be named as follows:

- Use plural form (`discussions`)
- Separate multiple words with underscores (`access_tokens`)
- For relationships tables, join the two table names in singular form with an underscore in alphabetical order (eg. `discussion_user`)
