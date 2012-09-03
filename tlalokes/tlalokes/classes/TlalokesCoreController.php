<?
/**
 * Tlalokes Core Controller
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

require 'TlalokesResponse.php';
require 'ReflectionAnnotatedMethod.php';
require 'ControllerDefinition.php';
require 'ActionDefinition.php';

/**
 * Provides necesary methods and properties to load the controller
 *
 * @author Basilio Brice&ntilde;o Hern&aacute;ndez <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2009 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/lgpl.html GNU LGPL
 * @version 1.0
 * @package tlalokes.core
 */
abstract class TlalokesCoreController {

  public $response;
  public $default_locale;
  protected $request;
  protected $charset;
  protected $smarty;
  protected $path;
  protected $auth;
  protected $mailconf;

  /**
   * Loads all the necesary methods to display the component's content
   *
   * @param TlalokesRegistry $reg
   */
  public function __construct ( TlalokesRegistry &$reg )
  {
    // set paths to this scope
    $this->path = $reg->conf['path'];

    // set default charset from configuration
    $this->charset = $reg->conf['default']['charset'];
    
    $this->default_locale = $reg->conf['default']['locale'];

    // set authentication method
    $this->auth = isset( $reg->conf['auth'] ) ? $reg->conf['auth'] : null;

    // tranfer the Request object to local property
    $this->request =& $reg->conf['request'];

    // load the current action from the configuration
    $reg->conf = tlalokes_core_conf_get_action( $reg->conf, $this->request );
    $this->request->_action = $reg->conf['current']['action'];

    // load Response object injecting the locale messages
    $this->response = new TlalokesResponse( tlalokes_core_load_locale( $reg ) );
	
    // reflect current controller
    $ctl = new ReflectionAnnotatedClass( $reg->conf['current']['controller'] );
    
    // check if authentication is required
    if ( isset( $ctl->getAnnotation( 'ControllerDefinition' )->auth ) ) {
      // must be a method
      if ( !$this->auth ) {
        tlalokes_error_msg( 'Controller: Set authentication mode in '.
                            'configuration' );
      }
      // check authentication
      if ( !isset( $_SESSION['profiles'] ) || !isset( $_SESSION['role'] ) ) {
        // go to default controller
        tlalokes_error_msg( 'Authentication: Please login', false, 'default' );
      }
      // validate profile
      switch ( $this->auth ) {
        case 'db' :
          // check DSN
          if ( isset( $reg->conf['dsn'] ) || $reg->conf['dsn'] ) {
            if ( $reg->conf['dsn']['type'] && $reg->conf['dsn']['host'] &&
                 $reg->conf['dsn']['name'] && $reg->conf['dsn']['username'] ) {
              // load propel
              require_once 'propel/Propel.php';
              Propel::init( tlalokes_core_conf_get_propel_dsn( $reg->conf ) );
            }
          }
        case 'array' :
          $file = $reg->conf['path']['app'].$reg->conf['path']['bss'].
                  'AuthBss.php';
          if ( !file_exists( $file ) ) {
            tlalokes_error_msg( 'Authentication: AuthBss.php not found.'.
                                'Please execute the auth_array builder.' );
          }
          require $file;
          AuthBss::validate( $reg );
          unset( $file );
          break;
      }
    }

    // reflect current action / method
    $act =& $ctl->getMethod( $reg->conf['current']['action'] );

    // check if propel is required
    if ( isset( $act->getAnnotation( 'ActionDefinition' )->propel ) ) {
      // check DSN
      if ( isset( $reg->conf['dsn'] ) || $reg->conf['dsn'] ) {
        if ( $reg->conf['dsn']['type'] && $reg->conf['dsn']['host'] &&
             $reg->conf['dsn']['name'] && $reg->conf['dsn']['username'] ) {
          // load propel
          require_once 'propel/Propel.php';
          Propel::init( tlalokes_core_conf_get_propel_dsn( $reg->conf ) );
        }
      }
    }

    // set _locale
    if ( isset( $_SESSION['_locale'] ) ) {
      $this->response->_locale = $_SESSION['_locale'];
    }
    if ( isset( $reg->conf['request']->locale ) ) {
      $this->response->_locale = $reg->conf['request']->locale;
      $_SESSION['_locale'] = $reg->conf['request']->locale;
    } else {
      $this->response->_locale = $reg->conf['current']['locale'];
      $_SESSION['_locale'] = $reg->conf['current']['locale'];
    }

    //set _locale_uri
    $this->response->_locale_uri = $this->response->_locale != $this->default_locale
    								? '/locale/'.$this->response->_locale 
    								: '';
     
    
    // set email conf for email send
    if ( isset( $reg->conf['mail'] ) ) {
      $this->mailconf = $reg->conf['mail'];
    }

    // Templates
    if ( !isset( $act->getAnnotation( 'ActionDefinition' )->smarty ) ) {
      // load method
      $this->{$reg->conf['current']['action']}();
      // load in PHP
      if ( !isset( $reg->webservice ) || !$reg->webservice ) {
        $this->loadInPHP( $reg, $act );
      }
    } else {
      // load Smarty
      $this->loadInSmarty( $reg, $act );
      // load and display template file
      $this->loadActionInSmarty( $reg );
    }

    unset( $act );
    unset( $ctl );
  }

  /**
   * Load response in a PHP "template"
   *
   * @param TlalokesRegistry $reg
   */
  private function loadInPHP ( TlalokesRegistry &$reg, &$ref )
  {
    // get application path
    $app = $reg->conf['path']['app'];

    // get current method
    $method = $reg->conf['current']['action'];

    // load @ActionDefinition
    $action = $ref->getAnnotation( 'ActionDefinition' );

    // checks if file property is found in @ActionDefinition
    if ( isset( $action->file ) && $action->file ) {

      if ( !is_string( $action->file ) ) {
        tlalokes_error_msg( 'Controller: Provide a template file name' );
      }

      $filename = $action->file;

    // checks is layout is defined in @ActionDefinition
    } elseif ( isset( $action->layout ) ) {

      $filename = $action->layout;

      // parse and set zones
      if ( isset( $action->zone ) ) {
        $this->response->_layout['name'] = $action->layout;
        $this->response->_layout['views_path'] = $reg->conf['path']['app'] .
                                                 $reg->conf['path']['views'];
        $this->response->_layout['zones']=self::parseLayoutZones($action->zone);
      }

    }

    $file = $app . $reg->conf['path']['views'] . $filename;
    unset( $filename );
    if ( !file_exists( $file ) ) {
      tlalokes_error_msg( 'Controller: View file '.$file.' not found' );
    }

    // check if uri contains a query string and removes it
    if ( preg_match( '/(.*)(index.php)?(\?r=)/', $this->path['uri'] ) ) {
      $_uri = $this->path['uri'];
    }
    $uri = preg_replace( '/(.*)(index.php)?(\?r=)/', '$1', $this->path['uri'] );

    // assign some useful variables
    $this->response->_controller = $reg->conf['current']['controller'];
    $this->response->_action = $reg->conf['current']['action'];
    if ( isset( $reg->conf['request']->_id ) && $reg->conf['request']->_id ) {
      $this->response->_id = $reg->conf['request']->_id;
    }
    $this->response->_action = $reg->conf['current']['action'];
    $this->response->_uri = isset( $_uri ) && $_uri ? $_uri : $uri;
    $this->response->uri = $uri;
    $this->response->_img = $this->response->img = $uri . 'img/';
    $this->response->_css = $this->response->css = $uri . 'css/';
    $this->response->_js  = $this->response->js  = $uri . 'js/';
    $this->response->_extra  = $this->response->extra  = $uri . 'extra/';

    if ( isset( $this->response->_layout ) ) {
      // set local variables from TlalokesResponse in Layout
      foreach ( $this->response as $key => $value )  {
        if ( $key != '_layout' ) {
          $this->response->_layout['response'][$key] = $value;
        }
      }
    }

    // load template file
    tlalokes_core_load_template( $file, $this->response, $this->charset );
    unset( $tplfile );
  }

  /**
   * Loads Smarty and it's variables
   *
   * @param TlalokesRegistry $reg
   */
  private function loadInSmarty ( TlalokesRegistry &$reg, &$reflected_method )
  {
    $app = $reg->conf['path']['app'];

    // reflect Annotations in method
    $ref = $reflected_method;

    if ( $ref->hasAnnotation( 'ActionDefinition' ) ) {

      $this->{'reflection'} = $ref;

      $compile = $app . $reg->conf['path']['tpl_compile'];
      if ( $reg->conf['mode']['smarty']  == 'debug' ) {
        // check permissions of compilation directory
        if ( !is_writable( $compile ) ) {
          tlalokes_error_msg( 'Smarty: Path ('.$compile.') not writeable' );
        }
      }

      // load an instance of Smarty
      require_once $reg->conf['path']['libs'] . 'smarty/Smarty.class.php';
      $this->smarty = new Smarty;

      $this->smarty->template_dir = $app . $reg->conf['path']['views'];
      $this->smarty->compile_dir = $compile;
      $this->smarty->cache_dir = $app . $reg->conf['path']['tpl_cache'];

      if ( $reg->conf['mode']['smarty'] == 'production' ) {
        $this->smarty->compile_check = false;
        $smarty->debugging = false;
      }

      // check if uri contains a query string and removes it
      if ( preg_match( '/(.*)(\?r=)/', $this->path['uri'] ) ) {
        $_uri = $this->path['uri'];
      }
      // check if uri contains a query string and removes it
      $uri = preg_replace( '/(.*)(\?r=)/', '$1', $this->path['uri'] );

      // assign some useful variables
      $this->smarty->assign( '_controller',$reg->conf['current']['controller']);
      $this->smarty->assign( '_action', $reg->conf['current']['action'] );
      $this->smarty->assign( '_uri', isset( $_uri ) && $_uri ? $_uri : $uri );
      $this->smarty->assign( 'uri', $uri );
      $this->smarty->assign( 'img', $uri . 'img/' );
      $this->smarty->assign( 'css', $uri . 'css/' );
      $this->smarty->assign( 'js', $uri . 'js/' );
    }
  }

  /**
   * Loads everything needed to display to display current action in Smarty
   *
   * @param Registry $reg
   */
  protected function loadActionInSmarty ( TlalokesRegistry &$reg )
  {
    // get method from Registry
    $method = $reg->conf['current']['action'];

    // check @Template properties
    if ( isset( $this->reflection ) && is_a( $this->smarty, 'Smarty' ) ) {

      // load @Template
      $template = $this->reflection->getAnnotation( 'ActionDefinition' );

      // try to find file property in @Template
      if ( !$template->file ) {
        $msg = 'Smarty: Declare a template in '.$method.' with '.
               '@ActionDefinition in class '.$reflection->getDeclaringClass();
        tlalokes_error_msg( $msg );
      }

      // check file existance
      $file = $reg->conf['path']['app'] . $reg->conf['path']['views'] .
              $template->file;
      if ( !file_exists( $file ) ) {
        tlalokes_error_msg( 'Smarty: Template '.$template->file.' not found.' );
      }
      unset( $file );
      unset( $this->reflection );
    }

    // load method
    $this->{$method}();

    // load template
    if ( isset( $template ) ) {

      // load Response messages as Template variables
      foreach ( $this->response as $k => $v ) {
        if ( $k != 'locale' ) {
          $this->smarty->assign("$k", tlalokes_str_to_html($v, $this->charset));
        }
      }

      // set Smarty cache properties acording to lifetime property
      if ( $template->lifetime ) {
        $this->smarty->caching = 2;
        $this->smarty->cache_lifetime = $template->lifetime;
      }

      // display Template files
      $this->smarty->display( $template->file );
    }
  }

  /**
   * Parse the layout zones and it's blocks and returns it as an array
   *
   * @param string $string
   * @param boolean $recursive
   * @return array
   */
  private function parseLayoutZones ( $string, $recursive = false )
  {
    // check if more than one zone
    if ( preg_match( '/;/', $string ) ) {
      $zones = explode( ';', $string );
      foreach ( $zones as $zone ) {
        if ( $zone ) {
          $blocks[] = self::parseLayoutZones( $zone, true );
        }
      }
      unset( $zones );
    } else {
      // parsing a zone
      $items = preg_split( '/\:/', $string );
      if ( preg_match( '/\&/', $items[1] ) ) {
        $blocks[$items[0]] = explode( '&', $items[1] );
      } else {
        $blocks[$items[0]] = $items[1];
      }
      unset( $items );
    }
    return $blocks;
  }

  /**
   * Destroy properties after use
   */
  public function __destruct ()
  {
    foreach ( get_object_vars( $this ) as $key => $var ) {
      if ( $this->{$key} ) {
        unset( $this->{$key} );
      }
    }
  }
}
