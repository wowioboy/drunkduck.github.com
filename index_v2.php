<?php 
$showFeatured = true;
require_once('header_base.php');
require_once('bbcode.php'); 

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
<script>
$(document).ready(function(){
  $('.top-ten-image').live('mouseenter', function(){
  	var title = $(this).attr('title');
		var description = $(this).attr('description');
		var author = $(this).attr('author'); 
		var html = '<div class="preview"><h2>' + title + '</h2> <span>by ' + author + '</span><br />' + description + '</div>';
		$('#top-ten-description').html(html).slideDown();
	});
	$('#top-ten-holder').mouseleave(function(){
		$('#top-ten-description').slideUp();
	});
	$('.most-liked-image').live('mouseenter', function(){
    var title = $(this).attr('title');
    var description = $(this).attr('description');
    var author = $(this).attr('author'); 
    var html = '<div class="preview"><h2>' + title + '</h2> <span>by ' + author + '</span><br />' + description + '</div>';
    $('#most-liked-description').html(html).slideDown();
  });
  $('#most-liked-holder').mouseleave(function(){
    $('#most-liked-description').slideUp();
  });
  $('.latest-update-image').live('mouseenter', function(){
    var title = $(this).attr('title');
    var description = $(this).attr('description');
    var author = $(this).attr('author'); 
    var html = '<div class="preview"><h2>' + title + '</h2> <span>by ' + author + '</span><br />' + description + '</div>';
    $('#latest-update-description').html(html).slideDown();
  });
  $('#latest-update-holder').mouseleave(function(){
    $('#latest-update-description').slideUp();
  });
	
});
</script>
<div class="span-62 box-1 pull-1 canary rounded">

  <div class="span-61">
    <div class="span-16 green panel-header"><span>Top Ten</span></div>
  </div>
  <div class="span-61">
    <div class="span-61 green panel-body box-1">
      <div id="top-ten-holder" class="">
        <?php foreach ((array) $topTen as $comic) : ?>
          <?php 
  	        $path = 'http://www.drunkduck.com/comics/' . $comic['title']{0} . '/' . str_replace(' ', '_', $comic['title']) . '/gfx/thumb.jpg';
          ?>
          <a href="http://www.drunkduck.com/<?php echo str_replace(' ', '_', $comic['title']); ?>"><img class="top-ten-image" src="<?php echo $path; ?>" width="54" title="<?php echo $comic['title']; ?>" description="<?php echo $comic['description']; ?>" author="<?php echo $comic['author']; ?>" /></a>
        <?php endforeach; ?>
        <div id="top-ten-description" class="span-60 box-1 rounded pad-5" style="background-color:#fff;display:none;z-index:1000;position:absolute;">asdfasdkfjasodfj</div>   
      </div>
    </div>
  </div>

<hr class="space" />

<div class="span-61">
  <div class="span-16 green panel-header"><span>Most Liked of The Week</span></div>
</div>
<div class="span-61">
  <div class="span-61 green panel-body box-1">
    <div id="most-liked-holder" class="">
      <?php foreach ((array) $mostLiked as $comic) : ?>
        <?php 
          $path = 'http://www.drunkduck.com/comics/' . $comic['title']{0} . '/' . str_replace(' ', '_', $comic['title']) . '/gfx/thumb.jpg';
        ?>
        <a href="http://www.drunkduck.com/<?php echo str_replace(' ', '_', $comic['title']); ?>"><img class="most-liked-image" src="<?php echo $path; ?>" width="54" title="<?php echo $comic['title']; ?>" description="<?php echo $comic['description']; ?>" author="<?php echo $comic['author']; ?>" /></a>
      <?php endforeach; ?>
      <div id="most-liked-description" class="span-60 box-1 rounded pad-5" style="background-color:#fff;display:none;position:absolute;z-index:1000;">asdfasdkfjasodfj</div>
    </div>
  </div>
</div>

<hr class="space" />

<div class="span-61">
  <div class="span-16 green panel-header"><span>Latest Updates</span></div>
</div>
<div class="span-61">
  <div class="span-61 green panel-body box-1">
    <div id="latest-update-holder" class="">
      <?php foreach ((array) $latestUpdates as $comic) : ?>
        <?php 
          $path = 'http://www.drunkduck.com/comics/' . $comic['title']{0} . '/' . str_replace(' ', '_', $comic['title']) . '/gfx/thumb.jpg';
        ?>
        <a href="http://www.drunkduck.com/<?php echo str_replace(' ', '_', $comic['title']); ?>"><img class="latest-update-image" src="<?php echo $path; ?>" width="54" title="<?php echo $comic['title']; ?>" description="<?php echo $comic['description']; ?>" author="<?php echo $comic['author']; ?>" /></a>
      <?php endforeach; ?>
      <div id="latest-update-description" class="span-60 rounded pad-5" style="background-color:#fff;display:none;z-index:1000;position:absolute;">asdfasdkfjasodfj</div>
    </div>
  </div>
</div>
</div>
<div>

<div class="span-64">
  <div class="span-16 prepend-1">
    <div class="center">
       spotlight
    </div>
    <div class="rounded" style="height:400px;border:2px solid rgb(174,230,1);">
    </div>
  </div>
  <div class="span-45 prepend-1">
    <?php foreach ($news as $entry) : ?>
    <?php 
    $date = new DateTime($entry['created_on']);
    ?>
    <div class="post yellow rounded">
      <span class="headline"><?php echo $entry['title']; ?></span>
      <br />
      <span class="subtitle">posted by <?php echo $entry['author']; ?>
      <br />
      <?php echo $date->format('F j, Y - g:ia'); ?>
      </span>
      <p><?php echo bbcode2html($entry['body']); ?></p>
    </div>
    <hr class="space" />
    <?php endforeach; ?>
  </div>
</div>
</div>

<?php require_once('footer_base.php'); ?>