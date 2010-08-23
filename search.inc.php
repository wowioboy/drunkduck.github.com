<?
if ( $USER->flags & FLAG_IS_ADMIN ) {
  include_once(WWW_ROOT.'/search_v2.inc.php');
  return;
}
?>

<div align="left">
  <h1>Browse and Search Comics</h1>
</div>

<?
$SEARCH                         = array();
$SEARCH['searchTxt']                 = db_escape_string(trim( ( isset($_GET['searchTxt'])      ) ? $_GET['searchTxt']      : $_POST['searchTxt']       ));
if ( isset($_GET['tag']) ) $SEARCH['searchTxt'] = db_escape_string( $_GET['tag'] );





$SEARCH['browsetype']                = ( ( isset($_GET['browsetype'])     ) ? $_GET['browsetype']     : $_POST['browsetype']      );
if ( $SEARCH['browsetype'] === null ) $SEARCH['browsetype'] = -1;
else $SEARCH['browsetype'] = (int)$SEARCH['browsetype'];


$SEARCH['search_style']             = ( ( isset($_GET['search_style'])    ) ? $_GET['search_style']    : $_POST['search_style']     );
if ( $SEARCH['search_style'] === null ) $SEARCH['search_style'] = array_keys($COMIC_ART_STYLES);
else {
  foreach($SEARCH['search_style'] as $key=>$value) {
    $SEARCH['search_style'][$key] = (int)$value;
  }
}



$SEARCH['search_category']             = ( ( isset($_GET['search_category'])    ) ? $_GET['search_category']    : $_POST['search_category']     );
if ( $SEARCH['search_category'] === null ) $SEARCH['search_category'] = array_keys($COMIC_CATEGORIES);
else {
  foreach($SEARCH['search_category'] as $key=>$value) {
    $SEARCH['search_category'][$key] = (int)$value;
  }
}



$SEARCH['browsetone']          = ( ( isset($_GET['browsetone'])     ) ? $_GET['browsetone']     : $_POST['browsetone']      );
if ( $SEARCH['browsetone'] === null ) $SEARCH['browsetone'] = -1;
else if ( ($SEARCH['browsetone'] != -1) && !isset($COMIC_SUBCATS[$SEARCH['browsetone']]) ) {
  $SEARCH['browsetone'] = -1;
}

$SEARCH['browseupdate']        = ( ( isset($_GET['browseupdate'])   ) ? $_GET['browseupdate']   : $_POST['browseupdate']    );
if ( $SEARCH['browseupdate'] === null ) $SEARCH['browseupdate'] = 'any';
else if ( ( $SEARCH['browseupdate'] != 'any' ) && ( $SEARCH['browseupdate'] != 'today' ) && ( $SEARCH['browseupdate'] != 'lastweek' ) && ( $SEARCH['browseupdate'] != 'lastmonth' ) ) {
  $SEARCH['browseupdate'] = 'any';
}

$SEARCH['browsenum']       = ( ( isset($_GET['browsenum'])      ) ? $_GET['browsenum']      : $_POST['browsenum']       );
if ( $SEARCH['browsenum'] === null ) $SEARCH['browsenum'] = 2;
else if ( ( $SEARCH['browsenum'] != 2 ) && ( $SEARCH['browsenum'] != 10 ) && ( $SEARCH['browsenum'] != 50 ) && ( $SEARCH['browsenum'] != 'range' ) ) {
  $SEARCH['browsenum'] = 2;
}

$SEARCH['browsenum_low']   = (int)( ( isset($_GET['browsenum_low'])  ) ? $_GET['browsenum_low']  : $_POST['browsenum_low']   );
if ( $SEARCH['browsenum_low'] < 0 ) $SEARCH['browsenum_low'] = 0;

$SEARCH['browsenum_high']  = (int)( ( isset($_GET['browsenum_high']) ) ? $_GET['browsenum_high'] : $_POST['browsenum_high']  );
if ( $SEARCH['browsenum_high'] < $SEARCH['browsenum_low'] ) $SEARCH['browsenum_high'] = $SEARCH['browsenum_low'];

$SEARCH["sortby"]               = ( ( isset($_GET['sortby'])         ) ? $_GET['sortby']         : $_POST['sortby']          );
if ( $SEARCH['sortby'] === null ) $SEARCH['sortby'] = 'read';

$SEARCH["ascdesc"]               = ( ( isset($_GET['ascdesc'])         ) ? $_GET['ascdesc']         : $_POST['ascdesc']          );
if ( isset($_GET['forceOrder']) ) $SEARCH['ascdesc'] = $_GET['forceOrder'];
if ( $SEARCH['ascdesc'] === null ) $SEARCH['ascdesc'] = 'DESC';
else if ( ($SEARCH['ascdesc'] != 'ASC') && ($SEARCH['ascdesc'] != 'DESC') ) {
  $SEARCH['ascdesc'] = 'DESC';
}

$Q_STR = array();
foreach($SEARCH as $key=>$value)
{
  if ( is_array($value) )
  {
    foreach($value as $value2)
    {
      $Q_STR[] = rawurlencode($key.'[]').'='.rawurlencode($value2);
    }
  }
  else
  {
    $Q_STR[] = rawurlencode($key).'='.rawurlencode($value);
  }

}
?>

<script language="JavaScript">
function checkAllStyles()
{
  <?
  foreach($COMIC_ART_STYLES as $key=>$value)
  {
    ?>$('search_style_<?=$key?>').checked = true;
    <?
  }
  ?>
}

function unCheckAllStyles()
{
  <?
  foreach($COMIC_ART_STYLES as $key=>$value)
  {
    ?>$('search_style_<?=$key?>').checked = false;
    <?
  }
  ?>
}

function checkAllCats()
{
  <?
  foreach($COMIC_CATEGORIES as $key=>$value)
  {
    ?>$('search_category_<?=$key?>').checked = true;
    <?
  }
  ?>
}

function unCheckAllCats()
{
  <?
  foreach($COMIC_CATEGORIES as $key=>$value)
  {
    ?>$('search_category_<?=$key?>').checked = false;
    <?
  }
  ?>
}
</script>
<form action="<?=$_SERVER['PHP_SELF']?>" method="get">


  <table border="0" cellpadding="5" cellspacing="5" width="740" class="sort">
    <tr>
      <td colspan="4" align="left">
        <a href="http://<?=USER_DOMAIN?>/search.php">.: Go To "Search Users"</a>
      </td>
    </tr>
    <tr>
      <td align="left" valign="middle" bgcolor="#001D37" style="color: white;"><strong>Search:</strong></td>
      <td colspan="3" align="left" valign="middle" bgcolor="#001D37" style="color: white;">
        <input name="searchTxt" value="<?=$SEARCH['searchTxt']?>" type="text" size="72" style="width:100%;">
      </td>
    </tr>
    <tr>
      <td colspan="4" align="left" valign="top" bgcolor="#00325D" style="color: white;"><strong>Browse/Search Criteria:</strong></td>
    </tr>
    <tr>
      <td width="15%" align="left" valign="top" style="color: white;" bgcolor="#001D37">
        <strong>Type of Comic:</strong><br>
        <label for="browsetypebook"><input name="browsetype" type="radio" value="1" id="browsetypebook"   <?=(($SEARCH['browsetype']==1)?"CHECKED":"")?>>Comic Book/Story</label><br>
        <label for="browsetypestrip"><input name="browsetype" type="radio" value="0" id="browsetypestrip" <?=(($SEARCH['browsetype']==0)?"CHECKED":"")?>>Comic Strip</label><br>
        <label for="browsetypeboth"><input name="browsetype" type="radio" value="-1" id="browsetypeboth"  <?=(($SEARCH['browsetype']==-1)?"CHECKED":"")?>>Both</label>

        <p>&nbsp;</p>

        <strong>Tone: </strong><br>
        <?
        foreach($COMIC_SUBCATS as $id=>$name) {
          ?><label for="browsetone_<?=$id?>"><input name="browsetone" type="radio" value="<?=$id?>" id="browsetone_<?=$id?>" <?=(($SEARCH['browsetone']==$id)?"CHECKED":"")?>><?=$name?></label><br><?
        }
        ?>
        <label for="browsetone_any"><input name="browsetone" type="radio" value="-1" id="browsetone_any"  <?=(($SEARCH['browsetone']==-1)?"CHECKED":"")?>>Any Tone</label>

      </td>
      <td width="44%" align="left" valign="top" style="color: white;" bgcolor="#001D37">
        <strong>Style:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:checkAllStyles()">Check All</a> | <a href="javascript:unCheckAllStyles();">Clear All</a><br>

        <table cellspacing="0" cellpadding="0" border="0" class="sort" width="100%">
        <?
        $ct = -1;
        foreach( $COMIC_ART_STYLES as $id=>$name)
        {
          ++$ct;
          if ( $ct%3 == 0 ) {
            ?></td><td valign="top" width="33%"><?
          }
          else if ( $ct%16==0 ) {
            ?></tr><tr><?
          }

          ?><label for="search_style_<?=$id?>"><input type="checkbox" name="search_style[]" value="<?=$id?>" id="search_style_<?=$id?>" <?=((in_array($id,$SEARCH['search_style']))?"CHECKED":"")?>><?=$name?></label><br><?
          /* ?><label for="search_style_<?=$id?>"><input type="checkbox" name="search_style[]" value="<?=$id?>" id="search_style_<?=$id?>" <?=((in_array($id,$SEARCH['search_style']))?"CHECKED":"")?>><img src="<?=IMAGE_HOST_SITE_GFX?>/style_icons/<?=$id?>.gif" width="12" height="12" /><?=$name?></label><br><? */
        }
        ?>
          </tr>
        </table>

        <p>&nbsp;</p>

        <strong>Categories:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:checkAllCats()">Check All</a> | <a href="javascript:unCheckAllCats();">Clear All</a><br>
        <table cellspacing="0" cellpadding="0" border="0" class="sort" width="100%">
        <?
        $ct = -1;
        foreach( $COMIC_CATEGORIES as $id=>$name)
        {
          ++$ct;
          if ( $ct%6 == 0 ) {
            ?></td><td valign="top" width="33%"><?
          }
          else if ( $ct%16==0 ) {
            ?></tr><tr><?
          }

          ?><label for="search_category_<?=$id?>"><input type="checkbox" name="search_category[]" value="<?=$id?>" id="search_category_<?=$id?>" <?=((in_array($id,$SEARCH['search_category']))?"CHECKED":"")?>><img src="<?=IMAGE_HOST_SITE_GFX?>/genre_icons/<?=$id?>.gif" width="12" height="12" /><?=$name?></label><br><?
        }
        ?>
          </tr>
        </table>

      </td>

      <td width="13%" align="left" valign="top" bgcolor="#001D37">
        <strong>Last Update: </strong><br>
        <label for="browseupdate_today"><input name="browseupdate" type="radio" value="today" id="browseupdate_today" <?=(($SEARCH['browseupdate']=='today')?"CHECKED":"")?>>Today</label><br>
        <label for="browseupdate_lastweek"><input name="browseupdate" type="radio" value="lastweek" id="browseupdate_lastweek" <?=(($SEARCH['browseupdate']=='lastweek')?"CHECKED":"")?>>Last Week</label><br>
        <label for="browseupdate_lastmonth"><input name="browseupdate" type="radio" value="lastmonth" id="browseupdate_lastmonth" <?=(($SEARCH['browseupdate']=='lastmonth')?"CHECKED":"")?>>Last Month</label><br>
        <label for="browseupdate_any"><input name="browseupdate" type="radio" value="any" id="browseupdate_any" <?=(($SEARCH['browseupdate']=='any')?"CHECKED":"")?>>Any</label>
      </td>
      <td width="15%" align="left" valign="top" bgcolor="#001D37">
        <strong>Number of Pages/Strips: </strong><br>
        <label for="browsenum_2"><input name="browsenum" type="radio" value="2" id="browsenum_2" <?=(($SEARCH['browsenum']==2)?"CHECKED":"")?>>2+</label><br>
        <label for="browsenum_10"><input name="browsenum" type="radio" value="10" id="browsenum_10" <?=(($SEARCH['browsenum']==10)?"CHECKED":"")?>>10+</label><br>
        <label for="browsenum_50"><input name="browsenum" type="radio" value="50" id="browsenum_50" <?=(($SEARCH['browsenum']==50)?"CHECKED":"")?>>50+</label><br>
        <label for="browsenum_range"><input name="browsenum" type="radio" value="range" id="browsenum_range" <?=(($SEARCH['browsenum']=='range')?"CHECKED":"")?>>Range:<br>
          <span align="right">
            <input name="browsenum_low" type="text" size="3" style="height:16px; font-size:10px;" value="<?=$SEARCH['browsenum_low']?>"> to <input name="browsenum_high" type="text" size="3" style="height:16px; font-size:10px;" value="<?=$SEARCH['browsenum_high']?>">
          </span>
        </label>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center" valign="middle" bgcolor="#001D37" style="color: white;">
        Sort by:
        <label for="sortby_title"><input name="sortby" type="radio" value="" id="sortby_title" <?=(($SEARCH["sortby"]=='')?"CHECKED":"")?>>Relevance</label>
        <label for="sortby_title"><input name="sortby" type="radio" value="title" id="sortby_title" <?=(($SEARCH["sortby"]=='title')?"CHECKED":"")?>>Title</label>
        <!-- <label for="sortby_creator"><input name="sortby" type="radio" value="creator" id="sortby_creator" <?=(($SEARCH["sortby"]=='creator')?"CHECKED":"")?>>Creator</label> -->
        <label for="sortby_pages"><input name="sortby" type="radio" value="pages" id="sortby_pages" <?=(($SEARCH["sortby"]=='pages')?"CHECKED":"")?>># of Pages</label>
        <label for="sortby_update"><input name="sortby" type="radio" value="update" id="sortby_update" <?=(($SEARCH["sortby"]=='update')?"CHECKED":"")?>>Last Update</label>
        <label for="sortby_read"><input name="sortby" type="radio" value="read" id="sortby_read" <?=(($SEARCH["sortby"]=='read')?"CHECKED":"")?>>Readers</label>
      </td>
      <td align="center" valign="middle" bgcolor="#001D37" style="color: white;">
        <?
        if ( $SEARCH['ascdesc'] == 'ASC' )
        {
          ?>
          <strong>Ascending</strong>
          /
          <a href="<?=$_SERVER['PHP_SELF']?>?p=<?=$_GET['p']?>&<?=implode("&", $Q_STR)?>&forceOrder=DESC">Descending</a>
          <?
        }
        else
        {
          ?>
          <a href="<?=$_SERVER['PHP_SELF']?>?p=<?=$_GET['p']?>&<?=implode("&", $Q_STR)?>&forceOrder=ASC">Ascending</a>
          /
          <strong>Descending</strong>
          <?
        }
        ?>
      </td>
      <td align="center" valign="middle" bgcolor="#001D37" style="color: white;"><input name="submit" type="submit" value="Go!"></td>
    </tr>
  </table>
</form>


<hr>


<?

if ( $_GET['advanced'] == 1 ) return;

if ( $SEARCH['browsetype'] != -1 ) {
  $WHERE[] = "comic_type='".$SEARCH['browsetype']."'";
}

$WHERE[] = "search_style IN ('".implode("','", $SEARCH['search_style'])."')";

$WHERE[] = "(search_category IN ('".implode("','", $SEARCH['search_category'])."') OR search_category_2 IN ('".implode("','", $SEARCH['search_category'])."'))";

if ( $SEARCH['browsetone'] != -1 ) {
  $WHERE[] = "subcategory='".$SEARCH['browsetone']."'";
}
if ( $SEARCH['browseupdate'] != 'any' )
{
  switch($SEARCH['browseupdate'])
  {
    case 'today':
      $TS_1 = mktime(0,0,0,date("m"), date("d"), date("Y") );
      $TS_2 = time();
    break;
    case 'lastweek':
      $TS_1 = mktime(0,0,0,date("m"), date("d")-7, date("Y") );
      $TS_2 = time();
    break;
    case 'lastmonth':
      $TS_1 = mktime(0,0,0,date("m"), -30, date("Y") );
      $TS_2 = time();
    break;
  }
  $WHERE[] = "last_update>='".$TS_1."' AND last_update<='".$TS_2."'";
}

if ( $SEARCH['browsenum'] != 'range' ) {
  $WHERE[] = "total_pages>='".$SEARCH['browsenum']."'";
}
else {
  $WHERE[] = "total_pages>='".$SEARCH['browsenum_low']."' AND total_pages <='".$SEARCH['browsenum_high']."'";
}

switch($SEARCH['sortby'])
{
  case 'title':
    $ORDER_BY = "ORDER BY comic_name ".$SEARCH['ascdesc'];
  break;
  case 'pages':
    $ORDER_BY = "ORDER BY total_pages ".$SEARCH['ascdesc'];
  break;
  case 'update':
    $ORDER_BY = "ORDER BY last_update ".$SEARCH['ascdesc'];
  break;
  case 'read':
    $ORDER_BY = "ORDER BY seven_day_visits ".$SEARCH['ascdesc'];
  break;
  default:
  break;
}

if ( strlen($SEARCH['searchTxt']) > 0 ) {
  //$WHERE[] = "MATCH (comic_name, description) AGAINST ('".$SEARCH['searchTxt']."') OR comic_name LIKE '%".$SEARCH['searchTxt']."%'";
  $WHERE[] = "MATCH (comic_name, description) AGAINST ('".$SEARCH['searchTxt']."')";
  //$WHERE[] = "(comic_name LIKE '%".$SEARCH['searchTxt']."%' OR description LIKE '%".$SEARCH['searchTxt']."%')";
}


$AMT_PER_PAGE = (int)$_GET['amtPerPage'];
if ( ($AMT_PER_PAGE != 8) && ($AMT_PER_PAGE != 23) && ($AMT_PER_PAGE != 47) )
{
  $AMT_PER_PAGE = 23;
}

$P = (int)$_GET['p']-1;
if ($P<0)$P=0;


$res = db_query("SELECT COUNT(*) as total_results FROM comics WHERE ".implode(' AND ', $WHERE));
$row = db_fetch_object($res);
db_free_result($res);
$TOTAL = $row->total_results;
$TOTAL_PAGES = ceil($TOTAL / $AMT_PER_PAGE);


$res = db_query("SELECT comic_name, comic_id, last_update, category, total_pages, rating_symbol FROM comics WHERE ".implode(' AND ', $WHERE)." ".$ORDER_BY." LIMIT ".($P*$AMT_PER_PAGE).", ".$AMT_PER_PAGE);

?>



<table width="740" border="0" cellspacing="5" cellpadding="5" class="sort">
  <form action="<?=$_SERVER['PHP_SELF']?>" method="get">
  <tr>
    <td width="477" height="22" align="left" bgcolor="#001D37" style="color: white;">
      <strong>Result:</strong> <?=number_format($TOTAL)?> comics matched your selection criteria.
      <?
      switch( $AMT_PER_PAGE )
      {
        case 8:
          ?>
            <strong><b>8 per page</b></strong> |
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=23">23 per page</a> |
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=47">47 per page</a>
          <?
        break;
        case 23:
          ?>
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=8">8 per page</a>  |
            <strong><b>23 per page</b></strong> |
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=47">47 per page</a>
          <?
        break;
        case 47:
          ?>
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=8">8 per page</a>  |
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=23">23 per page</a> |
            <strong><b>47 per page</b></strong>
          <?
        break;
      }
      ?>
    </td>
    <td align="center" style="color: white;" bgcolor="#001D37">
      <div align="right">
        <?
          if ( $P > 0 ) {
            ?><a href="<?=$_SERVER['PHP_SELF']?>?p=<?=$P?>&<?=implode("&", $Q_STR)?>&amtPerPage=<?=$AMT_PER_PAGE?>">Last <?=$AMT_PER_PAGE?></a><?
          }
        ?>

        Page <input name="p" value="<?=($P+1)?>" style="width: 30px; height:16px; font-size:10px;" type="text"> of <?=number_format($TOTAL_PAGES)?>
        <?
          if ( $P < ($TOTAL_PAGES-1) ) {
            ?><a href="<?=$_SERVER['PHP_SELF']?>?p=<?=($P+2)?>&<?=implode("&", $Q_STR)?>&amtPerPage=<?=$AMT_PER_PAGE?>">Next <?=$AMT_PER_PAGE?></a><?
          }
        ?>
      </div>
    </td>
  </tr>
  <?
  foreach($SEARCH as $key=>$value)
  {
    if ( is_array($value) )
    {
      foreach($value as $value2)
      {
        ?><input type="hidden" name="<?=$key?>[]" value="<?=$value2?>"><?
      }
    }
    else
    {
      ?><input type="hidden" name="<?=$key?>" value="<?=$value?>"><?
    }

  }
  ?>
  </form>
</table>




  <div class="newspost" style="width:160px;float:right;">
<? $ord=time() ?>
<script language="JavaScript" src="http://ad.doubleclick.net/adj/dduck.template/skyscraper_main;sz=160x600;ord=<? echo $ord ?>?" type="text/javascript"></script>
<noscript><a href="http://ad.doubleclick.net/jump/dduck.template/skyscraper_main;sz=160x600;ord=<? echo $ord ?>?" target="_blank"><img src="http://ad.doubleclick.net/ad/dduck.template/skyscraper_main;sz=160x600;ord=<? echo $ord ?>?" width="160" height="600" border="0" alt=""></a></noscript>
  </div>
<table border="0" cellpadding="10" cellspacing="0" width="580" height="20" class="results">
  <tr bgcolor="#001D37">
    <?
    $ct = -1;
    $adshown = false;
    while($row = db_fetch_object($res))
    {
      $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name);
      if ( ++$ct%3 == 0 ) {
        if ( $ct%2 == 0 ) {
          ?></TR><TR><?
        }
        else {
          ?></TR><TR bgcolor="#001D37"><?
        }
      }
      ?>
        <td align="center" valign="top" width="33%">
          <a href='<?=$url?>'><img src="<?=thumb_processor($row)?>" border="0"></a>
          <br>
          <?=$row->comic_name?></a>
          <br>
          <span class="stripinfo">
            <?=number_format($row->total_pages)?> pgs | Updated: <?=((date("Ymd", $row->last_update)==YMD)?"Today":date("m/d/y", $row->last_update))?>
          </span>
        </td>
      <?

      if ( $ct == 6 && !$adshown )
      {
        $adshown = true;
        ?>
        <td colspan="2" rowspan="2" align="center">
<? $ord=time() ?>
<script language="JavaScript" src="http://ad.doubleclick.net/adj/dduck.template/box_main;sz=300x250;ord=<? echo $ord ?>?" type="text/javascript"></script>
<noscript><a href="http://ad.doubleclick.net/jump/dduck.template/box_main;sz=300x250;ord=<? echo $ord ?>?" target="_blank"><img src="http://ad.doubleclick.net/ad/dduck.template/box_main;sz=300x250;ord=<? echo $ord ?>?" width="300" height="250" border="0" alt=""></a></noscript>
        </td>
        <?
        $ct += 2;
      }
      if ( $ct == 9 )
      {
        $ct += 2;
      }

    }
    ?>
  </tr>
</table>
















<table width="740" border="0" cellspacing="5" cellpadding="5" class="sort">
  <form action="<?=$_SERVER['PHP_SELF']?>" method="get">
  <tr>
    <td width="477" height="22" align="left" bgcolor="#001D37" style="color: white;">
      <strong>Result:</strong> <?=number_format($TOTAL)?> comics matched your selection criteria.
      <?
      switch( $AMT_PER_PAGE )
      {
        case 8:
          ?>
            <strong><b>8 per page</b></strong> |
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=23">23 per page</a> |
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=47">47 per page</a>
          <?
        break;
        case 23:
          ?>
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=8">8 per page</a>  |
            <strong><b>23 per page</b></strong> |
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=47">47 per page</a>
          <?
        break;
        case 47:
          ?>
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=8">8 per page</a>  |
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=23">23 per page</a> |
            <strong><b>47 per page</b></strong>
          <?
        break;
      }
      ?>
    </td>
    <td align="center" style="color: white;" bgcolor="#001D37">
      <div align="right">
        <?
          if ( $P > 0 ) {
            ?><a href="<?=$_SERVER['PHP_SELF']?>?p=<?=$P?>&<?=implode("&", $Q_STR)?>&amtPerPage=<?=$AMT_PER_PAGE?>">Last <?=$AMT_PER_PAGE?></a><?
          }
        ?>

        Page <input name="p" value="<?=($P+1)?>" style="width: 30px; height:16px; font-size:10px;" type="text"> of <?=number_format($TOTAL_PAGES)?>
        <?
          if ( $P < ($TOTAL_PAGES-1) ) {
            ?><a href="<?=$_SERVER['PHP_SELF']?>?p=<?=($P+2)?>&<?=implode("&", $Q_STR)?>&amtPerPage=<?=$AMT_PER_PAGE?>">Next <?=$AMT_PER_PAGE?></a><?
          }
        ?>
      </div>
    </td>
  </tr>
  <?
  foreach($SEARCH as $key=>$value)
  {
    if ( is_array($value) )
    {
      foreach($value as $value2)
      {
        ?><input type="hidden" name="<?=$key?>[]" value="<?=$value2?>"><?
      }
    }
    else
    {
      ?><input type="hidden" name="<?=$key?>" value="<?=$value?>"><?
    }

  }
  ?>
  </form>
</table>