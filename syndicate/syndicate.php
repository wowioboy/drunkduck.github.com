<?
define('DEBUG_MODE', 0); // keep debug info from polluting response.
define('LEAN_AND_MEAN', 1); // Don't worry about loging them in or anything.

include_once('../includes/global.inc.php');
include_once('syndication_func.inc.php');


// http://syndicate.drunkduck.com/syndicate.js?key=5830c317d9da045fdc8687d7373cb5b3



$CLIENT_KEY = $_GET['key'];




// Is the client key valid?
$res = db_query("SELECT * FROM syndication_clients WHERE client_key='".$CLIENT_KEY."'");
if ( !$SYN = db_fetch_object($res) ) {
  die;
}
db_free_result($res);





// If its' a new day, advance the page position
if ( (date("Ymd") != $SYN->ymd_date) ) {
  $SYN->position = advance_page($SYN->client_id, $SYN->comic_id, $SYN->position);
}




// If no page found, die.
if ( $SYN->position == 0 ) die;




// Grab the comic row or die.
$res = db_query("SELECT * FROM comics WHERE comic_id='".$SYN->comic_id."'");
if ( !$COMIC_ROW = db_fetch_object($res) ) die;
db_free_result($res);




// Get the page info.
$res = db_query("SELECT * FROM comic_pages WHERE comic_id='".$SYN->comic_id."' AND order_id='".$SYN->position."' LIMIT 1");
if ( !$PAGE_ROW = db_fetch_object($res) ) die;
db_free_result($res);




//$CLICK_URL = 'http://'.DOMAIN.'/'.comicNameToFolder($COMIC_ROW->comic_name).'/?p='.$PAGE_ROW->page_id;
$CLICK_URL = 'http://'.DOMAIN.'/'.comicNameToFolder($COMIC_ROW->comic_name).'/';
$IMAGE_URL = 'http://'.DOMAIN.'/'.comicNameToFolder($COMIC_ROW->comic_name).'/pages/'.md5($COMIC_ROW->comic_id.$PAGE_ROW->page_id).'.'.$PAGE_ROW->file_ext;




// Spit out the javascript.

$URL_LENGTH = strlen($SYN->url);

?>
var urlTest = document.URL;
urlTest = urlTest.toLowerCase();

var allow = '<?=$SYN->url?>';
allow = allow.toLowerCase();

if ( urlTest.substr(0, allow.length) == allow ) {
  document.write('<a href="<?=$CLICK_URL?>"><img src="<?=$IMAGE_URL?>" border="0"></a>');
}
else {
  document.write('<div style="width:400px;">The <a href="/">DRUNKDUCK.COM</a> syndication key you are using is invalid. '+
                 'If you would like to get a valid key, please visit <a href="http://syndicate.drunkduck.com">syndicate.drunkduck.com</a></div>');
}