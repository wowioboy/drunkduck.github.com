<?
  header("Location: http://".USER_DOMAIN."/".$_GET['u']);

  return;

  $U = db_escape_string($_GET['u']);

  $res = db_query("SELECT * FROM users WHERE username='".$U."'");
  if ( !$uRow = db_fetch_object($res) ) return;
?>







<h1 align="left">Viewing User: <?=$uRow->username?></h1>

<script src="<?=HTTP_JAVASCRIPT?>/scriptaculous/scriptaculous.js" type="text/javascript"></script>
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
  <div align="left" style="width:300px;font-size:9px;">
    <form id='findUserForm' action='<?=$_SERVER['PHP_SELF']?>' method='GET'>
      Find User:
      <br>
      <input type="text" id="u" name="u" value="<?=$_GET['u']?>" style="width:300px;">
      <div id="autocomplete_choices" class="autocomplete"></div>
    </form>

    <script type="text/javascript">
      new Ajax.Autocompleter("u", "autocomplete_choices", "/xmlhttp/find_username.php", {paramName: "try", minChars: 3, afterUpdateElement: getSelectionId});

      function getSelectionId(text, li) {
        $('u').value = li.id;
        $('findUserForm').submit();
      }
    </script>
  </div>


  <?
    if ( $uRow->avatar_ext )
    {
      echo "<DIV ALIGN='CENTER'>";
      if ( $uRow->avatar_ext == 'swf' ) {
        $INFO = getimagesize(WWW_ROOT.'/gfx/avatars/avatar_'.$uRow->user_id.'.swf');
        $swf = new FlashMovie(IMAGE_HOST.'/avatars/avatar_'.$uRow->user_id.'.swf', $INFO[0], $INFO[1]);
        $swf->showHTML();
      }
      else {
        echo "<IMG SRC='".IMAGE_HOST."/avatars/avatar_".$uRow->user_id.".".$uRow->avatar_ext."'>";
      }
      echo "</DIV><BR>";
    }
  ?>

  <?
  if ( $USER )
  {
    ?><div align="center"><a href="http://<?=DOMAIN?>/community/message/author.php?to=<?=$_GET['u']?>"><IMG SRC='<?=IMAGE_HOST?>/mail/mail_new.gif' border="0"> Send <?=$_GET['u']?> a Private Quack!</a><div><?
  }

  ?>
  <br><br>
  <b>Comics by <?=$uRow->username?>:</b><br>
<table border="0" cellpadding="10" cellspacing="0" width="600" height="20" class="results" style="border:1px solid white;">
  <tr>
    <?
    $res = db_query("SELECT * FROM comics WHERE user_id='".$uRow->user_id."'");
    $ct = -1;
    while($row = db_fetch_object($res))
    {
      $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name);
      if ( ++$ct%4 == 0 ) {
        ?></TR><TR><?
      }
      ?>
        <td align="center" valign="top" width="20%">
        <a href='<?=$url?>'><?=get_current_thumbnail($row->comic_id, $row->comic_name)?>
        <br>
        <?=$row->comic_name?></a>
        <br>
        <span class="stripinfo">
          <img src="<?=IMAGE_HOST_SITE_GFX?>/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" title="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" />
          <?=number_format($row->total_pages)?> pgs | Updated: <?=((date("Ymd", $row->last_update)==YMD)?"Today":date("m/d/y", $row->last_update))?>
        </span>
      <?
    }
    ?>
  </tr>
</table>


  <br><br>
  <b>Comics assisted by <?=$uRow->username?>:</b><br>
<table border="0" cellpadding="10" cellspacing="0" width="600" height="20" class="results" style="border:1px solid white;">
  <tr>
    <?
    $res = db_query("SELECT * FROM comics WHERE secondary_author='".$uRow->user_id."'");
    $ct = -1;
    while($row = db_fetch_object($res))
    {
      $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name);
      if ( ++$ct%4 == 0 ) {
        ?></TR><TR><?
      }
      ?>
        <td align="center" valign="top" width="20%">
        <a href='<?=$url?>'><?=get_current_thumbnail($row->comic_id, $row->comic_name)?>
        <br>
        <?=$row->comic_name?></a>
        <br>
        <span class="stripinfo">
          <img src="<?=IMAGE_HOST_SITE_GFX?>/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" title="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" />
          <?=number_format($row->total_pages)?> pgs | Updated: <?=((date("Ymd", $row->last_update)==YMD)?"Today":date("m/d/y", $row->last_update))?>
        </span>
      <?
    }
    ?>
  </tr>
</table>



  <?
  /*
  ?>

<DIV STYLE='WIDTH:300px;HEIGHT:150px;' CLASS='container' ALIGN='LEFT'>
  <DIV CLASS='header' ALIGN='CENTER'>Comics:</DIV>

  <?
  $res = db_query("SELECT * FROM comics WHERE user_id='".$uRow->user_id."'");
  while ($row = db_fetch_object($res)) {
    echo comic_name($row)."<BR>";
  }
  ?>

  <DIV CLASS='header' ALIGN='CENTER'>Comics Assisting:</DIV>
  <?
  $res = db_query("SELECT * FROM comics WHERE secondary_author='".$uRow->user_id."'");
  while ($row = db_fetch_object($res)) {
    echo comic_name($row)."<BR>";
  }
  ?>
</DIV>


<?
*/
if ($USER->flags & FLAG_IS_ADMIN)
{
  ?>
  <BR><br>
  <DIV STYLE='WIDTH:300px;' CLASS='container' ALIGN='LEFT'>
    <DIV CLASS='header' ALIGN='CENTER'>Administrative Insight:</DIV>
    <?
    $res = db_query("SELECT * FROM demographics WHERE user_id='".$uRow->user_id."'");
    $row = db_fetch_object($res);
    db_free_result($res);
    ?>
    <table border="1" cellpadding="5" cellspacing="0" width="300">

      <tr>
        <td align="right" style="font-size:18px;" valign="top">
          <B>Name:</B>
        </td>
        <td align="left">
          <?=$row->first_name." ".$row->last_name?>
        </td>
      </tr>

      <tr>
        <td align="right" style="font-size:18px;" valign="top">
          <B>Email:</B>
        </td>
        <td align="left">
          <?=$row->email?>
          <?
          $USER_LIST = array();
          $res = db_query("SELECT user_id FROM demographics WHERE email='".trim($row->email)."' AND user_id != '".$row->user_id."'");
          while( $DEMO_ROW = db_fetch_object($res) ) {
            $USER_LIST[] = $DEMO_ROW->user_id;
          }
          db_free_result($res);

          if ( count($USER_LIST) )
          {
            ?>
            <br>
            <br>
            <u><b>Other Users with this email</b></u>
            <br>
            <?
            $res = db_query("SELECT * FROM users WHERE user_id IN ('".implode("','", $USER_LIST)."') ORDER BY username ASC");
            while( $oRow = db_fetch_object($res) ) {
              echo username($oRow->username)."<BR>";
            }
          }
          ?>
        </td>
      </tr>

      <tr>
        <td align="right" style="font-size:18px;" valign="top">
          <B>IP:</B>
        </td>
        <td align="left">
          <?=$uRow->ip?>
          <?
          $USER_LIST = array();
          $res = db_query("SELECT * FROM users WHERE ip='".$uRow->ip."' AND user_id != '".$row->user_id."' ORDER BY username ASC");
          if ( db_num_rows($res) > 0 )
          {
            ?>
            <br>
            <br>
            <u><b>Other Users with this IP</b></u>
            <br>
            <?
            while( $oRow = db_fetch_object($res) ) {
              echo username($oRow->username)."<BR>";
            }
          }
          db_free_result($res);
          ?>
        </td>
      </tr>

      <tr>
        <td align="right" style="font-size:18px;" valign="top">
          <B>Gender:</B>
        </td>
        <td align="left">
          <?=$row->gender?>
        </td>
      </tr>

      <tr>
        <td align="right" style="font-size:18px;" valign="top">
          <B>Joined:</B>
        </td>
        <td align="left">
          <?=date("m-d-Y",$uRow->signed_up)?>
        </td>
      </tr>

      <tr>
        <td align="right" style="font-size:18px;" valign="top">
          <B>Age:</B>
        </td>
        <td align="left">
          <?=floor( (time()-$row->dob_timestamp)/60/60/24/365 )?>
        </td>
      </tr>

    </table>

  </DIV>
    <br><br>

    Forum Topics Started:<br>
    <?
    include(WWW_ROOT.'/community/community_data.inc.php');
    include(WWW_ROOT.'/community/community_functions.inc.php');

    $tRes = db_query("SELECT * FROM community_topics WHERE user_id='".$uRow->user_id."' ORDER BY date_created DESC LIMIT 10");
    while($tRow = db_fetch_object($tRes) )
    {
      ?>
      <div align="left" style="padding:5px;border:1px solid white;width:600px;">
        <?
        $res2 = db_query("SELECT * FROM community_posts WHERE topic_id='".$tRow->topic_id."' ORDER BY post_id ASC LIMIT 1");
        if($post = db_fetch_object($res2) )
        {
          if ( !($post->flags&FORUM_FLAG_ADMIN_ONLY) || ($USER->flags & FLAG_IS_ADMIN) )
          {
            if ( !($post->flags&FORUM_FLAG_MOD_ONLY) || ($USER->flags & FLAG_IS_MOD) )
            {
              if ( $post->last_post_id == 0 )
              {
                $res = db_query("SELECT * FROM community_posts WHERE topic_id='".$post->topic_id."' ORDER BY post_id DESC LIMIT 1");
                $row = db_fetch_object($res);
                db_free_result($res);

                $post->last_post_id = $row->post_id;
                db_query("UPDATE community_topics SET last_post_id='".$post->last_post_id."' WHERE topic_id='".$post->topic_id."'");
              }

              ?><a href="http://<?=DOMAIN?>/community/view_topic.php?tid=<?=$tRow->topic_id?>&<?=passables_query_string( array('tid') )?>"><?=htmlentities(html_entity_decode($tRow->topic_name), ENT_QUOTES)?></a><?=( ($tRow->flags & FORUM_FLAG_LOCKED)?" *LOCKED*":"")?><hr><?
              echo nl2br(  community_bb_code( htmlentities(html_entity_decode($post->post_body), ENT_QUOTES) ) );
            }
          }
        }
        db_free_result($res2);
        ?>
      </div>
      <br><br>
      <?
    }
    ?>
  <?
  return;

  set_time_limit(0);
  $cRes = db_query("SELECT * FROM comics WHERE user_id='".$uRow->user_id."' OR secondary_author='".$uRow->user_id."'");
  while($cRow = db_fetch_object($cRes))
  {
    $baseURL = 'http://'.DOMAIN.'/'.comicNameToFolder($cRow->comic_name).'/';

    $folder = $cRow->comic_id;
    if ( !file_exists(WWW_ROOT.'/gfx/comic_thumbnails_cache/'.$folder) ) { mkdir(WWW_ROOT.'/gfx/comic_thumbnails_cache/'.$folder); }
    ?>
    <BR>
    <DIV STYLE='WIDTH:600px;' CLASS='container' ALIGN='LEFT'>
      <DIV CLASS='header' ALIGN='CENTER'>Comic Pages for <?=$cRow->comic_name?></DIV>
      <?
      $pRes = db_query("SELECT * FROM comic_pages WHERE comic_id='".$cRow->comic_id."' ORDER BY order_id ASC");
      while( $pRow = db_fetch_object($pRes) )
      {
        if ( $pRow->file_ext == 'swf' )
        {

        }
        else
        {
          $realURL = $baseURL.'?p='.$pRow->page_id;
          if ( !file_exists(WWW_ROOT.'/gfx/comic_thumbnails_cache/'.$folder.'/'.$cRow->comic_id.'_'.$pRow->page_id.'.jpg') )
          {
            $N = $cRow->comic_name;
            $FILE = WWW_ROOT.'/comics/'.$N{0}.'/'.str_replace(' ', '_', $N).'/pages/'.md5($pRow->comic_id.$pRow->page_id).'.'.$pRow->file_ext;
            thumb($FILE, WWW_ROOT.'/gfx/comic_thumbnails_cache/'.$folder.'/'.$cRow->comic_id.'_'.$pRow->page_id.'.jpg', 80, 100, false);
          }
          echo "<A HREF='$realURL'><IMG SRC='".IMAGE_HOST."/comic_thumbnails_cache/".$folder.'/'.$cRow->comic_id."_".$pRow->page_id.".jpg' border='0'></A>";
        }
      }
      ?>
    </DIV>
    <?
  }
}

?>