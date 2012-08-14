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

          <form method="post" action="<?=$_uri;?>auth_users/<?=$_id;?>">

            <div class="element">
              <label for="id"><?=$id;?></label>
              <input type="text" name="id" value="<?=$element['id'];?>" />
            </div>

            <div class="element">
              <label for="role"><?=$role;?></label>
              <select name="role">
                <option value=""><?=$select_a_role;?></option>
<?
foreach ( $auth_roles as $item ) :
?>
                <option value="<?=$item['id'];?>"<?=$element['role_name']==$item['name']?' selected':'';?>><?=$item['name'];?></option>
<?
endforeach;
?>
              </select>
            </div>

            <div class="element">
              <label for="email"><?=$email;?></label>
              <input type="text" name="email" value="<?=$element['email'];?>" />
            </div>

            <div class="element">
              <label for="password"><?=$password;?></label>
              <input type="password" name="pass" onchange="document.getElementById('password').value=this.value;" />
              <input type="hidden" id="password" name="password" value="<?=$element['password'];?>">
            </div>

            <div class="element">
              <label for="user_status"><?=$user_status;?></label>
              <select name="user_status">
                <option value="0"<?=!$element['user_status']?' selected':'';?>><?=$inactive;?></option>
                <option value="1"<?=$element['user_status']?' selected':'';?>><?=$active;?></option>
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
