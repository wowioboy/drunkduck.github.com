<?
define('TEMPLATE_PAGE', 1);
ob_start(); 


// added to assure relative-absolute path translation works on most levels.
if (!defined('WWW_ROOT')) define('WWW_ROOT', $_SERVER['DOCUMENT_ROOT']);
// A very important global include.
include_once(WWW_ROOT . '/includes/global.inc.php');
// Put this here so they never see any html etc. unless they are administrators.
if ( $ADMIN_ONLY ) {
  if ( !(FLAG_IS_ADMIN & $USER->flags ) ) {
    echo("<DIV ALIGN='CENTER'>You don't have the authorization to be here! This attempt has been logged for investigation.</DIV>");
    return;
  }
} else if ( $EXEC_ONLY ) {
  if ( !(FLAG_EXECUTIVE & $USER->flags ) ) {
    echo("<DIV ALIGN='CENTER'>You don't have the authorization to be here! This attempt has been logged for investigation.</DIV>");
    return;
  }
}

// If in adminstrative area, change page BG.
if ( strstr($_SERVER['PHP_SELF'], '/administration/') ) {
  $BG_OVERRIDE = '#983838';
  $BACKGROUND  = 'BACKGROUND='.IMAGE_HOST.'/site_gfx/admin.gif';
}


// Include the header.
if ( $ADMIN_PAGE == true ) {
  include_once(TEMPLATE.'/!admin/html_header.inc.php');
} else if ( !defined('NO_TEMPLATE') ) {
  include_once(WWW_ROOT . '/header_base.php');
}









// Require login?
if ( $REQUIRE_LOGIN && !$USER ) {
  include_once(WWW_ROOT.'/not_logged_in.inc.php');
} else if ( $REQUIRE_VERIFIED && !($USER->flags & FLAG_VERIFIED) ) {
  include_once(WWW_ROOT.'/not_verified.inc.php');
} else {
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
    	include_once(WWW_ROOT.'/'.$CONTENT_FILE);
	}
}









// Include the footer.
if ( $ADMIN_PAGE == true ) {
  	include_once(TEMPLATE.'/!admin/html_footer.inc.php');
} else if ( !defined('NO_TEMPLATE') ) {
	include_once(WWW_ROOT . '/footer_base.php');
  	define('TEMPLATE_VIEW', 1);
}

ob_end_flush();
?>
