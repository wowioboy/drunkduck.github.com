<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
/*ini_set('display_errors', 1);*/
/*error_reporting(E_ALL);*/

include_once('../includes/global.inc.php');
require_once('../includes/db.class.php');
$db = new DB();

if (!$USER) {
  die("You must be logged in to tag pages!");
}
if (!$tags = $_GET['tag']) {
  die("you didn't eneter a tag!");
}
if (!$CID = $_GET['cid']) {
  die('error. please try again later');
}
if (!$PID = $_GET['pid']) {
  die('error. please try again later');
}
$tags = explode(',', $tags);
foreach($tags as $t) {
  $t = trim($t);
  if (strlen($t) <= 50 && strlen($t) > 2) {
    $query = "insert INTO tags_used 
              (user_id, tag, comic_id, page_id) 
              VALUES 
              ('{$USER->user_id}', '$t', '$CID', '$PID')";
    if ($db->query($query, true) === true) {
      $query = "UPDATE tags_by_page 
                SET counter=counter+1 
                WHERE tag='$t' 
                AND comic_id='$CID' 
                AND page_id='$PID'";
      if ($db->query($query, true) !== true) {
        $query = "INSERT INTO tags_by_page 
                  (tag, comic_id, page_id, counter) 
                  VALUES 
                  ('$t', '$CID', '$PID', '1')";
        $db->query($query, true);
      }
      
      $query = "UPDATE tags_by_comic 
                SET counter=counter+1 
                WHERE tag='$t' 
                AND comic_id='$CID'";
      if ($db->query($query, true) !== true) {
        $query = "INSERT INTO tags_by_comic 
                  (tag, comic_id, counter) 
                  VALUES ('$t', '$CID', '1')";
        $db->query($query, true);
      }
      
      $query = "UPDATE tags_counter_daily 
                SET counter=counter+1 
                WHERE tag='$t' 
                AND ymd_date='" . date("Ymd") . "'";
      if ($db->query($query, true) !== true) {
        $query = "INSERT INTO tags_counter_daily 
                  (tag, counter, ymd_date) 
                  VALUES 
                  ('$t', '1', '" . date("Ymd") . "')";
        $db->query($query, true);
      }
      
      $query = "UPDATE tags_counter_all_time 
                SET counter=counter+1 
                WHERE tag='$t'";
      if ($db->query($query, true) !== true) {
        $query = "INSERT INTO tags_counter_all_time 
                  (tag, counter) 
                  VALUES ('$t', '1')";
        $db->query($query, true);
      }
    }
  }
}
