<?php

namespace Nigel\WitParser;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Config;

class WitManagerService
{
    protected $token;
    protected $base_url;
    protected $client;

    public function __construct()
    {
        $this->token = Config::get('wit.token');
        $this->base_url = Config::get('wit.base_url');
        $this->client = new Client([
            'base_uri' => $this->base_url,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Create a new entity
     */
    public function createEntity(string $name, array $values = []): array
    {
        try {
            $response = $this->client->post('/entities', [
                'json' => [
                    'name' => $name,
                    'values' => $values,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $this->handleRequestException($e);
        }
    }

    /**
     * Get all entities
     */
    public function getEntities(): array
    {
        try {
            $response = $this->client->get('/entities');
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $this->handleRequestException($e);
        }
    }

    /**
     * Create a new intent
     */
    public function createIntent(string $name, array $examples = []): array
    {
        try {
            $response = $this->client->post('/intents', [
                'json' => [
                    'name' => $name,
                    'examples' => $examples,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $this->handleRequestException($e);
        }
    }

    /**
     * Get all intents
     */
    public function getIntents(): array
    {
        try {
            $response = $this->client->get('/intents');
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $this->handleRequestException($e);
        }
    }

    /**
     * Get app info
     */
    public function getAppInfo(): array
    {
        try {
            $response = $this->client->get('/apps');
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $this->handleRequestException($e);
        }
    }

    /**
     * Handle request exceptions
     */
    protected function handleRequestException(RequestException $e): void
    {
        if ($e->hasResponse()) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            
            throw new \RuntimeException(
                "Wit.ai API request failed with status code {$statusCode}: {$body}"
            );
        }
        
        throw new \RuntimeException(
            "Wit.ai API request failed: " . $e->getMessage()
        );
    }
} 