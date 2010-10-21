<?php require_once('header_base.php'); ?>

<?php
$limit = 25;
$db = new DB();
$query = "select c.comic_id as id, c.comic_name as title, u.username as author, c.rating_symbol as rating, c.total_pages as pages, c.description, from_unixtime(c.created_timestamp) as date
          from comics c 
          inner join users u 
          on u.user_id = c.user_id 
          where created_timestamp != 0 
          and created_timestamp is not null 
          and total_pages > 0
          order by c.comic_name asc 
          limit $limit";
$featured = $db->fetchAll($query);
$query = "select count(1)
          from comics c";
$featuredCount = $db->fetchOne($query);
$query = "select from_unixtime(max(created_timestamp)) as max, from_unixtime(min(created_timestamp)) as min 
          from comics 
          where created_timestamp != 0 
          and created_timestamp is not null 
          and total_pages > 0";
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
  display:inline-block;
  border:1px solid #45b4b9;
  padding:10px 10px 0px 10px;
  background-color:#45b4b9;
  margin:0px 5px 10px 5px;
  text-align:left;
}
.grid-panel div {
  display:inline-block;
  width:80px;
  height:100px;
}
.grid-panel span {
  color:#fff;
}
</style>
<script type="text/javascript">
var pager = 0;
var pager_max = <?php echo $featuredCount; ?>;
var limit = <?php echo $limit; ?>;
  
$(document).ready(function() {
  $('#funktropic').submit(function(){
  $(this).ajaxSubmit({
    data: {
      offset: pager, 
      limit: limit
    },
    success: function(data) {
      data = jQuery.parseJSON(data);
      var html = '';
      pager_max = data.count;
      if (data.featured) {
        $.each(data.featured, function(){
            html += '<a href="/' + this.title.replace(/ /g, '_') + '/">' + 
                    '<div class="rounded grid-panel" title="<span class=\'drunk\'>' + this.title + '</span> by ' + this.author + '<br /><span class=\'teal-words\'>' + this.description + '</span>">' + 
                    '<div>' + 
                    '<img src="http://images.drunkduck.com/process/comic_' + this.id + '_0_T_0_sm.jpg" />' + 
                    '</div>' + 
                    '<br />' + 
                    '<span style="color:#fff;">' + this.date + '</span>' + 
                    '</div>' + 
                    '</a>';
        });
      }
      $('#featured_holder').html(html);
      $('*[title]').tooltip({
        position: "bottom center"
      });
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
    $('#funktropic').submit();
  });
  
  $('#select-button').click(function(){
    $('.check').attr('checked', true);
  });
  
  $('#unselect-button').click(function(){
    $('.check').attr('checked', false);
  });
  
});
</script>
<style>
.featured_search {
  background-image:url('/media/images/blue-search-box.png');
  width:135px;
}
</style>
        <div class="rounded canary span-63 box-1 pull-1">
            <div class="span-63 dark-green rounded header">
            <img src="/media/images/advanced-search.png" />
            </div>
<div class="span-64 box-1 header-menu">
  <button class="featured_button rounded left button" direction="prev">previous</button>
   
  <button class="featured_button rounded right button" direction="next">next</button>
</div>
        </div>
        
<div class="span-64 box-1">
<form id="funktropic" method="post" action="/ajax/advanced_search.php">
  <select name="month" class="button rounded featureMonth" style="border:none;">
    <option value="">Select Month</option>
    <?php foreach ($dateArray as $numDate => $dateString) : ?>
      <option value="<?php echo $numDate; ?>"><?php echo $dateString; ?></option>
    <?php endforeach; ?>
  </select>
 <input type="text" name="search" style="color:#fff;" class="button featured_search" />
 <input type="radio" name="sort_col" value="comic_name" checked /> title
 <input type="radio" name="sort_col" value="total_pages" /> # of pages
<table>
  <tr>
    <td>comic book/story</td>
    <td>genre</td>
    <td></td>
  </tr>
  <tr>
    <td style="vertical-align:top;">
      <input name="filter[comic_type][]" value="1" type="checkbox" class="check" checked />&nbsp;comic book/story
      <br />
      <input name="filter[comic_type][]" value="0" type="checkbox" class="check" checked />&nbsp;comic strip
      <br />
      <br />
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
      <input name="filter[search_category][]" value="0" type="checkbox" class="check" checked />&nbsp;fantasy
      <br />
      <input name="filter[search_category][]" value="1" type="checkbox" class="check" checked />&nbsp;parody
      <br />
      <input name="filter[search_category][]" value="2" type="checkbox" class="check" checked />&nbsp;real life
      <br />
      <input name="filter[search_category][]" value="4" type="checkbox" class="check" checked />&nbsp;sci-fi
      <br />
      <input name="filter[search_category][]" value="5" type="checkbox" class="check" checked />&nbsp;horror
      <br />
      <input name="filter[search_category][]" value="6" type="checkbox" class="check" checked />&nbsp;abstract
      <br />
      <input name="filter[search_category][]" value="8" type="checkbox" class="check" checked />&nbsp;adventure
      <br />
      <input name="filter[search_category][]" value="9" type="checkbox" class="check" checked />&nbsp;noir
      <br />
      <br />
      rating
      <br />
      <input name="filter[rating_symbol][]" value="E" type="checkbox" class="check" checked />&nbsp;everybody
      <br />
      <input name="filter[rating_symbol][]"  value="T" type="checkbox" class="check" checked />&nbsp;teens+
    </td>
    <td style="vertical-align:top;">
      <input name="filter[search_category][]" value="13" type="checkbox" class="check" checked />&nbsp;spiritual
      <br />
      <input name="filter[search_category][]" value="14" type="checkbox" class="check" checked />&nbsp;romance
      <br />
      <input name="filter[search_category][]" value="15" type="checkbox" class="check" checked />&nbsp;superhero
      <br />
      <input name="filter[search_category][]" value="16" type="checkbox" class="check" checked />&nbsp;western
      <br />
      <input name="filter[search_category][]" value="17" type="checkbox" class="check" checked />&nbsp;mystery
      <br />
      <input name="filter[search_category][]" value="18" type="checkbox" class="check" checked />&nbsp;war
      <br />
      <input name="filter[search_category][]" value="19" type="checkbox" class="check" checked />&nbsp;tribute
      <br />
      <input name="filter[search_category][]" value="12" type="checkbox" class="check" checked />&nbsp;political
      <br />
      <br />
      <br />
      <input name="filter[rating_symbol][]" value="M" type="checkbox" class="check" checked />&nbsp;mature
      <br />
      <input name="filter[rating_symbol][]" value="A" type="checkbox" class="check" checked />&nbsp;adult
    </td>
  </tr>
  <tr>
    <td style="text-align:center;"><input type="button" id="select-button" class="button" value="select all" /></td>
    <td style="text-align:center;"><input type="button" id="unselect-button" class="button" value="unselect all" /></td>
    <td style="text-align:right;"><input id="submitButton" class="button" type="button" value="Go!" /></td>
  </tr>
</table>
</form>
</div>
<div id="featured_holder" class="span-62 box-1" style="text-align:center;">
  <?php foreach ($featured as $comic) : ?>
  <?php 
  $date = new DateTime($comic['date']);
  ?>
    <a href="/<?php echo str_replace(' ', '_', $comic['title']); ?>/">
    <div class="rounded grid-panel" title="<?php echo "<span class='drunk'>" . htmlspecialchars($comic['title']) . "</span> by " . $comic['author'] . "<br /><span class='teal-words'>" . htmlspecialchars($comic['description']) . "</span>"; ?>">
      <div>
      <?php
      $path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
      ?>
        <img src="<?php echo $path; ?>" width="80" height="100" />
      </div>
      <br />
      <span style="color:#fff;"><?php echo $date->format('M j Y'); ?></span>
    </div>
    </a>
  <?php endforeach; ?>
</div>

<?php require_once('footer_base.php'); ?>