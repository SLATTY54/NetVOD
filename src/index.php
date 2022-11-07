<?php

require 'vendor/autoload.php';

use netvod\dispatcher\Dispatcher;

session_start();

\netvod\database\ConnectionFactory::setConfig('conf.ini');

$dispatcher = new Dispatcher();
$dispatcher->run();
