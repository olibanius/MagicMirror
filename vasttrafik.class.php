<?php

class Vasttrafik {
  protected $basicAuth = 'cmI5UTlwNUxZWE83SlpwSUFkY0w1ZlEwMzFzYTpzY2o4UnhWUWxHMlJuYUJvVk91RHRZeHptTTBh';
  protected $bearerToken = null;

  public function __construct() {
    $tokenResponse = exec('curl -k -d "grant_type=client_credentials" -H "Authorization: Basic '.$this->basicAuth.', Content-Type: application/x-www-form-urlencoded" https://api.vasttrafik.se:443/token 2>/dev/null');
    $tokenResponseArray = json_decode($tokenResponse, true);
    $this->bearerToken = $tokenResponseArray['access_token'];
  }

  public function searchStop($searchQuery) {
      $url = "https://api.vasttrafik.se/bin/rest.exe/v2/location.name?format=json&input=$searchQuery";
      $locationResponseArray = $this->curlVasttrafik($url);

      return $locationResponseArray['LocationList']['StopLocation'][0]['id'];
  }

  public function getDepartures($fromStopId, $toStopId, $count = 10) {

    $date = date('Y-m-d');
    $hour = date('H');
    $minute = date('i');
    $url = "https://api.vasttrafik.se/bin/rest.exe/v2/departureBoard?id=$fromStopId&date=$date&time=$hour%3A$minute&direction=$toStopId&format=json";
    $departuresResponseArray = $this->curlVasttrafik($url);

    $retArr = array();
    foreach ($departuresResponseArray['DepartureBoard']['Departure'] as $departureItem) {
      $retArr[] = array (
        'name' => $departureItem['sname'],
        'time' => $departureItem['time'],
        'date' => $departureItem['date'],
        'rtTime' => $departureItem['rtTime'],
        'rtDate' => $departureItem['rtDate'],
        'fgColor' => $departureItem['fgColor'],
        'bgColor' => $departureItem['bgColor']
      );
      if (count($retArr) == $count) {
        break;
      }
    }
    return $retArr;
  }

  private function curlVasttrafik($url) {
    try {
      ob_start();
      $curl = 'curl --silent --header "Authorization: Bearer '.$this->bearerToken.'" "'.$url.'"';
      passthru($curl);
      $response = ob_get_contents();
      ob_end_clean();

      $retArr = json_decode($response, true);

      return $retArr;
    } catch (Exception $e) {
      throw($e);
    }
  }

}
