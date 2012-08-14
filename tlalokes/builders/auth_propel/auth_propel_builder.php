<?
/**
 * Authentication builder with Propel
 * Copyright (C) 2010 Basilio Briceno Hernandez <bbh@tlalokes.org>
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
 * Main function for the Authentication builder with Propel
 *
 * @param array $conf
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2010 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL
 */
function auth_propel_builder_main ( &$reg )
{
  $conf =& $reg->conf;
  $builder = $conf['path']['builder'];

  $def_path = $conf['path']['app'].$conf['path']['def'];
  $ctl_path = $conf['path']['app'].$conf['path']['controllers'];
  $bss_path = $conf['path']['app'].$conf['path']['bss'];
  $viw_path = $conf['path']['app'].$conf['path']['views'];
  $loc_path = $conf['path']['app'].$conf['path']['locales'].'eng.php';
  $tmp_path = $conf['path']['app'].$conf['path']['tmp'];

  // validate definition object's directory existance
  if ( !file_exists( $def_path ) ) {
    tlalokes_error_msg("Definition Objects directory ($def_path) not existant");
  }

  // copy of definition objects
  foreach ( glob( $builder . '/templates/def/*Def.php' ) as $defile ) {

    // check destination file existance
    $defname = preg_replace( '/.*\/(.*Def.php)/', '$1', $defile );
    if ( !file_exists( $def_path.$defname ) ) {

      // copy definitions
      if ( !copy( $defile, $def_path.$defname ) ) {
        echo "<p>Cannot copy $defname</p>\n";
      } else {
        echo "<p>$defname copied</p>\n";
      }
    }
  }

  require_once 'TlalokesPropelFactory.php';

  // generate database and ORMs
  if ( $conf['mode']['propel'] ) {
    $conf['mode']['propel'] = 'build-all';
    // check if DSN is available
    if ( !isset( $conf['dsn'] ) ||
         ( !isset( $conf['dsn']['type'] ) || !$conf['dsn']['type'] ) ||
         ( !isset( $conf['dsn']['host'] ) || !$conf['dsn']['host'] ) ||
         ( !isset( $conf['dsn']['name'] ) || !$conf['dsn']['name'] ) ) {
      tlalokes_error_msg( 'Configuration: Declare your RDBMS data.' );
    }
    TlalokesPropelFactory::load( $reg );
  }

  // set default access profiles, role and user in to database
  if ( file_exists( $builder.'/data.xml' ) ) {

      // database
      if ( $conf['mode']['propel'] ) {

        // check if DSN is available
        if ( !isset( $conf['dsn'] ) ||
             ( !isset( $conf['dsn']['type'] ) || !$conf['dsn']['type'] ) ||
             ( !isset( $conf['dsn']['host'] ) || !$conf['dsn']['host'] ) ||
             ( !isset( $conf['dsn']['name'] ) || !$conf['dsn']['name'] ) ) {
          tlalokes_error_msg( 'Configuration: Declare your RDBMS data.' );
        }

        // set arguments
        $lib = $conf['path']['libs'];
        $tmp = $conf['path']['app'] . $conf['path']['tmp'];

        // build properties if not found
        if ( !file_exists( $tmp.'generator/build.properties' ) ) {
          TlalokesPropelFactory::buildBuildProperties( $reg );
        }

        // build runtime configuration if not found
        if ( !file_exists( $tmp.'generator/runtime-conf.xml' ) ) {
          TlalokesPropelFactory::buildRuntimeConfiguration( $reg );
        }

        $args = array('-f', $lib.'phpdb/propel/generator/build.xml',
                  '-Dusing.propel-gen=true', '-Dproject.dir='.$tmp.'generator');
        // transform data to SQL
        array_push( $args, 'datadump' );
        TlalokesPropelFactory::buildProject( $lib, $args );
        echo "<p>SQL data generated.</p>\n";
        unset( $args );

        // copy data.xml
        if ( !copy( $builder.'/data.xml', $tmp_path.'/generator/data.xml' ) ) {
          echo "<p>Cannot copy data.xml</p>\n";
        } else {
          echo "<p>data.xml copied</p>\n";

          $args = array('-f', $lib.'phpdb/propel/generator/build.xml',
                    '-Dusing.propel-gen=true', '-Dproject.dir='.$tmp.'generator');
          // transform data to SQL
          array_push( $args, 'datasql' );
          TlalokesPropelFactory::buildProject( $lib, $args );
          echo "<p>SQL data generated.</p>\n";
          unset( $args );

          $args = array('-f', $lib.'phpdb/propel/generator/build.xml',
                    '-Dusing.propel-gen=true', '-Dproject.dir='.$tmp.'generator');
          // insert SQL into DB
          array_push( $args, 'insert-sql' );
          TlalokesPropelFactory::buildProject( $lib, $args );
          echo "<p>SQL data inserted.</p>\n";
          unset( $args );
        }
    }
  }

  // copy of controllers
  foreach ( glob( $builder . '/templates/ctlr/*Ctl.php' ) as $ctlfile ) {

    // check destination file existance
    $ctlname = preg_replace( '/.*\/(.*Ctl.php)/', '$1', $ctlfile );
    if ( !file_exists( $ctl_path.$ctlname ) ) {

      // copy controllers
      if ( !copy( $ctlfile, $ctl_path.$ctlname ) ) {
        echo "<p>Cannot copy $ctlname</p>\n";
      } else {
        echo "<p>$ctlname copied</p>\n";
      }
    }
  }

  // copy of models
  foreach ( glob( $builder . '/templates/bss/*Bss.php' ) as $bssfile ) {

    // check destination file existance
    $bssname = preg_replace( '/.*\/(.*Bss.php)/', '$1', $bssfile );
    if ( !file_exists( $bss_path.$bssname ) ) {

      // copy models
      if ( !copy( $bssfile, $bss_path.$bssname ) ) {
        echo "<p>Cannot copy $bssname</p>\n";
      } else {
        echo "<p>$bssname copied</p>\n";
      }
    }
  }

  // copy of views
  if ( !file_exists( $viw_path.'auth_layout.tpl' ) ) {
    // copy auth_layout.tpl
    if ( !copy( $builder.'/templates/view/auth_layout.tpl', $viw_path.'auth_layout.tpl' ) ) {
      echo "<p>Cannot copy authentication layout</p>\n";
    } else {
      echo "<p>Authentication layout copied</p>\n";
    }
  }
  if ( !file_exists( $viw_path.'auth.tpl' ) ) {
    // copy auth.tpl
    if ( !copy( $builder.'/templates/view/auth.tpl', $viw_path.'auth.tpl' ) ) {
      echo "<p>Cannot copy login view.</p>\n";
    } else {
      echo "<p>Authentication layout copied.</p>\n";
    }
  }
  if ( !file_exists( $viw_path . 'block/'  ) ) {
    if ( !@mkdir( $viw_path . 'block/' ) ) {
      tlalokes_error_msg( "Cannot write " . $viw_path . 'block/' );
    }
  }
  // blocks
  foreach ( glob( $builder . '/templates/view/block/*.tpl' ) as $tplfile ) {

    // copy blocks
    $viewname = preg_replace( '/.*\/(.*.tpl)/', '$1', $tplfile );
    if ( !file_exists( $viw_path . $viewname ) ) {

      // copy files
      if ( !copy( $tplfile, $viw_path . 'block/' . $viewname ) ) {
        echo "<p>Cannot copy $viewname view</p>\n";
      } else {
        echo "<p>$viewname copied</p>\n";
      }
    }
  }

  // locales
  if ( file_exists( $loc_path ) ) {
    require $loc_path;
  } else {
    $locale['name'] = 'English';
  }

  $l['_keywords'] = 'some, keywords';
  $l['home'] = 'Home';
  $l['back'] = 'Back';
  $l['next'] = 'Next';
  $l['page'] = 'Page';
  $l['exit'] = 'Exit';
  $l['of'] = 'of';

  $l['nav_title'] = 'Auth';
  $l['nav_home'] = 'Home';
  $l['nav_users'] = 'Users';
  $l['nav_roles'] = 'Roles';
  $l['nav_access_profiles'] = 'Access Profiles';
  $l['nav_access_profiles_roles'] = 'Roles - Access Profiles';
  $l['nav_access_permissions'] = 'Access Permissions';
  $l['nav_logout'] = 'Logout';

  $l['controllers']['AuthCtl']['title'] = 'Auth';
  $l['controllers']['AuthCtl']['welcome'] = 'Welcome';
  $l['controllers']['AuthCtl']['email'] = 'Email';
  $l['controllers']['AuthCtl']['password'] = 'Password';
  $l['controllers']['AuthCtl']['login'] = 'Login';

  $l['controllers']['AuthAccessPermissionsCtl']['title'] = 'Access Permissions';
  $l['controllers']['AuthAccessPermissionsCtl']['id'] = 'Id';
  $l['controllers']['AuthAccessPermissionsCtl']['profile'] = 'Profile';
  $l['controllers']['AuthAccessPermissionsCtl']['controller'] = 'Controller';
  $l['controllers']['AuthAccessPermissionsCtl']['methods'] = 'Methods';
  $l['controllers']['AuthAccessPermissionsCtl']['add'] = 'Add';
  $l['controllers']['AuthAccessPermissionsCtl']['edit'] = 'Edit';
  $l['controllers']['AuthAccessPermissionsCtl']['delete'] = 'Delete';
  $l['controllers']['AuthAccessPermissionsCtl']['save'] = 'Save';
  $l['controllers']['AuthAccessPermissionsCtl']['filter'] = 'Filter';
  $l['controllers']['AuthAccessPermissionsCtl']['true'] = 'True';
  $l['controllers']['AuthAccessPermissionsCtl']['false'] = 'False';
  $l['controllers']['AuthAccessPermissionsCtl']['select_an_option'] = 'Select an option';

  $l['controllers']['AuthAccessProfilesCtl']['title'] = 'Access Profiles';
  $l['controllers']['AuthAccessProfilesCtl']['id'] = 'Id';
  $l['controllers']['AuthAccessProfilesCtl']['name'] = 'Name';
  $l['controllers']['AuthAccessProfilesCtl']['description'] = 'Description';
  $l['controllers']['AuthAccessProfilesCtl']['add'] = 'Add';
  $l['controllers']['AuthAccessProfilesCtl']['edit'] = 'Edit';
  $l['controllers']['AuthAccessProfilesCtl']['delete'] = 'Delete';
  $l['controllers']['AuthAccessProfilesCtl']['save'] = 'Save';
  $l['controllers']['AuthAccessProfilesCtl']['filter'] = 'Filter';
  $l['controllers']['AuthAccessProfilesCtl']['true'] = 'True';
  $l['controllers']['AuthAccessProfilesCtl']['false'] = 'False';

  $l['controllers']['AuthAccessProfilesRolesCtl']['title'] = 'Roles / Access Profiles';
  $l['controllers']['AuthAccessProfilesRolesCtl']['id'] = 'Id';
  $l['controllers']['AuthAccessProfilesRolesCtl']['profile'] = 'Profile';
  $l['controllers']['AuthAccessProfilesRolesCtl']['role'] = 'Role';
  $l['controllers']['AuthAccessProfilesRolesCtl']['add'] = 'Add';
  $l['controllers']['AuthAccessProfilesRolesCtl']['edit'] = 'Edit';
  $l['controllers']['AuthAccessProfilesRolesCtl']['delete'] = 'Delete';
  $l['controllers']['AuthAccessProfilesRolesCtl']['save'] = 'Save';
  $l['controllers']['AuthAccessProfilesRolesCtl']['filter'] = 'Filter';
  $l['controllers']['AuthAccessProfilesRolesCtl']['true'] = 'True';
  $l['controllers']['AuthAccessProfilesRolesCtl']['false'] = 'False';
  $l['controllers']['AuthAccessProfilesRolesCtl']['select_an_option'] = 'Select an option';

  $l['controllers']['AuthRolesCtl']['title'] = 'Roles';
  $l['controllers']['AuthRolesCtl']['id'] = 'Id';
  $l['controllers']['AuthRolesCtl']['name'] = 'Name';
  $l['controllers']['AuthRolesCtl']['role_status'] = 'Status';
  $l['controllers']['AuthRolesCtl']['add'] = 'Add';
  $l['controllers']['AuthRolesCtl']['edit'] = 'Edit';
  $l['controllers']['AuthRolesCtl']['delete'] = 'Delete';
  $l['controllers']['AuthRolesCtl']['save'] = 'Save';
  $l['controllers']['AuthRolesCtl']['filter'] = 'Filter';
  $l['controllers']['AuthRolesCtl']['true'] = 'True';
  $l['controllers']['AuthRolesCtl']['false'] = 'False';
  $l['controllers']['AuthRolesCtl']['active'] = 'Active';
  $l['controllers']['AuthRolesCtl']['inactive'] = 'Inactive';
  $l['controllers']['AuthRolesCtl']['select_one'] = 'Select one';

  $l['controllers']['AuthUsersCtl']['title'] = 'Users';
  $l['controllers']['AuthUsersCtl']['id'] = 'Id';
  $l['controllers']['AuthUsersCtl']['email'] = 'Email';
  $l['controllers']['AuthUsersCtl']['password'] = 'Password';
  $l['controllers']['AuthUsersCtl']['user_status'] = 'Status';
  $l['controllers']['AuthUsersCtl']['role'] = 'Role';
  $l['controllers']['AuthUsersCtl']['add'] = 'Add';
  $l['controllers']['AuthUsersCtl']['edit'] = 'Edit';
  $l['controllers']['AuthUsersCtl']['delete'] = 'Delete';
  $l['controllers']['AuthUsersCtl']['save'] = 'Save';
  $l['controllers']['AuthUsersCtl']['filter'] = 'Filter';
  $l['controllers']['AuthUsersCtl']['true'] = 'True';
  $l['controllers']['AuthUsersCtl']['false'] = 'False';
  $l['controllers']['AuthUsersCtl']['select_a_role'] = 'Select a role';
  $l['controllers']['AuthUsersCtl']['select_one'] = 'Select one';
  $l['controllers']['AuthUsersCtl']['active'] = 'Active';
  $l['controllers']['AuthUsersCtl']['inactive'] = 'Inactive';

  $nodes = tlalokes_str_from_array( $l );
  $nodes = preg_replace( '/^(\[.*)\n/m', "\$l$1\n", $nodes );

  $info = tlalokes_str_from_array( $locale );
  $info = preg_replace( '/^(\[.*)\n/m', "\$locale$1\n", $info );

  if ( !file_put_contents( $loc_path, "<?\n$info\n$nodes", LOCK_EX ) ) {
    tlalokes_error_msg( "Builder: eng.php could not be generated", true );
  }
  echo "<p>English locale generated</p>\n";
}
?>
