<?php require_once('../header_base.php'); ?>

<?php

if (!$USER) {
   header('Location: /login.php');
  die('please log in to use this page!');
}
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

<div class="box-2" style="clear:both">
    <div class="box-2 yellow rounded" >
<div class="drunk" style="font-size:3em;">Account</div>

<table>
  <tr>
    <td>
      <span class="drunk" style="font-size:2em;"><?php echo $USER->username; ?></span>
      <br />
      <span class="unread">member since <?php echo @$joined->format('F j, Y'); ?></span>
    </td>
    <td></td>
  </tr>
<form id="change-email" method="post" action="/ajax/control_panel/change_email.php">
<input type="hidden" name="id" value="<?php echo $USER->user_id; ?>" />
  <tr>
    <td style="vertical-align:top;">
    <div style="margin-bottom:10px;" class="unread">change email<div>
<input class="quack-input" type="text" name="email" style="width:200px;" value="<?php echo $email; ?>" /> 
    </td>
    <td style="vertical-align:top;">
<input type="submit" class="button" value="save email" />
    </td>
  </tr>
</form>
  <tr>
    <td style="vertical-align:top;">
<div style="margin-bottom:10px;" class="unread">avatar (maximum 100x100 pixels)</div> 
    <img src="http://drunkduck.com/gfx/avatars/avatar_<?php echo $USER->user_id; ?>.<?php echo $USER->avatar_ext; ?>" />
    </td>
    <td style="vertical-align:top;">
<form id="change-avatar" method="post" action="/ajax/control_panel/change_avatar.php">
<input type="hidden" name="id" value="<?php echo $USER->user_id; ?>" />
<input name="avatar" type="file" value="choose file" />
<br />
<br />
<input class="button" type="submit" value="upload" />
</form>
    </td>
  </tr>
  <tr>
  <form id="change-password" method="post" action="/ajax/control_panel/change_password.php">
    <input type="hidden" name="id" value="<?php echo $USER->user_id; ?>" />
    <td style="vertical-align:top;">
    <div style="margin-bottom:10px;" class="unread">change password<div>
      <div style="margin-bottom:5px;"><input class="quack-input" type="text" name="oldpass" value="old password" onclick="this.value=''" /></div>
      <div style="margin-bottom:5px;"><input class="quack-input" type="text" name="newpass" value="new password" onclick="this.value=''" /></div>
      <div style="margin-bottom:5px;"><input class="quack-input" type="text" name="confirmpass" value="comfirm password" onclick="this.value=''" /></div>
    </td>
    <td style="vertical-align:top;">
      <input type="submit" class="button" value="save password" />
    </td>
  </form>
  </tr>
</table>

</div></div>

<?php require_once('../footer_base.php'); ?>