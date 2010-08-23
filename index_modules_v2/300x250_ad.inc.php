<? $ord=time() ?>
<script language="JavaScript" src="http://ad.doubleclick.net/adj/dduck.template/box_main;sz=300x250;ord=<? echo $ord ?>?" type="text/javascript"></script>
<noscript><a href="http://ad.doubleclick.net/jump/dduck.template/box_main;sz=300x250;ord=<? echo $ord ?>?" target="_blank"><img src="http://ad.doubleclick.net/ad/dduck.template/box_main;sz=300x250;ord=<? echo $ord ?>?" width="300" height="250" border="0" alt=""></a></noscript><?
return;

  include(WWW_ROOT.'/rss/drunkduckwebcomics.youtube.inc.php');

  do {
    $rand = dice(1, count($rss_channel['ITEMS']))-1;
    $vid_url = $rss_channel['ITEMS'][$rand]['LINK'];
  } while( strstr( strtolower($rss_channel['ITEMS'][$rand]['TITLE']), 'rated r') || strstr( strtolower($rss_channel['ITEMS'][$rand]['TITLE']), 'avatar') );

  $vid_url = str_replace('?', '', $vid_url);
  $vid_url = str_replace('=', '/', $vid_url);

  $swf = new FlashMovie($vid_url, 300, 250);
  $swf->setScale(FLASH_SCALE_NOSCALE);
  $swf->setSAlign('');
  $swf->setBackground('#00325d');
  $swf->showHTML();
  return;



$MOVIES   = array();
$POSTERS  = array();

$MOVIES[]   = IMAGE_HOST.'/trailers/flv/WD_300x168.flv|300x168';
$POSTERS[]  = IMAGE_HOST.'/trailers/posters/wd.gif';

$MOVIES[]   = IMAGE_HOST.'/trailers/flv/BN_300x168.flv|300x168';
$POSTERS[]  = IMAGE_HOST.'/trailers/posters/bn.gif';

//$MOVIES[]   = IMAGE_HOST.'/trailers/flv/DejaVu_TR2_320x130.flv|320x130';
//$POSTERS[]  = IMAGE_HOST.'/trailers/posters/deja_vu_poster.gif';

$MOVIES[]   = IMAGE_HOST.'/trailers/flv/cowboysadaliens_trailer_320.flv|300x168';
$POSTERS[]  = IMAGE_HOST.'/trailers/posters/coyboysandaliens_poster2.gif';

$MOVIES[]   = IMAGE_HOST.'/trailers/flv/darkness_game_300x168.flv|300x168';
$POSTERS[]  = IMAGE_HOST.'/trailers/posters/thedarkness_poster.gif';

$MOVIES[]   = IMAGE_HOST.'/trailers/flv/theinvisible_trailer_320x130.flv|320x130';
$POSTERS[]  = IMAGE_HOST.'/trailers/posters/theinvisible_poster.gif';













  // limit to 5 or 10 thousand.
  $m = explode("|", $MOVIES[dice(1,count($MOVIES))-1]);
  $m = $m[0];
  $content_url = urlencode($m);

  // $content_url = urlencode('http://youtube-444.vo.llnwd.net/d1/01/53/TSTWqRkBN74.flv');
  $channel_id  = 68517;
  ?>
  <div id="player"></div>
  <script type="text/javascript" src="<?=IMAGE_HOST?>/spotxchange/swfobject.js"></script>
<script type="text/javascript">
// <![CDATA[
var fo = new SWFObject("<?=IMAGE_HOST?>/spotxchange/SimplePlayer.swf?channel_id=<?php echo $channel_id; ?>&ip_addr=<?php echo $_SERVER["REMOTE_ADDR"]; ?>&content_url=<?php echo $content_url; ?>&iab_imu=300x250&iab_imu_container_id=banner&session_capping=true&session_timeout_mins=5", "movie_player", "300", "240", 7, "#22547F");
fo.write("player");
// ]]>
</script>
  <?
  if ( !isBot() ) {
    misc_track('300x250 Commercial');
  }
  return;


if ( dice(1, 3) == 1 )
{
  ?><script language="javascript" src="http://media.fastclick.net/w/get.media?sid=11415&m=6&tp=8&d=j&t=s"></script><noscript><a href="http://media.fastclick.net/w/click.here?sid=11415&m=6&c=1" target="_top"><img src="http://media.fastclick.net/w/get.media?sid=11415&m=6&tp=8&d=s&c=1" width=300 height=250 border=1></a></noscript><?
  return;
}


$swf = new FlashMovie(IMAGE_HOST.'/swf/movieRotator_300x250.swf', 300, 250);
$swf->setBackground('#000000');
$swf->addVar('flvList',     implode(',', $MOVIES) );
$swf->addVar('posterList',  implode(',', $POSTERS) );

$swf->addVar('displayTime', 3000);
$swf->addVar('startPoint',  dice(1, count($MOVIES))-1);

$swf->showHTML();

return;
?>