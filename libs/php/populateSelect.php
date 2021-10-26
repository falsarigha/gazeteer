<?php

    ini_set('display_errors', 'On');
    error_reporting(E_ALL);
    ini_set('memory_limit', '1024M');

    $executionStartTime = microtime(true);

    $countryData = json_decode(file_get_contents("../json/countryBorders.geo.json"), true);

    $countries = [];

    foreach ($countryData['features'] as $feature) {

         $country = null;
         
         $country['iso3'] = $feature["properties"]['iso_a3'];
         $country['name'] = $feature["properties"]['name'];

         array_push($countries, $country); 
    };

    usort($countries, function ($item1, $item2) {

         return $item1['name'] <=> $item2['name'];
    });

     $output['status']['code'] = "200";
     $output['status']['name'] = "ok";
     $output['status']['description'] = "success";
     $output['status']['executedIn'] = intval((microtime(true) - $executionStartTime) * 1000) . " ms";
     $output['data'] = $countries;
 
     header('Content-Type: application/json; charset=UTF-8');

     echo json_encode($output);

?>