<?
include('../includes/global.inc.php');

/*
if ( false && $_GET['pq'] )
{
  include(WWW_ROOT.'/community/message/tikimail_func.inc.php');

  $res = db_query("SELECT * FROM users");
  while($row = db_fetch_object($res) )
  {
    $username = $row->username;
    $MSG = "\n".'[url=http://'.DOMAIN.'/ecard/][img]'.IMAGE_HOST.'/ecard/greeting_envelope.gif[/img][/url]'."\n\n";
    send_system_mail('DrunkDuck.com', $username, 'Happy Holidays from DrunkDuck!', $MSG);
  }
}
*/

if ( isset($_GET['msg']) )
{
  $res = db_query("SELECT * FROM ecards_sent WHERE msg_id='".db_escape_string($_GET['msg'])."'");
  if ( $row = db_fetch_object($res) ) {
    $MSG = $row->message;

    db_query("UPDATE ecards_sent SET is_read=is_read+1 WHERE msg_id='".db_escape_string($_GET['msg'])."'");
  }
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Happy Holidays from the Duck!</title>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>

<style type="text/css">
<!--
body {
	font-family: "Lucida Grande", "Lucida Sans", Arial, sans-serif;
	font-size: 12px;
	margin: 0px;
	padding: 0px;
	background:url(<?=IMAGE_HOST?>/ecard/dd-card_bg.gif) no-repeat top center #339900;
	color:#660000;

}

p {margin: 5px; padding:0;}

a {color:#300; font-weight:bold; text-decoration:none;}
.style3 {font-size: 12px}
.style4 {font-size: 9px}
.style5 {font-size: 14px}
-->
</style>
</head>
<body>
<!--url's used in the movie-->
<!--text used in the movie-->
<!-- saved from url=(0013)about:internet -->

<table width="988" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="50" colspan="2" align="right"><a href="send.php"><img src="<?=IMAGE_HOST?>/ecard/button.gif" width="300" height="50" border="0"></a></td>
  </tr>
  <tr>
    <td align="center" valign="middle">
    <?
    if ( isset($MSG) && isset($_GET['msg']) )
    {
      ?>
      <table border="0" cellpadding="0" cellspacing="5" width="260">
        <tr>
          <td align="right" style="color:red;"><b>To:</b></td>
          <td align="left" style="color:red;"><?=$row->to_name?></td>
        </tr>
        <tr>
          <td align="right" style="color:red;"><b>From:</b></td>
          <td align="left" style="color:red;"><?=$row->from_name?></td>
        </tr>
      </table>
      <br><br>
      <?
    }
    else if ( $USER )
    {
      ?>
      <table border="0" cellpadding="0" cellspacing="5" width="260">
        <tr>
          <td align="right" style="color:red;"><b>To:</b></td>
          <td align="left" style="color:red;"><?=$USER->username?></td>
        </tr>
        <tr>
          <td align="right" style="color:red;"><b>From:</b></td>
          <td align="left" style="color:red;">DrunkDuck</td>
        </tr>
      </table>
      <br><br>
      <?
    }
    ?>

    <p>The DrunkDuck team<br>
          <span class="style3">Volte6<br>
            Black Kitty, Ronson, Skoolmonkey, SpAng, <br>
            Dragonlova, Mojo, darthmoridin</span></p>
      <p>&nbsp;</p>
      <p class="style5">Artwork by John Daiker<br>
      aka <a href="/user_lookup.php?u=Cindermain">Cindermain</a></p>
      <p class="style3">animation by darthmoridin<br>
      music by Andre Devon Magone </p>
      <p class="style3">&nbsp;</p>
      <p><span class="style5">Special thanks to</span><br>
          <span class="style4">(Back row standing)</span><br>
          <span class="style5">Ronson &#8211; <a href="/The_Gods_of_ArrKelaan/" target="_blank">&#8220;The Gods of Arrkelaan&#8221;</a><br>
          djcoffman &#8211; <a href="/Hero_By_Night_Diaries/" target="_blank">&#8220;Hero By Night Diaries&#8221;</a> <br>
          Inkmonkey &#8211; <a href="/Elijah_and_Azuu/" target="_blank">&#8220;Elijah and Azuu&#8221;</a><br>
          Aywren &#8211; <a href="/Shimmer/index.php" target="_blank">&#8220;Shimmer&#8221;</a><br>
        TRS &#8211; <a href="/Vreakerz/" target="_blank">&#8220;Vreakers&#8221;</a></span><br>
  <span class="style4">(Front row sitting)</span> <BR>
        <span class="style5">Cheeko &#8211; <a href="/One_Question/" target="_blank">&#8220;One Question&#8221;</a><br>
        Amelius &#8211; <a href="/Charby_the_Vampirate/" target="_blank">&#8220;Charby the Vampirate&#8221;</a><BR>
        Hawk &#8211; <a href="/Culture_Shock/" target="_blank">&#8220;Culture Shock&#8221;</a><br>
        ZoeStead &#8211; <a href="/The_WAVAM_Project/" target="_blank">&#8220;WAVAAM Project&#8221;</a><br>
        Eviltwinpixie &#8211; <a href="/Grog/" target="_blank">&#8220;Grog&#8221;</a><br>
        </span><span class="style3">for their permission and participation.</span></p>
      <p><span class="style4"><br>
    </span></p></td>
    <td width="700" valign="top">
      <?
      $swf = new FlashMovie(IMAGE_HOST.'/ecard/dd_e-card.swf', 700, 700);
      $swf->setTransparent(true);
      $swf->addVar('msg', $MSG);
      $swf->showHTML();
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
      <p>&nbsp;</p>
      <p><span class="style4">All characters are copyrights of their respective owners.
        DrunkDuck
        &copy; 2006 <a href="http://www.platinumstudios.com">Platinum Studios, Inc.</a> All Rights Reserved.</span></p>
      <p>&nbsp;</p>
    </div></td>
  </tr>
</table>
</body>
</html>
