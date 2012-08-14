<?php
/**
 * Reference Definition class
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
 * Provides the structure for annotation ReferenceDef
 *
 * @author Basilio Briceno Hernandez <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2009 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/lgpl.html GNU LGPL
 * @package tlalokes.core.annotations
 */
class ReferenceDef {

  public $table;
  public $column;
  public $onDelete;
  public $onUpdate;

  /**
   * Returns this object as string
   *
   * @return string
   */
  public function getString ()
  {
    $r = " * @ReferenceDef( table='".$this->table."', " .
         "column='".$this->column."'";
    if ( $this->onDelete ) {
      $r .= ", onDelete='".$this->onDelete."'";
    }
    if ( $this->onUpdate ) {
      $r .= ", onUpdate='".$this->onUpdate."'";
    }
    return $r . " )\n";
  }
}
?>
