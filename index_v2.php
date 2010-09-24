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
		var html = '<div class="preview box-1 rounded" style="border:10px rgb(174,230,1) solid;background-color:#FFF"><a href="http://www.drunkduck.com/' + title.replace(/ /g, '_') + '"><h2>' + title + '</h2></a> <span>by <a style="color:#999;" href="http://user.drunkduck.com/' + author + '">' + author + '</a></span><br />' + description + '</div>';
		$('#top-ten-description').html(html).slideDown();
	});
	$('#top-ten-holder').mouseleave(function(){
		$('#top-ten-description').slideUp();
	});
	$('.most-liked-image').live('mouseenter', function(){
    var title = $(this).attr('title');
    var description = $(this).attr('description');
    var author = $(this).attr('author'); 
    var html = '<div class="preview box-1 rounded" style="border:10px rgb(174,230,1) solid;background-color:#FFF"><a href="http://www.drunkduck.com/' + title.replace(/ /g, '_') + '"><h2>' + title + '</h2></a> <span>by <a style="color:#999;" href="http://user.drunkduck.com/' + author + '">' + author + '</a></span><br />' + description + '</div>';
    $('#most-liked-description').html(html).slideDown();
  });
  $('#most-liked-holder').mouseleave(function(){
    $('#most-liked-description').slideUp();
  });
  $('.latest-update-image').live('mouseenter', function(){
    var title = $(this).attr('title');
    var description = $(this).attr('description');
    var author = $(this).attr('author'); 
    var html = '<div class="preview box-1 rounded" style="border:10px rgb(174,230,1) solid;background-color:#FFF"><a href="http://www.drunkduck.com/' + title.replace(/ /g, '_') + '"><h2>' + title + '</h2></a> <span>by <a style="color:#999;" href="http://user.drunkduck.com/' + author + '">' + author + '</a></span><br />' + description + '</div>';
    $('#latest-update-description').html(html).slideDown();
  });
  $('#latest-update-holder').mouseleave(function(){
    $('#latest-update-description').slideUp();
  });
  $('#boomdoggy').click(function(){
    $.fancybox({
      'content': $('#top-ten-filter').html()
    });
  });
  
  
  function getTopTen()
  {
    var thumb = 'http://www.drunkduck.com/comics/' + this.title.charAt(0) + '/' + this.title.replace(/ /g, '_') + '/gfx/thumb.jpg';
    var link = 'http://www.drunkduck.com/' + this.title.replace(/ /g, '_') + '/';
    var html += '<a href="' + link + '"><img class="top-ten-image" src="' + thumb + '" width="54" title="' + this.title + '" description="' + this.description + '" author="' + this.author + '" /></a>';  
  }
});
</script>
<!-- <button id="boomdoggy">open filter</button> -->
<div id="top-ten-filter" style="display:none;">
<table>
  <tr>
    <td>
    comic book/story
    </td>
    <td>
    genre
    </td>
    <td>
    </td>
  </tr>
  <tr>
    <td style="vertical-align:top;">
      <input type="checkbox" />&nbsp;comic book/story
      <br />
      <input type="checkbox" />&nbsp;comic strip
      <br />
      <br />
      art style
      <br />
      <input type="checkbox" />&nbsp;cartoon
      <br />
      <input type="checkbox" />&nbsp;american
      <br />
      <input type="checkbox" />&nbsp;manga
      <br />
      <input type="checkbox" />&nbsp;sprite
      <br />
      <input type="checkbox" />&nbsp;realistic
      <br />
      <input type="checkbox" />&nbsp;sketch
      <br />
      <input type="checkbox" />&nbsp;experimental
      <br />
      <input type="checkbox" />&nbsp;photographic
      <br />
      <input type="checkbox" />&nbsp;stick figure
    </td>
    <td style="vertical-align:top;">
      <input type="checkbox" />&nbsp;fantasy
      <br />
      <input type="checkbox" />&nbsp;parody
      <br />
      <input type="checkbox" />&nbsp;real life
      <br />
      <input type="checkbox" />&nbsp;sci-fi
      <br />
      <input type="checkbox" />&nbsp;horror
      <br />
      <input type="checkbox" />&nbsp;abstract
      <br />
      <input type="checkbox" />&nbsp;adventure
      <br />
      <input type="checkbox" />&nbsp;noir
      <br />
      <br />
      rating
      <br />
      <input type="checkbox" />&nbsp;everybody
      <br />
      <input type="checkbox" />&nbsp;teens+
    </td>
    <td style="vertical-align:top;">
      <input type="checkbox" />&nbsp;spiritual
      <br />
      <input type="checkbox" />&nbsp;romance
      <br />
      <input type="checkbox" />&nbsp;superhero
      <br />
      <input type="checkbox" />&nbsp;western
      <br />
      <input type="checkbox" />&nbsp;mystery
      <br />
      <input type="checkbox" />&nbsp;war
      <br />
      <input type="checkbox" />&nbsp;tribute
      <br />
      <input type="checkbox" />&nbsp;political
      <br />
      <br />
      <br />
      <input type="checkbox" />&nbsp;mature
      <br />
      <input type="checkbox" />&nbsp;adult
    </td>
  </tr>
</table>
</div>
<div class="span-62 box-1 pull-1 canary rounded">

  <div class="span-61">
    <div class="span-24 green panel-header"><span>Top Ten</span></div>
  </div>
  <div class="span-61">
    <div class="span-61 green panel-body box-1">
      <div id="top-ten-holder">
        <div id="top-ten-ajaxer">
        <?php foreach ((array) $topTen as $comic) : ?>
          <?php 
  	        $path = 'http://www.drunkduck.com/comics/' . $comic['title']{0} . '/' . str_replace(' ', '_', $comic['title']) . '/gfx/thumb.jpg';
          ?>
          <a href="http://www.drunkduck.com/<?php echo str_replace(' ', '_', $comic['title']); ?>"><img class="top-ten-image" src="<?php echo $path; ?>" width="54" title="<?php echo $comic['title']; ?>" description="<?php echo $comic['description']; ?>" author="<?php echo $comic['author']; ?>" /></a>
        <?php endforeach; ?>
      </div>
        <div id="top-ten-description" class="span-62 rounded green" style="display:none;z-index:1000;position:absolute;">asdfasdkfjasodfj</div>   
      </div>
    </div>
  </div>

<div style="height:10px;" class="span-64"></div>

<div class="span-61">
  <div class="span-24 green panel-header"><span>Most Liked of The Week</span></div>
</div>
<div class="span-61">
  <div class="span-61 green panel-body box-1">
    <div id="most-liked-holder">
      <div id="most-liked-ajaxer">
      <?php foreach ((array) $mostLiked as $comic) : ?>
        <?php 
          $path = 'http://www.drunkduck.com/comics/' . $comic['title']{0} . '/' . str_replace(' ', '_', $comic['title']) . '/gfx/thumb.jpg';
        ?>
        <a href="http://www.drunkduck.com/<?php echo str_replace(' ', '_', $comic['title']); ?>"><img class="most-liked-image" src="<?php echo $path; ?>" width="54" title="<?php echo $comic['title']; ?>" description="<?php echo $comic['description']; ?>" author="<?php echo $comic['author']; ?>" /></a>
      <?php endforeach; ?>
      </div>
      <div id="most-liked-description" class="span-62 rounded green" style="display:none;position:absolute;z-index:1000;">asdfasdkfjasodfj</div>
    </div>
  </div>
</div>

<div style="height:10px;" class="span-64"></div>

<div class="span-61">
  <div class="span-24 green panel-header"><span>Latest Updates</span></div>
</div>
<div class="span-61">
  <div class="span-61 green panel-body box-1">
    <div id="latest-update-holder">
      <div id="latest-update-ajaxer">
      <?php foreach ((array) $latestUpdates as $comic) : ?>
        <?php 
          $path = 'http://www.drunkduck.com/comics/' . $comic['title']{0} . '/' . str_replace(' ', '_', $comic['title']) . '/gfx/thumb.jpg';
        ?>
        <a href="http://www.drunkduck.com/<?php echo str_replace(' ', '_', $comic['title']); ?>"><img class="latest-update-image" src="<?php echo $path; ?>" width="54" title="<?php echo $comic['title']; ?>" description="<?php echo $comic['description']; ?>" author="<?php echo $comic['author']; ?>" /></a>
      <?php endforeach; ?>
      </div>
      <div id="latest-update-description" class="span-62 rounded green" style="display:none;z-index:1000;position:absolute;">asdfasdkfjasodfj</div>
    </div>
  </div>
</div>
</div>
<div>

<style type="text/css">
.spotlight {
    padding-top:60px;
    margin-left:10px;
    margin-top:10px;
    background: transparent url('/media/images/spotlight-title.png') top center no-repeat;
    }
.spoitlight div img {
    margin: 10px 0 10px 0;
    }
</style>
<div class="span-64">
  <div class="spotlight span-18 " style="">
    
    <div class="rounded center" style="padding:20px 0 20px 0;border:2px solid rgb(174,230,1);border-top:0;">
    <a href="featured_v2.php"><img src="/media/images/badge-featured.png" /></a>
    <div style="height:30px;" class="span-16"></div>
    <img src="/media/images/badge-ducktv.png" />
    <div style="height:30px;" class="span-16"></div>
    <img src="/media/images/badge-twitter.png" />
    <div style="height:30px;" class="span-16"></div>
    <img src="/media/images/badge-faceduck.png" />
    <div style="height:30px;" class="span-16"></div>
    </div>
  </div>
  <div class="span-43 prepend-1">
    <?php foreach ($news as $entry) : ?>
    <?php 
    $date = new DateTime($entry['created_on']);
    ?>
    <div class="post yellow rounded box-2">
      <span class="headline"><?php echo $entry['title']; ?></span>
      <br />
      <span class="subtitle">posted by <?php echo $entry['author']; ?>
      <br />
      <span style="font-weight:normal;font-size:10px"><?php echo $date->format('F j, Y - g:ia'); ?></span>
      </span>
      <p><?php echo bbcode2html($entry['body']); ?></p>
    </div>
    <hr class="space" />
    <?php endforeach; ?>
  </div>
</div>
</div>

<?php require_once('footer_base.php'); ?>