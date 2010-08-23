<?
  if ( isset($_POST['my_username']) && isset($_POST['my_email']) )
  {
    $res = db_query("SELECT * FROM users WHERE username='".db_escape_string($_POST['my_username'])."'");
    if ( !$user_row = db_fetch_object($res) )
    {
      echo "<P>The username you supplied was invalid.</P>";
    }
    else 
    {
      db_free_result($res);
      $res = db_query("SELECT * FROM demographics WHERE user_id='".$user_row->user_id."'");
      $row = db_fetch_object($res);
      if ( (strtolower( $row->email ) == strtolower($_POST['my_email'])) || ( md5(strtolower($_POST['my_email'])) == $row->email ) )
      {
        // resend email.
        include_once(WWW_ROOT.'/signup/signup_func.inc.php');
        
        sendActivationEmail($user_row->username, $row->first_name, ( ($user_row->flags & FLAG_OVER_12)?2:1), $_POST['my_email']);
        echo "<P>Your information has been resent! Please check your email.</P>";
        return;
      }
      else {
        echo "<P>Your email address did not match. Try again.</P>";
      }
    }
  } 
?>

In order for us to resend your information or confirmation email, you must first let us know your email address:

<FORM ACTION='<?=$_SERVER['PHP_SELF']?>' METHOD='POST'>
<DIV STYLE='WIDTH:350px;HEIGHT:100px;' CLASS='container' ALIGN='LEFT'>
  <DIV CLASS='header' ALIGN='CENTER'>Resend Info:</DIV>
  <TABLE BORDER='0' CELLPADDING='0' WIDTH='100%' CELLSPACING='0'>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'><B>Username</B></TD>
      <TD ALIGN='LEFT' WIDTH='70%'><INPUT TYPE='TEXT' NAME='my_username' VALUE='<?=$USER->username?>' STYLE='width:100%'></TD>
    </TR>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'><B>Email Address</B></TD>
      <TD ALIGN='LEFT' WIDTH='70%'><INPUT TYPE='TEXT' NAME='my_email' STYLE='width:100%'></TD>
    </TR>
    <TR>
      <TD ALIGN='CENTER' COLSPAN='2'><INPUT TYPE='SUBMIT' VALUE='Go!'></TD>
    </TR>
  </TABLE>
</DIV>
</FORM>