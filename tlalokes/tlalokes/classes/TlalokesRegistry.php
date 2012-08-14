<?php
/**
 * Tlalokes Registry
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
 * Provides an object to use the Registry Pattern Design
 *
 * @author Basilio Brice&ntilde;o Hern&aacute;ndez <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2009 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/lgpl.html GNU LGPL
 * @package tlalokes.core
 */
class TlalokesRegistry {

  private static $_instance;

  /**
   * Returns an instance of itself
   *
   * @return TlalokesRegistry
   */
  public static function instance ()
  {
    if ( self::$_instance == null ) {
      self::$_instance = new TlalokesRegistry;
    }
    return self::$_instance;
  }

  /**
   * Provides a method to assign a dynamic property
   *
   * @param string $name
   * @param mixed $value
   */
  public function __set ( $name, $value )
  {
    $this->{$name} = $value;
  }

  /**
   * Provides a method to obtain a dynamic property
   *
   * @param string $name
   * @return mixed
   */
  public function __get ( $name )
  {
    return isset( $this->{$name} ) ? $this->{$name} : false;
  }

  /**
   * Unsets every property
   */
  public function __destruct()
  {
    foreach ( get_object_vars( $this ) as $key => $var ) {
      unset( $this->{$key} );
    }
  }
}
?>
