<?
$COMIC_ROW->comic_caps_id = 0;
$seed = rand(1, 999999);
$ret = '<style type="text/css">
.ftr_'.$seed.':link {
	font-weight:bold;
	color: #FFCC00;
	text-decoration: none;
}
.ftr_'.$seed.':visited {
	font-weight:bold;

	color: #FFCC00;
	text-decoration: none;
}
.ftr_'.$seed.':hover {
	font-weight:bold;

	color: #FFFF00;
	text-decoration: underline;
}
.ftr_'.$seed.':active {
	font-weight:bold;

	color: #000000;
	text-decoration: underline;
	background-color: #FFFFFF;
}
</style>
<div align="center">
  <table border="0" cellpadding="0" cellspacing="0" width="800" style="margin-left:auto; margin-right:auto; margin-top:0; padding:0;">
    <tr>
      <td height="60" colspan="3" align="center" valign="middle" background="'.IMAGE_HOST_SITE_GFX.'/comic_caps/'.$COMIC_ROW->comic_caps_id.'/DD_btmtool_r2_c1.png">
        <p style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFF; margin:2px;">&nbsp;</p>
        <p style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFF; margin:2px;">
          <a href="http://'.DOMAIN.'/search.php" class="ftr_'.$seed.'">browse</a> |
          <a href="http://'.DOMAIN.'/news_archive.php" class="ftr_'.$seed.'">news</a> |
		      <a href="http://'.DOMAIN.'/community/" class="ftr_'.$seed.'">forums</a> |
          <a href="http://'.DOMAIN.'/store.php" class="ftr_'.$seed.'">store</a> |
          <a href="http://'.DOMAIN.'/account/" class="ftr_'.$seed.'">my controls</a> |
          <a href="'.$_SERVER['PHP_SELF'].'?p='.$PAGE_ROW->page_id.'&logout=1" class="ftr_'.$seed.'">logout</a>
        </p>
        <p style="font-family:Arial, Helvetica, sans-serif; font-size:9px; color:#FFF; padding:0; margin:2px;">
            <a href="http://'.DOMAIN.'/contact.php" class="ftr_'.$seed.'">About Us</a> | <a href="http://'.DOMAIN.'/contact.php" class="ftr_'.$seed.'">Contact</a> | <a href="http://'.DOMAIN.'/privacy.php" class="ftr_'.$seed.'">Privacy Policy</a> | Copyright &copy; 2010. WOWIO, Inc. All Rights Reserved.
        </p>
      </td>
    </tr> 
    <tr>
      <td bgcolor="#000000" align="center">
        <div style="border-left:1px solid #898989;border-right:1px solid #898989;margin-left:2px;margin-right:1px;">';

$ret .= '</div>
      </td>
  </table>
</div>
<script type="text/javascript"> for(var cs=0; cs<commandStack.length;cs++){eval(commandStack[cs]);} </script>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-606793-4";
urchinTracker();
</script>
<!--FTR-->';

return $ret;
?>
