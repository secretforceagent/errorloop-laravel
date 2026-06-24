<?php

use ErrorLoop\Sdk\ErrorLoopClient;
use ErrorLoop\Sdk\ErrorLoopReporter;
use Illuminate\Support\Facades\Http;

it('reports a throwable to errorloop', function () {
    Http::fake([
        'https://errorloop.test/api/events' => Http::response(['issue_id' => 1], 201),
    ]);

    $client = new ErrorLoopClient(
        endpoint: 'https://errorloop.test',
        apiKey: 'project-api-key',
        release: 'abc123',
    );

    $reporter = new ErrorLoopReporter($client, 'abc123');
    $reporter->report(new RuntimeException('Something broke'));

    Http::assertSent(fn ($request) => $request['exception_class'] === 'RuntimeException'
        && $request['message'] === 'Something broke'
        && $request['release'] === 'abc123');
});
