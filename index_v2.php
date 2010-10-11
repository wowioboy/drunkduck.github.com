<?php 
$showFeatured = true;
require_once('header_base.php');
require_once('bbcode.php'); 

$db = new DB();
$query = "select c.comic_id as id, c.comic_name as title, c.description, c.rating_symbol as rating, c.total_pages as pages, u.username as author 
from comics c 
inner join users u 
on u.user_id = c.user_id
order by visits desc 
limit 10";
$topTen = $db->fetchAll($query);
$query = "select c.comic_id as id, c.comic_name as title, c.description, c.rating_symbol as rating, c.total_pages as pages, u.username as author 
from comics c 
inner join users u 
on u.user_id = c.user_id
order by last_update desc 
limit 10";
$latestUpdates = $db->fetchAll($query);

$query = "select c.comic_id as id, c.comic_name as title, c.description, c.rating_symbol as rating, c.total_pages as pages, u.username as author
from comics c 
left join users u 
on u.user_id = c.user_id 
where c.total_pages > 0 
order by rand() 
limit 10";
$random = $db->fetchAll($query);

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
  $('.ten-image').live('mouseenter', function(){
  	var title = $(this).attr('comic_title');
		var description = $(this).attr('description');
		var author = $(this).attr('author'); 
		var html = '<div class="preview box-1 rounded" style="border:10px rgb(174,230,1) solid;background-color:#FFF"><a href="/' + title.replace(/ /g, '_') + '"><h2>' + title + '</h2></a> <span>by <a style="color:#999;" href="http://user.drunkduck.com/' + author + '">' + author + '</a></span><br />' + description + '</div>';
		$('#ten-description').html(html).slideDown();
	});
	$('#ten-holder').mouseleave(function(){
		$('#ten-description').slideUp();
	});
	$('.random-image').live('mouseenter', function(){
    var title = $(this).attr('comic_title');
    var description = $(this).attr('description');
    var author = $(this).attr('author'); 
    var html = '<div class="preview box-1 rounded" style="border:10px rgb(174,230,1) solid;background-color:#FFF"><a href="/' + title.replace(/ /g, '_') + '"><h2>' + title + '</h2></a> <span>by <a style="color:#999;" href="http://user.drunkduck.com/' + author + '">' + author + '</a></span><br />' + description + '</div>';
    $('#random-description').html(html).slideDown();
  });
  $('#random-holder').mouseleave(function(){
    $('#random-description').slideUp();
  });
  $('.latest-image').live('mouseenter', function(){
    var title = $(this).attr('comic_title');
    var description = $(this).attr('description');
    var author = $(this).attr('author'); 
    var html = '<div class="preview box-1 rounded" style="border:10px rgb(174,230,1) solid;background-color:#FFF"><a href="/' + title.replace(/ /g, '_') + '"><h2>' + title + '</h2></a> <span>by <a style="color:#999;" href="http://user.drunkduck.com/' + author + '">' + author + '</a></span><br />' + description + '</div>';
    $('#latest-description').html(html).slideDown();
  });
  $('#latest-holder').mouseleave(function(){
    $('#latest-description').slideUp();
  });
  
  $('#ten-filter-button').click(function(){
    $('#ten-filter').dialog({
      width:550,
      height:450,
      title:'top ten filter'
    });
  });
  $('#random-filter-button').click(function(){
    $('#random-filter').dialog({
      width:550,
      height:450,
      title:'quail\'s random filter'
    });
  });
  $('#latest-filter-button').click(function(){
    $('#latest-filter').dialog({
      width:550,
      height:450,
      title:'latest update filter'
    });
  });
  
  $('#ten-form').ajaxForm({
    success: function(data) {
      var html = '';
      data = jQuery.parseJSON(data);
      $.each(data, function(){
        var path = "http://images.drunkduck.com/process/comic_" + this.id + "_0_T_0_sm.jpg";
        html += '<a href="/' + this.title.replace(/ /g, '_') + '"><img class="ten-image" src="' + path + '" width="54" comic_title="' + this.title + '" description="' + this.description + '" author="' + this.author + '" /></a>';
      });
      $('#ten-ajaxer').html(html);
    }
  });
  $('#random-form').ajaxForm({
    success: function(data) {
      var html = '';
      data = jQuery.parseJSON(data);
      $.each(data, function(){
        var path = "http://images.drunkduck.com/process/comic_" + this.id + "_0_T_0_sm.jpg";
        html += '<a href="/' + this.title.replace(/ /g, '_') + '"><img class="ten-image" src="' + path + '" width="54" comic_title="' + this.title + '" description="' + this.description + '" author="' + this.author + '" /></a>';
      });
      $('#ten-ajaxer').html(html);
    }
  });
  $('#latest-form').ajaxForm({
    success: function(data) {
      var html = '';
      data = jQuery.parseJSON(data);
      $.each(data, function(){
        var path = "http://images.drunkduck.com/process/comic_" + this.id + "_0_T_0_sm.jpg";
        html += '<a href="/' + this.title.replace(/ /g, '_') + '"><img class="ten-image" src="' + path + '" width="54" comic_title="' + this.title + '" description="' + this.description + '" author="' + this.author + '" /></a>';
      });
      $('#ten-ajaxer').html(html);
    }
  });
});
</script>
<?php 
$filterArray = array('latest', 'random', 'ten');
?>
<?php foreach ($filterArray as $filter) : ?>
<div id="<?php echo $filter; ?>-filter" style="display:none;">
<form id="<?php echo $filter; ?>-form" method="post" action="/ajax/homepage/<?php echo $filter; ?>.php">
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
  <tr>
    <td colspan="3" style="text-align:right;"><input class="button" type="submit" value="save" /></td>
  </tr>
</table>
</form>
</div>
<?php endforeach; ?>
<div class="span-62 box-1 pull-1 canary rounded">

  <div class="span-61">
    <div class="span-24 green panel-header" style="height:25px;"><img id="ten-filter-button" src="/media/images/triangle.gif" />&nbsp;<span>Top Ten</span></div>
  </div>
  <div class="span-61">
    <div class="span-61 green panel-body box-1">
      <div id="ten-holder">
        <div id="ten-ajaxer">
        <?php foreach ((array) $topTen as $comic) : ?>
          <?php 
  	        $path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
          ?>
          <a href="/<?php echo str_replace(' ', '_', $comic['title']); ?>"><img class="ten-image" src="<?php echo $path; ?>" width="54" comic_title="<?php echo $comic['title']; ?>" description="<?php echo $comic['description']; ?>" author="<?php echo $comic['author']; ?>" /></a>
        <?php endforeach; ?>
      </div>
        <div id="ten-description" class="span-62 rounded green" style="display:none;z-index:1000;position:absolute;">asdfasdkfjasodfj</div>   
      </div>
    </div>
  </div>

<div style="height:10px;" class="span-64"></div>

<div class="span-61">
  <div class="span-24 green panel-header" style="height:25px;"><img id="random-filter-button" src="/media/images/triangle.gif" />&nbsp;<span>Quail's Random</span></div>
</div>
<div class="span-61">
  <div class="span-61 green panel-body box-1">
    <div id="random-holder">
      <div id="random-ajaxer">
      <?php foreach ((array) $random as $comic) : ?>
        <?php 
          $path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
        ?>
        <a href="/<?php echo str_replace(' ', '_', $comic['title']); ?>"><img class="random-image" src="<?php echo $path; ?>" width="54" comic_title="<?php echo $comic['title']; ?>" description="<?php echo $comic['description']; ?>" author="<?php echo $comic['author']; ?>" /></a>
      <?php endforeach; ?>
      </div>
      <div id="random-description" class="span-62 rounded green" style="display:none;position:absolute;z-index:1000;">asdfasdkfjasodfj</div>
    </div>
  </div>
</div>

<div style="height:10px;" class="span-64"></div>

<div class="span-61">
  <div class="span-24 green panel-header"  style="height:25px;"><img id="latest-filter-button" src="/media/images/triangle.gif" />&nbsp;<span>Latest Updates</span></div>
</div>
<div class="span-61">
  <div class="span-61 green panel-body box-1">
    <div id="latest-holder">
      <div id="latest-ajaxer">
      <?php foreach ((array) $latestUpdates as $comic) : ?>
        <?php 
          $path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
        ?>
        <a href="/<?php echo str_replace(' ', '_', $comic['title']); ?>"><img class="latest-image" src="<?php echo $path; ?>" width="54" comic_title="<?php echo $comic['title']; ?>" description="<?php echo $comic['description']; ?>" author="<?php echo $comic['author']; ?>" /></a>
      <?php endforeach; ?>
      </div>
      <div id="latest-description" class="span-62 rounded green" style="display:none;z-index:1000;position:absolute;">asdfasdkfjasodfj</div>
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
    <a href="http://www.twitter.com/drunkduck"><img src="/media/images/badge-twitter.png" /></a>
    <div style="height:30px;" class="span-16"></div>
    <a href="http://www.facebook.com/group.php?gid=2307463656"><img src="/media/images/badge-faceduck.png" /></a>
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
    <div style="height:10px;"></div>
    <?php endforeach; ?>
  </div>
</div>
</div>

<?php require_once('footer_base.php'); ?>