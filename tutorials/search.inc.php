<div align="left" class="header_title">
  Search Tutorials
</div>
<?
include_once(WWW_ROOT.'/tutorials/tutorials_tracking.inc.php');
include_once('tutorial_data.inc.php');
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

          <div align="left" id="tutorial_body">

            <div style="padding:5px;">

            <div class="rack_header" style="width:100%;">
              Search Tutorials
            </div>

            <p></p>

              <form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
              <div align="center">
                <input type="text" value="<?=$_GET['search']?>" name="search" style="width:242px;height:25px;font-size:19px;text-align:center;"><input type="submit" value="Search!" style="height:30px;width:70px;">
              </div>
              </form>

            <p></p>

              <?
              $TUTORIALS = array();
              $USER_IDS  = array();
              $TOTAL_RESULTS = 0;

              if ( isset($_GET['search']) )
              {
                $res = db_query("SELECT COUNT(*) as total_tutorials FROM tutorials WHERE MATCH(username, title, body, description) AGAINST ('".db_escape_string($_GET['search'])."') AND finalized='1'");
                $row = db_fetch_object($res);
                $TOTAL_RESULTS = $row->total_tutorials;

                $res = db_query("SELECT * FROM tutorials WHERE MATCH(username, title, body, description) AGAINST ('".db_escape_string($_GET['search'])."') AND finalized='1' LIMIT $PER_PAGE");
              }
              else if ( isset($_GET['tag']) )
              {
                $TUTORIAL_IDS = array();
                $res = db_query("SELECT * FROM tutorial_tags WHERE tag='".db_escape_string($_GET['tag'])."'");
                while($row = db_fetch_object($res) ) {
                  $TUTORIAL_IDS[$row->tutorial_id] = $row->tutorial_id;
                }

                $res = db_query("SELECT COUNT(*) as total_tutorials FROM tutorials WHERE tutorial_id IN ('".implode("','", $TUTORIAL_IDS)."') AND finalized='1' LIMIT $PER_PAGE");
                $row = db_fetch_object($res);
                $TOTAL_RESULTS = $row->total_tutorials;

                $res = db_query("SELECT * FROM tutorials WHERE tutorial_id IN ('".implode("','", $TUTORIAL_IDS)."') AND finalized='1' LIMIT $PER_PAGE");
              }
              else
              {
                $res = db_query("SELECT * FROM tutorials WHERE finalized='1' ORDER BY tutorial_id DESC LIMIT $PER_PAGE");
              }
              ?>

              <div align="left">
                <b>Results:</b> Your criteria gave <?=number_format($TOTAL_RESULTS)?> results
              </div>
            </div>

            <div style="background:white;">
              <?
              while($row = db_fetch_object($res) )
              {
                $USER_IDS[$row->user_id] = $row->user_id;
                $TUTORIALS[]             = $row;
              }



              $res = db_query("SELECT user_id, username FROM users WHERE user_id IN ('".implode("','", $USER_IDS)."')");
              while( $row = db_fetch_object($res) ) {
                $USER_IDS[$row->user_id] = $row->username;
              }
              db_free_result($res);


              foreach($TUTORIALS as $row )
              {
                ?>
                <div style="padding:10px;border-bottom:1px solid #828282;" align="left">
                  <div align="left">
                    <a href="view.php?id=<?=$row->tutorial_id?>" style="font-weight:bold;color:#002e57;font-size:15px;"><?=htmlentities($row->title, ENT_QUOTES)?></a>
                  </div>

                  <div style="font-weight:normal;color:#757575;font-size:12px;">
                    12/07/06 by <a href="http://<?=USER_DOMAIN?>/<?=$USER_IDS[$row->user_id]?>" style="color:#757575;"><?=$USER_IDS[$row->user_id]?></a>
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

        </div>

      </td>
    </tr>
  </table>

</div>