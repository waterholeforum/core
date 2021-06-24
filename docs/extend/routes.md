# Routes

Extensions can add routes to Waterhole to create new pages and actions.

Generally to add a new route to the JSON API, you will want to [define a new resource type](). In that case you won't need to add any routes, as the JSON API layer will do that automatically for you.

Waterhole has routers both on the backend and the frontend. Backend routes will generally show a HTML document which boots up the frontend with a specific data payload, and may also contain content HTML for the purpose of search engine optimization. Frontend routes show a Vue component in the content area, which can then make JSON API requests to display data.

## Backend Routes

Backend routes are handled by the [Laravel Router](). You can add routes using the `Routes` extender:

```php
use Waterhole\Extend;
use Illuminate\Routing\Router;

return [
    new Extend\Routes(function (Router $router) {
        $router->get('hello-world', function () {
            return 'Hello, world!';
        });
    })
];
```

You are free to structure your route handlers however you wish – as [controllers with multiple methods](), [invokable controllers](), or just plain Closures.

### Showing the Frontend

The `Waterhole\Frontend\FrontendViewFactory` class can be used to construct an instance of the frontend view – that is, a HTML document which boots the frontend Vue app. It has two methods, `forum` and `admin`, which each return an instance of `Waterhole\Frontend\FrontendView`. Simply return this instance from your route handler to have it rendered.

```php
use Waterhole\Frontend\Document;
use Illuminate\Routing\Controller;

class MyController extends Controller
{
    public function __invoke()
    {
        return FrontendView::forum();
    }
}
```

:::tip Admin Routes
When adding routes for the admin CP, don't worry about adding them on the backend. This is because the admin frontend routing uses *hash mode*, so only adding frontend routes is required.
:::

### Preloading Data

Usually on an initial page load, two document requests will have to be made in serial before content is shown to the user: one to retrieve the frontend HTML document and boot the single-page application, followed by a request to the JSON API to fetch data. To avoid this delay, backend routes can *preload* a JSON API document so that the second request does not need to be made.

To preload a JSON API document, call the `api` method on the `Waterhole\Frontend\FrontendView` instance:

```php
return FrontendView::forum()
    ->api('discussions/123?include=user');
```

Then, on the frontend, the first time an API request is made with the *exact same URL*, the Promise will resolve immediately with the preloaded data.

### Search Engine Optimization

In addition to speeding up the page load time, you can use preloaded data to render plain HTML for the purpose of search engine optimization. Pass a closure as the second argument for the `api` method, where you'll be able to work on the `FrontendView` instance with the resulting JSON API response/document:

```php
return FrontendView::forum()
    ->api(
		'discussions/123?include=user',
    	function (FrontendView $view, $document, ResponseInterface $response) {
            // ...
        }
	);
```

To change the document `<title>`, use the `title` method:

```php
$view->title('My Page');
```

To set content to be rendered within a `<noscript>` tag, use the `content` method. You can pass it a raw HTML string or `Illuminate\Contracts\Support\Renderable` instance (ie. a Laravel View).

```php
use Illuminate\Support\Facades\View;

$view->content(View::file('my-view.blade.php'));
```

### Injecting HTML

Use the `head` method to inject HTML before the `</head>` tag. Use the `body` method to inject HTML in the `<body>`. Note that it is injected before the JavaScript app is booted, so if loading a resource, carefully consider whether you need to load it syncronously or not to avoid slowing down the Time to Interactive.

```php
return FrontendView::forum()
	->head('<meta name="keywords" content="foo, bar">')
    ->body('<script src="analytics.js" async></script>');
```

## Frontend Routes

Frontend routes are handled by [Vue Router](https://router.vuejs.org). You can add routes using the `Routes` extender; pass a callback which returns an array of routes to add.

```ts
import { Extend } from 'waterhole/forum';
import HelloWorldPage from './components/HelloWorldPage.vue';

export default [
    new Extend.Routes(() => [
        {
            path: '/hello-world',
            name: 'hello-world',
            component: HelloWorldPage,
        }
    ])
];
```

