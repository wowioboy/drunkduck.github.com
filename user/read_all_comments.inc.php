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

<script type="text/javascript">
  function delComment(comment_id) {
    var grabAjax = new Ajax.Request( '/xmlhttp/delete_comment.php', { method: 'post', parameters: 'comment_id='+comment_id, onComplete: onDelComment} );
  }

  function appComment(comment_id) {
    var grabAjax = new Ajax.Request( '/xmlhttp/approve_comment.php', { method: 'post', parameters: 'comment_id='+comment_id, onComplete: onDelComment} );
  }

  function onDelComment( originalReq ) {
    $('comment_'+originalReq.responseText).style.display = 'none;';
  }

</script>

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

<div style="background:#ffffff;margin-bottom:5px;" align="center">

  <?

  include(WWW_ROOT.'/community/community_data.inc.php');
  include(WWW_ROOT.'/community/community_functions.inc.php');

  $COMMENTS = array();
  $USERS    = array();
  $res = db_query("SELECT * FROM profile_comments WHERE user_id='".$viewRow->user_id."' AND approved='".( ($_GET['approve']) ? '0' : '1' )."' ORDER BY id DESC");
  while( $row = db_fetch_object($res) ) {
    $COMMENTS[] = $row;
    $USERS[$row->poster_id] = $row->poster_id;
  }
  db_free_result($res);

  $res = db_query("SELECT user_id, username, avatar_ext FROM users WHERE user_id IN ('".implode("','", $USERS)."')");
  while( $row = db_fetch_object($res) ) {
    $USERS[$row->user_id] = $row;
  }
  db_free_result($res);


  foreach( $COMMENTS as $row )
  {
    $u = &$USERS[$row->poster_id];
    ?>
    <div id="comment_<?=$row->id?>" style="padding:3px;">
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td rowspan="3" valign="top">
            <a href="http://<?=USER_DOMAIN?>/<?=$u->username?>"><img src="http://images.drunkduck.com/process/user_<?=$u->user_id?>.<?=$u->avatar_ext?>" width="50" style="margin:2px;"><br /><?=$u->username?></a>
          </td>

          <td width="18" height="5"><img src="<?=IMAGE_HOST?>/profile_gfx/comment_gfx/corner_tl.gif" style="border:0px;"></td>
          <td width="100%" height="5" align="left" background="<?=IMAGE_HOST?>/profile_gfx/comment_gfx/tile_top.gif" height="5" width="100%"><img src="<?=IMAGE_HOST?>/profile_gfx/comment_gfx/tile_top.gif" style="border:0px;"></td>
          <td width="5" height="5"><img src="<?=IMAGE_HOST?>/profile_gfx/comment_gfx/corner_tr.gif" style="border:0px;"></td>
        </tr>

        <tr>
          <td width="18" height="100%" valign="top" background="<?=IMAGE_HOST?>/profile_gfx/comment_gfx/tile_left.gif"><img src="<?=IMAGE_HOST?>/profile_gfx/comment_gfx/pointer.gif" style="border:0px;"></td>
          <td align="left" bgcolor="#dee7f7" height="60" width="100%">
            <div style="margin:10px;" align="left">
              <?=( ($USER->user_id == $viewRow->user_id) ? '<p align="right"><a href="#" onClick="if ( confirm(\'Are you SURE you want to delete this comment?\') ) { delComment('.$row->id.'); return false; }"><img src="'.IMAGE_HOST.'/profile_gfx/delete.png" style="border:0px;"></a></p>' : '' )?>
              <?=nl2br( community_bb_code($row->comment) )?>
              <?
              if ( $_GET['approve'] ) {
                ?><div align="right"><a href="#" onClick="appComment('<?=$row->id?>');return false;">Approve this Comment</a></div><?
              }
              ?>
            </div>
          </td>
          <td width="5" height="100%" background="<?=IMAGE_HOST?>/profile_gfx/comment_gfx/tile_right.gif"><img src="<?=IMAGE_HOST?>/profile_gfx/comment_gfx/tile_right.gif" style="border:0px;"></td>
        </tr>

        <tr>
          <td width="18" height="5"><img src="<?=IMAGE_HOST?>/profile_gfx/comment_gfx/corner_bl.gif" style="border:0px;"></td>
          <td width="100%" height="5" align="left" background="<?=IMAGE_HOST?>/profile_gfx/comment_gfx/tile_bottom.gif" height="5" width="100%"></td>
          <td width="5" height="5"><img src="<?=IMAGE_HOST?>/profile_gfx/comment_gfx/corner_br.gif" style="border:0px;"></td>
        </tr>
      </table>
    </div>
    <?
  }
  ?>

  <?
  if ( !$USER ) {
    ?><div align="center">Log in to leave a comment!</div><?
  }
  else if ( $USER->user_id == $viewRow->user_id ) {

  }
  else if ( !$GLOBALS['FRIEND_DATA']->you_are_their_friend )
  {
    ?><div align="center"><?=$viewRow->username?> must add you as a friend before you can leave a comment.!</div><?
  }
  else
  {
    ?>
    <div style="display:none;" id="comment_form">
      <form action="http://<?=USER_DOMAIN?>/<?=$viewRow->username?>" method="POST">
        <div align="left">
          <b>Leave a Comment:</b>
        </div>
        <textarea name="comment" style="width:98%;" rows="7"></textarea>
        <br>
        <input type="submit" value="Send!">
      </form>
    </div>

    <div align="right" id="comment_link">
      <a href="#" onClick="new Effect.BlindDown('comment_form');new Effect.BlindUp('comment_link');return false;">Add a comment</a>
    </div>
    <?
  }
  ?>

</div>

<p>&nbsp;</p>

</div>