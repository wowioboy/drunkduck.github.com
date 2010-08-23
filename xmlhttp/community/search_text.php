<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../../includes/global.inc.php');


$search = ltrim($_POST['value']);
if ( strlen($search) < 3 ) return;





$POST_ROWS  = array();
$TOPIC_ROWS = array();
$CAT_ROWS   = array();


$res = db_query("SELECT * FROM community_posts WHERE MATCH(post_body) AGAINST('".db_escape_string($search)."') GROUP BY topic_id LIMIT 25");
while( $row = db_fetch_object($res) )
{
  $POST_ROWS[$row->post_id]   = $row;
  $TOPIC_ROWS[$row->topic_id] = null;
}
db_free_result($res);



$res = db_query("SELECT * FROM community_topics WHERE topic_id IN ('".implode("','", array_keys($TOPIC_ROWS))."')");
while( $row = db_fetch_object($res) )
{
  $TOPIC_ROWS[$row->topic_id]   = $row;
  $CAT_ROWS[$row->category_id]  = null;
}
db_free_result($res);





$res = db_query("SELECT * FROM community_categories WHERE category_id IN ('".implode("','", array_keys($CAT_ROWS) )."')");
while($row = db_fetch_object($res) )
{
  $CAT_ROWS[$row->category_id] = $row;
}
db_free_result($res);


include(WWW_ROOT.'/community/community_data.inc.php');
include(WWW_ROOT.'/community/community_functions.inc.php');

$ct = 0;
?><ul><?
foreach($POST_ROWS as $row)
{
  $row->post_body = html_entity_decode($row->post_body);
  if ( ($CAT_ROWS[$TOPIC_ROWS[$row->topic_id]->category_id]->flags & FORUM_FLAG_ADMIN_ONLY)  )
  {

    if ( $USER->flags & FLAG_IS_ADMIN )
    {
      ?><li id="<?=$row->topic_id?>"><?=htmlentities($TOPIC_ROWS[$row->topic_id]->topic_name, ENT_QUOTES)?><span class="informal"><?=nl2br(bbcode( trim(substr($row->post_body, 0, 100)).'...'))?><span class="informal_rt"><?=number_format($TOPIC_ROWS[$row->topic_id]->post_ct)?> posts</span></span></li><?
      if ( ++$ct%5 == 0 ) break;
    }

  }
  else if ( ($CAT_ROWS[$TOPIC_ROWS[$row->topic_id]->category_id]->flags & FORUM_FLAG_MOD_ONLY) )
  {

    if ( ($USER->flags & FLAG_IS_ADMIN) || ($USER->flags & FLAG_IS_MOD) )
    {
      ?><li id="<?=$row->topic_id?>"><?=htmlentities($TOPIC_ROWS[$row->topic_id]->topic_name, ENT_QUOTES)?><span class="informal"><?=nl2br(bbcode( trim(substr($row->post_body, 0, 100)).'...'))?><span class="informal_rt"><?=number_format($TOPIC_ROWS[$row->topic_id]->post_ct)?> posts</span></span></li><?
      if ( ++$ct%5 == 0 ) break;
    }

  }
  else
  {
    ?><li id="<?=$row->topic_id?>"><?=htmlentities($TOPIC_ROWS[$row->topic_id]->topic_name, ENT_QUOTES)?><span class="informal"><?=nl2br(bbcode( trim(substr($row->post_body, 0, 100)).'...'))?><span class="informal_rt"><?=number_format($TOPIC_ROWS[$row->topic_id]->post_ct)?> posts</span></span></li><?
    if ( ++$ct%5 == 0 ) break;
  }

}
?></ul>