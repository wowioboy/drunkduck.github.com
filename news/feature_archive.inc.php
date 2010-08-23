<?
$dateSelection = array();
for($i=2006; $i<=date("Y"); $i++) {
  if ( $i == $_GET['year'] ) {
    $dateSelections[] = $i;
  }
  else {
    $dateSelections[] = '<a href="'.$_SERVER['PHP_SELF'].'?year='.$i.'">'.$i.'</a>';
  }
}
?>
<link href="news.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>

<div id="newspage" style="color:black;text-align:left;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="100%" colspan="2" valign="top">
        <h1 class="style1">News</h1>
		    <div id="contentpage">
		      <h2>Featured Comic Archive</h2>
		      <p class="byline"><?=implode(" | ", $dateSelections)?></p>



          <?
            if( isset($_GET['year']) ) {
              $start = (int)( $_GET['year'].'0101' );
              $end   = (int)( $_GET['year'].'1231' );
            }
            else {
               $start = 0;
               $end   = 0;
            }

            $FEATURED_ARR = array();
            $res = db_query("SELECT * FROM featured_comics WHERE approved='1' AND ymd_date_live>='".$start."' AND ymd_date_live<='".$end."' ORDER BY ymd_date_live DESC");
            while($row = db_fetch_object($res))
            {
              $FEATURED_ARR[(int)$row->comic_id] = $row;
            }

            $COMIC_DATA = array();
            $res = db_query("SELECT * FROM comics WHERE comic_id IN ('".implode("','", array_keys($FEATURED_ARR))."')");
            while($row = db_fetch_object($res) )
            {
              $USERS[$row->user_id] = $row->user_id;

              $res2 = db_query("SELECT page_id FROM comic_pages WHERE comic_id='".$row->comic_id."' ORDER BY order_id ASC LIMIT 1");
              $row2 = db_fetch_object($res2);
              db_free_result($res2);

              $row->first_page_id = $row2->page_id;

              $COMIC_DATA[$row->comic_id] = $row;
            }
            db_free_result($res);

            $res = db_query("SELECT user_id, username FROM users WHERE user_id IN ('".implode("','", $USERS)."')");
            while($row = db_fetch_object($res)) {
              $USERS[$row->user_id] = $row->username;
            }

            foreach($FEATURED_ARR as $id=>$row)
            {
              $url         = 'http://'.DOMAIN.'/'.comicNameToFolder($COMIC_DATA[$id]->comic_name.'/');

              $description = htmlentities( $row->description, ENT_QUOTES );
              $description = nl2br($description);
              $description = str_replace("\n", '',  $description);
              $description = str_replace("\r", '',    $description);


              list($fyear, $fmo, $fday) = sscanf($row->ymd_date_live, "%4d%2d%2d");
              ?>

              <table class="feature">
                <tr>
                  <td>
                    <h4><a href="<?=$url?>"><img src="<?=IMAGE_HOST?>/featured_comic_gfx/<?=$row->feature_id?>.gif" width="80" height="110" border="2" align="left" class="feature_thumb" /><?=$COMIC_DATA[$id]->comic_name?></a></h4>
                    <p>By: <?=username( $USERS[$COMIC_DATA[$id]->user_id] )?> |
                    <img src="<?=IMAGE_HOST?>/site_gfx_new/genre_icons/<?=$COMIC_DATA[$id]->category?>.gif" alt="Genre: <?=$COMIC_CATS[$COMIC_DATA[$id]->category]?>" title="Genre: <?=$COMIC_CATS[$COMIC_DATA[$id]->category]?>" width="12" height="12" /><?=$COMIC_CATS[$COMIC_DATA[$id]->category]?> | <?=$COMIC_STYLES[$COMIC_DATA[$id]->comic_type]?> | <?=number_format($COMIC_DATA[$id]->total_pages)?> pages | Last Update: <?=date("m/d/Y", $COMIC_DATA[$id]->last_update)?> | Featured: <?=$fmo?>/<?=$fday?>/<?=$fyear?></p>
                    <p><?=nl2br($row->description)?></p>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p class="feature_nav"><img src="<?=IMAGE_HOST?>/site_gfx_new_v2/interview_images/readit.gif" alt="read it" width="54" height="16" /> <a href="<?=$url?>?p=<?=$COMIC_DATA[$id]->first_page_id?>"><img src="<?=IMAGE_HOST?>/site_gfx_new_v2/interview_images/first.gif" alt="first" width="44" height="16" border="0" /></a> <a href="<?=$url?>"><img src="<?=IMAGE_HOST?>/site_gfx_new_v2/interview_images/last.gif" alt="last" width="52" height="16" border="0" /></a></p>
                  </td>
                </tr>
              </table>
              <?
            }
          ?>



		      <p>&nbsp;</p>
		      <p>&nbsp;</p>
		      <p>&nbsp;</p>
		      <p>&nbsp;</p>
		      <p>&nbsp;</p>
		      <p>&nbsp;</p>
		    </div>
	    </td>
    </tr>
  </table>
</div>