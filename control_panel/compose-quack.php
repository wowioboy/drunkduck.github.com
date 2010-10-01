<?php 
$username = 'pscomics';

$to = $_REQUEST['to'];
$subject = stripslashes($_REQUEST['subject']);
?>
<script type="text/javascript">
$(document).ready(function(){
  $('form#compose-quack').ajaxForm({
    success: function() {
      $.fancybox.close();
    }
  });
});
</script>
<form id="compose-quack" method="post" action="/ajax/control_panel/quack-send.php">
<input type="hidden" name="username" value="<?php echo $username; ?>" />
<table>
  <tr>
    <td>to:</td>
    <td><input type="text" name="to" value="<?php echo $to; ?>" /></td>
  </tr>
  <tr>
    <td style="vertical-align:top;">subject:</td>
    <td><input type="text" name="subject" value="<?php echo $subject; ?>" /></td>
  </tr>
  <tr>
    <td style="vertical-align:top;">message:</td>
    <td><textarea name="message" style="width:200px;"></textarea></td>
  </tr>
</table>
<input type="submit" value="send" />
</form>
