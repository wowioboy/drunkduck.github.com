<?
if ( !defined('NO_TRACK') )
{
  $PACKAGE_DIR = dirname(__FILE__);
  require_once($PACKAGE_DIR.'/pageview_tracking.inc.php');
  require_once($PACKAGE_DIR.'/unique_tracking.inc.php');
}

// Declare as loaded.
package_loaded('tracking');
?>