var border;
var userLat;
var userLng;
var iso2 = null;
var userMarker;
var cityMarker;
var cities = L.featureGroup();
var singleCity;

//Display loader 
$(window).on("load", function () {
  $("#loader-container").hide();
  $("#loader").hide();
  
});

// Retrieve coordinates from the user
$(document).ready(() => {
  const successCallback = (position) => {
    userLat = position.coords.latitude;
    userLng = position.coords.longitude;
    
    
    $.ajax({
      url: "libs/php/infoUserData.php",
      type: "POST",
      dataType: "json",
      data: {
        lat: userLat,
        lng: userLng,
      },
      success: function (result) {
        console.log(result);

        if (result.status.name == "ok") {
          // Country Info
          console.log(result["geoNames"]["info"]["iso3"]);
          $('#selectCountry').val(result["geoNames"]["info"]["iso3"]).attr('selected', true);
          $(".countryName").html(result["geoNames"]["info"]["name"]);
          $(".region").html(result['geoNames']["info"]['regions']);
          $(".area").html(result['geoNames']["info"]['area'] + " km<sup>2</sup>");
          $(".population").html(result['geoNames']["info"]["population"]);
          $(".capital").html(result['geoNames']["info"]["capital"]);
          $(".timezone").html(result["openCage"]["timezone"]);
          $(".datetime").html(result["timezone"]['time']);
  
          if (result["timezone"]['time'] == null) {
            $(".datetime").html("Unable to retrieve local date & time.");
          } else {
            
            //timeformatted
            const dateTime = new Date(result["timezone"]['time']);
            const timeFormatted = dateTime.toLocaleString('en-GB', {timeZone: 'UTC'} );
            console.log(timeFormatted);
            $(".datetime").text(timeFormatted);
          }
          $(".coordinates").html(
            result["openCage"]["lat"] +
              " / " +
              result["openCage"]["lng"]
          );
          
          if (
            result["geoNames"]["wiki"] == null ||
            typeof result["geoNames"]["wiki"] == "undefined"
          ) {
            $(".wikiSummary").html("");
            $(".wikiUrl").attr("href", "");
            $(".wikiTitle").html("Unable to retrieve");
          } else {
            $(".wikiSummary").html(result["geoNames"]["wiki"][0]["wikiSummary"]);
            $(".wikiUrl").attr("href", `https://${result["geoNames"]["wiki"][0]["wikiUrl"]}`);
            $(".wikiTitle").html(result["geoNames"]["wiki"][0]["title"]);
            $(".wikiSummary1").html(result["geoNames"]["wiki"][1]["wikiSummary"]);
            $(".wikiUrl1").attr("href", `https://${result["geoNames"]["wiki"][1]["wikiUrl"]}`);
            $(".wikiTitle1").html(result["geoNames"]["wiki"][1]["title"]);
            $(".wikiSummary2").html(result["geoNames"]["wiki"][2]["wikiSummary"]);
            $(".wikiUrl2").attr("href", `https://${result["geoNames"]["wiki"][2]["wikiUrl"]}`);
            $(".wikiTitle2").html(result["geoNames"]["wiki"][2]["title"]);
            $(".wikiSummary3").html(result["geoNames"]["wiki"][3]["wikiSummary"]);
            $(".wikiUrl3").attr("href", `https://${result["geoNames"]["wiki"][3]["wikiUrl"]}`);
            $(".wikiTitle3").html(result["geoNames"]["wiki"][3]["title"]);
            $(".wikiSummary4").html(result["geoNames"]["wiki"][4]["wikiSummary"]);
            $(".wikiUrl4").attr("href", `https://${result["geoNames"]["wiki"][4]["wikiUrl"]}`);
            $(".wikiTitle4").html(result["geoNames"]["wiki"][4]["title"]);
          }
          
          // Weather Info
          $(".userLocation").html("your area");
          $(".weatherIcon").attr("src", result["openWeather"]["icon"]);
          $(".main").html(result["openWeather"]["main"]);
          $(".description").html(result["openWeather"]["description"]);
          $(".feelsLike").html(
            Math.floor(result["openWeather"]["feelsLike"]) + "&deg;C"
          );
          $(".max").html(Math.floor(result["openWeather"]["max"]) + "&deg;C");
   
          $(".humidity").html(result["openWeather"]["humidity"] + "%");
          
          $(".windSpeed").html(result["openWeather"]["windSpeed"] + " m/s");
          $(".pressure").html(result["openWeather"]["pressure"] + " hPa");

          //forecast
          const options = { weekday: 'long'};
          const event = new Date(result["forecast_weather"][0] * 1000);
          const dt = event.toLocaleDateString('en', options);
          const event1 = new Date(result["forecast_weather"][3] * 1000);
          const dt1 = event1.toLocaleDateString('en', options);
          const event2 = new Date(result["forecast_weather"][6] * 1000);
          const dt2 = event2.toLocaleDateString('en', options);
          const event3 = new Date(result["forecast_weather"][9] * 1000);
          const dt3 = event3.toLocaleDateString('en', options);
          const event4 = new Date(result["forecast_weather"][12] * 1000);
          const dt4 = event4.toLocaleDateString('en', options);
          const event5 = new Date(result["forecast_weather"][15] * 1000);
          const dt5 = event5.toLocaleDateString('en', options);
          const event6 = new Date(result["forecast_weather"][18] * 1000);
          const dt6 = event6.toLocaleDateString('en', options);
          //console.log(event.toLocaleDateString('en', options));
          
          $(".dt").text(dt);
          $(".dt1").text(dt1);
          $(".dt2").text(dt2);
          $(".dt3").text(dt3);
          $(".dt4").text(dt4);
          $(".dt5").text(dt5);
          $(".dt6").text(dt6);
          $(".mainFo").html(result["forecast_weather"][1]);
          $(".mainFo1").html(result["forecast_weather"][4]);
          $(".mainFo2").html(result["forecast_weather"][7]);
          $(".mainFo3").html(result["forecast_weather"][10]);
          $(".mainFo4").html(result["forecast_weather"][13]);
          $(".mainFo5").html(result["forecast_weather"][16]);
          $(".mainFo6").html(result["forecast_weather"][19]);
          $(".iconForecast").attr("src",`http://openweathermap.org/img/wn/${result["forecast_weather"][2]}.png`);
          $(".iconForecast1").attr("src",`http://openweathermap.org/img/wn/${result["forecast_weather"][5]}.png`);
          $(".iconForecast2").attr("src",`http://openweathermap.org/img/wn/${result["forecast_weather"][8]}.png`);
          $(".iconForecast3").attr("src",`http://openweathermap.org/img/wn/${result["forecast_weather"][11]}.png`);
          $(".iconForecast4").attr("src",`http://openweathermap.org/img/wn/${result["forecast_weather"][14]}.png`);
          $(".iconForecast5").attr("src",`http://openweathermap.org/img/wn/${result["forecast_weather"][17]}.png`);
          $(".iconForecast6").attr("src",`http://openweathermap.org/img/wn/${result["forecast_weather"][20]}.png`);


          
          
          // Latest News
          if (result.news == "N/A") {
            $(".firstNewsImage").attr("src", "");
            $(".firstNewsTitle").html(" ");
            $(".firstNewsDescription").html(
              "Unable to retrieve news from this country."
            );
            $(".firstNewsSource").html("N/A.");
            $(".firstNewsUrl").attr("href", "");

            $(".secondNewsImage").attr("src", "");
            $(".secondNewsTitle").html(" ");
            $(".secondNewsDescription").html(
              "Unable to retrieve news from this country."
            );
            $(".secondNewsSource").html("N/A.");
            $(".secondNewsUrl").attr("href", "");

            $(".thirdNewsImage").attr("src", "");
            $(".thirdNewsTitle").html(" ");
            $(".thirdNewsDescription").html(
              "Unable to retrieve news from this country."
            );
            $(".thirdNewsSource").html("N/A.");
            $(".thirdNewsUrl").attr("href", "");

            $(".fourthNewsImage").attr("src", "");
            $(".fourthNewsTitle").html(" ");
            $(".fourthNewsDescription").html(
              "Unable to retrieve news from this country."
            );
            $(".fourthNewsSource").html("N/A.");
            $(".fourthNewsUrl").attr("href", "");

            $(".fifthNewsImage").attr("src", "");
            $(".fifthNewsTitle").html(" ");
            $(".fifthNewsDescription").html(
              "Unable to retrieve news from this country."
            );
            $(".fifthNewsSource").html("N/A.");
            $(".fifthNewsUrl").attr("href", "");
          } else {
            $(".firstNewsImage").attr("src", result["news"]["firstImageUrl"]);
            $(".firstNewsUrl").attr("href", result["news"]["firstUrl"]);
            $(".firstNewsTitle").html(result["news"]["firstTitle"]);
            $(".firstNewsDescription").html(result["news"]["firstDescription"]);
            $(".firstNewsSource").html(result["news"]["firstSource"]);

            $(".secondNewsImage").attr("src", result["news"]["secondImageUrl"]);
            $(".secondNewsUrl").attr("href", result["news"]["secondUrl"]);
            $(".secondNewsTitle").html(result["news"]["secondTitle"]);
            $(".secondNewsDescription").html(
              result["news"]["secondDescription"]
            );
            $(".secondNewsSource").html(result["news"]["secondSource"]);

            $(".thirdNewsImage").attr("src", result["news"]["thirdImageUrl"]);
            $(".thirdNewsUrl").attr("href", result["news"]["thirdUrl"]);
            $(".thirdNewsTitle").html(result["news"]["thirdTitle"]);
            $(".thirdNewsDescription").html(result["news"]["thirdDescription"]);
            $(".thirdNewsSource").html(result["news"]["thirdSource"]);

            $(".fourthNewsImage").attr("src", result["news"]["fourthImageUrl"]);
            $(".fourthNewsUrl").attr("href", result["news"]["fourthUrl"]);
            $(".fourthNewsTitle").html(result["news"]["fourthTitle"]);
            $(".fourthNewsDescription").html(
              result["news"]["fourthDescription"]
            );
            $(".fourthNewsSource").html(result["news"]["fourthSource"]);

            $(".fifthNewsImage").attr("src", result["news"]["fifthImageUrl"]);
            $(".fifthNewsUrl").attr("href", result["news"]["fifthUrl"]);
            $(".fifthNewsTitle").html(result["news"]["fifthTitle"]);
            $(".fifthNewsDescription").html(result["news"]["fifthDescription"]);
            $(".fifthNewsSource").html(result["news"]["fifthSource"]);
          }

          if (mymap.hasLayer(border)) {
            mymap.removeLayer(border);
          }

          border = L.geoJSON(result["border"], {
            style: function (feature) {
              return { color: "#7fcdbb" };
            },
          }).addTo(mymap);
          mymap.fitBounds(border.getBounds());

          cities.eachLayer(function (layer) {
            cities.removeLayer(layer);
          });

          if (result["geoNames"]["cities"] != null) {
            result["geoNames"]["cities"].forEach((city) => {
              cityMarker = L.marker([city.lat, city.lng], {
                icon: cityIcon,
              }).bindPopup(
                `<b class="popupTitle">${city.name}</b><br>Population: ${Number(
                  city.population
                ).toLocaleString("en")}<br><a target=_blank href="https://${
                  city.wikipedia
                }">Wikipedia</a>`
              );

              cities.addLayer(cityMarker).addTo(mymap);
            });
          }

          $("#loader-container").hide();
          $("#loader").hide();
        }
      },
      error: function (request, status, error) {
        console.log(error, status, request);
        $("#loader-container").hide();
        $("#loader").hide();
      },
    });
  };

  const errorCallback = (error) => {
    console.log("Unable to retrieve your location");

    if (!navigator.geolocation) {
      console.log("Geolocation is not supported by your browser");
    }
  };

  navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {
    enableHighAccuracy: true,
  });
});

// Map layer

var OpenStreetMap_Mapnik = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
  {
    maxZoom: 19,
    attribution:
      '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  });

var OPNVKarte = L.tileLayer('https://tileserver.memomaps.de/tilegen/{z}/{x}/{y}.png', 
{
	maxZoom: 119,
	attribution: 'Map <a href="https://memomaps.de/">memomaps.de</a> <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
});

var OpenTopoMap = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
 {
	maxZoom: 17,
	attribution: 'Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'
});

// Icon
var userIcon = L.icon({
  iconUrl: "./img/map-markers.png",
  iconSize: [50, 50],
  iconAnchor: [25, 25],
});

var cityIcon = L.icon({
  iconUrl: "./img/location.png",
  iconSize: [50, 50],
  iconAnchor: [25, 25],
});

// Create map
var mymap = L.map("mapid", {
  attributionControl: false,
  center: [0, 0],
  zoom: 3,
  layers: [OpenStreetMap_Mapnik],
});


var baseMaps = {
  "Streets Map": OpenStreetMap_Mapnik,
  "Topographic Map ": OpenTopoMap,
  "Aereoport Map": OPNVKarte
  
};

L.control.layers(baseMaps).addTo(mymap);

//EasyButtons
L.easyButton(
  "<i class='fas fa-info-circle'></i>",
  function () {
    $("#countryInfo").modal("toggle");
  },
  "Country Information"
).addTo(mymap);

L.easyButton(
  "<i class='fab fa-wikipedia-w'></i>",
  function () {
    $("#wikiInfo").modal("toggle");
  },
  "Wikipedia Information"
).addTo(mymap);

L.easyButton(
  "<i class='fas fa-cloud-sun-rain'></i>",
  function () {
    $("#weatherInfo").modal("toggle");
  },
  "Weather Information"
).addTo(mymap);

L.easyButton(
  "<i class='fas fa-newspaper'></i>",
  function () {
    $("#latestNews").modal("toggle");
  },
  "Latest News"
).addTo(mymap);

// Populate select field
$.ajax({
  url: "libs/php/populateSelect.php",
  type: "POST",
  dataType: "json",
  success: function (result) {

    $.each(result.data, function (index) {
      $("#selectCountry").append(
        $("<option>", {
          value: result.data[index].iso3,
          text: result.data[index].name,
          
        }),
      );
    });
  },
  error: function (request, status, error) {
    console.log(error);
  },
});

// Design the border
$("#selectCountry").change(function () {
  $("#loader-container").show();
  $("#loader").show();

  $.ajax({
    url: "libs/php/countryBorder.php",
    type: "POST",
    dataType: "json",
    data: {
      code: $("#selectCountry").val(),
    },
    success: function (result) {
      console.log(result);

      if (mymap.hasLayer(border)) {
        mymap.removeLayer(border);
      }

      border = L.geoJSON(result["border"], {
        style: function (feature) {
          return { color: "#7fcdbb" };
        },
      }).addTo(mymap);
      mymap.fitBounds(border.getBounds());
    },
    error: function (request, status, error) {
      console.log(error);
    },
  });
  // Retrieve data for each country
  $.ajax({
    url: "libs/php/getData.php",
    type: "POST",
    dataType: "json",
    data: {
      code: $(this).find('option:selected').text()
    },
    success: function (result) {
      console.log(result);
       
      if (result.status.name == "ok") {
        if (mymap.hasLayer(singleCity)) {
          mymap.removeLayer(singleCity);
        }

        if (mymap.hasLayer(userMarker)) {
          mymap.removeLayer(userMarker);
        }

        cities.eachLayer(function (layer) {
          cities.removeLayer(layer);
        });

        if (result["geoNames"]["cities"] != null) {
          result["geoNames"]["cities"].forEach((city) => {
            cityMarker = L.marker([city.lat, city.lng], {
              icon: cityIcon,
            }).bindPopup(
              `<b class="popupTitle">${city.name}</b><br>Population: ${Number(
                city.population
              ).toLocaleString("en")}<br><a target=_blank href="https://${
                city.wikipedia
              }">Wikipedia</a>`
            );

            cities.addLayer(cityMarker).addTo(mymap);
          });
        } else {
          singleCity = L.marker(
            [result["openCage"]["lat"], result["openCage"]["lng"]],
            {
              icon: cityIcon,
            }
          );
          singleCity.addTo(mymap);
        }

        // Country Info
        $(".countryFlag").html(result["flag"]);
        $(".countryName").html(result["geoNames"]["info"]["name"]);
        $(".region").html(result['geoNames']["info"]['regions']);
        $(".area").html(result['geoNames']["info"]['area'] + " km<sup>2</sup>");
        $(".population").html(result['geoNames']["info"]["population"]);
        $(".capital").html(result['geoNames']["info"]["capital"]);
        $(".timezone").html(result["openCage"]["timezone"]);
        $(".datetime").html(result["timezone"]['time']);

        if (result["timezone"]['time'] == null) {
          $(".datetime").html("Unable to retrieve local date & time.");
        } else {
          
          //timeformatted
          const dateTime = new Date(result["timezone"]['time']);
          const timeFormatted = dateTime.toLocaleString('en-GB', {timeZone: 'UTC'} );
          console.log(timeFormatted);
          $(".datetime").text(timeFormatted);
        }
        $(".coordinates").html(
          result["openCage"]["lat"] +
            " / " +
            result["openCage"]["lng"]
        );
        
        if (
          result["geoNames"]["wiki"] == null ||
          typeof result["geoNames"]["wiki"] == "undefined"
        ) {
          $(".wikiSummary").html("");
          $(".wikiUrl").attr("href", "");
          $(".wikiTitle").html("Unable to retrieve");
        } else {
          $(".wikiSummary").html(result["geoNames"]["wiki"][0]["wikiSummary"]);
            $(".wikiUrl").attr("href", `https://${result["geoNames"]["wiki"][0]["wikiUrl"]}`);
            $(".wikiTitle").html(result["geoNames"]["wiki"][0]["title"]);
            $(".wikiSummary1").html(result["geoNames"]["wiki"][1]["wikiSummary"]);
            $(".wikiUrl1").attr("href", `https://${result["geoNames"]["wiki"][1]["wikiUrl"]}`);
            $(".wikiTitle1").html(result["geoNames"]["wiki"][1]["title"]);
            $(".wikiSummary2").html(result["geoNames"]["wiki"][2]["wikiSummary"]);
            $(".wikiUrl2").attr("href", `https://${result["geoNames"]["wiki"][2]["wikiUrl"]}`);
            $(".wikiTitle2").html(result["geoNames"]["wiki"][2]["title"]);
            $(".wikiSummary3").html(result["geoNames"]["wiki"][3]["wikiSummary"]);
            $(".wikiUrl3").attr("href", `https://${result["geoNames"]["wiki"][3]["wikiUrl"]}`);
            $(".wikiTitle3").html(result["geoNames"]["wiki"][3]["title"]);
            $(".wikiSummary4").html(result["geoNames"]["wiki"][4]["wikiSummary"]);
            $(".wikiUrl4").attr("href", `https://${result["geoNames"]["wiki"][4]["wikiUrl"]}`);
            $(".wikiTitle4").html(result["geoNames"]["wiki"][4]["title"]);
        }

        // Weather Info
        $(".weatherIcon").attr("src", result["openWeather"]["icon"]);
        $(".main").html(result["openWeather"]["main"]);
        $(".description").html(result["openWeather"]["description"]);
        $(".feelsLike").html(
          Math.floor(result["openWeather"]["feelsLike"]) + "&deg;C"
        );
        $(".max").html(Math.floor(result["openWeather"]["max"]) + "&deg;C");
        $(".min").html(Math.floor(result["openWeather"]["min"]) + "&deg;C");
        $(".humidity").html(result["openWeather"]["humidity"] + "%");
        $(".clouds").html(result["openWeather"]["clouds"] + "%");
        $(".windSpeed").html(result["openWeather"]["windSpeed"] + " m/s");
        $(".pressure").html(result["openWeather"]["pressure"] + " hPa");

        //forecast
        const options = { weekday: 'long'};
        const event = new Date(result["forecast_weather"][0] * 1000);
        const dt = event.toLocaleDateString('en', options);
        const event1 = new Date(result["forecast_weather"][3] * 1000);
        const dt1 = event1.toLocaleDateString('en', options);
        const event2 = new Date(result["forecast_weather"][6] * 1000);
        const dt2 = event2.toLocaleDateString('en', options);
        const event3 = new Date(result["forecast_weather"][9] * 1000);
        const dt3 = event3.toLocaleDateString('en', options);
        const event4 = new Date(result["forecast_weather"][12] * 1000);
        const dt4 = event4.toLocaleDateString('en', options);
        const event5 = new Date(result["forecast_weather"][15] * 1000);
        const dt5 = event5.toLocaleDateString('en', options);
        const event6 = new Date(result["forecast_weather"][18] * 1000);
        const dt6 = event6.toLocaleDateString('en', options);
        //console.log(event.toLocaleDateString('en', options));
        
        $(".dt").text(dt);
        $(".dt1").text(dt1);
        $(".dt2").text(dt2);
        $(".dt3").text(dt3);
        $(".dt4").text(dt4);
        $(".dt5").text(dt5);
        $(".dt6").text(dt6);
        $(".mainFo").html(result["forecast_weather"][1]);
        $(".mainFo1").html(result["forecast_weather"][4]);
        $(".mainFo2").html(result["forecast_weather"][7]);
        $(".mainFo3").html(result["forecast_weather"][10]);
        $(".mainFo4").html(result["forecast_weather"][13]);
        $(".mainFo5").html(result["forecast_weather"][16]);
        $(".mainFo6").html(result["forecast_weather"][19]);
        $(".iconForecast").attr("src",`http://openweathermap.org/img/wn/${result["forecast_weather"][2]}.png`);
        $(".iconForecast1").attr("src",`http://openweathermap.org/img/wn/${result["forecast_weather"][5]}.png`);
        $(".iconForecast2").attr("src",`http://openweathermap.org/img/wn/${result["forecast_weather"][8]}.png`);
        $(".iconForecast3").attr("src",`http://openweathermap.org/img/wn/${result["forecast_weather"][11]}.png`);
        $(".iconForecast4").attr("src",`http://openweathermap.org/img/wn/${result["forecast_weather"][14]}.png`);
        $(".iconForecast5").attr("src",`http://openweathermap.org/img/wn/${result["forecast_weather"][17]}.png`);
        $(".iconForecast6").attr("src",`http://openweathermap.org/img/wn/${result["forecast_weather"][20]}.png`);


        // Latest News
        if (result.news == "N/A") {
          $(".firstNewsImage").attr("src", "");
          $(".firstNewsTitle").html(" ");
          $(".firstNewsDescription").html(
            "Unable to retrieve news from this country."
          );
          $(".firstNewsSource").html("N/A.");
          $(".firstNewsUrl").attr("href", "");

          $(".secondNewsImage").attr("src", "");
          $(".secondNewsTitle").html(" ");
          $(".secondNewsDescription").html(
            "Unable to retrieve news from this country."
          );
          $(".secondNewsSource").html("N/A.");
          $(".secondNewsUrl").attr("href", "");

          $(".thirdNewsImage").attr("src", "");
          $(".thirdNewsTitle").html(" ");
          $(".thirdNewsDescription").html(
            "Unable to retrieve news from this country."
          );
          $(".thirdNewsSource").html("N/A.");
          $(".thirdNewsUrl").attr("href", "");

          $(".fourthNewsImage").attr("src", "");
          $(".fourthNewsTitle").html(" ");
          $(".fourthNewsDescription").html(
            "Unable to retrieve news from this country."
          );
          $(".fourthNewsSource").html("N/A.");
          $(".fourthNewsUrl").attr("href", "");

          $(".fifthNewsImage").attr("src", "");
          $(".fifthNewsTitle").html(" ");
          $(".fifthNewsDescription").html(
            "Unable to retrieve news from this country."
          );
          $(".fifthNewsSource").html("N/A.");
          $(".fifthNewsUrl").attr("href", "");
        } else {
          $(".firstNewsImage").attr("src", result["news"]["firstImageUrl"]);
          $(".firstNewsUrl").attr("href", result["news"]["firstUrl"]);
          $(".firstNewsTitle").html(result["news"]["firstTitle"]);
          $(".firstNewsDescription").html(result["news"]["firstDescription"]);
          $(".firstNewsSource").html(result["news"]["firstSource"]);

          $(".secondNewsImage").attr("src", result["news"]["secondImageUrl"]);
          $(".secondNewsUrl").attr("href", result["news"]["secondUrl"]);
          $(".secondNewsTitle").html(result["news"]["secondTitle"]);
          $(".secondNewsDescription").html(result["news"]["secondDescription"]);
          $(".secondNewsSource").html(result["news"]["secondSource"]);

          $(".thirdNewsImage").attr("src", result["news"]["thirdImageUrl"]);
          $(".thirdNewsUrl").attr("href", result["news"]["thirdUrl"]);
          $(".thirdNewsTitle").html(result["news"]["thirdTitle"]);
          $(".thirdNewsDescription").html(result["news"]["thirdDescription"]);
          $(".thirdNewsSource").html(result["news"]["thirdSource"]);

          $(".fourthNewsImage").attr("src", result["news"]["fourthImageUrl"]);
          $(".fourthNewsUrl").attr("href", result["news"]["fourthUrl"]);
          $(".fourthNewsTitle").html(result["news"]["fourthTitle"]);
          $(".fourthNewsDescription").html(result["news"]["fourthDescription"]);
          $(".fourthNewsSource").html(result["news"]["fourthSource"]);

          $(".fifthNewsImage").attr("src", result["news"]["fifthImageUrl"]);
          $(".fifthNewsUrl").attr("href", result["news"]["fifthUrl"]);
          $(".fifthNewsTitle").html(result["news"]["fifthTitle"]);
          $(".fifthNewsDescription").html(result["news"]["fifthDescription"]);
          $(".fifthNewsSource").html(result["news"]["fifthSource"]);
        }

        $("#loader-container").hide();
        $("#loader").hide();
      }
    },
    error: function (request, status, error) {
      console.log(error,status,request);
      $("#loader-container").hide();
      $("#loader").hide();
    },
  });
});
