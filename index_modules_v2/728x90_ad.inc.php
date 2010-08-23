<script language='JavaScript' type='text/javascript' src='http://ads.platinumstudios.net/adx.js'></script>
<script language='JavaScript' type='text/javascript'>
<!--
   if (!document.phpAds_used) document.phpAds_used = ',';
   phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);

   document.write ("<" + "script language='JavaScript' type='text/javascript' src='");
   document.write ("http://ads.platinumstudios.net/adjs.php?n=" + phpAds_random);
   document.write ("&amp;what=zone:53");
   document.write ("&amp;exclude=" + document.phpAds_used);
   if (document.referrer)
      document.write ("&amp;referer=" + escape(document.referrer));
   document.write ("'><" + "/script>");
//-->
</script><noscript><a href='http://ads.platinumstudios.net/adclick.php?n=a7f7bc83' target='_blank'><img src='http://ads.platinumstudios.net/adview.php?what=zone:53&amp;n=a7f7bc83' border='0' alt=''></a></noscript>
<?
return;


if ( true )
{
  ?><script language="javascript" src="http://media.fastclick.net/w/get.media?sid=11415&m=1&tp=5&d=j&t=s"></script><noscript><a href="http://media.fastclick.net/w/click.here?sid=11415&m=1&c=1" target="_top"><img src="http://media.fastclick.net/w/get.media?sid=11415&m=1&tp=5&d=s&c=1" width=728 height=90 border=1></a></noscript><?
  return;
}







include(WWW_ROOT.'/includes/xmllib.inc.php');


  $channel_id  = 68517;

  $url = "http://search.spotxchange.com/".$channel_id."?ip_addr=".$_SERVER["REMOTE_ADDR"]."&media_format=FlashVideo&media_transcoding=medium&iab_imu=leaderboard";
  //$url = "http://www.spotxc1hange.com/spec/madrss_example.xml";
  $data = file_get_contents($url);
  $xml = XML_unserialize($data);

/*
  ?><pre><?
  var_dump( $xml['rss']['channel']['madrss:result'] );
  ?></pre><?
*/


  if ( $xml['rss']['channel']['madrss:result']['madrss:total_available'] == 1 ) {
    $AD_LIST = array($xml['rss']['channel']['item']);
  }
  else {
    $AD_LIST = $xml['rss']['channel']['item'];
  }

  $VALID_ADS = array();

  foreach( $AD_LIST as $AD )
  {
    foreach($AD['madrss:ad_unit'] as $testAd)
    {
      $size = $testAd['width'].'x'.$testAd['height'];
      if ( $size == '728x90' ) {
        $VALID_ADS[ '$'.$AD['madrss:bid'] ] = $testAd;
      }
    }
  }

  if ( !count($VALID_ADS) ) return;

  foreach( $VALID_ADS as $CPM=>$AD )
  {
    switch($AD['type'])
    {
      case 'image/gif':
        ?><!-- <?=$CPM?> --><a href="<?=$AD['link']?>" target="_top"><img src="<?=$AD['url']?>" height="<?=$AD['height']?>" width="<?=$AD['width']?>" border="0"></a><?
        return;
      break;
    }
  }



return;
  ?>
  <div id="player"></div>
  <script type="text/javascript" src="<?=IMAGE_HOST?>/spotxchange/swfobject.js"></script>
<script type="text/javascript">
// <![CDATA[
var fo = new SWFObject("<?=$AD_SPOT['']?>", "movie_player", "320", "240", 7, "#22547F");
fo.write("player");
// ]]>
</script>
  <?
  if ( !isBot() ) {
    misc_track('728x90 Leaderboard');
  }

?>