<?
  include('tikimail_header.inc.php');

  $P = (int)$_GET['p']-1;
  if ( $P < 0 ) $P = 0;
  $ITEMS_PER_PAGE = 50;
  $TOTAL_MAIL = $TOTAL_MAIL; // defined in tikimail_header.inc.php

  ?>
  <br><br>
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="community_general_container">
  <form action='delete_mail.php' method='POST'>
  <tr>
    <td align='center' width='50' class='community_hdr'>delete?</td>
    <td align='center' class='community_hdr'>from</td>
    <td align='center' class='community_hdr'>subject</td>
    <td align='center' width='50' class='community_hdr'>status</td>
  </tr>
  <?
  if ( ($P) > 0 )
  {
    ?>
    <tr>
      <td align="left" colspan="4" style="padding:3px;"><a href="<?=$_SERVER['PHP_SELF']?>?p=<?=$P?>"><b>Previous <?=$ITEMS_PER_PAGE?></b></a></td>
    </tr>
    <?
  }
  $res = db_query("SELECT * FROM mailbox WHERE username_to='".$USER->username."' ORDER BY time_sent DESC LIMIT ".($P*$ITEMS_PER_PAGE).",".$ITEMS_PER_PAGE);
  while( $row = db_fetch_object($res) )
  {
    ?>
    <TR <?=( (++$ct%2==0)?"BGCOLOR='#001D37'":"" )?>>
      <TD ALIGN='CENTER' WIDTH='50'>
        <INPUT TYPE='CHECKBOX' NAME='delete[]' VALUE='<?=$row->mail_id?>'>
      </TD>
      <TD ALIGN='LEFT' WIDTH='150'><B><?
        if ( $row->non_username_from )  {
          echo $row->non_username_from;
        }
        else {
          echo username($row->username_from);
        }
      ?></B></TD>
      <TD ALIGN='LEFT' WIDTH='100%'><A HREF='read.php?mail_id=<?=$row->mail_id?>'><?=htmlentities($row->title, ENT_QUOTES)?></A></TD>
      <TD ALIGN='CENTER' WIDTH='50'>
        <?
          if ( $row->viewed == 0 ) {
            ?><IMG SRC='<?=IMAGE_HOST?>/mail/mail_new.gif' ALT='New Message!' TITLE='New Message!' ALT='New Message!'><?
          }
          else {
            ?><IMG SRC='<?=IMAGE_HOST?>/mail/mail.gif' ALT='New Message!' TITLE='Old Message.' ALT='Old Message.'><?
          }
        ?>
      </TD>
    </TR>
    <?
  }

  if ( $TOTAL_MAIL > ( ($P+1) * $ITEMS_PER_PAGE ) )
  {
    ?>
    <tr>
      <td align="right" colspan="4" style="padding:3px;"><a href="<?=$_SERVER['PHP_SELF']?>?p=<?=($P+2)?>"><b>Next <?=$ITEMS_PER_PAGE?></b></a></td>
    </tr>
    <?
  }

  ?></TABLE>
    <INPUT TYPE='SUBMIT' VALUE='Delete Selected Messages'>
    </FORM><?

  include('tikimail_footer.inc.php');
?>