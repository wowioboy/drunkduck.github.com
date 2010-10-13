<?php require_once('header_base.php'); ?>


<div class="rounded canary span-63 box-1 pull-1">
  <div class="span-63 green rounded header">
           <img src="/media/images/feedback.png" />
  </div>
</div>
<div class="box-2" style="padding-top:120px">
  <div class="box-2 yellow rounded" >
<?php if ($message = $_POST['message']) : ?>
  <?php
  $message = 'Username: ' . $USER->username . '<br /><br />' . $message;
  mail('knguyen@wowio.com', 'Drunk Duck Beta Feedback', $message);
  ?>
  Thank you for submitting your feedback!
<?php else : ?>
    <div>
      Please submit your feedback!
    </div>
  <form method="post">
    <div class="table fill">
      <div class="cell right" style="width:100%;">
        <textarea style="width:100%;" name="message"></textarea>
        <br />
        <input type="submit" class="rounded teal button" value="submit" />
      </div>
    </div>
  </form>
<?php endif; ?>  
  </div>
</div>

<?php require_once('footer_base.php'); ?>
