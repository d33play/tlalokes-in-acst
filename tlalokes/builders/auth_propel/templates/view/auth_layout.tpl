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

<?
if ( isset( $exception ) && $exception ) :
?>
    <table class="list" align="center">
      <tr>
        <td class="title">
          <div id="title"><?=$title;?></div>
          <div align="right">
            <a href="<?=$_uri;?>"><?=$home;?></a> &nbsp;
            <a href="<?=$_uri;?><?=str_replace('Ctl','',$_controller);?>/add"><?=$add;?></a>
          </div>
        </td>
      </tr>
      <tr>
        <td class="data">
          <p align="center"><?=$exception;?></p>
          <p align="center"><a href="javascript:history.back();"><?=$back;?></a></p>
        </td>
      </tr>
    </table>
<?
else:
  tlalokes_layout_zone( 'content', $_layout );
endif;
?>

  </body>

</html>
