<?
/**
 * Application Configuration
 * Copyright (C) 2007 Basilio Briceno Hernandez <bbh@tlalokes.org>
 *
 * This file is part of the Tlalokes Administration User Interfase.
 *
 * Tlalokes Administration User Interfase is free software: you can redistribute
 * it and/or modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation, version 3 of the License.
 *
 * Tlalokes Administration User Interfase is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Tlalokes Administration User Interfase.
 * If not, see <http://www.gnu.org/licenses/gpl.html>.
 */

/**
 * Provides access to the application configuration file
 *
 * @author Basilio Brice&ntilde;o Hern&aacute;ndez <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2007 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL
 * @package tlalokes.admin.model
 */
class AppConfig {

  public $file;

  public $key;

  //public $path_tlalokes;
  public $path_app;

  public $path_controllers;
  public $path_views;
  public $path_model;
  public $path_bss;
  public $path_def;
  public $path_orm;
  public $path_tmp;
  public $path_tpl_compile;
  public $path_tpl_cache;
  public $path_languages;

  public $path_libs;
  public $path_core;

  public $default_controller;
  public $default_language;
  public $default_charset;

  public $mode_database;
  public $mode_templates;

  public $dsn_type;
  public $dsn_host;
  public $dsn_name;
  public $dsn_username;
  public $dsn_password;

  public function __construct ( $path )
  {
    try {
      $file = $path['application'] . '/config.php';

      if ( !file_exists( $file ) ) {
        throw new ModelException('Application: There is no configuration file');
      }
      $this->file = $file;

      require $this->file;
      unset( $file );

      $this->key              = $c['key'];
      //$this->path_tlalokes   = $path->tlalokes;
      $this->path_app         = $path['application'];
      $this->path_controllers = $c['path']['controllers'];
      $this->path_views       = $c['path']['views'];
      $this->path_model       = $c['path']['model'];
      $this->path_bss         = $c['path']['bss'];
      $this->path_def         = $c['path']['def'];
      $this->path_orm         = $c['path']['orm'];
      $this->path_tmp         = $c['path']['tmp'];
      $this->path_tpl_compile = $c['path']['tpl_compile'];
      $this->path_tpl_cache   = $c['path']['tpl_cache'];
      $this->path_languages   = $c['path']['languages'];
      $this->path_libs        = $c['path']['libs'];
      $this->path_core        = $c['path']['core'];

      $this->default_controller = $c['default']['controller'];
      $this->default_language   = $c['default']['language'];
      $this->default_charset    = $c['default']['charset'];

      $this->mode_database  = $c['mode']['database'];
      $this->mode_templates = $c['mode']['templates'];

      $this->dsn_type     = $c['dsn']['type'];
      $this->dsn_host     = $c['dsn']['host'];
      $this->dsn_name     = $c['dsn']['name'];
      $this->dsn_username = $c['dsn']['username'];
      $this->dsn_password = $c['dsn']['password'];

    } catch ( ModelException $e ) {
      echo $e->getMessage();
      exit;
    }
  }

  public function write ()
  {
    try {
      /*if ( !$this->path_tlalokes ) {
        throw new ModelException( 'Set tlalokes path' );
      }
      if ( !$this->path_app ) {
        throw new ModelException( 'Set application path' );
      }*/
      if ( !$this->path_controllers ) {
        throw new ModelException( 'Set controllers path' );
      }
      if ( !$this->path_languages ) {
        throw new ModelException( 'Set languages path' );
      }
      if ( !$this->path_views ) {
        throw new ModelException( 'Set views path' );
      }
      if ( !$this->path_model ) {
        throw new ModelException( 'Set model path' );
      }
      if ( !$this->path_def ) {
        throw new ModelException( 'Set definition objects path' );
      }
      if ( !$this->path_orm ) {
        throw new ModelException( 'Set ORMs path' );
      }
      if ( !$this->path_tpl_compile ) {
        throw new ModelException( 'Set compile templates path' );
      }
      if ( !$this->path_tpl_cache ) {
        throw new ModelException( 'Set cache templates path' );
      }
      if ( !$this->path_tmp ) {
        throw new ModelException( 'Set temporal path' );
      }
      if ( !$this->path_libs ) {
        throw new ModelException( 'Set libraries path' );
      }
      if ( !$this->path_core ) {
        throw new ModelException( 'Set core objects path' );
      }
      if ( !$this->mode_database ) {
        throw new ModelException( 'Set database mode' );
      }
      if ( !$this->mode_templates ) {
        throw new ModelException( 'Set templates mode' );
      }
      $str = "<?\n"
           . '$c[\'key\'] = \'' . $this->key . "';\n"
           //. '$c[\'path\'][\'tlalokes\'] = isset( $tlalokes ) ? $tlalokes : \'\';'."\n"
           //. '$c[\'path\'][\'app\'] = isset( $app ) ? $app : \'\';'."\n"
           //. '$c[\'path\'][\'uri\'] = isset( $uri ) ? $uri : \'\';'."\n"
           . '$c[\'path\'][\'controllers\'] = \''.$this->path_controllers.''."';\n"
           . '$c[\'path\'][\'views\']       = \''.$this->path_views.''."';\n"
           . '$c[\'path\'][\'model\']       = \''.$this->path_model.''."';\n"
           . '$c[\'path\'][\'bss\']         = \''.$this->path_bss.''."';\n"
           . '$c[\'path\'][\'def\']         = \''.$this->path_def.''."';\n"
           . '$c[\'path\'][\'orm\']         = \''.$this->path_orm.''."';\n"
           . '$c[\'path\'][\'tmp\']         = \''.$this->path_tmp.''."';\n"
           . '$c[\'path\'][\'tpl_compile\'] = \''.$this->path_tpl_compile.''."';\n"
           . '$c[\'path\'][\'tpl_cache\']   = \''.$this->path_tpl_cache.''."';\n"
           . '$c[\'path\'][\'languages\']   = \''.$this->path_languages.''."';\n"
           . '$c[\'path\'][\'libs\'] = \''.$this->path_libs.''."';\n"
           . '$c[\'path\'][\'core\'] = \''.$this->path_core.''."';\n"
           . '$c[\'default\'][\'controller\'] = \''.$this->default_controller."';\n"
           . '$c[\'default\'][\'language\']   = \''.$this->default_language."';\n"
           . '$c[\'default\'][\'charset\']    = \''.$this->default_charset."';\n"
           . '$c[\'mode\'][\'database\']  = \''.$this->mode_database."';\n"
           . '$c[\'mode\'][\'templates\'] = \''.$this->mode_templates."';\n"
           . '$c[\'dsn\'][\'type\'] = \''.$this->dsn_type."';\n"
           . '$c[\'dsn\'][\'host\'] = \''.$this->dsn_host."';\n"
           . '$c[\'dsn\'][\'name\'] = \''.$this->dsn_name."';\n"
           . '$c[\'dsn\'][\'username\'] = \''.$this->dsn_username."';\n"
           . '$c[\'dsn\'][\'password\'] = \''.$this->dsn_password."';\n"
           . '?>';
      file_put_contents( $this->file, $str );
    } catch ( ModelException $e ) {
      echo $e->getMessage();
      exit;
    }
  }
}