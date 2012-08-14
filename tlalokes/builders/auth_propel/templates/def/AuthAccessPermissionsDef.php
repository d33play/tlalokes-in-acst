<?
/**
 * @DefinitionObject( table='auth_access_permissions', build )
 */
class AuthAccessPermissionsDef {

  /**
   * @DefinitionObject( column='id', type='integer', required, autoIncrement, primaryKey )
   */
  public $id;

  /**
   * @DefinitionObject( column='profile', type='integer', required )
   * @ReferenceDef( table='auth_access_profiles', column='id' )
   */
  public $profile;

  /**
   * @DefinitionObject( column='controller', type='varchar', size='128', required, unique )
   */
  public $controller;

  /**
   * @DefinitionObject( column='methods', type='longvarchar' )
   */
  public $methods;
}
?>
