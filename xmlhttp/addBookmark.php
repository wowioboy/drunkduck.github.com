<?php
include_once('../includes/global.inc.php');
require_once('../includes/db.class.php');
$db = new DB();

if (!$USER) {
  die('you must be logged in');
}
if (!$CID = $_GET['cid']) {
  die('error. try again later');
}
if (!$PID = $_GET['p']) {
  die('error. try again later');
}
$query = "select count(1) 
          from comic_favs 
          where user_id = '{$USER->user_id}' 
          and comic_id = '$CID'";
if ((bool) $db->fetchOne($query)) {
  $query = "UPDATE comic_favs 
            SET bookmark_page_id='$PID' 
            WHERE user_id='{$USER->user_id}' 
            AND comic_id='$CID'";
  $db->query($query);
  die('This page has been saved. Click "GO" at any time on this comic to jump to your bookmark.');
} else {
  $query = "insert into comic_favs 
            (bookmark_page_id, comic_id, user_id) 
            values 
            ('$PID', '$CID', '{$USER->user_id}')";
  $db->query($query);
  die("This page has been bookmarked and added to your favorites.");
}