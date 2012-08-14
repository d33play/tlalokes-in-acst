<?
/**
 * Tlalokes Response
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
 * Provides response properties
 *
 * @author Basilio Brice&ntilde;o Hern&aacute;ndez <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2009 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/lgpl.html GNU LGPL
 * @package tlalokes.core
 */
class TlalokesResponse {

  /**
   * Builds Response and sets locale
   *
   * @param array $locale
   */
  public function __construct ( array &$locale )
  {
    foreach ( $locale as $key => $value ) {
      $this->{$key} = self::recursiveSet( $key, $value );
    }
  }

  /**
   * Proceses every property before being setted
   *
   * @param string $name
   * @param mixed $value
   */
  public function __set ( $name, $value )
  {
    if ( $name != 'locale' ) {
      $value = tlalokes_core_get_type( $value );
      $this->{$name} = self::recursiveSet( $name, $value );
    }
  }

  /**
   * Removes slashes and set order in a recursive way
   *
   * @param string $name
   * @param mixed $value
   * @return mixed
   */
  private static function recursiveSet ( $name, $value )
  {
    if ( is_array( $value ) ) {
      foreach( $value as $key => $item ) {
        $response[$key] = self::recursiveSet( $key, $item );
      }
    } else {
      $response = is_string( $value ) ? stripslashes($value) : $value;
    }
    return isset( $response ) ? $response : '';
  }
}