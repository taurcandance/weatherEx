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

$cacheTemplateString = getTemplateMain($memcacheObj);

$session->init();
renderMain($twig, $cacheTemplateString);
$session->close();