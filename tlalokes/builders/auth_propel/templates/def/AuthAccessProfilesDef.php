<?
/**
 * @DefinitionObject( table='auth_access_profiles', build )
 */
class AuthAccessProfilesDef {

  /**
   * @DefinitionObject( column='id', type='integer', required, autoIncrement, primaryKey )
   */
  public $id;

  /**
   * @DefinitionObject( column='name', type='varchar', size='128', required, unique )
   */
  public $name;

  /**
   * @DefinitionObject( column='description', type='longvarchar' )
   */
  public $description;
}
?>
