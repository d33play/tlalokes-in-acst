<?php
/**
 * Tlalokes strings functions
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
 * Removes characters cosidered part of an injection attack
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param string $string
 * @return string
 */
function tlalokes_str_sanitize ( $string )
{
  $string = str_replace( '\\', '', $string ); // remove double backslash
  $string = trim( $string ); // remove \t & \n from ^ and $
  //$string = quotemeta( trim( $string ) );
  return addslashes( $string ); // add slash to quotes ', "
}

/**
 * Transforms string to html entities and returns it as the required charset
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param string $string
 * @param string $to charset name (UTF-8, ISO-8859-1, etc.) default UTF-8
 * @return string
 */
function tlalokes_str_apply_charset ( $string, $to = 'UTF-8' )
{
  if ( is_string( $string ) ) {
    // encode special chars as HTML entities
    $string = htmlentities( $string, ENT_NOQUOTES, $to );
    // decode HTML entities and return it as the specified charset
	$string = html_entity_decode( $string, ENT_NOQUOTES, $to );
  }
  return $string;
}

/**
 * Checks if a string is a well formed email
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param string $email
 * @return boolean
 */
function tlalokes_str_valid_email ( $email )
{
  $regex = '/[\w.]*@+[\w.]{3,}.\w{2,3}.?\w{0,2}/';
  if ( preg_match( $regex, $email )  ) {
    return true;
  }
  return false;
}

/**
 * Transforms an string like my_string to MyString
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param string $string
 * @return string
 */
function tlalokes_str_change_format ( $string )
{
  if ( preg_match( '/_/', $string ) ) {
    $response = '';
    foreach ( explode( '_', $string ) as $words ) {
      $response .= ucfirst( $words );
    }
  }
  return isset( $response ) ? $response : ucfirst( $string );
}

/**
 * Returns true or false if a search is in a string
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param string $search
 * @param string $subject
 * @return boolean
 */
function tlalokes_str_found ( $search, $subject )
{
  $search = strtolower( $search );
  $subject = strtolower( $subject );
  return !strstr( $subject, $search ) ? false : true;
}

/**
 * Set Response properties as HTML entities and accept HTML tags
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param mixed $value
 * @return mixed
 */
function tlalokes_str_to_html ( $value, $charset = 'UTF-8' )
{
  if ( is_array( $value ) ) {
    foreach ( $value as $key => $item ) {
      $value[$key] = tlalokes_str_to_html( $item );
    }
    $response = $value;
  } else {
//    $response = is_string( $value )
//                ? htmlentities( $value, ENT_QUOTES, $charset ) : $value;
    $response = is_string( $value )
    			? str_replace( array( '&lt;', '&gt;' ), array( '<', '>' ), htmlentities( $value, ENT_QUOTES, $charset ) ) : $value;
  }
  return $response;
}

/**
 * Transforms an array into a string
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param mixed $element
 * @param mixed $parent;
 */
function tlalokes_str_from_array ( $element, $parent = false )
{
  $response = '';
  if ( is_array( $element ) ) {
    foreach ( $element as $k => $v ) {
      $parent = preg_replace( '/(\[\')*(.*)(\'\])*/', '$2', $parent );
      $key = ( $parent ? "['$parent']" : '' ) . "['$k']";
      $response .= tlalokes_str_from_array( $v, $key );
    }
  } else {
    $response .= str_replace( "']']", "']", $parent )." = '$element';\n" ;
  }
  return $response;
}
