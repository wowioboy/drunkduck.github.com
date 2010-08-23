<?
include('tikimail_header.inc.php');
echo "<BR><BR>";
//require(WWW_ROOT.'/includes/packages/wordfilter_package/load.inc.php');

// commmunity_data has emotes.
include_once(WWW_ROOT.'/community/community_data.inc.php');
include_once(WWW_ROOT.'/community/community_functions.inc.php');

$res = db_query("SELECT * FROM mailbox WHERE mail_id='".(int)$_GET['mail_id']."' AND username_to='".$USER->username."'");
if( $row = db_fetch_object($res) )
{
  if ( $row->viewed == 0 )
  {
    db_query("UPDATE users SET pending_mail=pending_mail-1 WHERE username='".$USER->username."'");
    db_query("UPDATE mailbox SET viewed='1' WHERE mail_id='".(int)$_GET['mail_id']."'");
  }

  ?><table cellpadding="0" cellspacing="0" width="100%" class="community_postcont">
      <TR>
        <TD ALIGN='RIGHT' WIDTH='100' class='community_hdr'>
          <B>From</B>
        </TD>
        <TD ALIGN='LEFT' class="community_post" style="border-bottom:1px solid black;">
          <?
            if ( $row->non_username_from )  {
              echo $row->non_username_from;
            }
            else {
              echo username($row->username_from);
            }
          ?>
        </TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='100' class="community_hdr">
          <B>Subject</B>
        </TD>
        <TD ALIGN='LEFT' class="community_post" style="border-bottom:1px solid black;">
          <?=$row->title?>
        </TD>
      </TR>

      <TR>
        <TD VALIGN='TOP' ALIGN='RIGHT' WIDTH='100' class='community_hdr'>
          <B>Message</B>
        </TD>
        <TD ALIGN='LEFT'  class="community_post" style="padding-left:5px;">
          <div align='right' style="font-size:9px;">
            <?=date("M j,`y g:ia", $row->time_sent)?>
          </div>
          <hr style="border:1px solid white;height:1px;">
          <?=community_bb_code( nl2br($row->message) )?>
        </TD>
      </TR>

      <TR>
        <TD ALIGN='RIGHT' WIDTH='100' class='community_hdr'>
          <B>Action</B>
        </TD>
        <TD ALIGN='LEFT' class="community_post" style="border-top:1px solid black;">
          <A HREF="delete_mail.php?delete=<?=$row->mail_id?>">Delete this mail</A>
          <?=( ( ($USER->flags & FLAG_OVER_12) && (!$row->non_username_from) )?'| <A HREF="report.php?report='.$row->mail_id.'">Report this mail</A>':'')?>
          <?=( ( ($USER->flags & FLAG_OVER_12) && (!$row->non_username_from) )?'| <A HREF="author.php?reply='.$row->mail_id.'">Write Back</A>':'')?>
        </TD>
      </TR>
    </TABLE>
    <BR>
    <BR>
    <?
}

include('tikimail_footer.inc.php');
?>