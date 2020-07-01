# Laravel Idempotent Requests

First, what is Idempotency and why I need it?

Checkout this awesome [post from Brandum Leach](https://stripe.com/blog/idempotency).

### Install

Require this package with composer using the following command:

```bash
composer require javidalpe/laravel-idempotency 
```

### Usage

Register Idempotency middleware on your http kernel file:

```php
'api' => [
    'throttle:60,1',
    'bindings',
    \Javidalpe\Idempotency\Idempotency::class,
], 
```

To perform an idempotent request, provide an additional `Idempotency-Key: <key>` header to the request.

### How it works

If the header `Idempotency-Key` is present on the request and the request method is different from GET, PUT and DELETE, the middleware stores the response on the cache. Next time you make a request with same idempotency key, the middleware will return the cached response.

How you create unique keys is up to you, but I suggest using V4 UUIDs or another appropriately random string. It'll always send back the same response for requests made with the same key, and keys can't be reused with different request parameters. Keys expire after 24 hours.  

### License

The Laravel Idempotency is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
