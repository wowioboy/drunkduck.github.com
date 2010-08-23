<?
/*
  TROPHY TESTS BASED ON AVAILBLE DATABASE INFO
*/
if ( !isset($USER_OBJ) || !isset($TROPHY_ID) ) return;


switch( $TROPHY_ID )
{

  /*****************************************
  * COMIC PAGES
  *****************************************/
  case 1: // 1 page
  case 2: // 100 pages
  case 3: // 500 pages
  case 4: // 1,000 pages
    $res = db_query("SELECT SUM(total_pages) as page_count FROM comics WHERE user_id='".$USER_OBJ->user_id."' OR secondary_author='".$USER_OBJ->user_id."'");
    $row = db_fetch_object($res);
    db_free_result($res);

    switch( true )
    {
      case ($row->page_count >= 1000):
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 4 );
      case ($row->page_count >= 500):
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 3 );
      case ($row->page_count >= 100):
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 2 );
      case ($row->page_count >= 1):
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 1 );
      break;
    }
  break;


  /*****************************************
  * COMIC RANK
  *****************************************/
  case 5: // Top 5
  case 6: // Top 10
  case 7: // Top 100
  case 8: // Top 1000
    $res = db_query("SELECT seven_day_visits FROM comics WHERE user_id='".$USER_OBJ->user_id."' OR secondary_author='".$USER_OBJ->user_id."'");
    while( $COMIC_ROW = db_fetch_object($res) )
    {
      $res2 = db_query("SELECT COUNT(*) as rank FROM comics WHERE seven_day_visits>'".$COMIC_ROW->seven_day_visits."' AND comic_type='".$COMIC_ROW->comic_type."'");
      $row2 = db_fetch_object($res2);
      db_free_result($res2);

      switch( true )
      {
        case ( $row2->rank <= 5 ):
          give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 8 );
        case ( $row2->rank <= 10 ):
          give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 7 );
        case ( $row2->rank <= 100 ):
          give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 6 );
        case ( $row2->rank <= 1000 ):
          give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 5 );
        break;
      }

      if ( $row2->rank <= 5 ) break; // If they got it all that's good enough.
    }
    db_free_result($res);


  /*****************************************
  * FAVORITE'd
  *****************************************/
  case 9:
    $COMIC_IDS = array();
    $res = db_query("SELECT comic_id FROM comics WHERE user_id='".$USER_OBJ->user_id."' OR secondary_author='".$USER_OBJ->user_id."'");
    while( $COMIC_ROW = db_fetch_object($res) ) {
      $COMIC_IDS[] = $COMIC_ROW->comic_id;
    }
    db_free_result($res);
    $res = db_query("SELECT * FROM comic_favs WHERE comic_id IN ('".implode("','", $COMIC_IDS)."') LIMIT 1");
    if ( $row = db_fetch_object($res) ) {
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 9 );
    }
    db_free_result($res);
  break;


  /*****************************************
  * UNIQUE VISITORS
  *****************************************/
  case 10: // 100
  case 11: // 1,000
  case 12: // 10,000
    $COMIC_IDS = array();
    $res = db_query("SELECT comic_id FROM comics WHERE user_id='".$USER_OBJ->user_id."' OR secondary_author='".$USER_OBJ->user_id."'");
    while( $COMIC_ROW = db_fetch_object($res) ) {
      $COMIC_IDS[] = $COMIC_ROW->comic_id;
    }
    db_free_result($res);

    $res = db_query("SELECT * FROM unique_comic_tracking_archive WHERE comic_id IN '".implode("','", $COMIC_IDS)."' AND counter>=10000 LIMIT 1");
    if ( $row = db_fetch_object($res) )
    {
      db_free_result($res);
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 10 );
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 11 );
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 12 );
      break;
    }
    db_free_result($res);

    $res = db_query("SELECT * FROM unique_comic_tracking_archive WHERE comic_id IN '".implode("','", $COMIC_IDS)."' AND counter>=1000 LIMIT 1");
    if ( $row = db_fetch_object($res) )
    {
      db_free_result($res);
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 10 );
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 11 );
      break;
    }
    db_free_result($res);

    $res = db_query("SELECT * FROM unique_comic_tracking_archive WHERE comic_id IN '".implode("','", $COMIC_IDS)."' AND counter>=100 LIMIT 1");
    if ( $row = db_fetch_object($res) )
    {
      db_free_result($res);
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 10 );
      break;
    }
    db_free_result($res);

  break;


  /*****************************************
  * FORUM POSTS
  *****************************************/
  case 13: // 1
  case 14: // 100
  case 15: // 1,000
  case 16: // 2,500

    switch( true )
    {
      case ($USER_OBJ->forum_post_ct >= 2500 ):
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 13 );
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 14 );
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 15 );
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 16 );
      break;
      case ($USER_OBJ->forum_post_ct >= 1000 ):
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 13 );
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 14 );
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 15 );
      break;
      case ($USER_OBJ->forum_post_ct >= 100 ):
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 13 );
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 14 );
      break;
      case ($USER_OBJ->forum_post_ct >= 1 ):
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 13 );
      break;
    }

  break;


  /*****************************************
  * FEATURED STORY
  *****************************************/
  case 17:
    $COMIC_IDS = array();
    $res = db_query("SELECT comic_id, category FROM comics WHERE (user_id='".$USER_OBJ->user_id."' OR secondary_author='".$USER_OBJ->user_id."') AND comic_type='1'");
    while( $COMIC_ROW = db_fetch_object($res) ) {
      $COMIC_IDS[] = $COMIC_ROW->comic_id;
    }
    db_free_result($res);

    $res = db_query("SELECT * FROM featured_comics WHERE comic_id IN ('".implode("','", $COMIC_IDS)."') AND ymd_date_live != '0' LIMIT 1");
    if ( $row = db_fetch_object($res) ) {
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 17 );
    }
    db_free_result($res);

  break;


  /*****************************************
  * FEATURED STRIP
  *****************************************/
  case 18:
    $COMIC_IDS = array();
    $res = db_query("SELECT comic_id, category FROM comics WHERE (user_id='".$USER_OBJ->user_id."' OR secondary_author='".$USER_OBJ->user_id."') AND comic_type='0'");
    while( $COMIC_ROW = db_fetch_object($res) ) {
      $COMIC_IDS[] = $COMIC_ROW->comic_id;
    }
    db_free_result($res);

    $res = db_query("SELECT * FROM featured_comics WHERE comic_id IN ('".implode("','", $COMIC_IDS)."') AND ymd_date_live != '0' LIMIT 1");
    if ( $row = db_fetch_object($res) ) {
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 18 );
    }
    db_free_result($res);

  break;


  /*****************************************
  * FRIEND COUNT
  *****************************************/
  case 19: // 100
  case 20: // 1,000
    $res = db_query("SELECT COUNT(*) as total_friends FROM friends WHERE user_id='".$USER_OBJ->user_id."'");
    $row = db_fetch_object($res);
    db_free_result($res);

    if ( $row->total_friends >= 1000 ) {
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 19 );
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 20 );
    }
    else if ( $row->total_friends >= 100 ) {
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 19 );
    }

  break;


  /*****************************************
  * MEMBER TROPHY
  *****************************************/
  case 29:
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 29 );
  break;


  /*****************************************
  * CUSTOM PROFILE
  *****************************************/
  case 30:
    if ( $USER_OBJ->about_self && (strlen($USER_OBJ->about_self) > 0) ) {
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 30 );
    }
  break;


  /*****************************************
  * MEMBERSHIP DURATION
  *****************************************/
  case 500: // 1 Year
  case 501: // 2 Years
    $t = time() - $USER_OBJ->signed_up;
    $t = floor( $t / 60 / 60 / 24 / 365 );

    if ( $t >= 2 ) {
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 500 );
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 501 );
    }
    else if ( $t >= 1 ) {
      give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 500 );
    }

  break;


  /*****************************************
  * GAME TROPHIES
  *****************************************/
  case 1000: // CAT
    $res = db_query("SELECT * FROM user_highscores WHERE game_id='5' AND username='".$USER_OBJ->username."'");
    $scoreRow = db_fetch_object($res);
    db_free_result($res);

    if ($scoreRow) {
      $res = db_query("SELECT score_to_beat FROM game_info WHERE game_id='5'");
      $gameRow = db_fetch_object($res);
      db_free_result($res);

      if ( $scoreRow->highscore >= $gameRow->score_to_beat ) {
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 1000 );
      }
    }
  break;
  case 1010: // C&A Line up
    $res = db_query("SELECT * FROM user_highscores WHERE game_id='6' AND username='".$USER_OBJ->username."'");
    $scoreRow = db_fetch_object($res);
    db_free_result($res);

    if ($scoreRow) {
      $res = db_query("SELECT score_to_beat FROM game_info WHERE game_id='6'");
      $gameRow = db_fetch_object($res);
      db_free_result($res);

      if ( $scoreRow->highscore >= $gameRow->score_to_beat ) {
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 1010 );
      }
    }
  break;
  case 1020: // HBN
    $res = db_query("SELECT * FROM user_highscores WHERE game_id='7' AND username='".$USER_OBJ->username."'");
    $scoreRow = db_fetch_object($res);
    db_free_result($res);

    if ($scoreRow) {
      $res = db_query("SELECT score_to_beat FROM game_info WHERE game_id='7'");
      $gameRow = db_fetch_object($res);
      db_free_result($res);

      if ( $scoreRow->highscore >= $gameRow->score_to_beat ) {
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 1020 );
      }
    }
  break;
  case 1030: // Monkey Deathbot
    $res = db_query("SELECT * FROM user_highscores WHERE game_id='3' AND username='".$USER_OBJ->username."'");
    $scoreRow = db_fetch_object($res);
    db_free_result($res);

    if ($scoreRow) {
      $res = db_query("SELECT score_to_beat FROM game_info WHERE game_id='3'");
      $gameRow = db_fetch_object($res);
      db_free_result($res);

      if ( $scoreRow->highscore >= $gameRow->score_to_beat ) {
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, 1030 );
      }
    }
  break;

  default:
  break;
}

?>