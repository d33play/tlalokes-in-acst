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
            <a href="<?=$_uri;?>auth_access_permissions/add"><?=$add;?></a>
          </div>
        </td>
      </tr>

      <tr>
        <td class="filter">

          <form method="get" action="<?=$_uri;?>auth_access_permissions/filter<?=$pager['limit']?'/limit/'.$pager['limit']:'';?>">

            <div class="formElement">
              <label for="id"><?=$id;?></label>
              <input type="text" name="id" />
            </div>

            <div class="formElement">
              <label for="profile"><?=$profile;?></label>
              <select name="profile">
                <option value="">Select an option</option>
<?
  foreach ( $auth_access_profiles as $item ) :
?>
                <option value="<?=$item['id'];?>"><?=$item['name'];?></option>
<?
  endforeach;
?>
              </select>
            </div>

            <div class="formElement">
              <label for="controller"><?=$controller;?></label>
              <input type="text" name="controller" />
            </div>

            <div class="formElement">
              <label for="methods"><?=$methods;?></label>
              <input type="text" name="methods" />
            </div>

            <div class="formElement">
              <input type="submit" id="submit" value="<?=$filter;?>" />
            </div>

          </form>

        </td>
      </tr>

      <tr>
        <td class="paging">
          <form method="get" action="<?=$_uri;?>auth_access_permissions/<?=$_action=='filter'?'filter':'read';?><?=$pager['limit']?'/limit/'.$pager['limit']:'';?>">
<?
  if ( $pager['prev'] >= 1 ) :
?>
          <span id="link">
            <a href="<?=$_uri;?>auth_access_permissions/<?=$_action=='filter'?'filter':'read';?><?=$pager['limit']>0?'/limit/'.$pager['limit']:'';?>/page/<?=$pager['prev'];?><?=$vars?'?'.$vars:'';?>">
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
            <a href="<?=$_uri;?>auth_access_permissions/<?=$_action=='filter'?'filter':'read';?><?=$pager['limit']>0?'/limit/'.$pager['limit']:'';?>/page/<?=$pager['next'];?><?=$vars?'?'.$vars:'';?>">
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
              <th><?=$profile;?></th>
              <th><?=$controller;?></th>
              <th><?=$methods;?></th>
            </tr>
<?
  foreach ( $list as $item ) :
?>
            <tr>
              <td><a href="<?=$_uri;?>auth_access_permissions/<?=$item['id'];?>/read"><?=$item['id'];?></a></td>
              <td><a href="<?=$_uri;?>auth_access_permissions/<?=$item['id'];?>/read"><?=$item['profile'];?></a></td>
              <td><a href="<?=$_uri;?>auth_access_permissions/<?=$item['id'];?>/read"><?=$item['controller'];?></a></td>
              <td><a href="<?=$_uri;?>auth_access_permissions/<?=$item['id'];?>/read"><?=$item['methods'];?></a></td>
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
            <a href="<?=$_uri;?>auth_access_permissions"><?=$back;?></a> &nbsp;
            <a href="<?=$_uri;?>auth_access_permissions/<?=$_id;?>/edit"><?=$edit;?></a> &nbsp;
            <a href="<?=$_uri;?>auth_access_permissions/<?=$_id;?>/delete"><?=$delete;?></a>
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
            <label><?=$profile;?></label>
            <span><?=$element['profile'];?></span>
          </div>

          <div class="element">
            <label><?=$controller;?></label>
            <span><?=$element['controller'];?></span>
          </div>

          <div class="element">
            <label><?=$methods;?></label>
            <span><?=$element['methods'];?></span>
          </div>

        </td>
      </tr>
<?
endif;
?>
    </table>
