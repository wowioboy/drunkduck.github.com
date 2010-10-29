<?php 
$showFeatured = true;
require_once('header_base.php');
require_once('bbcode.php'); 

$db = new DB();
$query = "select c.comic_id as id, c.comic_name as title, c.description, c.rating_symbol as rating, c.total_pages as pages, u.username as author 
from comics c 
inner join users u 
on u.user_id = c.user_id
order by seven_day_visits desc 
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
<?php 
$filterArray = array('latest update' => 'latest', "quail's random" => 'random', 'top ten' => 'ten');
?>
<script type="text/javascript">
$(document).ready(function(){
  <?php foreach ($filterArray as $title => $filter) : ?>
    $('.<?php echo $filter; ?>-image').live('mouseenter', function(){
    	var title = $(this).attr('comic_title');
  		var description = $(this).attr('description');
  		var author = $(this).attr('author'); 
  		var rating = $(this).attr('rating'); 
  		var pages = $(this).attr('pages'); 
  		var html = '<div class="preview box-1 rounded" style="border:10px rgb(174,230,1) solid;background-color:#FFF">' + 
  		           '<div style="float:left;"><a href="/' + title.replace(/ /g, '_') + '"><h2>' + title + '</h2></a>&nbsp;<span>by&nbsp;<a style="color:#999;" href="/control_panel/profile.php?username=' + author + '">' + author + '</a></span></div>' + 
  		           '<div style="float:right;">' + rating + ', ' + pages + ' pages</div>' + 
  		           '<div style="clear:both;">' + description + '</div>' +
  		           '</div>';
  		
  		var position = $(this).position();
      var left = position.left -10;
      left += 'px';
  		
  		$('#<?php echo $filter; ?>-description').html(html).slideDown();
  		 $('#<?php echo $filter; ?>-point').stop().show();
      $('#<?php echo $filter; ?>-point').animate({left: left});
  	});
  	$('#<?php echo $filter; ?>-holder').parent().mouseleave(function(){
  		$('#<?php echo $filter; ?>-description').slideUp();
  		$('#<?php echo $filter; ?>-point').hide();
  	});
    $('#<?php echo $filter; ?>-filter-button').click(function(){
      $('#<?php echo $filter; ?>-filter').dialog({
        width:550,
        height:450,
        title:"<?php echo $title; ?> filter"
      });
    });
    $('#<?php echo $filter; ?>-form').ajaxForm({
      success: function(data) {
        var html = '';
        data = jQuery.parseJSON(data);
        $.each(data, function(){
          var path = "http://images.drunkduck.com/process/comic_" + this.id + "_0_T_0_sm.jpg";
          html += '<a class="showcase" href="/' + this.title.replace(/ /g, '_') + '/"><img class="<?php echo $filter; ?>-image" src="' + path + '" comic_title="' + this.title + '" description="' + this.description + '" author="' + this.author + '" pages="' + this.pages + '" rating="' + this.rating + '" /></a>';
        });
        $('#<?php echo $filter; ?>-ajaxer').html(html);
      }
    });
    $('#<?php echo $filter; ?>-select-button').click(function(){
      $('.<?php echo $filter; ?>-check').attr('checked', true);
    });
    $('#<?php echo $filter; ?>-unselect-button').click(function(){
      $('.<?php echo $filter; ?>-check').attr('checked', false);
    });
  <?php endforeach; ?>
});
</script>
<style type="text/css"> 
.showcase {
  margin:0 2px;
  float:left;
}
.showcase img {
  margin:0;
  width:57px;
}
.description {
  display:none;
  z-index:5;
  position:absolute;
  top:75px;
  left:-10px;
}
.point {
  position:absolute;
  top:71px;
  left:10px;
  z-index:6;
  display:none;
}
</style>
<?php foreach ($filterArray as $filter) : ?>
<div id="<?php echo $filter; ?>-filter" style="display:none;">
<form id="<?php echo $filter; ?>-form" method="post" action="/ajax/homepage/<?php echo $filter; ?>.php">
<table>
  <tr>
    <td>comic book/story</td>
    <td>genre</td>
    <td></td>
  </tr>
  <tr>
    <td style="vertical-align:top;">
      <input name="filter[comic_type][]" value="1" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;comic book/story
      <br />
      <input name="filter[comic_type][]" value="0" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;comic strip
      <br />
      <br />
      art style
      <br />
      <input name="filter[search_style][]" value="0" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;cartoon
      <br />
      <input name="filter[search_style][]" value="1" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;american
      <br />
      <input name="filter[search_style][]" value="2" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;manga
      <br />
      <input name="filter[search_style][]" value="4" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;sprite
      <br />
      <input name="filter[search_style][]" value="3" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;realistic
      <br />
      <input name="filter[search_style][]" value="5" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;sketch
      <br />
      <input name="filter[search_style][]" value="6" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;experimental
      <br />
      <input name="filter[search_style][]" value="7" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;photographic
      <br />
      <input name="filter[search_style][]" value="8" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;stick figure
    </td>
    <td style="vertical-align:top;">
      <input name="filter[search_category][]" value="0" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;fantasy
      <br />
      <input name="filter[search_category][]" value="1" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;parody
      <br />
      <input name="filter[search_category][]" value="2" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;real life
      <br />
      <input name="filter[search_category][]" value="4" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;sci-fi
      <br />
      <input name="filter[search_category][]" value="5" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;horror
      <br />
      <input name="filter[search_category][]" value="6" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;abstract
      <br />
      <input name="filter[search_category][]" value="8" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;adventure
      <br />
      <input name="filter[search_category][]" value="9" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;noir
      <br />
      <br />
      rating
      <br />
      <input name="filter[rating_symbol][]" value="E" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;everybody
      <br />
      <input name="filter[rating_symbol][]"  value="T" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;teens+
    </td>
    <td style="vertical-align:top;">
      <input name="filter[search_category][]" value="13" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;spiritual
      <br />
      <input name="filter[search_category][]" value="14" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;romance
      <br />
      <input name="filter[search_category][]" value="15" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;superhero
      <br />
      <input name="filter[search_category][]" value="16" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;western
      <br />
      <input name="filter[search_category][]" value="17" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;mystery
      <br />
      <input name="filter[search_category][]" value="18" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;war
      <br />
      <input name="filter[search_category][]" value="19" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;tribute
      <br />
      <input name="filter[search_category][]" value="12" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;political
      <br />
      <br />
      <br />
      <input name="filter[rating_symbol][]" value="M" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;mature
      <br />
      <input name="filter[rating_symbol][]" value="A" type="checkbox" class="<?php echo $filter; ?>-check" checked />&nbsp;adult
    </td>
  </tr>
  <tr>
    <td style="text-align:center;"><input type="button" id="<?php echo $filter; ?>-select-button" class="button" value="select all" /></td>
    <td style="text-align:center;"><input type="button" id="<?php echo $filter; ?>-unselect-button" class="button" value="unselect all" /></td>
    <td style="text-align:right;"><input class="button" type="submit" value="save" /></td>
  </tr>
</table>
</form>
</div>
<?php endforeach; ?>
<div class="span-62 box-1 pull-1 canary rounded" style="padding-top:0;">

  <div class="span-61">
    <div class="span-24 green panel-header">
        <span>Top Ten</span>
        <a id="ten-filter-button" href="javascript:">filter</a>&nbsp;&nbsp;
    </div>
  </div>
  <div class="span-61">
    <div class="span-61 green panel-body box-1 center">
      <div id="ten-holder">
        <div id="ten-ajaxer">
        <?php foreach ((array) $topTen as $comic) : ?>
          <?php 
  	        $path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
          ?>
          <a class="showcase" href="/<?php echo str_replace(' ', '_', $comic['title']); ?>/"><img class="ten-image" src="<?php echo $path; ?>" comic_title="<?php echo $comic['title']; ?>" description="<?php echo htmlspecialchars($comic['description']); ?>" author="<?php echo $comic['author']; ?>" rating="<?php echo $comic['rating']; ?>" pages="<?php echo $comic['pages']; ?>" /></a>
        <?php endforeach; ?>
      </div>
        <div style="position:relative;">
          <div id="ten-description" class="span-63 bottom-rounded green left description"></div> 
          <img id="ten-point" class="point" src="/media/images/tooltip-point.png" />  
        </div>
      </div>
    </div>
  </div>


<div class="span-61">
  <div class="span-24 green panel-header" style="">
        <span>Quail's Random</span>  
        <a id="random-filter-button" href="javascript:">filter</a>&nbsp;&nbsp;
  </div>
</div>
<div class="span-61">
  <div class="span-61 green panel-body box-1 center">
    <div id="random-holder">
      <div id="random-ajaxer">
      <?php foreach ((array) $random as $comic) : ?>
        <?php 
          $path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
        ?>
        <a class="showcase" href="/<?php echo str_replace(' ', '_', $comic['title']); ?>/"><img class="random-image" src="<?php echo $path; ?>" comic_title="<?php echo $comic['title']; ?>" description="<?php echo htmlspecialchars($comic['description']); ?>" author="<?php echo $comic['author']; ?>" rating="<?php echo $comic['rating']; ?>" pages="<?php echo $comic['pages']; ?>" /></a>
      <?php endforeach; ?>
      </div>
      <div style="position:relative;">
        <div id="random-description" class="span-63 bottom-rounded green left description"></div>
        <img id="random-point" class="point" src="/media/images/tooltip-point.png" />
      </div>
    </div>
  </div>
</div>


<div class="span-61">
  <div class="span-24 green panel-header" >
        <span>Latest Updates</span>
        <a id="latest-filter-button" href="javascript:">filter</a>&nbsp;&nbsp;
  </div>
</div>
<div class="span-61">
  <div class="span-61 green panel-body box-1 center">
    <div id="latest-holder">
      <div id="latest-ajaxer">
      <?php foreach ((array) $latestUpdates as $comic) : ?>
        <?php 
          $path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
        ?>
        <a class="showcase" href="/<?php echo str_replace(' ', '_', $comic['title']); ?>/"><img class="latest-image" src="<?php echo $path; ?>" comic_title="<?php echo $comic['title']; ?>" description="<?php echo htmlspecialchars($comic['description']); ?>" author="<?php echo $comic['author']; ?>" rating="<?php echo $comic['rating']; ?>" pages="<?php echo $comic['pages']; ?>" /></a>
      <?php endforeach; ?>
      </div>
      <div style="position:relative;">
        <div id="latest-description" class="span-63 bottom-rounded green left description"></div>
        <img id="latest-point" class="point" src="/media/images/tooltip-point.png" />
      </div>
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
    <a href="/feedback.php"><img src="/media/images/badge-beta-feedback.png" /></a>
    <div style="height:30px;" class="span-16"></div>
    <a href="featured.php"><img src="/media/images/badge-featured.png" /></a>
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