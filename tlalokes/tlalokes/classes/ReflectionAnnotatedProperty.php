<?php
/**
 * Reflection Annotated Property
 * Copyright (C) 2009 Basilio Briceno Hernandez <bbh@tlalokes.org>
 *
 * This work is based in Addendum <http://code.google.com/p/addendum/>
 * written by Jan Suchal <johno@jsmf.net>, but this version has been modified
 * in order to be faster.
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
 * Provides methods to Reflect Annotated Properties
 *
 * @author Basilio Brice&ntilde;o Hern&aacute;ndez <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2009 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/lgpl.html GNU LGPL
 * @package tlalokes.core
 */
class ReflectionAnnotatedProperty extends ReflectionProperty {

  private $annotations;

  public function __construct ( &$class, $name )
  {
    parent::__construct( $class, $name );
		$this->annotations = tlalokes_parser_annotations( $this );
  }

  public function hasAnnotation( $annotation )
  {
    return isset( $this->annotations[$annotation] );
  }

  public function getAnnotation ( $annotation )
  {
    if ( $this->hasAnnotation( $annotation ) ) {
      return $this->annotations[$annotation];
    }
    return false;
  }

  public function getAnnotations ()
  {
    return array_values( $this->annotations );
  }

  public function getDeclaringClass ()
  {
    $class = parent::getDeclaringClass();
    return new ReflectionAnnotatedClass( $class->getName() );
  }

  public function __destruct ()
  {
    unset( $this->annotations );
  }
}
?>
