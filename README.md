
Location Service
```php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require 'vendor/autoload.php';

$googleClient = new \mhndev\location\GoogleGeocoder();

$googleClient->setHttpAgent(new \mhndev\location\GuzzleHttpAgent());

$result = $googleClient
    ->setLocale('fa-IR')
    ->geocode('پیروزی پرستار', 2);


Kint::dump($result);


$reyhoon = new \mhndev\location\ReyhoonLocationSuggester();

$result = $reyhoon->suggest('پیروزی');

Kint::dump($result);



$google = new \mhndev\location\GoogleLocationSuggester();

$result = $google->suggest('پیروزی');

Kint::dump($result);

```
