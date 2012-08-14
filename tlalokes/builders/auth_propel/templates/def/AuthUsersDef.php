<?php
/**
 * AuthUsers Definition Object Model
 * Copyright (C) 2010 Basilio Briceno Hernandez <bbh@tlalokes.org>
 *
 * This file is part of Tlalokes <http://tlalokes.org>.
 *
 * Tlalokes is free software: you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the
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
 * @DefinitionObject( table='auth_users', build )
 *
 * @author Basilio Brice&ntilde;o Hern&aacute;ndez <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2010 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/lgpl.html GNU LGPL
 */
class AuthUsersDef {

  /**
   * @DefinitionObject( column='id', type='integer', required, autoIncrement, primaryKey )
   */
  public $id;

  /**
   * @DefinitionObject( column='role', type='integer', required )
   * @ReferenceDef( table='auth_roles', column='id' )
   */
  public $role;

  /**
   * @DefinitionObject( column='email', type='varchar', size='128', required, unique )
   */
  public $email;

  /**
   * @DefinitionObject( column='password', type='varchar', size='128', required )
   */
  public $password;

  /**
   * @DefinitionObject( column='user_status', type='boolean', required )
   */
  public $user_status;
}
?>
