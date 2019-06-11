<?php

$seriesID = $_REQUEST['seriesid'];

$service_url = $_ENV['SERIES_API'] . "/v1/series/GetSeriesRecommendations?series_id=" . $seriesID;

$session = curl_init( $service_url );

curl_setopt($session, CURLOPT_HEADER, false);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($session);
curl_close($session);

print_r( $response );
