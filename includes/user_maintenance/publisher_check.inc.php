<?

if ( $USER->flags & FLAG_PUBLISHER_QUAL )
{
  // Make sure they still qualify as a publisher.
  if ( !canBePublisher($USER->user_id) )
  {
    $USER->flags = $USER->flags & ~FLAG_IS_PUBLISHER;
    $USER->flags = $USER->flags & ~FLAG_PUBLISHER_QUAL;

    db_query("UPDATE users SET flags='".$USER->flags."' WHERE user_id='".$USER->user_id."'");


    $FOM = "\n\n\nFind out more about publisher status in our FAQ located at the bottom of [url=http://www.drunkduck.com/news/]this page[/url].";
    include_once(WWW_ROOT.'/community/message/tikimail_func.inc.php');
    send_system_mail('DD Publishers', $USER->username, 'You not longer qualify as a DrunkDuck Publisher!', "Hi ".$USER->username."!\n\nUnfortunately, it seems you no longer have 5 or more comics with at least 20 pages each. This means your status as a Publisher on DrunkDuck will have to be removed.".$FOM);
  }
}
else
{
  // See if they qualify to be a publisher.
  if ( canBePublisher($USER->user_id) )
  {
    $USER->flags = $USER->flags | FLAG_PUBLISHER_QUAL;

    db_query("UPDATE users SET flags='".$USER->flags."' WHERE user_id='".$USER->user_id."'");

    include_once(WWW_ROOT.'/community/message/tikimail_func.inc.php');
    send_system_mail('DD Publishers', $USER->username, 'You qualify as a DrunkDuck Publisher!', "Hi ".$USER->username."!\n\nYou have 5 or more comics with at least 20 pages each, and so qualify for the DrunkDuck Publisher Program! Just visit [url=http://user.drunkduck.com/".$USER->username."]your profile[/url] and click the link near your name to have your status updated at any time.".$FOM);
  }
}


function canBePublisher($user_id)
{
  $COMIC_COUNT = 0;

  $res = db_query("SELECT total_pages FROM comics WHERE user_id='".(int)$user_id."'");
  while( $row = db_fetch_object($res) )
  {
    if ( $row->total_pages >= 20 ) {
      $COMIC_COUNT++;
    }
  }

  if ( $COMIC_COUNT >= 5 ) {
    return true;
  }
  return false;
}
?>