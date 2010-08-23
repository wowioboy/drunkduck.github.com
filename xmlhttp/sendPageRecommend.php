<?
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');

$res = db_query("SELECT * FROM comics WHERE comic_id='".(int)$_GET['cid']."'");
if ( $COMIC_ROW = db_fetch_object($res) )
{
  db_free_result($res);

  $res = db_query("SELECT * FROM comic_pages WHERE page_id='".(int)$_GET['pid']."' AND comic_id='".(int)$_GET['cid']."'");
  if ( $PAGE_ROW = db_fetch_object($res) )
  {
    $URL = 'http://'.DOMAIN.'/'.comicNameToFolder($COMIC_ROW->comic_name).'/?p='.$_GET['pid'];

    foreach($_GET as $key=>$value)
    {
      if ( $key == 'emails' )
      {
        foreach($value as $email)
        {
          if ( strstr($email, '@') )
          {
            sendMail($email, 'Somebody wants you to check out a comic strip!', "Hi, somebody at DrunkDuck.com has recommended a comic for you to check out! Click <a href=\"".$URL."\">here</a> to see it.\nIf that doesn't appear to be a link, copy and paste the following URL into your browser: ".$URL, 'NoReply@DrunkDuck.com');
          }
        }
      }
    }
  }
  db_free_result($res);
}

?>