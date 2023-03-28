<?php

require_once 'vendor/autoload.php';

define("ROOT", dirname(__FILE__));

$config = require_once 'config.php';

try
{
    $bootstrap = new \App\Bootstrap\Bootstrap($config);
    $bootstrap->init();
}
catch (\Exception $e)
{
    var_dump($e->getMessage());

    die;
}