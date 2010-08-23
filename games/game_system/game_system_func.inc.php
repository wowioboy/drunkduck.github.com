<?
function strip_chars(&$string, $start, $charCount)
{
  $part1  = substr($string, $start, $charCount);
  $string = substr($string, $start+$charCount);
  return $part1;
}

function create_session($USER, $game_id, $duration=3600)
{
  if ( is_object($USER) ) { $user = $USER->username; }
  else { $user = $USER; }

  db_query("INSERT INTO game_sessions (username, game_id, time_expired) VALUES ('".$user."', '".$game_id."', '".(time()+$duration)."')");
  return db_get_insert_id();
}

function find_session($USER, $game_id)
{
  if ( is_object($USER) ) { $user = $USER->username; }
  else { $user = $USER; }

  $res = db_query("SELECT * FROM game_sessions WHERE username='".$user."' AND game_id='".$game_id."'");
  if ( $row = db_fetch_object($res) )
  {
    if ($row->time_expired < time()) {
      kill_session($USER, $row->session_id);
      return false;
    }
    return $row->session_id;
  }

  return false;
}

function bump_session($USER, $session_id, $duration=3600) {
  if ( is_object($USER) ) { $user = $USER->username; }
  else { $user = $USER; }

  db_query("UPDATE game_sessions SET time_expired=time_expired+".$duration." WHERE session_id='".$session_id."' AND username='".$user."' AND time_expired>".time());
}

function has_session($USER, $session_id)
{
  if ( is_object($USER) ) { $user = $USER->username; }
  else { $user = $USER; }

  $res = db_query("SELECT * FROM game_sessions WHERE session_id='".$session_id."' AND username='".$user."'");
  if ( !($SESSION_ROW = db_fetch_object($res)) ) {
    return false;
  }
  else if ($SESSION_ROW->time_expired < time()) {
    kill_session($USER, $session_id);
    return false;
  }
  return $SESSION_ROW;
}

function kill_session($USER, $session_id)
{
  if ( is_object($USER) ) { $user = $USER->username; }
  else { $user = $USER; }
  db_query("DELETE FROM game_sessions WHERE session_id='".$session_id."' AND username='".$user."'");
}
?>