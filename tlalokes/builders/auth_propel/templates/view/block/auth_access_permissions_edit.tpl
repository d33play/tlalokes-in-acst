    <table class="list" align="center">

      <tr>
        <td class="title">
          <div id="title"><?=$title;?></div>
          <div align="right">
            <a href="<?=$_uri;?>"><?=$home;?></a> &nbsp;
            <a href="javascript:history.back();"><?=$back;?></a> &nbsp;
          </div>
        </td>
      </tr>

      <tr>
        <td class="data">

          <form method="post" action="<?=$uri;?>auth_access_permissions/<?=$_id;?>">

            <div class="element">
              <label for="id"><?=$id;?></label>
              <input type="text" name="id" value="<?=$element['id'];?>" />
            </div>

            <div class="element">
              <label for="profile"><?=$profile;?></label>
              <select name="profile">
                <option value=""><?=$select_an_option;?></option>
<?
foreach ( $auth_access_profiles as $item ) :
?>
                <option value="<?=$item['id'];?>"<?=$element['profile']==$item['name']?' selected':'';?>><?=$item['name'];?></option>
<?
endforeach;
?>
              </select>
            </div>

            <div class="element">
              <label for="controller"><?=$controller;?></label>
<?
if ( isset( $controllers ) ) :
?>
              <select name="controller" onchange="location.replace('<?=$_uri;?>auth_access_permissions/<?=$_id;?>/<?=$_action;?>/controller/'+this.value+'/profile/'+profile.value);">
                <option><?=$select_an_option;?></option>
<?
  foreach ( $controllers as $ctlr ) :
    if ( isset( $req_controller ) ) :
?>
                <option<?=$req_controller==$ctlr?' selected':'';?>><?=$ctlr;?></option>
<?
    else :
?>
                <option<?=$element['controller']==$ctlr?' selected':'';?>><?=$ctlr;?></option>
<?
    endif;
  endforeach;
?>
              </select>
<?
else :
?>
              <input type="text" name="controller" value="<?=$element['controller'];?>" />
<?
endif;
?>
            </div>

            <div class="element">
              <label for="methods"><?=$methods;?></label>
              <textarea name="methods"><?=isset($_methods)&&$_methods?implode(',',$_methods):$element['methods'];?></textarea>
            </div>

            <div class="element">
              <label>&nbsp;</label>
              <input type="submit" value="<?=$save;?>" />
            </div>

          </form>

        </td>
      </tr>

    </table>
