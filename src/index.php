<?php

require 'vendor/autoload.php';

use netvod\dispatcher\Dispatcher;

session_start();


ConnectionFactory::setConfig('config.ini');
$dispatcher = new Dispatcher();
$dispatcher->run();
