<?
define('FORUM_FLAG_MOD_ONLY',   0x000001);
define('FORUM_FLAG_ADMIN_ONLY', 0x000002);
define('FORUM_MIGRATED_POST',   0x000004);
define('FORUM_FLAG_LOCKED',     0x000008);
define('FORUM_FLAG_IMPORTANT',  0x000010);

$RESULTS_PER_PAGE   = 30;

$MAX_SIG_LENGTH     = 500;

$POST_DATE_FORMAT   = "M j,`y g:ia";
//$JOINED_DATE_FORMAT = "M j, Y";
$JOINED_DATE_FORMAT = "n-j-Y";

$EMOTES = array();

// 3 Length
$EMOTES['>:)'] = 'evil.gif';
// 2 Length
$EMOTES[':)']           = 'grin.gif';
$EMOTES[':(']           = 'sad.gif';
$EMOTES['B)']           = 'cool.gif';
$EMOTES[';)']           = 'wink.gif';
$EMOTES[':gem:']        = 'gem.gif';
$EMOTES['lol!']         = 'lol.gif';
$EMOTES['8D']           = '8D.png';
$EMOTES[':spin:']       = 'animation1.gif';
$EMOTES[':cat:']        = 'catblink.gif';
$EMOTES[':cry:']        = 'Cry.gif';
$EMOTES[':cry2:']       = 'cryemote.gif';
$EMOTES[':dizzy:']      = 'dizzyemote.gif';
$EMOTES[':evil:']       = 'evil.gif';
$EMOTES['huh!?']        = 'huhemote2.gif';
$EMOTES[':kitty:']      = 'icon_3nodding.gif';
$EMOTES[':mad:']        = 'mad.gif';
$EMOTES[':nerd:']       = 'nerdemote.gif';
$EMOTES[':nervous:']    = 'Nervous.gif';
$EMOTES[':neenjah:']    = 'ninjaemote.gif';
$EMOTES[':robo:']       = 'robotemote.gif';
$EMOTES[':sleepy:']     = 'sleepemote.gif';
$EMOTES[':stache:']     = 'stache.gif';
$EMOTES[':whistling:']  = 'whistling.gif';



$GROUPINGS[1]  = 'GENERAL';
$GROUPINGS[2]  = 'COMMUNITY';
$GROUPINGS[3]  = 'ART/ENTERTAINMENT';
$GROUPINGS[4]  = 'HELP';
$GROUPINGS[5]  = 'FEEDBACK';
$GROUPINGS[6]  = 'OTHER';

?>