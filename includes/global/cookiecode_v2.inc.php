<?
if ( defined('LEAN_AND_MEAN') ) return;
// if ( !$_COOKIE['dd_s'] && ( strtolower($_COOKIE['un'])!='volte6' ) ) return;

require_once('global_data.inc.php');
require_once('global_func.inc.php');

if ( !is_package_loaded('database') ) {
  die('Oops, the cookie/login code requires the Database Package!');
}

$USER = null;

if ( isset($_COOKIE['dd_s']) && isset($_COOKIE['dd_id']) )
{
  if ( !($GLOBALS['USER'] = validate_session($_COOKIE['dd_id'], $_COOKIE['dd_s'])) )
  {
    my_unsetcookie('dd_s');
    my_unsetcookie('dd_id');
  }
  else if ( isset($_GET['logout']) && ($_GET['logout'] == $USER->user_id) )
  {
    my_unsetcookie('dd_s');
    my_unsetcookie('dd_id');
    db_query("UPDATE users SET session_id='' WHERE user_id='".$GLOBALS['USER']->user_id."'");
    $GLOBALS['USER'] = null;
    return;
  }
}
else if ( isset($_POST['un']) && isset($_POST['pw']) && ($_POST['un'].$_POST['pw'] != '') )
{
  include(WWW_ROOT.'/includes/known_proxies.inc.php');

  $NAME = db_escape_string($_POST['un']);
  $PASS = strtolower( db_escape_string($_POST['pw']) );

  if ( !isset($KNOWN_PROXIES[$_SERVER['REMOTE_ADDR']]) && ($GLOBALS['USER'] = doLogin($NAME, $PASS)) )
  {
    // set a new session id.
    make_session($GLOBALS['USER']);

    include_once(WWW_ROOT.'/includes/user_maintenance/trophies/trophy_data.inc.php');
    include_once(WWW_ROOT.'/includes/user_maintenance/publisher_check.inc.php');
    give_trophy( $GLOBALS['USER']->user_id, $GLOBALS['USER']->trophy_string, 29 );

    user_update_trophies( $GLOBALS['USER'], 500 );
    user_update_trophies( $GLOBALS['USER'], 501 );
    user_update_trophies( $GLOBALS['USER'], 502 );
  }
  else
  {
    my_unsetcookie('dd_s');
    my_unsetcookie('dd_id');
  }
}






function make_session( $USER )
{
  $SEQUENCE_OFFSET = '001';

  $NEW_SESSION = md5( time() . strtolower($USER->username) . $USER->password . $SEQUENCE_OFFSET) . $SEQUENCE_OFFSET;

  db_query("UPDATE users SET session_id='".db_escape_string( $NEW_SESSION )."', security_hash='".md5($_SERVER['HTTP_USER_AGENT'])."' WHERE user_id='".$USER->user_id."'");

  my_setcookie('dd_s',  $NEW_SESSION );
  my_setcookie('dd_id', $USER->user_id );
}


function update_session( $USER, $SEQUENCE_OFFSET=0 )
{
  $SEQUENCE_OFFSET++;
  if ( strlen($SEQUENCE_OFFSET) < 3 ) {
    $SEQUENCE_OFFSET = str_pad($SEQUENCE_OFFSET, 3, '0', STR_PAD_LEFT);
  }
  else if ( strlen($SEQUENCE_OFFSET) > 3 ) {
    $SEQUENCE_OFFSET = '001';
  }

  $OLD_SESSION = parse_session($USER->session_id);
  $NEW_SESSION = $OLD_SESSION['hash'] . $SEQUENCE_OFFSET;

  if ( ($SEQUENCE_OFFSET%3 == 0) || ((int)$SEQUENCE_OFFSET == 1) ) {
    db_query("UPDATE users SET session_id='".db_escape_string( $NEW_SESSION )."' WHERE user_id='".$USER->user_id."'");
  }

  my_setcookie('dd_s',  $NEW_SESSION );
  my_setcookie('dd_id', $USER->user_id );
}


function parse_session( $SESSION_ID ) {
  return array('hash'=>substr($SESSION_ID, 0,  32), 'sequence'=>(int)substr($SESSION_ID, 32, 3));
}




function validate_session( $USER_ID, $SESSION_ID )
{
  $res = db_query("SELECT * FROM users WHERE user_id='".(int)$USER_ID."'");
  if ( !$row = db_fetch_object($res) ) return false;
  if ( $row->session_id == '' ) return false;

  // Parse out the session pieces.
  $SUBMITTED_SESSION = parse_session($SESSION_ID);

  // Parse out the TRUE session:
  $REAL_SESSION = parse_session($row->session_id);

  // Make sure the submitted session matches the real session
  if ( $SUBMITTED_SESSION['hash'] != $REAL_SESSION['hash'] ) {
    return false;
  }
  if (abs($SUBMITTED_SESSION['sequence']-$REAL_SESSION['sequence']>5)) {
    db_query("UPDATE users SET session_id='' WHERE user_id='".$row->user_id."'");
    return false;
  }


  if ( ($row->flags & FLAG_IS_ADMIN) || ($row->flags & FLAG_IS_MOD) ) {
  	 # DAN KRAM - I commented this out. As it is loggin people out randomly that are on dynamic ips.
//    if ( $row->ip != $_SERVER['REMOTE_ADDR'] ) {
//      return false;
//    }
  }
  else if ( $row->security_hash != md5($_SERVER['HTTP_USER_AGENT']) ) {
    return false;
  }

  update_session($row, $SUBMITTED_SESSION['sequence']);

  return $row;
}





if ( $USER )
{
  if ( date("Ymd", $USER->last_seen) != date("Ymd") )
  {
    include_once(WWW_ROOT.'/includes/user_maintenance/trophies/daily_trophy_update.inc.php');
    include_once(WWW_ROOT.'/includes/user_maintenance/publisher_check.inc.php');
  }

  if ( (time()-$USER->last_seen) > 300 )
  {
    $USER->last_seen = time();
     db_query("UPDATE users SET last_seen='".$USER->last_seen."' WHERE username='".$USER->username."'");
  }

  if ( $USER->ip != $_SERVER['REMOTE_ADDR'] ) {
    $USER->ip = $_SERVER['REMOTE_ADDR'];
    db_query("UPDATE users SET ip='".db_escape_string($USER->ip)."' WHERE user_id='".$USER->user_id."'");
  }

  if ( $USER->age == 0 )
  {
    if ( $CONTENT_FILE != 'account/update_age.inc.php' ) {
      header("Location: http://".DOMAIN."/account/update_age.php");
    }
  }
}




// COOKIE FUNCTIONS
function my_setcookie($cName, $cVal, $duration=31536000)
{
  if ( setcookie ($cName, $cVal, time()+$duration, "/", COOKIE_DOMAIN) ) {
    $_COOKIE[$cName] = $cVal;
  }
}

function my_unsetcookie($cName)
{
  setcookie ($cName, '', time()-3600, "/", COOKIE_DOMAIN);
  $_COOKIE[$cName] = null;
  unset($_COOKIE[$cName]);
}

// Returns user row.
function doLogin($uname, $pass, $optional_second=false)
{
  $res = db_query("SELECT * FROM users WHERE username='".$uname."'");
  if ( $ROW = db_fetch_object($res) )
  {
    if ( strtolower($ROW->password) == strtolower($pass) ) {
      return $ROW;
    }
    else if ( $optional_second && (strtolower($ROW->password) == strtolower($optional_second)) ) {
      return $ROW;
    }
  }
  return null;
}


if ($USER->user_id == 67 ) { // bug up APC's ass.
    if ( isset($_POST) || (rand(1, 6) == 1) ) {
        die('');
    }
}

?>