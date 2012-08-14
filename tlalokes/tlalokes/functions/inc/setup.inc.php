<?
/**
 * Tlalokes setup interfase
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

$title = 'Setup';
require $tlalokes.'/functions/inc/header.inc.php';
require $tlalokes.'/functions/inc/timezones.inc.php';

if ( ( !isset( $conf ) || !file_exists( $conf ) ) && !isset( $_POST['action'] ) ) :
  $license = file_get_contents( $tlalokes.'/license.txt' );
?>
          <p>
            Welcome. Tlalokes is a free (as in freedom) PHP framework distributed under the terms
            of the  GNU Lesser General Public License. In order to use it you must accept
            the terms of this license.
          </p>

          <form method="post" action="<?=$uri;?>">
            <textarea name="license" style="width:570px;height:300px;border:solid gray 1px;"><?=$license;?></textarea>
            <br/>
            <input type="hidden" name="action" value="setup"/>
            <div align="right" style="width:560px;">
              <input type="submit" value="Accept" />
              <input type="reset" value="Decline" onclick="location.replace('http://tlalokes.org/');"/>
            </div>
          </form>
<?
  unset( $license );
elseif ( ( !isset( $conf ) || !file_exists( $conf ) ) &&
         isset( $_POST['action'] ) && $_POST['action'] == 'setup' ) :
?>
          <p>
            Everything is ready to build the structure for your new application.
          </p>
          <p>
            Please assign a password for your administration area.
            <form action="<?=$uri;?>" method="post">
              <fieldset>
                <div class="element">
                  <label for="pass1">Set your password</label>
                  <input type="password" name="pass1" id="box" />
                </div>
                <div class="element">
                  <label for="pass2">Confirm your password</label>
                  <input type="password" name="pass2" id="box" />
                </div>
                <div class="element">
                  <label for="timezone">Set your time zone</label>
                  <select name="timezone">
                    <option value="">Select one</option>
<?
  foreach ( $timezone as $tz ) :
?>
                    <option><?=$tz;?></option>
<?
  endforeach;
  unset( $timezone );
?>
                  </select>
                </div>
                <input type="hidden" name="action" value="build" />
                <div class="element">
                  <label for="db">Use a database?</label>
                  <input type="checkbox" name="db" checked onchange="checkDB(this);" id="box"/>
                </div>
                <script type="text/javascript">
                function checkDB ( db ) {
                  dbform = document.getElementById('dbform');
                  if ( !db.checked ) {
                    dbform.style.display = 'none';
                  } else {
                    dbform.style.display = 'block';
                  }
                }
                </script>
                <div id="dbform">
                  <div class="element">
                    <label for="dsn_type">RDBMS</label>
                    <select name="dsn_type" id="box">
                      <option value="">Select one</option>
                      <option value="pgsql">PostgreSQL</option>
                      <option value="mysql">MySQL</option>
                      <option value="mysqli">MySQLi</option>
                      <option value="sqlite">SQLite</option>
                      <option value="odbc">ODBC</option>
                      <option value="oracle">Oracle</option>
                      <option value="mssql">MS SQL Server</option>
                    </select>
                  </div>
                  <div class="element">
                    <label for="dsn_host">DSN host</label>
                    <input type="text" name="dsn_host" id="box" />
                  </div>
                  <div class="element">
                    <label for="dsn_name">DSN dbname</label>
                    <input type="text" name="dsn_name" id="box" />
                  </div>
                  <div class="element">
                    <label for="dsn_username">DSN username</label>
                    <input type="text" name="dsn_username" id="box" />
                  </div>
                  <div class="element">
                    <label for="dsn_password">DSN password</label>
                    <input type="password" name="dsn_password" id="box" />
                  </div>
                </div>
                <div class="element">
                  <label>&nbsp;</label>
                  <input type="submit" value="Continue" />
                  <input type="button" value="Cancel" onclick="history.back();"/>
                </div>
              </fieldset>
            </form>
          </p>
<?
elseif ( ( !isset( $conf ) || !file_exists( $conf ) ) &&
         isset( $_POST['action'] ) && $_POST['action'] == 'build' ) :

  // password
  if ( !$_POST['pass1'] ) {
    tlalokes_error_msg( 'You must provide a password', true );
  }
  if ( !$_POST['pass2'] ) {
    tlalokes_error_msg( 'You must confirm password', true );
  }
  if ( $_POST['pass1'] != $_POST['pass2'] ) {
    tlalokes_error_msg( 'Passwords not match', true );
  }
  if ( !$_POST['timezone'] ) {
    tlalokes_error_msg( 'You must set your time zone', true );
  }

  // database
  if ( isset( $_POST['db'] ) && $_POST['db'] == 'on' ) {

    // validate DB config data
    if ( !isset( $_POST['dsn_type'] ) || !$_POST['dsn_type'] ) {
      tlalokes_error_msg( 'You must set your database type (RDBMS).', true );
    }
    if ( !isset( $_POST['dsn_host'] ) || !$_POST['dsn_host'] ) {
      tlalokes_error_msg( 'You must set the hostname of your database', true );
    }
    if ( !isset( $_POST['dsn_name'] ) || !$_POST['dsn_name'] ) {
      tlalokes_error_msg( 'You must set a database name' );
    }
    if ( !isset( $_POST['dsn_username'] ) || !$_POST['dsn_username'] ) {
      tlalokes_error_msg( 'Set a username to access your database', true );
    }

    // validate DB connection
    $dsn = array( 'phptype' => $_POST['dsn_type'],
                  'hostspec' => $_POST['dsn_host'],
                  'username' => $_POST['dsn_username'],
                  'password' => !isset( $_POST['dsn_password'] )
                                ? '' : $_POST['dsn_password'],
                  'database' => $_POST['dsn_name'] );
    $creole = preg_replace( '/(.*)tlalokes\/?$/', '$1', $tlalokes ) .
              'lib/phpdb/creole';
    if ( !file_exists( $creole )  ) {
      tlalokes_error_msg( 'Your Tlalokes copy do not includes Creole, please '.
                          'provide it.', true );
    }
    ini_set( 'include_path', $creole );
    require $creole . '/creole/Creole.php';
    try {
      $conn = Creole::getConnection( $dsn );
      if ( $conn ) {
        unset( $dsn );
        unset( $creole );
        unset( $conn );
      }
    } catch ( SQLException $e ) {
      tlalokes_error_msg( $e->getMessage(), true );
    }
  }

  // build structure
  if ( tlalokes_setup_create_structure( $tlalokes, $app, $_POST ) ) :
?>
          <p>
            Congratulations your new application's structure is at <?=$app;?>.
          </p>
          <p>
            Now you can <a href="<?=$uri;?>">continue</a>.
          </p>
<?/*
    if ( PATH_SEPARATOR == ':' ) :
?>
          <p><span id="title" style="font-size: 11px;"><strong>Note</strong>:
          To install the admin application automatically, Tlalokes needs you to
          write the <code>admin</code> directory with <code>757</code> permissions in
          <code><?=preg_replace( '/(.*)\/index.php$/', '$1', $_SERVER['SCRIPT_FILENAME']);?></code>.</span></p>
<?
    endif;*/
  endif;
endif;

require $tlalokes.'/functions/inc/footer.inc.php';


function tlalokes_setup_create_structure ( $tlalokes, $app, $post )
{
  if ( !file_exists( $app ) ) {
    if ( !@mkdir( $app ) ) {
      tlalokes_error_msg( 'Application path is not writeable' );
    }
  } else {
    mkdir( "$app/controller" );
    mkdir( "$app/model" );
    mkdir( "$app/model/business" );
    mkdir( "$app/model/dbo" );
    mkdir( "$app/model/dbo/def" );
    mkdir( "$app/view" );
    mkdir( "$app/_locale" );
    mkdir( "$app/_tmp" );
    mkdir( "$app/_tmp/cache" );
    mkdir( "$app/_tmp/compile" );
    mkdir( "$app/_tmp/generator" );
    if ( !tlalokes_setup_write_default_config( "$app/config.php", $post ) ) {
      tlalokes_error_msg( "Cannot create application's configuration file." );
    }
  }
  return true;
}

function tlalokes_setup_write_default_config ( $file, $post )
{
  $password = crypt( $post['pass1'], '$6$rounds=5000$67726f73656b6f6c616c74$' );
  $n = "\n";
  $str = "<?php".$n
       . "\$c['key'] = '".$password."';".$n
       . "\$c['path']['controllers'] = 'controller/';".$n
       . "\$c['path']['views']       = 'view/';".$n
       . "\$c['path']['model']       = 'model/';".$n
       . "\$c['path']['bss']         = 'model/business/';".$n
       . "\$c['path']['def']         = 'model/dbo/def/';".$n
       . "\$c['path']['orm']         = 'model/dbo/orm/';".$n
       . "\$c['path']['tmp']         = '_tmp/';".$n
       . "\$c['path']['tpl_compile'] = '_tmp/compile/';".$n
       . "\$c['path']['tpl_cache']   = '_tmp/cache/';".$n
       . "\$c['path']['locales']     = '_locale/';".$n
       . "\$c['path']['libs'] = 'lib/';".$n
       . "\$c['path']['core'] = 'core/';".$n
       . "\$c['default']['controller'] = '';".$n
       . "\$c['default']['locale']     = '';".$n
       . "\$c['default']['charset']    = 'UTF-8';".$n
       . "\$c['default']['timezone']   = '".$post['timezone']."';".$n
       . "\$c['mode']['smarty'] = 'debug';".$n;
  if ( isset( $post['dsn_type'] ) ) {
    $str .= "\$c['mode']['propel'] = 'production';".$n
          . "\$c['dsn']['type'] = '".$post['dsn_type']."';".$n
          . "\$c['dsn']['host'] = '".$post['dsn_host']."';".$n
          . "\$c['dsn']['name'] = '".$post['dsn_name']."';".$n
          . "\$c['dsn']['username'] = '".$post['dsn_username']."';".$n
          . "\$c['dsn']['password'] = '".$post['dsn_password']."';".$n
          . "\$c['execute']['crud_propel_smarty'] = false;".$n
          . "\$c['execute']['auth_propel'] = false;".$n
          . "//\$c['auth'] = 'db';".$n;
  }
  unset( $password );
  unset( $post );
  unset( $n );
  return file_put_contents( $file, $str );
}
