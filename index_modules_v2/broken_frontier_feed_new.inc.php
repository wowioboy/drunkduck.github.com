<?
/* get latest headlines */
$latest_headlines_qry = db_query("SELECT id, title, intro, DATE_FORMAT(date, '%M %d, %Y') AS sdate FROM bf_headlines WHERE date <= NOW() AND department='1' ORDER BY date DESC, id DESC LIMIT 5");

/* set latest_reviews */
$latest_headlines = '';

?>
<style type="text/css">
.newsfeed_bfnews {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 9px;
	padding: 0px;
	border: 0px none #000000;
	color: #333333;
	margin-top: 2px;
	margin-right: 6px;
	margin-bottom: 5px;
	margin-left: 6px;
  }

 .newsfeed_bfnews h1 {
	padding: 0px;
	margin-bottom: 5px;
	font-size: 11px;
	margin-top: 0px;
  background: none;
  border: 0px;
	font-family:Arial, Helvetica, sans-serif;
	font-weight: bold;
  }

 .newsfeedstory_bfnews {
	padding: 4px;
	background-image: url(/zachp/v2/comp_images/bg_grad.png);
	background-repeat: repeat-x;
	background-color: #000000;
	border-bottom-width: 1px;
	border-bottom-style: dashed;
	border-bottom-color: #E58300;
	color: #FFFFFF;
	text-align: left;
	border-right-width: 1px;
	border-left-width: 1px;
	border-right-style: dashed;
	border-left-style: dashed;
	border-top-color: #E58300;
	border-right-color: #E58300;
	border-left-color: #E58300;
  }

 .newsfeedstory_bfnews img {
	margin-left: 5px;
  }

 .newsfeedstory_bfnews a:link {
	color: #CCCCCC;
	text-decoration: none;
  }

 .newsfeedstory_bfnews a:visited {
	color: #CCCCCC;
	text-decoration: none;
  }

 .newsfeedstory_bfnews a:hover {
	color: #FFFF00;
	text-decoration: underline;
  }

 .newsfeedstory_bfnews a:active {
	color: #000000;
	text-decoration: underline;
	background-color: #FFFFFF;
  }

  </style>

<table style="width:200px;" border="0" cellpadding="0" cellspacing="0" class="newsfeed_bfnews">
<tr>
  <td bgcolor="black">
    <img src="<?=IMAGE_HOST_SITE_GFX?>/sect_feed.gif" width="212" height="30" />
    <img src="<?=IMAGE_HOST_SITE_GFX?>/providedbf.gif" />
  </td>
</tr>

<?
/* fetch latest reviews */
while($latest_headlines_obj = mysql_fetch_object($latest_headlines_qry))
{
	// location
	$location = "/var/www/html/brokenfrontier.com/images/headlines/";
	$new_location = WWW_ROOT.'/gfx/bf_feed_images/';
  $http_location = "http://images2.drunkduck.com/bf_feed_images/";
	// check for image

	if(file_exists($location . $latest_headlines_obj->id . ".gif"))
	{
	  copy( $location . $latest_headlines_obj->id . ".gif",
	        $new_location . $latest_headlines_obj->id . ".gif");

	  $image = $http_location . $latest_headlines_obj->id .".gif";
	}
	else if(file_exists($location . $latest_headlines_obj->id . ".jpg"))
	{
	  copy( $location . $latest_headlines_obj->id . ".jpg",
	        $new_location . $latest_headlines_obj->id . ".jpg");

	  $image = $http_location . $latest_headlines_obj->id .".jpg";
	}

	if(!isset($image)) {
	  $image = '';
	}

	?>
  <tr>
    <td class="newsfeedstory_bfnews">
      <h1><img src="<?=$image?>" width="30" height="41" align="right" /><a href="http://www.brokenfrontier.com/headlines/details.php?id=<?=$latest_headlines_obj->id?>" target="_blank"><?=htmlentities(stripslashes($latest_headlines_obj->title), ENT_QUOTES)?></a></h1>
      <p>
        <?=htmlentities(stripslashes($latest_headlines_obj->intro), ENT_QUOTES)?><br>
        <span class="style1">Posted on <?=$latest_headlines_obj->sdate?></span>
      </p>
    </td>
  </tr>
	<?
	// unset image
	unset($image);
}
?>
</table>