<?php
require_once 'vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment( $loader );
if ( !$_GET['city'] )
{
    echo $twig->render('index.html.twig', array(
        'title_name' => 'Прогноз погоды',
        'form_name' => 'Прогноз погоды' ));
}
else
{
    require_once 'weatherApi.php';
    if( $error_conn ) { echo $error_conn; return; }
    $sunRise = date('d-m-Y h:i:s',$info_weather['sys']['sunrise']);
    $sunSet = date('d-m-Y h:i:s',$info_weather['sys']['sunset']);
    $temp = $info_weather['main']['temp'] - 273.15;
    $rain = $info_weather['rain']['3h'];

    echo $twig->render('index.html.twig', array(
        'title_name' => 'Прогноз погоды',
        'form_name' => 'Прогноз погоды',
        'city' => $info_weather,
        'sunrise' => $sunRise,
        'sunset' => $sunSet,
        'temp' => $temp,
        'rain' => $rain));
}