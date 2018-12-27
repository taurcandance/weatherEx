<?php
require_once 'vendor/autoload.php';
require_once 'connectMemcacheServer.php';
require_once 'functions.php';

use SessionState\SessionState;

$session = new SessionState();
$session->setIniParams(2880, 2880, 1000);
$loader = new Twig_Loader_Filesystem('templates');
$twig   = new Twig_Environment($loader);
$twig->addExtension(new Twig_Extension_StringLoader());

if ($memcacheObj && $cacheTemplateString = $memcacheObj->get('main_template')) {
} elseif ($memcacheObj && ! $cacheTemplateString = $memcacheObj->get('main_template')) {
    $cacheTemplateString = giveTemplateFromFile();
    $memcacheObj->add('main_template', giveTemplateFromFile());
} else {
    $cacheTemplateString = giveTemplateFromFile();
}


$session->init();
if ($_GET['city'] or ! isset($_GET['city']) && isset($_SESSION['lastCity'])) {
    require_once 'weatherApi.php';
    if (true == $error) {
//        $errorList = $_GET['city'];   //TODO add handler process incorrect city names
        getMinContent($twig, $cacheTemplateString, $error);
    } else {
        if (isset($_GET['city']) && ! is_null($_GET['city'])) {
            $_SESSION['lastCity'] = $_GET['city'];
        }
        getMaxContent($infoWeather, $twig, $cacheTemplateString);
    }
} else {
    getMinContent($twig, $cacheTemplateString, $error);
}
$session->close();