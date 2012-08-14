  <?
/**
 * Model View Controller Generator
 * Copyright (C) 2009 Basilio Briceno Hernandez <bbh@tlalokes.org>
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
 * If not, see <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Generates generic Model, View, Controller & Locale files
 *
 * @author Basilio Briceno Hernandez <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2008 Basilio Briceno Hernandez
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL
 * @version 1.0
 */
class MVCGenerator {

  private $conf;
  public  $name;
  private $table;
  private $columns;
  public $build;
  private $build_path;

  public function __construct ( $conf, $def_path )
  {
    $this->conf = $conf;

    $this->build_path = $conf['path']['builder'].'/';

    $def_name = preg_replace( '/.*\/([a-zA-Z0-9]*)Def.php$/', '$1', $def_path );

    $do = new DefObj( $def_name.'Def', $def_path );

    $this->build = $do->build;
    $this->columns = $do->columns;
    $this->name = $def_name;

    unset( $do_path );
    unset( $do );
  }

  public function generate ()
  {
    $controller = $this->conf['path']['app'].
                  $this->conf['path']['controllers'].$this->name . 'Ctl.php';
    $model = $this->conf['path']['app'].$this->conf['path']['bss'].
             $this->name . 'Bss.php';
    $view = $this->conf['path']['app'].$this->conf['path']['views'].
            strtolower( $this->name ) . '.tpl';
    $locale = $this->conf['path']['app'].$this->conf['path']['locales'].'eng.php';

    if ( !@file_put_contents( $controller, $this->getController(), LOCK_EX ) ) {
      tlalokes_error_msg( 'Builder: Cannot write '.$controller, true );
    }
    echo "<p>$this->name controller generated</p>\n";

    if ( !@file_put_contents( $model, $this->getModel(), LOCK_EX ) ) {
      tlalokes_error_msg( 'Builder: Cannot write '.$model, true );
    }
    echo "<p>$this->name model generated</p>\n";

    if ( !@file_put_contents( $view, $this->getView(), LOCK_EX ) ) {
      tlalokes_error_msg( 'Builder: Cannot write '.$view, true );
    }
    echo "<p>$this->name view generated</p>\n";

    if ( !@file_put_contents( $locale, $this->getLocale( $locale ), LOCK_EX ) ) {
      tlalokes_error_msg( 'Builder: Cannot write '.$locale, true );
    }
    echo "<p>English locale generated</p>\n";

    $head = $this->conf['path']['app'].'/'.$this->conf['path']['views'].'head.tpl';
    $foot = $this->conf['path']['app'].'/'.$this->conf['path']['views'].'foot.tpl';
    if ( !file_exists( $head ) ) {
      if ( !copy( $this->build_path.'views/head.tpl', $head ) ) {
        tlalokes_error_msg( "Builder: Cannot copy head.tpl", true );
      }
      echo "<p>Copied head.tpl</p>\n";
    }
    if ( !file_exists( $foot ) ) {
      if ( !copy( $this->build_path.'views/foot.tpl', $foot) ) {
        tlalokes_error_msg( "Builder: Cannot copy foot.tpl", true );
      }
      echo "<p>Copied foot.tpl</p>\n";
    }
  }

  /**
   * Returns a string with the code for a controller class
   *
   * @return string
   */
  private function getController ()
  {
    $name = $this->name;
    $namelow = strtolower( $name );
    $columns =& $this->columns;

    require $this->build_path.'templates/controller.php';
    return $str;
  }

  /**
   * Returns a string with the code for a model class
   *
   * @return string
   */
  private function getModel ()
  {
    $name = $this->name;
    $columns =& $this->columns;

    require $this->build_path.'templates/model.php';
    return $str;
  }

  /**
   * Returns a string with the code for a view
   *
   * @return string
   */
  private function getView ()
  {
    $name = $this->name;
    $columns =& $this->columns;

    require $this->build_path.'templates/view.php';
    return $str;
  }

  /**
   * Returns a string with the code for a locale
   *
   * @return string
   */
  private function getLocale ( $path )
  {
    if ( file_exists( $path ) ) {
      require $path;
    }
    $locale['name'] = 'English';

    $l['home'] = 'Home';
    $l['back'] = 'Back';
    $l['next'] = 'Next';
    $l['page'] = 'Page';
    $l['of'] = 'of';

    $l['controllers']["{$this->name}Ctl"]['title'] = $this->name;
    foreach ( $this->columns as $name => $column ) {
      $l['controllers']["{$this->name}Ctl"][$name] = $name;
    }
    $l['controllers']["{$this->name}Ctl"]['add'] = 'Add';
    $l['controllers']["{$this->name}Ctl"]['edit'] = 'Edit';
    $l['controllers']["{$this->name}Ctl"]['delete'] = 'Delete';
    $l['controllers']["{$this->name}Ctl"]['save'] = 'Save';
    $l['controllers']["{$this->name}Ctl"]['filter'] = 'Filter';
    $l['controllers']["{$this->name}Ctl"]['true'] = 'True';
    $l['controllers']["{$this->name}Ctl"]['false'] = 'False';

    $nodes = tlalokes_str_from_array( $l );
    $nodes = preg_replace( '/^(\[.*)\n/m', "\$l$1\n", $nodes );

    $info = tlalokes_str_from_array( $locale );
    $info = preg_replace( '/^(\[.*)\n/m', "\$locale$1\n", $info );

    return "<?\n$info\n$nodes";
  }

  /**
   * Destroy properties after use
   */
  public function __destruct ()
  {
    foreach ( get_object_vars( $this ) as $key => $var ) {
      if ( $this->$key ) {
        unset( $this->$key );
      }
    }
  }
}