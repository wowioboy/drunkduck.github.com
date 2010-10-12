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
    <div class="span-63 dark-green rounded header">
        <img src="/media/images/control-panel.png" />
    </div>
</div>

<div class="span-64 box-1 header-menu">
<a class="rounded button" href="/control_panel/quacks.php">quacks</a>
<a class="rounded button" href="/control_panel/favorites.php">favorites</a>
<a class="rounded button" href="/control_panel/profile.php">profile</a>
</div>

<div class="box-2" style="padding-top:160px">
    <div class="box-2 yellow rounded" >
<div class="drunk" style="font-size:3em;">Account</div>

<div>
    <img src="http://drunkduck.com/gfx/avatars/avatar_<?php echo $USER->user_id; ?>.<?php echo $USER->avatar_ext; ?>" />
    <div class="drunk">
        <div><?php echo $user['username']; ?></div>
        <span style="font-size:0.8em;">member since <?php echo @$joined->format('F j, Y'); ?></span>
    </div>
</div>

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
      <table>
        <tr>
          <td><input class="rounded" type="text" name="oldpass" value="old password" onclick="this.value=''" /></td>
          <td></td>
        </tr>
        <tr>
          <td><input class="rounded" type="text" name="newpass" value="new password" onclick="this.value=''" /></td>
          <td></td>
        </tr>
        <tr>
          <td><input class="rounded" type="text" name="confirmpass" value="comfirm password" onclick="this.value=''" /></td>
          <td><input type="submit" class="button" value="save password" /></td>
        </tr>
      </table>
  </form>
</div>

</div></div>

<?php require_once('../footer_base.php'); ?>