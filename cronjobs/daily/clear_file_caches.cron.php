#!/usr/bin/php -q
<?php
  include('/var/www/html/drunkduck.com/cronjobs/cron_data.inc.php');

  clearCache(CACHE_ROOT);

  // First delete backup zip files.
  $dp = opendir(WWW_ROOT.'/backups');
  while($file = readdir($dp) )
  {
    if ( $file != '..' && $file != '.' ) {
      echo(WWW_ROOT.'/backups/'.$file."\n");
      unlink(WWW_ROOT.'/backups/'.$file);
    }
  }
  closedir($dp);

  // clear out all rss
  $dp = opendir(WWW_ROOT.'/rss/cache');
  while($file = readdir($dp) )
  {
    if ( $file != '..' && $file != '.' ) {
      echo(WWW_ROOT.'/rss/cache/'.$file."\n");
      unlink(WWW_ROOT.'/rss/cache/'.$file);
    }
  }
  closedir($dp);

  // clear out all graphs
  $dp = opendir(WWW_ROOT.'/gfx/pageview_graph_cache');
  while($file = readdir($dp) )
  {
    if ( $file != '..' && $file != '.' ) {
      echo(WWW_ROOT.'/gfx/pageview_graph_cache/'.$file."\n");
      unlink(WWW_ROOT.'/gfx/pageview_graph_cache/'.$file);
    }
  }
  closedir($dp);
?>
