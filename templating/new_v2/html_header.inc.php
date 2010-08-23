<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="description" content="Drunk Duck is the webcomics community that provides FREE hosting and memberships to people who love to read or write comic books, or comic strips.">
<meta name="keywords" content="The Webcomics Community, Webcomics Community, The Comics Community, Comics Community, Comics, Webcomics, Stories, Strips, Comic Strips, Comic Books, Funny, Interesting, Read, Art, Drawing, Photoshop">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<META NAME="robots" CONTENT="index,follow">
<script src="/__utm.js" type="text/javascript"></script>
<title>DrunkDuck: The Webcomics Community - <?=$TITLE?></title>
<link href="<?=IMAGE_HOST_SITE_GFX?>/ddstyles.css" rel="stylesheet" type="text/css" />
<SCRIPT LANGUAGE="JavaScript" SRC="<?=HTTP_JAVASCRIPT?>/prototype-1.4.0_modified.js" TYPE="text/javascript"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="<?=HTTP_JAVASCRIPT?>/commonJS.js" TYPE="text/javascript"></SCRIPT>
</head>

<body <?=( ($USER->user_id==1) ? 'onLoad="$(\'template_top_ad\').innerHTML = AD_SPOTS.template_top_ad;$(\'template_bottom_ad\').innerHTML = AD_SPOTS.template_bottom_ad;"' : '' )?>>

<!--  <script type="text/javascript" src="http://www.platinumstudios.com/processing/ps_hat_top.js"></script>-->

  <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="145" align="center" valign="top">
        <!--header-->
        <table width="940" height="145" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td valign="bottom"><a href="http://<?=DOMAIN?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/HDR_elduque.gif" width="194" height="145" border="0" /></a></td>
            <td valign="bottom">

              <table width="728" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td valign="bottom"><?
                  if ( substr($_SERVER['PHP_SELF'], 0, strlen('/tutorials/')) == '/tutorials/' ) {
                    ?><? $ord=time() ?><script language="JavaScript" src="http://ad.doubleclick.net/adj/dduck.tutorials/banner;sz=728x90;ord=<? echo $ord ?>?" type="text/javascript"></script><noscript><a href="http://ad.doubleclick.net/jump/dduck.tutorials/banner;sz=728x90;ord=<? echo $ord ?>?" target="_blank"><img src="http://ad.doubleclick.net/ad/dduck.tutorials/banner;sz=728x90;ord=<? echo $ord ?>?" width="728" height="90" border="0" alt=""></a></noscript><?
                  }
                  else {
                    ?><? $ord=time() ?><script language="JavaScript" src="http://ad.doubleclick.net/adj/dduck.template/banner_main_top;sz=728x90;ord=<? echo $ord ?>?" type="text/javascript"></script><noscript><a href="http://ad.doubleclick.net/jump/dduck.template/banner_main_top;sz=728x90;ord=<? echo $ord ?>?" target="_blank"><img src="http://ad.doubleclick.net/ad/dduck.template/banner_main_top;sz=728x90;ord=<? echo $ord ?>?" width="728" height="90" border="0" alt=""></a></noscript><?
                  }?></td>
                </tr>
                <tr>
                  <td valign="top">
                    <table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td><a href="http://<?=DOMAIN?>/search.php" target="_top"><img src="<?=IMAGE_HOST_SITE_GFX?>/Nav_01.gif" alt="Browse &amp; Search" width="132" height="55" border="0"/></a></td>
                        <td><a href="http://<?=DOMAIN?>/news/" target="_top"><img src="<?=IMAGE_HOST_SITE_GFX?>/Nav_02.gif" alt="News" width="90" height="55" border="0"/></a></td>
                        <td><a href="http://<?=DOMAIN?>/community/" target="_top"><img src="<?=IMAGE_HOST_SITE_GFX?>/Nav_03.gif" alt="Forums" width="90" height="55" border="0"/></a></td>
                        <td><a href="http://<?=DOMAIN?>/tutorials/" target="_top"><img src="<?=IMAGE_HOST_SITE_GFX?>/btn_tutorials.gif" alt="Tutorials" width="90" height="55" border="0"/></a></td>
                        <td><a href="http://<?=DOMAIN?>/games/"><img src="<?=IMAGE_HOST_SITE_GFX?>/btn_games.gif" alt="" width="111" height="55" border="0"/></a></td>
                        <td><a href="http://<?=DOMAIN?>/account/overview/" target="_top"><img src="<?=IMAGE_HOST_SITE_GFX?>/Nav_06.gif" alt="My Controls" width="105" height="55" border="0"/></a></td>
                        <td><a href="http://<?=DOMAIN?>/account/overview/add_comic.php" target="_top"><img src="<?=IMAGE_HOST_SITE_GFX?>/Nav_07.gif" alt="Create a Comic!" width="110" height="55" border="0"/></a></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

            </td>
            <td valign="bottom"><img src="<?=IMAGE_HOST_SITE_GFX?>/HDR_sponsor.gif" width="18" height="145" /></td>
          </tr>
        </table>
        <!--END header-->
      </td>
    </tr>
    <tr>
      <td align="center" valign="top">
        <!--main content-->
        <table width="940" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="00325D" id="content">
          <tr>
            <td align="center" valign="top" id="main" width="100%">