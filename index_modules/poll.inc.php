<table width="280" border="0" cellpadding="0" cellspacing="0" class="poll">
  <tr>
    <td colspan="2" valign="top"><img src="<?=IMAGE_HOST?>/site_gfx_new/DD_hdr_pollquestion.png" width="280" height="25" class="headerimg" /></td>
  </tr>
  <tr>
    <td colspan="2" class="padding" style="background-image: url(<?=IMAGE_HOST?>/site_gfx_new/bg_grad.png);">
      <?
    
      $res = db_query("SELECT * FROM poll_questions WHERE is_live='1' ORDER BY id DESC LIMIT 1");
      if ( !$qRow = db_fetch_object($res) )
      {
        db_free_result($res);
        ?><p><strong>No Questions Today</strong></p><?
      }
      else 
      {
        db_free_result($res);
        ?>
        <p><strong><?=$qRow->question?></strong></p>
        <?
        
        if ( isset($_GET['results']) || ($_COOKIE['poll'] == $qRow->id) )
        {
          $maxWidth = 250;
          $total    = 0;
          $rows     = array();
          $res      = db_query("SELECT * FROM poll_answers WHERE question_id='".$qRow->id."' ORDER BY counter DESC");
          while( $aRow = db_fetch_object($res) ) 
          {
            $rows[] = $aRow;
            $total  += $aRow->counter;
          }
          db_free_result($res);
          foreach($rows as $aRow) {
            echo "<div align='left' style='font-size:9px;padding-left:5px;'>".$aRow->answer."<br><img src='".IMAGE_HOST."/site_gfx_new/bar.gif' height='10' width='".floor(($aRow->counter/$total)*$maxWidth)."'>".floor(($aRow->counter/$total)*100)."%</div>";
          }
        }
        else 
        {
          ?>
          <form action="http://<?=DOMAIN?>/poll_submit.php" method="POST">
          <?
          $res = db_query("SELECT * FROM poll_answers WHERE question_id='".$qRow->id."' ORDER BY id ASC");
          while($aRow = db_fetch_object($res))
          {
            ?>
            <p>
              <label>
                <input name="aid" type="radio" value="<?=$aRow->id?>" />
              </label>
              <?=$aRow->answer?>
            </p>
            <?
          }
          db_free_result($res);
          ?>
          <p align='center'><input type="submit" value="Submit"></p>
          <input type="hidden" name="qid" value="<?=$qRow->id?>">
          </form>
          <?
        }
      }
      ?>
    </td>
  </tr>
</table>