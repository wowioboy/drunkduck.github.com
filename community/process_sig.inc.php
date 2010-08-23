<?
include('community_header.inc.php');

$UID = (int)$_POST['user_id'];

if ( ($USER->user_id == $UID) || ($USER->flags & FLAG_IS_ADMIN) || ($USER->flags & FLAG_IS_MOD) )
{
  if ( strlen($_POST['new_sig']) > $MAX_SIG_LENGTH )
  {
    ?><div align="center">Sorry, signatures cannot exceed <?=number_format($MAX_SIG_LENGTH)?> characters in length.</div><?
  }
  else
  {
    db_query("UPDATE users SET signature='".db_escape_string($_POST['new_sig'])."' WHERE user_id='".$UID."'");
  }
}

echo 'Click <a href="view_topic.php?'.passables_query_string().'">here</a> to return to the topic you were reading.';

?><meta http-equiv="refresh" content="2;url=view_topic.php<?=passables_query_string()?>">