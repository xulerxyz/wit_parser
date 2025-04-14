# Wit.ai Parser for Laravel

A Laravel package for interacting with Wit.ai's API, providing easy-to-use services for message parsing and entity/intent management.

## Installation

1. Install the package via Composer:
```bash
composer require nigel/wit_parser
```

2. Publish the configuration file:
```bash
php artisan vendor:publish --provider="Nigel\WitParser\WitParserServiceProvider" --tag="config"
```

3. Add your Wit.ai credentials to your `.env` file:
```
WIT_AI_TOKEN=your_wit_ai_token_here
WIT_AI_BASE_URL=https://api.wit.ai/
```

## Usage

### Message Parsing

```php
use Nigel\WitParser\WitParserService;

class YourController extends Controller
{
    public function parseMessage()
    {
        $parser = new WitParserService();
        $result = $parser->parse("What's the weather in New York?");
        
        // Access parsed data
        $intent = $result->intent;        // e.g., "get_weather"
        $confidence = $result->confidence; // e.g., 0.95
        $entities = $result->entities;    // Array of entities
        $raw = $result->raw;              // Raw API response
    }
}
```

### Managing Entities and Intents

```php
use Nigel\WitParser\WitManagerService;

class YourController extends Controller
{
    public function manageWit()
    {
        $manager = new WitManagerService();
        
        // Create a new entity
        $entity = $manager->createEntity('location', [
            ['value' => 'New York'],
            ['value' => 'London']
        ]);
        
        // Get all entities
        $entities = $manager->getEntities();
        
        // Create a new intent
        $intent = $manager->createIntent('get_weather', [
            ['text' => 'What\'s the weather in New York?'],
            ['text' => 'How\'s the weather in London?']
        ]);
        
        // Get all intents
        $intents = $manager->getIntents();
        
        // Get app info
        $appInfo = $manager->getAppInfo();
    }
}
```

## Available Methods

### WitParserService
- `parse(string $message): WitResult` - Parse a message and get structured data

### WitManagerService
- `createEntity(string $name, array $values = []): array` - Create a new entity
- `getEntities(): array` - Get all entities
- `createIntent(string $name, array $examples = []): array` - Create a new intent
- `getIntents(): array` - Get all intents
- `getAppInfo(): array` - Get app information

## WitResult Object

The `parse()` method returns a `WitResult` object with the following properties:
- `intent`: The detected intent
- `confidence`: Confidence score (0-1)
- `entities`: Array of detected entities
- `raw`: Raw API response

## Error Handling

The package throws `RuntimeException` with descriptive messages for:
- API request failures
- Invalid responses
- Network errors
- Authentication issues

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT). 