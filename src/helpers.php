<?php
namespace mhndev\location;

/**
 * @param array $array
 * @param array $keys
 * @param null $default
 * @return array|mixed|null
 */
function get_in(array $array, array $keys, $default = null)
{
    if (!$keys) {
        return $array;
    }

    // This is a micro-optimization, it is fast for non-nested keys, but fails for null values
    if (count($keys) === 1 && isset($array[$keys[0]])) {
        return $array[$keys[0]];
    }

    $current = $array;
    foreach ($keys as $key) {
        if (!is_array($current) || !array_key_exists($key, $current)) {
            return $default;
        }

        $current = $current[$key];
    }

    return $current;
}


/**
 * @param array $array
 * @param array $keys
 * @param callable $f
 * @return array
 */
function update_in(array $array, array $keys, callable $f /* , $args... */)
{
    $args = array_slice(func_get_args(), 3);

    if (!$keys) {
        return $array;
    }

    $current = &$array;
    foreach ($keys as $key) {
        if (!is_array($current) || !array_key_exists($key, $current)) {
            throw new \InvalidArgumentException(sprintf('Did not find path %s in structure %s', json_encode($keys), json_encode($array)));
        }

        $current = &$current[$key];
    }

    $current = call_user_func_array($f, array_merge([$current], $args));

    return $array;
}


/**
 * @param array $array
 * @param array $keys
 * @param $value
 * @return array
 */
function assoc_in(array $array, array $keys, $value)
{
    if (!$keys) {
        return $array;
    }

    $current = &$array;
    foreach ($keys as $key) {

        if (!is_array($current)) {
            $current = [];
        }

        $current = &$current[$key];
    }

    $current = $value;

    return $array;
}


/**
 * Calculates the great-circle distance between two points, with the Vincenty formula.
 *
 * @param float $latFrom Latitude of start point in [deg decimal]
 * @param float $lngFrom Longitude of start point in [deg decimal]
 * @param float $latTo Latitude of target point in [deg decimal]
 * @param float $lngTo Longitude of target point in [deg decimal]
 * @param float|int $earthRadius Mean earth radius in [m]
 *
 * @return float Distance between points in [m] (same as earthRadius)
 */
function distance(
    $latFrom, $lngFrom, $latTo, $lngTo, $earthRadius = 6371000)
{
    // convert from degrees to radians
    $latFrom = deg2rad($latFrom);
    $lonFrom = deg2rad($lngFrom);
    $latTo = deg2rad($latTo);
    $lonTo = deg2rad($lngTo);

    $lonDelta = $lonTo - $lonFrom;
    $a = pow(cos($latTo) * sin($lonDelta), 2) +
        pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
    $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

    $angle = atan2(sqrt($a), $b);
    return $angle * $earthRadius;
}
