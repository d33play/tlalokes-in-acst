<?php
/**
 * Tlalokes Propel Factory
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

require_once 'DefinitionObject.php';
require_once 'ReferenceDef.php';
require_once 'ReflectionAnnotatedClass.php';
require_once 'ReflectionAnnotatedProperty.php';

/**
 * Provides methods to make database transformations
 *
 * @author Basilio Brice&ntilde;o Hern&aacute;ndez <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2009 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/lgpl.html GNU LGPL
 * @package tlalokes.core
 */
class TlalokesPropelFactory {

  /**
   * Loads database transformation mode and executes the right methods
   *
   * @param TlalokesRegistry $conf
   */
  public static function load ( TlalokesRegistry &$reg )
  {
    // set arguments
    $lib = $reg->conf['path']['libs'];
    $tmp = $reg->conf['path']['app'] . $reg->conf['path']['tmp'];
    $args = array('-f', $lib.'phpdb/propel/generator/build.xml',
                  '-Dusing.propel-gen=true', '-Dproject.dir='.$tmp.'generator');

    // load actions
    switch ( $reg->conf['mode']['propel'] ) {
      case 'create' :
        tlalokes_error_msg( 'Database creation not available' );
        break;

      // builds configuration files, schema, SQL, ORMs and tables
      // WARNING it will drop every existent table, so if you want just
      //         an alteration use alter-tables instead
      case 'build-all' :
        // build generator properties
        self::buildBuildProperties( $reg );

        // build runtime configuration
        self::buildRuntimeConfiguration( $reg );

        // build schema from definition objects
        self::buildSchemaFromDefs( $reg );

        // build SQL and OMs from schema
        self::buildProject( $lib, $args );

        // build properties
        array_push( $args, 'insert-sql' );
        self::buildProject( $lib, $args );

        break;

      // builds configuration files, schema and ORMs
      case 'build-conf' :

        // build generator properties
        self::buildBuildProperties( $reg );

        // build runtime configuration
        self::buildRuntimeConfiguration( $reg );

        // build schema from definition objects
        self::buildSchemaFromDefs( $reg );

        // build SQL from schema
        array_push( $args, 'sql' );
        self::buildProject( $lib, $args );

        break;

      // builds tables, if no configuration file then builds it
      // WARNING: it will drop every existent table, so if you want just
      //          an alteration use alter-tables instead
      case 'build-tables' :
        // build properties if not found
        if ( !file_exists( $tmp.'generator/build.properties' ) ) {
          self::buildBuildProperties( $reg );
        }

        // build schema from definition objects if not found
        if ( !file_exists( $tmp.'generator/schema.xml' ) ) {
          self::buildSchemaFromDefs( $reg );
        }

        // build SQL file from schema if not found
        if ( !file_exists( $tmp.'generator/build/sql/schema.sql' ) ) {
          array_push( $args, 'sql' );
          self::buildProject( $lib, $args );
          array_pop( $args );
        }

        // build SQL file from scheme
        array_push( $args, 'insert-sql' );
        self::buildProject( $lib, $args );

        break;

      // builds definition file from an existent database and
      // builds its ORM files. (configuration files if needed too)
      // WARNING: the interpretation of creole data types is not
      //          very precise, im working on this but be careful
      //          with the auto-generated SQL file.
      case 'build-from-db' :

        // if there are no configuration files builds it
        if ( !file_exists( $tmp.'generator/build.properties' ) ) {
          self::buildBuildProperties( $reg );
        }
        if ( !file_exists( $tmp.'generator/runtime-conf.xml' ) ) {
          self::buildRuntimeConfiguration( $reg );
        }

        // build schema from database
        array_push( $args, 'creole' );
        self::buildProject( $lib, $args );
        array_pop( $args );

        // build definition objects from schema
        self::buildDefsFromSchema( $reg );

        // rebuild schema from new definition objects to fix errors
        self::buildSchemaFromDefs( $reg );

        // build SQL from schema
        array_push( $args, 'sql' );
        self::buildProject( $lib, $args );
        array_pop( $args );

        // build OMs from schema
        array_push( $args, 'om' );
        self::buildProject( $lib, $args );

        break;

      case 'alter-tables' :

        tlalokes_error_msg('Database alteration not available in this version');

        /*// build properties if not found
        if ( !file_exists( $tmp.'generator/build.properties' ) ) {
          self::buildBuildProperties( $reg );
        }

        // build schema from database
        array_push( $args, 'creole' );
        self::buildProject( $lib, $args );
        array_pop( $args );

        // copy schema from db
        copy( $tmp.'generator/schema.xml',
              $tmp.'generator/schema_from_db.xml' );
        unlink( $tmp.'generator/schema.xml' );

        // build schema from definition objects
        self::buildSchemaFromDefs( $reg );

        // copy schema from definition objects
        copy( $tmp.'generator/schema.xml',
              $tmp.'generator/schema_from_df.xml' );

        // build alterations from schemas comparation
        switch ( $reg->conf['dsn']['type'] ) {
          case 'mysql' :
            self::alterTablesMySQL( $reg );
            break;
          default :
            $msg = "Tables alteration: Invalid database type".
                   "({$reg->conf['dsn']['type']})";
            tlalokes_error_msg( $msg );
        }*/
        /*if ( self::buildAlterationFromSchemas( $reg ) ) {
          array_push( $args, 'insert-sql' );
          self::buildProject( $lib, $args );
        }*/

        /*echo "// remove temporal files\n";
        CoreUtils::RemoveDirectory( $tmp . 'generator' );
        mkdir( $tmp.'generator', 0770, true );//*/

        break;

      // build Database access objects
      case 'build-om' :

        // build properties if not found
        if ( !file_exists( $tmp.'generator/build.properties' ) ) {
          self::buildBuildProperties( $reg );
        }
        // build runtime configuration if not found
        if ( !file_exists( $tmp.'generator/runtime-conf.xml' ) ) {
          self::buildRuntimeConfiguration( $reg );
        }
        // build schema from definition objects if not found
        if ( !file_exists( $tmp.'generator/schema.xml' ) ) {
          self::buildSchemaFromDefs( $reg );
        }

        array_push( $args, 'om' );
        self::buildProject( $lib, $args );

        break;

      default :
        tlalokes_error_msg('Database Transformation: Define a valid mode',true);
    }
  }

  /**
   * Calls Phing's methods to generate propel's objects
   *
   * @param string $libraries_path
   * @param array $arguments
   */
  public static function buildProject ( $libraries_path, array &$arguments )
  {
    require_once 'phing/Phing.php';
    Phing::startup();
    Phing::setProperty( 'phing.home', $libraries_path.'phing' );
    Phing::start( $arguments );
  }

  /**
   * Builds a build properties file required by propel
   *
   * @param TlalokesRegistry $reg
   */
  private static function buildBuildProperties ( TlalokesRegistry &$reg )
  {
    // set local vars to use to set build.properties string
    $password = $reg->conf['dsn']['password'] ? ":{$reg->conf['dsn']['password']}" : '';
    $dburi = $reg->conf['dsn']['type'] . '://' . $reg->conf['dsn']['username']
           . $password . '@' . $reg->conf['dsn']['host']  . '/'
           . $reg->conf['dsn']['name'];

    // set build.properties string
    $r = //'propel.project = tlalokes_' . $reg->conf['dsn']['name'] . "\n"
         'propel.database = ' . $reg->conf['dsn']['type'] . "\n"
       . 'propel.database.url = ' . $dburi . "\n"
       . 'propel.php.dir = ' .$reg->conf['path']['app'].$reg->conf['path']['orm']. "\n"
       . "propel.addGenericAccessors = true\n"
       . "propel.addGenericMutators = true\n"
       . "propel.disableIdentifierQuoting = true\n";

    // set build.properties file path
    $file = $reg->conf['path']['app'] . $reg->conf['path']['tmp']
          . 'generator/build.properties';

    // write build.properties or return a CoreException
    if ( !@file_put_contents( $file, $r ) ) {
      tlalokes_error_msg( 'Propel: Cannot write '.$file, true );
    }
  }

  /**
   * Builds a runtime configuration xml file required by propel
   *
   * @param TlalokesRegistry $reg
   */
  private static function buildRuntimeConfiguration( TlalokesRegistry &$reg )
  {
    // set local vars
    $type = $reg->conf['dsn']['type'];
    $dbname = $reg->conf['dsn']['name'];
    $host = $reg->conf['dsn']['host'];
    $user = $reg->conf['dsn']['username'];
    $pass = $reg->conf['dsn']['password'];

    // set XML DOM object
    $dom = new DOMDocument( '1.0', 'utf-8' );
    $dom->formatOutput = true;
    $con = $dom->appendChild( new DOMElement( 'config' ) );
    $log = $con->appendChild( new DOMElement( 'log' ) );
    $ident = $log->appendChild( new DOMElement( 'ident' ) );
    $ident->appendChild( new DOMText( "tlalokes_$dbname" ) );
    $level = $log->appendChild( new DOMElement( 'level' ) );
    $level->appendChild( new DOMText( '7' ) );
    $name = $log->appendChild( new DOMElement( 'name' ) );
    $name->appendChild( new DOMText( $reg->conf['path']['app'].
                                     $reg->conf['path']['tmp'].
                                     'generator/db_access.log' ) );
    $propel = $con->appendChild( new DOMElement( 'propel' ) );
    $datasrcs = $propel->appendChild( new DOMElement( 'datasources' ) );
    $datasrcs->setAttribute( 'default', $dbname );
    $datasrc = $datasrcs->appendChild( new DOMElement( 'datasource' ) );
    $datasrc->setAttribute( 'id', $dbname );
    $adapter = $datasrc->appendChild( new DOMElement( 'adapter' ) );
    $adapter->appendChild( new DOMText( $type ) );
    $conn = $datasrc->appendChild( new DOMElement( 'connection' ) );
    $phptype = $conn->appendChild( new DOMElement( 'phptype' ) );
    $phptype->appendChild( new DOMText( $type ) );
    $hostspec = $conn->appendChild( new DOMElement( 'hostspec' ) );
    $hostspec->appendChild( new DOMText( $host ) );
    $database = $conn->appendChild( new DOMElement( 'database' ) );
    $database->appendChild( new DOMText( $dbname ) );
    $username = $conn->appendChild( new DOMElement( 'username' ) );
    $username->appendChild( new DOMText( $user ) );
    $password = $conn->appendChild( new DOMElement( 'password' ) );
    $password->appendChild( new DOMText( $pass ) );

    // set runtime-conf.xml file path
    $file = $reg->conf['path']['app'] . $reg->conf['path']['tmp']
          . 'generator/runtime-conf.xml';

    // write runtime-conf.xml or return a CoreException
    if ( !@file_put_contents( $file, $dom->saveXML() ) ) {
      tlalokes_error_msg( 'Propel: Cannort write runtime configuration', true );
    }
  }

  /**
   * Creates a schema.xml file based in Database Definition objects
   *
   * @param TlalokesRegistry $reg
   */
  private static function buildSchemaFromDefs ( TlalokesRegistry &$reg )
  {
    // static flags
    static $uniques = 0;
    static $indexes = 0;

    // build database xml dom object
    $dom = new DOMDocument( '1.0', 'utf-8' );
    $dom->formatOutput = true;
    $db = $dom->appendChild( new DOMElement( 'database' ) );
    $db->setAttribute( 'name', $reg->conf['dsn']['name'] );

    // find table definitions
    foreach( glob( $reg->conf['path']['app'].$reg->conf['path']['def'].'*Def.php' ) as $def ) {

      // get class name
      $class_name = preg_replace( '/.*\/(\w*Def).php$/', '$1', $def );

      // reflect annotated class
      $ref = new ReflectionAnnotatedClass( $class_name );

      // check if @DefinitonObject is set
      if ( !$ref->hasAnnotation( 'DefinitionObject' ) ) {
        tlalokes_error_msg( 'PropelFactory: There is no DefinitionObject in '.
                            $class_name );
      }

      // check if object is marked for build
      if ( $ref->getAnnotation( 'DefinitionObject' )->build ) {

        // build xml table node
        $table = $db->appendChild( new DOMElement( 'table' ) );

        // set table name attribute
        $table_name = $ref->getAnnotation( 'DefinitionObject' )->table;
        $table->setAttribute( 'name', $table_name );
        unset( $table_name );
        $table->setAttribute( 'idMethod', 'native' );

        // find columns
        foreach ( $ref->getProperties() as $property ) {

          // reflect column
          $column = $property->getAnnotation( 'DefinitionObject' );

          // build xml column nodes
          $col = $table->appendChild( new DOMElement( 'column' ) );
          // name
          $col->setAttribute( 'name', $column->column );
          // phpName
          if ( $property->getName() != $column->column ) {
            $col->setAttribute( 'phpName', (string) $property->getName() );
          }
          // type
          $col->setAttribute( 'type', $column->type );
          // size
          if ( $column->size ) {
            $col->setAttribute( 'size', $column->size );
          }
          // scale
          if ( $column->scale ) {
            $col->setAttribute( 'scale', $column->scale );
          }
          // required
          if ( $column->required ) {
            $col->setAttribute( 'required', $column->required ? 'true' :'false' );
          }
          // autoIncrement
          if ( $column->autoIncrement ) {
            $col->setAttribute( 'autoIncrement', $column->autoIncrement
                                                 ? 'true':'false');
            // If RBDMS is PgSQL and there is no default set the default
            // WARNING: It needs to be tested with Oracle
            if ( $reg->conf['dsn']['type'] == 'pgsql' ) {
              // build id-method-parameter
              $imp_name = 'id-method-parameter';
              $imp = $table->appendChild( new DOMElement( $imp_name ) );
              $imp->setAttribute( 'value', $table_name .'_seq' );
              // set default
              if  ( !isset( $column->default ) ) {
                $col->setAttribute( 'default', 'nextval(\'' . $table_name .
                                               '_seq\'::regclass)' );
              }
            }
          }
          // primaryKey
          if ( $column->primaryKey ) {
            $col->setAttribute( 'primaryKey',$column->primaryKey?'true':'false' );
          }
          // default
          if ( $column->default || $column->default === 0 ) {
            $col->setAttribute( 'default', tlalokes_core_get_type( $column->default ) );
          }
          // find unique
          if ( isset( $column->unique ) && $column->unique ) {
            if ( isset( $uniques ) ) {
              $uniques++;
            } else {
              $uniques = 1;
            }
            $unique_column_name[] = $column->column;
          }
          // find index
          if ( isset( $column->index ) && $column->index ) {
            $indexes = isset( $indexes ) ? $indexes + 1 : 1;
            $index_column_name[] = $column->column;
          }
          // find reference
          $reference = $property->getAnnotation( 'ReferenceDef' );
          if ( $reference ) {
            // build foreign-key xml node
            $fk = $table->appendChild( new DOMElement( 'foreign-key' ) );
            $fk->setAttribute( 'foreignTable', $reference->table );
            $fk->setAttribute( 'onDelete', strtolower( $reference->onDelete ) );
            $fk->setAttribute( 'onUpdate', strtolower( $reference->onUpdate ) );
            $rf = $fk->appendChild( new DOMElement( 'reference' ) );
            $rf->setAttribute( 'local', $column->column );
            $rf->setAttribute( 'foreign', $reference->column );
          }
        }
        // find uniques flag
        if ( isset( $uniques ) &&  $uniques >= 1 ) {
          if ( isset( $unique_column_name ) ) {
            foreach ( $unique_column_name as $ucn ) {
              // build unique xml node
              $unique = $table->appendChild( new DOMElement( 'unique' ) );
              // build unique-column xml node
              $uc = $unique->appendChild( new DOMElement( 'unique-column' ) );
              $uc->setAttribute( 'name', $ucn );
            }
            unset( $unique_column_name );
          }
          unset( $uniques );
        }
        // find indexes flag
        if ( isset( $indexes ) && $indexes >= 1 ) {
          foreach ( $index_column_name as $icn ) {
            // build index xml node
            $index = $table->appendChild( new DOMElement( 'index' ) );
            // build index-column xml node
            $ic = $index->appendChild( new DOMElement( 'index-column' ) );
            $ic->setAttribute( 'name', $icn );
          }
          unset( $indexes );
          unset( $index_column_name );
        }
      }

      // set file path to schema.xml
      $file = $reg->conf['path']['app'] . $reg->conf['path']['tmp']
            . 'generator/schema.xml';

      // write schema.xml file or return a CoreException
      if ( !@file_put_contents( $file, $dom->saveXML() ) ) {
        tlalokes_error_msg( 'Propel: Cannot write database schema', true );
      }
    }
  }

  /**
   * Creates definition object files based in the schema xml file
   *
   * @param TlalokesRegistry $reg
   */
  private static function buildDefsFromSchema ( TlalokesRegistry &$reg )
  {
    // load schema xml file
    $schema = $reg->conf['path']['app'] . $reg->conf['path']['tmp']
            . 'generator/schema.xml';
    $sx = simplexml_load_file( $schema );

    // set response class string by table
    foreach ( $sx->table as $table ) {
      $table_name = (string) $table['name'];
      $r = '<?
/**
 * @DefinitionObject( table=\''.$table_name.'\', build )
 */
class '.tlalokes_str_change_format( $table_name ).'Def {
';

      // find column elements
      foreach ( $table->children() as $key => $node ) {

        // set response two array
        if ( $key == 'column' ) {
          $r2[(string)$node->name] = '';
        }

        // find foreign-key nodes and set it to an array (r2)
        if ( $key == 'foreign-key' ) {
          $f_tab = (string) $node['foreignTable'];
          $f_col = (string) $node->reference['foreign'];
          // on delete
          $f_ond = isset( $node['onDelete'] ) ? ', onDelete=\''
          . strtolower( (string)$node['onDelete'] ) . '\'' : '';
          // on update
          $f_onu = isset( $node['onUpdate'] ) ? ', onUpdate=\''
          . strtolower( (string)$node['onUpdate'] ) . '\'' : '';
          // reference_flag
          $r2[(string)$node->reference['local']] = '
     * @ReferenceDef( table=\''.$f_tab.'\', column=\''.$f_col.'\''.$f_ond.' )';
        }

        // find index nodes and add it to a flag array (r3)
        if ( $key == 'index' ) {
          foreach ( $node->children() as $index ) {
            $r3[(string)$index['name']] = true;
          }
        }

        // find unique nodes and add it to a flag array (r4)
        if ( $key == 'unique' ) {
          foreach ( $node->children() as $unique ) {
            $r4[(string)$unique['name']] = true;
          }
        }
      }

      // set response property string by column
      foreach ( $table->column as $column ) {
        $col_name = (string) $column['name'];
        $col_type = ', type=\'' . strtolower( (string) $column['type'] ) . '\'';
        $col_size = isset( $column['size'] )
                    ? ', size=\''.(string) $column['size'].'\'' : '';
        $col_scal = isset( $column['scale'] )
                    ? ', scale=\''.(string) $column['scale'].'\'' : '';
        $col_requ = isset( $column['required'] ) ? ', required=\'true\'' : '';
        $col_auto = isset( $column['autoIncrement'] )
                    ? ', autoIncrement=\'true\'' : '';
        $col_pkey = isset( $column['primaryKey'] ) ? ', primaryKey=\'true\'':'';
        $col_defa = isset( $column['default'] )
                    ? ', default=\''.$column['default'].'\'' : '';
        $col_indx = isset( $r3[$col_name] ) ? ', index=\'true\'' : '';
        $col_uniq = isset( $r4[$col_name] ) ? ', unique=\'true\'' : '';
        $r .= '
    /**
     * @DefinitionObject( column=\''.$col_name.'\''.$col_type.$col_size.$col_requ
        .$col_auto.$col_pkey.$col_defa.$col_indx.$col_uniq.' )';

        // if foreign-key exists for this column set response two in it
        if ( isset( $r2[$col_name] ) ) {
          $r .= $r2[$col_name];
        }

        // set php property name
        $r .= '
     */
    public $'.$col_name.';
';
      }

      // close definition class string
      $r .= "
}
?>\n";

      // set definition file name
      $definition_file = $reg->conf['path']['app'] . $reg->conf['path']['def']
                       . tlalokes_str_change_format( $table_name ) . 'Def.php';

      // write file or send Exception
      if ( !@file_put_contents( $definition_file, $r ) ) {
        tlalokes_error_msg( 'Propel: Cannot write '.$definition_file, true );
      }
    }
  }

  private static function alterTablesMySQL ( TlalokesRegistry $reg )
  {
    // set temporal directory
    $tmp = $reg->conf['path']['app'] . $reg->conf['path']['tmp'];

    // load db files
    $db = simplexml_load_file( $tmp . 'generator/schema_from_db.xml' );
    $df = simplexml_load_file( $tmp . 'generator/schema_from_df.xml' );

    $db = self::getSimpleXMLToArray( $db );
    $df = self::getSimpleXMLToArray( $df );

    if ( $db != $df ) {

      foreach ( $db as $dbname => $dbcols ) {
        foreach ( $df as $dfname => $dfcols ) {

          if ( $dbname == $dfname ) {

            echo "$dfname table already exists\n";

            foreach ( $dfcols as $dfc ) {
              foreach ( $dbcols as $dbc ) {

                if ( $dbc['name'] == $dfc['name'] ) {
                  echo $dfc['name']." column already exists\n";

                  // check size property
                  if ( ( isset( $dfc['size'] ) && !isset( $dbc['size'] ) ) ||
                       $dfc['size'] != $dbc['size'] ||
                       ( isset( $dfc['scale'] ) && !isset( $dbc['scale'] ) ) ) {
                    // set scale
                    $scale = isset( $dfc['scale'] ) ? ",{$dfc['scale']}" : '';
                    // set size
                    $size = "({$dfc['size']}$scale)";
                  }

                  if ( $dfc['type'] != $dbc['type'] || isset( $size ) ) {

                    // integer cannot have size
                    if ( isset( $dfc['size'] ) && $dfc['type'] == 'integer' ) {
                      $e = "Data type '{$dfc['type']}', specified for column " .
                           "'{$dfc['name']}', do not support size.";
                      tlalokes_error_msg( $e );
                    }

                    // varchar cannot have scale
                    if ( isset( $dfc['scale'] ) && $dfc['type'] == 'varchar' ||
                         $dfc['type'] == 'longvarchar' ) {
                      $e = "Data type '{$df['type']}', specified for column " .
                           "'{$dfc['name']}', do not support scale.";
                      tlalokes_error_msg( $e );
                    }

                  }

                }

              }
            }

          } else {
            echo "$dfname is new\n";
          }

        }
      }
    }
  }

  private static function compare ( $definition, $database )
  {
    $r = '';
    if ( $definition['type'] != $definition['type'] ) {
      $r .= 'ALTER COLUMN ';
    }
  }

  private static function getSimpleXMLToArray ( SimpleXMLElement $obj )
  {
    foreach ( $obj->table as $objtable ) {
      $name = (string) $objtable['name'];
      foreach ( $objtable->column as $objkc => $objcolumn ) {
        foreach ( $objcolumn->attributes() as $key => $item ) {
          $value = strtolower( (string) $item );
          $_obj[$name][$key][] = tlalokes_get_type( $value );
          unset( $value );
        }
      }
      unset( $name );
    }
    foreach ( $_obj as $name => $table ) {
      foreach ( $table as $property => $column ) {
        foreach ( $column as $key => $value ) {
          $array[$name][$key][$property] = $value;
        }
      }
    }
    unset( $_obj );

    return $array;
  }

  /**
   * Compares two schemas and produces a SQL file to alterate tables
   * NOTE: This version only works for PostgreSQL
   *
   * @param TlalokesRegistry $reg
   * @todo check WARNINGS
   */
  private function buildAlterationFromSchemas ( TlalokesRegistry $reg )
  {
    $r = '';
    $tmp = $reg->conf['path']['app'] . $reg->conf['path']['tmp'];

    // load db files
    $db = simplexml_load_file( $tmp . 'generator/schema_from_db.xml' );
    $df = simplexml_load_file( $tmp . 'generator/schema_from_df.xml' );

    // iterate tables
    foreach ( $db->table as $db_table ) {
      foreach ( $df->table as $df_table ) {

        // work on existant tables
        if ( (string) $db_table['name'] == (string) $df_table['name'] ) {

          // iterate columns
          foreach ( $df_table->column as $df_column ) {
            foreach ( $db_table->column as $db_column ) {

              // work on existant columns
              if ( (string)$df_column['name'] == (string)$db_column['name'] ) {

                // check size property
                if ( ( isset( $df_column['size'] ) &&
                     !isset( $db_column['size'] ) ) ||
                     (string)$df_column['size'] != (string)$db_column['size'] ||
                     ( isset( $df_column['scale'] ) &&
                       !isset( $db_column['scale'] ) ) ) {
                  // set scale
                  $scale = isset( $df_column['scale'] )
                           ? ',' . (string) $df_column['scale'] : '';
                  // set size
                  $size = '('.(string)$df_column['size'] . "$scale)";
                }

                // check type property
                // WARNING: It needs to check types against
                //          RDBMS via Creole Types. Right now
                //          is using direct types from DefObjs
                if ( strtolower( (string) $df_column['type'] ) !=
                     strtolower( (string) $db_column['type'] ) ||
                     isset( $size ) ) {

                  // integer cannot have size
                  if ( isset( $df_column['size'] ) &&
                       (string) $df_column['type'] == 'integer' ) {
                    $e = 'Data type \'' . (string) $df_column['type']
                       . '\', specified for column \''
                       . (string) $df_column['name'] .'\', do not support size';
                    tlalokes_error_msg( $e );
                  }

                  // varchar cannot have scale
                  if ( isset( $df_column['scale'] ) &&
                       (string) $df_column['type'] == 'varchar' ||
                       (string) $df_column['type'] == 'longvarchar' ) {
                    $e = 'Data type ' . (string) $df_column['type']
                       . '\', specified for column \''
                       . (string) $df_column['name'].'\', do not support scale';
                    tlalokes_error_msg( $e );
                  }

                  // WARNING: This is a horrible fix
                  if ( (string) $df_column['type'] == 'longvarchar' ) {
                    $df_column['type'] = 'text';
                  }

                  // set alteration string
                  $r .= 'ALTER TABLE ' . $df_table['name'] . ' '
                      . 'ALTER COLUMN ' . $df_column['name'] . ' '
                      . 'TYPE ' . (string)$df_column['type'];
                  $r .= isset( $df_column['size'] ) ? $size : '';
                  $r .= ";\n";
                }

                // check required property
                if ( isset( $db_column['required'] ) &&
                     !isset( $df_column['required'] ) ) {
                  $r .= 'ALTER TABLE ' . $df_table['name'] . ' ALTER COLUMN '
                     .  $df_column['name'] . " DROP NOT NULL;\n";
                } elseif ( isset( $df_column['required'] ) &&
                           !isset( $db_column['required'] ) ) {
                  $r .= 'ALTER TABLE ' . $df_table['name'] . ' ALTER COLUMN '
                     .  $df_column['name'] . " SET NOT NULL;\n";
                }

                // check primaryKey property
                if ( isset( $db_column['primaryKey'] ) &&
                     !isset( $df_column['primaryKey'] ) ) {
                  $r .= 'ALTER TABLE ' . $df_table['name'] . ' '
                     .  'DROP CONSTRAINT ' . $df_table['name'] . "_pkey;\n";
                } elseif ( isset( $df_column['primaryKey'] ) &&
                           !isset( $db_column['primaryKey'] ) ) {
                  $r .= 'ALTER TABLE '.$df_table['name'].' '
                     .  'ADD PRIMARY KEY (' . $df_column['name'] . ");\n";
                }
                unset( $size );

                // set new columns to add array
              } elseif ( !count( $db_table->xpath( 'column[@name=\'' .
                                                   $df_column['name'] .
                                                   '\']' ) ) ) {
                // add columns to array
                $add_array[(string)$df_table['name']]
                            [(string)$df_column['name']] = $df_column;
              }
            }
          }

          // find index nodes in db
          if ( isset( $db_table->index ) ) {
            // iterate index nodes in db
            foreach ( $db_table->index as $db_index ) {
              foreach ( $db_index->children() as $dbic ) {
                // set an array of existant indexes in db
                $db_idx[ (string) $dbic['name'] ] = (string) $db_index['name'];
              }
            }
          }
          // find index nodes in definition objects
          if ( isset( $df_table->index ) ) {
            static $i = 0;
            // iterate index nodes in definition objects
            foreach ( $df_table->index as $df_index ) { $i++;
              foreach ( $df_index->children() as $dfic ) {
                // check if db have not an existant index
                if ( !isset( $db_idx[ (string) $dfic['name'] ] ) ) {
                  // set index creation string
                  $r .= 'CREATE INDEX '
                      .  (string) $df_table['name'] . '_i_' . $i . ' ON '
                      .  (string) $df_table['name'] . ' ('
                      .  (string) $dfic['name'] . ");\n";
                }
              }
            }
          }
          // find indexes to drop
          if ( isset( $db_idx ) ) {
            foreach ( $db_idx as $db_idx_name => $db_idx_col ) {
              $xpath = "index/index-column[@name='$db_idx_name']";
              if ( !$df_table->xpath( $xpath ) ) {
                $r .= "DROP INDEX $db_idx_col;\n";
              }
            }
          }

          // find unique nodes in db
          if ( isset( $db_table->unique ) ) {
            // iterate unique nodes in db
            foreach ( $db_table->unique as $db_unique ) {
              foreach ( $db_unique->children() as $dbun ) {
                // set an array of existant uniques in db
                $db_unq[ (string) $dbun['name'] ] = (string) $db_unique['name'];
              }
            }
          }
          // find uniques nodes in definition objects
          if ( isset( $df_table->unique ) ) {
            $iu = count( $db_unq );
            // iterate unique nodes in definition objects
            foreach ( $df_table->unique as $df_unique ) {
              foreach ( $df_unique->children() as $dfun ) {
                // check if db have not an existant unique
                if ( !isset( $db_unq[ (string) $dfun['name'] ] ) ) { $iu++;
                  // set unique creation string
                  $r .= 'ALTER TABLE ' . (string) $df_table['name']
                      . ' ADD CONSTRAINT '
                      . (string) $df_table['name'] . "_u_$iu UNIQUE ("
                      . (string) $dfun['name'] . ");\n";
                }
              }
            }
          }
          // find uniques to drop
          if ( isset( $db_unq ) ) {
            foreach ( $db_unq as $db_unq_name => $db_unq_col ) {
              $xpath = "unique/unique-column[@name='$db_unq_name']";
              if ( !$df_table->xpath( $xpath ) ) {
                $r .= "DROP CONSTRAINT $db_unq_col;\n";
              }
            }
          }

        }
      }

      // if there are new columns to add, do it
      if ( isset( $add_array ) ) {
        // iterate columns
        foreach ( $add_array as $table => $column ) {
          foreach ( $column as $col ) {
            // set scale
            $scale = isset( $col['scale'] ) ? ',' . (string) $col['scale'] : '';
            // set size
            $size = isset( $col['size'] ) ? '('.(string)$col['size']."$scale)":'';
            // set null
            $null = isset( $col['required'] ) && $col['required'] == 'true'
                    ? ' NOT NULL' : '';
            // set primary key
            $pkey = isset( $col['primaryKey'] ) && $col['primaryKey'] == 'true'
                    ? ' PRIMARY KEY' : '';

            // set index
            //$index  = isset( $col['index'] ) && $col['index'] == 'true'
            //          ? 'CREATE INDEX ON ' : '';
            //$index .= $table . '(' . $column['name'] . ");\n";

            // varchar cannot have scale
            if ( (string) $col['type'] == 'varchar' ||
                 (string) $col['type'] == 'longvarchar' &&
                 isset( $col['size'] ) ) {
              $e = 'Data type '.(string) $col['type'].', in column \''
                 . (string) $df_column['name'].'\', do not support size';
              tlalokes_error_msg( $e );
            }
            // WARNING: This is a horrible fix
            if ( (string) $col['type'] == 'longvarchar' ) {
              $col['type'] = 'text';
            }

            // set response string
            $r .= 'ALTER TABLE ' .$table. ' ADD COLUMN ' . (string) $col['name']
                . ' ' . (string) $col['type'] . $size . $null . $pkey .";\n";
            //$r .= $index;
            unset( $size );
          }
        }
      }

      // check if there are actions to apply
      if ( $r ) {
        // check sqldb.map directory existance
        $sql_dir = $tmp.'generator/build/sql';
        if ( !file_exists( $sql_dir ) ) {
          mkdir( $sql_dir, 0770, true );
        }
        // write sql alterations file
        $sql_file = $sql_dir . '/def_obj_alt.sql';
        if ( !file_put_contents( $sql_file, $r ) ) {
          tlalokes_error_msg( 'Cannot write '.$sql_file );
        }
        // write a new sqldb.map file with file reference alteration
        $content = 'def_obj_alt.sql=' . $reg->conf['dsn']['name'] . "\n";
        if ( !@file_put_contents( $sql_dir.'/sqldb.map', $content ) ) {
          tlalokes_error_msg( 'Propel: Cannot write '.$sqldbmp_file, true );
        }
        return true;
      }
    }
  }
}
