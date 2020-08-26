<?php
$apikey = "94bc76131465087810a5fcee2f66defe";
$apiCall = "https://api.openweathermap.org/data/2.5/onecall?lat=50.85&lon=4.35&exclude=minutely,hourly&units=metric&appid=94bc76131465087810a5fcee2f66defe";
$data = json_decode(file_get_contents($apiCall), true);
var_dump($data['lat']);
