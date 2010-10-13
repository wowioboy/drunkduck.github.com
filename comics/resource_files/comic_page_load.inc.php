<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<?
define('TEMPLATE_VIEW', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// Make sure the php_self is seen as NOT in comics.
$_SERVER['PHP_SELF'] = preg_replace('`\/comics\/[a-zA-Z0-9_](.*)`', "\\1", $_SERVER['PHP_SELF']);

// hack
$_SERVER['PHP_SELF'] = $_SERVER['REDIRECT_URI'];
/*var_dump($_SERVER['PHP_SELF']); exit;*/

if ( strstr($_SERVER['REQUEST_URI'], '/comics') ) {
  if ( strlen($_SERVER['QUERY_STRING']) > 0 ) {
    header("Location: ".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
  }
  else {
    header("Location: ".$_SERVER['PHP_SELF']);
  }
}

$FOLDER_NAME = basename( getcwd() );
/*
 * DEV HACK -jihan
 */
$FOLDER_NAME = array_shift(array_filter(explode('/',$_SERVER['REDIRECT_URL'])));
$_SERVER['PHP_SELF'] = '/' . $FOLDER_NAME . '/';
$COMIC_NAME  = str_replace('_', ' ', $FOLDER_NAME );
/*var_dump(__LINE__ . ':' . basename(__FILE__), $FOLDER_NAME); exit;*/
$TITLE = $COMIC_NAME;

if ( file_exists('../../includes/global.inc.php') ) {
  require_once('../../includes/global.inc.php');
}
else if ( file_exists('/var/www/html/drunkduck.com/includes/global.inc.php') ) {
  require_once('/var/www/html/drunkduck.com/includes/global.inc.php');
}
else {
  require_once('../../../includes/global.inc.php');
}

if ( $USER->flags & FLAG_FROZEN ) {
  header("Location: http://".DOMAIN."/");
  die("Your account has been frozen.");
}
/****************************
*                           *
* LOAD ALL COMIC DATA HERE! *
*                           *
****************************/
$res = db_query("SELECT * FROM comics WHERE comic_name='".$COMIC_NAME."'");
$COMIC_ROW = db_fetch_object($res);
db_free_result($res);

if ( $COMIC_ROW->flags & FLAG_FROZEN ) {
  echo "<DIV ALIGN='CENTER' STYLE='color:red;'>This comic has been frozen.</DIV>";
  return;
}

$doCheck = true;
if ( $COMIC_ROW->user_id == $USER->user_id ) {
  $doCheck = false;
}
if ( $COMIC_ROW->secondary_author == $USER->user_id ) {
  $doCheck = false;
}
if ( !$USER ) {
  $doCheck = true;
}

if ( $doCheck )
{
  switch($COMIC_ROW->rating_symbol)
  {
    case 'E':
    case 'T':
    case 'M':
    break;
    case 'A':
       if ( !$USER || ($USER->age < 18) ) {
         header("Location: /ratings.php?denied=A");
       }
    break;
  }

}

/***********************************
*                                  *
* LOAD ALL OF THE USERS FAVORITES  *
*                                  *
***********************************/

if ( $USER )
{
  $COMIC_FAVS = array();
  $IN_FAVORITE   = false;
  $res = db_query("SELECT comic_id, bookmark_page_id FROM comic_favs WHERE user_id='".$USER->user_id."'");
  while($row = db_fetch_object($res))
  {
    if ( $COMIC_ROW->comic_id == $row->comic_id ) {
      $IN_FAVORITE = true;
    }
    $COMIC_FAVS[$row->comic_id] = $row;
  }
  db_free_result($res);
}







$res = db_query("SELECT * FROM users WHERE user_id='".$COMIC_ROW->user_id."'");
$OWNER_ROW = db_fetch_object($res);
db_free_result($res);


/*******************************************
*                                          *
* DECIDE IF WE ARE VIEWING ARCHIVE OR NOT  *
*                                          *
*******************************************/

if ( !isset($_GET['p']) && !isset($_POST['p']) && ($COMIC_ROW->flags & FLAG_USE_HOMEPAGE) )
{
  include_once('load_homepage.inc.php');
}
else
{
  include_once('load_archive.inc.php');
}








// $TEMPLATE should be set now.
$TEMPLATE = str_replace(".cookie", ".cook1e", $TEMPLATE);

//preg_match_all('`\<\!--\[([a-zA-Z0-9_]*)\]--\>`U', $TEMPLATE, $MATCHES);
preg_match_all('`\<\!--\[(.*)\]--\>`U', $TEMPLATE, $MATCHES);

$adCount = 0;
foreach($MATCHES[1] as $idx=>$tag)
{
  $args = false;

  if ( strstr($tag, '=') ) {
    list($tag, $args) = explode('=', $tag);
  }

  if ( isset($GLOBALS['DDTags'][$tag]) )
  {
    $CB = $GLOBALS['DDTags'][$tag]->callback;
    $TEMPLATE = str_replace($MATCHES[0][$idx], $CB($args), $TEMPLATE);
  }
}

// strip out new lines... it tends to help the regex
$TEMPLATE = str_replace("\n", "", $TEMPLATE);
// Get the header

  $hdr = include(WWW_ROOT.'/comics/resource_files/comic_caps/comic_header_v3.inc.php');
//  $hdr = include(WWW_ROOT.'/comics/resource_files/comic_caps/comic_header.inc.php');

// Inject the header
$TEMPLATE = preg_replace("`<(body.*)>`iUm", "<\\1>".$hdr, $TEMPLATE, 1);

// Get the footer
$ftr = include(WWW_ROOT.'/comics/resource_files/comic_caps/comic_footer.inc.php');
// Inject the footer.
$TEMPLATE = preg_replace("`<(/body.*)>`iUm", $ftr."<\\1>", $TEMPLATE, 1);


if ( !strstr($TEMPLATE, '<!--HDR-->') ) {
  $url = 'http://'.DOMAIN.'/'.comicNameToFolder($COMIC_ROW->comic_name).'/';
  $TEMPLATE = $hdr.$TEMPLATE;
}

if ( !strstr($TEMPLATE, '<!--FTR-->') ) {
  $url = 'http://'.DOMAIN.'/'.comicNameToFolder($COMIC_ROW->comic_name).'/';
  $TEMPLATE = $TEMPLATE.$ftr;
}

/*
if ( strstr($TEMPLATE, 'eval ') ) {
  $TEMPLATE = str_replace('eval ', 'evil ', $TEMPLATE);
}
if ( strstr($TEMPLATE, 'eval(') ) {
  $TEMPLATE = str_replace('eval(', 'evil(', $TEMPLATE);
}
if ( strstr($TEMPLATE, 'fromCharCode') ) {
  $TEMPLATE = str_replace('fromCharCode', 'fronCharCode', $TEMPLATE);
}
*/

// Finally inject META code:

  $META = array();
  $META[] = '<meta name="robots" content="All,INDEX,FOLLOW">';
  $META[] = '<meta name="description" content="Drunk Duck is the webcomics community that provides FREE hosting and memberships to people who love to read or write comic books, or comic strips.">';
  $META[] = '<meta name="keywords" content="The Webcomics Community, Webcomics Community, The Comics Community, Comics Community, Comics, Webcomics, Stories, Strips, Comic Strips, Comic Books, Funny, Interesting, Read, Art, Drawing, Photoshop, '.implode(", ", $PAGE_TAGS).'">';
  //$TEMPLATE = str_replace('</title>', '</title>'.implode("\n", $META), $TEMPLATE);
  //$TEMPLATE = str_replace('</TITLE>', '</TITLE>'.implode("\n", $META), $TEMPLATE);
  $TEMPLATE = preg_replace("`(<title>.*</title>)`iUm", "\\1\n".implode("\n", $META), $TEMPLATE, 1);



// Track this view as a possible unique.
db_query("INSERT INTO unique_comic_tracking (comic_id, ymd_date, page_id, ip) VALUES ('".$COMIC_ROW->comic_id."', '".YMD."', '".((int)$PAGE_ROW->page_id)."', '".$_SERVER['REMOTE_ADDR']."')");
// Track pageview:
track_comic_view($COMIC_ROW->comic_id);

echo $TEMPLATE;



    
/*var_dump($GLOBALS['DDTags']['PAGE']);*/
?>
