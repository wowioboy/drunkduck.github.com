<?php
  function dd_replace($str)
  {
    if ( rand(1,2) == 1 )
    {
      $ad = '<script language=\'JavaScript\' type=\'text/javascript\' src=\'http://ads.platinumstudios.com/adx.js\'></script>
<script language=\'JavaScript\' type=\'text/javascript\'>
<!--
   if (!document.phpAds_used) document.phpAds_used = \',\';
   phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);

   document.write ("<" + "script language=\'JavaScript\' type=\'text/javascript\' src=\'");
   document.write ("http://ads.platinumstudios.com/adjs.php?n=" + phpAds_random);
   document.write ("&amp;what=zone:7&amp;target=_top");
   document.write ("&amp;exclude=" + document.phpAds_used);
   if (document.referrer)
      document.write ("&amp;referer=" + escape(document.referrer));
   document.write ("\'><" + "/script>");
//-->
</script><noscript><a href=\'http://ads.platinumstudios.com/adclick.php?n=a04d60ec\' target=\'_top\'><img src=\'http://ads.platinumstudios.com/adview.php?what=zone:7&amp;n=a04d60ec\' border=\'0\' alt=\'\'></a></noscript>';
    }
    else
    {
      $ad = '<script type="text/javascript"><!--
google_ad_client = "pub-2149547298462648";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_type = "text_image";
google_ad_channel ="";
google_color_border = "ffffff";
google_color_bg = "ffffff";
google_color_link = "0066b8";
google_color_text = "000000";
google_color_url = "000000";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';
    }

    $str = str_replace('<tt>ad</tt>', $ad, $str);
    return $str;
  }
  ob_start('dd_replace');
// phpBB 2.x auto-generated config file
// Do not change anything in this file!

$dbms = 'mysql';

$dbhost = 'localhost';
$dbname = 'drunkduck';
$dbuser = 'drunkduck';
$dbpasswd = 'ice22hdi5m';

$table_prefix = 'forum_';

define('PHPBB_INSTALLED', true);

?>