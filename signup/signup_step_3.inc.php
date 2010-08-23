<?
require_once('signup_data.inc.php');
require_once('signup_func.inc.php');
require_once(PACKAGES.'/wordfilter_package/load.inc.php');

if ( $USER ) {
  echo "<DIV ALIGN='CENTER'>Oops! You are already logged in!</DIV>";
  return;
}

if ( !isset($_POST['ageGroup']) || (($_POST['ageGroup'] != 1) && ($_POST['ageGroup'] != 2)) ) {
  header('Location: /signup/index.php');
}

if ( isset($_POST['uname']) ) $_POST['uname'] = trim($_POST['uname']);

if ( !isset($_POST['uname']) || preg_match('/([^a-zA-Z0-9_ ])+/', $_POST['uname']) ) {
  echo "<DIV ALIGN='CENTER'>Oops! Your username had invliad characters! <A HREF='JavaScript:history.back();'>Click here to go back and re-enter it.</A></DIV>";
  return;  
}
else if ( strlen($_POST['uname']) < 3 ) {
  echo "<DIV ALIGN='CENTER'>Oops! Your username wasn't long enough! <A HREF='JavaScript:history.back();'>Click here to go back and re-enter it.</A></DIV>";
  return;  
}
else if ( username_taken($_POST['uname']) ) {
  echo "<DIV ALIGN='CENTER'>Oops! That username is already taken! <A HREF='JavaScript:history.back();'>Click here to go back and try a new one.</A></DIV>";
  return;  
}

if ( !isset($_POST['pw']) ) {
  echo "<DIV ALIGN='CENTER'>Oops! For some reason your password didn't stick! <A HREF='JavaScript:history.back();'>Click here to go back and re-enter it.</A></DIV>";
  return;  
}
else if ( $_POST['pw'] != $_POST['pw_verify'] ) {
  echo "<DIV ALIGN='CENTER'>Oops! Your two password entries did not match! <A HREF='JavaScript:history.back();'>Click here to go back and re-enter it.</A></DIV>";
  return;  
}
else if ( strlen($_POST['pw']) < 6 ) {
  echo "<DIV ALIGN='CENTER'>Oops! Your password wasn't long enough! <A HREF='JavaScript:history.back();'>Click here to go back and re-enter it.</A></DIV>";
  return;  
}

if ( !isset($_POST['gender']) || ($_POST['gender'] != 'm' && $_POST['gender'] != 'f') ) {
  echo "<DIV ALIGN='CENTER'>Oops! Your selection for gender had a problem! <A HREF='JavaScript:history.back();'>Click here to go back and re-enter it.</A></DIV>";
  return;  
}

if ( !$_POST['iAgree'] ) {
  echo "<DIV ALIGN='CENTER'>Oops! You never agreed to our TERMS OF SERVICE! <A HREF='JavaScript:history.back();'>Click here to go back, read, and accept it.</A></DIV>";
  return;  
}

?>

<script type="text/javascript">
function formIsValid( frm )
{
  <?
  if ( $_POST['ageGroup'] != 1 )
  {
    ?>
    if ( frm.first_name.value.length < 2 ) {
      alert('Please enter a first name.');
    }
    if ( frm.last_name.value.length < 2 ) {
      alert('Please enter a first name.');
    }
    <?
  }
  ?>
  
	var emailFilter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	
  if ( !emailFilter.test( frm.email_addy.value ) ) {
    alert("Your email address doesn't seem to be valid.");
    return false;
  }
  if ( frm.email_addy.value != frm.email_addy_confirm.value ) {
    alert("Your email address entries did not match!");
    return false;
  }
  
  if ( frm.zipcode.value.length < 4 ) {
    alert("Please enter a zip code.");
    return false;
  }
  
  
  return true;
}
</script>

<FORM ACTION='signup_step_4.php' METHOD='POST' onSubmit="return formIsValid(this);">
<TABLE BORDER='0' CELLPADDING='5' CELLSPACING='0' WIDTH='400'>

<? 
if ( $_POST['ageGroup'] != 1 )
{
  ?>
  <TR>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <B>First Name</B>
    </TD>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <INPUT TYPE='TEXT' NAME='first_name' STYLE='WIDTH:100%;'>
    </TD>
  </TR>

  <TR>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <B>Last Name</B>
    </TD>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <INPUT TYPE='TEXT' NAME='last_name' STYLE='WIDTH:100%;'>
    </TD>
  </TR>
  <?
}


if ( $_POST['ageGroup'] == 1 ) 
{
  ?>
  <TR>
    <TD ALIGN='LEFT' WIDTH='100%' COLSPAN='2'>
      <B>NOTE:</B> <I>In compliance with <A HREF='http://coppa.org' TARGET='_blank'>COPPA</A>, since you are under 13 we WILL NOT keep your email address on record. It will only be used to send your account verification email.</I>
    </TD>
  </TR>
  <?
}
?>
  <TR>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <B>Email Address</B>
    </TD>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <INPUT TYPE='TEXT' NAME='email_addy' STYLE='WIDTH:100%;'>
    </TD>
  </TR>

  <TR>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <B>Confirm Email Address</B>
    </TD> 
    <TD ALIGN='LEFT' WIDTH='50%'>
      <INPUT TYPE='TEXT' NAME='email_addy_confirm' STYLE='WIDTH:100%;'>
    </TD>
  </TR>
  
  <TR>
    <TD ALIGN='LEFT' WIDTH='100%' COLSPAN='2'>
      <B>NOTE:</B> <I>We will be sending you a confirmation email. Please be sure that emails from DrunkDuck.com are not being blocked from your email!</I>
    </TD>
  </TR>

  <TR>
    <TD ALIGN='LEFT' WIDTH='100%' COLSPAN='2'>
      &nbsp;
    </TD>
  </TR>
  
  <TR>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <B>Birthday</B>
    </TD>
    <TD ALIGN='LEFT' WIDTH='50%'>
        <SELECT NAME='born_month'>
          <?
          for ( $i=1; $i<13; $i++ ) {
            echo "<OPTION VALUE='".$i."'>".date("F", mktime(1,1,1,$i,1,1) )."</OPTION>";
          }
          ?>
        </SELECT><SELECT NAME='born_day'>
          <?
            for ($i=1; $i<32; $i++) {
              echo "<OPTION VALUE='".$i."'>".$i."</OPTION>";
            }
          ?>
        </SELECT><SELECT NAME='born_year'>
          <?
            for ($i=date("Y"); $i>=(date("Y")-100); $i--) {
              echo "<OPTION VALUE='".$i."'>".$i."</OPTION>";
            }
          ?>
        </SELECT>
    </TD>
  </TR>

  <TR>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <B>Country</B>
    </TD>
    <TD ALIGN='LEFT' WIDTH='50%'>
        <SELECT ID='country' NAME='country' STYLE='WIDTH:100%;' onchange="sv=this.options[this.selectedIndex].value;ss=this.form.state;if(sv=='US'){ss.disabled=false;}else {ss.disabled=true;ss.selectedIndex=0;}">
        <?=getKeyValueSelect($ARRAY_COUNTRIES, $COUNTRY)?>
        </SELECT>
    </TD>
  </TR>

  <TR>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <B>State</B>
    </TD>
    <TD ALIGN='LEFT' WIDTH='50%'>
        <SELECT ID='state' NAME='state' STYLE='WIDTH:100%;'>
          <?=getKeyValueSelect($ARRAY_STATES, $STATE)?>
        </SELECT>
    </TD>
  </TR>

  <TR>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <B>Zipcode</B>
    </TD>
    <TD ALIGN='LEFT' WIDTH='50%'>
      <INPUT TYPE='TEXT' NAME='zipcode' STYLE='WIDTH:100%;' MAXLENGTH='12'>
    </TD>
  </TR>

  <TR>
    <TD ALIGN='CENTER' COLSPAN='2'>
      <INPUT TYPE='SUBMIT' VALUE='Continue!'>
    </TD>
  </TR>

</TABLE>
<INPUT TYPE='HIDDEN' NAME='ageGroup' VALUE='<?=$_POST['ageGroup']?>'>
<INPUT TYPE='HIDDEN' NAME='uname' VALUE='<?=$_POST['uname']?>'>
<INPUT TYPE='HIDDEN' NAME='pw' VALUE='<?=$_POST['pw']?>'>
<INPUT TYPE='HIDDEN' NAME='pw_verify' VALUE='<?=$_POST['pw_verify']?>'>
<INPUT TYPE='HIDDEN' NAME='gender' VALUE='<?=$_POST['gender']?>'>
<INPUT TYPE='HIDDEN' NAME='iAgree' VALUE='<?=$_POST['iAgree']?>'>
</FORM>