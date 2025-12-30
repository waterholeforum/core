<?php

namespace Waterhole\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;
use Tobyz\JsonApiServer\Exception\ErrorProvider;
use Waterhole\Extend\Api\JsonApi;
use Waterhole\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function __construct(private readonly JsonApi $api)
    {
    }

    public function __invoke(Request $request)
    {
        try {
            return DB::transaction(fn() => $this->api->handle($request));
        } catch (Throwable $e) {
            // If debug mode is on, and the exception that has occurred is not
            // JSON:API-friendly, then re-throw it so that it will bubble up
            // to the Laravel exception handler which will show full info.
            if (!$e instanceof ErrorProvider) {
                if (config('app.debug')) {
                    throw $e;
                }

                report($e);
            }

            return $this->api->error($e);
        }
    }
}
