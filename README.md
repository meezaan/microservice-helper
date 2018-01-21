# Various helpers for Microservice templates

## Usage

```php
use Meezaan\MicroServiceHelper\HttpCodes;
use Meezaan\MicroServiceHelper\Response;

echo HttpCodes::getCode(401); // BAD REQUEST

// Use to get output for an API with Http Status code built into the response
echo Response::build('my message', 200); // ['code' => 200, 'status' => 'OK', 'data' => 'my message'];
```

### To run tests
```
composer install
```

Then ```vendor/bin/phpunit tests/Unit```

