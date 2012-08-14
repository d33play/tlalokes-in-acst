<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <title><?=$title;?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="en-uk" />
    <meta name="robots" content="index,follow" />
    <meta name="author" content="Tlalokes Framework" />
    <meta name="generator" content="Tlalokes Framework v1.0a" />
    <meta name="keywords" content="some, keywords" />
    <link rel="stylesheet" href="<?=$css;?>tlalokes_crud_blue.css" />
  </head>

  <body <?if ( isset( $exception ) && $exception ) :?>onload="alert('<?=$exception;?>');"<?endif;?>>

<table class="list" align="center">

  <tr>
    <td class="data">
<?
// Exception
if ( isset( $exception ) && $exception ) :
?>
      <div class="element">
        <p><?=$exception;?></p>
        <p><a href="javascript:window.back();"><?=$back;?></a></p>
      </div>
<?
else :
  // logout
  if ( $_action == 'logout' ) :
?>
      <div class="element">
        <p><a href="<?=$uri;?>auth"><?=$back;?></a></p>
      </div>
<?
// login
  else :
    if ( !isset( $flag ) || !$flag ) :
?>
      <form action="<?=$uri;?>auth" method="post">

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
          <input type="submit" value="<?=$submit;?>" />
        </div>

      </form>
<?
    else :
?>
      <div class="element">
        <p><?=$welcome;?></p>
        <p><a href="<?=$uri;?>auth/logout"><?=$exit;?></a></p>
      </div>
<?
    endif;
  endif;
endif;
?>

    </td>
  </tr>

</table>

  </body>

</html>