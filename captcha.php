<?
ob_start();
require_once('./includes/captcha.class.php');

//Create a CAPTCHA
$captcha = new captcha();

//Store the String in a session
$str = $captcha->GetCaptchaString();
my_setcookie('ig', $str);


function my_setcookie($cName, $cVal, $duration=31536000)
{
  if ( setcookie ($cName, $cVal, time()+$duration, "/", '.drunkduck.com') ) {
    $_COOKIE[$cName] = $cVal;
  }
}
?>