<?php

function giveTemplateFromFile(){
    $fp                 = fopen('templates/index.html.twig', 'r');
    $stringPageTemplate = fread($fp, filesize('templates/index.html.twig'));
    fclose($fp);
    return $stringPageTemplate;
}

function getMinContent($twig, $cacheTemplateString, $errorMessage = null)
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

function getMaxContent(array $infoWeather, $twig, $cacheTemplateString)
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