<?
if ( !defined('MAINTENANCE_LEVEL') ) {
  define('MAINTENANCE_LEVEL', 1);
}

/************************
* This file handles major migration of user stuff when needed. If somebody logs in it checks their migration status and does what's needed.
*************************/

if ( !$USER ) return;
if ( $USER->maintenance_level == MAINTENANCE_LEVEL ) return;

if ( $USER->maintenance_level < 1 )
{
  include_once(WWW_ROOT.'/includes/trophies/trophy_data.inc.php');

  foreach( $GLOBALS['TROPHIES'] as $id=>$data ) {
    user_update_trophies( $USER, 1 );
    user_update_trophies( $USER, 5 );
    user_update_trophies( $USER, 9 );
    user_update_trophies( $USER, 10 );
    user_update_trophies( $USER, 13 );
    user_update_trophies( $USER, 17 );
    user_update_trophies( $USER, 18 );
    user_update_trophies( $USER, 19 );
    user_update_trophies( $USER, 29 );
    user_update_trophies( $USER, 30 );
    user_update_trophies( $USER, 500 );
    user_update_trophies( $USER, 1000 );
    user_update_trophies( $USER, 1010 );
    user_update_trophies( $USER, 1020 );
    user_update_trophies( $USER, 1030 );
  }

  $USER->maintenance_level = 1;

  db_query("UPDATE users SET maintenance_level='".$USER->maintenance_level."' WHERE user_id='".$USER->user_id."'");
}

?>