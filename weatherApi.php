<?php

$appId = "233ba77fcc5b0f257350ddae875de7fb";
$lang  = 'ru';
if ($_GET['city']) {
    $city = $_GET['city'];
} else {
    $city = $_SESSION['lastCity'];
}
$url = 'http://api.openweathermap.org/data/2.5/weather?q='.$city.'&lang='.$lang.'&appid='.$appId;

try {
    $response = @file_get_contents($url);   //TODO @
    if (false == $response) {
        throw new Exception('Server not available or incorrect city name');
    } else {
        $infoWeather = json_decode($response, true);
    }
} catch (Exception $exception) {
    $error = $exception->getMessage();
}