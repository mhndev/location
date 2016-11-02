<?php

include "vendor/autoload.php";

$gSuggester = (new \mhndev\location\GoogleLocationSuggester())->suggest($_GET['query']);

Kint::dump($gSuggester);
die();
