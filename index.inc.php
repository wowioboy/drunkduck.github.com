<?
if ( $USER->flags & FLAG_IS_ADMIN )
{
  include('index_v3.inc.php');
  return;
}

misc_track('Main Page Hits (1 in 10)');
include(WWW_ROOT.'/index_modules_v2/searchbar.inc.php');
?>
<table width="100%" height="250" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td id="features"><?cached_include(WWW_ROOT.'/index_modules_v2/editorial_features.inc.php');?></td>
    <td id="video" width="100%" align="center"><?include(WWW_ROOT.'/index_modules_v2/300x250_ad.inc.php');?></td>
  </tr>
</table>

<?
if ( isset($_GET['notfound']) )
{
  $ATTEMPT = $_GET['notfound'];

  if ( strstr($ATTEMPT, '/comics/') )
  {

    $COMIC_NAME = substr( $ATTEMPT, strrpos($ATTEMPT, '/')+1 );


    $COMIC_NAME = trim( str_replace('_', ' ', $COMIC_NAME) );

    // $COMIC_NAME = substr( $ATTEMPT, strrpos($ATTEMPT, '/')+1 );


    $COMIC_NAME = trim( str_replace('_', ' ', $COMIC_NAME) );


    $res = db_query("SELECT comic_name FROM comics WHERE comic_name='".db_escape_string($COMIC_NAME)."'");

    if ( db_num_rows($res) == 1 )
    {
      $row = db_fetch_object($res);
      header("Location: http://".DOMAIN."/".comicNameToFolder($row->comic_name));
      return;
    }
    db_free_result($res);


    //$res = db_query("SELECT * FROM comics WHERE MATCH (comic_name, description) AGAINST ('".db_escape_string($COMIC_NAME)."') LIMIT 5");
    $res = db_query("SELECT * FROM comics WHERE comic_name LIKE '".db_escape_string($COMIC_NAME)."%' ORDER BY visits DESC LIMIT 5");
    if ( db_num_rows($res) != 0 )
    {
      ?>
      <p style="margin-top:10px;">
        <b>Did you mean one of these?</b>
      </p>
      <table width="762" border="0" cellspacing="0" cellpadding="0" class="results">
        <tr bgcolor="#0059ad">
          <?
          $ct = -1;
          while( $row = db_fetch_object($res) )
          {
            $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name);
            ?>
            <td align="center" valign="top" width="20%" bgcolor="#0059ad" style="padding:10px;">
            <a href='<?=$url?>'><img src="<?=thumb_processor($row)?>" border="0"></a>
            <br>
            <?=$row->comic_name?></a>
            <br>
            <span class="stripinfo">
              <img src="<?=IMAGE_HOST_SITE_GFX?>/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" title="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" />
              <?=number_format($row->total_pages)?> pgs | Updated: <?=((date("Ymd", $row->last_update)==YMD)?"Today":date("m/d/y", $row->last_update))?>
            </span>
            <?
          }
          ?>
        </tr>
      </table>
      <p style="margin-top:10px;">
        &nbsp;
      </p>
      <?
    }

  }
}
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="212" valign="top" id="col1">

      <div class="toplistsbg">
        <?include(WWW_ROOT.'/index_modules_v2/most_read_stories.inc.php');?>
        <?cached_include(WWW_ROOT.'/index_modules_v2/recently_updated_stories.inc.php', 60);?>
        <?cached_include(WWW_ROOT.'/index_modules_v2/gigcast.inc.php');?>
        <?cached_include(WWW_ROOT.'/index_modules_v2/supporters.inc.php');?>
      </div>

    </td>

    <td width="212" valign="top" id="col2">

      <div class="toplistsbg">
        <?include(WWW_ROOT.'/index_modules_v2/most_read_strips.inc.php');?>
        <?include(WWW_ROOT.'/index_modules_v2/recently_updated_strips.inc.php');?>
        <?cached_include(WWW_ROOT.'/index_modules_v2/games.inc.php', 30, (bool)($USER->flags & FLAG_IS_ADMIN));?>
        <?include(WWW_ROOT.'/index_modules_v2/poll.inc.php');?>
      </div>

    </td>

    <td width="100%" valign="top" id="col3">
      <?cached_include(WWW_ROOT.'/index_modules_v2/news.inc.php');?>
    </td>
  </tr>
</table>