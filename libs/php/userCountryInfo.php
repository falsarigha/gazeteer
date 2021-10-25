<?php
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);
    ini_set('memory_limit', '1024M');

    $executionStartTime = microtime(true);

    //OpenCage Routine
    $oc_url='https://api.opencagedata.com/geocode/v1/json?q=' . $_REQUEST['lat'] . '+' . $_REQUEST['lng'] . '&key=c10468007be7424bb69d013e20efe738';

    $oc_ch = curl_init();
    curl_setopt($oc_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($oc_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($oc_ch, CURLOPT_URL, $oc_url);

    $oc_result = curl_exec($oc_ch);

    curl_close($oc_ch);

    $oc_decode = json_decode($oc_result,true);
    $openCage = null;
    $openCage['code'] = $oc_decode['results'][0]['components']['ISO_3166-1_alpha-3'];
    if (!isset($oc_decode['results'][0]['annotations']['currency'])) {
        $openCage['smallDenom'] = "N/A";
        $openCage['subunit'] = "N/A";
        $openCage['subToUnit'] = "N/A";
        $openCage['driveOn'] = $oc_decode['results'][0]['annotations']['roadinfo']['drive_on'];
        $openCage['speedIn'] = $oc_decode['results'][0]['annotations']['roadinfo']['speed_in'];
    } else {
        if (!isset($oc_decode['results'][0]['annotations']['currency']['smallest_denomination'])) {
            $openCage['smallDenom'] = " ";
        } else {
            $openCage['subToUnit'] = $oc_decode['results'][0]['annotations']['currency']['subunit_to_unit'];
            $openCage['driveOn'] = $oc_decode['results'][0]['annotations']['roadinfo']['drive_on'];
            $openCage['speedIn'] = $oc_decode['results'][0]['annotations']['roadinfo']['speed_in'];
            if (!isset($oc_decode['results'][0]['annotations']['currency']['subunit'])) {
                $openCage['subunit'] = "N/A";
            } else {
                $openCage['subunit'] = $oc_decode['results'][0]['annotations']['currency']['subunit'];
            }
        }
    }

    // RESTCountries Routine
    $rc_url='https://restcountries.eu/rest/v2/alpha/' . $openCage['code'];

	$rc_ch = curl_init();
    curl_setopt($rc_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($rc_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($rc_ch, CURLOPT_URL, $rc_url);

	$rc_result = curl_exec($rc_ch);

	curl_close($rc_ch);

    $rc_decode = json_decode($rc_result,true);
    $rest_countries = null;
    $rest_countries['iso2'] = $rc_decode['alpha2Code'];
    $rest_countries['capital'] = $rc_decode['capital'];
    $rest_countries['region'] = $rc_decode['region'];
    $rest_countries['subregion'] = $rc_decode['subregion'];
    $rest_countries['lat'] = $rc_decode['latlng'][0];
    $rest_countries['lng'] = $rc_decode['latlng'][1];
    $rest_countries['population'] = $rc_decode['population'];
    $rest_countries['demonym'] = $rc_decode['demonym'];
    $rest_countries['area'] = $rc_decode['area'];
    $rest_countries['callCode'] = $rc_decode['callingCodes'][0];
    $rest_countries['timezone'] = $rc_decode['timezones'][0];
    $rest_countries['currency'] = $rc_decode['currencies'][0];
    $rest_countries['flag'] = $rc_decode['flag'];
    $rest_countries['webDomain'] = $rc_decode['topLevelDomain'][0];

    //OpenWeather Routine
    $ow_api_key = 'cdab949d45e6ad36e58acb23d320ef18';

    $ow_url='https://api.openweathermap.org/data/2.5/weather?lat=' . $_REQUEST['lat'] . '&lon=' . $_REQUEST['lng'] . '&units=metric&appid=' . $ow_api_key;

    $ow_ch = curl_init();
    curl_setopt($ow_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ow_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ow_ch, CURLOPT_URL, $ow_url);

    $ow_result = curl_exec($ow_ch);

    curl_close($ow_ch);

    $ow_decode = json_decode($ow_result,true);
    $open_weather = null;
    $open_weather['main'] = $ow_decode['weather'][0]['main'];
    $open_weather['description'] = $ow_decode['weather'][0]['description'];
    $open_weather['icon'] = 'http://openweathermap.org/img/wn/' . $ow_decode['weather'][0]['icon'] . '@2x.png';
    $open_weather['feelsLike'] = $ow_decode['main']['feels_like'];
    $open_weather['min'] = $ow_decode['main']['temp_min'];
    $open_weather['max'] = $ow_decode['main']['temp_max'];
    $open_weather['pressure'] = $ow_decode['main']['pressure'];
    $open_weather['humidity'] = $ow_decode['main']['humidity'];
    $open_weather['windSpeed'] = $ow_decode['wind']['speed'];
    $open_weather['clouds'] = $ow_decode['clouds']['all'];

    //Exchange Rates Routine
    $er_url='https://v6.exchangerate-api.com/v6/0e25348ef7f09a5b28274858/latest/' . $rest_countries['currency']['code'];

    $er_ch = curl_init();
    curl_setopt($er_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($er_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($er_ch, CURLOPT_URL, $er_url);

    $er_result = curl_exec($er_ch);

    curl_close($er_ch);

    $er_decode = json_decode($er_result,true);
    $exchange_rates = null;
    if ($er_decode['result'] == "error") {
        $exchange_rates = "N/A";
    } else {
    $exchange_rates['rate'] = $er_decode['conversion_rates']['GBP'];
    }

    //GeoNames Country Info Routine
    $gn_url='http://api.geonames.org/countryInfoJSON?country=' . $rest_countries['iso2'] . '&maxRows=3&username=samw93';

    $gn_ch = curl_init();
    curl_setopt($gn_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($gn_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($gn_ch, CURLOPT_URL, $gn_url);

    $gn_result = curl_exec($gn_ch);

    curl_close($gn_ch);

    $gn_decode = json_decode($gn_result,true);
    $geonames_info = null;
    if ($gn_decode['geonames'][0]['countryName'] == "DR Congo") {
        $geonames_info['name'] = "Democratic Republic of the Congo";
        $geonames_info['north'] = $gn_decode['geonames'][0]['north'];
        $geonames_info['south'] = $gn_decode['geonames'][0]['south'];
        $geonames_info['east'] = $gn_decode['geonames'][0]['east'];
        $geonames_info['west'] = $gn_decode['geonames'][0]['west'];
     } else {
        $geonames_info['name'] = $gn_decode['geonames'][0]['countryName'];
        $geonames_info['north'] = $gn_decode['geonames'][0]['north'];
        $geonames_info['south'] = $gn_decode['geonames'][0]['south'];
        $geonames_info['east'] = $gn_decode['geonames'][0]['east'];
        $geonames_info['west'] = $gn_decode['geonames'][0]['west'];
     }
    
    // GeoNames Wiki Routine
    $gnw_url='http://api.geonames.org/wikipediaBoundingBoxJSON?north=' . $geonames_info['north'] . '&south=' . $geonames_info['south'] . '&east=' . $geonames_info['east'] . '&west=' . $geonames_info['west'] . '&maxRows=50&username=samw93';

    $gnw_ch = curl_init();
    curl_setopt($gnw_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($gnw_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($gnw_ch, CURLOPT_URL, $gnw_url);

    $gnw_result = curl_exec($gnw_ch);

    curl_close($gnw_ch);

    $gnw_decode = json_decode($gnw_result,true);
    $geonames_wiki = [];
    if (isset($gnw_decode['geonames'])) {
        foreach($gnw_decode['geonames'] as $obj) {
            if ($obj['title'] == $geonames_info['name'] || (str_contains($obj['title'], $geonames_info['name']) && (isset($obj['countryCode']) == $rest_countries['iso2'] || str_contains($obj['summary'], $geonames_info['name'])))) {
                $wiki = null;
                $wiki['title'] = $obj['title'];
                $wiki['wikiUrl'] = $obj['wikipediaUrl'];
                $wiki['wikiSummary'] = $obj['summary'];
                $geonames_wiki[] = [$wiki['title'], $wiki['wikiUrl'], $wiki['wikiSummary']];
            } else {
                $geonames_wiki = null;
            }
            
        }
    } else {
        $geonames_wiki = null;
    }

    // GeoNames Cities Routine
    $gnc_url='http://api.geonames.org/citiesJSON?north=' . $geonames_info['north'] . '&south=' . $geonames_info['south'] . '&east=' . $geonames_info['east'] . '&west=' . $geonames_info['west'] . '&lang=en&maxRows=20&username=samw93';

    $gnc_ch = curl_init();
    curl_setopt($gnc_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($gnc_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($gnc_ch, CURLOPT_URL, $gnc_url);

    $gnc_result = curl_exec($gnc_ch);

    curl_close($gnc_ch);

    $gnc_decode = json_decode($gnc_result,true);
    $geonames_cities = [];
    if (isset($gnc_decode['geonames']) || is_array($gnc_decode['geonames']) || is_object($gnc_decode['geonames'])) {
        foreach($gnc_decode['geonames'] as $obj) {
            if ($obj['countrycode'] == $rest_countries['iso2']) {
                $city = null;
                $city['name'] = $obj['name'];
                $city['population'] = $obj['population'];
                $city['wikipedia'] = $obj['wikipedia'];
                $city['lat'] = $obj['lat'];
                $city['lng'] = $obj['lng'];

                array_push($geonames_cities, $city);
            }
        }
    } else {
        $geonames_cities = null;
    }

    //Timezone Routine
    $tz_url='https://timezone.abstractapi.com/v1/current_time?api_key=10f6a0ab29b841cca8ada144c04e152d&location=' . $rest_countries['capital'] . ',' . $geonames_info['name'];

    $tz_ch = curl_init();
    curl_setopt($tz_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($tz_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($tz_ch, CURLOPT_URL, $tz_url);

    $tz_result = curl_exec($tz_ch);

    curl_close($tz_ch);

    $tz_decode = json_decode($tz_result,true);
    $timezone = null;
    if (!isset($tz_decode['datetime'])) {
        $timezone['datetime'] = null;
    } else {
        $timezone['datetime'] = $tz_decode['datetime'];
    }

    //News Routine
    $news_url='https://newsapi.org/v2/top-headlines?country=' . $rest_countries['iso2'] . '&apiKey=28a6da9206f946b78fe57038813fd730';

    $news_ch = curl_init();
    curl_setopt($news_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($news_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($news_ch, CURLOPT_URL, $news_url);

    $news_result = curl_exec($news_ch);

    curl_close($news_ch);

    $news_decode = json_decode($news_result,true);

    $news = null;
    if (!isset($news_decode['totalResults']) || $news_decode['status'] == "error") {
        $news = "N/A";
    } else {
        $news['firstTitle'] = $news_decode['articles'][0]['title'];
        $news['firstDescription'] = $news_decode['articles'][0]['description'];
        $news['firstUrl'] = $news_decode['articles'][0]['url'];
        $news['firstImageUrl'] = $news_decode['articles'][0]['urlToImage'];
        $news['firstSource'] = $news_decode['articles'][0]['source']['name'];
    
        $news['secondTitle'] = $news_decode['articles'][1]['title'];
        $news['secondDescription'] = $news_decode['articles'][1]['description'];
        $news['secondUrl'] = $news_decode['articles'][1]['url'];
        $news['secondImageUrl'] = $news_decode['articles'][1]['urlToImage'];
        $news['secondSource'] = $news_decode['articles'][1]['source']['name'];
    
        $news['thirdTitle'] = $news_decode['articles'][2]['title'];
        $news['thirdDescription'] = $news_decode['articles'][2]['description'];
        $news['thirdUrl'] = $news_decode['articles'][2]['url'];
        $news['thirdImageUrl'] = $news_decode['articles'][2]['urlToImage'];
        $news['thirdSource'] = $news_decode['articles'][2]['source']['name'];
    
        $news['fourthTitle'] = $news_decode['articles'][3]['title'];
        $news['fourthDescription'] = $news_decode['articles'][3]['description'];
        $news['fourthUrl'] = $news_decode['articles'][3]['url'];
        $news['fourthImageUrl'] = $news_decode['articles'][3]['urlToImage'];
        $news['fourthSource'] = $news_decode['articles'][3]['source']['name'];
    
        $news['fifthTitle'] = $news_decode['articles'][4]['title'];
        $news['fifthDescription'] = $news_decode['articles'][4]['description'];
        $news['fifthUrl'] = $news_decode['articles'][4]['url'];
        $news['fifthImageUrl'] = $news_decode['articles'][4]['urlToImage'];
        $news['fifthSource'] = $news_decode['articles'][4]['source']['name'];
    }

    //COVID-19 Routine
    $covid_ch = curl_init();

    curl_setopt_array($covid_ch, [
        CURLOPT_URL => "https://covid-19-data.p.rapidapi.com/country/code?code=" . $rest_countries['iso2'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: covid-19-data.p.rapidapi.com",
            "x-rapidapi-key: ee24dd5034msh9a2e078a6db28fdp1778c5jsn1d27ffeeae70"
        ],
    ]);

    $covid_result = curl_exec($covid_ch);

    curl_close($covid_ch);

    $covid_decode = json_decode($covid_result,true);
    $covid = null;
    $covid['confirmed'] = $covid_decode[0]['confirmed'];
    $covid['recovered'] = $covid_decode[0]['recovered'];
    $covid['critical'] = $covid_decode[0]['critical'];
    $covid['deaths'] = $covid_decode[0]['deaths'];

    // Get Country Border
    $geoJSON = json_decode(file_get_contents("../json/countryBorders.geo.json"), true);
    $geoJsonCountries = $geoJSON['features'];
    $countryBorder = null;

    foreach($geoJsonCountries as $country){
        if($country['properties']['iso_a2'] == $rest_countries['iso2']){
            $countryBorder = $country['geometry'];
        break;
        }
    }

    // Output
    $output['status']['code'] = "200";
    $output['status']['name'] = "ok";
    $output['status']['description'] = "success";
    $output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
    $output['restCountries'] = $rest_countries;
    $output['openWeather'] = $open_weather;
    $output['exchangeRates'] = $exchange_rates;
    $output['geoNames']['info'] = $geonames_info;
    $output['geoNames']['wiki'] = $geonames_wiki;
    $output['geoNames']['cities'] = $geonames_cities;
    $output['openCage'] = $openCage;
    $output['timezone'] = $timezone;
    $output['news'] = $news;
    $output['covid'] = $covid;
    $output['border'] = $countryBorder;
    
    header("Content-Type: application/json; charset=UTF-8");

    echo json_encode($output);
?>