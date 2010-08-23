<div align="left" class="header_title">
  Read Tutorial
</div>
<?
include_once(WWW_ROOT.'/tutorials/tutorials_tracking.inc.php');
?>
<link href="tutorials.css" rel="stylesheet" type="text/css" />

<div>

  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td align="left" width="160" valign="top">
        <?
        include('tutorial_side_nav.inc.php');
        ?>
      </td>
      <td align="center" valign="top" width="100%">

        <div style="margin:10px;">

          <div align="left">

            <?
            if ( $_GET['finalize'] ) {
              if ( $USER->flags & FLAG_IS_ADMIN ) {
                db_query("UPDATE tutorials SET finalized='1' WHERE tutorial_id='".(int)$_GET['finalize']."'");
              }
              else {
                db_query("UPDATE tutorials SET finalized='1' WHERE tutorial_id='".(int)$_GET['finalize']."' AND user_id='".$USER->user_id."'");
              }
              header('Location: view.php?id='.$_GET['finalize']);
              return;
            }

            if ( isset($_GET['id']) )
            {
              if ( $USER->flags & FLAG_IS_ADMIN ) {
                $res = db_query("SELECT * FROM tutorials WHERE tutorial_id='".(int)$_GET['id']."'");
              }
              else {
                $res = db_query("SELECT * FROM tutorials WHERE tutorial_id='".(int)$_GET['id']."' AND (finalized='1' OR user_id='".$USER->user_id."')");
              }
              if ( $TUTORIAL_ROW = db_fetch_object($res) )
              {
                $res = db_query("SELECT * FROM users WHERE user_id='".$TUTORIAL_ROW->user_id."'");
                $uRow = db_fetch_object($res);

                if ( $TUTORIAL_ROW->finalized != 1 )
                {
                  ?>
                  <div align="center" style="border:1px solid red;color:black;margin-bottom:5px;padding:5px;">
                    <form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
                      Your tutorial is not yet live.
                      You must click the "finalize" link below to make it available to the public.<br>
                      <input type="submit" value="Finalize!">
                      <input type="hidden" name="finalize" value="<?=$TUTORIAL_ROW->tutorial_id?>">
                    </form>
                  </div>
                  <?
                }

                if ( $TUTORIAL_ROW->user_id == $USER->user_id )
                {
                  ?>
                  <div align="center" style="border:1px solid red;color:black;margin-bottom:5px;padding:5px;">

                    You created this tutorial. You have the option to delete or edit it.<br>

                    <form action="create.php" method="GET">
                      <input type="submit" value="Edit!">
                      <input type="hidden" name="edit_id" value="<?=$TUTORIAL_ROW->tutorial_id?>">
                    </form>

                    <form action="delete.php" method="POST">
                      <input type="submit" value="Delete!">
                      <input type="hidden" name="tutorial_id" value="<?=$TUTORIAL_ROW->tutorial_id?>">
                      <input type="hidden" name="ck" value="<?=md5($TUTORIAL_ROW->tutorial_id.'salt')?>">
                    </form>

                  </div>
                  <?
                }
                else if ( $USER->flags & FLAG_IS_ADMIN )
                {
                  ?>
                  <div align="center" style="border:1px solid red;color:black;margin-bottom:5px;padding:5px;">

                    You have the option to delete or edit this.<br>

                    <form action="create.php" method="GET">
                      <input type="submit" value="Edit!">
                      <input type="hidden" name="edit_id" value="<?=$TUTORIAL_ROW->tutorial_id?>">
                    </form>

                    <form action="delete.php" method="POST">
                      <input type="submit" value="Delete!">
                      <input type="hidden" name="tutorial_id" value="<?=$TUTORIAL_ROW->tutorial_id?>">
                      <input type="hidden" name="ck" value="<?=md5($TUTORIAL_ROW->tutorial_id.'salt')?>">
                    </form>

                  </div>
                  <?
                }

                ?>
                <div style="color:#3f424b;font-size:24px;font-weight:bold;" align="left"><?=$TUTORIAL_ROW->title?></div>
                <div style="color:#3f424b;font-size:12px;" align="left">Tutorial by <a href="http://<?=USER_DOMAIN?>/<?=$uRow->username?>" id="tutorial"><?=$uRow->username?></a></div>
                <div style="color:#3f424b;font-size:12px;" align="left">Created: <?=date("m/d/Y", $TUTORIAL_ROW->timestamp)?></div>
                <?
                $res = db_query("SELECT * FROM tutorial_votes WHERE tutorial_id='".$TUTORIAL_ROW->tutorial_id."' AND user_id='".$USER->user_id."'");
// don't know why the above line doesn't work, but commented it out and
// changed the code below to allow multiple ratings
//if (1)
                if( !$row = db_fetch_object($res) )
                {
                  ?>
                  <div style="color:#3f424b;font-size:12px;" align="left">
                    <table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="left">
                          Rate this tutorial:
                        </td>
                        <td align="left">
                          <a href="rate.php?id=<?=$TUTORIAL_ROW->tutorial_id?>&rate=1"><img src="<?=IMAGE_HOST?>/ratingon.png" border="0"></a><a href="rate.php?id=<?=$TUTORIAL_ROW->tutorial_id?>&rate=2"><img src="<?=IMAGE_HOST?>/ratingon.png" border="0"></a><a href="rate.php?id=<?=$TUTORIAL_ROW->tutorial_id?>&rate=3"><img src="<?=IMAGE_HOST?>/ratingon.png" border="0"></a><a href="rate.php?id=<?=$TUTORIAL_ROW->tutorial_id?>&rate=4"><img src="<?=IMAGE_HOST?>/ratingon.png" border="0"></a><a href="rate.php?id=<?=$TUTORIAL_ROW->tutorial_id?>&rate=5"><img src="<?=IMAGE_HOST?>/ratingon.png" border="0"></a>
                        </td>
                      </tr>
                    </table>
                  </div>
                  <?
                }
                else
                {
                  ?>
                  <div style="color:#3f424b;font-size:12px;" align="left">
                    <?
                    for($i=1; $i<6; $i++) {
                      if ( $TUTORIAL_ROW->vote_avg > $i ) {
                        ?><img src="<?=IMAGE_HOST?>/ratingon.png" border="0"><?
                      }
                      else {
                        ?><img src="<?=IMAGE_HOST?>/ratingoff.png" border="0"><?
                      }
                    }
                    ?>
                  </div>
                  <?
                }
                ?>
                <div style="color:#3f424b;font-size:12px;" align="left">
                Tags:
                <?
                $res = db_query("SELECT * FROM tutorial_tags WHERE tutorial_id='".$TUTORIAL_ROW->tutorial_id."'");
                if ( db_num_rows($res) < 1 ) {
                  ?>None<?
                }
                while($row = db_fetch_object($res) ){
                  ?><a href="search.php?tag=<?=$row->tag?>" id="tutorial"><?=$row->tag?></a> <?
                }
                ?>
                </div>
              </div>


             <div align="left" id="tutorial_body">

                <?=nl2br( BBCode($TUTORIAL_ROW->body) )?>

                <p align="center">
                  <?
                  $res = db_query("SELECT * FROM tutorial_images WHERE tutorial_id='".$TUTORIAL_ROW->tutorial_id."' ORDER BY order_id ASC");
                  while($row = db_fetch_object($res) )
                  {
                    $ARTICLE_ID_2 = str_pad( $row->tutorial_id, 2, '0', STR_PAD_LEFT);

                    ?><p align="center">
                          <a href="http://images.drunkduck.com/tutorials/content/<?=(int)($ARTICLE_ID_2{0})?>/<?=(int)($ARTICLE_ID_2{1})?>/<?=$row->tutorial_id?>_<?=$row->image_id?>.<?=$row->file_ext?>" target="_blank"><img src="http://images.drunkduck.com/tutorials/content/<?=(int)($ARTICLE_ID_2{0})?>/<?=(int)($ARTICLE_ID_2{1})?>/<?=$row->tutorial_id?>_<?=$row->image_id?>_thumb.<?=$row->file_ext?>" style="border:5px solid #cecece;"></a>
                          <br>(Click to enlarge)
                      </p><?
                  }
                  ?>
                </p>

              </div>

              <?
              }
              else
              {
                ?>Tutorial not found.<?
              }
            }
            else
            {
              ?>Tutorial not found.<?
            }
            ?>
        </div>

      </td>
    </tr>
  </table>

</div>
