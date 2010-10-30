<?php 
$REQUIRE_LOGIN = false;
$ADMIN_ONLY    = false;
$TITLE         = 'Tutorials';
require_once('../header_base.php'); ?>

<?php
  $limit = 8;

$db = new DB();
$query = "select tutorial_id as id, title, username as author, description, from_unixtime(timestamp) as timestamp, round(vote_avg, 1) as rating
from tutorials
where finalized = '1' 
order by timestamp desc 
limit $limit";
$featured = $db->fetchAll($query);
$query = "select count(1)
from tutorials 
where finalized = '1'";
$featuredCount = $db->fetchOne($query);
$query = "select from_unixtime(max(timestamp)) as max, from_unixtime(min(timestamp)) as min 
          from tutorials 
          where timestamp != 0 and timestamp is not null";
$featuremaxmin = $db->fetchRow($query);
$min = new DateTime($featuremaxmin['min']);
$max = new DateTime($featuremaxmin['max']);
$dateArray = array($min->format('Y-m') => $min->format('M Y'));
do {
  $min->modify('+1 month');
  $dateArray[$min->format('Y-m')] = $min->format('M Y');
} while ($min->format('Y-m') != $max->format('Y-m'));
$dateArray = array_reverse($dateArray);
?>
<style>
.subtitle {
  color:#999;
}
</style>
<script type="text/javascript">
var pager = 0;
var pager_max = <?php echo $featuredCount; ?>;
var search = '';
var month = '';
var limit = <?php echo $limit; ?>;
  
$(document).ready(function(){
  
  function getFeatured() 
  {
    $.getJSON('/ajax/tutorials.php', {offset: pager, search: search, month: month, limit: limit}, function(data){
          var html = '';
          pager_max = data.count;
          if (data.featured) {
        $.each(data.featured, function(){
          html +=   '<div class="post green rounded box-1">' + 
                    '<div class="white rounded box-1" style="background-color:#FFF">' + 
                    '<a href="/tutorials/view.php?id=' + this.id + '"><span class="headline">' + this.title + '</span></a>' + 
                    '<br />' + 
                    '<span class="subtitle">' + this.timestamp + ' by <a href="/control_panel/profile.php?username=' + this.author + '"><b>' + this.author + '</b></a></span>' + 
                    '<br />' + 
                    '<p>' + this.description + '</p>' + 
                    '<div style="display:inline-block;float:right;position:relative;top:-10px;">' + 
                    'rating: ' + this.rating + ' out of 5.0' + 
                    '</div>' + 
                    '</div>' + 
                    '</div>' + 
                    '<div style="height:10px;"></div>'; 
        });
          }
        $('#featured_holder').html(html);
      });
  }
  
  $('.featured_search').keyup(function(){
    search = $(this).val();
    $('.featured_search').val(search);
    pager = 0;
    getFeatured();
  });
  
  $('.featured_button').click(function() {
    var valid = false;
    var dir = $(this).attr('direction');
    if (dir == 'next') {
      if (pager < pager_max - <?php echo $limit; ?>) {
        pager += <?php echo $limit; ?>;
        valid = true;
      }
    } else {
      if (pager > 0) {
        pager -= <?php echo $limit; ?>;  
        valid = true;
      }
    }
    if (valid) {
      getFeatured();
    }
  });
  
  $('.featureMonth').change(function(){
    month = $(this).val();
    $('.featureMonth').val(month);
    getFeatured();
  });
});
</script>
<style>
.featured_search {
  background-image:url('/media/images/blue-search-box.png');
  width:135px;
}
</style>
<div class="rounded canary span-63 box-1 pull-1">
  <div class="span-63 green rounded header">
    <img src="/media/images/tutorials.png" />
  </div>
  <div class="span-61 box-1 header-menu">
    <div style="float:left;">
      <button class="featured_button left button" direction="prev">previous</button>
      <select class="button featureMonth" style="border:none;">
        <option value="">Select Month</option>
        <?php foreach ($dateArray as $numDate => $dateString) : ?>
          <option value="<?php echo $numDate; ?>"><?php echo $dateString; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div style="float:right;">
      <input type="text"  style="color:#fff;" class="button featured_search" />
      <button class="featured_button right button" direction="next">next</button>
    </div>
    <div style="clear:both;"></div>
  </div>
</div>

<div style="height:20px;clear:both;"></div>
<div style="text-align:center;">
  <div class="green top-rounded header" style="display:inline-block;font-size:24px;padding:0 10px;">
    Featured!
  </div>
</div>
<div class="span-58 push-2 green rounded box-1" style="clear:both;">
  <div class="span-25 white rounded box-1">
      <a href="/tutorials/view.php?id=11"><img src="http://images.drunkduck.com/tutorials/content/1/1/11_47_thumb.jpg" border="0"></a>
        <br>
        <span class="drunk">Drawing the Ozone way!</span>
        <div class="preview">
          <span>
              July 16th 2007 by <a href="/control_panel/profile.php?username=ozoneocean">ozoneocean</a>
          </span>    
        </div>
  </div>
  <div class="span-25 white rounded box-1" style="float:right;">
        <a href="/tutorials/view.php?id=12"><img src="http://images.drunkduck.com/tutorials/content/1/2/12_52_thumb.jpg" border="0"></a>
        <br>
        <span class="drunk">Creating Rain Effects</span>
        <div class="preview">
          <span>
              July 17th 2007 by <a href="/control_panel/profile.php?username=silentkitty">silentkitty</a>
          </span>    
        </div>
  </div>
</div>
<div style="text-align:center;">
<a href="/tutorials/create.php" class="button">create tutorial</a>
</div>
<div id="featured_holder" class="span-60 box-2">
  <?php foreach ($featured as $comic) : ?>
  <?php $date = new DateTime($comic['timestamp']); ?>
    <div class="post green rounded box-1">
      <div class="white rounded box-1" style="background-color:#FFF">
        <a href="/tutorials/view.php?id=<?php echo $comic['id']; ?>"><span class="headline"><?php echo $comic['title']; ?></span></a> 
        <br />
        <span class="subtitle"><?php echo $date->format('F j Y');?> by <a href="/control_panel/profile.php?username=<?php echo $comic['author']; ?>"><b><?php echo $comic['author']; ?></b></a></span>
        <br />
        <p><?php echo $comic['description']; ?></p>
        <div style="float:right;position:relative;top:-10px;">
        Rating: <?php echo $comic['rating']; ?> out of 5.0
        </div>
      </div>
    </div>
  <div style="height:10px;"></div>
  <?php endforeach; ?>
</div>
<div class="span-60 push-2">
  <div style="float:left;">
  <button class="featured_button left button" direction="prev">previous</button>
  </div>
  <div style="float:right;">
  <button class="featured_button right button" direction="next">next</button>
  </div>
  <div style="clear:both;"></div>
</div>

<?php require_once('../footer_base.php'); ?>