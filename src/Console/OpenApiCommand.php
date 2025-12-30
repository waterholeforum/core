<?php

namespace Waterhole\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Tobyz\JsonApiServer\OpenApi\OpenApiGenerator;
use Waterhole\Extend\Api\JsonApi;

class OpenApiCommand extends Command
{
    protected $signature = 'waterhole:openapi';

    protected $description = 'Generate an OpenAPI description of the Waterhole API';

    public function handle(JsonApi $api): void
    {
        $generator = new OpenApiGenerator();

        $spec = $generator->generate($api);

        $spec['info'] = ['title' => config('waterhole.forum.name') . ' API'];
        $spec['components']['securitySchemes']['BearerAuth'] = [
            'type' => 'http',
            'scheme' => 'bearer',
        ];
        $spec['security'][] = ['BearerAuth' => []];

        File::put(base_path('waterhole-openapi.json'), json_encode($spec, JSON_PRETTY_PRINT));
    }
}
