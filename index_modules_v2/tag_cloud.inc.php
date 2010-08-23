<?

$CLOUD_SIZES     = array();
$CLOUD_SIZES[0]  = 'font-size:11px;font-weight:normal;';
$CLOUD_SIZES[1]  = 'font-size:11px;font-weight:bold;';
$CLOUD_SIZES[2]  = 'font-size:12px;font-weight:bold;';

$CLOUD_SIZES[3]  = 'font-size:14px;font-weight:normal;';
$CLOUD_SIZES[4]  = 'font-size:14px;font-weight:bold;';
$CLOUD_SIZES[5]  = 'font-size:15px;font-weight:bold;';

$CLOUD_SIZES[6]  = 'font-size:17px;font-weight:normal;';
$CLOUD_SIZES[7]  = 'font-size:17px;font-weight:bold;';
$CLOUD_SIZES[8]  = 'font-size:18px;font-weight:bold;';

$CLOUD_SIZES[9]  = 'font-size:19px;font-weight:bold;text-transform:uppercase;';


$TAG_CLOUD   = array();
$LARGEST_TAGS = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                      0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

$res = db_query("SELECT * FROM tags_counter_daily ORDER BY counter DESC LIMIT 30");
while( $row = db_fetch_object($res) )
{
  foreach($LARGEST_TAGS as $key=>$amt)
  {
    if ( $row->counter > $amt )
    {
      $LARGEST_TAGS[$key] = $row->counter;
      break;
    }
  }

  $TAG_CLOUD[$row->tag] = $row->counter;
}
db_free_result($res);


$CLOUD_KEYS = array_keys($TAG_CLOUD);
shuffle($CLOUD_KEYS);

?><div style="padding:3px;background:#005aaa;" align="center"><?

foreach($CLOUD_KEYS as $key)
{
  $tag = $key;
  $ct  = $TAG_CLOUD[$key];

  $spot = 0;
  foreach( $LARGEST_TAGS as $key=>$amt ) {
    if ( $amt <= $ct ) {
      $spot = count($CLOUD_SIZES)-1-$key;
      break;
    }
  }

  $style = $CLOUD_SIZES[$spot];
  $feedback = $CLOUD_SIZES[$spot] . ' / ' . $spot;
  ?><a href="#" style="<?=$style?>"><?=$tag?></a> <?
}

?></div>