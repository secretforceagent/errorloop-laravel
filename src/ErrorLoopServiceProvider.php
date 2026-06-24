<?php

namespace ErrorLoop\Sdk;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;

class ErrorLoopServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/errorloop.php', 'errorloop');

        $this->app->singleton(ErrorLoopClient::class, function ($app) {
            return new ErrorLoopClient(
                endpoint: $app['config']->get('errorloop.endpoint'),
                apiKey: $app['config']->get('errorloop.api_key'),
                release: $app['config']->get('errorloop.release'),
            );
        });

        $this->app->singleton(ErrorLoopReporter::class, function ($app) {
            return new ErrorLoopReporter(
                client: $app->make(ErrorLoopClient::class),
                release: $app['config']->get('errorloop.release'),
            );
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/errorloop.php' => config_path('errorloop.php'),
            ], 'errorloop-config');
        }

        $this->registerExceptionHandler();
    }

    private function registerExceptionHandler(): void
    {
        $handler = $this->app->make(ExceptionHandler::class);

        if (! method_exists($handler, 'reportable')) {
            return;
        }

        $handler->reportable(function (\Throwable $throwable) {
            if (! config('errorloop-sdk.enabled', true)) {
                return;
            }

            if ($this->shouldIgnore($throwable)) {
                return;
            }

            $this->app->make(ErrorLoopReporter::class)->report($throwable);
        });
    }

    private function shouldIgnore(\Throwable $throwable): bool
    {
        $ignored = config('errorloop-sdk.ignore_exceptions', []);

        foreach ($ignored as $class) {
            if ($throwable instanceof $class) {
                return true;
            }
        }

        return false;
    }
}
