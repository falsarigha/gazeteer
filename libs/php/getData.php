<?php
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);
    ini_set('memory_limit', '1024M');

    $executionStartTime = microtime(true);

    // Opencage API
    $rc_url = 'https://api.opencagedata.com/geocode/v1/json?q='. $_REQUEST['code'] .'&key=5df3b1b2e1e445c5b3324e85c816cbd0';
    //$rc_url = 'https://api.opencagedata.com/geocode/v1/json?q=it&key=5df3b1b2e1e445c5b3324e85c816cbd0';
	
    $rc_ch = curl_init();
    curl_setopt($rc_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($rc_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($rc_ch, CURLOPT_URL, $rc_url);

	$rc_result = curl_exec($rc_ch);

	curl_close($rc_ch);

    $rc_decode = json_decode($rc_result,true);
    $open_Cage = null;

    $open_Cage['iso2'] = $rc_decode['results'][0]['components']['ISO_3166-1_alpha-2'];
    $open_Cage['lat'] = $rc_decode['results'][0]['geometry']['lat'];
    $open_Cage['lng'] = $rc_decode['results'][0]['geometry']['lng'];;
    $open_Cage['timezone'] = $rc_decode['results'][0]['annotations']['timezone']['name'];
    $open_Cage['currency'] = $rc_decode['results'][0]['annotations']['currency']['name'];
    $open_Cage['flag'] = $rc_decode['results'][0]['annotations']['flag'];
    

    //flag API
    $fl_url = 'https://flagcdn.com/16x12/'. $open_Cage['iso2'] .'.png';

    $fl_ch = curl_init();

    curl_setopt($fl_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($fl_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($fl_ch, CURLOPT_URL, $fl_url);

    $fl_result = curl_exec($fl_ch);

    curl_close($fl_ch);

    $fl_decode = json_decode($fl_result);


    //OpenWeather API
    
    $ow_url='https://api.openweathermap.org/data/2.5/onecall?lat=' . $open_Cage['lat'] . '&lon=' . $open_Cage['lng'] . '&units=metric&appid=e1c3308c9a1b6fff63552f064569a9a8';
    $ow_ch = curl_init();
    curl_setopt($ow_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ow_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ow_ch, CURLOPT_URL, $ow_url);

    $ow_result = curl_exec($ow_ch);

    curl_close($ow_ch);

    $ow_decode = json_decode($ow_result,true);
    $open_weather = null;
    $open_weather['main'] = $ow_decode['current']['weather'][0]['main'];
    $open_weather['description'] = $ow_decode['current']['weather'][0]['description'];
    $open_weather['icon'] = 'http://openweathermap.org/img/wn/' . $ow_decode['current']['weather'][0]['icon'] . '@2x.png';
    $open_weather['feelsLike'] = $ow_decode['current']['feels_like'];
    
    $open_weather['max'] = $ow_decode['current']['temp'];
    $open_weather['pressure'] = $ow_decode['current']['pressure'];
    $open_weather['humidity'] = $ow_decode['current']['humidity'];
    $open_weather['windSpeed'] = $ow_decode['current']['wind_speed'];

    $forecast_weather = [];
    foreach($ow_decode['daily'] as $dt)
    {     
        
        $dt['dt'];
        
        foreach($dt['weather'] as $weather)
        {
            $weather['main'];
            'http://openweathermap.org/img/wn/' . $weather['icon'] . '@2x.png';

        }
        array_push($forecast_weather, $dt['dt'], $weather['main'], $weather['icon']);
        
       

    }

    //GeoNamesCountryInfo API
    $gn_url='http://api.geonames.org/countryInfoJSON?country=' . $open_Cage['iso2'] . '&maxRows=3&username=falsarigha';

    $gn_ch = curl_init();
    curl_setopt($gn_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($gn_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($gn_ch, CURLOPT_URL, $gn_url);

    $gn_result = curl_exec($gn_ch);

    curl_close($gn_ch);

    $gn_decode = json_decode($gn_result,true);
    $geonames_info = null;

    $geonames_info['regions'] = $gn_decode['geonames'][0]['continentName'];
    $geonames_info['area'] = $gn_decode['geonames'][0]['areaInSqKm'];
    $geonames_info['population'] = $gn_decode['geonames'][0]['population'];
    $geonames_info['capital'] = $gn_decode['geonames'][0]['capital'];
    
    
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
    
    // GeoNamesWiki API
    $gnw_url='http://api.geonames.org/wikipediaBoundingBoxJSON?north=' . $geonames_info['north'] . '&south=' . $geonames_info['south'] . '&east=' . $geonames_info['east'] . '&west=' . $geonames_info['west'] . '&maxRows=5&username=falsarigha';

    $gnw_ch = curl_init();
    curl_setopt($gnw_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($gnw_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($gnw_ch, CURLOPT_URL, $gnw_url);

    $gnw_result = curl_exec($gnw_ch);

    curl_close($gnw_ch);

    $gnw_decode = json_decode($gnw_result,true);
    $geonames_wiki = [];
    //wikipedia entry
    foreach($gnw_decode['geonames'] as $obj)
    {   
        $wiki = null;
        $wiki['title'] = $obj['title'];
        $wiki['wikiSummary'] = $obj['summary'];
        $wiki['wikiUrl'] = $obj['wikipediaUrl'];

        array_push($geonames_wiki, $wiki);
    }

        
    // GeoNames Cities API
    $gnc_url='http://api.geonames.org/citiesJSON?north=' . $geonames_info['north'] . '&south=' . $geonames_info['south'] . '&east=' . $geonames_info['east'] . '&west=' . $geonames_info['west'] . '&lang=en&maxRows=20&username=falsarigha';

    $gnc_ch = curl_init();
    curl_setopt($gnc_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($gnc_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($gnc_ch, CURLOPT_URL, $gnc_url);

    $gnc_result = curl_exec($gnc_ch);

    curl_close($gnc_ch);

    $gnc_decode = json_decode($gnc_result,true);
    $geonames_cities = [];
    if (isset($gnc_decode['geonames'])) {
        foreach($gnc_decode['geonames'] as $obj) {
            if ($obj['countrycode'] == $open_Cage['iso2']) {
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

    //Timezone API
    $tz_url= 'http://api.geonames.org/timezoneJSON?lat=' .$open_Cage['lat'] . '&lng=' .$open_Cage['lng'] . '&username=falsarigha';

    $tz_ch = curl_init();
    curl_setopt($tz_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($tz_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($tz_ch, CURLOPT_URL, $tz_url);

    $tz_result = curl_exec($tz_ch);

    curl_close($tz_ch);

    $tz_decode = json_decode($tz_result,true);
    $timezone = null;
    
    $timezone['time'] = $tz_decode['time'];

    //News API
    $news_url='https://newsapi.org/v2/top-headlines?country=' . $open_Cage['iso2'] . '&apiKey=fc97ffca4a3f4fdfa287d243c795702a';

    $news_ch = curl_init();
    curl_setopt($news_ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($news_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($news_ch, CURLOPT_URL, $news_url);

    $news_result = curl_exec($news_ch);

    curl_close($news_ch);

    $news_decode = json_decode($news_result,true);
    $news = null;
    if (!isset($news_decode['totalResults']) || $news_decode['status'] == "error" || $news_decode['totalResults'] == 0) {
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

    // Output
    $output['status']['code'] = "200";
    $output['status']['name'] = "ok";
    $output['status']['description'] = "success";
    $output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
    $output['openCage'] = $open_Cage;
    $output['openWeather'] = $open_weather;
    $output['geoNames']['info'] = $geonames_info;
    $output['geoNames']['wiki'] = $geonames_wiki;
    $output['geoNames']['cities'] = $geonames_cities;
    $output['news'] = $news;
    $output['flag'] =$fl_decode;
    $output['timezone'] = $timezone;
    
   
    
    header("Content-Type: application/json; charset=UTF-8");
    header('permissions-policy: interest-cohort=()');

    echo json_encode($output);
?>