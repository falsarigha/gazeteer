<?php
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);
    ini_set('memory_limit', '1024M');

    $executionStartTime = microtime(true);

    $iss_url='https://api.wheretheiss.at/v1/satellites/25544';

    $iss_ch = curl_init();
    curl_setopt($iss_ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($iss_ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($iss_ch, CURLOPT_URL, $iss_url);

    $iss_result = curl_exec($iss_ch);

    curl_close($iss_ch);

    $iss_decode = json_decode($iss_result,true);
    $iss = null;
    $iss['lat'] = $iss_decode['latitude'];
    $iss['lng'] = $iss_decode['longitude'];

    $output['status']['code'] = "200";
    $output['status']['name'] = "ok";
    $output['status']['description'] = "success";
    $output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
    $output['iss'] = $iss;

    header("Content-Type: application/json; charset=UTF-8");

    echo json_encode($output);
?>