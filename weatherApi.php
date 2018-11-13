<?php
/*
 * Do not send requests more than 1 time per 10 minutes from one device/one API key.
 *  Normally the weather is not changing so frequently.
 *  + not more than 60 requests per hour for 1 account
 *
 * API keys for https:openweathermap.org:
 * 0772a41feab2238f3a139c90cca0f0b3 default
 * 233ba77fcc5b0f257350ddae875de7fb andry
 * 6ed6039bcc84d6a7e53a98b53033025b ded
 */

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
