<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <title><?=$title;?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="en-us" />
    <meta name="robots" content="index,follow" />
    <meta name="author" content="Basilio Briceno" />
    <meta name="generator" content="Tlalokes's auth-propel builder" />
<?
if ( isset( $_keywords ) && $_keywords ):
?>
    <meta name="keywords" content="<?=$_keywords;?>" />
<?
endif;
?>
    <link rel="stylesheet" href="<?=$css;?>tlalokes_crud_blue.css" />
  </head>

  <body<? if ( isset( $exception ) && $exception ) :?> onload="alert('<?=$exception;?>');"<? endif;?>>

<?
// Exception
if ( isset( $exception ) && $exception ) :
?>
    <table border="0" cellspacing="0" cellpadding="0" align="center" class="list">
      <tr>
        <td class="data">
          <div class="element">
            <p><?=$exception;?></p>
            <p align="center"><a href="javascript:history.back();">&laquo; <?=$back;?></a></p>
          </div>
        </td>
      </tr>
    </table>
<?
// Login and logout
else :
  if ( !isset( $flag ) || !$flag ) :
?>
    <table border="0" cellspacing="0" cellpadding="0" align="center" class="list">
      <tr>
        <td class="data">
          <form action="<?=$_uri;?>auth/login" method="post">
            <div class="element">
              <label for="email"><?=$email;?></label>
              <input type="text" name="email" />
            </div>
            <div class="element">
              <label for="password"><?=$password;?></label>
              <input type="password" name="password" />
            </div>
            <div class="element">
              <label>&nbsp;</label>
              <button type="submit"><?=$login;?></button>
            </div>
          </form>
          <p align="center">
            <a href="<?=$_uri;?>">&laquo; <?=$back;?></a>
          </p>
        </td>
      </tr>
    </table>
<?
  else :
?>
    <div align="center">
      <div class="nav">
        <span><?=$nav_title;?>:</span>
        <a href="<?=$_uri;?>auth"><?=$nav_home;?></a> |
        <a href="<?=$_uri;?>auth_users"><?=$nav_users;?></a> |
        <a href="<?=$_uri;?>auth_roles"><?=$nav_roles;?></a> |
        <a href="<?=$_uri;?>auth_access_profiles"><?=$nav_access_profiles;?></a> |
        <a href="<?=$_uri;?>auth_access_profiles_roles"><?=$nav_access_profiles_roles;?></a> |
        <a href="<?=$_uri;?>auth_access_permissions"><?=$nav_access_permissions;?></a> |
        <a href="<?=$_uri;?>auth/logout"><?=$nav_logout;?></a>
      </div>
    </div>

    <table border="0" cellspacing="0" cellpadding="0" align="center" class="list">
      <tr>
        <td class="data">
          <p><?=$welcome;?></p>
          <p align="center"><a href="<?=$_uri;?>auth/logout"><?=$exit;?></a></p>
        </td>
      </tr>
    </table>
<?
  endif;
endif;
?>

  </body>

</html>
