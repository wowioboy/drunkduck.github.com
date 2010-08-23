<?php
$REQUIRE_LOGIN = true;
$ADMIN_ONLY    = true;
$ADMIN_PAGE    = true;
$TITLE         = 'Tiki Games Admin [Editing Game #'.$_GET['gid']."]";
$CONTENT_FILE  = 'admin/games/edit_game.inc.php';
include_once('../../template.inc.php'); 
?>