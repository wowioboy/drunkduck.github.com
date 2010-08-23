<script src="<?=HTTP_JAVASCRIPT?>/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<script type="text/javascript">

  var _videoShowing = null;
  function toggleBlind( movie_id )
  {
    if ( $(movie_id + '_view').style.display == 'none' )
    {
      if ( _videoShowing != null )
      {
        new Effect.BlindUp( _videoShowing + '_view', {duration:1} );
        _videoShowing = null;
      }
      new Effect.BlindDown( movie_id + '_view', {duration:1} );
      _videoShowing = movie_id;
    }
    else
    {
      new Effect.BlindUp( movie_id + '_view', {duration:1} );
      _videoShowing = null;
    }
  }


  function delVid(video_id) {
    var grabAjax = new Ajax.Request( '/xmlhttp/delete_video.php', { method: 'post', parameters: 'video_id='+video_id, onComplete: onDelVid} );
  }

  function onDelVid( originalReq ) {
    $('video_'+originalReq.responseText).style.display = 'none;';
  }


</script>

<style>
.content_body
{
  font: 11px Verdana, Arial, Helvetica, Geneva, sans-serif;
  color:#000000;
  background:#f8d689 url(http://images.drunkduck.com/site_gfx_new_v2/tan_page_bg.png) repeat-x 0 top;
  height:100%;
}

.content_body a {
  color: #00305b;
  font-weight:bold;
}
.content_body a:hover {
  color: #0067cc;
}
.content_body a:visited {
  color: #00305b;
}

.content_body img {
  border: 1px solid black;
}
</style>
<div class="content_body">
  <div style="background:url(<?=IMAGE_HOST?>/site_gfx_new_v2/page_title_bg.gif);height:40px;" align="left">
    <img src="<?=IMAGE_HOST?>/profile_gfx/profile_hdr.gif" style="margin-left:5px;margin-top:10px;float:left;border:0px;">
  </div>
<?
  if ( !$_GET['u'] ) return;

  $U = db_escape_string($_GET['u']);

  $res = db_query("SELECT * FROM users WHERE username='".$U."'");
  if ( !$viewRow = db_fetch_object($res) ) return;
  db_free_result($res);

  ?>
  <div align="left" style="margin:5px;"><a href="http://<?=USER_DOMAIN?>/<?=$viewRow->username?>">.: Back to <?=$viewRow->username?>'s profile</a></div>
  <?

  if ( isset($_GET['top']) )
  {
    db_query("UPDATE grabbed_movies SET order_id='0' WHERE user_id='".$USER->user_id."' AND movie_id='".(int)$_GET['top']."'");
    if ( db_rows_affected() > 0 ) {
      db_query("UPDATE grabbed_movies SET order_id=order_id+1 WHERE user_id='".$USER->user_id."'");
    }
  }

$res = db_query("SELECT COUNT(*) as total_movies FROM grabbed_movies WHERE user_id='".$viewRow->user_id."'");
$row = db_fetch_object($res);
db_free_result($res);

$TOTAL_MOVIES = $row->total_movies;

$USER_MOVIES  = array();
$MOVIE_ROWS   = array();

if ( $TOTAL_MOVIES > 0 )
{
  $res = db_query("SELECT * FROM grabbed_movies WHERE user_id='".$viewRow->user_id."' ORDER BY order_id ASC");
  while($row = db_fetch_object($res)) {
    $USER_MOVIES[$row->movie_id] = $row;
  }
  db_free_result($res);


  $res = db_query("SELECT * FROM pool_movies WHERE id IN ('".implode("','", array_keys($USER_MOVIES))."')");
  while( $row = db_fetch_object($res) ) {
    $MOVIE_ROWS[$row->id] = $row;
  }


  include(WWW_ROOT.'/includes/video_package/video_func.inc.php');


  if ( count($MOVIE_ROWS) != count($USER_MOVIES) )
  {

    foreach( $USER_MOVIES as $id=>$row )
    {

      if ( !isset($MOVIE_ROWS[$id]) ) {
        ungrabVideo($viewRow->user_id, $id);
      }

    }

  }
}
    foreach( $USER_MOVIES as $id=>$gRow )
    {
      ?>
      <div style="background:#ffffff;border-bottom:1px solid black;" align="left" id="video_<?=$id?>">
        <div align="left" id="movie_<?=$id?>_preview" style="padding:5px;">
          <?=( ($USER->user_id == $viewRow->user_id) ? '<p align="right"><a href="'.$_SERVER['PHP_SELF'].'?u='.$USER->username.'&top='.$id.'"><img src="'.IMAGE_HOST.'/profile_gfx/up.png" style="border:0px;" title="Move to top." alt="Move to top."></a><a href="#" onClick="if ( confirm(\'Are you SURE you want to delete this video?\') ) { delVid('.$id.'); return false; }"><img src="'.IMAGE_HOST.'/profile_gfx/delete.png" style="border:0px;"></a></p>' : '' )?>
          <font style="font-weight:bold;font-size:16px;"><a href="#" onClick="toggleBlind( 'movie_<?=$id?>' );return false;"><?=pickNonEmpty( $USER_MOVIES[$id]->title, $MOVIE_ROWS[$id]->title, 'Video '.$id)?></a></font>
          <br>
          <?=nl2br( pickNonEmpty( $USER_MOVIES[$id]->description, $MOVIE_ROWS[$id]->description, 'No Description') )?>
        </div>

        <div align="center" style="display:none;" id="movie_<?=$id?>_view">
          <?
          $sz = scaleVideo( array($MOVIE_ROWS[$id]->width, $MOVIE_ROWS[$id]->height), array(600, 500) );
          ?>
          <embed src="<?=$MOVIE_ROWS[$id]->url?>" width="<?=$sz['width']?>" height="<?=$sz['height']?>" type="<?=$MOVIE_ROWS[$id]->movie_type?>" allowscriptaccess="never" allownetworking="internal" enablejsurl="false" enablehref="false" saveembedtags="true"> </embed>
        </div>
      </div>
      <?
    }
    ?>

  <?
  if ($USER->user_id == $viewRow->user_id ) {
    ?><div align="center"><b>Note:</b> Only the most recent 10 comments appear on your profile page.<i></i></div><?
  }
  ?>

  <p>&nbsp;</p>

</div>