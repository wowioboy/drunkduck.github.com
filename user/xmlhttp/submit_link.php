<?php
define('NO_TRACK', 1);
define('DEBUG_MODE', 0); // keep debug info from polluting response.

include_once('../../includes/global.inc.php');

if ( !$USER ) {

  ?>alert('You are not logged in');<?
  return;

}

$NAME    = db_escape_string($_POST['name']);
$URL     = db_escape_string($_POST['url']);
$DESC    = db_escape_string($_POST['description']);


if ( strlen($NAME) < 1 ) {
  ?>alert('You must provide a name for your link.');<?
  return;
}

if ( strlen($URL) < 1 || substr(strtolower($URL), 0, strlen('http://')) != 'http://' ) {
  ?>alert('The URL provided seems to be invalid.');<?
  return;
}

if ( strlen($DESC) < 1 ) {
  $DESC = 'No Description';
}

db_query("INSERT INTO publisher_links (user_id, url, name, description) VALUES ('".$USER->user_id."', '".$URL."', '".$NAME."', '".$DESC."')");

$ID = db_get_insert_id();

$ALERT = '<div align="left" style="margin-bottom:5px;" id="link_'.db_get_insert_id($ID).'">'.
         '<a href="'.$URL.'">'.htmlentities($NAME, ENT_QUOTES).'</a>'.
         '<a href="#" onClick="deleteLink('.$ID.');return false;"><img src="'.IMAGE_HOST.'/site_gfx_new_v2/remove_button.gif" style="border:0px;"></a>'.
         '<br>'.
         nl2br(htmlentities($DESC, ENT_QUOTES)).
         '</div>';

?>$('pub_links').innerHTML = '<?=$ALERT?>' + $('pub_links').innerHTML<?
return;
?>