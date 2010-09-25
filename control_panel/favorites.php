<?php require_once('../header_base.php'); ?>

<?php
$USER = new stdClass();
$USER->user_id = '19085';
$USER->username = 'pscomics';
$USER->signed_up = '2009-12-31';

$query = "select c.comic_id as id, c.comic_name as title, from_unixtime(c.last_update) as updated_on 
          from comics c 
          left join comic_favs f 
          on f.comic_id = c.comic_id
          where f.user_id = '{$USER->user_id}'";
$favorites = DB::getInstance()->fetchAll($query);
?>
<script type="text/javascript">
$(document).ready(function(){
  $('.big-favorite-reccomend').change(function(){
    if ($(this).attr('checked')) {
      $('.favorite-reccomend').attr('checked', true);
    }
  });
  $('.big-favorite-alerts').change(function(){
    if ($(this).attr('checked')) {
      $('.favorite-alerts').attr('checked', true);
    }
  });
  $('.big-favorite-delete').change(function(){
    if ($(this).attr('checked')) {
      $('.favorite-delete').attr('checked', true);
    }
  });
  
  $('.favorite-reccomend').change(function(){
    if (!$(this).attr('checked')) {
      $('.big-favorite-reccomend').attr('checked', false);
    }
  });
  $('.favorite-alerts').change(function(){
    if (!$(this).attr('checked')) {
      $('.big-favorite-alerts').attr('checked', false);
    }
  });
  $('.favorite-delete').change(function(){
    if (!$(this).attr('checked')) {
      $('.big-favorite-delete').attr('checked', false);
    }
  });
  
  
});
</script>
<table>
 <thead>
 <tr>
   <th>comic</th>
   <th>last updated</th>
   <th>reccomend</th>
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
  <tr comic="<?php echo $favorite['id']; ?>">
    <td><?php echo $favorite['title']; ?></td>
    <td><?php echo $date; ?></td>
    <td><input class="favorite-reccomend" type="checkbox" name="reccomend[<?php echo $favorite['id']; ?>]" /></td>
    <td><input class="favorite-alerts" type="checkbox" name="alerts[<?php echo $favorite['id']; ?>]" /></td>
    <td><input class="favorite-delete" type="checkbox" name="delete[<?php echo $favorite['id']; ?>]" /></td>
  </tr>
<?php endforeach; ?>
<tr>
  <td>All</td>
  <td>&nbsp;</td>
  <td><input class="big-favorite-reccomend" type="checkbox" /></td>
  <td><input class="big-favorite-alerts" type="checkbox" /></td>
  <td><input class="big-favorite-delete" type="checkbox" /></td>
</tr>
 </tbody>
</table>
<div>
<button>save changes</button>
</div>

<?php require_once('../footer_base.php'); ?>