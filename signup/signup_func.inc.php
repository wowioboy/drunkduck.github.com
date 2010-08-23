<?php

function username_taken($uName)
{
  $res = db_query("SELECT * FROM users WHERE username='".db_escape_string($uName)."'");
  if ( db_num_rows($res) > 0 ) {
    db_free_result($res);
    return true;
  }
  db_free_result($res);
  return false;
}


function sendActivationEmail($username, $realname, $agegroup, $emailAddy)
{
$EMAIL_BODY =
'Hi '.(($agegroup==2)?$realname:$username).',

Welcome to DrunkDuck, a webcomic community!

To confirm your account ( and get better access to the site ), please follow the following link. If it doesn\'t appear as a link, please copy it and paste it into your browser url.

<a href="http://'.DOMAIN.'/signup/activate_account.php?u='.rawurlencode($username).'&c='.md5(strtolower($username).'tikiverification').'">http://'.DOMAIN.'/signup/activate_account.php?u='.rawurlencode($username).'&c='.md5(strtolower($username).'tikiverification').'</a>

';


if ( $agegroup!=2 )
{
  $EMAIL_BODY .=
'
Since you are under 13, we will need your parent or legal guardian to print and fill out the following form and mail or fax it in!

Don\'t worry... until then there are plenty of fun comics to read on DrunkDuck.com!

-------------------------------- CUT HERE --------------------------------

DrunkDuck.com: COPPA Permission Form

Instructions for a parent or guardian

Please print this form out, complete it and fax it to the number specified (if present) or mail to the mailing address below.

Fax Number: 310.279.2799

Mailing Address:
11400 W. Olympic Blvd. 14th floor
Los Angeles, CA. 90064


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

  sendMail($emailAddy, 'Welcome to DrunkDuck.com!!!', $EMAIL_BODY, 'support@DrunkDuck.com');
}
?>