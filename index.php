<?php
//require __DIR__ . '/vendor/autoload.php';
////$location = "Brussels";

use SKAgarwal\GoogleApi\PlacesApi;

//$googlePlaces = new PlacesApi('AIzaSyBbIMyRDgay42Q3-F91m6fk36g9OJjgrk4');
//$response = $googlePlaces->nearbySearch($location, $radius = null, $params = []);
//echo $response;
$geocode_stats = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=" . $_POST["cityname"] . "&sensor=false&key=AIzaSyBbIMyRDgay42Q3-F91m6fk36g9OJjgrk4");

$output_deals = json_decode($geocode_stats);
//var_dump($output_deals);
$city = $output_deals->results[0]->address_components[0]->long_name;
$latLng = $output_deals->results[0]->geometry->location;
//var_dump($latLng);

$lat = $latLng->lat;
$lng = $latLng->lng;

$apikey = "94bc76131465087810a5fcee2f66defe";
$apiCall = "https://api.openweathermap.org/data/2.5/onecall?lat=" . $lat . "&lon=" . $lng . "&exclude=minutely&units=metric&appid=" . $apikey;
$data = json_decode(file_get_contents($apiCall));

$timestamp = $data->current->dt;
$hour = date("H", $timestamp);
$timestamp_sunrise = $data->current->sunrise;
$sunrise = date("H", $timestamp_sunrise);
$timestamp_sunset = $data->current->sunset;
$sunset = date("H", $timestamp_sunset);
$temp = round($data->current->temp);
$temp_min = round($data->daily[0]->temp->min);
$temp_max = round($data->daily[0]->temp->max);
$summary = $data->current->weather[0]->main;
$icon_id = $data->current->weather[0]->id;
$feelslike = $data->current->feels_like;
$uvindex = $data->current->uvi;
$humidity = $data->current->humidity;
$wind = round($data->current->wind_speed * 3.5997);

if ((int)$hour >= (int)$sunrise && (int)$hour < (int)$sunset) {
    $icon = "wi wi-owm-day-" . "$icon_id";
} else {
    $icon = "wi wi-owm-night-" . "$icon_id";
}
$time_forecast = [];
$temp_forecast = [];
$temp_min_forecast = [];
$temp_max_forecast = [];
$day_forecast = [];
$icon_forecast = [];

for ($i = 0; $i < 25; $i++) {
    $time_hourly = date("H:i", $data->hourly[$i]->dt);
    $temp_hourly = round($data->hourly[$i]->temp);
    array_push($time_forecast, $time_hourly);
    array_push($temp_forecast, $temp_hourly);

    $i += 3;
}
for ($i = 1; $i < 7; $i++) {
    array_push($day_forecast, date("D", $data->daily[$i]->dt));
    array_push($icon_forecast, $data->daily[$i]->weather[0]->id);
    array_push($temp_min_forecast, round($data->daily[$i]->temp->min));
    array_push($temp_max_forecast, round($data->daily[$i]->temp->max));
}
//var_dump($time_forecast);
//var_dump($temp_forecast);
//var_dump($day_forecast);
//var_dump($temp_min_forecast);
//var_dump($temp_max_forecast);



//echo $temp;
//echo $wind;
//var_dump($data);
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
                    <div class="p-6 w-full bg-blue-400 text-white">
                        <div id="searchBox" class="container mx-auto w-full">
                            <div class="flex justify-end ">
                                <form method="get" action="" class="mt-2 mb-4 flex w-full">
                                    <input id="inputField" class="w-full p-2 border-t border-b border-l text-gray-800 border-gray-200 bg-white focus:outline-none" type="text" name="cityname" placeholder="Search for a city..." placesearch />
                                    <button id="search" class="bg-yellow-400 text-gray-800 font-bold p-2 px-4 border-yellow-500 focus:outline-none"><i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="mb-8 mt-6 text-center">
                            <h2 id="city" class="text-2xl inline-flex leading-none pb-1"><?php echo $city ?></h2>
                            <h3 id="day" class="opacity-75 text-xs">Updated as of <?php echo date("h:i A", $timestamp); ?></h3>
                        </div>
                        <div class="flex justify-center mb-3">
                            <span id="summary" class="text-2xl"><?php echo $summary ?>
                            </span>
                        </div>
                        <div class="grid grid-cols-2 mb-3">
                            <div id="icon" class="flex justify-end text-6xl font-bold p-1 pr-3 border-r">
                                <i class="<?php echo $icon ?>"></i>
                            </div>
                            <div class="flex justify-start p-1 pl-2">
                                <span id="temperature" class="leading-none text-6xl font-weight-bolder"><?php echo $temp ?></span>
                                <span class="text-3xl font-weight-bolder align-top">ºC</span>
                            </div>
                        </div>
                        <div class="flex justify-center mb-12">
                            <span id="minmaxT" class="leading-none font-weight-bolder ">L <strong class="text-lg"><?php echo $temp_min ?>º</strong> - H
                                <strong class="text-lg"><?php echo $temp_max ?></strong>º</span>
                        </div>
                        <div class="grid grid-flow-col grid-cols-3 grid-rows-2 md:grid-cols-6 md:grid-rows-1 gap-1">

                            <div class="bg-gray-900">
                                <div id="day1" class="text-center mt-1">
                                    <span class="font-normal block"><?php echo $day_forecast[0]; ?></span>
                                    <span class="flex justify-center text-3xl">
                                        <i class="wi wi-owm-<?php echo $icon_forecast[0]; ?>"></i>
                                    </span>
                                    <span class="block">
                                        <span class="text-xs">L </span><span class="text-sm"><?php echo $temp_min_forecast[0]; ?>º - </span><span class="text-xs">H</span>
                                        <span class="text-sm"><?php echo $temp_max_forecast[0]; ?>º</span>
                                    </span>
                                </div>
                            </div>

                            <div class="bg-gray-900">
                                <div id="day2" class="text-center mt-1">
                                    <span class="font-normal block"><?php echo $day_forecast[1]; ?></span>
                                    <span class="flex justify-center text-3xl">
                                        <i class="wi wi-owm-<?php echo $icon_forecast[1]; ?>"></i>
                                    </span>
                                    <span class="block">
                                        <span class="text-xs">L </span><span class="text-sm"><?php echo $temp_min_forecast[1]; ?>º - </span><span class="text-xs">H</span>
                                        <span class="text-sm"><?php echo $temp_max_forecast[1]; ?>º</span>
                                    </span>
                                </div>
                            </div>

                            <div class="bg-gray-900">
                                <div id="day3" class="text-center mt-1">
                                    <span class="font-normal block"><?php echo $day_forecast[2]; ?></span>
                                    <span class="flex justify-center text-3xl">
                                        <i class="wi wi-owm-<?php echo $icon_forecast[2]; ?>"></i>
                                    </span>
                                    <span class="block">
                                        <span class="text-xs">L </span><span class="text-sm"><?php echo $temp_min_forecast[2]; ?>º - </span><span class="text-xs">H</span>
                                        <span class="text-sm"><?php echo $temp_max_forecast[2]; ?>º</span>
                                    </span>
                                </div>
                            </div>

                            <div class="bg-gray-900">
                                <div id="day4" class="text-center mt-1">
                                    <span class="font-normal block"><?php echo $day_forecast[3]; ?></span>
                                    <span class="flex justify-center text-3xl">
                                        <i class="wi wi-owm-<?php echo $icon_forecast[3]; ?>"></i>
                                    </span>
                                    <span class="block">
                                        <span class="text-xs">L </span><span class="text-sm"><?php echo $temp_min_forecast[3]; ?>º - </span><span class="text-xs">H</span>
                                        <span class="text-sm"><?php echo $temp_max_forecast[3]; ?>º</span>
                                    </span>
                                </div>
                            </div>

                            <div class="bg-gray-900">
                                <div id="day5" class="text-center mt-1">
                                    <span class="font-normal block"><?php echo $day_forecast[4]; ?></span>
                                    <span class="flex justify-center text-3xl">
                                        <i class="wi wi-owm-<?php echo $icon_forecast[4]; ?>"></i>
                                    </span>
                                    <span class="block">
                                        <span class="text-xs">L </span><span class="text-sm"><?php echo $temp_min_forecast[4]; ?>º - </span><span class="text-xs">H</span>
                                        <span class="text-sm"><?php echo $temp_max_forecast[4]; ?>º</span>
                                    </span>
                                </div>
                            </div>

                            <div class="bg-gray-900">
                                <div id="day6" class="text-center mt-1">
                                    <span class="font-normal block"><?php echo $day_forecast[5]; ?></span>
                                    <span class="flex justify-center text-3xl">
                                        <i class="wi wi-owm-<?php echo $icon_forecast[5]; ?>"></i>
                                    </span>
                                    <span class="block">
                                        <span class="text-xs">L </span><span class="text-sm"><?php echo $temp_min_forecast[5]; ?>º - </span><span class="text-xs">H</span>
                                        <span class="text-sm"><?php echo $temp_max_forecast[5]; ?>º</span>
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
                                <span id="feelslike"><?php echo $feelslike ?>º</span>
                            </div>
                            <div class="bg-gray-600 text-center p-5">
                                <i class="wi wi-day-sunny text-3xl"></i>
                                <span class="block text-90">
                                    UV index
                                </span>
                                <span id="uvindex" class="w-auto"><?php echo $uvindex ?></span>
                            </div>
                            <div class="bg-gray-600 text-center p-5">
                                <i class="fas fa-humidity fa-2x"></i>
                                <span class="block text-90">
                                    Humidity
                                </span>
                                <span id="humidityPercent"><?php echo $humidity ?> %</span>
                            </div>
                            <div class="bg-gray-600 text-center p-5">
                                <i class="fas fa-wind fa-2x"></i>
                                <span class="block text-90">
                                    Wind
                                </span>
                                <span id="windSpeed"><?php echo $wind ?> km/h</span>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBbIMyRDgay42Q3-F91m6fk36g9OJjgrk4&libraries=places">
    </script>
    <script>
        let cityAndCountry, longitude, latitude;
        const inputField = document.querySelector('#inputField');
        const search = document.querySelector('#search');
        const searchPlaces = new google.maps.places.SearchBox(inputField);
        searchPlaces.addListener('places_changed', () => {
            const place = searchPlaces.getPlaces()[0];
            let city = place.vicinity;
            place.address_components.forEach((component) => {
                if (component.types[0] === 'country') {
                    country = component.short_name;
                }
            });
            if (place == null) return;
            latitude = place.geometry.location.lat();
            longitude = place.geometry.location.lng();
            console.log(place);
            cityAndCountry = `${city}, ${country}`;
        });
        search.addEventListener('click', (e) => {
            e.preventDefault();
            inputField.value = '';
            let obj = {};
            obj.lon = longitude;
            obj.lat = latitude;
            obj.city = cityAndCountry;
            console.log(obj);
            // <?php $data = '<script>document.writeln(obj);</script>';
                // echo $data;
                // 
                ?>

            // fetch('location.php', {
            //         method: 'POST',
            //         headers: {
            //             'Accept': 'application/json',
            //             'Content-Type': 'application/json'
            //         },
            //         body: JSON.stringify(obj)
            //     })
            //     .then((res) => {
            //         res.json();
            //     })
            //     .then((data) => {
            //         console.log(data);
            //     });
            //const content = await rawResponse.json();

            //console.log(content);

        });
        let temp = <?php echo json_encode($temp_forecast); ?>,
            time = <?php echo json_encode($time_forecast); ?>,
            tMin = <?php echo (min($temp_forecast) - 5); ?>,
            tMax = <?php echo (max($temp_forecast) + 5); ?>;
        console.log(time)
        console.log(temp)
        console.log(tMin)
        console.log(tMax)

        let myChart = new Chart(chart, {
            type: 'line',
            data: {
                labels: time,
                datasets: [{
                    data: temp,
                    backgroundColor: '#718096',
                    borderColor: "#fff",
                    borderWidth: 2,
                    lineTension: 0,
                    pointBorderColor: "#fff",
                    pointBackgroundColor: "#718096",
                    pointRadius: 4,
                    pointBorderWidth: 2,
                    showTooltips: false
                }],

            },
            options: {
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            fontColor: '#fff'
                        },
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false,
                        },
                        ticks: {
                            display: false,
                            min: tMin,
                            max: tMax
                        }
                    }]
                },
                legend: {
                    display: false
                },
                tooltips: {
                    enabled: false
                },
                hover: {
                    mode: null
                },
                plugins: {
                    // Change options for ALL labels of THIS CHART
                    datalabels: {

                        color: '#fff',
                        align: 'top',
                        formatter: function(value) {
                            return value + 'º';
                        }
                    }

                }
            }
        })
    </script>

</body>

</html>