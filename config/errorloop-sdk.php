<?php

return [
    'enabled' => env('ERRORLOOP_ENABLED', true),

    'endpoint' => env('ERRORLOOP_ENDPOINT', 'https://errorloop.example.com'),

    'api_key' => env('ERRORLOOP_API_KEY'),

    'agent_token' => env('ERRORLOOP_AGENT_TOKEN'),

    'release' => env('ERRORLOOP_RELEASE', config('app.version')),

    'ignore_exceptions' => [
        // \Illuminate\Validation\ValidationException::class,
    ],
];
