<?
if ( $_POST['qid'] && $_POST['aid'] )
{
  $QID = (int)$_POST['qid'];
  $AID = (int)$_POST['aid'];
}
else { return; }

$res = db_query("SELECT * FROM poll_questions WHERE id='".$QID."' AND is_live='1'");
if ( !($qRow = db_fetch_object($res)) ) { return; }
else if ( !$qRow->allow_anon && !$USER ) { return; }
db_free_result($qRow);




$res = db_query("SELECT * FROM poll_answers WHERE id='".$AID."' AND question_id='".$QID."'");
if ( !($aRow = db_fetch_object($res)) ) { return; }

if ( $_COOKIE['poll'] == $qRow->id )
{
  echo "<DIV ALIGN='CENTER' style='color:white;'><b>Sorry, you have already voted on this poll.</b></DIV>";
  return;
}
else if ( $USER )
{
  db_query("INSERT INTO poll_votes (question_id, user_id) VALUES ('".$QID."', '".$USER->user_id."')");
  if (db_rows_affected() < 1 )
  {
    echo "<DIV ALIGN='CENTER' style='color:white;'><b>Sorry, you have already voted on this poll.</b></DIV>";
    my_setcookie('poll', $QID);
    return;
  }
}


my_setcookie('poll', $QID);

db_query("UPDATE poll_answers SET counter=counter+1 WHERE id='".$AID."'");
echo "<DIV ALIGN='CENTER' style='color:white;'><b>Thanks for voting!</b></DIV>";
header("Location: http://".$_POST['fromsubdom'].".drunkduck.com");
?>