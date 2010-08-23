<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');

$COMIC_ID = (int)$_GET['comic_id'];

$cRes = db_query("SELECT * FROM comics WHERE comic_id='".$COMIC_ID."'");
if ( !$COMIC_ROW = db_fetch_object($cRes) ) return;
db_free_result($cRes);



$ARRAY = array();

$pRes = db_query("SELECT * FROM comic_pages WHERE comic_id='".$COMIC_ID."' ORDER BY order_id ASC");
while( $pRow = db_fetch_object($pRes) )
{
  $ARRAY[$pRow->order_id] = serialize($pRow);
}
db_free_result($pRes);


print("<?".
      "\n\n".
      "\$PAGE_ARRAY = ");

var_export($ARRAY);

print(";\n\n?>");
?>