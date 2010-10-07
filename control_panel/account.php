<?php require_once('../header_base.php'); ?>

<?php
$query = "select email 
          from demographics 
          where user_id = '{$USER->user_id}'";
$email = $db->fetchOne($query);
try {
$joined = new DateTime($USER->signed_up);
} catch (exception $e) {
  $joined = new DateTime();
}
?>
<script type="text/javascript">
$(document).ready(function(){
  $('#change-email').ajaxForm();
  $('#change-avatar').ajaxForm();
  $('#change-password').ajaxForm({
    beforeSubmit: function(data) {
      if (data[2].value == data[3].value) {
        return true;
      } else {
        alert('your passwords do not match. please make sure they are the same.');
      }
      return false;
    },
    success: function(response) {
      console.log(response);
    }
  });
});
</script>
 <div class="rounded canary span-63 box-1 pull-1" style="clear:both;">
            <div class="span-63 green rounded header">
            User Control Panel
            </div>
        </div>
<div class="span-64 box-1 header-menu">
<a class="teal rounded button" href="/control_panel/quacks.php">quacks</a>
<a class="teal rounded button" href="/control_panel/favorites.php">favorites</a>
<a class="teal rounded button" href="/control_panel/profile.php">profile</a>
</div>
<div class="drunk" style="font-size:3em;">Account</div>
<img src="http://drunkduck.com/gfx/avatars/avatar_<?php echo $USER->user_id; ?>.<?php echo $USER->avatar_ext; ?>" />
<h2><?php echo $USER->username; ?></h2>
<h4>member since <?php echo $joined->format('F j, Y'); ?></h4>

<div>
email
<br />
<form id="change-email" method="post" action="/ajax/control_panel/change_email.php">
<input type="hidden" name="id" value="<?php echo $USER->user_id; ?>" />
<input type="text" name="email" value="<?php echo $email; ?>" /> <input type="submit" class="teal button rounded" value="save email" />
</form>
</div>

<div style="height:10px;"></div>

<div>
<form id="change-avatar" method="post" action="/ajax/control_panel/change_avatar.php">
avatar (maximum 100x100 pixels) <input name="avatar" type="file" value="choose file" />
<input type="hidden" name="id" value="<?php echo $USER->user_id; ?>" />
<input type="submit" value="upload" />
</form>
</div>

<div style="height:10px;"></div>

<div>
<form id="change-password" method="post" action="/ajax/control_panel/change_password.php">
<input type="hidden" name="id" value="<?php echo $USER->user_id; ?>" />
old password <input type="text" name="oldpass" />
<br />
new password <input type="text" name="newpass" />
<br />
confirm password <Input type="text" name="confirmpass" />
<br />
<input type="submit" class="teal button rounded" value="save password" />
</form>
</div>

<?php require_once('../footer_base.php'); ?>