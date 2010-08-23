<?
$CID = ( isset($_GET['cid'])?(int)$_GET['cid']:(int)$_POST['cid'] );
if ( !$CID ) die;


if ( $USER->flags & FLAG_IS_ADMIN ) {
  $res = db_query("SELECT * FROM comics WHERE comic_id='".$CID."'");
}
else {
  $res = db_query("SELECT * FROM comics WHERE (user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."') AND comic_id='".$CID."'");
}


if ( db_num_rows($res) < 1 ) {
  db_free_result($res);
  die;
}
$COMIC_ROW = db_fetch_object($res);
db_free_result($res);


// Just in case... sync page count
$res = db_query("SELECT COUNT(*) as total_pages FROM comic_pages WHERE comic_id='".$COMIC_ROW->comic_id."'");
$row = db_fetch_object($res);
db_free_result($res);
$COMIC_ROW->total_pages = $row->total_pages;
db_query("UPDATE comics SET total_pages='".$COMIC_ROW->total_pages."' WHERE comic_id='".$COMIC_ROW->comic_id."'");

?>
<link href="../acctStyles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
  function updateCharCt(field, updateDiv, limit)
  {
    if ( field.value.length >= 255 ) {
      field.value = field.value.substring(0, 255);
      alert('You have reached the limit of the description size you are allowed.');
    }
    var div = document.getElementById(updateDiv);
    div.innerHTML = (limit-field.value.length)+" characters left.";
  }

  function make18() {
    ajaxCall('/xmlhttp/toggle_age.php?cid=<?=$CID?>&make18=1', updateInfo);
  }
  function makeUnder18() {
    ajaxCall('/xmlhttp/toggle_age.php?cid=<?=$CID?>', updateInfo);
  }
  function updateInfo(nfo) {
    document.getElementById('ageGroup').innerHTML = nfo;
  }
</script>

<h1 align="left">Comic: <?=$COMIC_ROW->comic_name?></h1>

<div id="editnav">
  <ul>
    <li><a href="http://<?=DOMAIN?>/account/comic/?cid=<?=$COMIC_ROW->comic_id?>" title="View and edit your comic's description, creatis and assistant"><strong>1. Comic Overview</strong></a></li>
    <li><a href="http://<?=DOMAIN?>/account/comic/manage_pages.php?cid=<?=$COMIC_ROW->comic_id?>" title="View your comic's pages, set the order, upload new pages"><strong>2. Comic Pages</strong></a></li>
    <li><a href="http://<?=DOMAIN?>/account/comic/edit_comic_homepage.php?cid=<?=$COMIC_ROW->comic_id?>" title="View and edit your comic's home page content, make a blog post"><strong>3. Homepage</strong></a></li>
    <li><a href="http://<?=DOMAIN?>/account/comic/comic_design.php?cid=<?=$COMIC_ROW->comic_id?>" title="View and edit the design of your comic's pages"><strong>4. Layout</strong></a></li>
    <li><a href="http://<?=DOMAIN?>/account/comic/manage_forum.php?cid=<?=$COMIC_ROW->comic_id?>" title="View and edit your forum/blog">Forum/Blog</a></li>
    <!-- <li><a href="http://<?=DOMAIN?>/account/comic/support_pages.php?cid=<?=$COMIC_ROW->comic_id?>" title="View and edit supplemental pages, like fan art pages and character pages">More Content</a></li> -->
    <li><a href="http://<?=DOMAIN?>/account/comic/upload_files.php?cid=<?=$COMIC_ROW->comic_id?>" title="View and manage your gallery images and extra files you've uploaded and upload more">Files</a></li>
    <li><a href="http://<?=DOMAIN?>/account/comic/comic_stats.php?cid=<?=$COMIC_ROW->comic_id?>" title="Check out your comic's pages views and other statistics">Stats</a></li>
    <!-- <li><a href="comic_promote.php" title="Info and helpful links to help get the word out about your comic!">Promote!</a></li> -->
  </ul>
</div>
<div class="pagecontent">