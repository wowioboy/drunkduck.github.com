<?
class DDTag
{
  var $callback;
  function DDTag($callbk) {
    $this->callback = $callbk;
  }
}






// <!--[FORUM_URL]-->
function displayForumURL($arg)
{
  global $COMIC_ROW;
  if ( $COMIC_ROW->flags & FLAG_HAS_FORUM ) {
    return 'http://'.DOMAIN.'/community/?comic_id='.$COMIC_ROW->comic_id;
  }
  else return '#';
}

// <!--[FORUM_BUTTON=path_to_button]-->
function displayForumBTN($arg)
{
  global $COMIC_ROW;
  if ( $COMIC_ROW->flags & FLAG_HAS_FORUM ) {
    if ( $arg ) {
      return '<a href="http://'.DOMAIN.'/community/?comic_id='.$COMIC_ROW->comic_id.'"><img src="'.$arg.'" border="0"></a>';
    }
    else {
      return '<a href="http://'.DOMAIN.'/community/?comic_id='.$COMIC_ROW->comic_id.'"><img src="'.IMAGE_HOST_SITE_GFX.'/visit_forum_button.gif" border="0"></a>';
    }
  }
  else return '';
}


// <!--[CREDITS]-->
function displayCredits($arg)
{
  global $COMIC_ROW;
  $ret = '';
  $res = db_query("SELECT * FROM credits WHERE comic_id='".$COMIC_ROW->comic_id."' ORDER BY credit_id ASC");
  while($row = db_fetch_object($res) )
  {
    $ret .= '<p><strong>'.$row->credit_name.':</strong> '.$row->credit_value.'</p>';
  }
  return $ret;
}

// <!--[PAGE_DATE=format]-->
function displayPageDate($arg)
{
  global $PAGE_ROW;

  if ( $arg )
    return date($arg, $PAGE_ROW->post_date);
  return date("m.d.Y", $PAGE_ROW->post_date);
}










// <!--[RSS=http:/www.drunkduck.com/gfx/site_gfx/rss.gif]-->
function displayComicRss()
{
  global $COMIC_ROW;

  if ( !$arg ) $arg = 'http://'.DOMAIN.'/gfx/site_gfx/rss.gif';

  return "<A HREF='http://".DOMAIN."/rss/rss.php?cid=".$COMIC_ROW->comic_id."' TITLE='Syndicate this Comic!'><IMG SRC='".$arg."' BORDER='0'></A>";
}










// <!--[PAGE]-->
function displayComicPage( $arg=true )
{
  global $COMIC_ROW, $PAGE_ROW;

  $EXTRA = include(WWW_ROOT.'/comics/resource_files/comic_tags.inc.php');
/*
<div align="center" style="width:472px;background-color:#efefef;padding:1px;border:1px dashed #aaaaaa;"><div align="left" style="font-weight:bold;margin-left:1px;color:#999999;font-size:8px;">Advertisement:</div><script language=\'JavaScript\' type=\'text/javascript\' src=\'http://ads.platinumstudios.net/adx.js\'></script>
  <script language=\'JavaScript\' type=\'text/javascript\'>

   if (!document.phpAds_used) document.phpAds_used = \',\';
   phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);

   document.write ("<" + "script language=\'JavaScript\' type=\'text/javascript\' src=\'");
   document.write ("http://ads.platinumstudios.net/adjs.php?n=" + phpAds_random);
   document.write ("&amp;what=zone:57");
   document.write ("&amp;exclude=" + document.phpAds_used);
   if (document.referrer)
      document.write ("&amp;referer=" + escape(document.referrer));
   document.write ("\'><" + "/script>");

  </script><noscript><a href=\'http://ads.platinumstudios.net/adclick.php?n=a4203da3\' target=\'_blank\'><img src=\'http://ads.platinumstudios.net/adview.php?what=zone:57&amp;n=a4203da3\' border=\'0\' alt=\'\'></a></noscript></div>
  */
  if ($PAGE_ROW->file_ext == 'swf')
  {
    $N = $COMIC_ROW->comic_name;

    $INFO = getimagesize(WWW_ROOT.'/comics/'.$N{0}.'/'.str_replace(' ', '_', $N).'/pages/'.md5($COMIC_ROW->comic_id.$PAGE_ROW->page_id).'.swf');
    $swf = new FlashMovie("http://".COMIC_DOMAIN."/".str_replace(' ', '_', $N)."/pages/".md5($COMIC_ROW->comic_id.$PAGE_ROW->page_id).".swf", $INFO[0], $INFO[1]);
    $swf->setTransparent(false);

    return "<table width=100 border=0 cellpadding=0 cellspacing=0><tr><td align=center>".
            $swf->getHTML().
            $EXTRA.
            "</td></tr></table>";

    return $swf->getHTML().$EXTRA;
  }

  //if ( $arg == "NO_TAGS" ) return "<IMG SRC='http://".COMIC_DOMAIN."/".comicNameToFolder($COMIC_ROW->comic_name)."/pages/".md5($COMIC_ROW->comic_id.$PAGE_ROW->page_id).".".$PAGE_ROW->file_ext."'>";


  return "<table width=100 border=0 cellpadding=0 cellspacing=0><tr><td align=center>".
            "<IMG SRC='http://".COMIC_DOMAIN."/".comicNameToFolder($COMIC_ROW->comic_name)."/pages/".md5($COMIC_ROW->comic_id.$PAGE_ROW->page_id).".".$PAGE_ROW->file_ext."'>".
            $EXTRA.
            "</td></tr></table>";

  return "<table width=100 border=0 cellpadding=0 cellspacing=0><tr><td align=center><IMG SRC='http://".COMIC_DOMAIN."/".comicNameToFolder($COMIC_ROW->comic_name)."/pages/".md5($COMIC_ROW->comic_id.$PAGE_ROW->page_id).".".$PAGE_ROW->file_ext."'>".$EXTRA."</td></tr></table>";
}



// <!--[PAGE_IMAGE_URL]-->
function displayComicPageImageURL( $arg=true )
{
  global $COMIC_ROW, $PAGE_ROW;

  return "http://".COMIC_DOMAIN."/".comicNameToFolder($COMIC_ROW->comic_name)."/pages/".md5($COMIC_ROW->comic_id.$PAGE_ROW->page_id).".".$PAGE_ROW->file_ext;
}







// <!--[NAV_FIRST=http://www.drunkduck.com/gfx/nav.gif,http://www.drunkduck.com/nonav.gif]-->
function displayNavFirst( $arg )
{
  if ( $arg )
  {
    if ( strstr($arg, ',') ) {
      list($linkImg, $noLinkImg) = explode(',', $arg);
    }
    else {
      $linkImg   = $arg;
      $noLinkImg = false;
    }
  }
  else {
    $linkImg   = 'http://'.DOMAIN.'/gfx/template_gfx/nav_first.gif';
    $noLinkImg = false;
  }

  if ( $url = displayUrlFirst() ) {
    return "<A HREF='".$url."' TITLE='The first page!'><IMG SRC='".$linkImg."' BORDER='0'></A>";
  }
  else if ( $noLinkImg ) {
    return "<IMG SRC='".$noLinkImg."' BORDER='0'>";
  }
  return '';
}










// <!--[NAV_PREVIOUS=http://www.drunkduck.com/gfx/nav.gif,http://www.drunkduck.com/nonav.gif]-->
function displayNavPrev( $arg )
{
  if ( $arg )
  {
    if ( strstr($arg, ',') ) {
      list($linkImg, $noLinkImg) = explode(',', $arg);
    }
    else {
      $linkImg   = $arg;
      $noLinkImg = false;
    }
  }
  else {
    $linkImg   = 'http://'.DOMAIN.'/gfx/template_gfx/nav_previous.gif';
    $noLinkImg = false;
  }

  if ( $url = displayUrlPrev() ) {
    return "<A HREF='".$url."' TITLE='The previous page!'><IMG SRC='".$linkImg."' BORDER='0'></A>";
  }
  else if ( $noLinkImg ) {
    return "<IMG SRC='".$noLinkImg."' BORDER='0'>";
  }
  return '';
}










// <!--[NAV_NEXT=http://www.drunkduck.com/gfx/nav.gif,http://www.drunkduck.com/nonav.gif]-->
function displayNavNext( $arg )
{
  if ( $arg )
  {
    if ( strstr($arg, ',') ) {
      list($linkImg, $noLinkImg) = explode(',', $arg);
    }
    else {
      $linkImg   = $arg;
      $noLinkImg = false;
    }
  }
  else {
    $linkImg   = 'http://'.DOMAIN.'/gfx/template_gfx/nav_next.gif';
    $noLinkImg = false;
  }

  if ( $url = displayUrlNext() ) {
    return "<A HREF='".$url."' TITLE='The next page!'><IMG SRC='".$linkImg."' BORDER='0'></A>";
  }
  else if ( $noLinkImg ) {
    return "<IMG SRC='".$noLinkImg."' BORDER='0'>";
  }
  return '';
}










// <!--[NAV_LAST=http://www.drunkduck.com/gfx/nav.gif,http://www.drunkduck.com/nonav.gif]-->
function displayNavLast( $arg )
{
  if ( $arg )
  {
    if ( strstr($arg, ',') ) {
      list($linkImg, $noLinkImg) = explode(',', $arg);
    }
    else {
      $linkImg   = $arg;
      $noLinkImg = false;
    }
  }
  else {
    $linkImg   = 'http://'.DOMAIN.'/gfx/template_gfx/nav_last.gif';
    $noLinkImg = false;
  }



  global $PAGE_LIST, $PAGE_ROW;

  foreach( $PAGE_LIST as $possible )
  {
    if ( $possible && ($PAGE_ROW->page_id != $possible->page_id) ) {
      return "<A HREF='".$_SERVER['PHP_SELF'].'?p='.$possible->page_id."' TITLE='The last page!'><IMG SRC='".$linkImg."' BORDER='0'></A>";
    }
    else if ( $noLinkImg ) {
      return "<IMG SRC='".$noLinkImg."' BORDER='0'>";
    }
    return '';
  }
}










// <!--[URL_FIRST]-->
function displayUrlFirst( $arg )
{
  global $PAGE_LIST, $PAGE_ROW;

  $keys       = array_keys($PAGE_LIST);
  $first_page = $keys[count($keys)-1];
  $possible   = &$PAGE_LIST[$first_page];

  if ( $possible && ($PAGE_ROW->page_id != $possible->page_id) ) {
    return $_SERVER['PHP_SELF'].'?p='.$possible->page_id;
  }
  return;
}










// <!--[URL_PREVIOUS]-->
function displayUrlPrev( $arg )
{
  global $PAGE_LIST, $PAGE_ROW;

  $possible = &$PAGE_LIST[$PAGE_ROW->order_id-1];

  if ( $possible && ($possible->page_id != $PAGE_ROW->page_id) )
  {
    return $_SERVER['PHP_SELF'].'?p='.$possible->page_id;
  }
  return;
}










// <!--[URL_NEXT]-->
function displayUrlNext( $arg )
{
  global $PAGE_LIST, $PAGE_ROW;

  $possible = &$PAGE_LIST[$PAGE_ROW->order_id+1];

  if ( $possible && ($possible->page_id != $PAGE_ROW->page_id) )
  {
    return $_SERVER['PHP_SELF'].'?p='.$possible->page_id;
  }
  return;
}










// <!--[URL_LAST]-->
function displayUrlLast( $arg )
{
  global $PAGE_LIST, $PAGE_ROW;

  if ( $PAGE_LIST[count($PAGE_LIST)-1] != null ) {
    return $_SERVER['PHP_SELF'].'?p='.$PAGE_LIST[count($PAGE_LIST)-1]->page_id;
  }
  else if ( $PAGE_LIST[count($PAGE_LIST)-2] != null ) {
    return $_SERVER['PHP_SELF'].'?p='.$PAGE_LIST[count($PAGE_LIST)-2]->page_id;
  }
  else {
    return $_SERVER['PHP_SELF'].'?p='.$PAGE_LIST[count($PAGE_LIST)-3]->page_id;
  }


  foreach( $PAGE_LIST as $possible )
  {
    if ( $possible && ($PAGE_ROW->page_id != $possible->page_id) ) {
      return $_SERVER['PHP_SELF'].'?p='.$possible->page_id;
    }
    return;
  }
}










// <!--[COMIC_NAME]-->
function displayComicName( $arg )
{
  global $COMIC_ROW;
  return $COMIC_ROW->comic_name;
}

// <!--[COMIC_NAME_IMAGE]-->
function displayComicNameImage( $arg )
{
  global $COMIC_ROW;

  $FOLDER_NAME = comicNameToFolder($COMIC_ROW->comic_name);

  if ( file_exists(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/gfx/comic_title.gif') ) {
    return '<a href="http://'.DOMAIN.'/'.$FOLDER_NAME.'/"><img src="http://'.COMIC_DOMAIN.'/'.$FOLDER_NAME.'/gfx/comic_title.gif" border="0"></a>';
  }
  if ( file_exists(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/gfx/comic_title.png') ) {
    return '<a href="http://'.DOMAIN.'/'.$FOLDER_NAME.'/"><img src="http://'.COMIC_DOMAIN.'/'.$FOLDER_NAME.'/gfx/comic_title.png" border="0"></a>';
  }
  if ( file_exists(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/gfx/comic_title.jpg') ) {
    return '<a href="http://'.DOMAIN.'/'.$FOLDER_NAME.'/"><img src="http://'.COMIC_DOMAIN.'/'.$FOLDER_NAME.'/gfx/comic_title.jpg" border="0"></a>';
  }
  if ( file_exists(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/gfx/comic_title.swf') )
  {
    $INFO = getimagesize(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/gfx/comic_title.swf');
    $swf = new FlashMovie('http://'.COMIC_DOMAIN.'/'.$FOLDER_NAME.'/gfx/comic_title.swf', $INFO[0], $INFO[1]);
    $swf->setTransparent(true);
    return $swf->getHTML();
  }

  return '';
}








// <!--[PAGE_TITLE]-->
function displayPageTitle( $arg )
{
  global $PAGE_ROW;
  return $PAGE_ROW->page_title;
}










// <!--[PAGE_DROPDOWN]-->
function displayPageDropDown( $arg )
{
  global $PAGE_ROW, $PAGE_LIST, $FOLDER_NAME;

  $ret  = '<script type="text/javascript" language="JavaScript">

				function jump(fe)
				{
					var opt_key = fe.selectedIndex;
					var url_val = fe.options[opt_key].value;
					window.open("/'.$FOLDER_NAME.'?p="+url_val,"_top");
					return true;
			 	}

			</script>';

  $ret .= "<SELECT NAME='p' STYLE='WIDTH:100%;' onChange=\"return jump(this);\">";
  foreach($PAGE_LIST as $row) {
    if ( $row ) {
      $PRE = '';
      $STYLE = '';
      if ( $row->is_chapter ) {
         $PRE = '&nbsp;&nbsp;&nbsp;- ';
         $STYLE = 'STYLE="background:#cecece;"';
      }

      $ret .= "<OPTION $STYLE VALUE='".$row->page_id."' ".(($row->page_id==$PAGE_ROW->page_id)?"SELECTED":"").">".$PRE.$row->page_title." ".((date("Ymd", $row->post_date)==date("Ymd"))?"[NEW!]":"")."</OPTION>";
    }
  }

  $ret .= "</SELECT>";
  return $ret;
}










// <!--[COMMENT_FORM=400,200]-->
function displayCommentForm( $arg )
{
  global $PAGE_ROW, $USER;

  if ( !$USER ) return "<DIV ALIGN='CENTER'><b>Only registered members may vote. Sign up <A HREF='http://".DOMAIN."/signup/'>here</a>!</b></div>";
  if ( !($USER->flags & FLAG_VERIFIED) ) return "<DIV ALIGN='CENTER'><b>Only verified members may vote. Click <A HREF='http://".DOMAIN."/resend_activation.phpp'>here</a> to have your activation resent!</b></div>";

  $MEASUREMENTS = split(',', $arg);

  if ( (count($MEASUREMENTS) == 2) ) {
    $DIMENSIONS = "WIDTH='".trim($MEASUREMENTS[0])."' HEIGHT='".trim($MEASUREMENTS[1])."'";
  }
  else {
    $DIMENSIONS = "WIDTH='400' HEIGHT='200'";
  }

  return "<FORM ACTION='".$_SERVER['PHP_SELF']."' METHOD='POST'>
  <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' $DIMENSIONS>
    <TR>
      <TD ALIGN='CENTER'>
        <B>Leave a comment?</B>
      </TD>
    </TR>
    <TR>
      <TD HEIGHT='100%'>
        <TEXTAREA NAME='comment' STYLE='WIDTH:100%;HEIGHT:100%;'></TEXTAREA>
      </TD>
    </TR>
    <TR>
      <TD ALIGN='CENTER'>
        ".(($USER)?"<SELECT NAME='vote_rating' STYLE='WIDTH:200px;'><OPTION>Rate this page?</OPTION><OPTION>5</OPTION><OPTION>4</OPTION><OPTION>3</OPTION><OPTION>2</OPTION><OPTION>1</OPTION></SELECT>":"<B>Logged in users can leave a rating!</DIV>")."
      </TD>
    </TR>
    <TR>
      <TD ALIGN='CENTER'>
        <INPUT TYPE='SUBMIT' VALUE='Say It!'> <INPUT TYPE='CHECKBOX' NAME='anonymous' VALUE='1'> Anonymous
      </TD>
    </TR>".
  (!($USER->flags & FLAG_VERIFIED)?"
    <TR>
      <TD ALIGN='CENTER'>
         <IMG SRC='http://".DOMAIN."/captcha.php' > Enter the code shown in the following text box:<INPUT TYPE='TEXT' NAME='ig'>
      </TD>
    </TR>":"")."
  </TABLE>
  <INPUT TYPE='HIDDEN' NAME='p' VALUE='".$PAGE_ROW->page_id."'>
  <INPUT TYPE='HIDDEN' NAME='com2' VALUE='".md5(md5($PAGE_ROW->comic_id.$PAGE_ROW->page_id))."'>
  </FORM>";
}










// <!--[AUTHORS_NOTES]-->
function displayAuthorsNotes( $arg )
{
  global $PAGE_ROW, $OWNER_ROW;

  $MEASUREMENTS = split(',', $arg);

  if ( (count($MEASUREMENTS) ) && ($MEASUREMENTS[0] >= 75) ) {
    $DIMENSIONS = "WIDTH='".trim($MEASUREMENTS[0])."'";
    if ( $MEASUREMENTS[1] ) {
      $DIMENSIONS .= " HEIGHT='".trim($MEASUREMENTS[1])."'";
    }
  }
  else {
    $DIMENSIONS = "WIDTH='400'";
  }

  if ( $PAGE_ROW->user_id != $OWNER_ROW )
  {
    $res    = db_query("SELECT user_id, username, avatar_ext FROM users WHERE user_id='".$PAGE_ROW->user_id."'");
    $POSTER = db_fetch_object($res);
    db_free_result($res);
  }
  else {
    $POSTER = $OWNER_ROW;
  }

  $ret = "<TABLE BORDER='0' CELLPADDING='5' CELLSPACING='0' $DIMENSIONS>
  <TR>
    <TD ALIGN='LEFT'>
      <a href=\"http://".USER_DOMAIN."/".$POSTER->username."\">".$POSTER->username."</a>
    </TD>
  </TR>
  <TR>
    <TD ALIGN='LEFT' HEIGHT='100%' VALIGN='TOP'>
      <div align='center' style='width:100px;height:100px;float:left;'><a href=\"http://".USER_DOMAIN."/".$POSTER->username."\"><IMG SRC='".IMAGE_HOST."/process/user_".$POSTER->user_id.".".$POSTER->avatar_ext."' border=\"0\" class=\"avatar_author\"></a><br><a href='http://".DOMAIN."/community/message/author.php?to=".$POSTER->username."'><img src='".IMAGE_HOST."/community_gfx/icon_sendpq.gif' border='0' alt='Send a private quack!' title='Send a private quack!'></a></div>".
        nl2br( BBCode($PAGE_ROW->comment) ) .
      "<div align=\"right\">
        -Posted on ".date("M d, Y", $PAGE_ROW->post_date)."
      </div>
    </TD>
  </TR>
  </TABLE>";

  return $ret;
}










// <!--[COMMENTS=400,200]-->
function displayComments( $arg )
{
  global $PAGE_ROW, $USER, $ALLOW_MODERATION;

  $MEASUREMENTS = split(',', $arg);

  if ( count($MEASUREMENTS) ) {
    $DIMENSIONS = "STYLE='WIDTH:".trim($MEASUREMENTS[0])."px;";
    if ( $MEASUREMENTS[1] )
    {
      $DIMENSIONS .= "HEIGHT:".trim($MEASUREMENTS[1])."px;OVERFLOW:AUTO;";
    }
    $DIMENSIONS .= "'";
  }
  else {
    $DIMENSIONS = "STYLE='WIDTH:400px'";
  }

  $res = db_query("SELECT * FROM page_comments WHERE page_id='".$PAGE_ROW->page_id."' ORDER BY post_date DESC");
  while ($row = db_fetch_object($res) )
  {
    $USER_IDS[] = $row->user_id;
    $COMMENTS[] = $row;
  }
  db_free_result($res);

  if ( COUNT($USER_IDS) )
  {
    $USER_IDS = array_unique($USER_IDS);

    $res = db_query("SELECT user_id, username, avatar_ext FROM users WHERE user_id IN ('".implode("','", $USER_IDS)."')");
    while ($row = db_fetch_object($res))
    {
      $USER_IDS[$row->user_id] = $row;
    }
    db_free_result($res);
  }

  foreach( $COMMENTS as $row )
  {
    $IP = '';
    if ( $USER->flags & FLAG_IS_ADMIN ) {
      $IP = "<DIV STYLE='FONT-SIZE:9px;COLOR:#FF0000;BACKGROUND:#FFFFFF;'>".$row->ip."</DIV>";
    }

    if ( !($row->flags & COMMENT_DELETED) )
    {
      $username = $USER_IDS[$row->user_id]->username;
      if ( !$username ) {
        $username = 'Not Logged In';
      }
      else if ( $row->flags & COMMENT_ANONYMOUS ) {
        if ( $USER->flags & FLAG_IS_ADMIN ) {
          $username = "Anonymous (<a href=\"http://".USER_DOMAIN."/".$username."\">".$username."</a>)";
        }
        else {
          $username = 'Anonymous';
        }
      }
      else {
        $username = "<a href=\"http://".USER_DOMAIN."/".$username."\">".$username."</a>";
      }

      if ( $row->flags & COMMENT_UNDER_REVIEW ) {
        $COMMENT = "<DIV ALIGN='CENTER' STYLE='WIDTH:100%;HEIGHT:100%;BACKGROUND:#FFFFFF;COLOR:#FF0000;'><B>UNDER ADMIN REVIEW</B></DIV>";
      }
      else if ( $row->flags & COMMENT_MUTED ) {
        $COMMENT = "<DIV ALIGN='CENTER' STYLE='WIDTH:100%;HEIGHT:100%;BACKGROUND:#FFFFFF;COLOR:#FF0000;'><B>MUTED</B></DIV>";
      }
      else {
        $COMMENT = nl2br( BBCode($row->comment) );
      }

      $ret .= "<P $DIMENSIONS>
                <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' WIDTH='100%' HEIGHT='100%'>
                  <TR>
                    <TD ALIGN='LEFT' WIDTH='50%'>
                      User: ".$username."
                    </TD>
                    <TD ALIGN='LEFT' WIDTH='50%'>";

      if ( $ALLOW_MODERATION )
      {
        if ( $row->flags & COMMENT_MUTED ) {
          $ret .= "<A HREF='".$_SERVER['PHP_SELF']."?p=".$PAGE_ROW->page_id."&unmute=".$row->comment_id."'>UnMute</A>";
        }
        else {
          $ret .= "<A HREF='".$_SERVER['PHP_SELF']."?p=".$PAGE_ROW->page_id."&mute=".$row->comment_id."'>Mute</A>";
        }

        if ( !($row->flags & COMMENT_APPROVED) && !($row->flags & COMMENT_UNDER_REVIEW) ) {
          $ret .= "| <A HREF='".$_SERVER['PHP_SELF']."?p=".$PAGE_ROW->page_id."&report=".$row->comment_id."'>Report BAD Comment</A>";
        }
      }

      $ret .=      "</TD>
                    <TD WIDTH='20' ALIGN='RIGHT'>
                      <B>".(($row->vote_rating)?$row->vote_rating:"")."</B>
                    </TD>
                  </TR>
                  <TR>
                    <TD ALIGN='LEFT' VALIGN='TOP' COLSPAN='3'>";
                      if ( !( $row->flags&COMMENT_ANONYMOUS) )
                      {
                        $ret .= "<div align='center' style='width:100px;height:100px;float:left;'><a href=\"http://".USER_DOMAIN."/".$USER_IDS[$row->user_id]->username."\"><IMG SRC='".IMAGE_HOST."/process/user_".$USER_IDS[$row->user_id]->user_id.".".$USER_IDS[$row->user_id]->avatar_ext."' border=\"0\" class=\"avatar_commentator\"></a><br><a href='http://".DOMAIN."/community/message/author.php?to=".$USER_IDS[$row->user_id]->username."'><img src='".IMAGE_HOST."/community_gfx/icon_sendpq.gif' border='0' alt='Send a private quack!' title='Send a private quack!'></a></div>";
                      }
                      else if ( ($row->user_id==0) || ($row->flags&COMMENT_ANONYMOUS)  ) {
                        $ret .= "<IMG SRC='".IMAGE_HOST."/site_gfx/anonymous.jpg' ALIGN='LEFT'>";
                      }

      $ret .=         $COMMENT.
                      "<div align=\"right\">
                        -Posted on ".date("M d, Y", $row->post_date)."
                      </div>".
                      $IP."
                    </TD>
                  </TR>
                </TABLE>
              </P>";
    }
  }
  return $ret;

}










// <!--[STAT_DASH]-->
function getStatDash($arg=false)
{
  global $COMIC_ROW;

  $ret = '<div style="background:url('.IMAGE_HOST.'/template_gfx_new/dash/dash_bg.png);width:600px;height:100px;"><div align="left" style="padding-left:55px;padding-top:20px;">';
  $embed = '';

  $res = db_query("SELECT COUNT(*) as total_comics FROM comics");
  $row = db_fetch_object($res);
  db_free_result($res);

  $TOTAL_COMICS = $row->total_comics;




  /* FAVORITES */
  $res = db_query("SELECT COUNT(*) as total_favs FROM comic_favs_tally");
  $row = db_fetch_object($res);
  db_free_result($res);

  $TOTAL_FAVS = $row->total_favs;

  $res = db_query("SELECT tally FROM comic_favs_tally WHERE comic_id='".$COMIC_ROW->comic_id."'");
  $row = db_fetch_object($res);
  db_free_result($res);
  $FAVS = $row->tally;

  $res = db_query("SELECT COUNT(*) as total_under FROM comic_favs_tally WHERE tally<'".$FAVS."'");
  $row = db_fetch_object($res);
  db_free_result($res);
  $UNDER = $row->total_under;

  $meter = round( $UNDER/$TOTAL_FAVS, 2 );


  $swf = new FlashMovie(IMAGE_HOST.'/template_gfx_new/dash/dash.swf', 57, 57);
  $swf->addVar('pinLevel', $meter);
  $swf->setTransparent(true);
  $swf->setScale(FLASH_SCALE_EXACTFIT);
  $embed .= $swf->getEmbedCode();
  /* END FAVORITES */



  $embed .= '<img src="'.IMAGE_HOST.'/site_gfx_new/spacer.gif" width="10" height="10">';



  /* ALL TIME VISITS */
  $res = db_query("SELECT COUNT(*) as total_below FROM comics WHERE visits<'".$COMIC_ROW->visits."'");
  $row = db_fetch_object($res);
  db_free_result($res);

  $meter = round($row->total_below / $TOTAL_COMICS, 2);

  $swf = new FlashMovie(IMAGE_HOST.'/template_gfx_new/dash/dash.swf', 57, 57);
  $swf->addVar('pinLevel', $meter);
  $swf->setTransparent(true);
  $swf->setScale(FLASH_SCALE_EXACTFIT);
  $embed .= $swf->getEmbedCode();
  /* END ALL TIME VISITS */



  $embed .= '<img src="'.IMAGE_HOST.'/site_gfx_new/spacer.gif" width="10" height="10">';



  /* SEVEN DAY VISITS */
  $res = db_query("SELECT COUNT(*) as total_below FROM comics WHERE seven_day_visits<'".$COMIC_ROW->seven_day_visits."'");
  $row = db_fetch_object($res);
  db_free_result($res);

  $BELOW = $row->total_below;

  $meter = round( $BELOW/$TOTAL_COMICS, 2 );

  $swf = new FlashMovie(IMAGE_HOST.'/template_gfx_new/dash/dash.swf', 70, 70);
  $swf->addVar('pinLevel', $meter);
  $swf->setTransparent(true);
  $swf->setScale(FLASH_SCALE_EXACTFIT);
  $embed .= $swf->getEmbedCode();
  /* END SEVEN DAY VISITS */



  $embed .= '<img src="'.IMAGE_HOST.'/site_gfx_new/spacer.gif" width="10" height="10">';



  /* SEVEN DAY GROWTH */
  $res = db_query("SELECT COUNT(*) as total_below FROM comics WHERE seven_day_growth<'".$COMIC_ROW->seven_day_growth."'");
  $row = db_fetch_object($res);
  $BELOW = $row->total_below;

  $meter = round($row->total_below/$TOTAL_COMICS, 2);

  $swf = new FlashMovie(IMAGE_HOST.'/template_gfx_new/dash/dash.swf', 70, 70);
  $swf->addVar('pinLevel', $meter);
  $swf->setTransparent(true);
  $swf->setScale(FLASH_SCALE_EXACTFIT);
  $embed .= $swf->getEmbedCode();
  /* END SEVEN DAY GROWTH */




  $ret .= $swf->getHTML($embed);


  $ret .= '</div></div>';

  return $ret;
}















































// <!--[LATEST_BLOG]-->
function getLatestBlog( $arg )
{
  global $COMIC_ROW, $PAGE_ROW, $OWNER_ROW;

  $MEASUREMENTS = split(',', $arg);

  $WIDTH = false;
  $HEIGHT = false;

  if ( (count($MEASUREMENTS) ) && ($MEASUREMENTS[0] >= 75) ) {
    $WIDTH = "width:".trim($MEASUREMENTS[0])."px;";
    if ( $MEASUREMENTS[1] ) {
      $HEIGHT = "height:".trim($MEASUREMENTS[1])."px;";
    }
  }

  if ( $WIDTH && $HEIGHT ) {
    $DIMENSIONS = 'style="'.$WIDTH.$HEIGHT.'"';
  }
  else {
    $DIMENSIONS = 'style="'.$WIDTH.'"';
  }



  // If they have no blog forum.
  if ( $COMIC_ROW->blog_forum_category_id == 0 )
  {
    // If the page user is the same as the owner of the comic.
    if ( $PAGE_ROW->user_id == $OWNER_ROW->user_id )
    {
      $POST_USER = $OWNER_ROW;
    }
    else
    {
      $res    = db_query("SELECT user_id, username, avatar_ext, flags FROM users WHERE user_id='".$PAGE_ROW->user_id."'");
      $POST_USER = db_fetch_object($res);
      db_free_result($res);
    }

    $res = db_query("SELECT COUNT(*) as total_comments FROM page_comments WHERE page_id='".$PAGE_ROW->page_id."'");
    $row = db_fetch_object($res);
    db_free_result($res);

    $POST_BODY        = $PAGE_ROW->comment;
    $POST_TOPIC       = $PAGE_ROW->page_title;
    $POST_DATE        = $PAGE_ROW->post_date;

    $POST_URL         = $_SERVER['PHP_SELF'].'?p='.$PAGE_ROW->page_id;
    $POST_COMMENT_CT  = $row->total_comments;
  }
  else
  {
    $res = db_query("SELECT * FROM community_topics WHERE category_id='".$COMIC_ROW->blog_forum_category_id."' ORDER BY topic_id DESC LIMIT 1");
    $tRow = db_fetch_object($res);
    db_free_result($res);

    $res = db_query("SELECT * FROM community_posts WHERE topic_id='".$tRow->topic_id."' ORDER BY post_id ASC LIMIT 1");
    $row = db_fetch_object($res);
    db_free_result($res);

    if ( $row->user_id != $OWNER_ROW->user_id )
    {
      $res    = db_query("SELECT user_id, username, avatar_ext, flags FROM users WHERE user_id='".$row->user_id."'");
      $POST_USER = db_fetch_object($res);
      db_free_result($res);
    }
    else
    {
      $POST_USER = $OWNER_ROW;
    }

    $POST_BODY    = $row->post_body;
    $POST_TOPIC   = $tRow->topic_name;
    $POST_DATE    = $tRow->date_created;

    $POST_URL     = 'http://'.DOMAIN.'/community/view_topic.php?tid='.$tRow->topic_id.'&cid='.$tRow->category_id.'&comic_id='.$COMIC_ROW->comic_id;

    $res = db_query("SELECT COUNT(*) as total_comments FROM community_posts WHERE topic_id='".$tRow->topic_id."'");
    $row = db_fetch_object($res);
    db_free_result($res);

    $POST_COMMENT_CT  = $row->total_comments-1;
  }





  ob_start();
  ?>
  <div class="blog" <?=$DIMENSIONS?>>
  	<h3 id="blog_title_1">Comic Blog</h3>
  	<div class="blog_hdr">
  	  <div align='center' style='width:100px;height:100px;float:left;' class='blog_img'>
  	   <a href="http://<?=USER_DOMAIN?>/<?=$POST_USER->username?>"><img src="<?=IMAGE_HOST?>/process/user_<?=$POST_USER->user_id?>.<?=$POST_USER->avatar_ext?>" border="0" class='blog_img'></a>
  	   <br>
  	   <a href='http://<?=DOMAIN?>/community/message/author.php?to=<?=$POST_USER->username?>'><img src='<?=IMAGE_HOST?>/community_gfx/icon_sendpq.gif' border='0' alt='Send a private quack!' title='Send a private quack!'></a>
  	  </div>
    	<div class="blog_title_2"><?=$POST_TOPIC?></div>
    	<div class="blog_author">Posted by <a href="http://<?=USER_DOMAIN?>/<?=$POST_USER->username?>"><?=$POST_USER->username?></a></div>
    	<div class="timestamp"> <?=date("M d, Y", $POST_DATE)?></div>
  	</div>
    <div class="blog_body">
      <?=nl2br( BBCode($POST_BODY) )?>
    </div>

    <div class="blog">
      <h4 id="blog_comment_hdr"><a href="<?=$POST_URL?>">Comments (<?=number_format($POST_COMMENT_CT)?>)</a></h4>
    </div>
  </div>
  <?
  return ob_get_clean();
}











// <!--[LATEST_BLOG_COMMENTS]-->
function getLatestBlogComments($arg)
{
  global $COMIC_ROW, $PAGE_ROW, $USER, $ALLOW_MODERATION;

  if ( $COMIC_ROW->blog_forum_category_id == 0 )
  {
    $res = db_query("SELECT COUNT(*) as total_comments FROM page_comments WHERE page_id='".$PAGE_ROW->page_id."'");
    $row = db_fetch_object($res);
    db_free_result($res);

    $url = $_SERVER['PHP_SELF'].'?p='.$PAGE_ROW->page_id;
  }
  else
  {
    $res = db_query("SELECT * FROM community_topics WHERE category_id='".$COMIC_ROW->blog_forum_category_id."' ORDER BY topic_id DESC LIMIT 1");
    $tRow = db_fetch_object($res);
    db_free_result($res);

    $res = db_query("SELECT COUNT(*) FROM community_posts WHERE topic_id='".$tRow->topic_id."' ORDER BY post_id ASC LIMIT 1");
    $row = db_fetch_object($res);
    db_free_result($res);

    $url = $_SERVER['PHP_SELF'].'?p='.$tRow->page_id;
  }
  $res = db_query("SELECT COUNT(*) as total_comments FROM page_comments WHERE page_id='".$PAGE_ROW->page_id."'");
  $row = db_fetch_object($res);
  db_free_result($res);

  ob_start();
  ?>
  <div class="blog">
    <h4 id="blog_comment_hdr"><a href="<?=$_SERVER['PHP_SELF']?>?p=<?=$PAGE_ROW->page_id?>">Read Comments (<?=number_format($row->total_comments)?>)</a></h4>
  </div>
  <?
  return ob_get_clean();
}

// <!--[LATEST_BLOG_COMMENT_FORM]-->
function getBlogCommentBox( $arg )
{
  global $PAGE_ROW, $USER;

  if ( !$USER ) return "<DIV ALIGN='CENTER'><b>Only registered members may vote. Sign up <A HREF='http://".DOMAIN."/signup/'>here</a>!</b></div>";

  $MEASUREMENTS = split(',', $arg);

  if ( (count($MEASUREMENTS) == 2) ) {
    $DIMENSIONS = "WIDTH='".trim($MEASUREMENTS[0])."' HEIGHT='".trim($MEASUREMENTS[1])."'";
  }
  else {
    $DIMENSIONS = "WIDTH='400' HEIGHT='200'";
  }

  ob_start();
  ?>
  <form action='<?=$_SERVER['PHP_SELF']?>' method='post'>
  <table border="0" align="center" cellpadding="2" cellspacing="4" class="comments" <?=$DIMENSIONS?>>
    <tr>
      <td align="center">
        <b>Comment on this post!</b>
      </td>
    </tr>
    <tr>
      <td height="100">
        <textarea name="comment" style="width: 100%; height: 100%;"></textarea>
      </td>
    </tr>
    <tr>
      <td align="center">
        <input value="Say It!" type="submit"> <input name="anonymous" value="1" type="checkbox"> Anonymous
      </td>
    </tr>
  </table>
  <input type='hidden' name='p' value='<?=$PAGE_ROW->page_id?>'>
  <input type='hidden' name='com2' value='<?=md5( md5( $PAGE_ROW->comic_id.$PAGE_ROW->page_id ) )?>'>
  </form>
  <?
  return ob_get_clean();
}


// <!--[DESCRIPTION]-->
function getDescription($arg)
{
  global $COMIC_ROW;
  return $COMIC_ROW->description_long;
}

// <!--[CHAPTERS]-->
function getChapterList($arg)
{
  global $COMIC_ROW;

  ob_start();
  ?>
  <ol>
  <?
  $res = db_query("SELECT * FROM comic_pages WHERE comic_id='".$COMIC_ROW->comic_id."' AND is_chapter='1' ORDER BY order_id ASC");
  while($row = db_fetch_object($res) )
  {
    ?><li><a href="<?=$_SERVER['PHP_SELF']?>?p=<?=$row->page_id?>"><?=( (strlen($row->page_title)==0)?"*No Title*":htmlentities($row->page_title, ENT_QUOTES) )?></a></li><?
  }
  ?>
  </ol>
  <?
  return ob_get_clean();
}


// <!--[COMIC_INFO]-->
function getComicInfo($arg)
{
  global $COMIC_ROW;
  ob_start();

  if ( $COMIC_ROW->created_timestamp == 0 )
  {
    $res = db_query("SELECT * FROM comic_pages WHERE comic_id='".$COMIC_ROW->comic_id."' ORDER BY post_date ASC LIMIT 1");
    if( $row = db_fetch_object($res) )
    {
      $COMIC_ROW->created_timestamp = $row->post_date;
      db_query("UPDATE comics SET created_timestamp='".$row->post_date."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
    }
  }

  global $COMIC_ROW;
  $ret = '';
  $res = db_query("SELECT * FROM credits WHERE comic_id='".$COMIC_ROW->comic_id."' ORDER BY credit_id ASC");
  while($row = db_fetch_object($res) ) {
    $ret .= '<p><strong>'.$row->credit_name.':</strong> '.$row->credit_value.'</p>';
  }
  return $ret;

  ?>
  <p><strong>Started:</strong> <?=date("F j, Y", $COMIC_ROW->created_timestamp)?></p>
  <p><strong>Number of strips:</strong> <?=number_format($COMIC_ROW->total_pages)?></p>
  <!--<p><strong>Update Schedule:</strong> 7 days a week, AM</p>-->
  <!--<p><strong>Most Recent Strip:</strong> Today, 12:01 AM PST</p>-->
  <?
  return ob_get_clean();
}



// <!--[RECOMMENDS]-->
function getRecommends($arg)
{
  global $OWNER_ROW;
  ob_start();

  $thumb = false;
  if ( $arg == 'thumbnail' ) {
    $thumb = true;
  }

  ?><ul><?
  $res = db_query("SELECT * FROM comic_favs WHERE user_id='".$OWNER_ROW->user_id."' AND recommend='1'");
  while($row = db_fetch_object($res) )
  {
    $COMIC_IDS[$row->comic_id] = $row->comic_id;
  }
  db_free_result($res);

  $res = db_query("SELECT comic_id, comic_name, category, last_update FROM comics WHERE comic_id IN ('".implode("','", array_keys($COMIC_IDS))."')");
  while($row = db_fetch_object($res) )
  {
    $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name).'/';

    if ( !$thumb ) {
      ?><li><a href="<?=$url?>"><img src="<?=IMAGE_HOST?>/site_gfx_new/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" title="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" border="0"> <?=$row->comic_name.((date("Ymd",$row->last_update)==YMD)?" *":"")?></a></li><?
    }
    else {
      ?><a href="<?=$url?>"><?=get_current_thumbnail($row->comic_id, $row->comic_name)?></a><?
    }
  }
  ?></ul><?
  return ob_get_clean();
}
?>