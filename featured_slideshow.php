<div class="span-96" style="height:10px"></div>
<?php
require_once('includes/db.class.php');
$db = new DB();
$query = "select f.feature_id as id, c.comic_name as title, f.description 
from comics c 
inner join featured_comics f 
on f.comic_id = c.comic_id 
where f.approved = '1'
order by ymd_date_live desc 
limit 24";
$featured = $db->fetchAll($query);
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
        window.location = '/featured_v2.php';
      }
    });
    $('#feature_prev_button').click(function(){
      if (previousAvailable) {
        $('#slideshow').cycle('prev');
      } 
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
</style>
<div class="span-96">
    <div class="span-2 box-1">
      <input type="image" src="/media/images/featured-lt-arow-faded.png" id="feature_prev_button" />
    </div>
    <div id="slideshow" class="span-88" style="height:131px;">
      <?php foreach ($featured as $i => $comic) : ?>
        <?php 
          $i++;
          $path = "http://images.drunkduck.com/featured_comic_gfx/{$comic['id']}.gif";
        ?>
        <?php if ($i % 8 == 1) : ?>
          <div <?php echo ($i > 1) ? 'style="display:none;"' : ''; ?>>
        <?php endif; ?>  
        <a href="/<?php echo str_replace(' ', '_', $comic['title']); ?>/" style="float:left;"><img title="<?php echo '<span class=\'drunk\'>' . htmlspecialchars($comic['title']) . '</span><br /><span class=\'teal-words\'>' . htmlspecialchars($comic['description']) . '</span>'; ?>" src="<?php echo $path; ?>" width="105" height="131" /></a>
        <?php if ($i % 8 == 0) : ?>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <div class="span-2 box-1">
        <input type="image" src="/media/images/featured-rt-arrow.png" id="feature_next_button" />
    </div>
    <div style="z-index:4; background: url('/media/images/featured-text.png') bottom center no-repeat; width:400px; position:absolute; top: 130px; left:280px; height:30px;"></div>

</div>    