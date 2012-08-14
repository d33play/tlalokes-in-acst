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

          <form method="post" action="<?=$uri;?>auth_access_profiles_roles/<?=$_id;?>">

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
              <label for="role"><?=$role;?></label>
              <select name="role">
                <option value=""><?=$select_an_option;?></option>
<?
foreach ( $auth_roles as $item ) :
?>
                <option value="<?=$item['id'];?>"<?=$element['role']==$item['name']?' selected':'';?>><?=$item['name'];?></option>
<?
endforeach;
?>
              </select>
            </div>

            <div class="element">
              <label>&nbsp;</label>
              <input type="submit" value="<?=$save;?>" />
            </div>

          </form>

        </td>
      </tr>

    </table>
