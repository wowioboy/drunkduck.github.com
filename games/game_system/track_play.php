<?
define('DEBUG_MODE', 0);
define('LEAN_AND_MEAN', 1); // do not count this as a pageview.


if ( (int)$_POST['game_id'] == 0 ) return;

include_once('../../includes/global.inc.php');


db_query("UPDATE game_plays SET counter=counter+1 WHERE date_year='".date("Y")."' AND date_month='".date("m")."' AND date_day='".date("d")."' AND game_id='".(int)$_POST['game_id']."'");
if ( db_rows_affected() < 1 ) {
  db_query("INSERT INTO game_plays (date_year, date_month, date_day, game_id, counter) VALUES ('".date("Y")."', '".date("m")."', '".date("d")."', '".(int)$_POST['game_id']."', '1')");
}
?>