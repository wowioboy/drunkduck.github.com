<?
if ( is_numeric($_GET['u']) ) {
  $res = db_query("SELECT * FROM users WHERE user_id='".(int)$_GET['u']."'");
}
else if ( isset($_GET['u']) ) {
  $res = db_query("SELECT * FROM users WHERE username='".db_escape_string($_GET['u'])."'");
}
if ( !$USER_ROW = db_fetch_object($res) ) return;

if ( isset($_GET['add']) ) {
  $USER_ROW->warning += (int)$_GET['add'];
  db_query("UPDATE users SET warning=".$USER_ROW->warning." WHERE user_id='".$USER_ROW->user_id."'");
  header("Location: http://".DOMAIN."/admin/warn_user.php?u=".$_GET['u']);
  return;
}
if ( isset($_GET['rem']) ) {
  $USER_ROW->warning -= (int)$_GET['rem'];
  if ( $USER_ROW->warning < 0 ) $USER_ROW->warning = 0;
  
  db_query("UPDATE users SET warning=".$USER_ROW->warning." WHERE user_id='".$USER_ROW->user_id."'");
  header("Location: http://".DOMAIN."/admin/warn_user.php?u=".$_GET['u']);
  return;
}

?>
<DIV STYLE='WIDTH:400px;' ALIGN='CENTER' CLASS='container'>
  <DIV CLASS='header' ALIGN='CENTER'>Warning "<?=$USER_ROW->username?>":</DIV>
  
  <TABLE BORDER='1' WIDTH='100%' CELLPADDING='0' CELLSPACING='0'>
    <TR>
      <TD ALIGN='LEFT'>
        <B>Current Warning:</B>
      </TD>
      <TD ALIGN='CENTER'>
        <?=$USER_ROW->warning?>%
      </TD>
    </TR>
    
    <TR>
      <TD ALIGN='LEFT'>
        <B>Add Warning:</B>
      </TD>
      <TD ALIGN='CENTER'>
        <A HREF='<?=$_SERVER['PHP_SELF']?>?u=<?=$_GET['u']?>&add=1'>1%</A> 
        | 
        <A HREF='<?=$_SERVER['PHP_SELF']?>?u=<?=$_GET['u']?>&add=5'>5%</A>
        |
        <A HREF='<?=$_SERVER['PHP_SELF']?>?u=<?=$_GET['u']?>&add=25'>25%</A>
        |
        <A HREF='<?=$_SERVER['PHP_SELF']?>?u=<?=$_GET['u']?>&add=50'>50%</A>
        |
        <A HREF='<?=$_SERVER['PHP_SELF']?>?u=<?=$_GET['u']?>&add=100'>100%</A>
      </TD>
    </TR>
    
    <TR>
      <TD ALIGN='LEFT'>
        <B>Remove Warning:</B>
      </TD>
      <TD ALIGN='CENTER'>
        <A HREF='<?=$_SERVER['PHP_SELF']?>?u=<?=$_GET['u']?>&rem=1'>1%</A> 
        | 
        <A HREF='<?=$_SERVER['PHP_SELF']?>?u=<?=$_GET['u']?>&rem=5'>5%</A>
        |
        <A HREF='<?=$_SERVER['PHP_SELF']?>?u=<?=$_GET['u']?>&rem=25'>25%</A>
        |
        <A HREF='<?=$_SERVER['PHP_SELF']?>?u=<?=$_GET['u']?>&rem=50'>50%</A>
        |
        <A HREF='<?=$_SERVER['PHP_SELF']?>?u=<?=$_GET['u']?>&rem=100'>100%</A>
      </TD>
    </TR>
  </TABLE>
  
</DIV>