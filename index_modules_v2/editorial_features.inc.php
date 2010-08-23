<?
/*
<style type="text/css">
<!--
.topstory {
	margin: 0px;
	padding: 10px;
	height: 220px;
	width: 428px;
	font-family: Arial, Helvetica, sans-serif;
	background:url(<?=IMAGE_HOST?>/news/feature_bg.gif) #600;
	color:#FFFFFF;
}

.topstory img {
	float:left;
	margin: 0 5px 0 0;
}
.topstory h2 {
	font-family: Arial, Helvetica, sans-serif;
	margin:0;
	padding:0;
	font-weight:normal;
	font-size:20px;
}

.topstory p {
	text-align:left;
	font-size: 12px;
	margin:4px 0 0 0;
	line-height: 1.4em;
}

a:link {color:#FFF; text-decoration:none;}
a:active {color:#0FF; text-decoration:none;}
a:hover {color:#CCC; text-decoration:none;}
a:visited {color:#FFF; text-decoration:none;}

.topstory h2 a:link {color:#FFF; text-decoration:none;}
.topstory h2 a:active {color:#0FF; text-decoration:none;}
.topstory h2 a:hover {color:#CCC; text-decoration:none;}
.topstory h2 a:visited {color:#FFF; text-decoration:none;}

-->
</style>
*/


$swf = new FlashMovie(IMAGE_HOST.'/news/feature_bar_v3.swf', 448, 20);
$swf->showHTML();
?>
<div style="width:448;height:230px;border:1px solid black;" id="featured_div"></div>
<script language="JavaScript">

var rotate = new Array();

<?
  $extra = '';
  if ( isset($GLOBALS['SUBDOM_TO_CAT'][SUBDOMAIN]) ) {
    $extra = "AND category='".$GLOBALS['SUBDOM_TO_CAT'][SUBDOMAIN]."'";
  }

  $res = db_query("SELECT feature_id, ymd_date_live FROM featured_comics WHERE ymd_date_live<='".date("Ymd")."' AND approved='1' ".$extra." ORDER BY ymd_date_live DESC LIMIT 1");
  $row = db_fetch_object($res);
  db_free_result($res);

  $START_DATE = $row->ymd_date_live;
  $START_ID = $row->feature_id;


  if ( date("w") == 1 || date("w") == 3 || date("w") == 5 )
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
  $res = db_query("SELECT * FROM featured_comics WHERE approved='1' AND ymd_date_live<='".$START_DATE."' ORDER BY ymd_date_live DESC LIMIT 5");
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

  foreach($FEATURED_ARR as $id=>$row)
  {
    $url         = 'http://'.DOMAIN.'/'.comicNameToFolder($COMIC_DATA[$id]->comic_name.'/');

    $description = htmlentities( $row->description, ENT_QUOTES );
    $description = nl2br($description);
    $description = str_replace("\n", '',  $description);
    $description = str_replace("\r", '',    $description);

    ?>
    rotate.push('<div class="topstory">'+
                '<a href="<?=$url?>"><img src="<?=IMAGE_HOST?>/featured_comic_gfx/<?=$row->feature_id?>.gif" width="160" height="220" border="0" /></a>'+
                '<h2><a href="<?=$url?>"><?=$COMIC_DATA[$id]->comic_name?></a> </h2>'+
                '<p><?=$description?></p>'+
                '<p>Read it now: <a href="<?=$url?>?p=<?=$COMIC_DATA[$id]->last_page_id?>">first page</a> | <a href="<?=$url?>">latest page</a></p>'+
                '</div>');
    <?
  }
?>

var featureNow;

function rotateFeatured(num)
{
  featureNow = Number(num)-1;
  $('featured_div').innerHTML = rotate[featureNow];
}

rotateFeatured(1);
</script>