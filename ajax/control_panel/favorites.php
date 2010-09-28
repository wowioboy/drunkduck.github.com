<?php 
require_once('../../includes/db.class.php');
if ($id = $_POST['id']) {
  $db = new DB();
  foreach ((array) $_POST['favorite'] as $favorite_id) {
    if (in_array($favorite_id, $_POST['delete'])) {
      $delete[] = $favorite_id;
    } else {
      if (in_array($favorite_id, $_POST['recommend'])) {
        $recommendOn[] = $favorite_id;
      } else {
        $recommendOff[] = $favorite_id;
      }
      if (in_array($favorite_id, $_POST['alert'])) {
        $alertOn[] = $favorite_id;
      } else {
        $alertOff[] = $favorite_id;
      }
    }
  }
  
  # TURN ON RECOMMEND
  if ($recommendOn) {
    $recommendOn = implode(',', $recommendOn);
    $query = "update comic_favs set recommend = '1' where user_id = $id and comic_id in ($recommendOn)";
    $db->query($query);
  }
  
  # TURN OFF RECOMMEND
  if ($recommendOff) {
    $recommendOff = implode(',', $recommendOff);
    $query = "update comic_favs set recommend = '0' where user_id = $id and comic_id in ($recommendOff)";
    $db->query($query);
  }
  
  # TURN OFF ALERTS
  if ($alertOff) {
    $alertOff = implode(',', $alertOff);
    $query = "update comic_favs set email_on_update = '0' where user_id = $id and comic_id in ($alertOff)";
    $db->query($query);
  }
  
  # TURN ON ALERTS
  if ($alertOn) {
    $alertOn = implode(',', $alertOn);
    $query = "update comic_favs set email_on_update = '1' where user_id = $id and comic_id in ($alertOn)";
    $db->query($query);
  }

  # DELETE COMIC FAVORITES
  if ($delete) {
    $deleteArray = implode(',', $delete);
    $query = "delete from comic_favs where user_id = $id and comic_id in ($deleteArray)";
    $db->query($query);
    echo json_encode($delete);
  }
}