<?php

namespace Nigel\WitParser;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Config;

class WitParserService{
    protected  $token;
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

    public function parse(string $message) : WitResult {
        try {
            $response = $this->client->get('/message', [
                'query' => [
                    'q' => $message,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Failed to parse Wit.ai response: ' . json_last_error_msg());
            }

            return WitResult::fromArray($data);
        } catch (RequestException $e) {
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
        } catch (GuzzleException $e) {
            throw new \RuntimeException(
                "Failed to communicate with Wit.ai API: " . $e->getMessage()
            );
        }
    }
}