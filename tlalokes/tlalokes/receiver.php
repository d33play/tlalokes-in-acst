<?php
/**
 * receiver
 * Copyright (C) 2008 Basilio Briceno Hernandez <bbh@tlalokes.org>
 *
 * This file is part of the Tlalokes Framework.
 *
 * Tlalokes Framework is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as published by the
 * Free Software Foundation, version 3 of the License.
 *
 * Tlalokes Framework is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License
 * for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Tlalokes Framework.
 * If not, see <http://www.gnu.org/licenses/lgpl.html>.
 */

// set short open tags <? as default
ini_set( 'short_open_tag', '1' );

// if load time is true show all errors
if ( isset( $tlalokes_load_time ) && $tlalokes_load_time ) {
  $start_time = microtime( true );
  error_reporting( E_ALL );
} else {
  error_reporting( E_ALL ^ E_NOTICE );
}

// init session
session_start();

require 'functions/tlalokes_receiver.php';

// check path of application
if ( !isset( $app ) ) {
  tlalokes_error_msg( 'Receiver: Provide the path for your application.' );
}
if ( !file_exists( $app ) ) {
  tlalokes_error_msg( 'Receiver: Path provided ('.$app.') not found.' );
}

// setup
if ( !isset( $admin ) ) {
  // check if application exists or load setup
  if ( !file_exists( $app.'/config.php' ) ) {
    if ( !is_writable( $app ) ) {
      tlalokes_error_msg( 'Setup: Path provided ('.$app.') no writeable' );
    }
    require 'functions/inc/setup.inc.php';
    return;
  }/* elseif ( file_exists( "$app/config.php" ) &&
             !file_exists(preg_replace( '/(.*)\/index.php$/', '$1',
                          $_SERVER['SCRIPT_FILENAME'] ).'/admin/config.php') &&
             isset( $_GET['admin'] ) && $_GET['admin'] == 'setup' ) {
    require 'functions/inc/setup_admin.inc.php';
    return;
  }*/
}

// load configuration file
require $app.'/config.php';

// check admin
// NOTE: This code will return for version 1.2
/*if ( isset( $admin ) ) {
  if ( !file_exists( $application.'/config.php' ) ) {
    tlalokes_error_msg( "Admin's receiver: Application's path not found" );
  }
  $c['path']['application'] = $application;
}*/

// set paths from index
$c['path']['tlalokes'] = $tlalokes;
$c['path']['app'] = $app;
$c['path']['uri'] = $uri;
if ( isset( $files ) ) {
  $c['path']['files'] = $files;
  unset( $files );
}

// load core functions
require 'functions/tlalokes_core.php';
require 'functions/tlalokes_strings.php';
require 'functions/tlalokes_parsers.php';
require 'functions/tlalokes_filesystem.php';

// load basic classes
require 'classes/TlalokesRegistry.php';

$r = TlalokesRegistry::instance();
$r->conf = tlalokes_core_conf_load( $c );

// check if execution is needed
tlalokes_core_execution( $r, $c );

unset( $c );
unset( $uri );
unset( $app );
unset( $tlalokes );

// look up for controller
$path  = $r->conf['path']['app'] . $r->conf['path']['controllers'];
if ( !file_exists( $path . $r->conf['current']['controller'].'.php' ) ) {
  if ( !class_exists( $r->conf['current']['controller'], false ) ) {
    $msg = 'Receiver: Class '.$r->conf['current']['controller'].' not found.';
    tlalokes_error_msg( $msg );
  }
}
unset( $ctlr_path );

// check if request is for a web service
tlalokes_receiver_webservices( $r );

// construct and load the controller
try {
  $load = new $r->conf['current']['controller'] ( $r );
  unset( $load );
} catch ( Exception $e ) {
  tlalokes_error_msg( $e->getMessage() );
}

// remove TlalokesRegistry
unset( $r );

// print load time and end program
if ( isset( $tlalokes_load_time ) && $tlalokes_load_time ) {
  echo tlalokes_calculate_time_and_memory( $start_time );
}

exit;
