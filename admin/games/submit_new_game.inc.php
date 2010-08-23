<?
include('games_admin_header.inc.php');

if ( $_POST['new'] ) {
  if ( !is_numeric($_POST['pts_to_tt']) ) $_POST['pts_to_tt'] = 1;
  db_query("INSERT INTO game_info (title, description, php_game_url, fps, game_version, width, height, pts_to_tokens, is_live, difficulty, score_to_beat, trophy_weekly_winner, trophy_beat_score) VALUES ('".db_escape_string($_POST['title'])."', '".db_escape_string($_POST['description'])."', '".db_escape_string($_POST['php_game_url'])."', '".(int)$_POST['fps']."', '".(int)$_POST['version']."', '".(int)$_POST['width']."', '".(int)$_POST['height']."', '".$_POST['pts_to_tt']."', '".(int)$_POST['is_live']."', '".db_escape_string($_POST['difficulty'])."', '".(int)$_POST['score_to_beat']."', '".(int)$_POST['weekly_winner_trophy']."', '".(int)$_POST['score_trophy']."')");
}

header("Location: http://".DOMAIN."/admin/games/");
?>