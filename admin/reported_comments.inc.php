<?
include_once('reported_comments_data.inc.php');


$ITEMS_PER_PAGE = 10;
$P = (int)$_GET['p']-1;
if ( $P<0 ) $P = 0;


if ( isset($_GET['handled']) ) 
{
  db_query("UPDATE comment_reports SET handled='".(int)$_GET['handled']."' WHERE comment_id='".(int)$_GET['comment_id']."'");
  
  if ( $_GET['handled'] == COMMENT_BAD ) {
    if ( $_GET['warn'] ) {
      $warn = (int)$_GET['warn'];
      db_query("UPDATE users SET warning=warning+".$warn." WHERE username='".db_escape_string($_GET['u'])."'");
    }
    bad_comment($_GET['comment_id']);
  }
  else if ( $_GET['handled'] == COMMENT_OKAY ) {
    okay_comment($_GET['comment_id']);
  }
  else {
    undo_handle($_GET['comment_id']);
  }
}
else if ( isset($_GET['super_clean']) )
{
  $UID = (int)$_GET['super_clean'];
  
  db_query("DELETE FROM comment_reports WHERE reported_user_id='".$UID."'");
  db_query("DELETE FROM page_comments WHERE user_id='".$UID."'");
}

  $WHERE = "WHERE handled=0";
if ( $_GET['handled_code'] ) {
  $WHERE = "WHERE handled=".(int)$_GET['handled_code'];
}

$res   = db_query("SELECT COUNT(*) as total_reports FROM comment_reports $WHERE");
$row   = db_fetch_object($res);
$TOTAL = $row->total_reports;
?>

<SPAN CLASS='headline'>Comment Reports</SPAN>

<DIV ALIGN='CENTER'>
  <A HREF='<?=$_SERVER['PHP_SELF']?>'>Unhandled Reports</A>
  |
  <A HREF='<?=$_SERVER['PHP_SELF']?>?handled_code=<?=COMMENT_OKAY?>'>Handled Reports OKAY</A>
  |
  <A HREF='<?=$_SERVER['PHP_SELF']?>?handled_code=<?=COMMENT_BAD?>'>Handled Reports BAD</A>
</DIV>

<DIV STYLE='WIDTH:800px;' CLASS='container' ALIGN='CENTER'>
  <DIV CLASS='header' ALIGN='CENTER'>
  <?
  if ( $_GET['handled_code']==COMMENT_OKAY ) {
    echo "OKAY'ed Comments";
  }
  else if ( $_GET['handled_code']==COMMENT_BAD ) {
    echo "BAD'ed Comments";
  }
  else {
    echo "Unhandled Reports";
  }
  ?>
  </DIV>
  
  
  <TABLE BORDER='0' CELLPADDING='0' WIDTH='80%' CELLSPACING='0'>
    <TR>
      <TD ALIGN='LEFT' WIDTH='25%'>
        <?
        if ( $P>0 ) {
          echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".$P."&handled_code=".(int)$_GET['handled_code']."'>Previous ".$ITEMS_PER_PAGE."</A>";
        }
        ?>
      </TD>
      <TD ALIGN='CENTER' WIDTH='50%'>
        p.<?=($P+1)?>/<?=number_format(ceil($TOTAL/$ITEMS_PER_PAGE))?>
      </TD>
      <TD ALIGN='RIGHT' WIDTH='25%'>
        <?
        if ( ($P+2)<=ceil($TOTAL/$ITEMS_PER_PAGE) ) {
          echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+2)."&handled_code=".(int)$_GET['handled_code']."'>Next ".$ITEMS_PER_PAGE."</A>";
        }
        ?>
      </TD>
    </TR>
  </TABLE>
  
  <TABLE BORDER='0' WIDTH='100%' CELLPADDING='5' CELLSPACING='0'>
    <TR>
      <TD ALIGN='LEFT' WIDTH='75%'>
        <B>Comment</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='25%'>
        <B>Action</B>
      <TD>
    </TR>
  <?php

  $res = db_query("SELECT * FROM comment_reports $WHERE ORDER BY comment_id ".(($_GET['handled_code']==0)?"ASC":"DESC")." LIMIT ".($P*$ITEMS_PER_PAGE).",".$ITEMS_PER_PAGE);
  while($row = db_fetch_object($res))
  {
    $REPORTS[$row->comment_id] = $row;
  }
  db_free_result($res);
  
  $res = db_query("SELECT * FROM page_comments WHERE comment_id IN ('".implode("','", array_keys($REPORTS))."')");
  while ($row = db_fetch_object($res) ) {
    $COMMENTS[$row->comment_id] = $row;
    $USER_IDS[$row->user_id] = $row->user_id;
    $COMIC_IDS[$row->comic_id] = $row->comic_id;
  }
  db_free_result($res);
  
  $res = db_query("SELECT * FROM users WHERE user_id IN ('".implode("','", $USER_IDS)."')");
  while($row = db_fetch_object($res))
  {
    $USER_IDS[$row->user_id] = $row;
  }
  db_free_result($res);
  
  $res = db_query("SELECT * FROM comics WHERE comic_id IN ('".implode("','", array_keys($COMIC_IDS))."')");
  while($row = db_fetch_object($res) ) {
    $COMICS[$row->comic_id] = $row;
  }
  
  foreach($REPORTS as $comment_id=>$report_row)
//  foreach($COMMENTS as $comment_id=>$row)
  {
    $row = $COMMENTS[$comment_id];
    $BG = "#FFFFFF";
    if ( ++$ct%2 == 0 ) {
      $BG = "#CDECDE";
    }
    
    echo "<TR BGCOLOR='$BG' onMouseOver=\"this.style.background='#EDCEDC';\" onMouseOut=\"this.style.background='$BG';\">
            <TD ALIGN='LEFT'>";
    
    echo "Comic: ".comic_name( $COMICS[$row->comic_id], $row->page_id )."<BR>";
    
    echo "    <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' WIDTH='100%' HEIGHT='100%'>
                <TR>
                  <TD ALIGN='LEFT' WIDTH='50%'>
                    User: ".username( ($USER_IDS[$row->user_id])?$USER_IDS[$row->user_id]->username:"Unknown")."
                  </TD>
                  <TD ALIGN='LEFT' WIDTH='50%'>
    
                  </TD>
                  <TD WIDTH='20' ALIGN='RIGHT'>
                    <B>".(($row->vote_rating)?$row->vote_rating:"")."</B>
                  </TD>
                </TR>
                <TR>
                  <TD ALIGN='LEFT' VALIGN='TOP' COLSPAN='3'>";
    
                    if ( !( $row->flags&COMMENT_ANONYMOUS) && $USER_IDS[$row->user_id]->avatar_ext )
                    {
                      if ( $USER_IDS[$row->user_id]->avatar_ext == 'swf' ) {
                        
                        $INFO = getimagesize(WWW_ROOT.'/gfx/avatars/avatar_'.$row->user_id.'.swf');
                        echo get_flash_movie(IMAGE_HOST.'/avatars/avatar_'.$row->user_id.'.swf', $INFO[0], $INFO[1]);
                      }
                      else { 
                        echo "<IMG SRC='".IMAGE_HOST."/avatars/avatar_".$row->user_id.".".$USER_IDS[$row->user_id]->avatar_ext."' ALIGN='LEFT'>";
                      }
                    }
                    else if ( ($row->user_id==0) || ($row->flags&COMMENT_ANONYMOUS)  ) {
                      echo "<IMG SRC='".IMAGE_HOST."/site_gfx/anonymous.jpg' ALIGN='LEFT'>";
                    }
    echo nl2br( BBCode($row->comment) );
    echo "        </TD>
                </TR>
              </TABLE>";
    
    
    
    
    echo   "</TD>
            <TD ALIGN='LEFT'>";

    if ( $REPORTS[$row->comment_id]->handled == COMMENT_OKAY ) {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?handled=0&comment_id=".$row->comment_id."'>NOT Okay</A>";
    }
    else if ( $REPORTS[$row->comment_id]->handled == COMMENT_BAD ) {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?handled=0&comment_id=".$row->comment_id."'>NOT Bad</A>";
    }
    else {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?handled=".COMMENT_OKAY."&comment_id=".$comment_id."'>Okay</A>";
      echo " | ";
      echo "<A HREF='".$_SERVER['PHP_SELF']."?handled=".COMMENT_BAD."&comment_id=".$comment_id."'>Bad</A>";
      echo " | ";
      echo "<A HREF='".$_SERVER['PHP_SELF']."?handled=".COMMENT_BAD."&comment_id=".$comment_id."&u=".$USER_IDS[$row->user_id]->username."&warn=10'>Bad+Rude</A>";
      echo " | ";
      echo "<A HREF='".$_SERVER['PHP_SELF']."?handled=".COMMENT_BAD."&comment_id=".$comment_id."&u=".$USER_IDS[$row->user_id]->username."&warn=20'>Bad+Snipe</A>";
      
      echo "<hr STYLE='border:1px solid black;'>";
      
      if ( $row->user_id )
      {
        echo "<A HREF='".$_SERVER['PHP_SELF']."?super_clean=".$row->user_id."' onClick=\"return confirm('Clear out all comments this user has ever made???');\">Super-Clean Username</A>";
      }
    }

    
    
    echo "</TD>
          </TR>";
  }
  ?>
  </TABLE>
  
</DIV>