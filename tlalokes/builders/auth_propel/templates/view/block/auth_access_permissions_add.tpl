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

          <form method="post" action="<?=$uri;?>auth_access_permissions">

            <div class="element">
              <label for="profile"><?=$profile;?></label>
              <select name="profile">
                <option value=""><?=$select_an_option;?></option>
<?
foreach ( $auth_access_profiles as $item ) :
?>
                <option value="<?=$item['id'];?>"<?=isset($prfl)&&$prfl==$item['id']?' selected':'';?>><?=$item['name'];?></option>
<?
endforeach;
?>
              </select>
            </div>

            <div class="element">
              <label for="controller"><?=$controller;?></label>
<?
if ( isset( $controllers ) && $controllers ) :
?>
              <select name="controller" onchange="location.replace('<?=$_uri;?>auth_access_permissions/<?=$_action;?>/controller/'+this.value+'/profile/'+profile.value);">
                <option><?=$select_an_option;?></option>
<?
  foreach ( $controllers as $ctlr ) :
?>
                <option<?=isset($req_controller)&&$req_controller==$ctlr?' selected':'';?>><?=$ctlr;?></option>
<?
  endforeach;
?>
              </select>
<?
else :
?>
              <input type="text" name="controller" />
<?
endif;
?>
            </div>

<?
if ( isset( $_methods ) && $_methods ) :
?>
            <div class="element">
              <label for="methods"><?=$methods;?></label>
              <textarea name="methods"><?=implode( ',', $_methods );?></textarea>
            </div>
<?
endif;
?>

            <div class="element">
              <label>&nbsp;</label>
              <input type="submit" value="<?=$save;?>" />
            </div>

          </form>

        </td>
      </tr>

    </table>
