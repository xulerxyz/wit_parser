<?php

namespace Nigel\WitParser;

class WitResult
{
    public string $intent;
    public array $entities;
    public array $raw;
    public float $confidence;

    public static function fromArray(array $data): WitResult
    {
        $result = new self();
        
        $result->intent = $data['intents'][0]['name'] ?? 'Unknown';
        $result->confidence = $data['intents'][0]['confidence'] ?? 0;
        $result->entities = $data['entities'] ?? [];
        $result->raw = $data;

        return $result;
    }
}