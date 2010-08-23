<?
require(WWW_ROOT.'/includes/packages/wordfilter_package/load.inc.php');

if ( $_GET['del_mail'] )
{
  db_query("DELETE FROM mailbox WHERE mail_id='".(int)$_GET['del_mail']."'");
  db_query("DELETE FROM reported_mail WHERE mail_id='".(int)$_GET['del_mail']."'");
}
else if ( $_GET['del_report'] )
{
  db_query("DELETE FROM reported_mail WHERE mail_id='".(int)$_GET['del_report']."'");
}

$res = db_query("SELECT * FROM reported_mail ORDER BY mail_id DESC LIMIT 10");
while($row = db_fetch_object($res))
{
  $MAIL_IDS[$row->mail_id] = $row->cpu_flagged;
}
db_free_result($res);

$res = db_query("SELECT * FROM mailbox WHERE mail_id IN ('".implode("','", array_keys($MAIL_IDS))."')");
while($row = db_fetch_object($res))
{
  echo "<TABLE BORDER='0' CELLPADDING='0' CELLSPACING='10' WIDTH='600' STYLE='border:1px solid black;'>";
  echo "<TR>
          <TD ALIGN='RIGHT' WIDTH='100'>
            <B>To</B>
          </TD>
          <TD ALIGN='LEFT'>
            ".username($row->username_to)."
          </TD>
        </TR>
        
        <TR>
          <TD ALIGN='RIGHT' WIDTH='100'>
            <B>From</B>
          </TD>
          <TD ALIGN='LEFT'>
            ".username($row->username_from)."
          </TD>
        </TR>
        
        <TR>
          <TD ALIGN='RIGHT' WIDTH='100'>
            <B>Subject</B>
          </TD>
          <TD ALIGN='LEFT'>";
  
  $MSG   = $row->title;
  $WORDS = getAllBadWords($MSG);

  foreach($WORDS as $word) {
    $MSG = str_replace($word->badWord, "<FONT COLOR='red'><U>".$word->badWord."</U></FONT>", $MSG);
  }

  echo      $MSG;
  
  echo   "</TD>
        </TR>
        
        <TR>
          <TD ALIGN='RIGHT' WIDTH='100'>
            <B>Message</B>
          </TD>
          <TD ALIGN='LEFT'>";
  
  $MSG   = $row->message;
  $WORDS = getAllBadWords($MSG);

  foreach($WORDS as $word) {
    $MSG = str_replace($word->badWord, "<FONT COLOR='red'><U>".$word->badWord."</U></FONT>", $MSG);
  }
  
  echo    bbcode(nl2br($MSG));
  echo   "</TD>
        </TR>
        
        <TR>
          <TD ALIGN='RIGHT' WIDTH='100'>
            <B>Action</B>
          </TD>
          <TD ALIGN='LEFT'>
            <A HREF='".$_SERVER['PHP_SELF']."?del_mail=".$row->mail_id."'>Delete this mail.</A> | <A HREF='".$_SERVER['PHP_SELF']."?del_report=".$row->mail_id."'>Delete this report.</A>
          </TD>
        </TR>";
  echo "</TABLE><BR><BR>";
}
?>