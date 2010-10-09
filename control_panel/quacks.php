<?php require_once('../header_base.php'); ?>


<?php
  $where = "username_to = '{$USER->username}' and username_from is not null";
$query = "select mail_id as id, username_to as `to`, username_from as `from`, title as subject, from_unixtime(time_sent) as recieved, viewed as status, message 
          from mailbox  
          where $where 
          order by time_sent desc 
          limit 0, 20";
$quacks = DB::getInstance()->fetchAll($query);
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
    $.fancybox.showActivity();
    $.getJSON('/ajax/control_panel/quacks-get.php', {offset: pager, view: '<?php echo $quacks_view; ?>', username: '<?php echo $USER->username; ?>'}, function(data){
      var i = 0;
      var html = '';
      if (data) {
        $.each(data, function(){
          console.log(this);
          html += '<tr quack="' + this.id + '">' + 
                  '<td><input type="checkbox" class="quack-check" name="quack" value="' + this.id + '" /></td>';
            html += '<td>' + this.from + '</td>';
            html += '<td><a class="toggle-quack-message" quack="' + this.id + '" style="text-decoration:underline;" href="javascript:">' + this.subject + '</a></td>' + 
                    '<td>' + this.recieved + '</td>' + 
                    '<td class="quack-status" quack="' + this.id + '">' + this.status + '</td>' + 
                    '</tr>' + 
                    '<tr class="quack-message" quack="' + this.id + '" style="display:none;">' + 
                    '<td colspan="5">' + this.message + '</td>' + 
                    '</tr>'
                    ;
        });
      }
      $('#quack_holder').html(html);
      $.fancybox.hideActivity();
    });
  }
  
  $('.big-quack-check').change(function(){
    if ($(this).attr('checked')) {
      $('.quack-check').attr('checked', true);
    } else {
      $('.quack-check').attr('checked', false);
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
    $.fancybox.showActivity();
      $.post('/ajax/control_panel/quacks-delete.php', {username: '<?php echo $USER->username; ?>', quacks: deletes}, function(data){
        $.each(deletes, function(k, v) {
          $('tr[quack=' + v + ']').fadeOut();
        });
      });
    $.fancybox.hideActivity();
    }
    }
  });
  
  $('.toggle-quack-message').live('click', function(){
    var quackId = $(this).attr('quack');
    var quackMessage = $('.quack-message[quack=' + quackId + ']');
    var quackStatus = $('.quack-status[quack=' + quackId + ']');
    if (quackMessage.css('display') == 'none') {
      quackMessage.show();
      if (quackStatus.html() == 'unread') {
        $.post('/ajax/control_panel/quack-read.php', {mail_id: quackId}, function(){
          quackStatus.html('read');
        });
      }
    } else {
      quackMessage.hide();
    }
  });
  
  $('.quack-reply-button').click(function(){
    var from = $(this).attr('from');
    var subject = $(this).attr('subject');
    $.fancybox({
      type: 'ajax',
      href: '/control_panel/compose-quack.php?to=' + from + '&subject=RE: ' + subject,
      autoDimensions: false,
      height:500
    });
  });
});
</script>

<div class="rounded canary span-63 box-1 pull-1" style="clear:both;">
    <div class="span-63 green rounded header">
    User Control Panel
    </div>
</div>

<div class="span-55 box-1 header-menu">
<a class="teal rounded button" href="/control_panel/account.php">account</a>
<a class="teal rounded button" href="/control_panel/profile.php">profile</a>
<a class="teal rounded button" href="/control_panel/favorites.php">favorites</a>
</div>
<div class="box-2" style="padding-top:120px">
    <div class="box-2 yellow rounded" >
        <div class="drunk" style="font-size:3em;">Personal Quacks</div>


<div>
<a class="teal rounded button" href="/control_panel/quacks-outbox.php">Sent</a>
</div>

<style>
tbody{

    }
tbody tr {
    
    background-color: white;    
    }    
tr td {
    padding: 10px 0 10px 0;
    font-weight: bold;
    font-family: helvetica;
    font-size: 12px;
    color: #006563;
    }
tr td:first-child {
    padding:10px;       
}
tr.quack-message td {
    color: #333;
    font-weight:normal;
    font-size:11px;}      
/*thead tr:first-child {
    background-color: transparent;
}*/
thead tr, thead tr th{
    background-color: transparent;
    border-radius: 10px;
    }
</style>
<div>
    <button class="teal button rounded quack_button" direction="prev">previous</button>
  <button class="teal button rounded quack_button" direction="next">next</button>
  <button class="teal button rounded" id="quack-delete-button">delete</button>
</div>
<table >
 <thead>
 <tr>
   <th><input type="checkbox" class="" /></th>
   <th>from</th>
   <th>subject</th>
   <th>recieved</th>
   <th>status</th>
 </tr>
 </thead>
 <tbody id="quack_holder">
 <tr sytle="padding:0;height:10px;"><td colspan="5" style="padding:0;border-radius:10px 10px 0 0;height:10px;"></td></tr>
<?php foreach ((array) $quacks as $quack) : ?>
<?php
if (!$from = $quack['from']) {
  $from = 'admin';
}
$status = ($quack['status']) ? 'read' : 'unread';

$recieved = new DateTime($quack['recieved']);
$now = new DateTime();
if ($recieved->format('Y-m-d') == $now->format('Y-m-d')) {
  $recieved = $recieved->format('g:i a');
} else {
  $recieved = $recieved->format('M j');
}
?>
<tr quack="<?php echo $quack['id']; ?>">
  <td><input type="checkbox" class="quack-check" name="quack" value="<?php echo $quack['id']; ?>" /></td>
  <td><?php echo $from; ?></td>
  <td><a class="toggle-quack-message" quack="<?php echo $quack['id']; ?>" href="javascript:"><?php echo $quack['subject']; ?></a></td>
  <td><?php echo $recieved; ?></td>
  <td class="quack-status" quack="<?php echo $quack['id']; ?>"><?php echo $status; ?></td>
</tr>
<tr class="quack-message" quack="<?php echo $quack['id']; ?>" style="display:none;">
  <td colspan="5">
    <?php echo $quack['message']; ?>
    <br />
    <button class="quack-reply-button" from="<?php echo $from; ?>" subject="<?php echo $quack['subject']; ?>">reply</button>
  </td>
</tr>

<?php endforeach; ?>
<tr sytle="padding:0;height:10px;"><td colspan="5" style="padding:0;border-radius:0 0 10px 10px;height:10px;"></td></tr>
 </tbody>
</table>

    </div>
</div>
<?php require_once('../footer_base.php'); ?>