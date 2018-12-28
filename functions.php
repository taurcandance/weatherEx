<?php
require_once 'InfoWeather\InfoWeather.php';

use InfoWeather\InfoWeather;

function queryWeatherFromResourceApi($city)
{
    $appId    = "233ba77fcc5b0f257350ddae875de7fb";
    $lang     = 'ru';
    $url      = 'http://api.openweathermap.org/data/2.5/weather?q='.$city.'&lang='.$lang.'&appid='.$appId;
    $response = @file_get_contents($url);

    if (false == $response) {
        $error = 'Server not available or incorrect city name';

        return new InfoWeather(null, $error);
    }
    $infoWeather = json_decode($response, true);

    return new InfoWeather($infoWeather, null);
}

function getTemplateMain($redisObj)
{
    if ( ! $redisObj) {
        return giveTemplateFromFile();
    }
    $cacheTemplateString = $redisObj->get('main_template');

    if ( ! $cacheTemplateString) {
        $cacheTemplateString = giveTemplateFromFile();
        $redisObj->set('main_template', $cacheTemplateString);
    }

    return $cacheTemplateString;
}

function queryAndRenderWeather($twig, $cacheTemplateString, $city)
{
        //  TODO: если нет в памяти отдать мин.контент
    $result = queryWeatherFromResourceApi($city);

    if ( ! is_null($result->error)) {
        showMinContent($twig, $cacheTemplateString, $result->error);

        return;
    }
    showMaxContent($result->response, $twig, $cacheTemplateString);
}

function renderMain($twig, $cacheTemplateString)
{
    if ($_GET['city']) {
        queryAndRenderWeather($twig, $cacheTemplateString, $_GET['city']);

        return;
    }

    if (isset($_SESSION['lastCity'])) {
        queryAndRenderWeather($twig, $cacheTemplateString, $_SESSION['lastCity']);

        return;
    }

    showMinContent($twig, $cacheTemplateString, null);
}

function giveTemplateFromFile()
{
    $fp                 = fopen('templates/index.html.twig', 'r');
    $stringPageTemplate = fread($fp, filesize('templates/index.html.twig'));
    fclose($fp);

    return $stringPageTemplate;
}

function showMinContent($twig, $cacheTemplateString, $errorMessage = null)
{
    if ( ! is_null($errorMessage)) {
        $cityInfoArray['name'] = $errorMessage;
    } else {
        $cityInfoArray['name'] = '';
    }

    $output_array = array(
        'title_name' => 'Current weather',
        'form_name'  => 'Current weather',
        'city'       => $cityInfoArray,
    );

    $templateIndexPage = $twig->createTemplate($cacheTemplateString);
    echo $templateIndexPage->render($output_array);
}

function showMaxContent(array $infoWeather, $twig, $cacheTemplateString)
{
    $sunRise = date('d-m-Y h:i:s', $infoWeather['sys']['sunrise']);
    $sunSet  = date('d-m-Y h:i:s', $infoWeather['sys']['sunset']);
    $temp    = $infoWeather['main']['temp'] - 273.15;
    $rain    = $infoWeather['rain']['3h'];

    $templateIndexPage = $twig->createTemplate($cacheTemplateString);
    echo $templateIndexPage->render(
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