<?php require_once('../header_base.php'); ?>

<?php
if (!$USER) {
   header('Location: /login.php');
  die('please log in to use this page!');
}

$where = "username_from = '{$USER->username}' and username_to is not null";
$query = "select mail_id as id, username_to as `to`, username_from as `from`, title as subject, from_unixtime(time_sent) as recieved, viewed as status, message 
          from mailbox  
          where $where 
          order by time_sent desc 
          limit 0, 20";
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

$query = "select count(1) 
          from mailbox  
          where $where";
$quackCount = DB::getInstance()->fetchOne($query);
?>
<style type="text/css">
.quack-message {
  background-color:#fff;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
  var pager = 0;
  var pager_max = <?php echo $quackCount; ?>;
  
  $('.quack_button').click(function() {
    var valid = false;
    var dir = $(this).attr('direction');
    if (dir == 'next') {
      if (pager < pager_max - 20) {
        pager += 20;
        valid = true;
      }
    } else {
      if (pager > 0) {
        pager -= 20;  
        valid = true;
      }
    }
    if (valid) {
      getQuacks();
    }
  });
  
  function getQuacks() 
  {
    $.getJSON('/ajax/control_panel/quacks-get.php', {offset: pager, view: '<?php echo $quacks_view; ?>', username: '<?php echo $USER->username; ?>'}, function(data){
      var i = 0;
      var html = '';
      if (data) {
          html += '<tr sytle="padding:0;height:10px;"><td colspan="5" style="padding:0;-moz-border-radius:10px 10px 0 0;border-radius:10px 10px 0 0;height:10px;"></td></tr>';
        $.each(data, function(){
          console.log(this);
          html += '<tr quack="' + this.id + '" class="' + this.status + '">' + 
                  '<td><input type="checkbox" class="quack-check" name="quack" value="' + this.id + '" /></td>';
            html += '<td>' + this.to + '</td>';
            html += '<td><a class="toggle-quack-message" quack="' + this.id + '" href="javascript:">' + this.subject + '</a></td>' + 
                    '<td>' + this.recieved + '</td>' + 
                    '<td class="quack-status" quack="' + this.id + '">' + this.status + '</td>' + 
                    '</tr>' + 
                    '<tr class="quack-message" quack="' + this.id + '" style="display:none;">' + 
                    '<td colspan="5">' + this.message + '</td>' + 
                    '</tr>';
        });
        html += '<tr sytle="padding:0;height:10px;"><td colspan="5" style="padding:0;-moz-border-radius:0 0 10px 10px;border-radius:0 0 10px 10px;height:10px;"></td></tr>';
      }
      $('#quack_holder').html(html);
    });
  }
  
  $('.big-quack-check').change(function(){
    if ($(this).attr('checked')) {
      $('.quack-check').attr('checked', true);
    } else {
      $('.quack-check').attr('checked', false);
    }
  });
   $('.quack-check').change(function(){
    if (!$(this).attr('checked')) {
      $('.big-quack-check').attr('checked', false);
    }
  });
  $('#quack-delete-button').click(function(){
    var deletes = new Array();
    $('.quack-check:checked').each(function(){
      deletes.push($(this).val());
    });
    if (deletes.length) {
    var ans = confirm('are you sure you want to delete these messages?');
    if (ans) {
      $.post('/ajax/control_panel/quacks-delete.php', {username: '<?php echo $USER->username; ?>', quacks: deletes}, function(data){
        $.each(deletes, function(k, v) {
          $('tr[quack=' + v + ']').fadeOut();
        });
      });
    }
    }
  });
  
  $('.toggle-quack-message').live('click', function(){
    var quackId = $(this).attr('quack');
    var quackMessage = $('.quack-message[quack=' + quackId + ']');
    var quackStatus = $('.quack-status[quack=' + quackId + ']');
    if (quackMessage.css('display') == 'none') {
      quackMessage.show();
    } else {
      quackMessage.hide();
    }
  });
});
</script>

<div class="rounded canary span-63 box-1 pull-1" style="height:100px;clear:both;">
  <div class="span-63 dark-green rounded header">
    <img src="/media/images/control-panel.png" />
  </div>
  <div class="span-61 box-1 header-menu">
    <a class="button nav" href="/control_panel/profile.php">public profile</a>
    <a class="button nav" href="/control_panel/account.php">account</a>
    <a class="button nav" href="/control_panel/favorites.php">favorites</a>
    <a class="button nav" href="/control_panel/quacks.php">personal quacks</a>
  </div>
</div>
<div class="box-2" style="clear:both;">
    <div class="box-2 yellow rounded" >
        <div class="drunk" style="font-size:3em;">Personal Quacks</div>


<div style="height:50px;">
<a class="yellow-button teal-words" href="/control_panel/quacks.php">inbox</a>
<a class="yellow-button teal-words" href="/control_panel/compose-quack.php">compose new quack</a>
<a class="yellow-button" href="javascript:">sent</a>
</div>

<style type="text/css">
.quack-table tbody tr {
  background-color:#fff;
}
.quack-table thead tr, .quack-table thead tr th {
  background-color:transparent;
}
</style>
<table class="quack-table" width="100%" style="clear:both;margin-top:20px;">
 <thead>
 <tr class="unread">
   <th><input type="checkbox" class="big-quack-check" /></th>
   <th>to</th>
   <th>subject</th>
   <th>recieved</th>
   <th>status</th>
 </tr>
 </thead>
 <tbody id="quack_holder">
 <tr sytle="padding:0;height:10px;"><td colspan="5" style="padding:0;-moz-border-radius:10px 10px 0 0;border-radius:10px 10px 0 0;height:10px;"></td></tr>
<?php foreach ((array) $quacks as $quack) : ?>
<tr quack="<?php echo $quack['id']; ?>" class="<?php echo $quack['status']; ?>">
  <td><input type="checkbox" class="quack-check" name="quack" value="<?php echo $quack['id']; ?>" /></td>
  <td><?php echo $quack['to']; ?></td>
  <td><a class="toggle-quack-message" quack="<?php echo $quack['id']; ?>" href="javascript:"><?php echo $quack['subject']; ?></a></td>
  <td><?php echo $quack['recieved']; ?></td>
  <td class="quack-status" quack="<?php echo $quack['id']; ?>"><?php echo $quack['status']; ?></td>
</tr>
<tr class="quack-message" quack="<?php echo $quack['id']; ?>" style="display:none;">
  <td colspan="5">
    <?php echo $quack['message']; ?>
  </td>
</tr>

<?php endforeach; ?>
<tr sytle="padding:0;height:10px;"><td colspan="5" style="padding:0;-moz-border-radius:0 0 10px 10px;border-radius:0 0 10px 10px;height:10px;"></td></tr>
 </tbody>
</table>
<div style="height:10px;"></div>
<div class="table fill">
  <div class="cell">
    <button class="button quack_button" direction="prev">previous</button>
  </div>
  <div class="cell right">
    <button class="button quack_button" direction="next">next</button>
  </div>
</div>
    </div>
</div>
<?php require_once('../footer_base.php'); ?>