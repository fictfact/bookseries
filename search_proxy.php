<?php

$searchType = $_REQUEST['searchType'];

//just doing series for now
$searchType = "series";

$term = $_REQUEST['term'];

$service_url = $_ENV['SEARCH_SERVER'] . "/2011-02-01/search?bq=series_name%3A'". str_replace(' ','+', $term) ."*'&return-fields=series_author_name%2Cseries_id%2Cseries_name%2Cseries_tags&size=100&rank=series_name";

$session = curl_init( $service_url );

curl_setopt($session, CURLOPT_HEADER, false);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($session);
curl_close($session);

print_r( $response );
