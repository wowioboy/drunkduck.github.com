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

if ( isset($_POST['sendPost']) )
{
  db_query("INSERT INTO admin_blog (user_id, title, body, timestamp_date, ymd_date) VALUES ('".$USER->user_id."', '".db_escape_string($_POST['title'])."', '".db_escape_string($_POST['body'])."', '".time()."', '".YMD."')");
  $_GET['a'] = 'list';
  //unlink(HTML_CACHE_FOLDER.'/d2569efb8e129ebb32ed3f820592609c.cache');
}
else if ( isset($_POST['editPost']) ) {
  db_query("UPDATE admin_blog SET title='".db_escape_string($_POST['title'])."', body='".db_escape_string($_POST['body'])."', edit_user_id='".$USER->user_id."', edit_timestamp='".time()."' WHERE blog_id='".(int)$_POST['editPost']."'");
  $_GET['a'] = 'list';
  //unlink(HTML_CACHE_FOLDER.'/d2569efb8e129ebb32ed3f820592609c.cache');
}
else if ( isset($_POST['deleteBlogs']) )
{
  $delArray = array();
  foreach($_POST['deleteBlogs'] as $blog_id) {
    $delArray[] = (int)$blog_id;
  }
  db_query("DELETE FROM admin_blog WHERE blog_id IN ('".implode("','", $delArray)."')");
  $_GET['a'] = 'list';
  unlink(HTML_CACHE_FOLDER.'/d2569efb8e129ebb32ed3f820592609c.cache');
}

?>
<P>
  <A HREF='<?=$_SERVER['PHP_SELF']?>?a=post'>New Post</A>
  |
  <A HREF='<?=$_SERVER['PHP_SELF']?>?a=list'>List Posts</A>
</P>
<?



if ( ($_GET['a'] == 'post') || ($_GET['a'] == 'edit') )
{
  if ($_GET['a'] == 'edit') {
    $res  = db_query("SELECT * FROM admin_blog WHERE blog_id='".(int)$_GET['b']."'");
    $blog = db_fetch_object($res);
  }

  ?>
  <DIV STYLE='WIDTH:600px;' ALIGN='CENTER' CLASS='container'>
    <DIV CLASS='header' ALIGN='CENTER'>Post News</DIV>

    <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' WIDTH='100%'>
      <FORM ACTION='<?=$_SERVER['PHP_SELF']?>' METHOD='POST'>
      <TR>
        <TD ALIGN='LEFT'>
          Post Title<BR>
          <INPUT TYPE='TEXT' NAME='title' STYLE='width:100%;' VALUE='<?=htmlentities($blog->title, ENT_QUOTES)?>'>
        </TD>
      </TR>
      <TR>
        <TD ALIGN='LEFT'>
          Post Body<BR>

          <script language="JavaScript">
              textareaName = 'body';
          </script>
          <script src="../community/bbcode.js" language="JavaScript"></script>
          <TEXTAREA NAME='body' id='body' STYLE='width:100%;height:300px;'><?=$blog->body?></TEXTAREA>
        </TD>
      </TR>
      <TR>
        <TD ALIGN='CENTER'>
          <INPUT TYPE='SUBMIT' VALUE='Send!'>
        </TD>
      </TR>
      <?
        if ( $_GET['a'] == 'edit' ) {
          ?><INPUT TYPE='HIDDEN' NAME='editPost' VALUE='<?=(int)$_GET['b']?>'><?
        }
        else {
          ?><INPUT TYPE='HIDDEN' NAME='sendPost' VALUE='1'><?
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
        <TD ALIGN='LEFT' WIDTH='150'><B>Date</B></TD>
        <TD ALIGN='LEFT' WIDTH='400'><B>Title</B></TD>
        <TD ALIGN='LEFT' WIDTH='50' ALIGN='CENTER'><B>Delete?</B></TD>
      </TR>

  <?
  $res = db_query("SELECT * FROM admin_blog ORDER BY timestamp_date DESC");
  while($row = db_fetch_object($res))
  {
    ?>
      <TR>
        <TD ALIGN='LEFT' WIDTH='150'><?=strtoupper(date("F d, Y", $row->timestamp_date))?></TD>
        <TD ALIGN='LEFT' WIDTH='400'><A HREF='<?=$_SERVER['PHP_SELF']?>?a=edit&b=<?=$row->blog_id?>'><?=$row->title?></A></TD>
        <TD ALIGN='LEFT' WIDTH='50' ALIGN='CENTER'><INPUT TYPE='CHECKBOX' NAME='deleteBlogs[]' VALUE='<?=$row->blog_id?>' onClick="return confirm('Are you SURE you want to delete this post?');"></TD>
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