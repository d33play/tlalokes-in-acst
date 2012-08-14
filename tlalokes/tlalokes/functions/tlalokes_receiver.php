<?php
/**
 * Tlalokes receiver functions
 * Copyright (C) 2009 Basilio Briceno Hernandez <bbh@tlalokes.org>
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

/**
 * Includes files for required classes, don't abuse of this feature
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param string $class
 */
function __autoload ( $class )
{
  // set the class file name
  $file_name = $class . '.php';

  // try to find the class directly
  if ( file_exists( $file_name ) ) {
    $file_required = $file_name;

  // find classes into include_path directories
  } else {
    foreach ( preg_split( '/['.PATH_SEPARATOR.']/', get_include_path() ) as $path ) {
      if ( file_exists( $path . $file_name ) ) {
        $file_required = $path . $file_name;
      }
    }
  }

  // if class exists load it
  if ( isset( $file_required ) ) {
    if ( !is_readable( $file_required ) ) {
      tlalokes_error_msg( 'Receiver: Cannot read '. $file_required, true );
    }
    require_once $file_required;
    //echo "<pre>\nautoload..$file_required\n</pre>\n";
  }
}

/**
 * Print a error message and exits
 * It function existis, because Exceptions consumes too much memory
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param string $message
 * @param boolean $noheader
 * @param mixed $go_to controller/action
 */
function tlalokes_error_msg ( $message, $noheader = false, $go_to = false )
{
  if ( !$go_to ) {
    $title = 'Exception';
    if ( !$noheader ) {
      require 'inc/header.inc.php';
    }
    echo '<p><span id="title">Message</span><span id="dots">: </span>',
         '<span id="message">',$message,"</span></p>\n";
    require 'inc/footer.inc.php';
    unset( $title );
    unset( $message );
    exit;
  } else {
    tlalokes_go_to( $go_to );
  }
}

/**
 * Calculates time and memory
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param integer $start_time
 * @return string
 */
function tlalokes_calculate_time_and_memory ( $start_time )
{
  $final_time = round( microtime( true ) - $start_time, 4 );
  $_mem = memory_get_usage( true );
  $mem = round( ( $_mem / 1024 ) / 1204 , 2 );
  $_mem = round( $_mem / 1024, 2 );
  return $final_time.' seconds and '.$mem.'MB ('.$_mem."KB) of memory\n";
}

/**
 * Checks if web services are declares in the controller and loads them
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param TlalokesRegistry $reg
 */
function tlalokes_receiver_webservices ( &$reg )
{
  // reflect Annotations in method
  require 'ReflectionAnnotatedClass.php';
  $ref = new ReflectionAnnotatedClass( $reg->conf['current']['controller'] );

  //require 'ControllerDefinition.php';
  if ( $ref->hasAnnotation( 'ControllerDefinition' ) ) {

    // JSON
    if ( $ref->getAnnotation( 'ControllerDefinition' )->json ) {
      // check request
      if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
        var_dump( $_POST );
        $reg->webservice = true;
        $obj = new $reg->conf['current']['controller'] ( $reg );
        unset( $reg );
        echo json_encode( $obj->response );
        exit;
      }
    }

    // JSON-RPC
    if ( $ref->getAnnotation( 'ControllerDefinition' )->jsonrpc ) {
      require 'tlalokes_jsonrpc.php';
      // check request
      if ( tlalokes_jsonrpc_server_check() ) {
        $reg->json = true;
        $obj = new $reg->conf['current']['controller'] ( $reg );
        unset( $reg );
        tlalokes_jsonrpc_server_handle( $obj );
      }
    }

    // SOAP
    if ( $ref->getAnnotation( 'ControllerDefinition' )->soap ) {
      if ( $_SERVER['REQUEST_METHOD'] == 'POST' ||
           $_SERVER['CONTENT_TYPE'] == 'application/soap+xml' ) {
        // set URI for the service
        $uri = 'http://' .$_SERVER['HTTP_HOST'] . $reg->conf->path->uri .
               preg_replace( '/^\/(.*).php/', '$1', $_SERVER['SCRIPT_NAME'] ) .
               '/' . $reg->conf['current']['controller'];
        // set service and handle it
        $server = new SoapServer( null, array( 'uri' => $uri ) );
        $server->setClass( $reg->conf['current']['controller'].'Ctl', $reg );
        $server->handle();
        unset( $ref );
        exit;
      }
    }
  }
}
