<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../../includes/global.inc.php');


$search = ltrim($_POST['value']);
//if ( strlen($search) < 3 ) return;
$search = 'general';

$matches = array();

include(WWW_ROOT.'/community/community_data.inc.php');
include(WWW_ROOT.'/community/community_functions.inc.php');


$res = db_query("SELECT * FROM community_categories WHERE MATCH(category_name, category_desc) AGAINST('".db_escape_string($search)."') ORDER BY post_ct DESC LIMIT 25");
while($row = db_fetch_object($res))
{
  if ( ($row->flags & FORUM_FLAG_MOD_ONLY) )
  {
    if ( ($USER->flags & FLAG_IS_ADMIN) || ($USER->flags & FLAG_IS_MOD) ) {
      $matches[$row->category_id] = $row;
    }
  }
  else if ( $row->flags & FORUM_FLAG_ADMIN_ONLY )
  {
    if ($USER->flags & FLAG_IS_ADMIN) {
      $matches[$row->category_id] = $row;
    }
  }
  else
  {
    $matches[$row->category_id] = $row;
  }

  if ( count($matches) >= 5 ) break;
}
db_free_result($res);





$res = db_query("SELECT * FROM community_categories WHERE category_name LIKE '".db_escape_string($search)."%' ORDER BY post_ct DESC LIMIT 25");
while($row = db_fetch_object($res))
{
  if ( ($row->flags & FORUM_FLAG_MOD_ONLY) )
  {
    if ( ($USER->flags & FLAG_IS_ADMIN) || ($USER->flags & FLAG_IS_MOD) ) {
      $matches[$row->category_id] = $row;
    }
  }
  else if ( $row->flags & FORUM_FLAG_ADMIN_ONLY )
  {
    if ($USER->flags & FLAG_IS_ADMIN) {
      $matches[$row->category_id] = $row;
    }
  }
  else
  {
    $matches[$row->category_id] = $row;
  }

  if ( count($matches) >= 10 ) break;
}
db_free_result($res);






?><ul><?
foreach($matches as $category_id=>$row)
{
  $row->category_name = html_entity_decode($row->category_name);
  ?><li id="<?=$row->category_id?>"><?=htmlentities($row->category_name, ENT_QUOTES)?><span class="informal"><?=nl2br(htmlentities(substr($row->category_desc, 0, 100), ENT_QUOTES))?><span class="informal_rt"><?=number_format($row->post_ct)?> posts</span></span></li><?
}
?></ul>