<?
// db_query("INSERT INTO help_questions (question_txt) VALUES ('I lost my password.')");
// db_query("INSERT INTO help_answers (answer_txt) VALUES ('Click <A HREF=\"http://www.drunkduck.com/forgot_password.php\">here</A> and fill out the form. Your password will be mailed to you.')");

// db_query("INSERT INTO help_connectors (parent_question_id, answer_id) VALUES ('1', '1')");  

/*
CREATE TABLE help_questions
(
  question_id         INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  question_txt        VARCHAR(255),
  INDEX(question_txt)
);
ALTER TABLE help_questions ADD FULLTEXT(question_txt);

CREATE TABLE help_answers
(
  answer_id           INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  answer_txt          TEXT
);
ALTER TABLE help_answers ADD FULLTEXT(answer_txt);

CREATE TABLE help_q_to_a
(
  question_id  INT(11) NOT NULL DEFAULT '0',
  answer_id    INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY(question_id, answer_id)
);

CREATE TABLE help_q_to_q
(
  parent_question_id  INT(11) NOT NULL DEFAULT '0',
  question_id           INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY(parent_question_id, question_id)
);

CREATE TABLE help_queries
(
  question_txt  VARCHAR(255) NOT NULL,
  final_question_id INT(11) NOT NULL,
  final_answer_id INT(11)    NOT NULL,
  counter         INT(11)    NOT NULL,
  
  UNIQUE KEY(question_txt, final_question_id, final_answer_id)
);
ALTER TABLE help_queries ADD FULLTEXT(question_txt);

*/

?>
<form action='<?=$_SERVER['PHP_SELF']?>' METHOD='GET'>

  Search: <input type='text' name='searchTxt'>
  
</form>

<?

/*
  First build a basic data structure
*/
$SEARCH['txt'] = trim($_GET['searchTxt']);
$SEARCH['qid'] = (int)$_GET['qid'];


if ( (strlen($SEARCH['txt']) >= 3) && !$SEARCH['qid'] )
{
  echo "<DIV ALIGN='CENTER' STYLE='font-size:18px;'><b>\"".$SEARCH['txt']."\"</b></DIV>";
  
  $res = db_query("SELECT * FROM help_questions WHERE question_txt='".db_escape_string($SEARCH['txt'])."'");
  if ( $row = db_fetch_object($res) )
  {
    Header("Location: ".$_SERVER['PHP_SELF']."?qid=".$row->question_id."&searchTxt=".rawurlencode($SEARCH['txt']));
    return;
  }
  $res = db_query("SELECT * FROM help_questions WHERE question_txt LIKE '%".db_escape_string($SEARCH['txt'])."%' OR MATCH(question_txt) against('".db_escape_string($SEARCH['txt'])."') ORDER BY visit_counter DESC");
  showRelatedQuestions($res);
  
  ?><br><?
  
  ?>
  <table border="1" cellpadding="5" cellspacing="0" width="600">
    <tr>
      <td align="left" style="font-size:18px;"><b>These answers helped other users:</b></td>
    </tr>
    <?
  $COMMON_SOLUTIONS = array();
  $res = db_query("SELECT * FROM help_queries WHERE ( question_txt LIKE '%".db_escape_string($SEARCH['txt'])."%' OR MATCH(question_txt) AGAINST('".db_escape_string($SEARCH['txt'])."') ) AND (final_question_id != 0 AND final_answer_id != 0) ORDER BY COUNTER DESC LIMIT 5");
  while( $row = db_fetch_object($res) ) 
  {
    
    $res2 = db_query("SELECT * FROM help_questions WHERE question_id='".$row->final_question_id."'");
    if ( $row2 = db_fetch_object($res2) )
    {
      db_free_result($res2);
      ?>
      <tr>
        <td align="left">
          <div align="left" style="padding-left:25px;">
            <b>Q.</b> <u><?=$row2->question_txt?></u><br>
          </div>
          <?
          $res2 = db_query("SELECT * FROM help_answers WHERE answer_id='".$row->final_answer_id."'");
          if ( $row2 = db_fetch_object($res2))
          {
            ?>
            <div align="left" style="padding-left:25px;">
              <b>A.</b> <?=nl2br( $row2->answer_txt )?>
            </div>
            <br>
            <div align="left">
              <a href="send_results.php?qid=<?=$row->final_question_id?>&aid=<?=$row->final_answer_id?>&searchTxt=<?=rawurlencode($row->question_txt)?>&helped=yes">&lt; This answer helped me. &gt;</a>
            </div>
            <?
          }
          ?>
        </td>
      </tr>
      <?
    }
  }
  ?></table><?
}
else if ( $SEARCH['qid'] )
{  
  $res = db_query("SELECT * FROM help_questions WHERE question_id='".$SEARCH['qid']."'");
  if( !$row = db_fetch_object($res) ) return;
  db_free_result($res);
  
  // Display the title ( question )
  echo "<DIV ALIGN='CENTER' STYLE='font-size:18px;'><b>\"".$row->question_txt."\"</b></DIV>";
  
  echo "<br />";
  
  // Now lets get all the answers and related questions
  $RELATED_ANSWERS = array();
  $res = db_query("SELECT * FROM help_q_to_a WHERE question_id='".$SEARCH['qid']."'");
  while( $row = db_fetch_object($res) ) { $RELATED_ANSWERS[] = $row->answer_id; }
  db_free_result($res);
  showRelatedAnswers( $RELATED_ANSWERS );
  
  echo "<br>";
  
  $RELATED_QUESTIONS = array();
  $res = db_query("SELECT * FROM help_q_to_q WHERE parent_question_id='".$SEARCH['qid']."'");
  while( $row = db_fetch_object($res) ) { $RELATED_QUESTIONS[] = $row->question_id; }
  db_free_result($res);
  showRelatedQuestions( $RELATED_QUESTIONS );
}


  ?>
  <br>
  <table border="1" cellpadding="5" cellspacing="0" width="600">
    <tr>
      <td align="left" style="font-size:18px;"><b>Problem?</b></td>
    </tr>
    <tr>
      <td align="left">
        <a href="send_results.php?searchTxt=<?=rawurlencode($SEARCH['txt'])?>&helped=no">&lt; My question was not answered. &gt;</a>
      </td>
    </tr>
  </table>
  <?





function showRelatedAnswers( $resultOrArray )
{
  global $SEARCH;
  $idArray = array();
  $result  = null;
  ?>
  <table border="1" cellpadding="5" cellspacing="0" width="600">
    <tr>
      <td align="left" style="font-size:18px;"><b>Related Answers:</b></td>
    </tr>
  <?
  
  if ( is_resource($resultOrArray) )
  {
    $result = $resultOrArray;
  }
  else if ( is_array($resultOrArray) )
  {
    if ( count( $resultOrArray) ) {
      $result = db_query("SELECT * FROM help_answers WHERE answer_id IN ('".implode("','", $resultOrArray)."')");
    }
    else {
      $result = null;
    }
  }
  else if ( is_numeric($resultOrArray) )
  {
    $result = db_query("SELECT * FROM help_answers WHERE answer_id='".(int)$resultOrArray."'");;
  }
  else 
  {
    $result = null;
  }
  
  if ( $result )
  {
    while( $row = db_fetch_object($result) )
    {
      ?>
      <tr>
        <td align="left">
          <div style="padding-left:25px;">
            <?=nl2br( $row->answer_txt )?>
          </div>
          <br>
          <div align="left">
            <a href="send_results.php?qid=<?=$SEARCH['qid']?>&aid=<?=$row->answer_id?>&searchTxt=<?=rawurlencode($SEARCH['txt'])?>&helped=yes">&lt; This answer helped me. &gt;</a>
          </div>
        </td>
      </tr>
      <?
    }
  }
  ?>
  </table>
  <?
}


function showRelatedQuestions( $resultOrArray )
{
  global $SEARCH;
  $idArray = array();
  $result  = null;
  ?>
  <table border="1" cellpadding="5" cellspacing="0" width="600">
    <tr>
      <td align="left" style="font-size:18px;"><b>Related Questions:</b></td>
    </tr>
  <?

  if ( is_resource($resultOrArray) )
  {
    $result = $resultOrArray;
  }
  else if ( is_array($resultOrArray) )
  {
    if ( count( $resultOrArray) ) {
      $result = db_query("SELECT * FROM help_questions WHERE question_id IN ('".implode("','", $resultOrArray)."') ORDER BY visit_counter DESC");
    }
    else {
      $result = null;
    }
  }
  else if ( is_numeric($resultOrArray) )
  {
    $result = db_query("SELECT * FROM help_questions WHERE question_id='".(int)$resultOrArray."'");;
  }
  else 
  {
    $result = null;
  }
  
  if ( $result )
  {
    while( $row = db_fetch_object($result) )
    {
      ?>
      <tr>
        <td align="left" style="padding-left:25px;">
          <a href="<?=$_SERVER['PHP_SELF']?>?qid=<?=$row->question_id?>&searchTxt=<?=rawurlencode($SEARCH['txt'])?>"><?=htmlentities($row->question_txt, ENT_QUOTES)?></a>
        </td>
      </tr>
      <?
    }
  }
  ?>
  </table>
  <?
}


?>