<?php
/**
 * Tlalokes Request
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
 * Provides properties to control GET and POST variables
 *
 * @author Basilio Brice&ntilde;o Hern&aacute;ndez <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2009 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/lgpl.html GNU LGPL
 * @package tlalokes.core
 */
class TlalokesRequest {

  public $_controller;
  public $_id;
  public $_action;

  /**
   * Sets GET and POST into local properties
   */
  public function __construct ( &$conf = false )
  {
    // parse vars from URI
    if ( isset( $_SERVER['QUERY_STRING'] ) && $_SERVER['QUERY_STRING'] ) {
      $qrystr = str_replace( 'r=', '', filter_var( $_SERVER['QUERY_STRING'] ) );
      if ( !strstr( $qrystr, 'exe/' ) ) {
        $this->parseUri( $qrystr );
      } else {
        $key = explode( '/', $qrystr );
        if ( isset( $key[1] ) ) {
          $this->_exe = $key[1];
        }
      }
      unset( $qrystr );
    }

    // vars from GET
    if ( $_GET ) {
      while ( list( $key, $value ) = each( $_GET ) ) {
        if ( $key != 'r' ) {
          $this->{$key} = $value;
        }
      }
    }

    // vars from POST
    if ( $_POST ) {
      while ( list( $key, $value ) = each( $_POST ) )	{
        $this->{$key} = $value;
      }
    }

    // vars from FILES
    if ( $_FILES ) {
      $this->setFiles( $conf );
    }
  }

  /**
   * Parses URI and sets the request values
   *
   * @param string $query_string
   */
  private function parseUri ( $query_string )
  {
    $uri = explode( '/', $query_string );

    // controller
    if ( isset( $uri[0] ) ) {
      $class = tlalokes_str_change_format( $uri[0] ).'Ctl';
      if ( !class_exists( $class ) ) {
        tlalokes_error_msg( 'Controller do not exist' );
      }
      $this->_controller = $class;
    }

    // action
    $count = count( $uri );
    if ( $count > 1 ) {

      // clear action
      $rx = '/^([a-zA-Z0-9_\-]*).*/';
      if ( isset( $uri[1] ) ) {
        $uri[1] = preg_replace( $rx, '$1', $uri[1] );
      }
      if ( isset( $uri[2] ) ) {
        $uri[2] = preg_replace( $rx, '$1', $uri[2] );
      }
      unset( $rx );

      // set action
      if ( method_exists( $class, $uri[1] ) ) {
        $this->_action = $uri[1];
      } elseif ( isset( $uri[2] ) && method_exists( $class, $uri[2] ) ) {
        $this->_action = $uri[2];
        $this->_id = tlalokes_core_get_type( $uri[1] );
      } elseif ( isset( $uri[1] ) ) {
        $this->_id = tlalokes_core_get_type( $uri[1] );
      }
    }

    // set action according to REST
    if ( !isset( $this->_action ) || !$this->_action ) {
      // set PUT
      if ( isset( $_POST ) && $_POST && ( !isset( $this->_id ) ||
                                          !$this->_id )  ) {
        $this->_action = 'create';
      // set POST
      } elseif ( isset( $_POST ) && $_POST && isset( $this->_id ) ) {
        $this->_action = 'update';
      // set GET
      } elseif ( ( !isset( $_POST ) || !$_POST ) &&
                 ( !isset( $this->_id ) || isset( $this->_id ) ) ) {
        $this->_action = 'read';
      }
    } else {
      // set DELETE
      if ( !isset( $_POST ) && !$_POST && isset( $this->_id ) &&
           $this->_action == 'delete' ) {
        $this->_action = 'delete';
      }
    }

    // aditional vars
    if ( $count == 4 ) {
      $uri = array_slice( $uri, 2 );
    } elseif ( $count >= 5 ) {
      if ( isset( $this->_action ) && $this->_action == $uri[1] ) {
        $uri = array_slice( $uri, 2 );
      } else {
        $uri = array_slice( $uri, 3 );
      }
    }

    if ( $count >= 4 ) {
      for ( $i = 0; $i < $count; ++$i ) {
        if ( isset( $uri[$i] ) ) {
          $array[$i] = $uri[$i].'='.$uri[++$i];
        }
      }

      parse_str( implode( '&', $array ), $uri );

      // set new vars
      foreach ( $uri as $key => $value ) {
        if ( $value ) {
          $this->{$key} = $value;
        }
      }
    }
  }

  /**
   * Set _FILES into this->_files
   *
   * @param array $conf
   */
  private function setFiles ( &$conf )
  {
    // vars from FILES
    while ( list( $key, $value ) = each( $_FILES ) ) {
      $this->_files->raw[$key] = $value;
    }
    // set paths
    $path = str_replace( 'index.php', '', $_SERVER['SCRIPT_FILENAME'] );
    $this->_files->path['absolute'] = $path . $conf['path']['files'];
    $this->_files->path['relative'] = $conf['path']['uri'] .
                                      $conf['path']['files'];
    unset( $path );
  }

  /**
   * Set default charset
   *
   * @param string $charset
   */
  public function charset ( $charset = 'UTF-8' )
  {
    foreach ( get_object_vars( $this ) as $key => $value ) {
      if ( is_string( $value ) ) {
        $this->{$key} = tlalokes_str_apply_charset( $this->{$key}, $charset );
      }
    }
  }

  /**
   * Actions before setting properties
   *
   * @param string $name
   * @param mixed $value
   */
  public function __set ( $name, $value )
  {
    // get type
    $value = tlalokes_core_get_type( $value );

    // sanitize value
    $this->{$name} = is_string($value) ? tlalokes_str_sanitize($value) : $value;
  }

  /**
   * Destroy properties after use
   */
  public function __destruct ()
  {
    foreach ( get_object_vars( $this ) as $key => $value ) {
      unset( $this->{$key} );
    }
  }
}
