<div align="left" class="header_title">
  Tutorials
</div>
<?
include_once(WWW_ROOT.'/tutorials/tutorials_tracking.inc.php');
?>
<div>

  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td align="left" width="310" valign="bottom">
        <img src="<?=IMAGE_HOST?>/tutorials/tut_header_v2.png">
      </td>
      <td align="center" valign="top" width="100%">
        <div align="center" class="thumblist_large_title">
          FEATURED TUTORIALS
        </div>
        <br>
        <table border="0" cellpadding="0" cellspacing="10" width="100%">
          <tr>
            <td align="center">
              <a href="http://www.drunkduck.com/tutorials/view.php?id=11"><img src="http://images.drunkduck.com/tutorials/content/1/1/11_47_thumb.jpg" border="0"></a>
              <br>
              <b>Drawing the Ozone way!</b>
              <br>
              <font style="font-size:9px;">
                07/16/2007 by <a href="http://user.drunkduck.com/ozoneocean" style="color:#ffffff;">ozoneocean</a>
              </font>
            </td>
            <td align="center">
              <a href="http://www.drunkduck.com/tutorials/view.php?id=12"><img src="http://images.drunkduck.com/tutorials/content/1/2/12_52_thumb.jpg" border="0"></a>
              <br>
              <b>Creating Rain Effects</b>
              <br>
              <font style="font-size:9px;">
                07/17/2007 by <a href="http://user.drunkduck.com/silentkitty" style="color:#ffffff;">silentkitty</a>
              </font>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</div>


<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="160" align="center" valign="top">
      <? include(WWW_ROOT.'/ads/ad_includes/main_template/160x600.html'); ?>
    </td>
    <td valign="top">
      <div align="center" style="padding:22px;">
        <div class="rack_header" style="width:100%;">
          Search Tutorials
        </div>
        <br>
        <form action="search.php" method="GET">
          <input type="text" name="search" style="width:242px;height:25px;font-size:19px;text-align:center;"><input type="submit" value="Search!" style="height:30px;width:70px;">
        </form>
        <br>
        <br>
        <div class="rack_header" style="width:100%;">
          Popular Tags
        </div>
        <br>
        <?
        $res = db_query("SELECT * FROM tutorial_tags_used WHERE counter>0 ORDER BY counter DESC LIMIT 30");
        while($row = db_fetch_object($res) ) {
          ?><a href="search.php?tag=<?=$row->tag?>"><?=$row->tag?></a> <?
        }
        ?>
        <br>
        <br>
        <form action="create.php" method="GET">
          <input type="submit" value="Create a Tutorial!" style="width:120px;">
        </form>
      </div>

      <div style="padding:22px;" align="right">

        <div style="background:#ffffff;width:550px;" align="center">
          <div class="rack_header">
            New Tutorials
          </div>
          <?
          $TUTORIALS = array();
          $USER_IDS  = array();
          $res = db_query("SELECT * FROM tutorials WHERE finalized='1' ORDER BY tutorial_id DESC");
          while($row = db_fetch_object($res) )
          {
            $USER_IDS[$row->user_id] = $row->user_id;
            $TUTORIALS[] = $row;
          }
          db_free_result($res);

          $res = db_query("SELECT user_id, username FROM users WHERE user_id IN ('".implode("','", $USER_IDS)."')");
          while( $row = db_fetch_object($res) ) {
            $USER_IDS[$row->user_id] = $row->username;
          }
          db_free_result($res);


          foreach($TUTORIALS as $row )
          {
            ?>
            <div style="margin:10px;border-bottom:1px solid #828282;" align="left">
              <div align="left">
                <a href="view.php?id=<?=$row->tutorial_id?>" style="font-weight:bold;color:#002e57;font-size:15px;"><?=htmlentities($row->title, ENT_QUOTES)?></a>
              </div>

              <div style="font-weight:normal;color:#757575;font-size:12px;">
                <?=date('m/d/Y', $row->timestamp)?> by <a href="http://<?=USER_DOMAIN?>/<?=$USER_IDS[$row->user_id]?>" style="color:#757575;"><?=$USER_IDS[$row->user_id]?></a>
              </div>

              <div style="font-weight:normal;color:#000000;font-size:12px;">
                <?=htmlentities($row->description, ENT_QUOTES)?>
              </div>

              <div align="left">
                <?
                for($i=1; $i<6; $i++) {
                  if ( $row->vote_avg > $i ) {
                    ?><img src="<?=IMAGE_HOST?>/ratingon.png" border="0"><?
                  }
                  else {
                    ?><img src="<?=IMAGE_HOST?>/ratingoff.png" border="0"><?
                  }
                }
                ?>
              </div>
            </div>
            <?
          }
          ?>

        </div>

      </div>
    </td>
  </tr>
</table>