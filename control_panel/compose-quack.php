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


<div class="rounded canary span-63 box-1 pull-1" style="clear:both;">
     <div class="span-63 dark-green rounded header">
    <img src="/media/images/control-panel.png" />
    </div>
</div>

<div class="span-55 box-1 header-menu">
<a class="button" href="/control_panel/account.php">account</a>
<a class="button" href="/control_panel/profile.php">profile</a>
<a class="button" href="/control_panel/favorites.php">favorites</a>
</div>
<div class="box-2" style="padding-top:120px">
    <div class="box-2 yellow rounded" >
        <div class="drunk" style="font-size:3em;">Personal Quacks</div>


<div>
<a class="button" href="/control_panel/quacks.php">Inbox</a>
<a class="button" href="javascript:">Compose</a>
<a class="button" href="/control_panel/quacks-outbox.php">Outbox</a>
</div>
<?php if ($sent) : ?>
<script type="text/javascript">
$(document).ready(function(){
 $('#quack-form').clearForm();
});  
</script>
Your quack has been sent!
<?php endif; ?>
<form id="quack-form" method="post">
<table width="100%">
  <tr>
    <td style="text-align:right;width:50px;">to:</td>
    <td><input style="width:100%;" type="text" name="to" value="<?php echo $to; ?>" /></td>
  </tr>
  <tr>
    <td style="text-align:right;vertical-align:top;">subject:</td>
    <td><input style="width:100%;" type="text" name="subject" value="<?php echo $subject; ?>" /></td>
  </tr>
  <tr>
    <td style="vertical-align:top;text-align:right;">message:</td>
    <td><textarea name="message" style="width:100%;"></textarea></td>
  </tr>
  <tr>
    <td></td>
    <td style="text-align:right;">
      <input class="button" type="submit" value="send" />
    </td>
  </tr>
</table>
</form>

</div>
</div>

<?php require_once('../footer_base.php'); ?>