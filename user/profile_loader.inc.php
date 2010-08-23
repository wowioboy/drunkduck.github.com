<?
if ( !$_GET['u'] && $USER ) {
  header('Location: http://'.USER_DOMAIN.'/search.php');
}

if ( !$_GET['u'] ) return;

$U = db_escape_string($_GET['u']);

$res = db_query("SELECT * FROM users WHERE username='".$U."'");
if ( !$viewRow = db_fetch_object($res) )
{
  ?><div align="center" style="height:300px;"><b>That user does not exist.</b></div><?
  return;

}
db_free_result($res);










if ( $viewRow->flags & FLAG_IS_PUBLISHER ) {
  define('IS_PUBLISHER', 1);
}
$IMAGE_FOLDER = IMAGE_HOST.'/profile_gfx/design_gfx/user';

if ( IS_PUBLISHER == 1 ) {
  $IMAGE_FOLDER = IMAGE_HOST.'/profile_gfx/design_gfx/publisher';
}












?>
<style>
.content_body
{
  font: 11px Verdana, Arial, Helvetica, Geneva, sans-serif;
  color:#000000;
  background:#f8d689 url(<?=$IMAGE_FOLDER?>/page_bg.png) repeat-x 0 top;
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

<style>
div.autocomplete {
  position:absolute;
  width:250px;
  background-color:white;
  border:1px solid #6699ff;
  margin:0px;
  padding:0px;
}
div.autocomplete ul {
  list-style-type:none;
  margin:0px;
  padding:0px;
}
div.autocomplete ul li.selected { background-color: #fffa8c;}
div.autocomplete ul li {
  list-style-type:none;
  display:block;
  margin:0;
  padding:2px;
  cursor:pointer;
  color:#000000;
}

div.autocomplete li span.informal {
  padding-left:10px;
  display:block;
  font-size:9px;
  color:#888;
}

div.autocomplete li span.informal span.informal_rt {
  padding-left:10px;
  display:block;
  font-size:9px;
  color:#888;
  text-align: right;
}


</style>

<script src="<?=HTTP_JAVASCRIPT?>/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<div class="content_body">

  <div style="background:url(<?=$IMAGE_FOLDER?>/page_title_bg.gif);height:40px;" align="left">
    <img src="<?=$IMAGE_FOLDER?>/profile_hdr.gif" style="margin-left:5px;margin-top:10px;float:left;border:0px;">
    <div style="width:200px;float:right;margin-right:15px;margin-top:7px;" align="right">
        <input type="text" id="u" name="u" value="<?=$_GET['u']?>" style="width:100%;border:1px solid #955b1b;color:955b1b;text-align:right;">
        <div id="autocomplete_choices" class="autocomplete"></div>
    </div>
  </div>
    <script type="text/javascript">
      new Ajax.Autocompleter("u", "autocomplete_choices", "/xmlhttp/find_username.php", {paramName: "try", minChars: 3, afterUpdateElement: getSelectionId});

      function getSelectionId(text, li) {
        var url = 'http://<?=USER_DOMAIN?>/'+li.id;
        window.open(url ,"_top");
      }
    </script>
  <?
    include(WWW_ROOT.'/community/community_data.inc.php');
    include(WWW_ROOT.'/community/community_functions.inc.php');



    $GLOBALS['FRIEND_DATA'] = new stdClass();

    $res = db_query("SELECT COUNT(*) as total_friends FROM friends WHERE user_id='".$viewRow->user_id."'");
    $row = db_fetch_object($res);
    db_free_result($res);
    $GLOBALS['total_friends'] = $row->total_friends;


    // Friends are special. We should pull them up front.
    $real_friends = false;
    $GLOBALS['FRIEND_DATA']->top_friends = array();
    $res = db_query("SELECT * FROM friends WHERE user_id='".$viewRow->user_id."' ORDER BY order_id DESC LIMIT 12");
    while( $row = db_fetch_object($res) )
    {
      if ( $row->order_id > 0 )
      {
        $real_friends = true;
        $GLOBALS['FRIEND_DATA']->top_friends[$row->friend_id] = $row->friend_id;
      }
      else if ( !$real_friends ) {
        $GLOBALS['FRIEND_DATA']->top_friends[$row->friend_id] = $row->friend_id;
      }
    }
    db_free_result($res);


    $res = db_query("SELECT user_id, username, avatar_ext FROM users WHERE user_id IN ('".implode("','", $GLOBALS['FRIEND_DATA']->top_friends)."')");
    while($row = db_fetch_object($res) ) {
      $GLOBALS['FRIEND_DATA']->top_friends[$row->user_id] = $row;
    }
    db_free_result($res);




    $GLOBALS['FRIEND_DATA']->you_are_their_friend = false;
    if ( $USER )
    {
      $res = db_query("SELECT * from friends WHERE user_id='".$viewRow->user_id."' AND friend_id='".$USER->user_id."'");
      if ( $row = db_fetch_object($res) ) {
        $GLOBALS['FRIEND_DATA']->you_are_their_friend = true;
      }
      db_free_result($res);
    }

    $GLOBALS['FRIEND_DATA']->they_are_your_friend = false;
    if ( $USER )
    {
      $res = db_query("SELECT * from friends WHERE user_id='".$USER->user_id."' AND friend_id='".$viewRow->user_id."'");
      if ( $row = db_fetch_object($res) ) {
        $GLOBALS['FRIEND_DATA']->they_are_your_friend = true;
      }
      db_free_result($res);
    }




    if ( $USER && ($USER->user_id != $viewRow->user_id) )
    {
      $_POST['comment'] = trim($_POST['comment']);
      if ( strlen($_POST['comment']) >= 3 )
      {
        if ( $GLOBALS['FRIEND_DATA']->you_are_their_friend )
        {
          $COMMENT = $_POST['comment'];
          //$COMMENT = preg_replace('`<[ ]*script`', '', $COMMENT);
          $COMMENT = substr($COMMENT, 0, 2000);

          db_query("INSERT INTO profile_comments (user_id, poster_id, comment, approved, posted) VALUES ('".$viewRow->user_id."', '".$USER->user_id."', '".db_escape_string(htmlentities($COMMENT, ENT_QUOTES))."', '0', '".time()."')");

          include_once(WWW_ROOT.'/community/message/tikimail_func.inc.php');

          send_system_mail('DD Profiles', $viewRow->username, $USER->username.' has left you a comment!', "Hi ".$viewRow->username."!\n\n [url=http://user.drunkduck.com/".$USER->username."]".$USER->username."[/url] has left you a comment!\n\nYou can view all of your unapproved comments by clicking [url=http://".USER_DOMAIN."/read_all_comments.php?u=".$viewRow->username."&approve=yes]here[/url]!");
        }
      }
    }

    if ( ($USER->user_id == $viewRow->user_id) || ($USER->flags & FLAG_IS_ADMIN) )
    {
      if ( $_GET['del'] ) {
        db_query("DELETE FROM profile_comments WHERE user_id='".$USER->user_id."' AND id='".(int)$_GET['del']."'");
      }

      if ( isset($_POST['about_self']) )
      {
        $ABOUT = $_POST['about_self'];
        $ABOUT = substr($ABOUT, 0, 4000);
        $ABOUT = htmlentities($ABOUT, ENT_QUOTES);

        db_query("UPDATE users SET about_self='".db_escape_string( $ABOUT )."' WHERE user_id='".$viewRow->user_id."'");
        $viewRow->about_self = $ABOUT;
        give_trophy( $USER->user_id, $USER->trophy_string, 30 );
      }

      if ( isset($_GET['publisher']) )
      {
        if ( ($USER->flags & FLAG_PUBLISHER_QUAL) && ($USER->flags & FLAG_IS_PUBLISHER) )
        {
          // Offer to remove publisher status.
          if ( $_GET['publisher'] == 0 ) {
            $USER->flags = $USER->flags & ~FLAG_IS_PUBLISHER;
            db_query("UPDATE users SET flags='".$USER->flags."' WHERE user_id='".$USER->user_id."'");
            header("Location: http://".USER_DOMAIN."/".$USER->username);
            die;
          }
        }
        else if ( ($USER->flags & FLAG_PUBLISHER_QUAL) )
        {
          // Offer to add publisher status.
          if ( $_GET['publisher'] == 1 ) {
            $USER->flags = $USER->flags | FLAG_IS_PUBLISHER;
            db_query("UPDATE users SET flags='".$USER->flags."' WHERE user_id='".$USER->user_id."'");
            header("Location: http://".USER_DOMAIN."/".$USER->username);
            die;
          }
        }
      }

    }

  ?>
  <table border="0" cellpadding="0" cellspacing="5" width="100%" height="100%">
    <tr>
      <td width="43%" align="left" valign="top">
        <? include('modules/left/profile_basic_info.inc.php'); ?>

        <?
        if ( IS_PUBLISHER == 1 ) {
          include('modules/left/publisher_links.inc.php');
        }
        ?>

        <? include('modules/left/trophies.inc.php'); ?>

        <? include('modules/left/game_high_scores.inc.php'); ?>

        <? include('modules/left/forum_topics.inc.php'); ?>

      </td>
      <td width="57%" align="left" valign="top">
        <? include('modules/right/user_comics.inc.php'); ?>

        <? include('modules/right/user_assisted_comics.inc.php'); ?>

        <? include('modules/right/friends.inc.php'); ?>

        <? include('modules/right/recommended_comics.inc.php'); ?>

        <? include('modules/right/videos.inc.php'); ?>

        <? include('modules/right/comments.inc.php'); ?>
      </td>
    </tr>
  </table>

</div>