<?
/**
 * Tlalokes Applications Administrator Definition's Object
 * Copyright (C) 2008 Basilio Briceno Hernandez <bbh@tampico.org.mx>
 *
 * This file is part of the Tlalokes Applications Administrator.
 *
 * Tlalokes Applications Administrator is free software: you can redistribute
 * it and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * Tlalokes Applications Administrator is distributed in the hope that
 * it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tlalokes Applications Administrador.
 * If not, see <http://www.gnu.org/licenses/>
 */

require_once 'ReflectionAnnotatedProperty.php';
require_once 'DefinitionObject.php';
//require_once 'TableDef.php';
//require_once 'ColumnDef.php';

/**
 * Component provive access to a definition's object's methods & properties
 *
 * @author Basilio Brice&ntilde;o H. <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2008 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL
 * @version 0.2
 * @package tlalokes.apps.admin.model
 */
class DefObj {

  private $file_name;
  public $table;
  public $columns;
  public $build;

  public function __construct( $class_name, $path )
  {
    $this->file_name = $path;
    // load definition object from file
    $this->reflectClass( $class_name );
  }

  private function reflectClass ( $class_name )
  {
    require_once $this->file_name;

    // reflect class
    $class = new ReflectionAnnotatedClass( $class_name );
    // reflect @DefinitionObject
    if ( $class_annotation = $class->getAnnotation( 'DefinitionObject' ) ) {
      // set table
      $table = new DefinitionObject;
      $table->table = $class_annotation->table;
      $this->setTable( $class->getName(), $table );
      unset( $table );

      if ( isset( $class_annotation->build ) && $class_annotation->build ) {
        $this->build = $class_annotation->build;
      }

      // iterate properties
      foreach ( $class->getProperties() as $property ) {

        $ref_col = $property->getAnnotation( 'DefinitionObject' );
        // create column
        $column = new DefinitionObject;
        $column->column = $ref_col->column;
        $column->type = $ref_col->type;
        $column->size = $ref_col->size;
        $column->scale = $ref_col->scale;
        $column->default = $ref_col->default;
        $column->required = $ref_col->required;
        $column->autoIncrement = $ref_col->autoIncrement;
        $column->primaryKey = $ref_col->primaryKey;
        $column->index = $ref_col->index;
        $column->unique = $ref_col->unique;

        // reflect @ReferenceDef
        if ( $ref_ref = $property->getAnnotation( 'ReferenceDef' ) ) {
          // create reference
          $ref = new ReferenceDef();
          $ref->table = $ref_ref->table;
          $ref->column = $ref_ref->column;
          $ref->onUpdate = $ref_ref->onUpdate;
          $ref->onDelete = $ref_ref->onDelete;
        }

        // set column string
        $this->setColumn( $property->getName(), $column, isset( $ref )
                                                         ? $ref : false );
        unset( $ref );
        unset( $column );
      }
    }
  }

  public function getString ()
  {
    $r = "<?\n";
    foreach ( $this->table as $name => $value ) {
      $r .= "/**\n  " . $value->getString() . "\n  */\nclass {$name} {\n\n";
    }
    foreach ( $this->columns as $name => $value ) {
      $r .= "/**\n" . $value->column->getString();
      $r .= isset( $value->reference ) ? $value->reference->getString() : '';
      $r .= " */\npublic \${$name};\n";
    }
    return $r . "\n}\n?>";
  }

  public function write ()
  {
    file_put_contents( $this->file_name, $this->getString() );
  }

  public function setTable ( $name, DefinitionObject $table )
  {
    $this->table->{$name} = $table;
  }

  public function setColumn ( $name, DefinitionObject $column, $reference = false )
  {
    $this->columns->{$name}->{'column'} = $column;
    if ( $reference ) {
      $this->columns->{$name}->{'reference'} = $reference;
    }
  }
}