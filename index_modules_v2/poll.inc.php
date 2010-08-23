<img src="<?=IMAGE_HOST_SITE_GFX?>/sect_poll.gif" width="212" height="30" />
<div class="narrow">

  <?
  $res = db_query("SELECT * FROM poll_questions WHERE is_live='1' AND category='".(int)$SUBDOM_TO_CAT[SUBDOM]."' ORDER BY id DESC LIMIT 1");
  if ( !$qRow = db_fetch_object($res) )
  {
    db_free_result($res);
    ?><h3>No Questions Today</h3><?
  }
  else
  {
    db_free_result($res);
    ?>
    <h3><?=$qRow->question?></h3>
    <?

    if ( isset($_GET['results']) || ($_COOKIE['poll'] == $qRow->id) )
    {
      $maxWidth = 190;
      $total    = 0;
      $rows     = array();
      $res      = db_query("SELECT * FROM poll_answers WHERE question_id='".$qRow->id."' ORDER BY counter DESC");
      while( $aRow = db_fetch_object($res) )
      {
        $rows[] = $aRow;
        $total  += $aRow->counter;
      }
      db_free_result($res);
      foreach($rows as $aRow)
      {
        ?>
        <div style="font-size: 10px; margin: 0px 0px 8px 0px;">
          <?=$aRow->answer?>
          <br>
          <img src='<?=IMAGE_HOST_SITE_GFX?>/bar.gif' height='10' width='<?=floor(($aRow->counter/$total)*$maxWidth)?>'><?=floor(($aRow->counter/$total)*100)?>%
        </div>
        <?
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
        <div style="font-size: 10px; margin: 0px 0px 8px 0px;">
          <label>
            <input name="aid" type="radio" value="<?=$aRow->id?>" />
          </label>
          <?=$aRow->answer?>
        </div>
        <?
      }
      db_free_result($res);
      ?>
      <p align='center'><input type="submit" value="Submit"></p>
      <p align='center'><a href="<?=$_SERVER['PHP_SELF']?>?results=view">See Results</a></p>
      <input type="hidden" name="qid" value="<?=$qRow->id?>">
      <input type="hidden" name="fromsubdom" value="<?=SUBDOM?>">
      </form>
      <?
    }
  }
  ?>

  <p align="center"><a href="/community/"><img src="<?=IMAGE_HOST_SITE_GFX?>/smbtns_quackabout.gif" width="151" height="14" border="0" /></a></p>
  <!--<p align="center"><a href="#"><img src="<?=IMAGE_HOST_SITE_GFX?>/smbtns_oldpolls.gif" width="56" height="14" border="0" /></a></p>-->
</div>