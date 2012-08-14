<?php
/**
 * Reflection Annotated Class
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
 * Provides methods to Reflect Annotated Classes
 *
 * @author Basilio Brice&ntilde;o Hern&aacute;ndez <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2009 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/lgpl.html GNU LGPL
 * @package tlalokes.core
 */
class ReflectionAnnotatedClass extends ReflectionClass {

	private $annotations;

	public function __construct ( &$class )
	{
		parent::__construct( $class );
	  $this->annotations = tlalokes_parser_annotations( $this );
	}

	public function hasAnnotation ( $annotation )
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

	public function getAnnotations()
	{
		return array_values( $this->annotations );
	}

	public function getConstructor()
	{
		return $this->createReflectionAnnotatedMethod( parent::getConstructor() );
	}

	public function getMethod ( $name )
	{
		return $this->createReflectionAnnotatedMethod( parent::getMethod( $name ) );
	}

	public function getMethods()
	{
		$result = array();
		foreach ( parent::getMethods() as $method ) {
			$result[] = $this->createReflectionAnnotatedMethod( $method );
		}
		return $result;
	}

	public function getProperty ( $name )
	{
	  $property = parent::getProperty( $name );
		return $this->createReflectionAnnotatedProperty( $property );
	}

	public function getProperties()
	{
		$result = array();
		foreach( parent::getProperties() as $property ) {
			$result[] = $this->createReflectionAnnotatedProperty( $property );
		}
		return $result;
	}

	public function getInterfaces()
	{
		$result = array();
		foreach( parent::getInterfaces() as $interface ) {
			$result[] = $this->createReflectionAnnotatedClass( $interface );
		}
		return $result;
	}

	public function getParentClass()
	{
		$class = parent::getParentClass();
		return $this->createReflectionAnnotatedClass( $class );
	}

	private function createReflectionAnnotatedClass ( $class )
	{
		return $class !== false
		       ? new ReflectionAnnotatedClass( $class->getName() ) : false;
	}

	private function createReflectionAnnotatedMethod ( $method )
	{
		return $method !== null
		       ? new ReflectionAnnotatedMethod( $this->getName(),
		                                        $method->getName() ) : null;
	}

	private function createReflectionAnnotatedProperty( $property )
	{
		return $property !== null
		       ? new ReflectionAnnotatedProperty( $this->getName(),
		                                          $property->getName() ) : null;
	}

  public function __destruct ()
  {
    unset( $this->annotations );
  }
}
?>
