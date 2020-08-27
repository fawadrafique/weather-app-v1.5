<?php
require __DIR__ . '/vendor/autoload.php';
$location = "Brussels";

use SKAgarwal\GoogleApi\PlacesApi;

$googlePlaces = new PlacesApi('AIzaSyBbIMyRDgay42Q3-F91m6fk36g9OJjgrk4');
$response = $googlePlaces->nearbySearch($location, $radius = null, $params = []);
echo $response;
$apikey = "94bc76131465087810a5fcee2f66defe";
$apiCall = "https://api.openweathermap.org/data/2.5/onecall?lat=50.85&lon=4.35&exclude=minutely,hourly&units=metric&appid=94bc76131465087810a5fcee2f66defe";
$data = json_decode(file_get_contents($apiCall));
//$data['lat']
$temp = round($data->current->temp);
$summary = $data->current->weather[0]->main;
$icon_id = $data->current->weather[0]->id;
$feelslike = $data->current->feels_like;
$uvindex = $data->current->uvi;
$humidity = $data->current->humidity;
$wind = round($data->current->wind_speed * 3.5997);



//echo $temp;
echo $wind;
var_dump($data);
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="./src/style/styles.css" />
    <link rel="stylesheet" href="./src/style/weather-icons.min.css">
    <title>Weather Forecast</title>
</head>


<body class="flex flex-col h-screen">
    <div class="flex-grow">
        <div class="w-full mt-16 lg:mt-56 lg:px-10 justify-center container mx-auto">

            <div class="flex flex-wrap w-full lg:w-auto opacity-90">
                <div class="w-full lg:w-1/2 flex bg-auto">
                    <div class="p-6 w-full bg-blue-400 text-white relative">
                        <div class="mx-auto relative z-10">
                            <button id="toggleMenu" class="relative z-10 block overflow-hidden pr-1 focus:outline-none">
                                <i class="fas fa-ellipsis-v text-gray-800 font-bold"></i>
                            </button>
                            <button id="clickOutside" tabindex="-1" class="fixed inset-0 h-full w-full cursor-default focus:outline-none"></button>
                            <div id="menuBox" class="absolute top-auto mt-1 py-2 w-72 bg-white rounded-lg shadow-xl hidden">
                                <form class="block px-4 py-2">
                                    <input id="inputField" class="p-2 border-t border-b border-l text-gray-800 border-gray-200 bg-white" type="text" placeholder="Search for a city..." placesearch />
                                    <button id="search" class="bg-yellow-400 text-gray-800 font-bold p-2 px-4 border-yellow-500 focus:outline-none"><i class="fas fa-search"></i>
                                    </button>
                                </form>
                                <div id="menuBox1" class="px-4 py-2 text-gray-800 font-bold flex justify-between items-center">
                                    <div class="font-normal">
                                        <span id="city1"></span>
                                        <span id="temp1" class="block">
                                        </span>
                                    </div>
                                    <div id="icon1">
                                    </div>
                                </div>
                                <div id="menuBox2" class="px-4 py-2 text-gray-800 font-bold flex justify-between  items-center">
                                    <div class="font-normal">
                                        <span id="city2"></span>
                                        <span id="temp2" class="block">

                                        </span>
                                    </div>
                                    <div id="icon2">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="mb-8 mt-6 text-center">
                            <h2 id="city" class="text-2xl inline-flex leading-none pb-1">Brussels, BE</h2>
                            <h3 id="day" class="leading-none pb-2 opacity-75">Thursday, 13 August 2020</h3>
                        </div>
                        <div class="flex justify-center mb-3">
                            <span id="summary" class="text-2xl">Sunny</span>
                        </div>
                        <div class="grid grid-cols-2 mb-3">
                            <div id="icon" class="flex justify-end text-6xl font-bold p-1 pr-3 border-r">
                                <i class="wi wi-day-sunny"></i>
                            </div>
                            <div class="flex justify-start p-1 pl-2">
                                <span id="temperature" class="leading-none text-6xl font-weight-bolder">29</span>
                                <span class="text-3xl font-weight-bolder align-top">ºC</span>
                            </div>
                        </div>
                        <div class="flex justify-center mb-12">
                            <span id="minmaxT" class="leading-none font-weight-bolder ">L <strong class="text-lg">29º</strong> - H
                                <strong class="text-lg">19</strong>º</span>
                        </div>
                        <div class="grid grid-flow-col grid-cols-3 grid-rows-2 md:grid-cols-6 md:grid-rows-1 gap-1">

                            <div class="bg-gray-900">
                                <div id="day1" class="text-center mt-1">
                                    <span class="font-normal block">Wed</span>
                                    <span class="flex justify-center text-3xl">
                                        <i class="wi wi-day-sunny"></i>
                                    </span>
                                    <span class="block">
                                        <span class="text-xs">L </span><span class="text-sm">29º - </span><span class="text-xs">H</span>
                                        <span class="text-sm">19º</span>
                                    </span>
                                </div>
                            </div>

                            <div class="bg-gray-900">
                                <div id="day2" class="text-center mt-1">
                                    <span class="font-normal block">Wed</span>
                                    <span class="flex justify-center text-3xl">
                                        <i class="wi wi-day-sunny"></i>
                                    </span>
                                    <span class="block">
                                        <span class="text-xs">L </span><span class="text-sm">29º - </span><span class="text-xs">H</span>
                                        <span class="text-sm">19º</span>
                                    </span>
                                </div>
                            </div>

                            <div class="bg-gray-900">
                                <div id="day3" class="text-center mt-1">
                                    <span class="font-normal block">Wed</span>
                                    <span class="flex justify-center text-3xl">
                                        <i class="wi wi-day-sunny"></i>
                                    </span>
                                    <span class="block">
                                        <span class="text-xs">L </span><span class="text-sm">29º - </span><span class="text-xs">H</span>
                                        <span class="text-sm">19º</span>
                                    </span>
                                </div>
                            </div>

                            <div class="bg-gray-900">
                                <div id="day4" class="text-center mt-1">
                                    <span class="font-normal block">Wed</span>
                                    <span class="flex justify-center text-3xl">
                                        <i class="wi wi-day-sunny"></i>
                                    </span>
                                    <span class="block">
                                        <span class="text-xs">L </span><span class="text-sm">29º - </span><span class="text-xs">H</span>
                                        <span class="text-sm">19º</span>
                                    </span>
                                </div>
                            </div>

                            <div class="bg-gray-900">
                                <div id="day5" class="text-center mt-1">
                                    <span class="font-normal block">Wed</span>
                                    <span class="flex justify-center text-3xl">
                                        <i class="wi wi-day-sunny"></i>
                                    </span>
                                    <span class="block">
                                        <span class="text-xs">L </span><span class="text-sm">29º - </span><span class="text-xs">H</span>
                                        <span class="text-sm">19º</span>
                                    </span>
                                </div>
                            </div>

                            <div class="bg-gray-900">
                                <div id="day6" class="text-center mt-1">
                                    <span class="font-normal block">Wed</span>
                                    <span class="flex justify-center text-3xl">
                                        <i class="wi wi-day-sunny"></i>
                                    </span>
                                    <span class="block">
                                        <span class="text-xs">L </span><span class="text-sm">29º - </span><span class="text-xs">H</span>
                                        <span class="text-sm">19º</span>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-1/2 flex ml-0">
                    <div class="lg:my-4 bg-gray-800 text-white p-8 w-full">
                        <canvas id="chart" class="mb-5"></canvas>
                        <div class="grid grid-cols-4 gap-4 mx-auto">
                            <div class="bg-gray-600 text-center p-5">
                                <i class="fas fa-thermometer-three-quarters fa-2x"></i>
                                <span class="block text-90">
                                    Real feel
                                </span>
                                <span id="feelslike">29º</span>
                            </div>
                            <div class="bg-gray-600 text-center p-5">
                                <i class="wi wi-day-sunny text-3xl"></i>
                                <span class="block text-90">
                                    UV index
                                </span>
                                <span id="uvindex" class="w-auto">5</span>
                            </div>
                            <div class="bg-gray-600 text-center p-5">
                                <i class="fas fa-humidity fa-2x"></i>
                                <span class="block text-90">
                                    Humidity
                                </span>
                                <span id="humidityPercent">29 %</span>
                            </div>
                            <div class="bg-gray-600 text-center p-5">
                                <i class="fas fa-wind fa-2x"></i>
                                <span class="block text-90">
                                    Wind
                                </span>
                                <span id="windSpeed">12 km/h</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="flex justify-between text-gray-900 px-5">
        <span id="footer"></span>
        <a href="https://github.com/fawadrafique/weather-app-v2">GitHub <i class="fab fa-github"> </i>
        </a>
    </footer>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBbIMyRDgay42Q3-F91m6fk36g9OJjgrk4&libraries=places">
    </script>
</body>

</html>