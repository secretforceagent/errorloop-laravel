<?php

namespace ErrorLoop\Sdk;

use Illuminate\Support\Facades\Http;

class ErrorLoopClient
{
    public function __construct(
        private string $endpoint,
        private string $apiKey,
        private ?string $release = null,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public function sendEvent(array $payload): void
    {
        $response = Http::timeout(5)
            ->connectTimeout(2)
            ->withToken($this->apiKey)
            ->post($this->endpoint('/events'), $payload);

        $response->throw();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function sendDeploy(array $payload): void
    {
        $response = Http::timeout(5)
            ->connectTimeout(2)
            ->withToken($this->agentToken())
            ->post($this->endpoint('/deploys'), $payload);

        $response->throw();
    }

    public function getRelease(): ?string
    {
        return $this->release;
    }

    private function endpoint(string $path): string
    {
        return rtrim($this->endpoint, '/').'/api'.$path;
    }

    private function agentToken(): string
    {
        return config('errorloop-sdk.agent_token', '');
    }
}
