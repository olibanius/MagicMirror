<?php

date_default_timezone_set('Europe/Stockholm');

include('vasttrafik.class.php');

$vasttrafik = new Vasttrafik;
$fromId = $vasttrafik->searchStop('Lana');
$toId = $vasttrafik->searchStop('Brunnsparken');
$departures = $vasttrafik->getDepartures($fromId, $toId, 3);
print_r($departures);
die;

$tokenResponse = exec('curl -k -d "grant_type=client_credentials" -H "Authorization: Basic cmI5UTlwNUxZWE83SlpwSUFkY0w1ZlEwMzFzYTpzY2o4UnhWUWxHMlJuYUJvVk91RHRZeHptTTBh, Content-Type: application/x-www-form-urlencoded" https://api.vasttrafik.se:443/token 2>/dev/null');
$tokenResponseArray = json_decode($tokenResponse, true);
$bearerToken = $tokenResponseArray['access_token'];
echo "Bearer token: $bearerToken \n";

$lanaId = searchStop('Lana');
$brunnsparkenId = searchStop('Brunnsparken');

var_dump($lanaId);
var_dump($brunnsparkenId);

print_r(getDepartures($lanaId, $brunnsparkenId));


function searchStop($searchQuery) {
    global $bearerToken;

    ob_start();
    passthru('curl --silent --header "Authorization: Bearer ' . $bearerToken . '" "https://api.vasttrafik.se/bin/rest.exe/v2/location.name?format=json&input="' . $searchQuery);
    $locationResponse = ob_get_contents();
    ob_end_clean();

    $locationResponseArray = json_decode($locationResponse, true);

    return $locationResponseArray['LocationList']['StopLocation'][0]['id'];
}

function getDepartures($fromStopId, $toStopId) {
    global $bearerToken;

    ob_start();
    passthru('curl --silent --header "Authorization: Bearer ' . $bearerToken . '" "https://api.vasttrafik.se/bin/rest.exe/v2/departureBoard?id=' . $fromStopId . '&date=' . date('Y-m-d', time()) . '&time=' . date('H', time()) . '%3A' . date('i', time()) . '&direction=' . $toStopId . '&format=json"');
    $departuresResponse = ob_get_contents();
    ob_end_clean();

    var_dump('curl --silent --header "Authorization: Bearer ' . $bearerToken . '" "https://api.vasttrafik.se/bin/rest.exe/v2/departureBoard?id=' . $fromStopId . '&date=' . date('Y-m-d', time()) . '&time=' . date('H', time()) . '%3A' . date('i', time()) . '&direction=' . $toStopId . '&format=json"');

    $departuresResponseArray = json_decode($departuresResponse, true);

    return $departuresResponseArray;
}

function curlResponse($url) {
  global $bearerToken;

  ob_start();
  passthru('curl --silent --header "Authorization: Bearer ' . $bearerToken . '" "' .$url . '"');
  $departuresResponse = ob_get_contents();
  ob_end_clean();

  var_dump('curl --silent --header "Authorization: Bearer ' . $bearerToken . '" "https://api.vasttrafik.se/bin/rest.exe/v2/departureBoard?id=' . $fromStopId . '&date=' . date('Y-m-d', time()) . '&time=' . date('H', time()) . '%3A' . date('i', time()) . '&direction=' . $toStopId . '&format=json"');

  $departuresResponseArray = json_decode($departuresResponse, true);

  return $departuresResponseArray;
}
