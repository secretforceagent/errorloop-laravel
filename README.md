# ErrorLoop Laravel SDK

[![Latest Version](https://img.shields.io/github/v/tag/secretforceagent/errorloop-laravel)](https://github.com/secretforceagent/errorloop-laravel/tags)

Report production exceptions from your Laravel applications to an [ErrorLoop](https://github.com/secretforceagent/errorloop) server.

## Requirements

- PHP 8.3+
- Laravel 11, 12, or 13

## Installation

```bash
composer require errorloop/laravel-sdk
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="ErrorLoop\Sdk\ErrorLoopServiceProvider"
```

Then set your ErrorLoop project credentials in `.env`:

```env
ERRORLOOP_ENDPOINT=https://er.ma.rs
ERRORLOOP_API_KEY=your-project-api-key
ERRORLOOP_ENABLED=true
```

The API key is created in your ErrorLoop project and is used to authenticate exception ingestion requests.

The config file is published as `config/errorloop-sdk.php`.

## Usage

The SDK registers an exception handler that automatically reports uncaught exceptions to ErrorLoop.

You can also report manually by resolving the reporter from the container:

```php
use ErrorLoop\Sdk\ErrorLoopReporter;

try {
    // risky code
} catch (Throwable $e) {
    app(ErrorLoopReporter::class)->report($e);
}
```

Or inject it into a controller or job:

```php
use ErrorLoop\Sdk\ErrorLoopReporter;

public function __construct(private ErrorLoopReporter $reporter) {}

public function handle(): void
{
    try {
        // risky code
    } catch (Throwable $e) {
        $this->reporter->report($e);
    }
}
```

## Disable reporting

Set `ERRORLOOP_ENABLED=false` in your environment to disable reporting without removing the package.

## License

MIT
