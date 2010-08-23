<?
define("DEBUG_MODE", 0);
include('includes/global.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="description" content="Drunk Duck is the webcomics community that provides FREE hosting and memberships to people who love to read or write comic books, or comic strips.">
<meta name="keywords" content="The Webcomics Community, Webcomics Community, The Comics Community, Comics Community, Comics, Webcomics, Stories, Strips, Comic Strips, Comic Books, Funny, Interesting, Read, Art, Drawing, Photoshop">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<META NAME="robots" CONTENT="index,follow">
<script src="/__utm.js" type="text/javascript"></script>
<title>DrunkDuck: The Webcomics Community - <?=$TITLE?></title>
<link href="<?=IMAGE_HOST?>/css_new_v2/ddstyles.css" rel="stylesheet" type="text/css" />
<SCRIPT LANGUAGE="JavaScript" SRC="<?=HTTP_JAVASCRIPT?>/prototype-1.4.0_modified.js" TYPE="text/javascript"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="<?=HTTP_JAVASCRIPT?>/commonJS.js" TYPE="text/javascript"></SCRIPT>
</head>
<body>
<table border="0" width="100%" height="404" cellpadding="0" cellspacing="0">
<tr>
<td width="180" height="100%" align="center" valign="top" id="user">
<?
if ( !$USER )
{
  if ( isset($_POST['un']) )
  {
    ?><div align="center" style="border:1px dashed white">Invalid Login!</div><?
  }
  ?>
  <form action="<?=$_SERVER['PHP_SELF']?>" METHOD="POST">
  <img src="<?=IMAGE_HOST_SITE_GFX?>/become_member.gif"  />
  <a href="http://<?=DOMAIN?>/signup/" target="_top">Signup for a FREE Account!</a></p>
  <p><strong>Registered users can:</strong></p>
  <p><strong> Comment on comics!</strong></p>
  <p><strong>Create their own comics!</strong></p>
  <p><strong>Vote in polls and contests!</strong></p>
  <p><strong>Use the forums!</strong></p>
  <img src="<?=IMAGE_HOST_SITE_GFX?>/member_login.gif" />
  <br>
  <img src="<?=IMAGE_HOST_SITE_GFX?>/login_r1_c1.gif" height="20" width="60">
  <br>
  <input name="un" value="username" onfocus="this.value='';" style="height: 20px; width: 100px;" type="text">
  <br>
  <img src="<?=IMAGE_HOST_SITE_GFX?>/login_r2_c1.gif" height="20" width="60">
  <br>
  <input name="pw" value="password" onfocus="this.value='';" style="height: 20px; width: 100px;" type="password">
  <br>
  &nbsp;<input type="image" src="<?=IMAGE_HOST_SITE_GFX?>/smbtns_login.gif" width="56" height="14" border="0">
  <form>
  <?
}
else {
  ?>
  &nbsp;
  <?
}
?>
</td>
</tr>
</table>
</body>
</html>