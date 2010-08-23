<div style="font-weight:bold;">Sorry, couldn't find the page or file you requested!</div>

<?
header("Location: /index.php?notfound=".rawurlencode($_SERVER['REDIRECT_URL']));
die;
$ATTEMPT = $_SERVER['REDIRECT_URL']; // "/comics/c/comics/c/casdf

if ( strstr($ATTEMPT, '/comics/') )
{

  $COMIC_NAME = substr( $ATTEMPT, strrpos($ATTEMPT, '/')+1 );


  $COMIC_NAME = trim( str_replace('_', ' ', $COMIC_NAME) );


  $res = db_query("SELECT * FROM comics WHERE comic_name='".db_escape_string($COMIC_NAME)."'");

  if ( db_num_rows($res) == 1 )
  {
    $row = db_fetch_object($res);
    header("Location: http://".DOMAIN."/".comicNameToFolder($row->comic_name));
    return;
  }

  if ( db_num_rows($res) == 0 )
  {
    $res = db_query("SELECT * FROM comics WHERE comic_name LIKE '".db_escape_string($COMIC_NAME)."%' ORDER BY visits DESC LIMIT 20");
    if ( db_num_rows($res) == 0 ) return;
  }

  ?>
  <div style="font-weight:bold;">Did you mean one of these?</div>

  <table border="0" cellpadding="10" cellspacing="0" width="740" height="20" class="results">
    <tr bgcolor="#001D37">
      <?
      $ct = -1;
      while( $row = db_fetch_object($res) )
      {
        $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name);
        if ( ++$ct%5 == 0 ) {
          if ( $ct%2 == 0 ) {
            ?></TR><TR><?
          }
          else {
            ?></TR><TR bgcolor="#001D37"><?
          }
        }
        ?>
        <td align="center" valign="top" width="20%">
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
  <?

}
?>