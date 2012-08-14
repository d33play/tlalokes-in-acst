<?
/**
 * Test builder functions
 * Copyright (C) 2009 Basilio Briceno Hernandez <bbh@tlalokes.org>
 * This builder copies a controller with two actions, its views and two
 * locale files, is just for demonstration.
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
 * Main function for the Test builder
 *
 * @param array $conf
 * @author Basilio Briceno <bbh@tlalokes.org>
 */
function test_builder_main ( &$conf )
{
  $builder = $conf['path']['builder'].'/';

  $ctllr = $conf['path']['app'].$conf['path']['controllers'];
  $views = $conf['path']['app'].$conf['path']['views'];
  $local = $conf['path']['app'].$conf['path']['locales'];

  // copy controller
  if ( !file_exists( $builder.'templates/controller/TestCtl.php' ) ) {
    if ( !copy( $builder.'templates/controller/TestCtl.php', $ctllr.'TestCtl.php' ) ) {
      tlalokes_error_msg( 'Test builder: Cannot copy TestCtl.php', true );
    }
    echo "<p>Test controller copied</p>";
  }

  // copy templates
  if ( !file_exists( $builder.'templates/view/one.tpl' ) ) {
    if ( !copy( $builder.'templates/view/one.tpl', $views.'one.tpl' ) ) {
      tlalokes_error_msg( 'Test builder: Cannot copy one.tpl', true );
    }
    echo "<p>Test template one copied</p>\n";
  }
  if ( !file_exists( $builder.'templates/view/two.tpl' ) ) {
    if ( !copy( $builder.'templates/view/two.tpl', $views.'two.tpl' ) ) {
      tlalokes_error_msg( 'Test builder: Cannot copy two.tpl', true );
    }
    echo "<p>Test template two copied</p>\n";
  }

  // copy locales
  if ( !file_exists( $builder.'templates/_locale/eng.php' ) ) {
    if ( !copy( $builder.'templates/_locale/eng.php', $local.'eng.php' ) ) {
      tlalokes_error_msg( 'Test builder: Cannot copy eng.php', true );
    }
    echo "<p>Test locale english copied</p>\n";
  }
  if ( !file_exists( $builder.'templates/_locale/spa.php' ) ) {
    if ( !copy( $builder.'templates/_locale/spa.php', $local.'spa.php' ) ) {
      tlalokes_error_msg( 'Test builder: Cannot copy spa.php', true );
    }
    echo "<p>Test locale spanish copied</p>\n";
  }
}