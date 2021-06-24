# Introduction

Waterhole has been designed from the ground up to be highly extensible. With a little bit of know-how, you can customize almost any aspect of Waterhole in a sustainable way, without modifying core source code files. This document will take you through some essential concepts, after which you'll build your first Waterhole extension from scratch. We recommend allowing about an hour to follow it through from start to finish.

## Architecture

In order to understand how to extend Waterhole, first we need to understand a bit about how Waterhole is built. Waterhole is made up of three layers:

* First, there is the **backend**. This is built in PHP using the [Laravel](https://laravel.com/) framework.

* Second, the backend exposes a **public API** which allows frontend clients to interface with your forum's data. This is built according to the [JSON:API specification](https://jsonapi.org/), using the [tobyz/json-api-server](tobyz/json-api-server) library.

* Finally, there is the default web interface which we call the **frontend**. This is a [single-page JavaScript application](https://en.wikipedia.org/wiki/Single-page_application) built using [Vue](https://vuejs.org) which consumes the JSON:API.

Extensions will often need to interact with all three of these layers to make things happen. For example, if you wanted to build an extension that adds custom fields to user profiles, you would need to add the appropriate database structures in the **backend**, expose that data for reading/writing in the **public API**, and then display it and allow users to edit it on the **frontend**.

So... how do we extend these layers?

## Extenders

In order to extend Waterhole, we will be using a concept called **extenders**. Extenders are objects that describe in plain terms the goals you are trying to achieve (such as adding a new route to your forum, or executing some code when a new discussion was created).

Every extender is different. However, they will always look somewhat similar to this:

```php
// Register a JavaScript file to be delivered with the forum frontend
new Extend\ForumJs(__DIR__.'/forum.js')
```

To keep things consistent, we use this concept of extenders in both the backend (in PHP land) and the frontend (in JavaScript land). _Everything_ you do in your extension should be done via extenders, because they are a **guarantee** that a future minor release of Waterhole won't break your extension.

All of the extenders currently available to you from Waterhole can be found in the [`Extend` namespace](https://github.com/waterhole/core/blob/master/src/Extend). Extensions may also offer their own extenders.

## Site Customizations

Want to see an extender in action? The `bootstrap/extend.php` file in the root of your Waterhole installation is the easiest way to register extenders just for your site, without creating an actual extension. It should return an array of extender objects. Pop it open and add the following:

```php
<?php

use Waterhole\Extend;
use Waterhole\Frontend\View;

return [
    new Extend\ForumView(function (View $view) {
      	$view->head .= '<script>alert("Hello, world!")</script>';
    })
];
```

Now pay your forum a visit for a pleasant (though somewhat obtrusive) greeting. 👋

For simple site-specific customizations – like adding a bit of custom CSS/JavaScript, or integrating wth your site's authentication system – the `bootstrap/extend.php` file in your forum's root is great. But at some point, your customization might outgrow it. Or maybe you want to build an extension to share with the community from the get-go. Time to build an extension!

## Extension Structure

Every Waterhole extension is a [Composer](https://getcomposer.org) package. That means someone's Waterhole installation can "require" a certain extension and Composer will pull it in and keep it up-to-date. Nice!

During development, you can work on your extensions locally and set up a [Composer path repository](https://getcomposer.org/doc/05-repositories.md#path) to install your local copy. Create a new `packages` folder in the root of your Waterhole installation, and then run this command to tell Composer that it can find packages in here:

```bash
composer config repositories.0 path "packages/*"
```

### Scaffolding with Vue CLI

Remember those three layers we talked about earlier? We said that most of the time, Waterhole extensions will need to interact with all three of them – that means, more than likely you will be writing some JavaScript and Vue components to extend the frontend. And that means we're gonna need to set up a **build system** to compile our JavaScript/Vue source code into a bundle that browsers can understand.

The easiest way to do this is using [Vue CLI](https://cli.vuejs.org), which is a build system for Vue projects. To get started, you'll need to install the following:

* Node.js and npm: [Download](https://nodejs.org/en/download/)
* Vue CLI: `npm install -g @vue/cli`

Next, run the following command inside of your Waterhole installation:

```sh
cd packages
vue create --preset waterhole/extension-preset hello-world
```

Vue CLI will scaffold out a directory for your extension and install all the Node.js dependencies needed to compile Waterhole-compatible JavaScript. Let's take a look at the files inside:

```sh
.
├── admin/
    └── index.js          # Admin frontend extenders
├── forum/
    └── index.js          # Forum frontend extenders
├── migrations/           # Database migrations
├── src/                  # PHP classes
├── babel.config.js
├── composer.json         # Extension meta-data
├── extend.php            # PHP extenders
├── package-lock.json
├── package.json
├── README.md
└── vue.config.js
```

### `composer.json`

This file tells both Composer and Waterhole a bit about our package, and looks a bit like this:

```json
{
  "name": "acme/waterhole-hello-world",
  "description": "Say hello to the world!",
  "type": "waterhole-extension",
  "require": {
    "waterhole/core": "^0.1.0"
  },
  "autoload": {
    "psr-4": {
      "Acme\\HelloWorld\\": "src/"
    }
  },
  "extra": {
    "waterhole-extension": {
      "name": "Hello World",
    }
  }
}
```

* **name** is the name of the Composer package in the format `vendor/package`.
  * You should choose a vendor name that’s unique to you — your GitHub username, for example. For the purposes of this tutorial, we’ll assume you’re using `acme` as your vendor name.
  * You should prefix the `package` part with `waterhole-` to indicate that it’s a package specifically intended for use with Waterhole.

* **description** is a short one-sentence description of what the extension does.

* **type** MUST be set to `waterhole-extension`. This ensures that when someone "requires" your extension, it will be identified as such.

* **require** contains a list of your extension's own dependencies.
  * You'll want to specify the version of Waterhole that your extension is compatible with here. Since Waterhole uses [Semantic Verisoning](https://semver.org), generally you can use the [`^` prefix](https://getcomposer.org/doc/articles/versions.md#caret-version-range-) to indicate that your extension requires any version of Waterhole from the one you specify until the next backwards-compatibility-breaking release.
  
* **autoload** tells Composer where to find your extension's classes. The namespace in here should reflect your extension's vendor and package name in CamelCase.

* **extra.waterhole-extension** contains some Waterhole-specific information. For now it's just the display **name** of your extension.

See [the composer.json schema](https://getcomposer.org/doc/04-schema.md) documentation for information about other properties you can add to `composer.json`.

### `extend.php`

```php
<?php

use Waterhole\Extend;

return [
    // Your extenders here
];
```

The `extend.php` file is just like the one in the root of your site. It will return an array of extender objects that tell Waterhole what you want to do. It's been pre-populated with some common extenders which you may wish to use.

### `admin/index.js` and `forum/index.js`

```js
import { Extend } from '@waterhole/core/forum';

export const extend = [
	  // Your extenders here
];
```

These files are the JavaScript equivalent of `extend.php`. Like their PHP counterpart, they should export an array of extender objects which tell Waterhole what you want to do on the admin and forum frontends respectively.

Later on we'll use Vue CLI (which has been preconfigured via `package.json`, `babel.config.js`, and `vue.config.js`) to compile these JavaScript files into bundles that can be loaded into the Waterhole frontends.

:::tip TypeScript
If you'd like to write your extension in TypeScript instead of JavaScript, run the following command:

```sh
vue add typescript
```
This will create a bit of extra clutter in the `src` directory – you can go ahead and delete all of these files since we won't be needing them.

You'll also need to update `vue.config.js` to change the entry points from `index.js` to `index.ts`.

Finally, note that we importing stuff from the `@waterhole/core` – but this is not actually a real dependency, rather, it is set up as an [external](https://webpack.js.org/configuration/externals/). But since TypeScript *needs* it to be in `node_modules` for the types to be available, we'll need to link it there:

```bash
cd ~/path/to/waterhole/vendor/waterhole/core && npm link
cd ~/path/to/waterhole/packages/hello-world && npm link @waterhole/core
```

:::

## Hello World

Now we have everything in place, let's make our extension do something. But instead of just making it say "hello world", let's make it do something useful, and introduce a few important concepts along the way.

We're going to add a "favorite color" field to the sign-up form. With the *three layers* in mind, let's spec out what we'll need to do to achieve this:

1. Add a column to the `users` database table to store everyone's favorite colors
2. Expose this column in the JSON API as a readable/writable attribute on the `users` resource
3. Add the field to the sign up form on the frontend

Before we get started, you should go and set `debug` mode to **true** in `config.php`. This will ensure Waterhole gives us detailed error messages in case something goes wrong, and it will also mean we don't have to worry about over-caching of assets.

### 1. Adding a Migration

First up: adding a column to the `users` database table. In order to do this, we'll need to make a **database migration**. This is an instruction that tells Waterhole how to modify the database when the extension is installed, and how to reverse that change if the extension is uninstalled. Waterhole uses's [Laravel's migration implementation](https://laravel.com/docs/6.x/migrations), so it's a good idea to become familiar with that.

To generate a migration, run the following command:

```sh
php waterhole make:migration add_favorite_color_to_users_table \
	--table users \
	--path packages/hello-world/migrations
```

Open up the newly generated migation file. In the `up` method, add the following code:

```php
$table->string('favorite_color')->nullable();
```

And in the `down` method, add this:

```php
$table->dropColumn('favorite_color');
```

Done! We won't be running the migration just yet; for now let's move onto the next step.

### 2. Exposing the Data in the JSON API

Waterhole uses Laravel's [Eloquent ORM](https://laravel.com/docs/6.x/eloquent) to model the database. This means that our new `favorite_color` column will immediately be available on all `Waterhole\User` model instances. Nice!

Exposing that data in the JSON API is simple as well thanks to Waterhole's use of the [tobyz/json-api-server](https://github.com/tobyzerner/json-api-server) library, which takes care of all of the boilerplate for us. All we need to do is add an attribute to the API schema. We do this with – you guessed it – an extender:

`extend.php`

```php
<?php

use Waterhole\Models\Discussion;
use Waterhole\Extend;
use Waterhole\JsonApi\Condition;
use Waterhole\JsonApi\Rules;
use Tobyz\JsonApiServer\Schema\Type;

return [
    new Extend\JsonApi('users', function (Type $type) {
      	$type->attribute('favoriteColor')
            ->writable((new Condition)->creating->or->self)
            ->validate(new Rules('in:red,green,blue,yellow'));
    })
];
```

That's it! We've simply added an attribute called `favoriteColor` to the `users` API resource, and this will automatically correspond to the `favorite_color` attribute on the `Waterhole\User` model. There's a few interesting things going on here to restrict who can write to the attribute and what values are valid – these are explained more in-depth in the [JSON API]() section of the docs.

###3. Adding the Field to the Frontend

The final thing to do is add a field to the sign up form on the frontend to allow users to set their favorite color when they sign up. Recall that Waterhole's frontend is built with [Vue](https://vuejs.org)... so we're gonna need a Vue component to render the field's label/input. Let's create that now:

`forum/components/FavoriteColorField.vue`

```vue
<template>
  <div class="Field" :class="{ 'Field--error': error }">
    <label class="Field-label">Favorite Color</label>
    <input type="text" class="Input" v-model="value">
    <div class="Field-status" v-if="error">{{ error }}</div>
  </div>
</template>

<script>
export default {
  	name: 'FavoriteColorField',
  	props: ['value', 'error']
};
</script>
```

This is a pretty simple Vue component which just displays a label, an input ([modelling]() the `value` prop), and a validation error if one is present. The two props – `value` and `error` – will be passed into our component by the sign-up form.

Now let's inject this component into the form. In Waterhole, this is easy, because every mutable part of the interface is really just a *list of components*. These [Item Lists]() allow your extension to add, remove, and rearrange the components that make up the interface. So adding our field to the sign up form couldn't be easier – we just find the appropriate extender, and then add our component to the item list.

`forum/index.js`

```js
import { Extend } from '@waterhole/core/forum';
import FavoriteColorField from './components/FavoriteColorField.vue';

export default [
    new Extend.AuthSignUpFields(items => {
        items.add('favoriteColor', FavoriteColorField);
    })
];
```

OK, time to fire up the compiler. Run the following command in your extension's directory:

```bash
npm run watch
```

This will compile your browser-ready JavaScript code into the `dist/js/forum.js` file, and keep watching for changes to the source files. Nifty! Now just add an extender to `extend.php` to tell Waterhole about this compiled JavaScript file:

```php
new Extend\ForumJs(__DIR__.'/dist/js/forum.js')
```

### Installing Your Extension

The final thing we need to do to get up and running is, of course, to *install* your extension! Navigate to the root directory of your Waterhole install and run the following command:

```bash
composer require acme/waterhole-hello-world *@dev
```

Once that's done, go ahead and fire 'er up on your forum's Administration page. Waterhole will run your extension's migration and concatenate your extension's JavaScript bundle onto its own. 

Navigate back to your forum, log out, and try signing up a new account. (My favorite color is yellow, if anyone's wondering.)

## Summary

Not bad for your first extension, let alone in only a few lines of code! Let's quickly summarize what we learnt from this example:

* 

## Next Steps

* Learn more about interacting with the database – how Migrations work, how to extend Models, 
* Learn more about extending and consuming the JSON API
* Learn more about Item Lists 
