<?php
  $PACKAGE_DIR = dirname(__FILE__);

  require_once($PACKAGE_DIR.'/global_db_data.inc.php');
  require_once($PACKAGE_DIR.'/global_db_func.inc.php');

  if ( DEBUG_MODE ) {
    define('SHOW_DEBUG_INFO', 1);
  }

  package_loaded('database');
?>