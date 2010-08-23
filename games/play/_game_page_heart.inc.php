<?
// Set the back url for the page
$BACK_URL = '/games/';

require_once(WWW_ROOT.'/games/game_system/game_system_data.inc.php');
require_once(WWW_ROOT.'/games/game_system/game_system_func.inc.php');

// Reverse translate url
$GAME_TITLE           = substr(basename($_SERVER['PHP_SELF']), 0, strlen(basename($_SERVER['PHP_SELF']))-4);
$CONVERTED_GAME_TITLE = str_replace("-", "'", str_replace('_', ' ', $GAME_TITLE ));

if ( !$_GET['play'] ) {
  include_once(WWW_ROOT.'/games/play/_click_to_play.inc.php');
  return;
}

if ( isset($GAME_TITLE) )
{
  //$game_id = (int)$GAME_ID;
  $res = db_query("SELECT * FROM game_info WHERE title='".db_escape_string($CONVERTED_GAME_TITLE)."' AND is_live='1'");
  if ( db_num_rows($res) == 0 ) {
    die("<div align='center'>Invalid Game</div>");
  }


  if ( $row = db_fetch_object($res) )
  {
    $game_id = $row->game_id;
    $width   = $row->width;
    $height  = $row->height;

    if ( $_GET['width'] ) {
      $width = $_GET['width'];
    }
    if ( $_GET['height'] ) {
      $height = $_GET['height'];
    }


    $URL = IMAGE_HOST.'/games/game_shell_'.$row->fps.'fps.swf';
    if ( $USER->flags & FLAG_IS_ADMIN ) {
      $URL = $URL.'?debug=1';
    }

    $swf = new FlashMovie($URL, $width, $height);

    $swf->addVar('gId',            $row->game_id);
    $swf->addVar('gVersion',       $row->game_version);
    $swf->addVar('gWidth',         $width);
    $swf->addVar('gHeight',        $height);
    switch($row->game_id)
    {
      case 7:
        $swf->addVar('gLoadSwf',       IMAGE_HOST.'/games/loader_images/hbn_preloader_v2.swf');
        break;
      default:
        $swf->addVar('gLoadSwf',       IMAGE_HOST.'/games/loader_images/preloader_DD.swf');
    }
    $swf->addVar('gSendSwf',       IMAGE_HOST.'/games/loader_images/game_sendscorescreen_DD.swf');
    $swf->addVar('gGameServer',    IMAGE_HOST.'/games/');
    $swf->addVar('gSendScriptUrl', 'http://'.DOMAIN.'/games/game_system/send_score.php');
    $swf->addVar('playRecordURL',  'http://'.DOMAIN.'/games/game_system/track_play.php');

    $swf->addVar('uid',            $USER->username);

    if ( $_GET['challenge_id'] ) {
      $swf->addVar('passBack',     'CHALL='.$_GET['challenge_id']);
    }

    if ( $USER->username == 'Volte6' ) {
      // $swf->addVar('gConsole',       IMAGE_HOST.'/games/DebugConsole.swf');
    }

    if ( $sess_id = find_session($USER, $game_id) ) {
      $swf->addVar('sess_id', $sess_id);

      bump_session($USER, $sess_id);
    }
    else {
      $sess_id = create_session($USER, $game_id);
      $swf->addVar('sess_id', $sess_id);
    }
    $swf->addVar('sess_ck', md5($sess_id.SESSION_CHECKSUM_SALT));

    $swf->setScale(FLASH_SCALE_NOSCALE);
    $swf->setSAlign('LT');
    $swf->setBackground('000000');

    if ( $_GET['pup'] ) {
      ?><html>
      <head>
      	<title><?=$row->title?></title>
      </head>
      <body bgcolor="#000000" leftmargin=0 topmargin=0 marginheight=0 marginwidth=0>
      <?
    }
    else {
      ?>
      <link href="http://<?=DOMAIN?>/games/games.css" rel="stylesheet" type="text/css" />
        <h1 align="left">Play: <?=$TITLE?></h1>
      <div class="gameContent"><?
    }

    $swf->showHTML();

    db_query("UPDATE game_launches SET counter=counter+1 WHERE date_year='".date("Y")."' AND date_month='".date("m")."' AND date_day='".date("d")."' AND game_id='".$row->game_id."'");
    if ( db_rows_affected() < 1 ) {
      db_query("INSERT INTO game_launches (date_year, date_month, date_day, game_id, counter) VALUES ('".date("Y")."', '".date("m")."', '".date("d")."', '".$row->game_id."', '1')");
    }

    if ( $_GET['pup'] ) {
      ?>
      </body>
      </html>
      <?
    }


    if ( !$_GET['pup'] )
    {
      ?><div align="center"><a href="JavaScript:history.back();">Back</a></div>
      </div><?
      if ( $_GET['challenge_id'] )
      {
        ?><br><br><div align='center'><img src='<?=IMAGE_HOST?>/site_gfx/challenge_symbol.gif'></div><?
      }
    }
  }
}
?>