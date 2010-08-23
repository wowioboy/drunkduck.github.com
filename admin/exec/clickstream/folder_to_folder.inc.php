<?
include('clickstream_header.inc.php');

$TABLE_NAME   = 'click_path_folder_to_folder';
$SOURCE_FIELD = 'source_folder';
$TARGET_FIELD = 'target_folder';

$SUBDOM = strtolower(array_shift(explode('.', $_SERVER['HTTP_HOST'])));
$SITE_DOMAIN = str_replace($SUBDOM.'.', '', $_SERVER['HTTP_HOST']);


$WHERE = array();
if ( isset($_GET['from']) ) {
  $WHERE[] = $SOURCE_FIELD."='".db_escape_string($_GET['from'])."'";
}
if ( isset($_GET['to']) ) {
  $WHERE[] = $TARGET_FIELD."='".db_escape_string($_GET['to'])."'";
}

$WHERE[] = "ymd_date='".date("Ymd")."'";

if ( count($WHERE) ) {
  $res = db_query("SELECT * FROM ".$TABLE_NAME." WHERE ".implode(' AND ', $WHERE));
}
else {
  $res = db_query("SELECT * FROM ".$TABLE_NAME);
}

$TOTAL = 0;
while( $row = db_fetch_object($res) )
{
  if ( $row->$SOURCE_FIELD != $row->$TARGET_FIELD )
  {
    $obj              = new stdClass();
    $obj->subdomain   = $row->subdomain;
    if ( $row->$SOURCE_FIELD == 'Direct' ) {
      $obj->source_path = 'Direct';
    }
    else {
      $obj->source_path = $row->$SOURCE_FIELD;
    }
    $obj->target_path = $row->$TARGET_FIELD;

    $MAP[serialize($obj)] += $row->counter;

    $TOTAL += $row->counter;
  }
}



arsort($MAP);

?>
<table border="1" cellpadding="5" cellspacing="0" width="1000">
  <tr>
    <td align="center">
      <strong>Source</strong>
    </td>
    <td align="center">
      <strong>Destination</strong>
    </td>
    <td align="center">
      <strong>Counter</strong>
    </td>
  </tr>
<?
foreach( $MAP as $key=>$value )
{
  $DISP = unserialize($key);
  ?>
  <tr>
    <td align="left">
      <a href="<?=$_SERVER['PHP_SELF']?>?from=<?=$DISP->source_path?>"><?=$DISP->source_path?></a> <a href="<?=$DISP->source_path?>" target="_blank">[view]</a>
    </td>
    <td align="left">
      <a href="<?=$_SERVER['PHP_SELF']?>?to=<?=$DISP->target_path?>"><?=$DISP->target_path?></a> <a href="<?=$DISP->target_path?>" target="_blank">[view]</a>
    </td>
    <td align="left">
      <b><?=round(($value/$TOTAL)*100)?>%</b> [<?=number_format($value)?>]
    </td>
  </tr>
  <?
}
?></table><?




/*
+-------------+--------------+------+-----+---------+-------+
| Field       | Type         | Null | Key | Default | Extra |
+-------------+--------------+------+-----+---------+-------+
| subdomain   | varchar(20)  |      | PRI |         |       |
| source_file | varchar(255) |      | PRI |         |       |
| target_file | varchar(255) |      | PRI |         |       |
| counter     | int(11)      |      |     | 1       |       |
| ymd_date    | int(8)       |      | PRI | 0       |       |
| logged_in   | tinyint(1)   |      | PRI | 0       |       |
+-------------+--------------+------+-----+---------+-------+

*/

?>