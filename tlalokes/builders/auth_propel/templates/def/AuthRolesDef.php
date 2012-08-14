<?
/**
 * @DefinitionObject( table='auth_roles', build )
 */
class AuthRolesDef {

  /**
   * @DefinitionObject( column='id', type='integer', required, autoIncrement, primaryKey )
   */
  public $id;

  /**
   * @DefinitionObject( column='name', type='varchar', size='128', required, unique )
   */
  public $name;

  /**
   * @DefinitionObject( column='role_status', type='boolean', required )
   */
  public $role_status;
}
?>
