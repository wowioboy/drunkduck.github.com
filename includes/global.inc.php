<?
/*
if ( false )
{
  ?>
<div align="center" style="font-weight:24px;">
  NOW MIGRATING A DATABASE TABLE. PLEASE CHECK AGAIN IN A FEW MINUTES.
</div>
  <?
  die;
}
*/

if ( $_SERVER['REMOTE_ADDR'] == '66.77.242.73'  || $_SERVER['REMOTE_ADDR'] == '24.199.56.14' || $_SERVER['REMOTE_ADDR'] == "69.227.66.226" || $_SERVER['REMOTE_ADDR'] == "220.227.88.202") {
  define('DEBUG_MODE', 1 );
} else {
  define('DEBUG_MODE', 0 );
}

define('PLATINUM_OWNED', 1);

if ( $_SERVER['HTTP_HOST'] == 'linuxdev1' )
{
  define('DEBUG_MODE',      1);

  // The document root of the website.
  define('WWW_ROOT',        realpath('./'));
  // Does the name not say it all? Fine. The domain in which the cookies are valid.
  define('COOKIE_DOMAIN',   '.linuxdev1');
  // The domain.
  define('DOMAIN',          'linuxdev1');
}
else
{
  if ( $_SERVER['REMOTE_ADDR'] == '67.153.138.98' || $_SERVER['REMOTE_ADDR'] == '76.172.202.112' || $_SERVER['REMOTE_ADDR'] == '24.199.56.14' ||  $_SERVER['REMOTE_ADDR'] == "69.227.66.226" || $_SERVER['REMOTE_ADDR'] == "220.227.88.202") {
    define('DEBUG_MODE',        1);
  }

  // The document root of the website.
  define('WWW_ROOT',        realpath('./'));
  // Does the name not say it all? Fine. The domain in which the cookies are valid.
  define('COOKIE_DOMAIN',   '.drunkduck.com');
  // The domain.
  define('DOMAIN',          'www.drunkduck.com');
//  define('DOMAIN',          '69.7.183.55:8005');
}

//define('COMIC_DOMAIN',    'content-comics.drunkduck.com');
define('COMIC_DOMAIN', 'comics.drunkduck.com');
//define('COMIC_DOMAIN', '69.7.183.55:8006');

define('USER_DOMAIN', 'user.drunkduck.com');
//define('USER_DOMAIN', '69.7.183.55:8010');

// Where are images hosted? ( Usually a subfolder of WWW_ROOT )
//define('IMAGE_HOST',      'http://content-images.drunkduck.com');
define('IMAGE_HOST',      'http://images.drunkduck.com');
//define('IMAGE_HOST',      'http://69.7.183.55:8009');


define('SUBDOM', strtolower(array_shift(explode('.', $_SERVER['HTTP_HOST']))) );
define('SUBDOMAIN', SUBDOM);

/*
if ( date("m") == 10 && date("d") == 31 ) {
  define('IMAGE_HOST_SITE_GFX', IMAGE_HOST.'/site_gfx_new_v2_horror');
}
else
{
  switch( SUBDOM )
  {
    case 'manga':
    case 'anime':
      define('IMAGE_HOST_SITE_GFX', IMAGE_HOST.'/site_gfx_new_v2_manga');
    break;

    case 'horror':
      define('IMAGE_HOST_SITE_GFX', IMAGE_HOST.'/site_gfx_new_v2_horror');
    break;

    case 'www':
    default:
      define('IMAGE_HOST_SITE_GFX', IMAGE_HOST.'/site_gfx_new_v2');
    break;
  }
}
*/
define('IMAGE_HOST_SITE_GFX', IMAGE_HOST.'/site_gfx_new_v2');

// The includes folder.
define('INCLUDES',        WWW_ROOT.'/includes');
// Where globally included stuff is.
define('GLOBALS',         WWW_ROOT.'/includes/global');
// Where are the packages?
define('PACKAGES',        WWW_ROOT.'/includes/packages');

define('TEMPLATE',        WWW_ROOT.'/templating');

// http Javascript path
define('HTTP_JAVASCRIPT', 'http://'.DOMAIN.'/javascript');

require_once(WWW_ROOT.'/banned_ip_data.inc.php');
if ( isset($BANNED_IPS[$_SERVER['REMOTE_ADDR']]) ) {
  setcookie('b', '1', time()+(86400*365), "/", COOKIE_DOMAIN);
  die("<DIV ALIGN='CENTER'>Sorry. Your IP Address has been banned.</DIV>");
}
// Be sure to incldue packages before anything else!
require_once(PACKAGES.'/package_functions.inc.php');
require_once(PACKAGES.'/database_package/load.inc.php');
require_once(PACKAGES.'/clickpath_package/load.inc.php');
require_once(PACKAGES.'/ajax_user_settings/load.inc.php');

// Dynamic loading of global content routine.
$dp = opendir(GLOBALS);
while ( $f = readdir($dp) ) {
  if ( ($f != '.') && ($f != '..') && !is_dir(GLOBALS.'/'.$f) ) {
    require_once( GLOBALS.'/'.$f );
  }
}
closedir($dp);

if ( !defined('FLAG_IS_ADMIN') ) {
    die("DrunkDuck is down for maintenance. Please check back in 30 minutes.");
}

require_once(PACKAGES.'/pagetrack_package/load.inc.php');


error_reporting( E_ERROR | E_PARSE );
//error_reporting( 0 );
?>
