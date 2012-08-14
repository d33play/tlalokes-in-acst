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
            <a href="<?=$_uri;?>auth_access_profiles/add"><?=$add;?></a>
          </div>
        </td>
      </tr>

      <tr>
        <td class="filter">

          <form method="get" action="<?=$_uri;?>auth_access_profiles/filter<?=$pager['limit']?'/limit/'.$pager['limit']:'';?>">

            <div class="formElement">
              <label for="id"><?=$id;?></label>
              <input type="text" name="id" />
            </div>

            <div class="formElement">
              <label for="name"><?=$name;?></label>
              <input type="text" name="name" />
            </div>

            <div class="formElement">
              <label for="description"><?=$description;?></label>
              <input type="text" name="description" />
            </div>

            <div class="formElement">
              <input type="submit" id="submit" value="<?=$filter;?>" />
            </div>

          </form>

        </td>
      </tr>

      <tr>
        <td class="paging">
          <form method="get" action="<?=$_uri;?>auth_access_profiles/<?=$_action=='filter'?'filter':'read';?><?=$pager['limit']?'/limit/'.$pager['limit']:'';?>">
<?
  if ( $pager['prev'] >= 1 ) :
?>
          <span id="link">
            <a href="<?=$_uri;?>auth_access_profiles/<?=$_action=='filter'?'filter':'read';?><?=$pager['limit']>0?'/limit/'.$pager['limit']:'';?>/page/<?=$pager['prev'];?><?=$vars?'?'.$vars:'';?>">
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
            <a href="<?=$_uri;?>auth_access_profiles/<?=$_action=='filter'?'filter':'read';?><?=$pager['limit']>0?'/limit/'.$pager['limit']:'';?>/page/<?=$pager['next'];?><?=$vars?'?'.$vars:'';?>">
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
              <th><?=$description;?></th>
            </tr>
<?
  foreach ( $list as $item ) :
?>
            <tr>
              <td><a href="<?=$_uri;?>auth_access_profiles/<?=$item['id'];?>/read"><?=$item['id'];?></a></td>
              <td><a href="<?=$_uri;?>auth_access_profiles/<?=$item['id'];?>/read"><?=$item['name'];?></a></td>
              <td><a href="<?=$_uri;?>auth_access_profiles/<?=$item['id'];?>/read"><?=$item['description'];?></a></td>
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
            <a href="<?=$_uri;?>auth_access_profiles"><?=$back;?></a> &nbsp;
            <a href="<?=$_uri;?>auth_access_profiles/<?=$_id;?>/edit"><?=$edit;?></a> &nbsp;
            <a href="<?=$_uri;?>auth_access_profiles/<?=$_id;?>/delete"><?=$delete;?></a>
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
            <label><?=$description;?></label>
            <span><?=$element['description'];?></span>
          </div>

        </td>
      </tr>

    </table>
<?
endif;
?>
