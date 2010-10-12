<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');
require_once('../includes/db.class.php');
$db = new DB();

if (!$USER) {
  die('you must be logged in');
}
if (!$CID = $_GET['cid']) {
  die('error. try agian later');
}

$query = "UPDATE comic_favs 
          SET email_on_update = '1' 
          WHERE user_id='{$USER->user_id}' 
          AND comic_id='$CID'";
if ($db->query($query, true) === true) {
  die("You will be notified by email when this comic updates.");
} else {
  die("Oops! Make this comic a favorite first.");
}