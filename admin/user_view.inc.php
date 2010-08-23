<?
$NAMES_PER_PAGE = 200;
$P = (int)$_GET['p']-1;
if ( $P<0 ) $P = 0;


if ( isset($_GET['addflag']) )
{
  $res = db_query("SELECT flags FROM users WHERE user_id='".(int)$_GET['user_id']."'");
  if ( $row = db_fetch_object($res)) {
    db_query("UPDATE users SET flags='".($row->flags | (int)$_GET['addflag'])."' WHERE user_id='".(int)$_GET['user_id']."'");
  }
}
else if ( isset($_GET['remflag']) )
{
  $res = db_query("SELECT flags FROM users WHERE user_id='".(int)$_GET['user_id']."'");
  if ( $row = db_fetch_object($res)) {
    db_query("UPDATE users SET flags='".($row->flags & ~((int)$_GET['remflag']))."' WHERE user_id='".(int)$_GET['user_id']."'");
  }
}


$WHERE = '';
if ( $_GET['showflag'] ) {
  $WHERE = "WHERE (flags&".(int)$_GET['showflag'].")";
}
else if ( $_GET['ipSearch'] ) {
  $WHERE = "WHERE ip LIKE '%".db_escape_string( trim($_GET['ipSearch']) )."%'";
}
else if ( $_GET['userSearch'] ) {
  $WHERE = "WHERE username='".db_escape_string( trim($_GET['userSearch']) )."' OR username LIKE '%".db_escape_string( trim($_GET['userSearch']) )."%'";
}

$res   = db_query("SELECT COUNT(*) as total_users FROM users $WHERE");
$row   = db_fetch_object($res);
$TOTAL = $row->total_users;
?>

<SPAN CLASS='headline'>User List</SPAN>

<DIV STYLE='WIDTH:400px;' CLASS='container' ALIGN='CENTER'>
  <DIV CLASS='header' ALIGN='CENTER'>Search for IP Address</DIV>
<FORM ACTION='<?=$_SERVER['PHP_SELF']?>' METHOD='GET'>
    <INPUT TYPE="TEXT" NAME="ipSearch"> <INPUT TYPE='SUBMIT' VALUE='Search!'>

</FORM>
<BR>
<FORM ACTION='<?=$_SERVER['PHP_SELF']?>' METHOD='GET'>

  <DIV CLASS='header' ALIGN='CENTER'>Search for User</DIV>
  <INPUT TYPE="TEXT" NAME="userSearch"> <INPUT TYPE='SUBMIT' VALUE='Search!'>
</DIV>
</FORM>

<DIV ALIGN='CENTER'>
  <A HREF='<?=$_SERVER['PHP_SELF']?>'>All Users</A>
  |
  <A HREF='<?=$_SERVER['PHP_SELF']?>?showflag=<?=FLAG_IS_ADMIN?>'>Admin Users</A>
  |
  <A HREF='<?=$_SERVER['PHP_SELF']?>?showflag=<?=FLAG_IS_MOD?>'>Moderator Users</A>
  |
  <A HREF='<?=$_SERVER['PHP_SELF']?>?showflag=<?=FLAG_EDITOR?>'>Editor Users</A>
  |
  <A HREF='<?=$_SERVER['PHP_SELF']?>?showflag=<?=FLAG_EXECUTIVE?>'>Executive Users</A>
  |
  <A HREF='<?=$_SERVER['PHP_SELF']?>?showflag=<?=FLAG_FROZEN?>'>Frozen Users</A>
  |
  <A HREF='<?=$_SERVER['PHP_SELF']?>?showflag=<?=FLAG_BANNED_PC?>'>BANNED Users</A>
  |
  <A HREF='<?=$_SERVER['PHP_SELF']?>?showflag=<?=FLAG_IS_INTERNAL?>'>Internal Users</A>
  |
  <A HREF='<?=$_SERVER['PHP_SELF']?>?showflag=<?=FLAG_FORUM_BAN?>'>Forum Ban</A>
  |
  <A HREF='<?=$_SERVER['PHP_SELF']?>?showflag=<?=FLAG_NO_SENDING_PQ?>'>Ban from Sending PQ's</A>
</DIV>

<BR>

<DIV STYLE='WIDTH:1100px;' CLASS='container' ALIGN='CENTER'>
  <DIV CLASS='header' ALIGN='CENTER'>
  <?
  if ( $_GET['showfrozen'] ) {
    echo "Frozen Users";
  }
  else {
    echo "All Users";
  }
  ?>
  </DIV>


  <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0'>
    <TR>
      <TD ALIGN='LEFT' WIDTH='25%'>
        <?
        if ( $P>0 ) {
          echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".$P."'>Previous ".$NAMES_PER_PAGE."</A>";
        }
        ?>
      </TD>
      <TD ALIGN='CENTER' WIDTH='50%'>
        p.<?=($P+1)?>/<?=number_format(ceil($TOTAL/$NAMES_PER_PAGE))?>
      </TD>
      <TD ALIGN='RIGHT' WIDTH='25%'>
        <?
        if ( ($P+2)<=ceil($TOTAL/$NAMES_PER_PAGE) ) {
          echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+2)."'>Next ".$NAMES_PER_PAGE."</A>";
        }
        ?>
      </TD>
  </TABLE>

  <TABLE BORDER='0' WIDTH='100%' CELLPADDING='0' CELLSPACING='0'>
    <TR>
      <TD ALIGN='LEFT'>
        <B>Username</B>
      </TD>
      <TD>
        <B>Verified</B>
      </TD>
      <TD>
        <B>IP Address</B>
      </TD>
      <TD>
        <B>Email</B>
      </TD>
      <TD>
        <B>Last Seen</B>
      </TD>
      <TD ALIGN='LEFT'>
        <B>Flags</B>
      <TD>
    </TR>
  <?php
  $userArr = array();
  $res = db_query("SELECT * FROM users $WHERE ORDER BY username ASC LIMIT ".($P*$NAMES_PER_PAGE).",".$NAMES_PER_PAGE);
  while($row = db_fetch_object($res))
  {
    $userArr[$row->user_id] = $row;
  }
  db_free_result($res);

  $DEMO = array();
  $res = db_query("SELECT * FROM demographics WHERE user_id IN ('".implode("','", array_keys($userArr))."')");
  while($row = db_fetch_object($res))
  {
    $DEMO[$row->user_id] = $row;
  }

  foreach($userArr as $row)
  {
    $BG = "#FFFFFF";
    if ( ++$ct%2 == 0 ) {
      $BG = "#CDECDE";
    }

    echo "<TR BGCOLOR='$BG' onMouseOver=\"this.style.background='#EDCEDC';\" onMouseOut=\"this.style.background='$BG';\">
            <TD ALIGN='LEFT'>".username($row)."</TD>
            <TD>";
    if ( $row->flags & FLAG_VERIFIED ) {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&remflag=".FLAG_VERIFIED."'><FONT COLOR='green'>Verified</FONT></A>";
    }
    else {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&addflag=".FLAG_VERIFIED."'><FONT COLOR='purple'>Not Verified</FONT></A>";
    }

    echo    "</TD>
            <TD>
              ".$row->ip."
            </TD>
            <TD>".$DEMO[$row->user_id]->email."</TD>
            <TD>".date("m-d-Y", $row->last_seen)."</TD>

            <TD ALIGN='LEFT'>";

    if ( $row->flags & FLAG_IS_ADMIN ) {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&remflag=".FLAG_IS_ADMIN."'><FONT COLOR='red'>Admin</FONT></A>";
    }
    else {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&addflag=".FLAG_IS_ADMIN."'>Admin</A>";
    }

    echo " | ";

    if ( $row->flags & FLAG_IS_MOD ) {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&remflag=".FLAG_IS_MOD."'><FONT COLOR='red'>Moderator</FONT></A>";
    }
    else {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&addflag=".FLAG_IS_MOD."'>Moderator</A>";
    }

    echo " | ";

    if ( $row->flags & FLAG_EDITOR ) {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&remflag=".FLAG_EDITOR."'><FONT COLOR='red'>Editor</FONT></A>";
    }
    else {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&addflag=".FLAG_EDITOR."'>Editor</A>";
    }

    echo " | ";

    if ( $row->flags & FLAG_EXECUTIVE ) {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&remflag=".FLAG_EXECUTIVE."'><FONT COLOR='red'>Executive</FONT></A>";
    }
    else {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&addflag=".FLAG_EXECUTIVE."'>Executive</A>";
    }

    echo " | ";

    if ( $row->flags & FLAG_IS_INTERNAL ) {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&remflag=".FLAG_IS_INTERNAL."'><FONT COLOR='red'>Internal User</FONT></A>";
    }
    else {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&addflag=".FLAG_IS_INTERNAL."'>Internal User</A>";
    }

    echo " | ";

    if ( $row->flags & FLAG_OVER_12 ) {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&remflag=".FLAG_OVER_12."'><FONT COLOR='red'>No COPPA Restriction</FONT></A>";
    }
    else {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&addflag=".FLAG_OVER_12."'>COPPA Restricted</A>";
    }

    echo " | ";

    if ( $row->flags & FLAG_FORUM_BAN ) {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&remflag=".FLAG_FORUM_BAN."'><FONT COLOR='red'>Forum Ban</FONT></A>";
    }
    else {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&addflag=".FLAG_FORUM_BAN."'>Forum Ban</A>";
    }

    echo " | ";

    if ( $row->flags & FLAG_NO_SENDING_PQ ) {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&remflag=".FLAG_NO_SENDING_PQ."'><FONT COLOR='red'>No Sending PQ's</FONT></A>";
    }
    else {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&addflag=".FLAG_NO_SENDING_PQ."'>No Sending PQ's</A>";
    }

    echo " || ";

    if ( $row->flags & FLAG_FROZEN ) {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&remflag=".FLAG_FROZEN."'><FONT COLOR='red'>Frozen</FONT></A>";
    }
    else {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&addflag=".FLAG_FROZEN."'>Frozen</A>";
    }

    echo " | ";

    if ( $row->flags & FLAG_BANNED_PC ) {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&remflag=".FLAG_BANNED_PC."'><FONT COLOR='red'>Ban PC</FONT></A>";
    }
    else {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&user_id=".$row->user_id."&addflag=".FLAG_BANNED_PC."'>Ban PC</A>";
    }




    echo "</TD>
          </TR>";
  }
  ?>
  </TABLE>

</DIV>