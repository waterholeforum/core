<?php

use Tests\TestCase;
use Tobyz\JsonApiServer\JsonApi;

function jsonApi($method, $uri, array $data = [], array $headers = [])
{
    return test()->json(
        $method,
        $uri,
        $data,
        array_merge(
            [
                'Content-Type' => JsonApi::MEDIA_TYPE,
                'Accept' => JsonApi::MEDIA_TYPE,
            ],
            $headers,
        ),
    );
}

function extend(callable $callback): void
{
    $class = (new ReflectionFunction($callback))->getParameters()[0]->getType()->getName();

    app()->extend($class, function ($instance) use ($callback) {
        return $callback($instance) ?: $instance;
    });
}

pest()->extend(TestCase::class)->in('Feature');
