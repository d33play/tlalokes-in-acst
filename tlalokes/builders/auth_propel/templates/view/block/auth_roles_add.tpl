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

          <form method="post" action="<?=$_uri;?>auth_roles">

            <!--div class="element">
              <label for="id"><?=$id;?></label>
              <input type="text" name="id" />
            </div-->

            <div class="element">
              <label for="name"><?=$name;?></label>
              <input type="text" name="name" />
            </div>

            <div class="element">
              <label for="active"><?=$role_status;?></label>
              <select name="role_status">
                <option value="0"><?=$inactive;?></option>
                <option value="1"><?=$active;?></option>
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
