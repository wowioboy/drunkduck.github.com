<?php
$REQUIRE_LOGIN = false;
$ADMIN_ONLY    = false;
$TITLE         = 'Tutorials';

require_once('../header_base.php');
?>

<div class="rounded canary span-63 box-1 pull-1">
    <div class="span-63 green rounded header">
    Tutorials
    </div>
</div>

<div class="span-64 box-1 header-menu">
  <button class="news_button rounded left button" direction="prev">previous</button>
  <select id="newsMonth" class="button rounded">
    <option value="">select month</option>
    <?php foreach ($dateArray as $numDate => $dateString) : ?>
      <option value="<?php echo $numDate; ?>"><?php echo $dateString; ?></option>
    <?php endforeach; ?>
  </select>
<!--  <button class="rounded button dropdown">September 2010</button> -->
  <!-- <button class="rounded button">article view</button> -->
    <input class="rounded button" style="color:#FFF" id="news_search" />
  <button class="news_button rounded right button" direction="next">next</button>
</div>

<div class="span-60 box-2">
    <div class="span-58 green rounded box-1">
        <div class="span-25 white rounded box-1">
            <a href="/tutorials/view.php?id=11"><img src="http://images.drunkduck.com/tutorials/content/1/1/11_47_thumb.jpg" border="0"></a>
              <br>
              <span class="drunk">Drawing the Ozone way!</span>
              <div class="preview">
                <span>
                    July 16th 2007 by <a href="http://user.drunkduck.com/ozoneocean">ozoneocean</a>
                </span>    
              </div>
        </div>
        
        <div class="span-25 white push-4 rounded box-1">
              <a href="/tutorials/view.php?id=12"><img src="http://images.drunkduck.com/tutorials/content/1/2/12_52_thumb.jpg" border="0"></a>
              <br>
              <span class="drunk">Creating Rain Effects</span>
              <div class="preview">
                <span>
                    July 17th 2007 by <a href="http://user.drunkduck.com/silentkitty">silentkitty</a>
                </span>    
              </div>
        </div>
    </div>
    
    <div class="green box-1">
    </div>
</div>

<?php
require_once('../footer_base.php');
exit;

//$HEADER_IMG    = 'header_main_games.gif';
$CONTENT_FILE  = 'tutorials/index.inc.php';
include_once('../template.inc.php');



?>