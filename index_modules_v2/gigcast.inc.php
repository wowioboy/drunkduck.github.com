  <?
  return;
  ?>
<img src="<?=IMAGE_HOST_SITE_GFX?>/sect_gigcasts.gif" width="212" height="30" />
<div class="gigcasts" align="left" >
  <?
  require_once(WWW_ROOT.'/rss/gigcast.inc.php');
  foreach($rss_channel['ITEMS'] as $ct=>$piece)
  {
    $ts   = strtotime($piece['PUBDATE']);
    $name = date("M.d", $ts) .' '. $name;

    $piece['DESCRIPTION'] = preg_replace('`<a (.*)>(.*)</a>`U', '', $piece['DESCRIPTION']);
    $piece['DESCRIPTION'] = str_replace('"', '&quot;', $piece['DESCRIPTION']);
    $piece['DESCRIPTION'] = str_replace("\n", "", $piece['DESCRIPTION']);
    $piece['DESCRIPTION'] = str_replace("\r", "", $piece['DESCRIPTION']);
    $ts   = strtotime($piece['PUBDATE']);
    ?><p><?=date("M.d", $ts)?> <a href="<?=$piece['LINK']?>" alt="<?=$piece['TITLE']?>" title="<?=$piece['TITLE']?>"><?=$piece['TITLE']?></a></p><?

    if ( $ct == 3 ) break;
  }
  ?>
  <a href="http://www.itunes.com/podcast?id=105967880"><img src="<?=IMAGE_HOST_SITE_GFX?>/itunes.gif" border="0"></a>
</div>