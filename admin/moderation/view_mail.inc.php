<?


if ( $_GET['mail_id'] )
{
  $res = db_query("SELECT * FROM mailbox WHERE mail_id='".(int)$_GET['mail_id']."'");
  if($row = db_fetch_object($res))
  {
    echo "<TABLE BORDER='0' CELLPADDING='5' CELLSPACING='10' WIDTH='600' STYLE='border:1px solid black;'>";
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
    echo      $MSG;

    echo   "</TD>
          </TR>

          <TR>
            <TD ALIGN='RIGHT' WIDTH='100'>
              <B>Message</B>
            </TD>
            <TD ALIGN='LEFT'>";

    $MSG   = $row->message;

    echo    bbcode(nl2br($MSG));
    echo   "</TD>
          </TR>
        </TABLE>
        <BR>
        <BR>";
  }
}
else if ( $_GET['u'] )
{
  ?><table border="1" cellpadding="0" cellspacing="3" width="100%"><?
  $res = db_query("SELECT * FROM mailbox WHERE username_from='".db_escape_string(trim($_GET['u']))."' OR username_to='".db_escape_string(trim($_GET['u']))."' ORDER BY mail_id DESC LIMIT 500");
  while($row = db_fetch_object($res))
  {
    ?><tr>
        <td align="left"><?=$row->username_from?></td>
        <td align="left"><a href="<?=$_SERVER['PHP_SELF']?>?mail_id=<?=$row->mail_id?>"><?=$row->title?></a></td>
      </tr><?
  }
  ?></table><?
}
else
{
  ?><table border="1" cellpadding="0" cellspacing="3" width="100%"><?
  $res = db_query("SELECT * FROM mailbox ORDER BY mail_id DESC LIMIT 500");
  while($row = db_fetch_object($res))
  {
    ?><tr>
        <td align="left"><?=$row->username_from?></td>
        <td align="left"><a href="<?=$_SERVER['PHP_SELF']?>?mail_id=<?=$row->mail_id?>"><?=$row->title?></a></td>
      </tr><?
  }
  ?></table><?
}
?>