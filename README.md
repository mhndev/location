
Location Service
```php
require 'vendor/autoload.php';

$googleClient = new \mhndev\location\GoogleGeocoder();

$googleClient->setHttpAgent(new \mhndev\location\GuzzleHttpAgent());

$result = $googleClient
    ->setLocale('fa-IR')
    ->geocode('پیروزی پرستار', 4);


Kint::dump($result);
die();
```