<?php
/**
 * Tlalokes admin setup interfase
 * Copyright (C) 2009 Basilio Briceno Hernandez <bbh@tlalokes.org>
 * This code will return for version 1.2
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

$title = 'Admin Setup';

$remote = 'http://tlalokes.org/download/tlalokes-admin.latest';

$local = preg_replace( '/(.*)\/index.php$/', '$1', $_SERVER['SCRIPT_FILENAME']);

require $tlalokes.'/functions/inc/header.inc.php';

if ( extension_loaded( 'zip' ) ) :

  $admin = $local.'/admin';
  if ( PATH_SEPARATOR == ':' ) {
    if ( !file_exists( $admin ) ) {
      throw new Exception( 'Create the admin directory at '.$local );
    }
    if ( !is_writable( $admin ) ) {
      throw new Exception( 'Path '.$admin.' is not writeable.' );
    }
  }

  // download file and save in system's temporal directory
  $tmp_path = sys_get_temp_dir() . '/tlalokes_admin.zip';
  if ( !file_exists( $tmp_path ) ) {

    $remote = $remote . '.zip';

    $file = @fopen( $remote, 'rb' );
    if ( !$file ) {
      throw new Exception( 'File not available or not connected.' );
    }
    fclose( $file );

    file_put_contents( $tmp_path, file_get_contents( $remote ) );
  }

  // unzip file
  $zip = new ZipArchive;
  if ( $zip->open( $tmp_path ) === true ) {
    $zip->extractTo( $local );
    $zip->close();

    // rewrite the index file
    file_put_contents( $local.'/admin/index.php',
                       tlalokes_admin_get_index( $tlalokes, $app, $local ) );
  } else {
    throw new Exception( 'Extension error trying to unzip '.$tmp_path );
  }

  unlink( $tmp_path );
?>
  <p>Congratulations, the admin application has been installed, to load it click
  <a href="<?=$uri;?>admin">here</a>.</p>
<?
else :

  // if OS is UNIX use tar
  if ( PATH_SEPARATOR == ':' ) :

    // check if command tar is available
    if ( exec( 'tar --help' ) ) :

      $admin = $local.'/admin';
      if ( !file_exists( $admin ) ) {
        tlalokes_error_msg( 'Create the admin directory at $local.' );
      }
      if ( !is_writable( $admin ) ) {
        tlalokes_error_msg( 'Destination path '.$admin.' must be writeable.' );
      }
      unset( $admin );

      // download file and save in system's temporal directory
      $tmp_path = sys_get_temp_dir() . '/tlalokes_admin.tar';
      if ( !file_exists( $tmp_path ) ) {

        $remote = $remote . '.tar';

        $file = @fopen( $remote, 'rb' );
        if ( !$file ) {
          throw new Exception( 'File not available or not connected.' );
        }
        fclose( $file );

        file_put_contents( $tmp_path, file_get_contents( $remote ) );
      }

      // execute tar on system
      $cmd = 'tar -xf '.$tmp_path.' -C '.$local;
      system( $cmd );
      if ( exec( 'ls -l '.$local.'/admin' ) == 'total 0' ) {
        tlalokes_error_msg( 'System error executing '.$cmd  );
      }
      unlink( $tmp_path );

      // rewrite the index file
      file_put_contents( $local.'/admin/index.php',
                         tlalokes_admin_get_index( $tlalokes, $app, $local ) );
?>
  <p>Congratulations, the admin application has been installed, to load it click
  <a href="<?=$uri;?>admin">here</a>.</p>
<?
    endif;

  else :
?>
  <p>You do not have the binary extension
  (<a href="http://www.php.net/manual/en/book.zip.php">zip</a>) required to
  install the <strong>admin</strong> application automatically. If you want to
  install it automatically, install one that extension and
  <a href="javascript:location.reload();">reload</a> this page.</p>
  <p>Also you can install it <strong>manually</strong> following this guide:</p>
  <ul>
    <li>Download the admin application from
    <a href="http://tlalokes.org/download/tlalokes-admin.v1.0a.tar.bz2">here</a>
    (bzip2 tarball) or
    <a href="http://tlalokes.org/download/tlalokes-admin.v1.0a.zip">here</a>
    (zip).</li>
    <li>Uncompress it in this server at <code><?=$local;?></code>
<?
    if ( PATH_SEPARATOR == ':' ) :
?>
    <br/>
    Examples:
      <ul>
        <li><code>$ tar xvfj tlalokes-admin.v1.0a.tar.bz2</code></li>
        <li><code>$ unzip tlalokes-admin.v1.0a.zip</code></li>
      </ul>
    </li>
    <li>Set writing permissions to <code>admin/_tmp/compile</code>
      <ul>
        <li>If you have superuser access, set the web server's user as the
        owner.<br>Examples:
          <ul>
            <li><code># chown www admin/_tmp/compile</code></li>
            <li><code># chown nobody admin/_tmp/compile</code></li>
            <li><code># chown www-data admin/_tmp/compile</code></li>
          </ul>
        <li>If you have no super user access, change the permissions.<br/>
        Examples:
          <ul>
            <li><code>$ chmod a+w admin/_tmp/compile</code></li>
            <li><code>$ chmod 757 admin/_tmp/compile</code></li>
          </ul>
        </li>
      </ul>
<?
    endif;
?>
    </li>
    <li>Edit the index.php file and replace it's contents with this one:<br/>
      <textarea style="width:550px;height:320px;border:solid gray 1px;font-size:11px;">
<?=tlalokes_admin_get_index( $tlalokes, $app, $local );?></textarea>
    </li>
    <li>Finally click <a href="<?=$uri;?>admin">here</a>.</li>
  </ul>
<?
  endif;

endif;

require $tlalokes.'/functions/inc/footer.inc.php';

function tlalokes_admin_get_index ( $tlalokes, $application, $public )
{
  return "<?php\n" .
         "// Tlalokes framework's path\n" .
         "\$tlalokes = '".$tlalokes."';\n\n" .
         "// Application path\n" .
         "\$application = '".$application."';\n\n" .
         "// Public path\n" .
         "\$public = '$public';\n\n" .
         "// Admin's path\n" .
         "\$app = str_replace( '/index.php', '', \$_SERVER['SCRIPT_FILENAME'] );\n\n" .
         "// URI\n" .
         "\$uri = str_replace( 'index.php', '', \$_SERVER['SCRIPT_NAME'] );\n\n" .
         "// Admin's flag\n" .
         "\$admin = true;\n\n" .
         "// Load receiver\n" .
         "include \"\$tlalokes/receiver.php\";";
}