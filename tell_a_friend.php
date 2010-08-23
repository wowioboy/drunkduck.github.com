<?
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
<body bgcolor="#CCCCCC">
<?
$CID = ( ($_GET['cid'])?(int)$_GET['cid']:(int)$_POST['cid'] );
$PID = ( ($_GET['pid'])?(int)$_GET['pid']:(int)$_POST['pid'] );

$res = db_query("SELECT * FROM comics WHERE comic_id='".$CID."'");
if ( !($COMIC_ROW = db_fetch_object($res)) ) return;
db_free_result($res);

$res = db_query("SELECT * FROM comic_pages WHERE page_id='".$PID."' AND comic_id='".$CID."'");
if ( !($PAGE_ROW = db_fetch_object($res)) ) return;
db_free_result($res);

?>
  <div align="left"><img src="<?=IMAGE_HOST_SITE_GFX?>/tags/share_hdr.gif"></div>
<table width="100%" height="410" border="0" cellspacing="0" cellpadding="10" border="0" id="user">
	<tr>
<?

if ( !$_POST['recipients'] )
{
  ?>
	  <td width="270" valign="top">
      <form action="<?=$_SERVER['PHP_SELF']?>" id="ShareForm" name="ShareForm" method="POST">
        <strong><nobr>Email To:</nobr></strong>
        <br />
  			Enter email addresses, separated by commas. Maximum 200 characters.<br />
        <textarea id="recipients" name="recipients" rows="8" cols="32" value="" size="60" maxlength="255"></textarea>
        <br />
        <strong><nobr>Your First Name</nobr>:</strong> (optional)
        <br>
        <input type=text name="first_name" value="" maxlength="100" style="width :255px;">
        <br />
        <strong><nobr>Add a personal message:</nobr></strong> (optional)
        <br>
        <textarea wrap="virtual" name="message" rows="3" cols="32">This Comic is awesome!</textarea>
        <br />
        <input type="submit" onClick="addressframe.removeCommas();" name="action_send" value="Send">
  			<input type="hidden" name="cid" value="<?=$CID?>">
  			<input type="hidden" name="pid" value="<?=$PID?>">
      </form>
    </td>
    <td width="200" valign="top">
      <iframe id="addressframe"  name="addressframe" style="width:200px; height:408px; border: 0px"  src="/login.php" scrolling="auto" frameborder="no" framespacing="0" border="0"></iframe>
    </td>
  <?
}
else
{
  $EMAILS = substr($_POST['recipients'], 0, 200);
  $EMAILS = explode(",", $EMAILS);

  $URL    = 'http://'.DOMAIN.'/'.comicNameToFolder($COMIC_ROW->comic_name).'/?p='.$PID;
  $THUMB  = get_current_thumbnail($CID, $COMIC_ROW->comic_name);
  $THUMB  = str_replace("<img ", '<img width="80" height="100" ', $THUMB);
  $NAME   = $_POST['first_name'];
  if ( strlen($NAME) < 2 ) {
    if ( $USER ) {
      $NAME = $USER->username;
    }
    else {
      $NAME = "Someone";
    }
  }

  $ct = 0;
  foreach($EMAILS as $email)
  {
    $email = trim($email);
    if ( strstr($email, '@') )     {
      ++$ct;
      sendMail($email, $NAME.' sent you a comic strip!', "<p><b>I want to share the following comic with you:</b></p>".
                                                         "<p><a href=\"".$URL."\">".$THUMB."</a></p>".
                                                         "<p><b>Comic Description:</b></p>".
                                                         "<p>".$COMIC_ROW->description."</p>".
                                                         "<p><b>Personal Message:</b></p>".
                                                         "<p>".$_POST['message']."</p>".
                                                         "<p>Thanks,<br>".$NAME."</p>",
                                                         'DrunkDuck Service <service@drunkduck.com>');
    }
  }
  db_query("UPDATE tell_a_friend SET counter=counter+".$ct." WHERE ymd_date='".date("Ymd")."'");
  if ( db_rows_affected() < 1 ) {
    db_query("INSERT INTO tell_a_friend (ymd_date, counter) VALUES ('".date("Ymd")."', '".$ct."')");
  }

  db_query("UPDATE popular_tell_a_friends SET counter=counter+1 WHERE ymd_date='".date("Ymd")."' AND comic_id='".$CID."' AND page_id='".$PID."'");
  if ( db_rows_affected() < 1 ) {
    db_query("INSERT INTO popular_tell_a_friends (ymd_date, comic_id, page_id, counter) VALUES ('".date("Ymd")."', '".$CID."', '".$PID."', '1')");
  }
  ?>
  <td align="center" valign="top">
    <table border="0" cellpadding="0" cellspacing="20" width="100%" height="100%">
      <tr>
        <td valign="top" width="100" align="center">
          <div align="center" style="border:1px solid white;padding:5px;">
            <?=$THUMB?>
          </div>
        </td>
        <td valign="top" width="100%" align="left">
          <b>Success!</b> Comic has been sent to:
          <ul>
            <?
            foreach($EMAILS as $email)
            {
              $email = trim($email);
              if ( strstr($email, '@') )     {
                ?><li><?=$email?></li><?
              }
            }
            ?>
          </ul>
        </td>
      </tr>
      <?
      if ( !$USER )
      {
        ?>
        <tr>
          <td align="center" colspan="2">
            <div align="center" style="padding:5px;background:#2B0095;border:1px solid #CCCCCC;width:200px;"><a href="http://<?=DOMAIN?>/signup/" target="_blank">Sign up</a> for a free DrunkDuck account</div>
          </td>
        </tr>
        <?
      }
      ?>
    </table>
  </td>
  </tr>
  <tr>
    <td align="right" colspan="2" valign="bottom">
      <a href="#" onClick="window.close();">Close</a>
    </td>
  <?
}
?>
  </tr>
</table>
</body>
</html>