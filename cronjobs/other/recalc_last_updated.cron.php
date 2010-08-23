#!/usr/bin/php -q
<?php
  include('/var/www/html/drunkduck.com/cronjobs/cron_data.inc.php');


  // Cache this info so we don't keep querying for it!
  $GLOBALS['USER_CACHE'] = array();

  $date = new DateTime();
  $date->modify('+1 day');
  $TODAY_TS = mktime(0, 0, 0, $date->format("m"), $date->format("d"), $date->format("Y"));

  $MASS_RES = db_query("SELECT comic_id, comic_name, last_update FROM comics WHERE last_update<'".$TODAY_TS."'");
  while($COMIC_ROW = db_fetch_object($MASS_RES))
  {
    $res        = db_query("SELECT page_id, post_date FROM comic_pages WHERE comic_id='".$COMIC_ROW->comic_id."' AND post_date<='".time()."' ORDER BY post_date DESC LIMIT 1");
    $TOTALS_ROW = db_fetch_object($res);
    db_free_result($res);

    if ( $TOTALS_ROW->post_date > $COMIC_ROW->last_update ) {
      db_query("UPDATE comics SET last_update='".$TOTALS_ROW->post_date."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
      sendUpdateNotification($COMIC_ROW->comic_id, $COMIC_ROW->comic_name, $TOTALS_ROW->page_id);
    }
  }
  db_free_result($MASS_RES);




  function sendUpdateNotification( $comic_id, $comic_name, $page_id )
  {
    echo "SENDING NOTIFICATION FOR: ".$comic_name."\n";
    $URL = 'http://'.DOMAIN.'/'.comicNameToFolder($comic_name).'/?p='.$page_id;

    $USERS_TO_LOOKUP = array();
    $USERS_TO_EMAIL  = array();

    $res = db_query("SELECT * FROM comic_favs WHERE comic_id='".$comic_id."' AND email_on_update='1'");


    while ( $row = db_fetch_object($res) )
    {
      if ( !isset( $GLOBALS['USER_CACHE'][$row->user_id] ) ) {
        $USERS_TO_LOOKUP[] = $row->user_id;
      }
      else {
        $USERS_TO_EMAIL[$row->user_id] = &$GLOBALS['USER_CACHE'][$row->user_id];
      }
    }
    db_free_result($res);

    $res = db_query("SELECT * FROM demographics WHERE user_id IN ('".implode("','", $USERS_TO_LOOKUP)."')");
    while($row = db_fetch_object($res))
    {
      $GLOBALS['USER_CACHE'][$row->user_id] = $row;
      $USERS_TO_EMAIL[$row->user_id]        = $row;
    }
    db_free_result($res);

    if ( !count($USERS_TO_EMAIL) ) return;

    $MAIL_BODY  = "Hi %s!\n\n";
    $MAIL_BODY .= "This is just a reminder that one of your favorite DrunkDuck comics (%s) has updated!\n\n";
    $MAIL_BODY .= "To read the new page, just visit the URL below. If it doesn't appear to be a link, just copy and paste it into your browser address bar.\n\n";
    $MAIL_BODY .= "<a href=\"%s\">%s</a>";

    foreach($USERS_TO_EMAIL as $id=>$u)
    {
      if ( strstr($u->email, '@') )
      {
        sendMail( $u->email,
                  $comic_name." has updated!",
                  sprintf($MAIL_BODY, $GLOBALS['USER_CACHE'][$id]->first_name, $comic_name, $URL, $URL),
                  "NoReply@DrunkDuck.com"
                );
      }
    }
  }
?>
