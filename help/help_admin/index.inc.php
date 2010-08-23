<a href="index.php?view=queries">View unanswered queries</a> | <a href="index.php?view=archive">View achive of answers</a>



<br>
<br>



<?

if ( $_GET['view'] == 'archive' )
{
  ?>
  <table border="1" cellpadding="5" cellspacing="0" width="600">
    <tr>
      <td align="center"><b>Answered Questions</td>
    </tr>
  <?
  $res = db_query("SELECT * FROM help_questions ORDER BY question_id DESC");
  while( $row = db_fetch_object($res) )
  {
    $res2 = db_query("SELECT COUNT(*) as total_answers FROM help_q_to_a WHERE question_id='".$row->question_id."'");
    $row2 = db_fetch_object($res2);
    db_free_result($res2);
    ?>
    <tr>
      <td align="left">
        <div align="left" style="padding-left:25px;">
          <b>Q.</b> <?=$row->question_txt?>
        </div>
        <br>
        <a href="edit_question.php?qid=<?=$row->question_id?>">View/Edit Answers ( <?=number_format($row2->total_answers)?> )</a>
      </td>
    </tr>
    <?
  }
  ?>
  </table>
  <?
}
else 
{
  ?>
  <table border="1" cellpadding="5" cellspacing="0" width="600">
    <tr>
      <td align="center"><b>Recently unanswered queries</td>
    </tr>
  <?
  $res = db_query("SELECT * FROM help_queries WHERE final_question_id='0' AND final_answer_id='0' ORDER BY counter DESC");
  while( $row = db_fetch_object($res) )
  {
    ?>
    <tr>
      <td align="left">
        <div align="left" style="padding-left:25px;">
          <?=$row->question_txt?>
        </div>
        <br>
        <a href="answer_query.php?queryTxt=<?=rawurlencode($row->question_txt)?>">&lt; Answer &gt;</a>
      </td>
    </tr>
    <?
  }
  ?>
  </table>
  <?
}
?>