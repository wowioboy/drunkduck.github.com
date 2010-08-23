<?
/*
CREATE TABLE admin_blog
(
  blog_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT(11) NOT NULL,
  title   VARCHAR(100),
  body    TEXT,
  timestamp_date INT(11) NOT NULL,
  ymd_date       INT(8)  NOT NULL,
  INDEX(ymd_date),
  INDEX(timestamp_date)
);
*/

if ( isset($_POST['send']) )
{
  db_query("INSERT INTO faq (question, answer) VALUES ('".db_escape_string($_POST['question'])."', '".db_escape_string($_POST['answer'])."')");
  $_GET['a'] = 'list';
}
else if ( isset($_POST['edit']) ) {
  db_query("UPDATE faq SET question='".db_escape_string($_POST['question'])."', answer='".db_escape_string($_POST['answer'])."' WHERE id='".(int)$_POST['edit']."'");
  $_GET['a'] = 'list';
}
else if ( isset($_POST['delete']) )
{
  $delArray = array();
  foreach($_POST['delete'] as $blog_id) {
    $delArray[] = (int)$blog_id;
  }
  db_query("DELETE FROM faq WHERE id IN ('".implode("','", $delArray)."')");
  $_GET['a'] = 'list';
}

?>
<P>
  <A HREF='<?=$_SERVER['PHP_SELF']?>?a=post'>New Question</A>
  |
  <A HREF='<?=$_SERVER['PHP_SELF']?>?a=list'>List Questions</A>
</P>
<?



if ( ($_GET['a'] == 'post') || ($_GET['a'] == 'edit') )
{
  if ($_GET['a'] == 'edit') {
    $res  = db_query("SELECT * FROM faq WHERE id='".(int)$_GET['b']."'");
    $blog = db_fetch_object($res);
  }

  ?>
  <DIV STYLE='WIDTH:600px;' ALIGN='CENTER' CLASS='container'>
    <DIV CLASS='header' ALIGN='CENTER'>New Question</DIV>

    <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' WIDTH='100%'>
      <FORM ACTION='<?=$_SERVER['PHP_SELF']?>' METHOD='POST'>
      <TR>
        <TD ALIGN='LEFT'>
          Question<BR>
          <INPUT TYPE='TEXT' NAME='question' STYLE='width:100%;' VALUE='<?=htmlentities($blog->question, ENT_QUOTES)?>'>
        </TD>
      </TR>
      <TR>
        <TD ALIGN='LEFT'>
          Answer<BR>

          <script language="JavaScript">
              textareaName = 'answer';
          </script>
          <script src="../../community/bbcode.js" language="JavaScript"></script>
          <TEXTAREA NAME='answer' id='answer' STYLE='width:100%;height:300px;'><?=htmlentities($blog->answer, ENT_QUOTES)?></TEXTAREA>
        </TD>
      </TR>
      <TR>
        <TD ALIGN='CENTER'>
          <INPUT TYPE='SUBMIT' VALUE='Send!'>
        </TD>
      </TR>
      <?
        if ( $_GET['a'] == 'edit' ) {
          ?><INPUT TYPE='HIDDEN' NAME='edit' VALUE='<?=(int)$_GET['b']?>'><?
        }
        else {
          ?><INPUT TYPE='HIDDEN' NAME='send' VALUE='1'><?
        }
      ?>
      </FORM>
    </TABLE>

  </DIV>
  <?
}
else if ( $_GET['a'] == 'list' )
{
  ?>
  <DIV STYLE='WIDTH:700px;' ALIGN='CENTER' CLASS='container'>
    <DIV CLASS='header' ALIGN='CENTER'>List News</DIV>

    <TABLE BORDER='0' CELLPADDING='3' CELLSPACING='0' WIDTH='100%'>
      <FORM ACTION='<?=$_SERVER['PHP_SELF']?>' METHOD='POST'>
      <TR>
        <TD ALIGN='LEFT'><B>Title</B></TD>
        <TD ALIGN='LEFT' WIDTH='50' ALIGN='CENTER'><B>Delete?</B></TD>
      </TR>

  <?
  $res = db_query("SELECT * FROM faq ORDER BY id DESC");
  while($row = db_fetch_object($res))
  {
    ?>
      <TR>
        <TD ALIGN='LEFT'><A HREF='<?=$_SERVER['PHP_SELF']?>?a=edit&b=<?=$row->id?>'><?=$row->question?></A></TD>
        <TD ALIGN='LEFT' WIDTH='50' ALIGN='CENTER'><INPUT TYPE='CHECKBOX' NAME='delete[]' VALUE='<?=$row->id?>' onClick="return confirm('Are you SURE you want to delete this?');"></TD>
      </TR>
    <?
  }

  ?>
      <TR>
        <TD ALIGN='CENTER' COLSPAN='3'>
          <INPUT TYPE='SUBMIT' VALUE='Edit!'>
        </TD>
      </TR>
      </FORM>
    </TABLE>

  </DIV>
  <?
}
?>