<?php require_once('../header_base.php'); ?>

<?php
$USER = new stdClass();
$USER->user_id = '19085';
$USER->username = 'pscomics';
$USER->signed_up = '2009-12-31';

$query = "select c.comic_id as id, c.comic_name as title, from_unixtime(c.last_update) as updated_on, f.recommend, f.email_on_update as alert 
          from comics c 
          left join comic_favs f 
          on f.comic_id = c.comic_id
          where f.user_id = '{$USER->user_id}'";
$favorites = DB::getInstance()->fetchAll($query);
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
<form id="favorites_form" method="post" action="/ajax/control_panel/favorites.php">
<input type="hidden" name="id" value="<?php echo $USER->user_id; ?>" />
<table>
 <thead>
 <tr>
   <th>comic</th>
   <th>last updated</th>
   <th>recommend</th>
   <th>email alerts</th>
   <th>delete</th>
 </tr>
 </thead>
 <tbody>
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
  <tr favorite="<?php echo $favorite['id']; ?>">
<input type="hidden" name="favorite[]" value="<?php echo $favorite['id']; ?>" />
    <td width="200px;"><?php echo $favorite['title']; ?></td>
    <td><?php echo $date; ?></td>
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
 </tbody>
</table>
<div>
  <input type="submit" value="save changes" />
</div>
</form>

<?php require_once('../footer_base.php'); ?>