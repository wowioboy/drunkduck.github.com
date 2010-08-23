<?
/*
 Parse out all modules, load default if not.
*/
$INDEX_MODS = array();
if ( $USER )
{
  if ( $_COOKIE['idx_mods'] ) {
    $INDEX_MODS = explode(',', $_COOKIE['idx_mods']);
  }
  else {
    $res = db_query("SELECT mod_string FROM index_modules WHERE user_id='".$USER->user_id."'");
    if ( $row = db_fetch_object($res) ) {
      my_setcookie('idx_mods', $row->mod_string);
      $INDEX_MODS = explode(',', $row->mod_string);
    }
  }
}
if ( !count($INDEX_MODS) ) {
  $INDEX_MODS = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
  if ( $USER ) {
    db_query("INSERT INTO index_modules (user_id, mod_string) VALUES ('".$USER->user_id."', '".implode(',', $INDEX_MODS)."')");
    my_setcookie('idx_mods', implode(',', $INDEX_MODS));
  }
}








$INDEX_MODULES = array();

$INDEX_MODULES[1] = array('function'=>'index_top_strips',
                          'description'=>'Displays the top rated Comic Strips',
                          'location'=>'left');
function index_top_strips($args=false)
{
  global $INDEX_MODS;
  if ( !in_array(1, $INDEX_MODS) ) return;

  ?>
  <DIV STYLE='WIDTH:175px;' ALIGN='CENTER' CLASS='container'>
    <IMG SRC='<?=IMAGE_HOST_SITE_GFX?>/top_50_strips.gif'><BR><BR>
    <?
      $res = db_query("SELECT comic_name, last_update, flags FROM comics WHERE comic_type='0' AND total_pages>0 AND delisted=0 ORDER BY rating DESC LIMIT 25");
      while ($row = db_fetch_object($res))
      {
        if (date("Ymd",$row->last_update)==YMD)
        {
          echo "<DIV ALIGN='LEFT' class='uidxList'>* ".comic_name($row)."</DIV>";
        }
        else
        {
          echo "<DIV ALIGN='LEFT' class='idxList'>".comic_name($row)."</DIV>";
        }
      }
      db_free_result($res);
    ?>
    <A HREF='http://<?=DOMAIN?>/comics.php?s=0' STYLE='FONT-SIZE:9px;'>more</A>
    <BR><DIV ALIGN='CENTER' style='font-size:9px;'><I><font CLASS='upToday'>* </font>= updated today</I></DIV>
  </DIV>
  <BR>
  <?
}

$INDEX_MODULES[2] = array('function'=>'index_last_updated_strips',
                          'description'=>'Displays the last updated Comic Strips.',
                          'location'=>'left');
function index_last_updated_strips($args=false)
{
  global $INDEX_MODS;
  if ( !in_array(2, $INDEX_MODS) ) return;

  ?>
  <DIV STYLE='WIDTH:175px;' ALIGN='CENTER' CLASS='container'>
    <B>Top 25 Last Updated Strips</B><BR><BR>
    <?
    $res = db_query("SELECT comic_name, last_update, flags FROM comics WHERE comic_type='0' AND total_pages>0 AND delisted=0 ORDER BY last_update DESC LIMIT 25");
    while ($row = db_fetch_object($res))
    {
      if (date("Ymd",$row->last_update)==YMD)
      {
        echo "<DIV ALIGN='LEFT' class='uidxList'>* ".comic_name($row)."</DIV>";
      }
      else
      {
        echo "<DIV ALIGN='LEFT' class='idxList'>".comic_name($row)."</DIV>";
      }
    }
    db_free_result($res);
    ?>
    <A HREF='http://<?=DOMAIN?>/comics.php?s=0' STYLE='FONT-SIZE:9px;'>more</A>
    <BR><DIV ALIGN='CENTER' style='font-size:9px;'><I><font CLASS='upToday'>* </font>= updated today</I></DIV>
  </DIV>
  <BR>
  <?
}




$INDEX_MODULES[3] = array('function'=>'index_most_read_strips',
                          'description'=>'Displays the most visited Comics.',
                          'location'=>'left');
function index_most_read_strips($args=false)
{
  global $INDEX_MODS;
  if ( !in_array(3, $INDEX_MODS) ) return;

  ?>
  <DIV STYLE='WIDTH:175px;' ALIGN='CENTER' CLASS='container'>
    <B>Top 25 Most Read Strips</B><BR><BR>
    <?
      $res = db_query("SELECT comic_name, last_update, flags FROM comics WHERE comic_type='0' AND total_pages>0 AND delisted=0 ORDER BY visits DESC LIMIT 25");
      while ($row = db_fetch_object($res))
      {
        if ( date("Ymd",$row->last_update)==YMD )
        {
          echo "<DIV ALIGN='LEFT' class='uidxList'>* ".comic_name($row)."</DIV>";
        }
        else
        {
          echo "<DIV ALIGN='LEFT' class='idxList'>".comic_name($row)."</DIV>";
        }
      }
      db_free_result($res);
    ?>
    <A HREF='http://<?=DOMAIN?>/comics.php?s=0' STYLE='FONT-SIZE:9px;'>more</A>
    <BR><DIV ALIGN='CENTER' style='font-size:9px;'><I><font CLASS='upToday'>* </font>= updated today</I></DIV>
  </DIV>
  <BR>
  <?
}

$INDEX_MODULES[4] = array('function'=>'index_search_box',
                          'description'=>'Displays a comic search box.',
                          'location'=>'middle');
function index_search_box($args=false)
{
  global $INDEX_MODS;
  if ( !in_array(4, $INDEX_MODS) ) return;

  ?>
  <FORM ACTION='http://<?=DOMAIN?>/comics.php' METHOD='POST' onSubmit="if ( this.find.value.length<3 ) {alert('A minimum of 3 letters is required on searches.');return false;};return true;">
  <DIV STYLE='WIDTH:470px;' CLASS='container'>
        <I><B>Search DD</B></I> <INPUT TYPE='TEXT' NAME='find'><INPUT TYPE='SUBMIT' VALUE='Search!'>
  </DIV>
  </FORM>
  <?
}

$INDEX_MODULES[5] = array('function'=>'index_news',
                          'description'=>'Displays the latest DrunkDuck News.',
                          'location'=>'middle');
function index_news($args=false)
{
  global $INDEX_MODS;
  if ( !in_array(5, $INDEX_MODS) ) return;

  ?>
  <DIV STYLE='WIDTH:470px;' CLASS='container'>
    <TABLE BORDER='0' CELLPADDING='10' CELLSPACING='0' WIDTH='100%' HEIGHT='100%'>
      <TR>
        <TD WIDTH='100'>
          <IMG SRC='<?=IMAGE_HOST_SITE_GFX?>/news_news.gif'>
        </TD>
        <TD WIDTH='100%'>

        </TD>
      </TR>
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
    $U = &$USERS[$post->user_id];
    ?>
    <TR>
      <TD WIDTH='100%' VALIGN='TOP' ALIGN='LEFT' COLSPAN='2'>
      <?
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
        echo "<IMG SRC='".IMAGE_HOST_SITE_GFX."/anonymous.jpg' ALIGN='LEFT'>";
      }
      ?>
        <B><?=BBCode($post->title)?></B>
        <BR>
        <?=strtoupper(date("F d, Y", $post->timestamp_date))?> - <?=date("g:ia", $post->timestamp_date)?>
        <BR>
        <BR>
        <?=BBCode(nl2br($post->body))?>
        <BR><BR>
        <A HREF='/forum/viewforum.php?f=42'>Discuss this news post in the forum.</A>
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
          ?>
      </TD>
    </TR>
    <TR>
      <TD COLSPAN='2' BGCOLOR='#faa74a' STYLE='HEIGHT:3px;'></TD>
    </TR>
    <?
  }
  ?>
    <TR>
      <TD WIDTH='100%' VALIGN='TOP' ALIGN='LEFT' COLSPAN='2'>
        <A HREF='http://<?=DOMAIN?>/news.php' style='font-size:14px;'>&laquo; Read Past News &laquo;</A>
      </TD>
    </TR>
    </TABLE>
  </DIV><br>
  <?
}


$INDEX_MODULES[6] = array('function'=>'index_comics_in_need',
                          'description'=>'Displays the 10 comics most in need of comments.',
                          'location'=>'middle');
function index_comics_in_need($args=false)
{
  global $INDEX_MODS;
  if ( !in_array(6, $INDEX_MODS) ) return;

  ?>
  <DIV STYLE='WIDTH:470px;' ALIGN='CENTER' CLASS='container'>
    <B>Top 10 Comics IN NEED OF COMMENTS</B><BR><BR>
    <?
      $NEED = array();
      $res = db_query("SELECT comic_id FROM comics_in_need ORDER BY need DESC");
      while ($row = db_fetch_object($res)) {
        $NEED[] = $row->comic_id;
      }
      db_free_result($res);

      $res = db_query("SELECT comic_id, comic_name, last_update, flags FROM comics WHERE comic_id IN ('".implode("','", $NEED)."')");
      while ($row = db_fetch_object($res)) {
        $COMICS[$row->comic_id] = $row;
      }
      db_free_result($res);

      foreach($NEED as $inNeed)
      {
        if (date("Ymd",$COMICS[$inNeed]->last_update)==YMD)
        {
          echo "<DIV ALIGN='LEFT' class='uidxList'>* ".comic_name($COMICS[$inNeed])."</DIV>";
        }
        else
        {
          echo "<DIV ALIGN='LEFT' class='idxList'>".comic_name($COMICS[$inNeed])."</DIV>";
        }
      }
    ?>
    <BR><DIV ALIGN='CENTER' style='font-size:9px;'><I><font CLASS='upToday'>* </font>= updated today</I></DIV>
  </DIV>
  <BR>
  <?
}





$INDEX_MODULES[7] = array('function'=>'index_top_stories',
                          'description'=>'Displays the top rated Comic Strips',
                          'location'=>'right');
function index_top_stories($args=false)
{
  global $INDEX_MODS;
  if ( !in_array(7, $INDEX_MODS) ) return;

  ?>
  <DIV STYLE='WIDTH:175px;' ALIGN='CENTER' CLASS='container'>
    <IMG SRC='<?=IMAGE_HOST_SITE_GFX?>/top_50_stories.gif'><BR><BR>
    <?
      $res = db_query("SELECT comic_name, last_update, flags FROM comics WHERE comic_type='1' AND total_pages>0 AND delisted=0 ORDER BY rating DESC LIMIT 25");
      while ($row = db_fetch_object($res))
      {
        if (date("Ymd",$row->last_update)==YMD)
        {
          echo "<DIV ALIGN='LEFT' class='uidxList'>* ".comic_name($row)."</DIV>";
        }
        else
        {
          echo "<DIV ALIGN='LEFT' class='idxList'>".comic_name($row)."</DIV>";
        }
      }
      db_free_result($res);
    ?>
    <A HREF='http://<?=DOMAIN?>/comics.php?s=1' STYLE='FONT-SIZE:9px;'>more</A>
    <BR><DIV ALIGN='CENTER' style='font-size:9px;'><I><font CLASS='upToday'>* </font>= updated today</I></DIV>
  </DIV>
  <BR>
  <?
}

$INDEX_MODULES[8] = array('function'=>'index_last_updated_stories',
                          'description'=>'Displays the last updated Stories.',
                          'location'=>'right');
function index_last_updated_stories($args=false)
{
  global $INDEX_MODS;
  if ( !in_array(8, $INDEX_MODS) ) return;

  ?>
  <DIV STYLE='WIDTH:175px;' ALIGN='CENTER' CLASS='container'>
    <B>Top 25 Last Updated Stories</B><BR><BR>
    <?
      $res = db_query("SELECT comic_name, last_update, flags FROM comics WHERE comic_type='1' AND total_pages>0 AND delisted=0 ORDER BY last_update DESC LIMIT 25");
      while ($row = db_fetch_object($res))
      {
        if (date("Ymd",$row->last_update)==YMD)
        {
          echo "<DIV ALIGN='LEFT' class='uidxList'>* ".comic_name($row)."</DIV>";
        }
        else
        {
          echo "<DIV ALIGN='LEFT' class='idxList'>".comic_name($row)."</DIV>";
        }
      }
      db_free_result($res);
    ?>
    <A HREF='http://<?=DOMAIN?>/comics.php?s=1' STYLE='FONT-SIZE:9px;'>more</A>
    <BR><DIV ALIGN='CENTER' style='font-size:9px;'><I><font CLASS='upToday'>* </font>= updated today</I></DIV>
  </DIV>
  <BR>
  <?
}


$INDEX_MODULES[9] = array('function'=>'index_most_read_stories',
                          'description'=>'Displays the most visited Stories.',
                          'location'=>'right');
function index_most_read_stories($args=false)
{
  global $INDEX_MODS;
  if ( !in_array(9, $INDEX_MODS) ) return;

  ?>
  <DIV STYLE='WIDTH:175px;' ALIGN='CENTER' CLASS='container'>
    <B>Top 25 Most Read Stories</B><BR><BR>
    <?
      $res = db_query("SELECT comic_name, last_update, flags FROM comics WHERE comic_type='1' AND total_pages>0 AND delisted=0 ORDER BY visits DESC LIMIT 25");
      while ($row = db_fetch_object($res))
      {
        if (date("Ymd",$row->last_update)==YMD)
        {
          echo "<DIV ALIGN='LEFT' class='uidxList'>* ".comic_name($row)."</DIV>";
        }
        else
        {
          echo "<DIV ALIGN='LEFT'  class='idxList'>".comic_name($row)."</DIV>";
        }
      }
      db_free_result($res);
    ?>
    <A HREF='http://<?=DOMAIN?>/comics.php?s=1' STYLE='FONT-SIZE:9px;'>more</A>
    <BR><DIV ALIGN='CENTER' style='font-size:9px;'><I><font CLASS='upToday'>* </font>= updated today</I></DIV>
  </DIV>
  <BR>
  <?
}

$INDEX_MODULES[10] = array('function'=>'index_favorites',
                           'description'=>'Displays all of your favorite comics.',
                           'location'=>'left');
function index_favorites($args=false)
{
  global $INDEX_MODS, $USER;
  if ( !in_array(10, $INDEX_MODS) ) return;

  ?>
  <script>
  function killFav(favID)
  {
    if ( !confirm('Delete this favorite?') ) return;
    ajaxCall('/xmlhttp/removeFav.php?fav='+favID, onFavRes);
  }

  function onFavRes(resp)
  {
    if ( resp != 'noop' ) {
      $('fav_'+resp).style.display = 'none';
    }
  }
  </script>
  <DIV STYLE='WIDTH:175px;' ALIGN='CENTER' CLASS='container'>
    <B>My DD Favorites</B><BR><BR>
    <?
      $COMIC_IDS = array();
      $res = db_query("SELECT comic_id FROM comic_favs WHERE user_id='".$USER->user_id."'");
      while($row = db_fetch_object($res)) {
        $COMIC_IDS[] = $row->comic_id;
      }
      db_free_result($res);

      $res = db_query("SELECT comic_id, comic_name, last_update, flags FROM comics WHERE comic_id IN ('".implode("','", $COMIC_IDS)."')");
      while ($row = db_fetch_object($res))
      {
        if (date("Ymd",$row->last_update)==YMD)
        {
          echo "<DIV ID='fav_".$row->comic_id."' ALIGN='LEFT' class='uidxList'><A HREF='JavaScript:killFav(".$row->comic_id.");' ALT='Delete this favorite' TITLE='Delete this favorite'><FONT STYLE='color:#FF0000;size:9px;text-decoration: none;'>x</FONT></A>* ".comic_name($row)."</DIV>";
        }
        else
        {
          echo "<DIV ID='fav_".$row->comic_id."' ALIGN='LEFT' class='idxList'><A HREF='JavaScript:killFav(".$row->comic_id.");' ALT='Delete this favorite' TITLE='Delete this favorite'><FONT STYLE='color:#FF0000;size:9px;text-decoration: none;'>x</FONT></A> ".comic_name($row)."</DIV>";
        }
      }
      db_free_result($res);
    ?>
    <BR><DIV ALIGN='CENTER' style='font-size:9px;'><I><font CLASS='upToday'>* </font>= updated today</I></DIV>
  </DIV>
  <BR>
  <?
}



$INDEX_MODULES[11] = array('function'=>'index_dd_user_favs',
                           'description'=>'Displays the most favorite comics of DrunkDuck users.',
                           'location'=>'right');
function index_dd_user_favs($args=false)
{
  global $INDEX_MODS, $USER;
  if ( !in_array(11, $INDEX_MODS) ) return;

  ?>
  <DIV STYLE='WIDTH:175px;' ALIGN='CENTER' CLASS='container'>
    <B>Top 25 DrunkDuck Favorites</B><BR><BR>
    <?
      $COMIC_IDS = array();
      $res = db_query("SELECT comic_id FROM comic_favs_tally ORDER BY tally DESC LIMIT 25");
      while($row = db_fetch_object($res)) {
        $COMIC_IDS[] = $row->comic_id;
      }
      db_free_result($res);

      $res = db_query("SELECT comic_name, last_update, flags FROM comics WHERE comic_id IN ('".implode("','", $COMIC_IDS)."')");
      while ($row = db_fetch_object($res))
      {
        if (date("Ymd",$row->last_update)==YMD)
        {
          echo "<DIV ALIGN='LEFT' class='uidxList'>* ".comic_name($row)."</DIV>";
        }
        else
        {
          echo "<DIV ALIGN='LEFT' class='idxList'>".comic_name($row)."</DIV>";
        }
      }
      db_free_result($res);
    ?>
    <BR><DIV ALIGN='CENTER' style='font-size:9px;'><I><font CLASS='upToday'>* </font>= updated today</I></DIV>
  </DIV>
  <BR>
  <?
}

$INDEX_MODULES[12] = array('function'=>'index_gigcasts',
                           'description'=>'<A HREF=\'http://gigcast.libsyn.com/\'>Gigcast</A> - The definitive Podcast of the latest news in webcomics.',
                           'location'=>'left');
function index_gigcasts($args=false)
{
  global $INDEX_MODS;
  if ( !in_array(12, $INDEX_MODS) ) return;

  ?>
  <DIV STYLE='WIDTH:175px;' ALIGN='CENTER' CLASS='container'>
    <B>Latest Gigcasts</B><BR><BR>
    <?
    require_once(WWW_ROOT.'/rss/gigcast.inc.php');
    foreach($rss_channel['ITEMS'] as $ct=>$piece)
    {
      $name = "<A HREF='".$piece['LINK']."'>".$piece['TITLE']."</A>";
      $ts   = strtotime($piece['PUBDATE']);
      $name = date("M.d", $ts) .' '. $name;

      $piece['DESCRIPTION'] = preg_replace('`<a (.*)>(.*)</a>`U', '', $piece['DESCRIPTION']);
      $piece['DESCRIPTION'] = str_replace('"', '&quot;', $piece['DESCRIPTION']);
      $piece['DESCRIPTION'] = str_replace("\n", "", $piece['DESCRIPTION']);
      $piece['DESCRIPTION'] = str_replace("\r", "", $piece['DESCRIPTION']);

      $OMO = "onMouseover=\"ddrivetip('<DIV ALIGN=\'center\'>".str_replace("'", "\'", $piece['DESCRIPTION'])."</DIV>', '#FF0000');return true\"; onMouseout=\"hideddrivetip()\"";
      if ( YMD == date("Ymd", $ts) ) {
        echo "<DIV ALIGN='LEFT' class='uidxList' $OMO>* ".$name."</DIV>\n";
      }
      else {
        echo "<DIV ALIGN='LEFT' class='idxList' $OMO>".$name."</DIV>\n";
      }
      if ( $ct == 9 ) break;
    }
    ?>
    <BR>
    <A HREF='itpc://gigcast.libsyn.com/rss'><IMG SRC='<?=IMAGE_HOST_SITE_GFX?>/itunes.png' border='0'></A>
    <BR><DIV ALIGN='CENTER' style='font-size:9px;'><I><font CLASS='upToday'>* </font>= updated today</I></DIV>
  </DIV>
  <BR>
  <?
}









function index_468x60($args=false)
{
  ?>
  <DIV>
    <A HREF='http://www.lulu.com/content/221840'><IMG SRC='/gfx/cat_banner.png' BORDER='0'></A>
  </DIV>
  <BR>
  <?
}
?>