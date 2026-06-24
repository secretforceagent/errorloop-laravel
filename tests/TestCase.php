<?php

namespace ErrorLoop\Sdk\Tests;

use ErrorLoop\Sdk\ErrorLoopServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ErrorLoopServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('errorloop-sdk.endpoint', 'https://errorloop.test');
        $app['config']->set('errorloop-sdk.api_key', 'project-api-key');
        $app['config']->set('errorloop-sdk.agent_token', 'agent-token');
        $app['config']->set('errorloop-sdk.release', 'abc123');
    }
}
