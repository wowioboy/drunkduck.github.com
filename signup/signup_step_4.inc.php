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



$DOB_TIMESTAMP = mktime(1, 1, 1, $_POST['born_month'], $_POST['born_day'], $_POST['born_year']);
if ( (timestampToYears($DOB_TIMESTAMP) < 13) || ($_POST['ageGroup'] == 1) )
{
  $_POST['ageGroup']   = 1;
  $_POST['first_name'] = '';
  $_POST['last_name']  = '';
}


if ( $_POST['ageGroup'] != 1 )
{
  if ( strlen($_POST['first_name']) < 2 ) {
    echo "<DIV ALIGN='CENTER'>Oops! You forgot a first name! <A HREF='JavaScript:history.back();'>Click here to go back and re-enter it.</A></DIV>";
    return;
  }

  if ( strlen($_POST['last_name']) < 2 ) {
    echo "<DIV ALIGN='CENTER'>Oops! You forgot a last name! <A HREF='JavaScript:history.back();'>Click here to go back and re-enter it.</A></DIV>";
    return;
  }
}

/* Do a proxy detect */
include(WWW_ROOT.'/includes/known_proxies.inc.php');
if ( isset($KNOWN_PROXIES[$_SERVER['REMOTE_ADDR']]) ) {
  echo '<div align="CENTER">Do not sign up using a proxy service.</div>';
  return;
}


include(WWW_ROOT.'/includes/bad_emails.inc.php');

$test = strtolower($_POST['email_addy']);
foreach($BAD_EMAILS as $bad )
{
  if ( strstr($test, $bad) )
  {
    echo "<DIV ALIGN='CENTER'>Oops! Your email is from a disallowed provider (".$bad.")! <A HREF='JavaScript:history.back();'>Click here to go back and re-enter it.</A></DIV>";
    return;
  }
}
if ( !preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', $_POST['email_addy']) ) {
  echo "<DIV ALIGN='CENTER'>Oops! Your email address doesn't appear to be valid! <A HREF='JavaScript:history.back();'>Click here to go back and re-enter it.</A></DIV>";
  return;
}
else if ( $_POST['email_addy'] != $_POST['email_addy_confirm'] ) {
  echo "<DIV ALIGN='CENTER'>Oops! Your email addresses didn't match! <A HREF='JavaScript:history.back();'>Click here to go back and re-enter them.</A></DIV>";
  return;
}


if ( !$ARRAY_COUNTRIES[$_POST['country']] )
{
  echo "<DIV ALIGN='CENTER'>Oops! Somehow an invalid country was selected! <A HREF='JavaScript:history.back();'>Click here to go back and re-select it.</A></DIV>";
  return;
}

if (!$_POST['state'] && $_POST['country']!='US'){
  $_POST['state'] = 'NS';
}
if ( !$ARRAY_STATES[$_POST['state']] && $_POST['country']=='US' ) {
  echo "<DIV ALIGN='CENTER'>Oops! You forgot to choose a State! <A HREF='JavaScript:history.back();'>Click here to go back and re-select it.</A></DIV>";
  return;
}

if ( !$_POST['zipcode'] ) {
  echo "<DIV ALIGN='CENTER'>Oops! You must've forgotten to enter a zipcode! <A HREF='JavaScript:history.back();'>Click here to go back and re-enter it.</A></DIV>";
  return;
}

$res = db_query("INSERT INTO users (username, password, last_seen, signed_up, flags, vote_weight, age, maintenance_level) VALUES ('".$_POST['uname']."', '".$_POST['pw']."', '".time()."', '".time()."','".( ($_POST['ageGroup']==2)?FLAG_OVER_12:0 )."', '1', '".(int)timestampToYears($DOB_TIMESTAMP)."', '".MAINTENANCE_LEVEL."')");
if ( db_rows_affected($res) == 0 ) {
  db_free_result($res);
  echo "<DIV ALIGN='CENTER'>Hmmm something has gone wrong! Please try signing up again in 10 minutes.</DIV>";
  return;
}
db_free_result($res);

$res = db_query("INSERT INTO demographics (user_id, first_name, last_name, country, state, zipcode, email, dob_timestamp, gender) VALUES ('".db_get_insert_id()."', '".db_escape_string($_POST['first_name'])."', '".db_escape_string($_POST['last_name'])."', '".$_POST['country']."', '".$_POST['state']."', '".db_escape_string($_POST['zipcode'])."', '".db_escape_string(($_POST['ageGroup']==2)?$_POST['email_addy']:md5( strtolower($_POST['email_addy']) ))."', '".$DOB_TIMESTAMP."', '".$_POST['gender']."')");
if ( db_rows_affected($res) == 0 ) {
  db_query("DELETE FROM users WHERE username='".$_POST['uname']."'");
}
else
{
  if ( date("Y") == 2006 )
  {
    include(WWW_ROOT.'/community/message/tikimail_func.inc.php');

    $username = $_POST['uname'];
    $MSG = "\n".'[url=http://'.DOMAIN.'/ecard/][img]'.IMAGE_HOST.'/ecard/greeting_envelope.gif[/img][/url]'."\n\n";
    send_system_mail('DrunkDuck.com', $username, 'Happy Holidays from DrunkDuck!', $MSG);
  }
}


/*
$EMAIL_BODY =
'Hi '.(($_POST['ageGroup']==2)?$_POST['first_name']:$_POST['uname']).',

Welcome to DrunkDuck, a webcomic community!

To confirm your account ( and get better access to the site ), please follow the following link. If it doesn\'t appear as a link, please copy it and paste it into your browser url.

http://'.DOMAIN.'/signup/activate_account.php?u='.rawurlencode($_POST['uname']).'&c='.md5(strtolower($_POST['uname']).'tikiverification').'

';


if ( $_POST['ageGroup']!=2 )
{
  $EMAIL_BODY .=
'
Since you are under 13, we will need your parent or legal guardian to print and fill out the following form and mail or fax it in!

Don\'t worry... until then there are plenty of fun comics to read on DrunkDuck.com!

-------------------------------- CUT HERE --------------------------------

DrunkDuck.com: COPPA Permission Form

Instructions for a parent or guardian

Please print this form out, complete it and fax it to the number specified (if present) or mail to the mailing address below.

Fax Number: 818.888.8888

Mailing Address:
88888 San Ave.
Woodland Hills, CA. 91364


Enter your Username: ___________________________

Enter your Email Address:  ___________________________


Please sign the form below and send to us.


I understand that the information that the child has supplied is correct. I understand that the profile information may be changed by entering a password and I understand that I may ask for this registration profile to be removed.

Parent / Legal Guardian FULL name:  ___________________________

Relation to child:  ___________________________

Signature: ___________________________

Email Address: ___________________________

Phone Number: ___________________________

Date: ___________________________

-------------------------------- CUT HERE --------------------------------

';
}

$EMAIL_BODY .=
'Sincerely,

The DrunkDuck Support Team!';

sendMail($_POST['email_addy'], 'Welcome to DrunkDuck.com!!!', $EMAIL_BODY, 'support@DrunkDuck.com');
*/
sendActivationEmail($_POST['uname'], $_POST['first_name'], $_POST['ageGroup'], $_POST['email_addy']);

$res = db_query("SELECT * FROM users WHERE username='".db_escape_string($_POST['uname'])."'");
$USER = db_fetch_object($res);
make_session($USER);

db_query("INSERT INTO friends (user_id, friend_id, order_id) VALUES ('".$USER->user_id."', '1', '12')");
db_query("INSERT INTO friends (user_id, friend_id, order_id) VALUES ('1', '".$USER->user_id."', '0')");

//header("Location: /index.php");

?>



<DIV ALIGN='CENTER'><B>You successfully signed up! Be sure to check your email for the confirmation link we just sent you!</B></DIV>