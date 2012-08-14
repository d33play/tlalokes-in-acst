<?
/**
 * Authentication builder by array
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
 * Main function for the Authentication builder by array
 *
 * @param array $conf
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2009 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL
 */
function auth_array_builder_main ( &$reg )
{
  $conf =& $reg->conf;
  $builder = $conf['path']['builder'];

  $bss_path = $conf['path']['app'].$conf['path']['bss'];
  $ctl_path = $conf['path']['app'].$conf['path']['controllers'];
  $viw_path = $conf['path']['app'].$conf['path']['views'];
  $loc_path = $conf['path']['app'].$conf['path']['locales'].'eng.php';

  // coping of authentication controller
  if ( !file_exists( $ctl_path.'AuthCtl.php' ) ) {

    // copy AuthCtl
    if ( !@copy( $builder.'/classes/AuthCtl.php', $ctl_path.'AuthCtl.php' ) ) {
      tlalokes_error_msg( 'Cannot copy AuthCtl.php', true );
    } else {
      echo '<p>AuthCtl.php copied to '.$ctl_path."</p>\n";
    }
  }

  // coping of authentication model
  if ( !file_exists( $bss_path.'AuthBss.php' ) ) {

    // copy AuthBss
    if ( !@copy( $builder.'/classes/AuthBss.php', $bss_path.'AuthBss.php' ) ) {
      tlalokes_error_msg( 'Cannot copy AuthBss.php', true );
    } else {
      echo '<p>AuthBss.php copied to '.$bss_path."</p>\n";
    }
  }

  // coping of authentication view
  if ( !file_exists( $viw_path.'tlalokes_auth.tpl' ) ) {

    // copy auth.tpl
    if ( !@copy( $builder.'/view/tlalokes_auth.tpl',
                $viw_path.'tlalokes_auth.tpl' ) ) {
      tlalokes_error_msg( 'Cannot copy authentication view', true );
    } else {
      echo '<p>tlalokes_auth.tpl view copied to '.$viw_path."</p>\n";
    }
  }

  // configuration file
  if ( !file_exists( $conf['path']['app'].'auth.php' ) ) {

    // copy auth.tpl
    if ( !@copy( $builder.'/conf/auth.php', $conf['path']['app'].'auth.php' ) ) {
      tlalokes_error_msg( 'Cannot copy authentication configuration', true );
    } else {
      echo '<p>Authentication configuration file copied to '.$viw_path."</p>\n";
    }
  }

  // locale
  if ( @file_exists( $loc_path ) ) {
    require $loc_path;
  } else {
    $locale['name'] = 'English';
  }
  if ( !isset( $l['controllers']['AuthCtl'] ) ) {

    $l['controllers']['AuthCtl']['title'] = 'Login';
    $l['controllers']['AuthCtl']['welcome'] = 'Welcome';
    $l['controllers']['AuthCtl']['email'] = 'email';
    $l['controllers']['AuthCtl']['password'] = 'Password';
    $l['controllers']['AuthCtl']['submit'] = 'Login';
    $l['controllers']['AuthCtl']['back'] = 'Back';
    $l['controllers']['AuthCtl']['exit'] = 'Exit';

    $nodes = tlalokes_str_from_array( $l );
    $nodes = preg_replace( '/^(\[.*)\n/m', "\$l$1\n", $nodes );

    $info = tlalokes_str_from_array( $locale );
    $info = preg_replace( '/^(\[.*)\n/m', "\$locale$1\n", $info );

    if ( !@file_put_contents( $loc_path, "<?\n$info\n$nodes", LOCK_EX ) ) {
      tlalokes_error_msg( "Builder: eng.php could not be generated", true );
    }
    echo "<p>English locale generated</p>\n";
  }
}