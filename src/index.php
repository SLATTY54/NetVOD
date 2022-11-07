<?php

require 'vendor/autoload.php';

use netvod\dispatcher\Dispatcher;

session_start();



$dispatcher = new Dispatcher();
$dispatcher->run();
