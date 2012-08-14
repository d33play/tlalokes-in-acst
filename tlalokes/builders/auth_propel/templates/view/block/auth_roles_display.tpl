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
            <a href="<?=$_uri;?>auth_roles/add"><?=$add;?></a>
          </div>
        </td>
      </tr>

      <tr>
        <td class="filter">

          <form method="get" action="<?=$_uri;?>auth_roles/filter<?=$pager['limit']?'/limit/'.$pager['limit']:'';?>">

            <div class="formElement">
              <label for="id"><?=$id;?></label>
              <input type="text" name="id" />
            </div>

            <div class="formElement">
              <label for="name"><?=$name;?></label>
              <input type="text" name="name" />
            </div>

            <div class="formElement">
              <label for="role_status"><?=$role_status;?></label>
              <select name="role_status">
                <option value=""><?=$select_one;?></option>
                <option value="0"><?=$inactive;?></option>
                <option value="1"><?=$active;?></option>
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
          <form method="get" action="<?=$_uri;?>auth_roles/<?=$_action=='filter'?'filter':'read';?><?=$pager['limit']?'/limit/'.$pager['limit']:'';?>">
<?
  if ( $pager['prev'] >= 1 ) :
?>
          <span id="link">
            <a href="<?=$_uri;?>auth_roles/<?=$_action=='filter'?'filter':'read';?><?=$pager['limit']>0?'/limit/'.$pager['limit']:'';?>/page/<?=$pager['prev'];?><?=$vars?'?'.$vars:'';?>">
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
            <a href="<?=$_uri;?>auth_roles/<?=$_action=='filter'?'filter':'read';?><?=$pager['limit']>0?'/limit/'.$pager['limit']:'';?>/page/<?=$pager['next'];?><?=$vars?'?'.$vars:'';?>">
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
              <th><?=$name;?></th>
              <th><?=$role_status;?></th>
            </tr>
<?
  foreach ( $list as $item ) :
?>
            <tr>
              <td><a href="<?=$_uri;?>auth_roles/<?=$item['id'];?>/read"><?=$item['id'];?></a></td>
              <td><a href="<?=$_uri;?>auth_roles/<?=$item['id'];?>/read"><?=$item['name'];?></a></td>
              <td><a href="<?=$_uri;?>auth_roles/<?=$item['id'];?>/read"><?=$item['role_status']?$active:$inactive;?></a></td>
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
            <a href="<?=$_uri;?>auth_roles"><?=$back;?></a> &nbsp;
            <a href="<?=$_uri;?>auth_roles/<?=$_id;?>/edit"><?=$edit;?></a> &nbsp;
            <a href="<?=$_uri;?>auth_roles/<?=$_id;?>/delete"><?=$delete;?></a>
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
            <label><?=$name;?></label>
            <span><?=$element['name'];?></span>
          </div>

          <div class="element">
            <label><?=$role_status;?></label>
            <span><?=!$element['role_status']?$inactive:$active;?></span>
          </div>

        </td>
      </tr>
<?
endif;
?>
    </table>
