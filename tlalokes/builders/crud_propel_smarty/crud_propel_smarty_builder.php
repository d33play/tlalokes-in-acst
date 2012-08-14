<?
/**
 * CRUD builder functions
 * Copyright (C) 2009 Basilio Briceno Hernandez <bbh@tlalokes.org>
 * This builder generates controllers, models and views from a definition object
 * the definition object must contain the @DefinitionObject annotation and the
 * property 'build' in order to generate the code.
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
 * Main function for the CRUD builder with propel and smarty
 *
 * @param array $conf
 * @author Basilio Briceno <bbh@tlalokes.org>
 */
function crud_propel_smarty_builder_main ( &$reg )
{
  $conf =& $reg->conf;

  $def_path = $conf['path']['app'].$conf['path']['def'];

  // validate directory existance
  if ( !file_exists( $def_path ) ) {
    tlalokes_error_msg("Definition Objects directory ($def_path) not existant");
  }

  require_once 'classes/MVCGenerator.php';
  require_once 'classes/DefObj.php';

  // check definition objects
  foreach ( glob( $def_path . '*Def.php' ) as $defile ) {

    // check if Definition Object is marked for build
    $mvc = new MVCGenerator( $conf, $defile );
    if ( $mvc->build ) {
      $mvc->generate();
      echo "<p>{$mvc->name} CRUD has been generated.</p>\n";
    }
  }
}