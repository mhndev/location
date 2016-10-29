<?php

namespace mhndev\location;
use mhndev\location\Exception\InvalidArgument;
use mhndev\location\Exception\InvalidCredentials;
use mhndev\location\Exception\NoResult;
use mhndev\location\Exception\QuotaExceeded;
use mhndev\location\Exception\UnsupportedOperation;
use mhndev\location\Interfaces\iGeocoder;
use mhndev\location\Interfaces\iHttpAgent;
use mhndev\location\Interfaces\iLocaleAware;

/**
 * Class GoogleGeocoder
 * @package mhndev\location
 */
class GoogleGeocoder extends aGeocoder implements iGeocoder, iLocaleAware
{

    /**
     * @var iHttpAgent
     */
    private $httpAgent;


    /**
     * @var boolean
     */
    private $useSsl;


    /**
     * @var string
     */
    private $locale;


    /**
     * @var string
     */
    const ENDPOINT_URL = 'http://maps.googleapis.com/maps/api/geocode/json?address=%s';

    /**
     * @var string
     */
    const ENDPOINT_URL_SSL = 'https://maps.googleapis.com/maps/api/geocode/json?address=%s';


    /**
     * @param iHttpAgent $httpAgent
     * @return $this
     */
    public function setHttpAgent(iHttpAgent $httpAgent)
    {
        $this->httpAgent = $httpAgent;

        return $this;
    }


    /**
     * @return iHttpAgent
     */
    private function getHttpAgent()
    {
        if($this->httpAgent){
            return $this->httpAgent;
        }else{
            echo 'salam';
        }
    }


    /**
     * Returns the default results.
     *
     * @return array
     */
    protected function getDefaults()
    {
        return [
            'latitude'     => null,
            'longitude'    => null,
            'bounds'       => [
                'south' => null,
                'west'  => null,
                'north' => null,
                'east'  => null,
            ],
            'streetNumber' => null,
            'streetName'   => null,
            'locality'     => null,
            'postalCode'   => null,
            'subLocality'  => null,
            'adminLevels'  => [],
            'country'      => null,
            'countryCode'  => null,
            'timezone'     => null,
        ];
    }


    /**
     * @param $address
     * @param int $depth
     * @return mixed
     * @throws UnsupportedOperation
     */
    public function geocode($address, $depth = 1)
    {
        // Google API returns invalid data if IP address given, This API doesn't handle IPs
        if (filter_var($address, FILTER_VALIDATE_IP)) {
            throw new UnsupportedOperation('The GoogleMaps provider does not support IP addresses, only street addresses.');
        }

        $query = sprintf(
            $this->useSsl ? self::ENDPOINT_URL_SSL : self::ENDPOINT_URL,
            rawurlencode($address)
        );

        $query = sprintf('%s&language=%s', $query, $this->getLocale());


        $response = $this->httpAgent->GET($query);

        return $this->getAddressesFromResponse($response->getBody()->getContents(), $depth );
    }


    /**
     * @param $content
     * @param $depth
     * @return array
     */
    private function getAddressesFromResponse($content, $depth)
    {
        if (empty($content)) {
            throw new NoResult;
        }

        $json = json_decode($content);

        // API error
        if (!isset($json)) {
            throw new NoResult;
        }

        if ('REQUEST_DENIED' === $json->status && 'The provided API key is invalid.' === $json->error_message) {
            throw new InvalidCredentials;
        }

        if ('REQUEST_DENIED' === $json->status) {
            throw new InvalidCredentials;
        }

        // you are over your quota
        if ('OVER_QUERY_LIMIT' === $json->status) {
            throw new QuotaExceeded;
        }

        // no result
        if (!isset($json->results) || !count($json->results) || 'OK' !== $json->status) {
            throw new NoResult;
        }

        $results = [];
        foreach ($json->results as $result) {
            $resultSet = $this->getDefaults();

            // update address components
            foreach ($result->address_components as $component) {
                foreach ($component->types as $type) {
                    $this->updateAddressComponent($resultSet, $type, $component);
                }
            }

            // update coordinates
            $coordinates = $result->geometry->location;
            $resultSet['latitude']  = $coordinates->lat;
            $resultSet['longitude'] = $coordinates->lng;

            $resultSet['bounds'] = null;
            if (isset($result->geometry->bounds)) {
                $resultSet['bounds'] = array(
                    'south' => $result->geometry->bounds->southwest->lat,
                    'west'  => $result->geometry->bounds->southwest->lng,
                    'north' => $result->geometry->bounds->northeast->lat,
                    'east'  => $result->geometry->bounds->northeast->lng
                );
            } elseif ('ROOFTOP' === $result->geometry->location_type) {
                // Fake bounds
                $resultSet['bounds'] = array(
                    'south' => $coordinates->lat,
                    'west'  => $coordinates->lng,
                    'north' => $coordinates->lat,
                    'east'  => $coordinates->lng
                );
            }


            if($depth == 1){
                $resultSet['toString'] = $resultSet['streetName'];
            }else if ($depth == 2){
                $resultSet['toString'] = $resultSet['subLocality'].' '. $resultSet['streetName'];

            }else if ($depth == 3){
                $resultSet['toString'] = $resultSet['adminLevels'][0]['name'] .' ' . $resultSet['subLocality'].' '. $resultSet['streetName'];
            }else if ($depth == 4){
                $resultSet['toString'] = $resultSet['adminLevels'][1]['name'] .' ' .$resultSet['adminLevels'][0]['name'] .' ' . $resultSet['subLocality'].' '. $resultSet['streetName'];
            }

            $results[] = array_merge($this->getDefaults(), $resultSet);
        }

        return $results;
    }




    /**
     * Update current resultSet with given key/value.
     *
     * @param array  $resultSet resultSet to update
     * @param string $type      Component type
     * @param object $values    The component values
     *
     * @return array
     */
    private function updateAddressComponent(&$resultSet, $type, $values)
    {
        switch ($type) {
            case 'postal_code':
                $resultSet['postalCode'] = $values->long_name;
                break;

            case 'locality':
            case 'postal_town':
                $resultSet['locality'] = $values->long_name;
                break;

            case 'administrative_area_level_1':
            case 'administrative_area_level_2':
            case 'administrative_area_level_3':
            case 'administrative_area_level_4':
            case 'administrative_area_level_5':
                $resultSet['adminLevels'][]= [
                    'name' => $values->long_name,
                    'code' => $values->short_name,
                    'level' => intval(substr($type, -1))
                ];
                break;

            case 'country':
                $resultSet['country'] = $values->long_name;
                $resultSet['countryCode'] = $values->short_name;
                break;

            case 'street_number':
                $resultSet['streetNumber'] = $values->long_name;
                break;

            case 'route':
                $resultSet['streetName'] = $values->long_name;
                break;

            case 'sublocality':
                $resultSet['subLocality'] = $values->long_name;
                break;

            default:
        }

        return $resultSet;
    }


    /**
     * @param $lat
     * @param $lng
     * @return mixed
     */
    public function reverse($lat, $lng)
    {
        return $this->geocode(sprintf('%F,%F', $lat, $lng));
    }


    /**
     * @return string
     */
    function getLocale()
    {
        if($this->locale){
            return $this->locale;
        }
        else{
            $this->setLocale('en-US');

            return $this->locale;
        }
    }

    /**
     * @param $locale
     * @return $this
     */
    function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }
}
