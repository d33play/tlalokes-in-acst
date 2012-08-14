<?php
/**
 * Tlalokes parsing functions
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
 * Parses a DocComment string
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param mixed $doc_comment
 */
function tlalokes_parser_annotations ( &$ref )
{
  // find annotations and its values
  preg_match_all( '/@(\w*)\s*\(\s*(.*)\s*\)/', $ref->getDocComment(), $match );

  // iterate annotations to get its contents
  $count = count( $match[1] );
  for ( $i = 0; $i < $count; $i++ ) {

    if ( !class_exists( $match[1][$i] ) ) {
      tlalokes_error_msg( 'Annotations: Class '.$match[1][$i].' not found.' );
    }
    $class[$i][0] = $match[1][$i];
    $class[$i][1] = new $match[1][$i];

    // check if annotation contains more than one property
    $properties = explode( ',', $match[2][$i] );
    if ( count( $properties ) > 1 ) {

      // iterate multiple properties
      $count_prop = count( $properties );
      for ( $iprop = 0; $iprop < $count_prop; ++$iprop ) {

        // validate property and get its value
        $p = tlalokes_parser_annotation_valid( $properties[$iprop],
                                                                $class[$i][1] );
        // set value on class
        $class[$i][1]->{$p[0]} = tlalokes_core_get_type( $p[1] );
        unset( $p );
      }
      unset( $count_prop );

    // process single property
    } else {

      // validate property and get its value
      $p = tlalokes_parser_annotation_valid( $properties[0], $class[$i][1] );
      // set value on class
      $class[$i][1]->{$p[0]} = tlalokes_core_get_type( $p[1] );
      unset( $p );
    }
    unset( $properties );
  }
  unset( $count );

  if ( isset( $class ) && count( $class ) >= 1 ) {

    // set annotations for return
    foreach ( $class as $c ) {

      // get an instance of the class
      $in = new $c[0];

      //$count = count( $properties );
      foreach ( get_object_vars( $in ) as $k => $v ) {
        if ( isset( $c[1]->{$k} ) ) {
          $in->$k = $c[1]->$k;
        }
      }

      // set instances in an annotations array
      $annotations[$c[0]] =& $in;

      unset( $in );
    }

    unset( $class );
  }

  return isset( $annotations ) ? $annotations : false;
}


/**
 * Validates a property of an annotation object
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param string $property
 * @param ReflectionClass $class
 */
function tlalokes_parser_annotation_valid ( $property, &$class )
{
  // check if property has key
  if ( !strstr( $property, '=' ) ) {

    // remove garbage from property's value
    $property = preg_replace( '/\s*[\'|\"]?([a-zA-Z0-9_-\s]*)\s[\'|\"]?\s*/',
                              '$1', $property );

    return array( $property, true );

  // check property's key
  } else {

    // remove spaces
    $property = str_replace( ' = ', '=', $property );

    // get key and value
    list( $key, $value ) = explode( '=', $property );

    // remove spaces
    $key = str_replace( ' ', '', $key );

    // remove garbage from property's value
    $value = preg_replace( '/\s*[\'|\"](.*)[\'|\"]\s*/', '$1', $value );

    $rc = new ReflectionClass( $class );
    // validate property existance
    if ( !$rc->hasProperty( $key ) ) {
      tlalokes_error_msg( 'Annotations: No '.$key.' property.' );
    }
    unset( $rc );

    return array( $key, $value );
  }
}
