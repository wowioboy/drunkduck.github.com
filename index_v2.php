<?php require_once('header_v2.php'); ?>

<?php
$db = new DB();
$query = "select c.comic_name as title, c.description, c.rating_symbol as rating, c.total_pages as pages, u.username as author 
from comics c 
inner join users u 
on u.user_id = c.user_id
order by visits desc 
limit 10";
$topTen = $db->fetchAll($query);
$query = "select c.comic_name as title, c.description, c.rating_symbol as rating, c.total_pages as pages, u.username as author 
from comics c 
inner join users u 
on u.user_id = c.user_id
order by last_update desc 
limit 10";
$latestUpdates = $db->fetchAll($query);
$query = "select c.comic_name as title, c.description, c.rating_symbol as rating, c.total_pages as pages, u.username as author 
from comics c 
inner join users u 
on u.user_id = c.user_id
left join comic_pages p 
on p.comic_id = c.comic_id 
right join page_likes l 
on l.page_id = p.page_id 
where l.date between now() - interval 1 week and now()
group by title 
order by count(1) desc
limit 10";
$mostLiked = $db->fetchAll($query);
$query = "select b.title, u.username as author, b.body, from_unixtime(b.timestamp_date) as created_on
from admin_blog b 
left join users u 
on u.user_id = b.user_id 
order by created_on desc 
limit 2";
$news = $db->fetchAll($query);
?>
<style>
.post {
	padding:10px;
}
</style>
<script>
$(document).ready(function(){
  $('.top-ten-image').live('mouseenter', function(){
  	var title = $(this).attr('title');
		var description = $(this).attr('description');
		var author = $(this).attr('author'); 
		var html = title + ' by ' + author + '<br />' + description;
		$('#top-ten-description').html(html).slideDown();
	});
	$('#top-ten-holder').mouseleave(function(){
		$('#top-ten-description').slideUp();
	});
	$('.most-liked-image').live('mouseenter', function(){
    var title = $(this).attr('title');
    var description = $(this).attr('description');
    var author = $(this).attr('author'); 
    var html = title + ' by ' + author + '<br />' + description;
    $('#most-liked-description').html(html).slideDown();
  });
  $('#most-liked-holder').mouseleave(function(){
    $('#most-liked-description').slideUp();
  });
  $('.latest-update-image').live('mouseenter', function(){
    var title = $(this).attr('title');
    var description = $(this).attr('description');
    var author = $(this).attr('author'); 
    var html = title + ' by ' + author + '<br />' + description;
    $('#latest-update-description').html(html).slideDown();
  });
  $('#latest-update-holder').mouseleave(function(){
    $('#latest-update-description').slideUp();
  });
	
});
</script>
<div class="span-16">
  <div class="span-4 green panel-header">&raquo; Top Ten</div>
</div>
<div class="span-16">
  <div class="span-16 green panel-body">
    <div id="top-ten-holder">
      <?php foreach ((array) $topTen as $comic) : ?>
        <?php 
	        $path = 'http://www.drunkduck.com/comics/' . $comic['title']{0} . '/' . str_replace(' ', '_', $comic['title']) . '/gfx/thumb.jpg';
        ?>
        <img class="top-ten-image" src="<?php echo $path; ?>" width="54" title="<?php echo $comic['title']; ?>" description="<?php echo $comic['description']; ?>" author="<?php echo $comic['author']; ?>" />
      <?php endforeach; ?>
    </div>
    <div id="top-ten-description" class="span-16 rounded pad-5" style="background-color:#fff;display:none;position:absolute;z-index:1000;">asdfasdkfjasodfj</div>   
  </div>
</div>
<hr class="space" />
<div class="span-16">
  <div class="span-4 green panel-header">&raquo; Most Liked of The Week</div>
</div>
<div class="span-16">
  <div class="span-16 green panel-body">
    <div id="most-liked-holder">
      <?php foreach ((array) $mostLiked as $comic) : ?>
        <?php 
          $path = 'http://www.drunkduck.com/comics/' . $comic['title']{0} . '/' . str_replace(' ', '_', $comic['title']) . '/gfx/thumb.jpg';
        ?>
        <img class="most-liked-image" src="<?php echo $path; ?>" width="54" title="<?php echo $comic['title']; ?>" description="<?php echo $comic['description']; ?>" author="<?php echo $comic['author']; ?>" />
      <?php endforeach; ?>
    </div>
    <div id="most-liked-description" class="span-16 rounded pad-5" style="background-color:#fff;display:none;position:absolute;z-index:1000;">asdfasdkfjasodfj</div>
  </div>
</div>
<hr class="space" />
<div class="span-16">
  <div class="span-4 green panel-header">&raquo; Latest Updates</div>
</div>
<div class="span-16">
  <div class="span-16 green panel-body">
    <div id="latest-update-holder">
      <?php foreach ((array) $latestUpdates as $comic) : ?>
        <?php 
          $path = 'http://www.drunkduck.com/comics/' . $comic['title']{0} . '/' . str_replace(' ', '_', $comic['title']) . '/gfx/thumb.jpg';
        ?>
        <img class="latest-update-image" src="<?php echo $path; ?>" width="54" title="<?php echo $comic['title']; ?>" description="<?php echo $comic['description']; ?>" author="<?php echo $comic['author']; ?>" />
      <?php endforeach; ?>
    </div>
    <div id="latest-update-description" class="span-16 rounded pad-5" style="background-color:#fff;display:none;position:absolute;z-index:1000;">asdfasdkfjasodfj</div>
  </div>
</div>
<hr class="space" />
<div class="span-16">
  <div class="span-4">
    <div class="center">
       spotlight
    </div>
    <div class="rounded" style="height:400px;border:2px solid rgb(174,230,1);">
    </div>
  </div>
  <div class="span-12 last">
    <?php foreach ($news as $entry) : ?>
    <div class="post yellow">
      <span class="headline"><?php echo $entry['title']; ?></span>
      <br />
      <span class="subtitle">posted by <?php echo $entry['author']; ?></span>
      <p><?php echo $entry['body']; ?></p>
    </div>
    <hr class="space" />
    <?php endforeach; ?>
  </div>
</div>

<?php require_once('footer_v2.php'); ?>