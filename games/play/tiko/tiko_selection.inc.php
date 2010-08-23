<?
    $swf = new  FlashMovie(IMAGE_HOST.'/games/casino/kenoSelection_v1.swf', 500, 500);
	  $swf->setScale(FLASH_SCALE_NOSCALE);
	  $swf->setSAlign('LT');
	  $swf->setBackground('000000');
	  $swf->addVar("submitURL", "http://www.cnn.com");
	  $swf->showHTML();
?>