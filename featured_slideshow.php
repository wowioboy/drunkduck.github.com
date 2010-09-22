<div class="span-96" style="height:10px"> .</div>
<?php
require_once('includes/db.class.php');
$db = new DB();
$query = "select c.comic_name as title 
from comics c 
inner join featured_comics f 
on f.comic_id = c.comic_id 
where f.approved = '1'
order by feature_id desc 
limit 24";
$featured = $db->fetchCol($query);
?>
<script type="text/javascript">
$(document).ready(function(){
    $('#slideshow').cycle({ 
        fx:      'scrollHorz', 
        timeout: 0 
    });
    $('#next_button').click(function(){
        $('#slideshow').cycle('next');
    });
    $('#prev_button').click(function(){
      $('#slideshow').cycle('prev');
    });
    
});
</script>
<style type="text/css">
#slideshow img {
  float:right;
  clear:none;
  padding: 0 2px 0 2px;
  z-index:-5000;
}
</style>
<div class="span-96">
    <div style="float:left;">
      <button id="prev_button">&laquo;</button>
    </div>
    <div id="slideshow" style="float:left;width:905px;">
      <?php foreach ($featured as $i => $comic) : ?>
        <?php 
          $i++;
          $path = 'http://www.drunkduck.com/comics/' . $comic{0} . '/' . str_replace(' ', '_', $comic) . '/gfx/thumb.jpg';
        ?>
        <?php if ($i % 8 == 1) : ?>
          <div>
        <?php endif; ?>  
        <a href="http://www.drunkduck.com/<?php echo str_replace(' ', '_', $comic); ?>"><img src="<?php echo $path; ?>" title="<?php echo $comic; ?>" width="105" /></a>
        <?php if ($i % 8 == 0) : ?>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <div style="float:left;">
      <button id="next_button">&raquo;</button>
    </div>
</div>    