    <table class="list" align="center">
<?
// display all users
if ( !isset( $_id ) || !$_id ) :
?>
      <tr>
        <td class="title">
          <div id="title"><?=$title;?></div>
          <div align="right">
            <a href="<?=$_uri;?>"><?=$home;?></a> &nbsp;
            <a href="<?=$_uri;?>auth_users/add"><?=$add;?></a>
          </div>
        </td>
      </tr>

      <tr>
        <td class="filter">
          <form method="get" action="<?=$_uri;?>auth_users/filter<?=$pager['limit']?'/limit/'.$pager['limit']:'';?>">

            <div class="formElement">
              <label for="id"><?=$id;?></label>
              <input type="text" name="id" />
            </div>

            <div class="formElement">
              <label for="email"><?=$email;?></label>
              <input type="text" name="email" />
            </div>

            <div class="formElement">
              <label for="user_status"><?=$user_status;?></label>
              <select name="user_status">
                <option value=""><?=$select_one;?></option>
                <option value="0"><?=$inactive;?></option>
                <option value="1"><?=$active;?></option>
              </select>
            </div>

            <div class="formElement">
              <label for="role"><?=$role;?></label>
              <select name="role">
                <option value=""><?=$select_a_role;?></option>
<?
  foreach ( $auth_roles as $item ) :
?>
                <option value="<?=$item['id'];?>"><?=$item['name'];?></option>
<?
  endforeach;
?>
              </select>
            </div>

            <div class="formElement">
              <input type="submit" id="submit" value="<?=$filter;?>" />
            </div>

          </form>
        </td>
      </tr>

      <tr>
        <td class="paging">
          <form method="get" action="<?=$_uri;?>auth_users/<?=$_action=='filter'?'filter':'read';?><?=$pager['limit']?'/limit/'.$pager['limit']:'';?>">
<?
  if ( $pager['prev'] >= 1 ) :
?>
          <span id="link">
            <a href="<?=$_uri;?>auth_users/<?=$_action=='filter'?'filter':'read';?><?=$pager['limit']>0?'/limit/'.$pager['limit']:'';?>/page/<?=$pager['prev'];?><?=$vars?'?'.$vars:'';?>">
              &lt;
            </a>&nbsp;
          </span>
<?
  endif;
?>
          <span><?=$page;?> <? if ( $pager['total_pages'] > 0 ) :?><input type="text" name="page" value="<?=$pager['current'];?>" /> <?=$of;?> <?=$pager['total_pages'];?><? else :?>1 <?=$of;?> 1<? endif;?></span>
<?
  if ( $pager['next'] >= 1 ) :
?>
          <span id="link">&nbsp;
            <a href="<?=$_uri;?>auth_users/<?=$_action=='filter'?'filter':'read';?><?=$pager['limit']>0?'/limit/'.$pager['limit']:'';?>/page/<?=$pager['next'];?><?=$vars?'?'.$vars:'';?>">
              &gt;
            </a>
          </span>
<?
  endif;
?>
          </form>
        </td>
      </tr>

      <tr>
        <td class="data">
          <table>
            <tr>
              <th><?=$id;?></th>
              <th><?=$role;?></th>
              <th><?=$email;?></th>
              <th><?=$user_status;?></th>
            </tr>
<?
  foreach ( $list as $item ) :
?>
            <tr>
              <td><a href="<?=$_uri;?>auth_users/<?=$item['id'];?>/read"><?=$item['id'];?></a></td>
              <td><a href="<?=$_uri;?>auth_users/<?=$item['id'];?>/read"><?=$item['role_name'];?></a></td>
              <td><a href="<?=$_uri;?>auth_users/<?=$item['id'];?>/read"><?=$item['email'];?></a></td>
              <td><a href="<?=$_uri;?>auth_users/<?=$item['id'];?>/read"><?=! $item['user_status']?$inactive:$active;;?></a></td>
            </tr>
<?
  endforeach;
?>
          </table>
        </td>
      </tr>
<?
// display one element
else :
?>
      <tr>
        <td class="title">
          <div id="title"><?=$title;?></div>
          <div align="right">
            <a href="<?=$_uri;?>"><?=$home;?></a> &nbsp;
            <a href="<?=$_uri;?>auth_users"><?=$back;?></a> &nbsp;
            <a href="<?=$_uri;?>auth_users/<?=$_id;?>/edit"><?=$edit;?></a> &nbsp;
            <a href="<?=$_uri;?>auth_users/<?=$_id;?>/delete"><?=$delete;?></a>
          </div>
        </td>
      </tr>

      <tr>
        <td class="data">

          <div class="element">
            <label><?=$id;?></label>
            <span><?=$element['id'];?></span>
          </div>

          <div class="element">
            <label><?=$role;?></label>
            <span><?=$element['role_name'];?></span>
          </div>

          <div class="element">
            <label><?=$email;?></label>
            <span><?=$element['email'];?></span>
          </div>

          <div class="element">
            <label><?=$user_status;?></label>
            <span><?=!$element['user_status']?$inactive:$active;?></span>
          </div>

        </td>
      </tr>
<?
endif;
?>
    </table>
