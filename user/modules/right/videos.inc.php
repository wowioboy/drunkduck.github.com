<?
$DISPLAY_LIMIT = 10;

$res = db_query("SELECT COUNT(*) as total_movies FROM grabbed_movies WHERE user_id='".$viewRow->user_id."'");
$row = db_fetch_object($res);
db_free_result($res);

$TOTAL_MOVIES = $row->total_movies;

$USER_MOVIES  = array();
$MOVIE_ROWS   = array();

if ( $TOTAL_MOVIES > 0 )
{
  $res = db_query("SELECT * FROM grabbed_movies WHERE user_id='".$viewRow->user_id."' ORDER BY order_id ASC LIMIT $DISPLAY_LIMIT");
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

?>
<div style="height:24px;background:url(<?=$IMAGE_FOLDER?>/sub_title_hdr.gif);color:#ffffff;font-weight:bold;" align="left">
  <div style="padding-left:5px;padding-top:4px;">Videos shared by <?=$viewRow->username?></div>
</div>

<div style="background:#ffffff;margin-bottom:5px;" align="left" id="video_list">
  <?
  $_videoShowing = null;
  $ct = 0;
  foreach( $USER_MOVIES as $id=>$gRow )
  {
    if ( $_videoShowing == null ) {
      $_videoShowing = 'movie_'.$id;
    }
    ?>
    <div align="left" id="movie_<?=$id?>_preview" style="padding:5px;width:95%;">
      <font style="font-weight:bold;font-size:16px;"><a href="#" onClick="toggleBlind( 'movie_<?=$id?>' );return false;"><?=pickNonEmpty( $USER_MOVIES[$id]->title, $MOVIE_ROWS[$id]->title, 'Video '.$id)?></a></font>
      <br>
      <?=nl2br( pickNonEmpty( $USER_MOVIES[$id]->description, $MOVIE_ROWS[$id]->description, 'No Description') )?>
    </div>

    <div align="center" <?=( ($ct!=0) ? 'style="display:none;"' : '' )?> id="movie_<?=$id?>_view">
      <?
      $sz = scaleVideo( array($MOVIE_ROWS[$id]->width, $MOVIE_ROWS[$id]->height), array(400, 260) );
      ?>
      <embed src="<?=$MOVIE_ROWS[$id]->url?>" width="<?=$sz['width']?>" height="<?=$sz['height']?>" type="<?=$MOVIE_ROWS[$id]->movie_type?>" allowscriptaccess="never" allownetworking="internal" enablejsurl="false" enablehref="false" saveembedtags="true"> </embed>
    </div>
    <?
    ++$ct;
  }


  if ( $USER->user_id == $viewRow->user_id )
  {
    ?>
    <div align="right" id="submit_video_link">
      <a href="#" onClick="new Effect.BlindDown('submit_video');new Effect.BlindUp('submit_video_link');return false;">Add a video</a>
    </div>
    <div id="submit_video" style="width:90%;padding-left:10px;display:none;">
      <p>&nbsp;</p>
      <form id="new_video" action="#" method="POST" onSubmit="serializeAndSubmit(this);return false;">
        Title:<br>
        <input type="text" name="submitTitle" style="width:99%;"><br>
        Url/Embed:<br>
        <textarea name="submitBody" style="width:99%;height:100px;" wrap="virtual"></textarea>
        Description: (optional)<br>
        <textarea name="submitDescription" style="width:99%;height:50px;" wrap="virtual"></textarea><br>
        <div align="center"><input type="submit" value="Save"></div>
      </form>
      <p>&nbsp;</p>
    </div>
    <?
  }
  ?>
  <div align="right"><a href="http://<?=USER_DOMAIN?>/see_all_videos.php?u=<?=$viewRow->username?>">See all of <?=$viewRow->username?>'s videos</a></div>
</div>

  <script type="text/javascript">
    var formRef = null;
    function serializeAndSubmit( frm )
    {
      formRef = frm;
      var saveFrm = new Ajax.Request( '/xmlhttp/submit_video.php', { method: 'post', parameters: Form.serialize(frm), onComplete: onSubmittedVideo} );
      Form.disable(frm);
    }

    function onSubmittedVideo(originalReq) {
      Form.enable(formRef);
      eval(originalReq.responseText);
      new Effect.BlindDown('submit_video_link');
      new Effect.BlindUp('submit_video');
    }

    var _videoShowing = '<?=strtolower( (string)$_videoShowing )?>';
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
  </script>