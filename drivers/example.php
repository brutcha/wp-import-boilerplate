<?php
/**
 * Example import feed
 *
 * @link       https://github.com/brutcha/
 * @since      1.0.0
 *
 * @package    Import
 * @subpackage Import/drivers
 *
 */

$feed = file_get_contents( 'http://countryapi.gear.host/v1/Country/getCountries' );
$JSON = json_decode( $feed, true );

$info = array_reduce(
    $JSON['Response'],
    function ( $result, $country )
    {
        return $result . ' ' . sprintf('%s: %s', $country['Name'], $country['Alpha3Code']);
    },
    ''
);

echo json_encode([
    'info' => $info,
    'step' => 1,
    'totalSteps' => 1
]);
