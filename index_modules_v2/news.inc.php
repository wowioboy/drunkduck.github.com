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
?>

<img src="<?=IMAGE_HOST_SITE_GFX?>/sect_news.gif" width="320" height="30" />

<div class="newspost">
  <?
    $ct = 0;
    foreach($POSTS as $post)
    {
      $U = &$USERS[$post->user_id];
      ?>
        <h2><?=BBCode($post->title)?></h2>
        <p class="date"><?=strtoupper(date("F d, Y", $post->timestamp_date))?> - <?=date("g:ia", $post->timestamp_date)?></p>

        <a href="http://<?=USER_DOMAIN?>/<?=$U->username?>"><img src="<?=IMAGE_HOST?>/process/user_<?=$U->user_id?>.<?=$U->avatar_ext?>" align="left" width="50" style="padding:5px;border:0px;"></a>

        <?
        if ( strstr($post->body, '[quote') )
        {
          echo bbcode( nl2br($post->body) );
          ?>
          <div align="right">
            <br>
            <table border="0" cellpadding="0" cellspacing="0" >
              <tr>
                <td align="left">
                  <?include(WWW_ROOT.'/index_modules_v2/comics_needing_comments.inc.php');?>
                </td>
              </tr>
            </table>
            <br>
          </div>
          <?
        }
        else
        {
          $post->body =  str_replace("\n", '', str_replace("\r", '', nl2br($post->body)));
          $pos = strpos( $post->body, "<br /><br />");
          if ( $pos === false ) {
            $pos = 0;
          }

          echo bbcode( substr($post->body, 0, $pos) );
          echo bbcode( substr($post->body, $pos) );
        }
        ?>
        <br><br>
        <p><a href="/community/view_category.php?cid=227"><img src="<?=IMAGE_HOST_SITE_GFX?>/smbtns_quackabout.gif" width="151" height="14" border="0" /></a></p>

        <p><i>This message was posted by <?=username($U->username)?></i></p>

        <hr>
      <?
      ++$ct;
    }
  ?>
  <p><a href="http://<?=DOMAIN?>/news/" style="font-size: 14px;"><img border="0" src="<?=IMAGE_HOST_SITE_GFX?>/smbtns_pastnews.gif" /></a></p>
</div>