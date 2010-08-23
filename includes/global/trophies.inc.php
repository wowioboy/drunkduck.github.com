<?

$TROPHIES       = array();
$TROPHIES['G2'] = array(IMAGE_HOST.'/games/thumbnails/game_2_tn_small.gif', 'Hero by Night 1st Place Winner');
$TROPHIES['G3'] = array(IMAGE_HOST.'/games/thumbnails/game_3_tn_small.gif', 'Monkey Deathbot 1st Place Winner');
$TROPHIES['G4'] = array(IMAGE_HOST.'/games/thumbnails/game_4_tn_small.gif', 'C&A Line Up 1st Place Winner');
$TROPHIES['G5'] = array(IMAGE_HOST.'/games/thumbnails/game_5_tn_small.gif', 'CAT! 1st Place Winner');

function grant_trophy($username, $trophy_id)
{
  global $TROPHIES;
  if ( !isset($TROPHIES[$trophy_id]) ) return;

  $res = db_query("SELECT user_id, trophy_string FROM users WHERE username='".db_escape_string($username)."'");
  if( $row = db_fetch_object($res) )
  {
    $TROPHIES = explode('|', $row->trophy_string);
    $TROPHIES = array_unique($TROPHIES);

    if ( count($TROPHIES) == 1 && $TROPHIES[0] == '' ) {
      $TROPHIES = array();
    }

    if ( !in_array($trophy_id, $TROPHIES) )
    {
      $TROPHIES[] = $trophy_id;
      db_query("UPDATE users SET trophy_string='".implode("|", $TROPHIES)."' WHERE user_id='".$row->user_id."'");
    }
  }
}

?>