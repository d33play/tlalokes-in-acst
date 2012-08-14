<?
require 'TlalokesCoreController.php';

/**
 * @ControllerDefinition( default='one' )
 */
class TestCtl extends TlalokesCoreController {

  /**
   * @ActionDefinition( file='one.tpl' )
   */
  public function one ()
  {
    $this->response->title = "One";
  }

  /**
   * @ActionDefinition( file='two.tpl', smarty )
   */
  public function two ()
  {
    $this->response->title = "Two";
  }
}