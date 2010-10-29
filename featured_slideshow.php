<div class="span-96" style="height:10px"></div>
<?php
require_once('includes/db.class.php');
$db = new DB();
$query = "select f.feature_id as id, c.comic_name as title, f.description, u.username as author, c.rating_symbol as rating, c.total_pages as pages 
from comics c 
inner join featured_comics f 
on f.comic_id = c.comic_id 
inner join users u 
on u.user_id = c.user_id
where f.approved = '1'
order by ymd_date_live desc 
limit 24";
$featured = $db->fetchAll($query);
foreach ($featured as &$feature) {
  
}
?>
<script type="text/javascript">
$(document).ready(function(){
  var previousAvailable = false;
  var nextAvailable = true;
    $('#slideshow').cycle({ 
        fx: 'scrollHorz', 
        timeout: 0 ,
        prevNextClick: function(next, index) {
           if (next) {
             if (index > 0) {
               previousAvailable = true;
               if (index == 2) {
                 nextAvailable = false;
               }
             } 
           } else {
             if (index < 2) {
               nextAvailable = true;
               if (index == 0) {
                 previousAvailable = false;
               } 
             }
           }
           if (previousAvailable) {
             $('#feature_prev_button').attr('src', '/media/images/featured-lt-arrow.png');
           } else {
             $('#feature_prev_button').attr('src', '/media/images/featured-lt-arow-faded.png');
           }
        }
    });
    $('#feature_next_button').click(function(){
      if (nextAvailable) {
        $('#slideshow').cycle('next');
      } else {
        window.location = '/featured.php';
      }
    });
    $('#feature_prev_button').click(function(){
      if (previousAvailable) {
        $('#slideshow').cycle('prev');
      } 
    });
    $('.feature-image').live('mouseenter', function(){
      var title = $(this).attr('comic_title');
      var description = $(this).attr('description');
      var author = $(this).attr('author'); 
      var rating = $(this).attr('rating');
      var pages = $(this).attr('pages');
      var html = '<div class="preview box-1 rounded" style="border:10px rgb(174,230,1) solid;background-color:#FFF;position:relative;">' + 
                 '<div style="display:inline;float:left;"><a href="/' + title.replace(/ /g, '_') + '/"><h2>' + title + '</h2></a>' + 
                 '&nbsp;<span>by&nbsp;<a style="color:#999;" href="/control_panel/profile.php?username=' + author + '">' + author + '</a></span></div>' +
                 '<div style="display:inline;float:right;">' + rating + ', ' + pages + ' pages</div>' +   
                 '<div style="clear:both;">' + description + '</div>' + 
                 '</div>';
      var position = $(this).position();
      var left = position.left + 75;
      left += 'px';
      $('#feature-description').html(html).slideDown();
      $('#feature-point').stop().show();
      $('#feature-point').animate({left: left});
    });
    $('#feature_holder').mouseleave(function(){
      $('#feature-description').slideUp();
      $('#feature-point').hide();
    });
});
</script>
<style type="text/css">
#slideshow img {
  float:right;
  clear:none;
  padding: 0 2px 0 3px;
  z-index:-5000;
}
#feature-description {
  position:absolute;
  z-index:6;
  top:130px;
  display:none;
}
#feature-point {
  position:absolute;
  z-index:7;
  top:126px;
  left:80px;
  display:none;
}
</style>
<div class="span-96">
    <div class="span-2 box-1" style="text-align:right;">
      <input type="image" src="/media/images/featured-lt-arow-faded.png" id="feature_prev_button" />
    </div>
    <div id="feature_holder" style="position:relative;">
    <div id="slideshow" class="span-88" style="height:131px;">
      <?php foreach ($featured as $i => $comic) : ?>
        <?php 
          $i++;
          $path = "http://images.drunkduck.com/featured_comic_gfx/{$comic['id']}.gif";
        ?>
        <?php if ($i % 8 == 1) : ?>
          <div <?php echo ($i > 1) ? 'style="display:none;"' : ''; ?>>
        <?php endif; ?>  
        <a href="/<?php echo str_replace(' ', '_', $comic['title']); ?>/" style="float:left;"><img class="feature-image" comic_title="<?php echo $comic['title']; ?>" author="<?php echo $comic['author']; ?>" description="<?php echo htmlspecialchars($comic['description']); ?>" pages="<?php echo $comic['pages']; ?>" rating="<?php echo $comic['rating']; ?>" src="<?php echo $path; ?>" width="105" height="131" /></a>
        <?php if ($i % 8 == 0) : ?>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <img id="feature-point" src="/media/images/tooltip-point.png" />
    <div id="feature-description"></div>
    </div>
    <div class="span-2 box-1">
        <input type="image" src="/media/images/featured-rt-arrow.png" id="feature_next_button" />
    </div>
    <div style="z-index:4; background: url('/media/images/featured-text.png') bottom center no-repeat; width:400px; position:absolute; top: 130px; left:280px; height:30px;"></div>

</div>    