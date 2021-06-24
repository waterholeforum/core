# JSON API

Waterhole's JSON API strictly adheres to the [JSON:API specification](https://jsonapi.org/format/). A good understanding of this specification is integral for both extension and consumption of the JSON API.

## Extending the API

Waterhole's JSON API layer is powered by the [tobyz/json-api-server](https://github.com/tobyzerner/json-api-server) library which makes it very easy to modify the API schema. Refer to the json-api-server documentation for in-depth details about how to do this, and check out Waterhole's own [schema definition files]() for some great examples.

### Extending an Existing Resouce Type

To configure the schema for an existing resource type in Waterhole, simply use the `JsonApi` extender:

```php
use Tobyz\JsonApiServer\Schema\Type;

new Extend\JsonApi('users', function (Type $type) {
    $type->attribute('animalCount');
    $type->hasMany('animals');
})
```

### Defining a New Resource Type

If defining a new resource type, you'll need to pass in your Eloquent model as well:

```php
new Extend\JsonApi('animals', Animal::class, function (Type $type) {
    $type->attribute('species');
})
```

### Getting the Authenticated User

Waterhole adds an `actor` attribute to the [PSR-7 ServerRequestInterface](https://github.com/php-fig/http-message/blob/master/src/ServerRequestInterface.php) instance that is passed into various json-api-server schema callbacks. It contains the authenticated User, or null if no one is authenticated for the request. It is also acceptable to use Laravel's `Auth` Facade to get information about the authenticated user.

### Schema Conditions

The `Waterhole\Support\Condition` class is syntactic sugar for common logic that applies to API schema conditions – methods like `visible`, `writable`, `updatable`, `creatable`, `deletable`.

Let's say we have an attribute called `name` which should only be writable if the resource is being created, or if the actor has permission to "edit" the resource. The raw logic might look something like this:

```php
$type->attribute('name')
    ->writable(function ($model, $request) {
        $actor = $request->getAttribute('actor');
        return ! $model->exists || ($actor && $actor->can('edit', $model));
    });
```

The `Condition` class can be used to make this much more concise and readable:

```php
use Waterhole\Support\Schema;

$type->attribute('name')
    ->writable((new Schema)->creating->or->can('edit'));
```

In addition to schema conditions, it can be used as a getter for attributes and meta information as well:

```php
$type->meta('canReply', new Permission('reply-to-discussion'));
```

Available conditions are:

* `loggedIn` – true if the actor is logged in
* `admin` – true if the actor is an admin
* `can(string $ability)` – true if the actor has permission to perform `$ability` on this model
* `self` – true if this model is the actor
* `belongsToSelf(string $relation = 'user')` – true if this model's `$relation` is the actor
* `creating` – true if this model does not yet exist

Conditions can be chained, and the following modifiers are available:

* `and` – requires both of the surrounding conditions to be true (default)
* `or` – requires either one of the surrounding conditions to be true
* `not` – negates the following condition

A few examples:

```php
// True if the actor is updating their own account
(new Condition)->self->and->not->creating

// True if the actor has either the update or modify permission
(new Condition)->can('update')->or->can('modify')
```

### Validation

The `Waterhole\Support\Rules` class is a helper to build a json-api-server validator function which applies [Laravel Validation rules](https://laravel.com/docs/6.x/validation#available-validation-rules) to the data. Simply pass in an array of rules:

```php
use Waterhole\Support\Rules;

$type->attribute('name')
    ->writable()
    ->validate(new Rules(['required', 'min:3', 'max:20']));
```

Optionally, you can specify [custom error messages](https://laravel.com/docs/6.x/validation#working-with-error-messages):

```php
new Rules(
    ['required', 'min:3', 'max:20'],
    ['required' => 'You must have a name!']
)
```

## Consuming the API

### Making API Requests

To make requests to the API from the frontend, use the `app.api` method. This is a simple wrapper around [window.fetch]() which has some special behavior to streamline API requests:

* It checks if the request has been [preloaded](), and returns it straight away if it has.
* It sets the `Accept` and `Content-Type` headers to the [JSON:API media type]().
* It allows the `body` option to be an object, and JSON-encodes it into a string.
* It prepends the forum's API base URL.
* When data is returned, it syncs it into the [store]().

`app.api` returns a Promise which resolves an `ApiResult` object. This will contain the Fetch `response`, the JSON:API `document`, and [model(s)]() of the document's `data`.

```ts
import { app, User } from '@waterhole/core/forum';

app.api<User>('users/1')
    .then(({ data, document, response }) => {
        console.log(data instanceof User); // true
        console.log(data.id); // '1'
    });
```

### Handling Errors

In the event of an error, you can catch a `FetchError` object, which contains the Fetch `response` and an error `message`. By default the error message will be [toasted](); if you wish to prevent this in favor of a custom error display, call the `noToast` function:

```ts
.catch(({ response, message, noToast }) => {
    noToast();
});
```

### Building Queries

You can use the `JsonApiQuery` class to simplify the construction of JSON API query strings:

```ts
import { JsonApiQuery } from 'waterhole/forum';

const query = new JsonApiQuery();
query.push('include', 'foo,bar');
query.push({
    'filter[attribute]' => 1,
    'include' => 'test'
});

query.toString(); // include=foo,bar,test&filter[attribute]=1
```

### The Model Store

The `app.models` variable is an instance of [tobyz/json-api-store]() which wraps and stores JSON:API data and makes it much easier to work with. This class doesn't actually talk to the API itself – it just stores the data that has already been loaded via `app.api`. Read the [documentation]() to learn how to use the store and its models.

```ts
import { app, User } from 'waterhole/forum';

const user = app.models.find<User>('users', '1');
const users = app.models.findAll<User>('users');
```

The [Model instances]() themselves contain all of the original JSON:API resource object members (`type`, `id`, `attributes`, `relationships`, `links`, and `meta`), but they also allow accessing fields (attributes and relationships) as top-level properties.

Waterhole provides model subclasses for all core resource types, some of which provide getters and methods for common operations (for example, `Category.children`). Information about these can be found in the [Frontend API Reference]().

If you have added a new resource type, you can use it without defining a new Model subclass for it. However if you wish to provide any custom getters or methods, or explicitly define the available fields on your model for better typing, you can register a `Model` subclass using the `Model` extender:

```ts
import { Extend, Model } from 'waterhole/forum';

class Tag extends Model {
  	public name: string;
    public description: string;
}

export const extend = [
  	new Extend.Model('tags', Tag)
];
```

### Updating Resources

When constructing API requests, remember that JSON:API resource objects contain `links` that can be used instead of rebuilding the URL. Also, json-api-store models contain an `identifier` method that can be used to spread the `type` and `id` members into the document `data` (required by the specification). Here is an example of a request to update a resource:

```ts
const user = app.models.find<User>('users', '1');

app.api<User>(user.links.self, {
    method: 'PATCH',
    body: {
        data: {
            ...user.identifier(),
            attributes: { name: 'Changed' }
        }
    }
})
    .then(({ data }) => {
        console.log(data.name); // 'Changed'
    });
```

## Extending API Queries

Waterhole makes various JSON API requests to load data which you may wish to extend. For example, if you add a relationship to the "discussions" resource type, you may wish to have it included when the request is made to display the discussion index, or the discussion page.

Because these requests can happen in both the frontend and the backend (when [preloading data]()), Waterhole includes a mechanism to sync the URLs. Therefore you only need to extend them on the backend. You can do so using the following extenders:

* `DiscussionListQuery` for the request to load the discussion list on the index page
* `DiscussionQuery` for the request to load the discussion on a discussion page
* `PostsQuery` for the request to load posts on a discussion page
* `CategoryListQuery` for the request to load the category list on the categories page

Simply pass an array of query parameters you wish to add to the query. `include`s will be appended to the default includes. Note that parameters like `sort` and `page` may be overridden on the frontend.

```php
new Extend\DiscussionListQuery([
    'include' => 'yourRelationship,yourRelationship.child',
    'fields[yourResource]' => 'foo'
])
```

