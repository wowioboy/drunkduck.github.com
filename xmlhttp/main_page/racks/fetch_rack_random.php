<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../../../includes/global.inc.php');

$OBJ = get_ajax_settings( $USER );

$RACKNAME = 'random';

if ( isset($_POST['editrack']) )
{
  ?>rack_<?=$RACKNAME?>[1]<?

  unset($OBJ->rackfilter[$RACKNAME]['comic_type']);
  $OBJ->rackfilter[$RACKNAME]['comic_type'] = array();
  foreach( $COMIC_STYLES as $id=>$name)
  {
    if ( !in_array($id, $_POST['comic_type_filter']) ) {
      $OBJ->rackfilter[$RACKNAME]['comic_type'][$id] = 1;
    }
  }


  unset($OBJ->rackfilter[$RACKNAME]['search_style']);
  $OBJ->rackfilter[$RACKNAME]['search_style'] = array();
  foreach( $COMIC_ART_STYLES as $id=>$name )
  {
    if ( !in_array($id, $_POST['style_filter']) ) {
      $OBJ->rackfilter[$RACKNAME]['search_style'][$id] = 1;
    }
  }


  unset($OBJ->rackfilter[$RACKNAME]['search_category']);
  $OBJ->rackfilter[$RACKNAME]['search_category'] = array();
  foreach( $COMIC_CATEGORIES as $id=>$name )
  {
    if ( !in_array($id, $_POST['cat_filter']) ) {
      $OBJ->rackfilter[$RACKNAME]['search_category'][$id] = 1;
    }
  }

  unset($OBJ->rackfilter[$RACKNAME]['search_rating']);
  $OBJ->rackfilter[$RACKNAME]['search_rating'] = array();
  foreach( $RATINGS as $id=>$name )
  {
    if ( !in_array($id, $_POST['rating_filter']) ) {
      $OBJ->rackfilter[$RACKNAME]['search_rating'][$id] = 1;
    }
  }

  if ( $USER ) {
    save_ajax_settings( $USER, $OBJ );
  }
}

$WHERE = array();
$FILTERED = false;

if ( count($OBJ->rackfilter[$RACKNAME]['comic_type']) ) {
  $WHERE[] = "comic_type NOT IN ('".implode("','", array_keys($OBJ->rackfilter[$RACKNAME]['comic_type']))."')";
}

if ( count($OBJ->rackfilter[$RACKNAME]['search_style']) ) {
  $WHERE[] = "search_style NOT IN ('".implode("','", array_keys($OBJ->rackfilter[$RACKNAME]['search_style']))."')";
  $FILTERED = true;
}

if ( count($OBJ->rackfilter[$RACKNAME]['search_category']) ) {
  $WHERE[] = "search_category NOT IN ('".implode("','", array_keys($OBJ->rackfilter[$RACKNAME]['search_category']))."')";
  $WHERE[] = "search_category_2 NOT IN ('".implode("','", array_keys($OBJ->rackfilter[$RACKNAME]['search_category']))."')";
  $FILTERED = true;
}

if ( count($OBJ->rackfilter[$RACKNAME]['search_rating']) ) {
  $WHERE[] = "rating_symbol NOT IN ('".implode("','", array_keys($OBJ->rackfilter[$RACKNAME]['search_rating']))."')";
  $FILTERED = true;
}

if ( !isset($OBJ->rackfilter[$RACKNAME]['comic_type'][0]) && !isset($OBJ->rackfilter[$RACKNAME]['comic_type'][1]) ) {
  $LIST_NAME = 'Everything';
}
else if ( isset($OBJ->rackfilter[$RACKNAME]['comic_type'][0]) && !isset($OBJ->rackfilter[$RACKNAME]['comic_type'][1]) ) {
  $LIST_NAME = 'Stories';
}
else if ( !isset($OBJ->rackfilter[$RACKNAME]['comic_type'][0]) && isset($OBJ->rackfilter[$RACKNAME]['comic_type'][1]) ) {
  $LIST_NAME = 'Strips';
}

if ( $FILTERED ) {
  $LIST_NAME .= ' (Filtered)';
}
    
if ( isset($GLOBALS['SUBDOM_TO_CAT'][SUBDOMAIN]) || isset($GLOBALS['SUBDOM_TO_STYLE'][SUBDOMAIN]) )
{
    $WHERE = array();
    
    if ( isset($GLOBALS['SUBDOM_TO_CAT'][SUBDOMAIN]) ) {
        $WHERE[] = "search_category='".$GLOBALS['SUBDOM_TO_CAT'][SUBDOMAIN]."'";
    }
    if ( isset($GLOBALS['SUBDOM_TO_STYLE'][SUBDOMAIN]) ) {
        $WHERE[] = "search_style='".$GLOBALS['SUBDOM_TO_STYLE'][SUBDOMAIN]."'";
    }
}

$WHERE[] = "total_pages>'0'";
$WHERE[] = "delisted='0'";

$GLOBALS['QUERY'] = "SELECT comic_id, comic_name, last_update, category, rating_symbol, flags FROM comics WHERE ".implode(' AND ', $WHERE)." ORDER BY RAND() DESC LIMIT 10";
?>
<div align="left" style="width:168px;">
  Quail's Random:<br>
  <img src="<?=IMAGE_HOST?>/site_gfx_new_v3/sort_arrow.gif"> <a href="#" onClick="editFilter('<?=$RACKNAME?>');return false;"><?=$LIST_NAME?></a>
</div>
<?
cached_include(WWW_ROOT.'/xmlhttp/main_page/racks/rack_query.inc.php', 300, 'random');
?>