<?php
/**
 * Definition Object class
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
 * Provides the structure for @ColumnDef
 *
 * @author Basilio Briceno Hernandez <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2009 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/lgpl.html GNU LGPL
 * @package tlalokes.core.annotations
 */
class DefinitionObject {

  public $table;
  public $column;
  public $type;
  public $size;
  public $scale;
  public $default;
  public $required;
  public $autoIncrement;
  public $primaryKey;
  public $index;
  public $unique;

  public $build;

  /**
   * Returns DefinitonObject as an Annotation's string
   *
   * @return string
   */
  public function getString ()
  {
    $r = " * @DefinitionObject( ";
    if ( $this->table ) {
      $r .= "table='".$this->table."'";
      if ( $this->build ) {
        $r .= ", build";
      }
    } elseif ( $this->column ) {
      $r .= "column='".$this->column."'";
      if ( $this->type ) {
        $r .= ", type='".$this->type."'";
      }
      if ( $this->size ) {
        $r .= ", size='".$this->size."'";
      }
      if ( $this->scale ) {
        $r .= ", scale='".$this->scale."'";
      }
      if ( $this->default ) {
        $r .= ", default='".$this->default."'";
      }
      if ( $this->required == 'true' ) {
        $r .= ", required='true'";
      }
      if ( $this->autoIncrement  == 'true' ) {
        $r .= ", autoIncrement='true'";
      }
      if ( $this->primaryKey == 'true' ) {
        $r .= ", primaryKey='true'";
      }
      if ( $this->index == 'true' ) {
        $r .= ", index='true'";
      }
      if ( $this->unique == 'true' ) {
        $r .= ", unique='true'";
      }
    }
    $r .= " )\n";
    return $r;
  }
}
