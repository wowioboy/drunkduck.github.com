<?

if ( isset($_POST['originalQuestion']) && isset($_POST['questionTxt']) && isset($_POST['answerTxt']) )
{
  db_query("INSERT INTO help_questions (question_txt) VALUES ('".db_escape_string($_POST['questionTxt'])."')");
  $qId_1 = db_get_insert_id();
  
  db_query("INSERT INTO help_answers (answer_txt) VALUES ('".db_escape_string($_POST['answerTxt'])."')");
  $qId_2 = db_get_insert_id();
  
  db_query("INSERT INTO help_q_to_a (question_id, answer_id) VALUES ('".$qId_1."', '".$qId_2."')");

  db_query("DELETE FROM help_queries WHERE question_txt='".db_escape_string( rawurldecode($_POST['originalQuestion']) )."'");
  
  //header("Location: index.php");
  return;
}





$res = db_query("SELECT * FROM help_queries WHERE question_txt='".db_escape_string($_GET['queryTxt'])."'");
if ( !$row = db_fetch_object($res) )
{
  echo "Query not located in database.";
  return;
}

?>

<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
  <table border="1" cellpadding="5" cellspacing="0" width="600">
    <tr>
      <td align="left">
        <b>Question</b>
      </td>
    </tr>
    <tr>
      <td align="left">
        <input type="text" name="questionTxt" value="<?=htmlentities($_GET['queryTxt'], ENT_QUOTES)?>" style="width:100%;">
      </td>
    </tr>
    <tr>
      <td align="left">
        <b>Answer</b>
      </td>
    </tr>
    <tr>
      <td align="left">
        <textarea name="answerTxt" style="width:100%;" rows="10"></textarea>
      </td>
    </tr>
    <tr>
      <td align="center">
        <input type="submit" value="Save!">
      </td>
    </tr>
  </table>
  <input type="hidden" name="originalQuestion" value="<?=rawurlencode($_GET['queryTxt'])?>">
</form>