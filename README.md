# Gazetteer

## Summary:

The Gazetteer is an interactive geolocation map application, that works well across desktop, tablet and mobile devices. The Gazetteer provides the user with a variety of information on a selected country. This information includes demographic, climatic, geographical, currency exchange rate, Wikipedia, time and date, latest news and COVID-19 statistics. If permission is granted, the application also displays the users current location, as well as being able to track the International Space Station (ISS).

You can visit the live version [here.](https://gazetteer.samwelsh.co.uk/)

## Technologies used:

* HTML5.
* CSS3.
* SASS.
* Bootstrap.
* JavaScript and jQuery for DOM manipulation / AJAX.
* PHP / cURL.
* Leaflet.

## API's used:

* Geolocation API.
* REST Countries.
* OpenWeather.
* ExchangeRate-API.
* GeoNames.
* OpenCage.
* Abstract API - Time, Date, & Timezone API.
* News API.
* Rapid API - COVID-19 Data API.
* Where the ISS at?

## How the Gazetteer works:

1. Once the page has loaded, the user will be asked if it would like to allow the application to know their location.
2. If the user grants permission, a marker will be placed on their location, the map will adjust to the country's location on the map, the country will be bordered and highlighted, city markers will be displayed, and the information tables will be populated with information related to that country. If the user denies permission, the map will stay centered until the user selects a country.
3. The user can select a country from the dynamically populated select field in the top right of the screen. Again, the map will adjust to the country's location on the map, the country will be bordered and highlighted, and the information tables will be populated with information related to that country.
4. The user can change the map tiles by using the tile layers button in the top right of the screen.
5. In the top left of the screen, by clicking the easy buttons, the user can:
    * Zoom in/out.
    * Display the selected countries general information (flag, capital, population, related Wikipedia entries and much more).
    * Display the selected countries currency information.
    * Display the current weather for the selected country.
    * The five latest news articles from that country, if applicable.
    * The latest COVID-19 statistics for the selected country.
    * Track the ISS.

## Demonstration:

![Gazetteer](https://user-images.githubusercontent.com/69762070/110307806-3e12c800-7ff7-11eb-8b9b-9993ae59a0e3.gif)   
    
