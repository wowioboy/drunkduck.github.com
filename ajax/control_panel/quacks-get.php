<?php 
require_once('../../includes/db.class.php');
if ($username = $_REQUEST['username']) {
  if ($_REQUEST['view'] == 'sent') {
    $where = "username_from = '$username' and username_to is not null";
  } else {
    $where = "username_to = '$username' and username_from is not null";
  }
  $offset = $_REQUEST['offset'];
  $query = "select mail_id as id, username_to as `to`, username_from as `from`, title as subject, from_unixtime(time_sent) as recieved, viewed as status, message 
            from mailbox  
            where $where 
            order by time_sent desc 
            limit $offset, 25";
  $quacks = DB::getInstance()->fetchAll($query);
  foreach ($quacks as &$quack) {
    if (!$from = $quack['from']) {
      $quack['from'] = 'admin';
    }
    $quack['status'] = ($quack['status']) ? 'read' : 'unread';
    $recieved = new DateTime($quack['recieved']);
    $now = new DateTime();
    if ($recieved->format('Y-m-d') == $now->format('Y-m-d')) {
      $quack['recieved'] = $recieved->format('g:i a');
    } else {
      $quack['recieved'] = $recieved->format('M j');
    }
  }
  echo json_encode($quacks);
}