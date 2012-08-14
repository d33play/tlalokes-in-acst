<?
/**
 * Tlalokes JSON-RPC's functions
 * Copyright (C) 2009 Basilio Briceno Hernandez <bbh@tlalokes.org>
 *
 * This work is based in JSON-RPC PHP <http://jsonrpcphp.org/>
 * written by Sergio Vaccaro <sergio@inservibile.org>, but this version has been
 * modified in order to be faster and compatible with the Tlalokes Framework.
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
 * This function build a JSON-RPC server based in the specification version 1.0
 * http://json-rpc.org/wiki/specification
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param object $object
 * @return boolean
 */
function tlalokes_jsonrpc_server_handle ( &$object )
{
  if ( !tlalokes_jsonrpc_server_check() ) {
    return false;
  }

  // reads the input data
  $request = json_decode( file_get_contents( 'php://input' ), true );

  // executes the task on local object
  try {
    $result = call_user_func_array( array( $object, $request['method'] ),
                                    $request['params'] );
    if ( $result ) {
      $response = array ( 'id' => $request['id'],
                          'result' => $result,
                          'error' => null );
    } else {
      $response = array ( 'id' => $request['id'],
                          'result' => NULL,
                          'error' => 'Unknown method or incorrect parameters' );
    }
    unset( $result );
  } catch ( Exception $e ) {
    $response = array ( 'id' => $request['id'],
                        'result' => null,
                        'error' => $e->getMessage() );
  }

  // output the response
  if ( isset( $request['id'] ) && $request['id'] ) {
    // notifications don't want response
    header( 'content-type: text/javascript' );
    echo json_encode( $response );
  }

  // finish
  exit;
}


/**
 * Checks if a JSON-RCP request has been received
 *
 * @return boolean
 */
function tlalokes_jsonrpc_server_check ()
{
  if ( $_SERVER['REQUEST_METHOD'] != 'POST' || !$_SERVER['CONTENT_TYPE'] ||
       $_SERVER['CONTENT_TYPE'] != 'application/json' ) {
    return false;
  }
  return true;
}