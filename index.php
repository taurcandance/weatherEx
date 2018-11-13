<?php
require_once 'vendor/autoload.php';
require_once 'connectMemcachedServer.php';

$loader = new Twig_Loader_Filesystem('templates');
$twig   = new Twig_Environment($loader);
$twig->addExtension(new Twig_Extension_StringLoader());

if ( ! $cacheTemplateString = $memcacheObj->get('main_template')) {
    $fp                 = fopen('templates/index.html.twig', 'r');  // TODO : add checked file exist
    $stringPageTemplate = fread($fp, filesize('templates/index.html.twig'));
    fclose($fp);
    $memcacheObj->add('main_template', $stringPageTemplate);
}
$cacheTemplateString = $memcacheObj->get('main_template');


if ( ! $_GET['city']) {
    $output_array      = array(
        'title_name' => 'Current weather',
        'form_name'  => 'Current weather',
    );
    $templateIndexPage = $twig->createTemplate($cacheTemplateString);
    echo $templateIndexPage->render($output_array);
} else {
    require_once 'weatherApi.php';  // TODO : add checked user input $_GET[]

    $session = new \SessionState\SessionState();
    $session->save(2880, 2880, 1000);

    if ($error_conn) {  // TODO : $error_conn initialized in 'weatherApi.php'
        echo $error_conn;

        return;
    }

    $sunRise = date('d-m-Y h:i:s', $infoWeather['sys']['sunrise']); // TODO : $error_conn initialized in 'weatherApi.php'
    $sunSet  = date('d-m-Y h:i:s', $infoWeather['sys']['sunset']);
    $temp    = $infoWeather['main']['temp'] - 273.15;
    $rain    = $infoWeather['rain']['3h'];

    echo $twig->render(
        'index.html.twig',
        array(
            'title_name' => 'Current weather',
            'form_name'  => 'Current weather',
            'city'       => $infoWeather,
            'sunrise'    => $sunRise,
            'sunset'     => $sunSet,
            'temp'       => $temp,
            'rain'       => $rain,
        )
    );
}