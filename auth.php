<?php

require_once(WWW_ROOT . '/includes/db.class.php');

// Require login?
/*var_dump($REQUIRE_LOGIN); die();*/

if ( $REQUIRE_LOGIN && !$USER ) {
    header('location:/login.php');
}

if ( !$USER ) {
  require_once(WWW_ROOT.'/not_logged_in.inc.php');
} else if ( $REQUIRE_VERIFIED && !($USER->flags & FLAG_VERIFIED) ) {
  require_once(WWW_ROOT.'/not_verified.inc.php');
} else {
/*    echo '<pre>';*/
/*  var_dump($USER);*/
  // Include the file! 
    if ( $USER && !($USER->flags & FLAG_IS_ADMIN) ) {
      // FLAG_BANNED_PC
      // If banned, freeze them.
      if ( isset($_POST['banMe'] ) ) {
        if ( ($USER->username == $_POST['bannedUser']) && !($USER->flags & FLAG_BANNED_PC) ) {
          $clearBan = 1;
            $GLOBALS['USER']->flags &= ~FLAG_BANNED_PC;
            db_query("UPDATE users SET flags='".$GLOBALS['USER']->flags."' WHERE user_id='".$GLOBALS['USER']->user_id."'");
          } else {
            $GLOBALS['USER']->flags |= FLAG_FROZEN;
            $GLOBALS['USER']->flags |= FLAG_BANNED_PC;
            db_query("UPDATE users SET flags='".$GLOBALS['USER']->flags."' WHERE user_id='".$GLOBALS['USER']->user_id."'");
          }
      }
      if ( ($GLOBALS['USER']->flags & FLAG_BANNED_PC) || ( isset($_POST['banMe']) && (md5($USER->user_id.'fish') == $_POST['banVar']) ) ) {
        my_unsetcookie('un');
          my_unsetcookie('pw');
          $GLOBALS['USER']->flags |= FLAG_FROZEN;
          db_query("UPDATE users SET flags='".$GLOBALS['USER']->flags."' WHERE user_id='".$GLOBALS['USER']->user_id."'");
      }
      if ( $GLOBALS['USER']->flags & FLAG_FROZEN ) {
        my_unsetcookie('un');
        my_unsetcookie('pw');
          include_once(WWW_ROOT.'/frozen.inc.php');
          unset($CONTENT_FILE);
      }
    }
    if ( $GLOBALS['USER']->warning >= 100 ) {
      include_once(WWW_ROOT.'/maxed_warning.inc.php');
    } else if ( isset($CONTENT_FILE) && !isset($_GET['CONTENT_FILE']) && !isset($_POST['CONTENT_FILE']) ) {
/*        include_once(WWW_ROOT.'/'.$CONTENT_FILE);*/
    }
    require_once('user_panel.php');
/*    die('this is it.');*/
}