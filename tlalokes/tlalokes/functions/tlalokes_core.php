<?php
/**
 * Tlalokes core functions
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
 * Returns a fixed array with current controller, locale and sets charset
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param TlalokesRequest $request
 * @param array $conf
 * @return array
 */
function tlalokes_core_conf_load ( array &$conf )
{
  $app = preg_replace( '/(.*)\/$/', '$1', $conf['path']['app'] );
  $app = str_replace( '\\', '/', $app );
  $conf['path']['app'] = preg_match( '/^\//', $app )
                         ? $app.'/' : $conf['path']['app'].'/';

  // set application if is set
  if ( isset( $conf['path']['application'] ) && $conf['path']['application'] ) {
    require $conf['path']['application'].'/config.php';
    $conf['key'] = $c['key'];
    unset( $c );
  }

  // set include paths
  tlalokes_core_set_include_path( $conf['path'] );

  require_once 'TlalokesRequest.php';
  $conf['request'] = new TlalokesRequest( $conf );

  // apply default charset to request vars
  $conf['request']->charset( $conf['default']['charset'] );

  // try to find default controller
  if ( !isset( $conf['default']['controller'] ) ||
       !$conf['default']['controller'] ) {
    tlalokes_error_msg( 'Configuration: Define a default controller' );
  }

  // set current controller from Request or default
  $conf['current']['controller'] = isset( $conf['request']->_controller )
                                   ? $conf['request']->_controller
                                   : $conf['default']['controller'].'Ctl';

  // try to find default locale
  if ( !isset( $conf['default']['locale'] ) || !$conf['default']['locale'] ) {
    tlalokes_error_msg( 'Configuration: Define a default locale');
  }

  // set default timezone
  if ( isset( $conf['default']['timezone'] ) ) {
    date_default_timezone_set( $conf['default']['timezone'] );
  }

  // set current locale from Request of default
  $conf['current']['locale'] = isset( $conf['request']->locale )
                               ? $conf['request']->locale
                               : $conf['default']['locale'];
  return $conf;
}

/**
 * Loads current action according TlalokesRequest or default configuration
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param array $conf
 * @param TlalokesRequest $request
 */
function tlalokes_core_conf_get_action ( &$conf, TlalokesRequest &$request )
{
  require_once 'ReflectionAnnotatedClass.php';
  require_once 'ControllerDefinition.php';
  require_once 'ActionDefinition.php';

  // reflect Annotations from current controller class
  $rc = new ReflectionAnnotatedClass( $conf['current']['controller'] );

  // try to find the @ControllerDefinition
  if ( !$rc->hasAnnotation( 'ControllerDefinition' ) ) {
    tlalokes_error_msg( 'Define annotation @ControllerDefinition in ' .
                                               $conf['current']['controller'] );
  }

  // try to find the default action property in @ControllerDefinition
  if ( !$default = $rc->getAnnotation( 'ControllerDefinition' )->default ) {
    tlalokes_error_msg( 'Define a default action in @ControllerDefinition in ' .
                                               $conf['current']['controller'] );
  }

  // set the current action from TlalokesRequest or default if not found
  $conf['current']['action'] = !$rc->hasMethod( $request->_action )
                               ? $default : $request->_action;
  return $conf;
}

/**
 * Returns a fixed DSN array for Propel
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param array $conf
 * @return mixed
 */
function tlalokes_core_conf_get_propel_dsn ( array &$conf )
{
  if ( isset( $conf['dsn'] ) && $conf['dsn'] ) {
    $con['phptype'] = $conf['dsn']['type'];
    $con['hostspec'] = $conf['dsn']['host'];
    $con['database'] = $conf['dsn']['name'];
    $con['username'] = $conf['dsn']['username'];
    $con['password'] = $conf['dsn']['password'];

    $r['log'] = array ( 'ident' => 'db-'.$conf['dsn']['name'],
                        'level' => '7',
                        'name' => $conf['path']['app'] . $conf['path']['tmp'] .
                                  'db_access.log' );

    $r['propel']['datasources'][$conf['dsn']['name']]['adapter']=$conf['dsn']['type'];
    $r['propel']['datasources'][$conf['dsn']['name']]['connection'] = $con;
    //$r['propel']['datasources'][$conf['dsn']['name']]['default']=$conf['dsn']['name'];
    $r['propel']['datasources']['default'] = $conf['dsn']['name'];

    return $r;
  }
  return false;
}


/**
 * Returns the type of the provided $value
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param mixed $value
 * @return mixed
 */
function tlalokes_core_get_type ( $value )
{
  if ( is_numeric( $value ) ) {
    if ( preg_match( '/[0-9]*[\.][0-9]*/', $value ) ) {
      $value = (float) $value;
    } else {
      $value = (int) $value;
    }
  }
  if ( $value ) {
    if ( is_array( $value ) ) {
      foreach ( $value as $k => $v ) {
        $value[$k] = tlalokes_core_get_type( $v );
      }
    } else {
      if ( is_bool( $value ) ) {
        $value = $value;
      } elseif (  $value == 'false' || $value == 'FALSE' ) {
        $value = false;
      } elseif ( $value == 'true' || $value == 'TRUE' ) {
        $value = true;
      }
    }
  }
  return $value;
}

/**
 * Sets include paths for core and libraries into php environment (php.ini)
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param array $path
 */
function tlalokes_core_set_include_path ( array &$path )
{
  // set local variables
  $app = $path['app'];
  $path['libs'] = preg_replace( '/(.*)tlalokes\/?$/', '$1', $path['tlalokes'] ).
                  $path['libs'];
  $lib = $path['libs'];

  // set include_path string
  $include_path =  $path['tlalokes'] . 'functions/'
                . PATH_SEPARATOR . $path['tlalokes'] . '/classes/'
                . PATH_SEPARATOR . $path['tlalokes'] . '/classes/exceptions/'
                . PATH_SEPARATOR . $path['tlalokes'] . '/classes/annotations/'
                . PATH_SEPARATOR . $app . $path['controllers']
                . PATH_SEPARATOR . $app . $path['model']
                . PATH_SEPARATOR . $app . $path['bss']
                . PATH_SEPARATOR . $app . $path['def']
                . PATH_SEPARATOR . $app . $path['orm']
                . PATH_SEPARATOR . $lib . 'pear/'
                . PATH_SEPARATOR . $lib . 'phpdb/creole/'
                . PATH_SEPARATOR . $lib . 'phpdb/propel/runtime/'
                . PATH_SEPARATOR . $lib . 'phpdb/propel/runtime/propel/util/'
                . PATH_SEPARATOR . $lib . 'phing/classes/';

  // set include_path into environment
  ini_set( 'include_path', $include_path );

  unset( $app );
  unset( $lib );
}

/**
 * Returns an array of supported charsets
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @return array
 */
function tlalokes_core_get_charsets ()
{
  //$code = 'I&ntilde;t&euml;rn&acirc;ti&ocirc;n&agrave;liz&aelig;ti&oslash;n';
  return array( 'UTF-8',
                'ISO-8859-1','ISO-8859-15',
                'cp866', 'cp1251', 'cp1252',
                'KOI8-R', 'BIG5', 'GB2312', 'BIG5-HKSCS',
                'Shift_JIS', 'EUC-JP' );
}

/**
* Returns the query string vars in an array
*
* @author Basilio Briceno <bbh@tlalokes.org>
* @return array
*/
function tlalokes_core_get_request_vars ()
{
  if ( isset( $_SERVER['QUERY_STRING'] ) && $_SERVER['QUERY_STRING'] ) {
    foreach ( explode( '&', $_SERVER['QUERY_STRING'] ) as $variable ) {
      list( $name, $value ) = explode( '=', $variable );
      if ( $name && $value ) {
        $response[] = array( 'name' => $name, 'value' => $value );
      }
    }
    return $response;
  }
}

/**
 * Executes builders
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param TlalokesRegistry $r
 * @param array $c
 */
function tlalokes_core_execution ( TlalokesRegistry &$r, array &$c )
{
  // find if execution is needed
  if ( isset( $r->conf['request']->_exe ) ) {

    // verify action
    if ( $r->conf['request']->_exe == 'load' ) {

      tlalokes_core_execution_validate( $c );

      $title = 'Execution';

      // check if database transformation is needed
      // NOTE for version 1.0 this must be as a template application
      if ( $c['mode']['propel'] && $c['mode']['propel'] != 'production' ) {
        // check if DSN is available
        if ( !isset( $c['dsn'] ) ||
             ( !isset( $c['dsn']['type'] ) || !$c['dsn']['type'] ) ||
             ( !isset( $c['dsn']['host'] ) || !$c['dsn']['host'] ) ||
             ( !isset( $c['dsn']['name'] ) || !$c['dsn']['name'] ) ) {
          tlalokes_error_msg( 'Configuration: Declare your RDBMS data.' );
        }
        require_once "TlalokesPropelFactory.php";
        TlalokesPropelFactory::load( $r );
      }

      if ( isset( $c['execute'] ) && is_array( $c['execute'] ) ) {
        foreach ( $c['execute'] as $key => $value ) {

          // verify if execution is required
          if ( isset( $c['execute'][$key] ) && $c['execute'][$key] === true ) {

            // set builders path
            $c['path']['builder'] = preg_replace( '/(.*)tlalokes\/?$/' , '$1',
                                                  $r->conf['path']['tlalokes'] )
                                                  .'builders/'.$key;
            // verify builder file existance
            if ( !file_exists( $c['path']['builder'] ) ) {
              tlalokes_error_msg( 'Execution: '.$key.' directory do not exist',
                                                                         true );
            }
            $file = $c['path']['builder'].'/'.$key.'_builder.php';
            if ( !file_exists( $file ) ) {
              tlalokes_error_msg( 'Execution: '.$file.' do not exists', true );
            }
            $r->conf = $c;
            unset( $c );

            require_once $file;
            unset( $file );

            // verify function existance
            if ( !function_exists( $key.'_builder_main' ) ) {
              tlalokes_error_msg( 'Execution: '.$file.' not valid', true );
            }

            // execute builder
            call_user_func( $key.'_builder_main', $r );
          }
        }
      }
      echo "\n", '<p><a href="',
           str_replace( 'index.php', '', $_SERVER['SCRIPT_NAME'] ),
           'exe/logout">Exit</a></p>', "\n";
      require_once 'inc/footer.inc.php';
      exit;

    } elseif ( $r->conf['request']->_exe == 'logout' ) {
      if ( isset( $_SESSION['key'] ) ) {
        unset( $_SESSION['key'] );
        session_destroy();
      }
    }
  }
}

/**
 * Validates execution and/or prints login form
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param string $key
 */
function tlalokes_core_execution_validate ( &$config )
{
  require_once 'inc/header.inc.php';
  $form = "\n".'<form action="#" method="post">'."\n".
          '<label for="key">Key:</label>'."\n".
          '<input type="password" name="key" />'."\n".
          '<input type="submit" value="Validate" />'."\n".
          "</form>\n";
  $title = 'Execution';
  if ( isset( $_POST['key'] ) ) {
    if ( crypt( $_POST['key'], '$6$rounds=5000$67726f73656b6f6c616c74$' ) != $config['key'] ) {
      echo $form;
      require_once 'inc/footer.inc.php';
      exit;
    }
    $_SESSION['key'] = $config['key'];
    echo "<p>Access granted</p>\n";
  }
  if ( !isset( $_SESSION['key'] ) || !$_SESSION['key'] ) {
    echo $form;
    require_once 'inc/footer.inc.php';
    exit;
  }
}

/**
 * Load locale vars from file
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param TlalokesRegistry $reg
 * @return array
 */
function tlalokes_core_load_locale ( TlalokesRegistry &$reg )
{
  // check current locale syntax
  if ( !preg_match( '/[a-z]{2,3}/', $reg->conf['current']['locale'] ) ) {
    tlalokes_error_msg( 'locale code name wrong format. '.
                        'Use something like en or eng.' );
  }

  // set locale file name
  $file  = $reg->conf['path']['app'] . '/' . $reg->conf['path']['locales'] .
           $reg->conf['current']['locale'] . '.php';

  // check locale file existance
  if ( !file_exists( $file ) ) {
    tlalokes_error_msg( 'Locale: File not found.' );
  }

  // load locale content
  require $file;
  if ( !is_array( $l ) ) {
    tlalokes_error_msg( 'Locale: File must be an array.' );
  }

  // set response array from locale content array
  $ctl = $reg->conf['current']['controller'];
  $act = $reg->conf['current']['action'];
  // load global properties
  foreach ( $l as $k => $v ) {
    if ( $k != 'controllers' ) {
      $response[$k] = $v;
    }
  }
  // load current controller properties
  if ( isset( $l['controllers'][$ctl] ) ) {
    // load global controller properties
    foreach ( $l['controllers'][$ctl] as $k => $v ) {
      if ( $k != 'actions' ) {
        $response[$k] = $v;
      }
    }
    // load current action properties
    if ( isset( $l['controllers'][$ctl]['actions'][$act] ) ) {
      foreach ( $l['controllers'][$ctl]['actions'][$act] as $k => $v ) {
        $response[$k] = $v;
      }
    }
  }

  // set response array from locale metadata
  foreach ( $locale as $key => $value ) {
    $response['locale_'.$key] = $value;
  }
  unset( $file );
  unset( $locale );
  unset( $l );

  return $response;
}

/**
 * Loads TlalokesResponse properties into template file
 *
 * @author Basilio Briceno <bbh@tlalokes.org>
 * @param string $template_path
 * @param TlalokesResponse $response
 * @param string $charset
 */
function tlalokes_core_load_template ( $template_path, &$response, &$charset )
{
  // set local variables from TlalokesResponse
  foreach ( $response as $key => $value )  {
    ${$key} = tlalokes_str_to_html( $value, $charset );
  }

  if ( !is_readable( $template_path ) ) {
    tlalokes_error_msg( 'Template: Cannot read '.$template_path );
  }
  require $template_path;
}

/**
 * Loads layotus zones
 *
 * @param string $zone_name
 * @param array $layout
 */
function tlalokes_layout_zone ( $zone_name, &$layout )
{
  // iterate zones
  foreach( $layout['zones'] as $zones ) {
    // check if multiple block
    if ( is_array( $zones ) ) {
      // load only my required zone
      if ( isset( $zones[$zone_name] ) ) {
        if ( is_array( $zones[$zone_name] ) ) {
          // load blocks
          foreach ( $zones[$zone_name] as $block ) {
            tlalokes_layout_zone_block_include( $block, $layout );
          }
        } else {
          tlalokes_layout_zone_block_include( $zones[$zone_name], $layout );
        }
      }
    } else {
      // load block
      tlalokes_layout_zone_block_include( $zones, $layout );
    }
  }
}

/**
 * Includes block files and set layout's response variables
 *
 * @param string $block
 * @param array $layout
 */
function tlalokes_layout_zone_block_include ( $block, &$layout )
{
  // if file extension found load it as is
  if ( preg_match( '/\.(tpl|php)/', $block ) ) {
    $block  = $layout['views_path'] . 'block/' . $block;
    if ( !file_exists( $block ) ) {
      tlalokes_error_msg( 'Layout: block file "'.$block.'" not found.' );
    } else {
      // set local variables from TlalokesResponse
      if ( isset( $layout['response'] ) && $layout['response'] ) {
        var_dump( $layout['response'] );
        foreach ( $layouẗ́['response'] as $key => $value )  {
          ${$key} = $value;
        }
      }
      include $block;
    }
  // load based in the layout name
  } else {
    $file = $layout['views_path'] . 'block/' .
            str_replace( 'layout.tpl', '', $layout['name'] ) . $block . '.tpl';
    if ( !file_exists( $file ) ) {
      tlalokes_error_msg( 'Layout: block file "'.$file.'" not found.' );
    } else {
      // set local variables from TlalokesResponse
      if ( isset( $layout['response'] ) && $layout['response'] ) {
        foreach ( $layout['response'] as $key => $value )  {
          ${$key} = $value;
        }
      }
      include $file;
    }
    unset( $file );
  }
}

/**
 * Changes the header location to the controller/action you specify or a default
 *
 * @param string $controller_action
 */
function tlalokes_go_to ( $controller_action )
{
  $uri = str_replace( 'index.php', '', $_SERVER['SCRIPT_NAME'] );

  if ( $controller_action != 'default' ) {
    $uri .= $controller_action;
  }

  header( 'Location: http://'.$_SERVER['SERVER_NAME'] . $uri );
}

/**
 * Crypts in a 'one way' mode a string provided using SHA512 with 5000 rounds
 *
 * @param string $string String to crypt
 * @param string $code Application's code for salt
 */
function tlalokes_core_crypt ( $string, $code = false )
{
  $salt  = '$6$rounds=5000$';
  $salt .= !$code ? '67726f73656b6f6c616c74' : $code;
  $salt .= '$';
  return crypt( $string, $salt );
}