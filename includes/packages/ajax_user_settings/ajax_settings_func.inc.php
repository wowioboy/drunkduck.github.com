<?
function get_ajax_settings( &$USER )
{
  $obj = unserialize($USER->ajax_settings);
  if ( !is_object($obj) )
  {
    $obj = new stdClass();
    $obj->rackfilter = array();
  }

  if ( !isset($obj->rackfilter['10spot']) )   $obj->rackfilter['10spot']    = array();
  if ( !isset($obj->rackfilter['random']) )   $obj->rackfilter['random']    = array();
  if ( !isset($obj->rackfilter['new']) )      $obj->rackfilter['new']       = array();
  if ( !isset($obj->rackfilter['range']) )    $obj->rackfilter['range']     = 'all';
  if ( !isset($obj->rackfilter['expanded']) ) $obj->rackfilter['expanded']  = 'no';

  if ( is_object($USER) ) {
    save_ajax_settings($USER, $obj);
  }

  return $obj;
}

function save_ajax_settings( &$USER, $ajax_settings )
{
  if ( !is_object($USER) || !is_object($ajax_settings) ) {
    die("ERROR: save_ajax_settings() called without valid objects.");
  }

  db_query("UPDATE users SET ajax_settings='".db_escape_string(serialize($ajax_settings))."' WHERE username='".db_escape_string($USER->username)."'");
}



?>