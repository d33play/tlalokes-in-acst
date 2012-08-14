<?
/**
 * Authentication business model
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

/**
 * Authentication business model
 *
 * @author Basilio Brice&ntilde;o H. <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2009 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL
 */
class AuthBss {

  /**
   * Authentication by array
   *
   * @param TlalokesRegistry $reg
   */
  public static function validate ( TlalokesRegistry &$reg )
  {
    if ( count( $_SESSION['profiles'] ) >= 1 ) {

      if ( !file_exists( $reg->conf['path']['app'].'auth.php' ) ) {
        tlalokes_error_msg( 'Authentication: Source file not available' );
      }

      // include auth array
      require $reg->conf['path']['app'].'auth.php';

      // check if role is enabled
      if ( !$auth['roles'][$_SESSION['role']] ) {
        tlalokes_error_msg( 'Authentication: Your role is not enabled' );
      }

      // check if controller is available in profile
      foreach ( $_SESSION['profiles'] as $profile ) {

        if ( isset( $auth['permissions'][$profile]
                                     [$reg->conf['current']['controller']] ) ) {
          // validate access
          foreach ( $auth['permissions'][$profile]
                            [$reg->conf['current']['controller']] as $action ) {

            if ( $action == $reg->conf['current']['action'] ) {
              tlalokes_error_msg( 'Authentication: Your profile has no '.
                                  'access to this action' );
            }
          }
        }
      }
    }
  }
}