
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



$result = \mhndev\location\distance(35.691339, 51.471760, 35.734837, 51.441062);


var_dump($result);
//https://maps.googleapis.com/maps/api/distancematrix/json?origins=35.691339,51.471760&destinations=35.734837,51.441062&key=AIzaSyAwmVos1B201fS2cR_2ahJ79YS2chfa84Y
//35.691339, 51.471760
//35.734837, 51.441062

```
