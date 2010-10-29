<?php require_once('header_base.php'); ?>

<?php
if ($searchText = trim($_GET['searchTxt'])) {
  $where = "and (c.comic_name like '%$searchText%' or c.description like '%$searchText%')";  
}
$limit = 25;
$db = new DB();
$query = "select c.comic_id as id, c.comic_name as title, u.username as author, c.rating_symbol as rating, c.total_pages as pages, c.description, from_unixtime(if(c.last_update = 0, c.created_timestamp, c.last_update)) as date
          from comics c 
          inner join users u 
          on u.user_id = c.user_id 
          where created_timestamp != 0 
          and created_timestamp is not null 
          and total_pages > 0 
          $where
          order by c.comic_name asc 
          limit $limit";
$featured = $db->fetchAll($query);
$query = "select count(1)
          from comics c 
          where created_timestamp != 0 
          and created_timestamp is not null 
          and total_pages > 0 
          $where";
$featuredCount = $db->fetchOne($query);
/*
$query = "select from_unixtime(max(created_timestamp)) as max, from_unixtime(min(created_timestamp)) as min 
          from comics c
          where created_timestamp != 0 
          and created_timestamp is not null 
          and total_pages > 0 
          $where";
$featuremaxmin = $db->fetchRow($query);
$min = new DateTime($featuremaxmin['min']);
$max = new DateTime($featuremaxmin['max']);
$dateArray = array($min->format('Y-m') => $min->format('M Y'));
do {
  $min->modify('+1 month');
  $dateArray[$min->format('Y-m')] = $min->format('M Y');
} while ($min->format('Y-m') != $max->format('Y-m'));
$dateArray = array_reverse($dateArray); */
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
var limit = <?php echo $limit; ?>;
  
$(document).ready(function() {
  function setPagerMax(pagerMax) 
  {
    pager_max = pagerMax;
    var html = pager_max;
    if (pagerMax = 1) {
      html += ' comic';
    } else {
      html += ' comics';
    }
    html += ' match your selected criteria.';
    $('#match-number').html(html);
  }

  $('#funktropic').submit(function(){
  $(this).ajaxSubmit({
    data: {
      offset: pager, 
      limit: limit
    },
    success: function(data) {
      data = jQuery.parseJSON(data);
      var html = '';
      setPagerMax(data.count);
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
    }
  });
    return false;
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
      $('#funktropic').submit();
    }
  });
  
  
  
  $('#submitButton').click(function(){
    pager = 0;
    return true;
  });
  
  $('#select-button').click(function(){
    $('.check').attr('checked', true);
  });
  
  $('#unselect-button').click(function(){
    $('.check').attr('checked', false);
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
            <img src="/media/images/advanced-search.png" />
            </div>
        </div>
        
<div class="span-64">
<div class="box-2" style="padding-bottom:10px;">
<form id="funktropic" method="post" action="/ajax/advanced_search.php">
  <div style="text-align:center;margin-bottom:10px;">
 <input type="text" name="search" style="color:#fff;" class="button featured_search" value="<?php echo $searchText; ?>"/>&nbsp;&nbsp;<input type="submit" id="submitButton" class="button" style="min-width:0;" value="search" />
  </div>
  <div class="button">
  <table style="width:100%;">
    <tr>
      <td style="vertical-align:top;">
       sort by
       <br />
       <input type="radio" name="sort_col" value="comic_name" checked /> title
       <br />
       <input type="radio" name="sort_col" value="total_pages" /> # of pages
       <br />
       <input type="radio" name="sort_col" value="last_update" /> last updated
       <br />
       <br />
       <input type="radio" name="sort_dir" value="asc" checked /> ascending
       <br />
       <input type="radio" name="sort_dir" value="desc" /> descending
      </td>
      <td style="vertical-align:top;">
        type of comic
        <br />
        <input name="filter[comic_type][]" value="1" type="checkbox" class="check" checked />&nbsp;comic book/story
        <br />
        <input name="filter[comic_type][]" value="0" type="checkbox" class="check" checked />&nbsp;comic strip
      </td>
      <td style="vertical-align:top;">
        genre
        <br />
        <input name="filter[search_category][]" value="0" type="checkbox" class="check" checked />&nbsp;fantasy
        <br />
        <input name="filter[search_category][]" value="1" type="checkbox" class="check" checked />&nbsp;parody
        <br />
        <input name="filter[search_category][]" value="2" type="checkbox" class="check" checked />&nbsp;real life
        <br />
        <input name="filter[search_category][]" value="4" type="checkbox" class="check" checked />&nbsp;sci-fi
      </td>
      <td style="vertical-align:top;">
        &nbsp;
        <br />
        <input name="filter[search_category][]" value="5" type="checkbox" class="check" checked />&nbsp;horror
        <br />
        <input name="filter[search_category][]" value="6" type="checkbox" class="check" checked />&nbsp;abstract
        <br />
        <input name="filter[search_category][]" value="8" type="checkbox" class="check" checked />&nbsp;adventure
        <br />
        <input name="filter[search_category][]" value="9" type="checkbox" class="check" checked />&nbsp;noir
      </td>
      <td style="vertical-align:top;">
        &nbsp;
        <br />
        <input name="filter[search_category][]" value="13" type="checkbox" class="check" checked />&nbsp;spiritual
        <br />
        <input name="filter[search_category][]" value="14" type="checkbox" class="check" checked />&nbsp;romance
        <br />
        <input name="filter[search_category][]" value="15" type="checkbox" class="check" checked />&nbsp;superhero
        <br />
        <input name="filter[search_category][]" value="16" type="checkbox" class="check" checked />&nbsp;western
      </td>
      <td style="vertical-align:top;">
        &nbsp;
        <br />
        <input name="filter[search_category][]" value="17" type="checkbox" class="check" checked />&nbsp;mystery
        <br />
        <input name="filter[search_category][]" value="18" type="checkbox" class="check" checked />&nbsp;war
        <br />
        <input name="filter[search_category][]" value="19" type="checkbox" class="check" checked />&nbsp;tribute
        <br />
        <input name="filter[search_category][]" value="12" type="checkbox" class="check" checked />&nbsp;political
      </td>
    </tr>
    <tr>
      <td style="vertical-align:top;">
        art style
        <br />
        <input name="filter[search_style][]" value="0" type="checkbox" class="check" checked />&nbsp;cartoon
        <br />
        <input name="filter[search_style][]" value="1" type="checkbox" class="check" checked />&nbsp;american
        <br />
        <input name="filter[search_style][]" value="2" type="checkbox" class="check" checked />&nbsp;manga
        <br />
        <input name="filter[search_style][]" value="4" type="checkbox" class="check" checked />&nbsp;sprite
        <br />
        <input name="filter[search_style][]" value="3" type="checkbox" class="check" checked />&nbsp;realistic
      </td>
      <td style="vertical-align:top;">
        &nbsp;
        <br />
        <input name="filter[search_style][]" value="5" type="checkbox" class="check" checked />&nbsp;sketch
        <br />
        <input name="filter[search_style][]" value="6" type="checkbox" class="check" checked />&nbsp;experimental
        <br />
        <input name="filter[search_style][]" value="7" type="checkbox" class="check" checked />&nbsp;photographic
        <br />
        <input name="filter[search_style][]" value="8" type="checkbox" class="check" checked />&nbsp;stick figure
      </td>
      <td style="vertical-align:top;">
        rating
        <br />
        <input name="filter[rating_symbol][]" value="E" type="checkbox" class="check" checked />&nbsp;everybody
        <br />
        <input name="filter[rating_symbol][]"  value="T" type="checkbox" class="check" checked />&nbsp;teens+
        <br />
        <input name="filter[rating_symbol][]" value="M" type="checkbox" class="check" checked />&nbsp;mature
        <br />
        <input name="filter[rating_symbol][]" value="A" type="checkbox" class="check" checked />&nbsp;adult
      </td>
      <td style="vertical-align:top;">
        tone
        <br />
        <input name="filter[subcategory][]" value="0" type="checkbox" class="check" checked />&nbsp;comedy
        <br />
        <input name="filter[subcategory][]"  value="1" type="checkbox" class="check" checked />&nbsp;drama
        <br />
        <input name="filter[subcategory][]" value="2" type="checkbox" class="check" checked />&nbsp;random
      </td>
      <td style="vertical-align:top;">
        last update
        <br />
        <input name="update" value="any" type="radio" checked />&nbsp;any
        <br />
        <input name="update" value="today" type="radio" />&nbsp;today
        <br />
        <input name="update"  value="week" type="radio" />&nbsp;last week
        <br />
        <input name="update"  value="month" type="radio" />&nbsp;last month
      </td>
      <td style="vertical-align:top;">
        pages/strips
        <br />
        <input name="pages" value="0" type="radio" checked />&nbsp;any
        <br />
        <input name="pages"  value="2" type="radio" />&nbsp;2+
        <br />
        <input name="pages"  value="10" type="radio" />&nbsp;10+
        <br />
        <input name="pages"  value="50" type="radio" />&nbsp;50+
        <br />
        <input name="pages" value="range" type="radio" />&nbsp;range:
        <br />
        <input type="text" name="page_min" style="width:20px;" /> to <input type="text" name="page_max" style="width:20px;" />
      </td>
    </tr>
  </table>
 <div style="text-align:center;">
    <input type="button" id="select-button" class="rounded teal-words" style="background-color:#fff;border:0;" value="select all" />&nbsp;&nbsp;
    <input type="button" id="unselect-button" class="rounded teal-words" style="background-color:#fff;border:0;" value="unselect all" />&nbsp;&nbsp;
 </div>
  </div>
</form>
</div>
</div>
  <div style="text-align:center;margin-bottom:10px;">
    <span class="button" id="match-number"><?php echo $featuredCount; ?> comics match your selection criteria.</span>
  </div>
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
        <img class="search-image" src="<?php echo $path; ?>" width="80" height="100" comic_title="<?php echo $comic['title']; ?>" description="<?php echo htmlspecialchars($comic['description']); ?>" author="<?php echo $comic['author']; ?>" rating="<?php echo $comic['rating']; ?>" pages="<?php echo $comic['pages']; ?>" />
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
  <div id="search-description" style="text-align:left;">
  </div>
</div>
</div>
<div class="box-2">
   <button class="featured_button button" style="float:left;" direction="prev">previous</button>
  <button class="featured_button button" style="float:right;" direction="next">next</button>
  <div style="clear:both;"></div>
</div>

<?php require_once('footer_base.php'); ?>
