<?
define('HTML_CACHE_FOLDER', CACHE_ROOT.'/_html');
function cached_include($path, $secondsLifeSpan=300, $uniqueID='')
{
  $cacheFilePath = HTML_CACHE_FOLDER.'/'.$_SERVER['HTTP_HOST'].'.'.md5($path.$uniqueID).'.cache';

  if ( file_exists($cacheFilePath) && ((time()-filemtime($cacheFilePath)) < $secondsLifeSpan) )
  {
    include($cacheFilePath);
    return;
  }

  ob_start();
  $retValue = include($path);
  $output = ob_get_flush();
  if ( !($USER->flags & FLAG_IS_ADMIN) ) {
    write_file($output, $cacheFilePath);
  }
}
?>