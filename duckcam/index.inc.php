<?
return;
?>
<link href="http://<?=DOMAIN?>/games/games.css" rel="stylesheet" type="text/css" />
<h1 align="left">DuckCAM</h1>

<div class="gameContent">
  <embed src="http://player.stickam.com/stickamPlayer/174625835-2743920" type="application/x-shockwave-flash" width="480" height="480" scale="noscale" allowScriptAccess="always"></embed>

  <p>&nbsp;</p>

  <div align="center"><a href="/community/view_topic.php?cid=226&tid=30032">Discuss the DuckCam HERE</a></div>

  <p>&nbsp;</p>

  <?
/*
  if ( $USER ) {
    $username = $USER->username;
  }
  else {
    $username = 'Guest:'.$_SERVER['REMOTE_ADDR'];
  }

  db_query("INSERT INTO duckcam_watchers (username, timestamp) VALUES ('".db_escape_string($username)."', '".time()."')");
  if ( db_rows_affected() < 1 ) {
    db_query("UPDATE duckcam_watchers SET timestamp='".time()."' WHERE username='".db_escape_string($username)."'");
  }
  db_query("DELETE FROM duckcam_watchers WHERE timestamp<'".(time()-900)."'"); // 15 minutes

  $res = db_query("SELECT * FROM duckcam_watchers ORDER BY timestamp DESC");
*/
  ?>

</div>