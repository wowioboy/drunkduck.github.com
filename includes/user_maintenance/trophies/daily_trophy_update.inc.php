<?
// When a user visits the site and it's a new day (since they last visited), do what's here.

if ( !$USER ) return;

include_once(WWW_ROOT.'/includes/user_maintenance/trophies/trophy_data.inc.php');

user_update_trophies( $USER, 1 );  // Comic Page Update
user_update_trophies( $USER, 10 ); // Unique Visitors Check
?>