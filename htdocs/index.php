<?php
// tlalokes path
$tlalokes = realpath('../tlalokes/tlalokes');

// application path
$app = realpath('../app');

// application URI
//linea en local
$uri = '/';
//linea en server
//$uri = '/propuesta2/htdocs/';

// Prints the load time and memory
//$tlalokes_load_time = false;

// load receiver
include $tlalokes.'/receiver.php';
