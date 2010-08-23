<?
include('../includes/global.inc.php');

/*
			_level0.flvList		= 'assets/flv1.flv|300x128,assets/flv2.flv|300x100';
//			_level0.flvList		= 'assets/flv1.flv|586x250,assets/flv2.flv|300x100';
			_level0.posterList	= 'assets/poster1.gif,assets/poster2.gif';
			_level0.displayTime = 5000;
			_level0.startPoint  = 0;
*/


$MOVIES   = array();
$POSTERS  = array();

$MOVIES[]   = IMAGE_HOST.'/trailers/flv/WD_300x168.flv|300x168';
$POSTERS[]  = IMAGE_HOST.'/trailers/posters/wd.gif';

$MOVIES[]   = IMAGE_HOST.'/trailers/flv/BN_300x168.flv|300x168';
$POSTERS[]  = IMAGE_HOST.'/trailers/posters/bn.gif';

$MOVIES[]   = IMAGE_HOST.'/trailers/flv/cowboysadaliens_trailer_320.flv|300x128';
$POSTERS[]  = IMAGE_HOST.'/trailers/posters/coyboysandaliens_poster2.gif';

$MOVIES[]   = IMAGE_HOST.'/trailers/flv/grudge2_trailer.flv|300x250';
$POSTERS[]  = IMAGE_HOST.'/trailers/posters/grudge2_poster.gif';

$MOVIES[]   = IMAGE_HOST.'/trailers/flv/darkness_game_hi.flv|300x180';
$POSTERS[]  = IMAGE_HOST.'/trailers/posters/thedarkness_poster.gif';

$MOVIES[]   = IMAGE_HOST.'/trailers/flv/theinvisible_trailer_320x130.flv|320x130';
$POSTERS[]  = IMAGE_HOST.'/trailers/posters/theinvisible_poster.gif';

$MOVIES[]   = IMAGE_HOST.'/trailers/flv/theprestige_trailer_320x136.flv|320x136';
$POSTERS[]  = IMAGE_HOST.'/trailers/posters/theprestige_poster.gif';


$swf = new FlashMovie(IMAGE_HOST.'/swf/movieRotator_300x250.swf', 300, 250);
$swf->setBackground('#000000');
$swf->addVar('flvList',     implode(',', $MOVIES) );
$swf->addVar('posterList',  implode(',', $POSTERS) );
$swf->addVar('displayTime', 3000);
$swf->addVar('startPoint',  0);
$swf->showHTML();
?>