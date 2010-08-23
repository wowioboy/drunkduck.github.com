<?
if ( isset($_GET['notfound']) )
{
  $ATTEMPT = $_GET['notfound'];

  if ( strstr($ATTEMPT, '/comics/') )
  {

    $COMIC_NAME = substr( $ATTEMPT, strrpos($ATTEMPT, '/')+1 );


    $COMIC_NAME = trim( str_replace('_', ' ', $COMIC_NAME) );

    // $COMIC_NAME = substr( $ATTEMPT, strrpos($ATTEMPT, '/')+1 );


    $COMIC_NAME = trim( str_replace('_', ' ', $COMIC_NAME) );


    $res = db_query("SELECT comic_name FROM comics WHERE comic_name='".db_escape_string($COMIC_NAME)."'");

    if ( db_num_rows($res) == 1 )
    {
      $row = db_fetch_object($res);
      header("Location: http://".DOMAIN."/".comicNameToFolder($row->comic_name));
      return;
    }
    db_free_result($res);
  }
}
?>

	<div align="center">
		<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="875" height="480" id="utv567566"><param name="flashvars" value="autoplay=true&amp;brand=embed&amp;cid=4806539&amp;locale=en_US"/><param name="allowfullscreen" value="true"/><param name="allowscriptaccess" value="always"/><param name="movie" value="http://www.ustream.tv/flash/live/1/4806539"/><embed flashvars="autoplay=true&amp;brand=embed&amp;cid=4806539&amp;locale=en_US" width="875" height="480" allowfullscreen="true" allowscriptaccess="always" id="utv567566" name="utv_n_755847" src="http://www.ustream.tv/flash/live/1/4806539" type="application/x-shockwave-flash" /></object>
	</div>

      <div style="height:255px;">
        <div align="left" class="thumblist_large_title">
          Featured Webcomics
        </div>
        <div align="center" class="thumblist_large">
          <?
          $extra = '';
          
          if ( isset($GLOBALS['SUBDOM_TO_CAT'][SUBDOMAIN]) ) {
            $extra .= "AND category='".$GLOBALS['SUBDOM_TO_CAT'][SUBDOMAIN]."'";
          }
          
          if ( isset($GLOBALS['SUBDOM_TO_STYLE'][SUBDOMAIN]) ) {
            $extra .= "AND style='".$GLOBALS['SUBDOM_TO_STYLE'][SUBDOMAIN]."'";
          }

          $res = db_query("SELECT feature_id, ymd_date_live FROM featured_comics WHERE ymd_date_live<='".date("Ymd")."' AND approved='1' ".$extra." ORDER BY ymd_date_live DESC LIMIT 1");
          $row = db_fetch_object($res);
          db_free_result($res);

          $START_DATE = $row->ymd_date_live;
          $START_ID = $row->feature_id;


          if ( date("w") == 1 || date("w") == 4 )
          {
            if ( $row->ymd_date_live != date("Ymd") )
            {
              // find the new latest one
              $res = db_query("SELECT feature_id, comic_id, ymd_date_live FROM featured_comics WHERE ymd_date_live='0' AND approved='1' ORDER BY feature_id ASC LIMIT 1");
              if ( $row = db_fetch_object($res) )
              {
                db_query("UPDATE featured_comics SET ymd_date_live='".date("Ymd")."' WHERE feature_id='".$row->feature_id."'");
                $START_DATE = $row->ymd_date_live;
                $START_ID = $row->feature_id;

                include_once(WWW_ROOT.'/includes/trophies/trophy_data.inc.php');

                $cRes = db_query("SELECT * FROM comics WHERE comic_id='".$row->comic_id."'");
                if ($cRow = db_fetch_object($cRes) ) {
                  $uRes = db_query("SELECT user_id, trophy_string FROM users WHERE user_id='".$cRow->user_id."' OR user_id='".$cRow->secondary_author."'");
                  while( $uRow = db_fetch_object($uRes) ) {
                    user_update_trophies( $uRow, 17 );
                    user_update_trophies( $uRow, 18 );
                  }
                }

              }
              db_free_result($res);
            }
          }


          $FEATURED_ARR = array();
          //$res = db_query("SELECT * FROM featured_comics WHERE approved='1' AND feature_id<='".$START_ID."' ORDER BY ymd_date_live DESC LIMIT 5");
          $res = db_query("SELECT * FROM featured_comics WHERE approved='1' AND ymd_date_live<='".$START_DATE."' ".$extra." ORDER BY ymd_date_live DESC LIMIT 5");
          while($row = db_fetch_object($res))
          {
            $FEATURED_ARR[(int)$row->comic_id] = $row;
          }

          $COMIC_DATA = array();
          $res = db_query("SELECT * FROM comics WHERE comic_id IN ('".implode("','", array_keys($FEATURED_ARR))."')");
          while($row = db_fetch_object($res) )
          {
            $res2 = db_query("SELECT page_id FROM comic_pages WHERE comic_id='".$row->comic_id."' ORDER BY order_id ASC LIMIT 1");
            $row2 = db_fetch_object($res2);
            db_free_result($res2);

            $row->last_page_id = $row2->page_id;

            $COMIC_DATA[$row->comic_id] = $row;
          }
          db_free_result($res);

          foreach( $FEATURED_ARR as $id=>$row )
          {
            $url         = 'http://'.DOMAIN.'/'.comicNameToFolder($COMIC_DATA[$id]->comic_name.'/');

            $description = htmlentities( $row->description, ENT_QUOTES );
            $description = nl2br($description);
            $description = str_replace("\n", '',  $description);
            $description = str_replace("\r", '',    $description);

            ?>
            <div class="thumblist_large_thumb">
              <a href="<?=$url?>"><img src="<?=IMAGE_HOST?>/featured_comic_gfx/<?=$row->feature_id?>.gif" width="130" height="200" border="0" style="border:1px solid #bbb;" onMouseOver="LinkTip.show('<?=$id?>', this);" onMouseOut="LinkTip.hide();"></a><br>
              <?=$COMIC_DATA[$id]->comic_name?>
            </div>
            <?
          }
          ?>
        </div>
      </div> 

        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr>
            <td width="160" align="center" valign="top">
              <? 
			  if ($TITLE == 'Main Page') {?>
				  <!-- AD TAG BEGINS: DrunkDuck(gr.drunkduck) / homepage_atf / 160x600 -->
<script type="text/javascript">
	var gr_ads_zone = 'homepage_atf';
	var gr_ads_size = '160x600';
</script>
<script type="text/javascript" src="http://a.giantrealm.com/gr.drunkduck/a.js">
</script>
<noscript>
	<a href="http://ans.giantrealm.com/click/gr.drunkduck/homepage_atf;tile=3;sz=160x600;ord=1234567890">
		<img src="http://ans.giantrealm.com/img/gr.drunkduck/homepage_atf;tile=3;sz=160x600;ord=1234567890" width="160" height="600" alt="advertisement" />
	</a>
</noscript>
<!-- AD TAG ENDS: DrunkDuck / homepage_atf / 160x600 -->
<?
			  } else {?>
              <!-- AD TAG BEGINS: DrunkDuck(gr.drunkduck) / ros_atf / 160x600 -->
<script type="text/javascript">
	var gr_ads_zone = 'ros_atf';
	var gr_ads_size = '160x600';
</script>
<script type="text/javascript" src="http://a.giantrealm.com/gr.drunkduck/a.js">
</script>
<noscript>
	<a href="http://ans.giantrealm.com/click/gr.drunkduck/ros_atf;tile=3;sz=160x600;ord=1234567890">
		<img src="http://ans.giantrealm.com/img/gr.drunkduck/ros_atf;tile=3;sz=160x600;ord=1234567890" width="160" height="600" alt="advertisement" />
	</a>
</noscript>
<!-- AD TAG ENDS: DrunkDuck / ros_atf / 160x600 -->
			 	
			  <? // include(WWW_ROOT.'/ads/ad_includes/main_template/160x600.html'); 
               }?>
            </td>
            <td width="724" align="center" valign="top">
              <?
              $extra = '';
              if ( isset($GLOBALS['SUBDOM_TO_CAT'][SUBDOMAIN]) ) {
                $extra = "AND search_category='".$GLOBALS['SUBDOM_TO_CAT'][SUBDOMAIN]."'";
              }
              if ( isset($GLOBALS['SUBDOM_TO_STYLE'][SUBDOMAIN]) ) {
                $extra .= "AND search_style='".$GLOBALS['SUBDOM_TO_STYLE'][SUBDOMAIN]."'";
              }
              ?>

              <div class="rack_header">
                Quick Lists
              </div>

              <div class="rack" id="rack_10spot" style="border-right:1px dashed #bbb;">
                <? include(WWW_ROOT.'/xmlhttp/main_page/racks/fetch_rack_10spot.php'); ?>
              </div>

              <div class="rack" id="rack_new" style="border-right:1px dashed #bbb;">
                <? include(WWW_ROOT.'/xmlhttp/main_page/racks/fetch_rack_new.php'); ?>
              </div>

              <div class="rack" id="rack_random">
                <? include(WWW_ROOT.'/xmlhttp/main_page/racks/fetch_rack_random.php'); ?>
              </div>
            </td>
          </tr>
        </table>

      <div class="vert_spacer">&nbsp;</div>

      <!--
      <div>
        <div class="rack_header" style="width:724px;padding-left:0px;">
          The Duck Recommends:
        </div>

        <div style="margin-left:20px;width:700px;height:110px;">
          <div class="rack_thumb"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tmp_thumb_small.gif"></div>
          <div class="rack_thumb"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tmp_thumb_small.gif"></div>
          <div class="rack_thumb"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tmp_thumb_small.gif"></div>
          <div class="rack_thumb"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tmp_thumb_small.gif"></div>
          <div class="rack_thumb"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tmp_thumb_small.gif"></div>
          <div class="rack_thumb"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tmp_thumb_small.gif"></div>
          <div class="rack_thumb"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tmp_thumb_small.gif"></div>
          <div class="rack_thumb"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tmp_thumb_small.gif"></div>
        </div>

      </div>
      -->

