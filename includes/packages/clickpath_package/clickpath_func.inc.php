<?



function cp_setcookie($cName, $cVal, $duration=31536000)
{
  if ( setcookie ($cName, $cVal, time()+$duration, "/", CLICKPATH_COOKIE_DOM) ) {
    $_COOKIE[$cName] = $cVal;
  }
}

function cp_unsetcookie($cName)
{
  setcookie ($cName, '', time()-3600, "/", CLICKPATH_COOKIE_DOM);
  $_COOKIE[$cName] = null;
  unset($_COOKIE[$cName]);
}






function check_clickpath( $IDENTIFIER=false )
{
  if ( $IDENTIFIER == false ) {
    $IDENTIFIER = $_SERVER['REMOTE_ADDR'];
  }
  $IDENTIFIER .= date("Ymd");

  // tracking cookie consists of 3 parts separated by a pipe(|).
  // 1.) YMD 2.) Identifier 3.) Last Path
  // Example: 20080808|72.32.68.211|/index.php

  if ( isset($_COOKIE[CLICKPATH_COOKIENAME]) ) {
    $DATA = explode('|', $_COOKIE[CLICKPATH_COOKIENAME]);
  }
  else if ( clickpath_hash($IDENTIFIER, CLICKPATH_ODDS) == 0 ) {
    $DATA = array( date("Ymd"),  $IDENTIFIER, '' );
  }
  else {
    return;
  }


  if ( $DATA[0] != date("Ymd") ) {
    cp_unsetcookie(CLICKPATH_COOKIENAME);
    return;
  }

  track_clickpath( $DATA[2], $_SERVER['PHP_SELF'], ( ($GLOBALS['USER']) ? 1 : 0 ) );
  $DATA[2] = $_SERVER['PHP_SELF'];

  cp_setcookie(CLICKPATH_COOKIENAME, implode('|', $DATA) );
}


function track_clickpath( $last_path, $new_path, $logged_in=0 )
{
  $SUBDOM = strtolower(array_shift(explode('.', $_SERVER['HTTP_HOST'])));
  if ( strlen($SUBDOM) == 0 ) $SUBDOM = 'www';

  $LFILE  = $last_path;
  $NFILE  = $new_path;

  if ( $LFILE == '' ) { $LFILE = $LFOLD = 'Direct'; }
  else { $LFOLD  = dirname($LFILE).'/'; }

  $NFOLD  = dirname($NFILE).'/';

  $LOGGED = (int)$logged_in;


  db_query("UPDATE click_path_file_to_file SET counter=counter+1 WHERE subdomain='".db_escape_string($SUBDOM)."' AND source_file='".db_escape_string($LFILE)."' AND target_file='".db_escape_string($NFILE)."' AND ymd_date='".date("Ymd")."' AND logged_in='".$LOGGED."'");
  if ( db_rows_affected() < 1 ) {
    db_query("INSERT INTO click_path_file_to_file (subdomain, source_file, target_file, counter, ymd_date, logged_in) VALUES ('".db_escape_string($SUBDOM)."', '".db_escape_string($LFILE)."', '".db_escape_string($NFILE)."', '1', '".date("Ymd")."', '".$LOGGED."')");
  }

  db_query("UPDATE click_path_file_to_folder SET counter=counter+1 WHERE subdomain='".db_escape_string($SUBDOM)."' AND source_file='".db_escape_string($LFILE)."' AND target_folder='".db_escape_string($NFOLD)."' AND ymd_date='".date("Ymd")."' AND logged_in='".$LOGGED."'");
  if ( db_rows_affected() < 1 ) {
    db_query("INSERT INTO click_path_file_to_folder (subdomain, source_file, target_folder, counter, ymd_date, logged_in) VALUES ('".db_escape_string($SUBDOM)."', '".db_escape_string($LFILE)."', '".db_escape_string($NFOLD)."', '1', '".date("Ymd")."', '".$LOGGED."')");
  }

  db_query("UPDATE click_path_folder_to_file SET counter=counter+1 WHERE subdomain='".db_escape_string($SUBDOM)."' AND source_folder='".db_escape_string($LFOLD)."' AND target_file='".db_escape_string($NFILE)."' AND ymd_date='".date("Ymd")."' AND logged_in='".$LOGGED."'");
  if ( db_rows_affected() < 1 ) {
    db_query("INSERT INTO click_path_folder_to_file (subdomain, source_folder, target_file, counter, ymd_date, logged_in) VALUES ('".db_escape_string($SUBDOM)."', '".db_escape_string($LFOLD)."', '".db_escape_string($NFILE)."', '1', '".date("Ymd")."', '".$LOGGED."')");
  }

  db_query("UPDATE click_path_folder_to_folder SET counter=counter+1 WHERE subdomain='".db_escape_string($SUBDOM)."' AND source_folder='".db_escape_string($LFOLD)."' AND target_folder='".db_escape_string($NFOLD)."' AND ymd_date='".date("Ymd")."' AND logged_in='".$LOGGED."'");
  if ( db_rows_affected() < 1 ) {
    db_query("INSERT INTO click_path_folder_to_folder (subdomain, source_folder, target_folder, counter, ymd_date, logged_in) VALUES ('".db_escape_string($SUBDOM)."', '".db_escape_string($LFOLD)."', '".db_escape_string($NFOLD)."', '1', '".date("Ymd")."', '".$LOGGED."')");
  }
}



function clickpath_hash($str, $base=1000)
{
  $str   = trim(strtolower($str));
  $total = 0;
  for($i=0; $i<strlen($str); $i++) {
    $total += floor( ord($str{$i}) * $i );
  }
  return $total % $base;
}

?>