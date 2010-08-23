<?
define('SUPPORT_EMAIL',     'support@drunkduck.com');
 
define('FLAG_OVER_12',         0x00000001); 
define('FLAG_VERIFIED',        0x00000002);
define('FLAG_EMAIL_HASHED',    0x00000004);
define('FLAG_IS_ADMIN',        0x00000008); // Accessibility Flag
define('FLAG_FROZEN',          0x00000010);
define('FLAG_ADULT',           0x00000020);
define('FLAG_IS_MOD',          0x00000040); // Accessibility Flag
define('FLAG_EDITOR',          0x00000080); // Accessibility Flag
define('FLAG_EXECUTIVE',       0x00000100); // Accessibility Flag
define('FLAG_USE_HOMEPAGE',    0x00000200);
define('FLAG_NEW_MAIL',        0x00000400);
define('FLAG_RATING_LOCKED',   0x00000800);
define('FLAG_BANNED_PC',       0x00001000);
define('FLAG_IS_INTERNAL',     0x00002000); // Accessibility Flag
define('FLAG_FAVS_BY_DATE',    0x00004000);
define('FLAG_FAVS_BY_CAT',     0x00008000);
define('FLAG_FORUM_BAN',       0x00010000);
define('FLAG_HAS_FORUM',       0x00020000); // Does this comic have a forum?
define('FLAG_NO_SENDING_PQ',   0x00040000);
define('FLAG_PUBLISHER_QUAL',  0x00080000); // Do they have 5 comics with at least 20 pages each?
define('FLAG_IS_PUBLISHER',    0x00100000); // Do they have FLAG_PUBLISHER_QUAL and have opted to accept?
define('FLAG_ACCT_SIMPLE_MODE',0x00200000); // Advanced mode for adding pages etc.?

define('COMMENT_ANONYMOUS',    0x00000001);
define('COMMENT_MUTED',        0x00000002);
define('COMMENT_UNDER_REVIEW', 0x00000004);
define('COMMENT_APPROVED',     0x00000008);
define('COMMENT_DELETED',      0x00000010);

$COMIC_STYLES[0] = 'Comic Strip';
$COMIC_STYLES[1] = 'Comic Book/Story';

/*
Art Style:

Cartoon
American
Manga
Realism
Sprite
Sketch
Experimental
Photographic
Stick Figure

Genre:
*/


/*

UPDATE comics SET search_category=category, search_category_2=category;
UPDATE comics SET search_style='4', search_category='19', search_category_2='19' WHERE search_category='3';
UPDATE comics SET search_style='6', search_category='6', search_category_2='6' WHERE search_category='7';
UPDATE comics SET search_style='0', search_category='6', search_category_2='6' WHERE search_category='10';
UPDATE comics SET search_style='2', search_category='0', search_category_2='4' WHERE search_category='11';

*/


$COMIC_ART_STYLES[0] = 'Cartoon';
$COMIC_ART_STYLES[1] = 'American';
$COMIC_ART_STYLES[2] = 'Manga';
$COMIC_ART_STYLES[3] = 'Realism';
$COMIC_ART_STYLES[4] = 'Sprite';
$COMIC_ART_STYLES[5] = 'Sketch';
$COMIC_ART_STYLES[6] = 'Experimental';
$COMIC_ART_STYLES[7] = 'Photographic';
$COMIC_ART_STYLES[8] = 'Stick Figure';

$COMIC_CATEGORIES[0]   = 'Fantasy'; // Formerly Fantasy
$COMIC_CATEGORIES[1]   = 'Parody'; // Formerly Parody
$COMIC_CATEGORIES[2]   = 'Real Life'; // Formerly Real Life
$COMIC_CATEGORIES[4]   = 'Sci-Fi'; // Formerly Sci-Fi
$COMIC_CATEGORIES[5]   = 'Horror'; // Formerly Horror
$COMIC_CATEGORIES[6]   = 'Abstract'; // Formerly Abstract
$COMIC_CATEGORIES[8]   = 'Adventure'; // Formerly Adventure
$COMIC_CATEGORIES[9]   = 'Noir'; // Formerly Noir
$COMIC_CATEGORIES[12]  = 'Political'; // Formerly Political
$COMIC_CATEGORIES[13]  = 'Spritual'; // Formerly Spiritual
$COMIC_CATEGORIES[14]  = 'Romance'; // Formerly Romance
$COMIC_CATEGORIES[15]  = 'Superhero'; // Formerly Superhero
$COMIC_CATEGORIES[16]  = 'Western'; // Formerly Western
$COMIC_CATEGORIES[17]  = 'Mystery'; // Formerly Mystery
$COMIC_CATEGORIES[18]  = 'War'; // Formerly War
$COMIC_CATEGORIES[19]  = 'Tribute'; // Formerly Tribute





$COMIC_CATS[0]   = 'Fantasy';
//$COMIC_CATS[1]   = 'Reality';
$COMIC_CATS[2]   = 'Real Life';
$COMIC_CATS[3]   = 'Sprite';
$COMIC_CATS[4]   = 'Sci-Fi';
$COMIC_CATS[5]   = 'Horror';
$COMIC_CATS[6]   = 'Abstract';
$COMIC_CATS[7]   = 'Other';
$COMIC_CATS[8]   = 'Adventure';
$COMIC_CATS[9]   = 'Noir';
$COMIC_CATS[10]  = 'Humor';
$COMIC_CATS[11]  = 'Manga';
$COMIC_CATS[12]  = 'Political';
$COMIC_CATS[13]  = 'Spritual';
$COMIC_CATS[14]  = 'Romance';
$COMIC_CATS[15]  = 'Superhero';
$COMIC_CATS[16]  = 'Western';

$COMIC_SUBCATS[0]   = 'Comedy';
$COMIC_SUBCATS[1]   = 'Serious';
$COMIC_SUBCATS[2]   = 'Random';

$SUBDOM_TO_STYLE['manga'] = 2;
$SUBDOM_TO_STYLE['anime'] = 2;
$SUBDOM_TO_STYLE['sprite'] = 4;
$SUBDOM_TO_STYLE['infringement'] = 4;

$SUBDOM_TO_CAT['horror']  = 5;
$SUBDOM_TO_CAT['fantasy']  = 0;
$SUBDOM_TO_CAT['scifi']  = 4;

$ALLOWED_UPLOADS[] = 'gif';
$ALLOWED_UPLOADS[] = 'jpg';
$ALLOWED_UPLOADS[] = 'jpeg';
$ALLOWED_UPLOADS[] = 'png';
$ALLOWED_UPLOADS[] = 'swf';

$ALLOWED_GFX_FILES = $ALLOWED_UPLOADS;
$ALLOWED_GFX_FILES[] = 'wmv';
$ALLOWED_GFX_FILES[] = 'mov';
$ALLOWED_GFX_FILES[] = 'mpg';
$ALLOWED_GFX_FILES[] = 'flv';
$ALLOWED_GFX_FILES[] = '3gp';
$ALLOWED_GFX_FILES[] = 'm4v';

$max_filesize      = 524288;

$ALLOWED_HTML_UPLOADS[] = 'html';
$ALLOWED_HTML_UPLOADS[] = 'css';
$ALLOWED_HTML_UPLOADS[] = 'txt';

$GLOBALS['TRANS']['TRANS_LANGUAGES'] = array(
                                              'en'  =>  'English',
                                              'de'  =>  'German',
                                              'it'  =>  'Italian',
                                              'jp'  =>  'Japanese',
                                              'ko'  =>  'Korean',
                                              'es'  =>  'Spanish'
                                            );

define("YMD", date("Ymd"));


$HEADER_STYLES[0] = 'Obsidian';
$HEADER_STYLES[1] = 'Sand';
$HEADER_STYLES[2] = 'Iron';
$HEADER_STYLES[3] = 'Lipstick';
$HEADER_STYLES[4] = 'Blood';
$HEADER_STYLES[5] = 'Grape';
$HEADER_STYLES[6] = 'Ghost';
$HEADER_STYLES[7] = 'Snow';
$HEADER_STYLES[8] = 'Camo';
$HEADER_STYLES[9] = 'Laser';
$HEADER_STYLES[10] = 'Fudge';
$HEADER_STYLES[11] = 'Oxford';
$HEADER_STYLES[12] = 'Lemon';
$HEADER_STYLES[13] = 'Spang';
$HEADER_STYLES[14] = 'Glass';


?>