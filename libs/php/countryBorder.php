<?php
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);
    ini_set('memory_limit', '1024M');

    $executionStartTime = microtime(true);

    // Get Country Border
    $geoJSON = json_decode(file_get_contents("../json/countryBorders.geo.json"), true);
    $geoJsonCountries = $geoJSON['features'];
    $countryBorder = null;
    
    foreach($geoJsonCountries as $country){
        if($country['properties']['iso_a3'] == $_REQUEST['code']){
            $countryBorder = $country['geometry'];
        break;
        }
    }

    $output['border'] = $countryBorder;

    header("Content-Type: application/json; charset=UTF-8");

    echo json_encode($output);

?>