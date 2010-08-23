<?
if ( ($CONTENT_FILE != 'community/forum_denied.inc.php') && ($USER->flags & FLAG_FORUM_BAN) )
{
  header("Location: http://".DOMAIN."/community/forum_denied.php");
  die;
}


include('community_functions.inc.php');
include('community_data.inc.php');


/*
  Passable Vars:
  pid      = post id
  cid      = category id
  tid      = topic id
  comic_id = comic id
*/

$PASSABLES = array();
$PASSABLES['pid']      = (int)( isset($_GET['pid'])      ? $_GET['pid']      : $_POST['pid'] );
if ( $PASSABLES['pid'] == 0 ) unset( $PASSABLES['pid'] );

$PASSABLES['cid']      = (int)( isset($_GET['cid'])      ? $_GET['cid']      : $_POST['cid'] );
if ( $PASSABLES['cid'] == 0 ) unset( $PASSABLES['cid'] );

$PASSABLES['tid']      = (int)( isset($_GET['tid'])      ? $_GET['tid']      : $_POST['tid'] );
if ( $PASSABLES['tid'] == 0 ) unset( $PASSABLES['tid'] );

$PASSABLES['comic_id'] = (int)( isset($_GET['comic_id']) ? $_GET['comic_id'] : $_POST['comic_id'] );
if ( $PASSABLES['comic_id'] == 0 ) unset( $PASSABLES['comic_id'] );

if ( $PASSABLES['comic_id'] == 15069 ) {
  ?><img src="http://www.drunkduck.com/track_bug.php" width="1" height="1"><?
}



if ( isset($_GET['comic_id']) ) {
  $CID = (int)$_GET['comic_id'];
}
else {
  $CID = (int)$_POST['comic_id'];
}

if ( $CID )
{
  $res = db_query("SELECT * FROM comics WHERE comic_id='".$CID."'");
  if ( $COMIC_ROW = db_fetch_object($res) ) {
    db_query("INSERT INTO unique_comic_tracking (comic_id, ymd_date, page_id, ip) VALUES ('".$COMIC_ROW->comic_id."', '".YMD."', '0', '".$_SERVER['REMOTE_ADDR']."')");
    track_comic_view($COMIC_ROW->comic_id);
  }
  db_free_result($res);
}





/* Session Data */
$COMMUNITY_SESSION_CKSUM = $COMMUNITY_SESSION = null;
if ( $USER )
{
  $res = db_query("SELECT encoded_data FROM community_sessions WHERE user_id='".$USER->user_id."'");
  if ( $row = db_fetch_object($res) )
  {
    $COMMUNITY_SESSION_CKSUM  = $row->encoded_data;
    $COMMUNITY_SESSION        = unserialize(gzuncompress($COMMUNITY_SESSION_CKSUM));
  }
  db_free_result($res);
}
if( !is_a($COMMUNITY_SESSION, 'CommunitySessionEX') ) {
  $COMMUNITY_SESSION = new CommunitySessionEX();
}

$COMMUNITY_SESSION->cleanData();











?>
<link rel="stylesheet" type="text/css" href="community_styles2.css">

<?
if ( $COMIC_ROW ) {
  ?><div align="left" class="header_title">Forums for <?=$COMIC_ROW->comic_name?></div><?
}
else {
  ?><div align="left" class="header_title">Forums</div><?
}
?>
<!--TOP LEVEL STYLE-->

<div class="pagecontent">
