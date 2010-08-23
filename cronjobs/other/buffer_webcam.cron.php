#!/usr/bin/php -q
<?php
  include('/var/www/html/drunkduck.com/cronjobs/cron_data.inc.php');

  $ct = 0;
  while( $ct < 3 )
  {
    if ( file_exists(WWW_ROOT.'/cam/01.jpg') && filesize(WWW_ROOT.'/cam/01.jpg')>30000 )
    {
      if ( file_exists(WWW_ROOT.'/duckcam/buffer.jpg') && filesize(WWW_ROOT.'/duckcam/buffer.jpg')>30000 ) {
        copy(WWW_ROOT.'/duckcam/buffer.jpg', WWW_ROOT.'/duckcam/duckcam.jpg');
      }

      copy(WWW_ROOT.'/cam/01.jpg', WWW_ROOT.'/duckcam/buffer.jpg');
    }

    sleep(15);
    ++$ct;
  }
?>
