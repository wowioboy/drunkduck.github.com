<div class="newspost">
  <img src="<?=IMAGE_HOST?>/site_gfx_new/DD_newsbanner.png" width="280" height="25" />
<?
  $res      = db_query("SELECT ymd_date FROM admin_blog WHERE ymd_date<='".YMD."' ORDER BY ymd_date DESC LIMIT 1");
  $row      = db_fetch_object($res);
  $BLOG_YMD = $row->ymd_date;
  db_free_result($res);
  
  $USERS = array();
  $POSTS = array();
  
  $res = db_query("SELECT * FROM admin_blog WHERE ymd_date='".$BLOG_YMD."' ORDER BY timestamp_date DESC");
  while($row = db_fetch_object($res))
  {
    $POSTS[]                   = $row;
    $USERS[$row->user_id]      = $row->user_id;
    if ( $row->edit_user_id > 0 ) {
      $USERS[$row->edit_user_id] = $row->edit_user_id;
    }
  }
  db_free_result($res);
  
  
  $res = db_query("SELECT user_id, username, avatar_ext FROM users WHERE user_id IN ('".implode("','", $USERS)."')");
  while($row = db_fetch_object($res)) {
    $USERS[$row->user_id] = $row;
  }
  db_free_result($res);
  
  foreach($POSTS as $post)
  {
    ?><p><?
    $U = &$USERS[$post->user_id];
      if ( $U->avatar_ext )
      {
        if ( $U->avatar_ext == 'swf' ) {
          
          $INFO = getimagesize(WWW_ROOT.'/gfx/avatars/avatar_'.$U->user_id.'.swf');
          echo get_flash_movie(IMAGE_HOST.'/avatars/avatar_'.$U->user_id.'.swf', $INFO[0], $INFO[1]);
        }
        else { 
          echo "<IMG SRC='".IMAGE_HOST."/avatars/avatar_".$U->user_id.".".$U->avatar_ext."' ALIGN='LEFT'>";
        }
      }
      else {
        echo "<IMG SRC='".IMAGE_HOST."/site_gfx_new/anonymous.jpg' ALIGN='LEFT'>";
      }
      ?>
        <B><?=BBCode($post->title)?></B>
        <BR>
        <?=strtoupper(date("F d, Y", $post->timestamp_date))?> - <?=date("g:ia", $post->timestamp_date)?>
        <BR>
        <BR>
        <?=BBCode(nl2br($post->body))?>
        <BR><BR>
        <A HREF='http://www.drunkduck.com/forum/viewforum.php?f=42'>Discuss this news post in the forum.</A>
        <BR><BR>
        <B>
          <i>This message was posted by <?=(($U->user_id==1)?"The Administrator of DrunkDuck.com":username($U->username))?></i>
        </B>
          <?
          if ( $post->edit_timestamp )
          {
            ?>
            <br>
            <i>...and edited by <?=(($USERS[$post->edit_user_id]->user_id==1)?"The Administrator of DrunkDuck.com":username($USERS[$post->edit_user_id]->username))?> on <?=strtoupper(date("F d, Y", $post->edit_timestamp))?> - <?=date("g:ia", $post->edit_timestamp)?></i>
            <?
          }
    ?></p><?
  }
  ?>
  <hr />
    <A HREF='http://<?=DOMAIN?>/news.php' style='font-size:14px;'>&laquo; Read Past News &laquo;</A>
    
    <?
      // Get the comics in need of comments
      include_once(WWW_ROOT.'/index_modules/comments_needed.inc.php');
      ?>
</div>