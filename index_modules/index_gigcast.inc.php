<table width="280" border="0" cellpadding="0" cellspacing="0" class="gigcasts">
  <tr>
    <td colspan="2" valign="top"><img src="<?=IMAGE_HOST?>/site_gfx_new/DD_gigcast.png" width="280" height="35" class="headerimg" /></td>
  </tr>
  <tr>
    <td colspan="2" class="padding">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="padding">
        <tr>
          <td style="padding:5px;" >
            <?
            require_once(WWW_ROOT.'/rss/gigcast.inc.php');    
            foreach($rss_channel['ITEMS'] as $ct=>$piece) 
            {
              $name = "<A HREF='".$piece['LINK']."'>".$piece['TITLE']."</A>";
              $ts   = strtotime($piece['PUBDATE']);
              $name = date("M.d", $ts) .' '. $name;
              
              $piece['DESCRIPTION'] = preg_replace('`<a (.*)>(.*)</a>`U', '', $piece['DESCRIPTION']);
              $piece['DESCRIPTION'] = str_replace('"', '&quot;', $piece['DESCRIPTION']);
              $piece['DESCRIPTION'] = str_replace("\n", "", $piece['DESCRIPTION']);
              $piece['DESCRIPTION'] = str_replace("\r", "", $piece['DESCRIPTION']);
              
              //$OMO = "onMouseover=\"ddrivetip('<DIV ALIGN=\'center\'>".str_replace("'", "\'", $piece['DESCRIPTION'])."</DIV>', '#FF0000');return true\"; onMouseout=\"hideddrivetip()\"";
              if ( YMD == date("Ymd", $ts) ) {
                echo "<DIV CLASS='gigcasts' ALIGN='LEFT' $OMO>* ".$name."</DIV>";
              }
              else {
                echo "<DIV CLASS='gigcasts' ALIGN='LEFT' $OMO>".$name."</DIV>";
              }
              if ( $ct == 9 ) break;
            }
            ?>
            <div align="center" style='padding:10px;'>
              <A HREF='itpc://gigcast.libsyn.com/rss'><IMG SRC='<?=IMAGE_HOST?>/site_gfx/itunes.png' border='0'></A>
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>