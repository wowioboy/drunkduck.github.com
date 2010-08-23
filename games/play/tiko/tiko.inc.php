<?
    $swf = new  FlashMovie(IMAGE_HOST.'/games/casino/keno_v1.swf', 500, 500);
	  $swf->setScale(FLASH_SCALE_NOSCALE);
	  $swf->setSAlign('LT');
	  $swf->setBackground('000000');
	  $swf->addVar("passNumbers", "1,3,5,6,7,40");
	  $swf->showHTML();
?>