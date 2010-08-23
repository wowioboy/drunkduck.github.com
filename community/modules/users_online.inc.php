<?
$res = db_query("SELECT user_id, username, flags FROM users WHERE last_seen>=".(time()-(60*15))." ORDER BY user_id ASC");
?>
<div align="center" class="user_list">
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td bgcolor="#001D37" style="color:white;">
        <?
        $ADMINS     = array();
        $MODERATORS = array();
        $USERS      = array();
        while($row = db_fetch_object($res))
        {
          if ( ($row->flags & FLAG_IS_ADMIN) ) {
            $ADMINS[] = username($row->username);
          }
          else if ( ($row->flags & FLAG_IS_MOD) ) {
            $MODERATORS[] = username($row->username);
          }
          else if ( $row->user_id != 1 ) {
            $USERS[] = username($row->username);
          }
        }



          if ( count($ADMINS) )
          {
            ?>
            <div align="center" style="width:90%;">
              <?=number_format(count($ADMINS))?> Admins Online:
              <br>
              <?=implode(", ", $ADMINS)?>
            </div>
            <?
          }



          if ( count($MODERATORS) )
          {
            ?>
            <br>
            <div align="center" style="width:90%;">
              <?=number_format(count($MODERATORS))?> Moderators Online:
              <br>
              <?=implode(", ", $MODERATORS)?>
            </div>
            <?
          }

          ?>
          <br>
          <div align="center" style="width:90%;">
            <?=number_format(count($USERS))?> Users Online:
            <br>
            <table border="0" cellpadding="1" cellspacing="0" width="100%">
              <tr>
                <?
                $cols = 5;
                $ct = -1;
                foreach($USERS as $u)
                {
                  if ( ++$ct%$cols == 0 ) {
                    ?></tr><tr><?
                  }
                  ?><td align="left"><?=$u?></td><?
                }

                for($i=0;$i<($cols-1)-($ct%$cols);$i++) {
                  ?><td>&nbsp;</td><?
                }
                ?>
              </tr>
            </table>
          </div>
      </td>
    </tr>
  </table>
</div>