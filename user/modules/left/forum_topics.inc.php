<div align="left" style="margin-bottom:5px;">
  <div style="font-size:16px;font-weight:bold;">
    Forum Topics Started
  </div>
  <?
  $res = db_query("SELECT * FROM community_topics WHERE user_id='".$viewRow->user_id."' AND category_id NOT IN ('245', '246') ORDER BY date_created DESC LIMIT 5");
  if ( db_num_rows($res) == 0 ) {
    ?>None.<?
  }
  else
  {
    ?><ul><?
    while( $row = db_fetch_object($res) )
    {
      ?><li><a href="http://<?=DOMAIN?>/community/view_topic.php?tid=<?=$row->topic_id?>&cid=<?=$row->category_id?>"><?=htmlentities($row->topic_name, ENT_QUOTES)?></a></li><?
    }
    ?></ul><?
  }
  ?>
</div>