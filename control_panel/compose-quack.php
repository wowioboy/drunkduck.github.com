<?php require_once('../header_base.php'); ?>

<?php 

if (!$USER) {
   header('Location: /login.php');
  die('please log in to use this page!');
}
$username = $USER->username;

if ($message = $_POST['message']) {
  $to = $_POST['to'];
  $subject = stripslashes($_POST['subject']);
  $message = stripslashes($message);
  $time = time();
  $query = "insert into mailbox 
            (username_to, username_from, title, message, time_sent, viewed) 
            values 
            ('$to', '$username', '$subject', '$message', '$time', '0')";
  DB::getInstance()->query($query);
  $sent = true;
} else {
  $to = $_GET['to'];
  $subject = stripslashes($_GET['subject']);
}

?>
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


<div>
<a class="yellow-button teal-words" href="/control_panel/quacks.php">inbox</a>
<a class="yellow-button" href="javascript:">compose new quack</a>
<a class="yellow-button teal-words" href="/control_panel/quacks-outbox.php">sent</a>
</div>
<?php if ($sent) : ?>
<script type="text/javascript">
$(document).ready(function(){
 $('#quack-form').clearForm();
});  
</script>
Your quack has been sent!
<?php endif; ?>
<div style="position:relative;left:-8px;top:10px;clear:both;">
<form id="quack-form" method="post" style="display:block;">
<table width="100%">
  <tr>
    <td><input class="rounded quack-input" style="width:100%;" type="text" name="to" value="<?php echo ($to) ? $to : 'to'; ?>" onfocus="if(this.value=='to'){this.value=''}" onblur="if(this.value==''){this.value='to'}" /></td>
  </tr>
  <tr>
    <td><input class="rounded quack-input" style="width:100%;" type="text" name="subject" value="<?php echo ($subject) ? $subject : 'subject'; ?>" onfocus="if(this.value=='subject'){this.value=''}" onblur="if(this.value==''){this.value='subject'}" /></td>
  </tr>
  <tr>
    <td><textarea class="rounded quack-input" name="message" style="width:100%;" onfocus="if(this.value=='message'){this.value=''}" onblur="if(this.value==''){this.value='message'}">message</textarea></td>
  </tr>
  <tr>
    <td style="text-align:right;">
      <input style="position:relative;left:20px;top:-10px;" class="button" type="submit" value="send" />
    </td>
  </tr>
</table>
</form>
</div>

</div>
</div>

<?php require_once('../footer_base.php'); ?>