<?php 
require_once('header_base.php'); 
require_once('bbcode.php'); 
?>


<?php
$limit = 25;

$db = new DB();
$query = "select c.comic_id as id, c.comic_name as title, u.username as author, c.rating_symbol as rating, c.total_pages as pages, f.description, count(l.page_id) as likes, concat(substring(ymd_date_live, 1, 4), '-', substring(ymd_date_live, 5, 2), '-', substring(ymd_date_live, 7, 2)) as date
from comics c 
inner join featured_comics f 
on f.comic_id = c.comic_id 
inner join users u 
on u.user_id = c.user_id 
left join comic_pages p 
on c.comic_id = p.comic_id 
left join page_likes l 
on p.page_id = l.page_id
where f.approved = '1' 
group by c.comic_name
order by f.ymd_date_live desc 
limit $limit";
$featured = $db->fetchAll($query);
$query = "select count(1)
from comics c 
inner join featured_comics f 
on f.comic_id = c.comic_id 
where f.approved = '1'";
$featuredCount = $db->fetchOne($query);
$query = "select concat(substring(max(ymd_date_live), 1, 4), '-', substring(max(ymd_date_live), 5, 2)) as max, concat(substring(min(ymd_date_live), 1, 4), '-', substring(min(ymd_date_live), 5, 2)) as min 
          from featured_comics 
          where ymd_date_live != 0 and ymd_date_live is not null";
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
<style type="text/css">
.grid-panel {
  float:left;
  border:1px solid #45b4b9;
  padding:10px;
  background-color:#45b4b9;
  margin:0 10px 10px 0;
  width:90px;
}
.featured_search {
  background-image:url('/media/images/blue-search-box.png');
  width:135px;
}
.panel-date {
  text-align:center;
  color:#fff;
}
#search-description-holder {
  display:none;
  border:10px rgb(174,230,1) solid;
  padding:10px;
  background-color:#fff;
  position:absolute;
  z-index:6;
}
#feature-point {
  position:absolute;
  top:-14px;"
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
    $.getJSON('/ajax/featured.php', {offset: pager, search: search, month: month, limit: limit}, function(data){
          var html = '';
          pager_max = data.count;
          if (data.featured) {
        $.each(data.featured, function(){
          html += '<div class="rounded grid-panel">' + 
                    '<div style="text-align:center;">' + 
                    '<a href="/' + this.title.replace(/ /g, '_') + '/">' + 
                    '<img class="search-image" src="http://images.drunkduck.com/process/comic_' + this.id + '_0_T_0_sm.jpg" width="80" height="100" comic_title="' + this.title + '" author="' + this.author + '" description="' + this.description + '" rating="' + this.rating + '" pages="' + this.pages + '" />' + 
                    '</a>' +
                    '</div>' + 
                    '<div class="panel-date">' + this.date + '</div>' + 
                    '</div>';
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
  
  $('.featured_search').live('click', function(){
    $('.featured_search').val('');
    $('.featured_search').die('click');
  });
  
  $('.search-image').live('mouseenter', function(){
    var title = $(this).attr('comic_title');
    var description = $(this).attr('description');
    var author = $(this).attr('author'); 
    var rating = $(this).attr('rating');
    var pages = $(this).attr('pages');
    var html = '<div style="float:left;">' + 
               '<a class="drunk" href="/' + title.replace(/ /g, '_') + '/">' + title + '</a> by <a style="color:#999;" href="/control_panel/profile.php?username=' + author + '">' + author + '</a>' + 
               '</div>' +
               '<div style="float:right;">' + rating + ', ' + pages + ' pages</div>' +   
               '<div style="clear:both;">' + description + '</div>';
    var position = $(this).position();
    var left = (position.left + 10) + 'px';
    var top = (position.top + 100) + 'px';
    $('#search-description').html(html);
    $('#search-description-holder').show().stop().animate({top: top});
    $('#feature-point').stop().animate({left: left});
  });
 $('#featured_parent').mouseleave(function(){
 console.log('lfet');
   $('#search-description-holder').hide();
 });
});
</script>
<div class="rounded canary span-63 box-1 pull-1">
  <div class="span-63 dark-green rounded header">
    <img src="/media/images/featured.png" />
  </div>
  <div class="span-61 box-1 header-menu">
    <div style="float:left;">
    <button class="featured_button rounded left button" direction="prev">previous</button>
    <select class="button rounded featureMonth" style="border:none;">
      <option value="">Select Month</option>
      <?php foreach ($dateArray as $numDate => $dateString) : ?>
        <option value="<?php echo $numDate; ?>"><?php echo $dateString; ?></option>
      <?php endforeach; ?>
    </select>
    </div>
    <div style="float:right;">
    <input type="text" style="color:#fff;" class="rounded button featured_search" value="search featured" onfocus="if(this.value=='search_featured'){this.value='';}" />
    <button class="featured_button rounded right button" direction="next">next</button>
    </div>
  </div>
</div>
<div style="clear:both;height:20px;"></div>
<div id="featured_parent" class="span-62" style="position:relative;padding-left:20px;">
  <div id="featured_holder">
  <?php foreach ($featured as $comic) : ?>
  <?php 
  $date = new DateTime($comic['date']);
  ?>
    <div class="rounded grid-panel">
      <div style="text-align:center;">
      <?php
      $path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
      ?>
    <a href="/<?php echo str_replace(' ', '_', $comic['title']); ?>/">
        <img class="search-image" src="<?php echo $path; ?>" width="80" height="100" comic_title="<?php echo $comic['title']; ?>" description="<?php echo htmlspecialchars(bbcode2html($comic['description'])); ?>" author="<?php echo $comic['author']; ?>" rating="<?php echo $comic['rating']; ?>" pages="<?php echo $comic['pages']; ?>" />
    </a>
      </div>
      <div class="panel-date">
      <?php echo $date->format('M j Y'); ?>
      </div>
    </div>
  <?php endforeach; ?>
  </div>
<div id="search-description-holder" class="rounded pull-2 span-60">
  <img id="feature-point" src="/media/images/tooltip-point.png" />
  <div id="search-description">
  </div>
</div>
</div>
<div class="box-2">
  <div style="float:left;">
    <button class="featured_button rounded left button" direction="prev">previous</button>
  </div>
    <div style="float:right;">
    <button class="featured_button rounded right button" direction="next">next</button>
    </div>
    <div style="clear:both;"></div>
</div>

<?php require_once('footer_base.php'); ?>
