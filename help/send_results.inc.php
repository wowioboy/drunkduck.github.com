<?


$searchTerm   = db_escape_string(trim($_GET['searchTxt']));
$searchQuestion = (int)$_GET['qid'];
$searchAnswer = (int)$_GET['aid'];

if ( strlen($searchTerm) > 3 )
{
  if ( $_GET['helped'] == 'yes' )
  {
    db_query("UPDATE help_queries SET counter=counter+1 WHERE question_txt='".$searchTerm."' AND final_question_id='".$searchQuestion."' AND final_answer_id='".$searchAnswer."'");
    if ( db_rows_affected() < 1 ) {
      db_query("INSERT INTO help_queries (question_txt, final_question_id, final_answer_id, counter) VALUES ('".$searchTerm."', '".$searchQuestion."', '".$searchAnswer."', '1')");
    }
  }
  else 
  {
    db_query("UPDATE help_queries SET counter=counter+1 WHERE question_txt='".$searchTerm."' AND final_question_id='0' AND final_answer_id='0'");
    if ( db_rows_affected() < 1 ) {
      db_query("INSERT INTO help_queries (question_txt, final_question_id, final_answer_id, counter) VALUES ('".$searchTerm."', '0', '0', '1')");
    }
  }
}
?>

Thanks for your feedback! This will help us to fine tune our help system!