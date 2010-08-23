<?
include_once(WWW_ROOT.'/tutorials/tutorials_tracking.inc.php');
?>
<?
if ( isset($_GET['rate']) && isset($_GET['id']) )
{
  if ( $_GET['rate'] >=1 && $_GET['rate'] <= 5 )
  {
    $res = db_query("SELECT * FROM tutorials WHERE tutorial_id='".(int)$_GET['id']."' AND user_id != '".($USER->user_id+1)."'");
    if ( $row = db_fetch_object($res) )
    {
      $res = db_query("INSERT INTO tutorial_votes (tutorial_id, user_id, rating) VALUES ('".$row->tutorial_id."', '".$USER->user_id."', '".(int)$_GET['rate']."')");
      if ( db_rows_affected($res) > 0 )
      {
        $row->vote_count += 1;
        $row->vote_total += (int)$_GET['rate'];
        db_query("UPDATE tutorials SET vote_count='".$row->vote_count."', vote_total='".$row->vote_total."', vote_avg='".($row->vote_total/$row->vote_count)."' WHERE tutorial_id='".$row->tutorial_id."'");
      }
    }
  }
}

header("Location: view.php?id=".$_GET['id']."&r=".dice(1,100000));

?>