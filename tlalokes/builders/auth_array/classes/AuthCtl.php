<?
/**
 * Authentication controller
 * Copyright (C) 2009 Basilio Briceno Hernandez <bbh@tlalokes.org>
 *
 * This file is part of the Tlalokes Administration User Interfase.
 *
 * Tlalokes Administration User Interfase is free software: you can redistribute
 * it and/or modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation, version 3 of the License.
 *
 * Tlalokes Administration User Interfase is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Tlalokes Administration User Interfase.
 * If not, see <http://www.gnu.org/licenses/gpl.html>.
 */
require_once 'TlalokesCoreController.php';

/**
 * Authentication controller
 *
 * @author Basilio Brice&ntilde;o H. <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2009 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL
 *
 * @ControllerDefinition( default='login' )
 */
class AuthCtl extends TlalokesCoreController {

  /**
   * login action
   *
   * @ActionDefinition( file='tlalokes_auth.tpl' )
   */
  public function login ()
  {
    if ( !isset( $_SESSION['profiles'] ) || !isset( $_SESSION['role'] ) ) {
      // verify login
      if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
        // validate form
        if ( !isset( $_POST['email'] ) || !$_POST['email'] ) {
          $this->response->exception = 'Provide an email';
        } elseif ( !isset( $_POST['password'] ) || !$_POST['password'] ) {
          $this->response->exception = 'Provide a password';
        } else {
          // get file
          if ( !file_exists( $this->path['app'] . 'auth.php' ) ) {
            tlalokes_error_msg( 'Authentication: auth.php not found' );
          }
          require $this->path['app'] . 'auth.php';
          // check if account exists
          if ( !isset( $auth['users'][$this->request->email] ) ) {
            $this->response->exception = 'Invalid account';
          } else {
            // check password
            if ( $auth['users'][$this->request->email]['password'] ==
                 md5( $this->request->password ) ) {
              // check role
              if ( !isset( $auth['users'][$this->request->email]['role'] ) ||
                   !$auth['users'][$this->request->email]['role'] ) {
                $this->response->exception = 'Your account has no role';
              } else {
                // get role
                $role = $auth['users'][$this->request->email]['role'];
                // check if role is enabled
                if ( !$auth['roles'][$role] ) {
                  $this->response->exception = 'Your role has been disabled';
                } else {
                  // set profiles
                  foreach ( $auth['profiles'] as $profile => $array ) {
                    foreach ( $array as $value ) {
                      if ( $value == $role ) {
                        $profiles[] = $profile;
                      }
                    }
                  }
                  if ( isset( $profiles ) && $profiles >= 1 ) {
                    $_SESSION['profiles'] = $profiles;
                    unset( $profiles );
                  }
                  // set role
                  $_SESSION['role'] = $role;
                  unset( $role );
                  // set welcome flag
                  $this->response->flag = true;
                }
              }
            } else {
              $this->response->exception = 'Password invalid try again';
            }
          }
        }
      }
    } else {
      $this->response->flag = true;
    }
  }

  /**
   * logout action
   *
   * @ActionDefinition( file='tlalokes_auth.tpl' )
   */
  public function logout ()
  {
    session_destroy();
  }
}
