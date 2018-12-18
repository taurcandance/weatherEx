<?php


$appid = "233ba77fcc5b0f257350ddae875de7fb";
$lang  = 'ru';
$city  = $_GET['city'];
$url   = 'http://api.openweathermap.org/data/2.5/weather?q='.$city.'&lang='.$lang.'&appid='.$appid;

$response = file_get_contents($url);
if ($response) {
    $infoWeather = json_decode($response, true);
} else {
    $error_conn = "Server not available";
}
