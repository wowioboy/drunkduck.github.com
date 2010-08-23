<?
include('games_admin_header.inc.php');

if ( $_GET['gid'] && isset($_GET['live']) ) {
  db_query("UPDATE game_info SET is_live='".(int)$_GET['live']."' WHERE game_id='".(int)$_GET['gid']."'");
}

?>
<DIV STYLE='WIDTH:500px;' ALIGN='CENTER' CLASS='container'>
  <DIV CLASS='header' ALIGN='CENTER'>Game List:</DIV>
<?
echo "<TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' WIDTH='500'>";
  echo "<TR>
          <TD ALIGN='CENTER'><B>Game ID</B></TD>
          <TD ALIGN='LEFT'><B>Title</B></TD>
          <TD ALIGN='CENTER'><B>Live?</B></TD>
        </TR>";

$res = db_query("SELECT * FROM game_info ORDER BY game_id DESC");
while($row = db_fetch_object($res))
{
  echo "<TR ".( (!$row->is_live)?"BGCOLOR='#DEDEDE'":"").">
          <TD ALIGN='CENTER'>".$row->game_id."</TD>
          <TD ALIGN='LEFT'><img src='".IMAGE_HOST."/games/thumbnails/game_".$row->game_id."_tn_small.gif' align='absmiddle'> <A HREF='edit_game.php?gid=".$row->game_id."'>".$row->title."</A></TD>
          <TD ALIGN='CENTER'>";

          if ( $row->is_live ) {
            echo "<A HREF='".$_SERVER['PHP_SELF']."?gid=".$row->game_id."&live=0'>Yes</A>";
          }
          else {
            echo "<A HREF='".$_SERVER['PHP_SELF']."?gid=".$row->game_id."&live=1'>No</A>";
          }

  echo "  </TD>
        </TR>";


  $GAME_IDS[$row->game_id] = $row->title;
}

echo "</TABLE>";