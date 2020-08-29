<!-- PHP script begin-->
<?php

// Conditional statement to check if POST empty or not
// If POST is not empty, get longitude and latitute of the city using Google Geocode API
// If POST is empty, set default city Brussels and its longitude and latitute.
if (isset($_POST['cityname']) && !empty($_POST['cityname'])) {
    $location = str_replace(' ', '', $_POST["cityname"]);
    $google_api = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $location . "&sensor=false&key=AIzaSyBbIMyRDgay42Q3-F91m6fk36g9OJjgrk4";
    $output_deals = json_decode(file_get_contents($google_api));
    $city = $output_deals->results[0]->address_components[0]->long_name;
    $latLng = $output_deals->results[0]->geometry->location;
    $lat = $latLng->lat;
    $lng = $latLng->lng;
} else {
    $lat = 50.85;
    $lng = 4.35;
    $city = 'Brussels';
}

// Get weather forecast of the city using OpenWeatherMap API.
// Get the random image of the city using Unsplash API, also get source link and name of iamge uploader.
$owm_apiCall = "https://api.openweathermap.org/data/2.5/onecall?lat=" . $lat . "&lon=" . $lng . "&exclude=minutely&units=metric&appid=94bc76131465087810a5fcee2f66defe";
$data = json_decode(file_get_contents($owm_apiCall));
$unsplash_apiCall = "https://api.unsplash.com/search/photos?page=1&query=" . $city . "&order_by=popular&orientation=landscape&client_id=jRPGWBPEGuRHvLEve0t7QHqzx0a7NsWSv_FY-atuTWs";
$data_unsplash = json_decode(file_get_contents($unsplash_apiCall));
$rand = rand(0, 9);
$background = $data_unsplash->results[$rand]->urls->regular;
$user_name = $data_unsplash->results[$rand]->user->name;
$image_link = $data_unsplash->results[$rand]->links->html;

// Get weather data
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

//Check for day or night icon
if ((int)$hour >= (int)$sunrise && (int)$hour < (int)$sunset) {
    $icon = "wi wi-owm-day-" . "$icon_id";
} else {
    $icon = "wi wi-owm-night-" . "$icon_id";
}
// Weather forecast of next 6 days and save that to an array
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

?>
<!-- PHP script end -->


<!DOCTYPE html>
<html lang="en">
<!-- Link of stylesheet and weather icons -->

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="./src/style/styles.css" />
    <link rel="stylesheet" href="./src/style/weather-icons.min.css">
    <title>Weather Station</title>
</head>

<!-- HTML main body tag -->

<body class="flex flex-col h-screen">
    <div class="flex-grow">
        <div class="w-full mt-16 lg:mt-56 lg:px-10 justify-center container mx-auto">
            <!-- Weather app wr container -->
            <div class="flex flex-wrap w-full lg:w-auto opacity-90">
                <div class="w-full lg:w-1/2 flex bg-auto">
                    <div class="p-6 w-full bg-blue-400 text-white">
                        <!-- City search input field form -->
                        <div class="container mx-auto w-full">
                            <div class="flex justify-end ">
                                <form method="post" action="" class="mt-2 mb-4 flex w-full">
                                    <input id="inputField" class="w-full p-2 border-t border-b border-l text-gray-800 border-gray-200 bg-white focus:outline-none" type="text" name="cityname" placeholder="Search for a city..." />
                                    <input type="submit" class="bg-yellow-400 text-gray-800 font-bold p-2 px-4 border-yellow-500 focus:outline-none">
                                    </input>
                                </form>
                            </div>
                        </div>
                        <!-- City name and time of weather report -->
                        <div class="mb-8 mt-6 text-center">
                            <h2 class="text-2xl inline-flex leading-none pb-1"><?php echo $city ?></h2>
                            <h3 class="opacity-75 text-xs">Updated as of <?php echo date("h:i A", $timestamp); ?></h3>
                        </div>
                        <!-- Summary of the current weather -->
                        <div class="flex justify-center mb-3">
                            <span class="text-2xl"><?php echo $summary ?>
                            </span>
                        </div>
                        <!-- Temperature status and icon of the current summary -->
                        <div class="grid grid-cols-2 mb-3">
                            <div class="flex justify-end text-6xl font-bold p-1 pr-3 border-r">
                                <i class="<?php echo $icon ?>"></i>
                            </div>
                            <div class="flex justify-start p-1 pl-2">
                                <span class="leading-none text-6xl font-weight-bolder"><?php echo $temp ?></span>
                                <span class="text-3xl font-weight-bolder align-top">ºC</span>
                            </div>
                        </div>
                        <!-- Minimum and maximum temperature -->
                        <div class="flex justify-center mb-12">
                            <span class="leading-none font-weight-bolder ">L <strong class="text-lg"><?php echo $temp_min ?>º</strong> - H
                                <strong class="text-lg"><?php echo $temp_max ?></strong>º</span>
                        </div>
                        <!-- Forecast for next 6 days -->
                        <div class="grid grid-flow-col grid-cols-3 grid-rows-2 md:grid-cols-6 md:grid-rows-1 gap-1">
                            <!-- Next day weather report. Displays icon, day and minimum and maximum temperature-->
                            <div class="bg-gray-900">
                                <div class="text-center mt-1">
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
                            <!-- 2nd day weather report. Displays icon, day and minimum and maximum temperature-->
                            <div class="bg-gray-900">
                                <div class="text-center mt-1">
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
                            <!-- Third day weather report. Displays icon, day and minimum and maximum temperature-->
                            <div class="bg-gray-900">
                                <div class="text-center mt-1">
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
                            <!-- Fourth day weather report. Displays icon, day and minimum and maximum temperature-->
                            <div class="bg-gray-900">
                                <div class="text-center mt-1">
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
                            <!-- Fifth day weather report. Displays icon, day and minimum and maximum temperature-->
                            <div class="bg-gray-900">
                                <div class="text-center mt-1">
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
                            <!-- Sixth day weather report. Displays icon, day and minimum and maximum temperature-->
                            <div class="bg-gray-900">
                                <div class="text-center mt-1">
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
                <!-- Details of current weather like feelslike, UV index, humidity, wind speed and line chart graph for next 24 hours -->
                <div class="w-full lg:w-1/2 flex ml-0">
                    <div class="lg:my-4 bg-gray-800 text-white p-8 w-full">
                        <canvas id="chart" class="mb-5"></canvas>
                        <div class="grid grid-cols-4 gap-4 mx-auto">
                            <div class="bg-gray-600 text-center p-5">
                                <i class="fas fa-thermometer-three-quarters fa-2x"></i>
                                <span class="block text-90">
                                    Real feel
                                </span>
                                <span><?php echo $feelslike ?>º</span>
                            </div>
                            <div class="bg-gray-600 text-center p-5">
                                <i class="wi wi-day-sunny text-3xl"></i>
                                <span class="block text-90">
                                    UV index
                                </span>
                                <span class="w-auto"><?php echo $uvindex ?></span>
                            </div>
                            <div class="bg-gray-600 text-center p-5">
                                <i class="fas fa-humidity fa-2x"></i>
                                <span class="block text-90">
                                    Humidity
                                </span>
                                <span><?php echo $humidity ?> %</span>
                            </div>
                            <div class="bg-gray-600 text-center p-5">
                                <i class="fas fa-wind fa-2x"></i>
                                <span class="block text-90">
                                    Wind
                                </span>
                                <span><?php echo $wind ?> km/h</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer of the app, contains link to GitHub repository. Credits of background image and its source -->
    <footer class="flex justify-between text-blue-400 px-5">
        <span id="footer">
            Photo by: <a class="underline" href='<?php echo $image_link; ?>'><?php echo $user_name; ?></a> on <a class="underline" href='https://unsplash.com/'>Unsplash</a>
        </span>
        <a href="https://github.com/fawadrafique/weather-app-v1.5">GitHub <i class="fab fa-github"> </i>
        </a>
    </footer>
    <!-- JS script to get city suggestions via Google Places API and Chart display constructor-->
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
            cityAndCountry = `${city}, ${country}`;
        });
        document.body.style.backgroundSize = "cover";
        document.body.style.backgroundImage = "url(<?php echo $background; ?>)";

        let temp = <?php echo json_encode($temp_forecast); ?>,
            time = <?php echo json_encode($time_forecast); ?>,
            tMin = <?php echo (min($temp_forecast) - 5); ?>,
            tMax = <?php echo (max($temp_forecast) + 5); ?>;

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