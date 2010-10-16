<?php require_once('../header_base.php'); ?>

<?php
if (!$USER) {
   header('Location: /login.php');
  die('please log in to use this page!');
}

$query = "select c.comic_id as id, c.comic_name as title, from_unixtime(c.last_update) as updated_on, f.recommend, f.email_on_update as alert 
          from comics c 
          left join comic_favs f 
          on f.comic_id = c.comic_id
          where f.user_id = '{$USER->user_id}'";
$favorites = DB::getInstance()->fetchAll($query);
foreach ($favorites as &$favorite) {
  $date = new DateTime($favorite['updated_on']);
  $now = new DateTime();
  if ($date->format('Y-m-d') == $now->format('Y-m-d')) {
    $favorite['updated_on'] = 'today';
  } else {
    $favorite['updated_on'] = $date->format('M j');
  }
}
?>
<script type="text/javascript">
$(document).ready(function(){
  $('.big-favorite-recommend').change(function(){
    if ($(this).attr('checked')) {
      $('.favorite-recommend').attr('checked', true);
    } else {
      $('.favorite-recommend').attr('checked', false);
    }
  });
  $('.big-favorite-alert').change(function(){
    if ($(this).attr('checked')) {
      $('.favorite-alert').attr('checked', true);
    } else {
      $('.favorite-alert').attr('checked', false);
    }
  });
  $('.big-favorite-delete').change(function(){
    if ($(this).attr('checked')) {
      $('.favorite-delete').attr('checked', true);
    } else {
      $('.favorite-delete').attr('checked', false);
    }
  });
  
  $('.favorite-reccomend').change(function(){
    if (!$(this).attr('checked')) {
      $('.big-favorite-reccomend').attr('checked', false);
    }
  });
  $('.favorite-alert').change(function(){
    if (!$(this).attr('checked')) {
     $('.big-favorite-alert').attr('checked', false);
    }
  });
  $('.favorite-delete').change(function(){
    if (!$(this).attr('checked')) {
      $('.big-favorite-delete').attr('checked', false);
    }
  });
  
  $('#favorites_form').ajaxForm({
    beforeSubmit: function(arr){
      if ($('.favorite-delete:checked').size()) {
        var ans = confirm("are you sure you want to delete the selected favorites?");
        if (!ans) {
          return false;
        }
      }
      return true;
      $.fancybox.showActivity();
    },
    success: function(data) {
      $.fancybox.hideActivity();
      if (data) {
        data = $.parseJSON(data);
        $.each(data, function(k, v){
          $('tr[favorite=' + v + ']').fadeOut();
        });
      }
      alert('changes saved');
    }
  });
  
});
</script>
<style type="text/css">
.quack-table tbody tr {
  background-color:#fff;
}
.quack-table thead tr, .quack-table thead tr th {
  background-color:transparent;
}
</style>

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
        <div class="drunk" style="font-size:3em;">Favorites</div>

  <form id="favorites_form" method="post" action="/ajax/control_panel/favorites.php" width="100%">
    <input type="hidden" name="id" value="<?php echo $USER->user_id; ?>" />
    <table class="quack-table" width="100%" style="clear:both;margin-top:20px;">
      <thead>
        <tr class="unread">
          <th>comic</th>
          <th>last updated</th>
          <th>recommend</th>
          <th>email alerts</th>
          <th>delete</th>
       </tr>
     </thead>
   <tbody>
   <tr style="padding:0;height:10px;"><td colspan="5" style="padding:0;-moz-border-radius:10px 10px 0 0;border-radius:10px 10px 0 0;height:10px;"></td></tr>
  <?php foreach ($favorites as $favorite) : ?>
  <?php
  $date = new DateTime($favorite['updated_on']);
  $now = new DateTime();
  if ($date->format('Y-m-d') == $now->format('Y-m-d')) {
    $date = 'today';
  } else {
    $date = $date->format('M j');
  }
  ?>
  <tr favorite="<?php echo $favorite['id']; ?>" class="unread">
    <input type="hidden" name="favorite[]" value="<?php echo $favorite['id']; ?>" />
    <td width="200px;"><?php echo $favorite['title']; ?></td>
    <td><?php echo $favorite['updated_on']; ?></td>
    <td><input class="favorite-recommend" type="checkbox" name="recommend[]" value="<?php echo $favorite['id']; ?>"  <?php echo ($favorite['recommend']) ? 'checked' : ''; ?> /></td>
    <td><input class="favorite-alert" type="checkbox" name="alert[]" value="<?php echo $favorite['id']; ?>" <?php echo ($favorite['alert']) ? 'checked' : ''; ?> /></td>
    <td><input class="favorite-delete" type="checkbox" name="delete[]" value="<?php echo $favorite['id']; ?>" /></td>
  </tr>
  <?php endforeach; ?>
  <tr>
    <td>All</td>
    <td>&nbsp;</td>
    <td><input class="big-favorite-recommend" type="checkbox" /></td>
    <td><input class="big-favorite-alert" type="checkbox" /></td>
    <td><input class="big-favorite-delete" type="checkbox" /></td>
  </tr>
  <tr sytle="padding:0;height:10px;"><td colspan="5" style="padding:0;-moz-border-radius:0 0 10px 10px;border-radius:0 0 10px 10px;height:10px;"></td></tr>
 </tbody>
</table>
<div class="table fill">
  <div class="cell right">
        <input class="button" type="submit" value="save changes" />
  </div>
</div>
</form>
</div>
</div>

<?php require_once('../footer_base.php'); ?>