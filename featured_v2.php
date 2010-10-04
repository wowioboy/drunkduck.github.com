<?php require_once('header_base.php'); ?>

<?php
if (!$view = $_REQUEST['view']) {
  $view = 'list'; 
}
if ($view == 'grid') {
  $limit = 25;
} else {
  $limit = 8;
}

$db = new DB();
$query = "select c.comic_id as id, c.comic_name as title, u.username as author, c.rating_symbol as rating, c.total_pages as pages, f.description, count(l.page_id) as likes
from comics c 
inner join featured_comics f 
on f.comic_id = c.comic_id 
inner join users u 
on u.user_id = c.user_id 
left join comic_pages p 
on c.comic_id = p.comic_id 
left join page_likes l 
on p.page_id = l.page_id
where f.approved = '1' 
group by c.comic_name
order by f.feature_id desc 
limit $limit";
$featured = $db->fetchAll($query);
$query = "select count(1)
from comics c 
inner join featured_comics f 
on f.comic_id = c.comic_id 
where f.approved = '1'";
$featuredCount = $db->fetchOne($query);
$query = "select concat(substring(max(ymd_date_live), 1, 4), '-', substring(max(ymd_date_live), 5, 2)) as max, concat(substring(min(ymd_date_live), 1, 4), '-', substring(min(ymd_date_live), 5, 2)) as min 
          from featured_comics 
          where ymd_date_live != 0 and ymd_date_live is not null";
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
var search = '';
var month = '';
var limit = <?php echo $limit; ?>;
  
$(document).ready(function(){
  
  function getFeatured() 
  {
    $.getJSON('/ajax/featured.php', {offset: pager, search: search, month: month, limit: limit}, function(data){
          var html = '';
          pager_max = data.count;
          if (data.featured) {
        $.each(data.featured, function(){
          html += '<a href="http://www.drunkduck.com/' + this.title.replace(/ /g, '_') + '">';
          <?php if ($view == 'list') : ?>
          $path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
            html += '<div class="post teal rounded box-1" style="background-color:#45B4B9;">' + 
                    '<div class="white rounded box-1" style="background-color:#FFF">' + 
                    '<div class="table fill">' + 
                    '<div class="cell middle" style="width:100px;">' + 
                    '<img src="http://images.drunkduck.com/process/comic_' + this.id + '_0_T_0_sm.jpg" />' + 
                    '</div>' + 
                    '<div class="cell middle">' + 
                    '<div class="table fill">' + 
                    '<div class="cell">' + 
                    '<span class="headline">' + this.title + '</span> <span class="subtitle">by ' + this.author + '</span>' + 
                    '</div>' + 
                    '<div class="cell right">' + 
                    '<span class="subtitle">' + this.rating + ', ' + this.pages + ' pages</span>' + 
                    '</div>' + 
                    '</div>' + 
                    '<p>' + this.description + '</p>' + 
                    '</div>' + 
                    '</div>' + 
                    '</div>' + 
                 //   '<span style="color:#fff;">' + this.likes + ' people like this comic</span>' + 
                    '</div>' + 
                    '<div style="height:10px;"></div>'; 
          <?php else: ?>
            var attributes = '';
            $.each(this, function(key, value) {
              attributes += ' ' + key + '="' + value + '" ';
            });            
            html += '<div class="rounded grid-panel" ' + attributes + '>' + 
                    '<div>' + 
                    '<img src="http://images.drunkduck.com/process/comic_' + this.id + '_0_T_0_sm.jpg" />' + 
                    '</div>' + 
                 //   '<br />' + 
                 //   '<span>' + this.likes + ' likes</span>' + 
                    '</div>';
          <?php endif; ?>
        html += '</a>';
        });
          }
        $('#featured_holder').html(html);
      });
  }
  
  $('#featured_search').keyup(function(){
    search = $(this).val();
    pager = 0;
    getFeatured();
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
      getFeatured();
    }
  });
  
  $('#featureMonth').change(function(){
    month = $(this).val();
    getFeatured();
  });
});
</script>
        <div class="rounded canary span-63 box-1 pull-1">
            <div class="span-63 green rounded header">
            Featured Comics Archive
            </div>
        </div>
<div class="span-64 box-1 header-menu">
  <button class="featured_button rounded left button" direction="prev">previous</button>
  <select id="featureMonth" class="button rounded" style="border:none;">
    <option value="">Select Month</option>
    <?php foreach ($dateArray as $numDate => $dateString) : ?>
      <option value="<?php echo $numDate; ?>"><?php echo $dateString; ?></option>
    <?php endforeach; ?>
  </select>
  <form style="display:inline;" method="post">
  <input type="hidden" name="view" value="<?php echo ($view == 'grid') ? 'list' : 'grid'; ?>" />
  <input type="submit" class="rounded button" value="<?php echo ($view == 'grid') ? 'list' : 'grid'; ?> view" />
  </form>
    <input type="text"  style="color:#fff;" id="featured_search" class="rounded button" />
  <button class="featured_button rounded right button" direction="next">next</button>
</div>
<div id="featured_holder" class="span-62 box-1" <?php echo ($view == 'grid') ? 'style="text-align:center;"' : ''; ?>>
  <?php foreach ($featured as $comic) : ?>
    <a href="http://www.drunkduck.com/<?php echo str_replace(' ', '_', $comic['title']); ?>">
    <?php if ($view == 'list') : ?>
    <div class="post teal rounded box-1" style="background-color:#45B4B9;">
    <div class="white rounded box-1" style="background-color:#FFF">
      <div class="table fill">
      <div class="cell middle" style="width:100px;">
      <?php
      $path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
      ?>
    <img src="<?php echo $path; ?>" />
      </div>
            <div class="cell middle">
            <div class="table fill">
       <div class="cell">
      <span class="headline"><?php echo $comic['title']; ?></span> <span class="subtitle">by <?php echo $comic['author']; ?></span>
       </div>
       <div class="cell right">
      <span class="subtitle"><?php echo $comic['rating']; ?>, <?php echo $comic['pages']; ?> pages</span>
       </div>
            </div>
      <p><?php echo $comic['description']; ?></p>
      </div>
      </div>
    </div>
   <!-- <span style="color:#fff;"><?php echo $comic['likes']; ?> people like this comic</span> -->
    </div>
    <div style="height:10px;"></div>
    <?php else: ?>
    <?php
    $attributes = '';
    foreach ($comic as $attr => $value) {
      $attributes .= " $attr=\"$value\" ";
    }
    ?>
    <div class="rounded grid-panel" <?php echo $attributes; ?>>
      <div>
      <?php
      $path = "http://images.drunkduck.com/process/comic_{$comic['id']}_0_T_0_sm.jpg";
      ?>
        <img src="<?php echo $path; ?>" />
      </div>
        <!-- 
        <br />
        <span><?php echo $comic['likes']; ?> likes</span> 
        -->
    </div>
    <?php endif; ?>
    </a>
  <?php endforeach; ?>
</div>
  <button class="featured_button rounded left button" direction="prev">previous</button>
  <button class="featured_button rounded right button" direction="next">next</button>

<?php require_once('footer_base.php'); ?>
