<?
include('games_admin_header.inc.php');

if ( $_POST['del'] ) {
  db_query("DELETE FROM game_info WHERE game_id='".(int)$_POST['del']."'");
}
else {
  if ( !is_numeric($_POST['pts_to_tt']) ) $_POST['pts_to_tt'] = 1;
  db_query("UPDATE game_info SET fps='".(int)$_POST['fps']."', game_version='".(int)$_POST['version']."', title='".db_escape_string($_POST['title'])."', description='".db_escape_string($_POST['description'])."', width='".(int)$_POST['width']."', height='".(int)$_POST['height']."', pts_to_tokens='".$_POST['pts_to_tt']."', is_live='".( ($_POST['is_live'])?"1":"0" )."', php_game_url='".db_escape_string($_POST['php_game_url'])."', difficulty='".db_escape_string($_POST['difficulty'])."', score_to_beat='".(int)$_POST['score_to_beat']."', trophy_weekly_winner='".(int)$_POST['weekly_winner_trophy']."', trophy_beat_score='".(int)$_POST['score_trophy']."' WHERE game_id='".(int)$_POST['gid']."'");
}

header("Location: http://".DOMAIN."/admin/games/");
?>