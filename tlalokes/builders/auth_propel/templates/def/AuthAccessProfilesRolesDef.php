<?
/**
 * @DefinitionObject( table='auth_access_profiles_roles', build )
 */
class AuthAccessProfilesRolesDef {

  /**
   * @DefinitionObject( column='id', type='integer', required, autoIncrement, primaryKey )
   */
  public $id;

  /**
   * @DefinitionObject( column='profile', type='integer', required, index )
   * @ReferenceDef( table='auth_access_profiles', column='id' )
   */
  public $profile;

  /**
   * @DefinitionObject( column='role', type='integer', required, index )
   * @ReferenceDef( table='auth_roles', column='id' )
   */
  public $role;
}
?>
