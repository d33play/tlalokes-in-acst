<?
/**
 * Tlalokes Authentication Factory
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
 * Provides necesary methods and properties to authenticate users
 *
 * @author Basilio Brice&ntilde;o Hern&aacute;ndez <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2009 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/lgpl.html GNU LGPL
 * @package tlalokes.core
 */
class TlalokesAuthenticationFactory {

  private static $instance;
  private $auth;

  private function __construct( &$auth )
  {
    if ( !isset( $auth ) ) {
      tlalokes_error_msg( 'Set authentication method in configuration file' );
    }
    // set authentication method
    $this->auth = $auth;
  }

  /**
   * Returns an instance of TlalokesAuthenticationFactory
   *
   * @param string $auth
   * @return TlalokesAuthenticationFactory
   */
  public static function getInstance ( &$auth )
  {
    if ( !self::$instance ) {
      self::$instance = new TlalokesAuthenticationFactory( $auth );
    }
    return self::$instance;
  }

  /**
   * Returns password and role by username and password
   *
   * @param TlalokesRequest $request
   * @return array
   */
  public function getUserRole ( TlalokesRequest &$request )
  {
    try {
      // validate username and password existance
      if ( !$request->email ) {
        throw new Exception( 'Provide an email' );
      }
      if ( !$request->password ) {
        throw new Exception( 'Provide a password' );
      }
      $response = array();

      // get permissions by auth method
      switch ( strtolower( $this->auth ) ) {

        case 'sql' :

          // set Criteria object
          $c = new Criteria();
          $c->add( AuthUsersPeer::EMAIL, $request->email );
          // do select
          $result = AuthUsersPeer::doSelectOne( $c );
          unset( $c );
          if ( !$result ) {
            throw new Exception( 'There are no coincidences' );
          }
          // set response
          $response = array( 'password' => $result->getPassword(),
                             'role' => $result->getRole() );
          break;

        case 'array' :

          break;
      }

      return $response;
    } catch ( Exception $e ) {
      return $e->getMessage();
    }
  }

  /**
   * Sets permissions in session
   *
   * @param string $role
   * @return mixed
   */
  public function setPermissions ( $role )
  {
    try {
      // get permissions by auth method
      switch ( strtolower( $this->auth ) ) {

        case 'sql' :
          // set criteria
          $c = new Criteria();
          $c->add( AuthPermissionsPeer::ROLE, $role );
          // do select
          $result = AuthPermissionsPeer::doSelect( $c );
          unset( $c );
          if ( !$result ) {
            throw new Exception( 'There are no coincidences' );
          }
          // set array
          foreach ( $result as $obj ) {
            $r[][$obj->getAuthComponents()->getController()]
                [$obj->getAuthComponents()->getAction()] = $obj->getAccess();
          }
          // sort array correctly
          foreach ( $r as $item ) {
            foreach ( $item as $controller => $v ) {
              foreach ( $v as $action => $access ) {
                $response[$controller][$action] = $access;
              }
            }
          }
          unset( $r );
          break;
      }

      $_SESSION['auth'] =& $response;
    } catch ( Exception $e ) {
      return $e->getMessage();
    }
  }
}