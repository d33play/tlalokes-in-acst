<?php
/**
 * Tlalokes filesystem's functions
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
 * Removes a directory in a recursive way
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param string $path
 */
function tlalokes_rm_dir ( $path )
{
  if ( !file_exists( $path ) ) {
    tlalokes_error_msg( 'Directory provided not existant' );
  }
  if ( !is_writable( $path ) ) {
    tlalokes_error_msg( "Directory or file isn't writeable" );
  }
  // check if $path is directory or file
  if ( is_dir( $path ) ) {
    // iterate $path directory
    foreach ( glob( $path . '/*' ) as $item ) {
      // recursive call content in $item
      tlalokes_rm_dir( $item );
    }
    // remove directory $path
    rmdir( $path );
  } else {
    // remove file $path
    unlink( $path );
  }
}

/**
 * Saves files from the temportal path to the preconfigured path
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param stdObj $files
 */
function tlalokes_uploads_save ( &$files )
{
  foreach ( $files->raw as $file ) {
    if ( $file['size'] ) {
      $from =& $file['tmp_name'];
      $to = $files->path['absolute'] . $file['name'];
      if ( !@copy( $from, $to ) ) {
        tlalokes_error_msg( 'Upload save: Cannot write into ' .
                            $files->path['absolute'] );
      }
      unset( $from );
      unset( $to );
    }
  }
}

/**
 * Returns a file update that complain the filter's rules
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param stdObj $files
 * @param array $filter
 */
function tlalokes_uploads_filter ( &$file, array $filter )
{
  $_file = array();
  // filter by size
  if ( isset( $filter['size'] ) ) {
    if ( $filter['size'] >= $file['size'] ) {
      $_file = $file;
    }
  }
  // filter by type
  if ( isset( $filter['type'] ) ) {
    $types = explode( ',', $filter['type'] );
    foreach ( $types as $type ) {
      if ( preg_match( '/'.$type.'/', $file['type'] ) ) {
        $_file = $file;
      }
    }
  }
  return $_file;
}