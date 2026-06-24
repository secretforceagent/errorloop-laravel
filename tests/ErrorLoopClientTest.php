<?php

use ErrorLoop\Sdk\ErrorLoopClient;
use Illuminate\Support\Facades\Http;

it('sends an event to the errorloop endpoint', function () {
    Http::fake([
        'https://errorloop.test/api/events' => Http::response(['issue_id' => 1], 201),
    ]);

    $client = new ErrorLoopClient(
        endpoint: 'https://errorloop.test',
        apiKey: 'project-api-key',
        release: 'abc123',
    );

    $client->sendEvent([
        'exception_class' => 'RuntimeException',
        'message' => 'Oops',
        'top_frame' => ['file' => '/app.php', 'function' => 'handle'],
    ]);

    Http::assertSent(fn ($request) => $request->url() === 'https://errorloop.test/api/events'
        && $request->hasHeader('Authorization', 'Bearer project-api-key')
        && $request->data()['exception_class'] === 'RuntimeException');
});

it('sends a deploy to the errorloop endpoint', function () {
    Http::fake([
        'https://errorloop.test/api/deploys' => Http::response(['deploy_id' => 1], 201),
    ]);

    $client = new ErrorLoopClient(
        endpoint: 'https://errorloop.test',
        apiKey: 'project-api-key',
        release: 'abc123',
    );

    $client->sendDeploy([
        'project_id' => 1,
        'release' => 'abc123',
    ]);

    Http::assertSent(fn ($request) => $request->url() === 'https://errorloop.test/api/deploys'
        && $request->hasHeader('Authorization', 'Bearer agent-token'));
});
