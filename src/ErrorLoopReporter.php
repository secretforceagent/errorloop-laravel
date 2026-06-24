<?php

namespace ErrorLoop\Sdk;

use Throwable;

class ErrorLoopReporter
{
    public function __construct(
        private ErrorLoopClient $client,
        private ?string $release = null,
    ) {}

    public function report(Throwable $throwable): void
    {
        $frames = $throwable->getTrace();
        $topFrame = $frames[0] ?? [];

        $payload = [
            'exception_class' => get_class($throwable),
            'message' => $throwable->getMessage(),
            'release' => $this->release ?? $this->client->getRelease(),
            'top_frame' => [
                'file' => $topFrame['file'] ?? $throwable->getFile(),
                'function' => $topFrame['function'] ?? '',
            ],
            'payload' => [
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => $this->sanitizeTrace($frames),
            ],
        ];

        $this->client->sendEvent($payload);
    }

    /**
     * @param  array<int, array<string, mixed>>  $trace
     * @return array<int, array<string, mixed>>
     */
    private function sanitizeTrace(array $trace): array
    {
        return array_slice($trace, 0, 20);
    }
}
