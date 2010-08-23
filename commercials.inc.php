<?
  $swf = new FlashMovie(IMAGE_HOST.'/swf/MusicPlayer.swf', 110, 50);
  $swf->setTransparent(true);
  $swf->addVar('songListString', IMAGE_HOST.'/mp3/1.mp3');
  $swf->showHTML();
?>
<BR>
<BR>
<BR>
<?
  $swf = new FlashMovie(IMAGE_HOST.'/swf/MusicPlayer.swf', 110, 50);
  $swf->setTransparent(true);
  $swf->addVar('songListString', 'http://media.libsyn.com/media/gigcast/gigcast20060208.mp3');
  $swf->showHTML();
?>