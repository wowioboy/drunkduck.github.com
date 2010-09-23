<?php require_once('header_base.php'); ?>

<?php
$db = new DB();
$query = "select c.comic_name as title, u.username as author, c.rating_symbol as rating, c.total_pages as pages, f.description, count(l.page_id) as likes
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
limit 10";
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
<script type="text/javascript">
var pager = 0;
var pager_max = <?php echo $featuredCount; ?>;
var search = '';
var month = '';
  
$(document).ready(function(){
  
  function getFeatured() 
  {
    $.getJSON('/ajax/featured.php', {offset: pager, search: search, month: month}, function(data){
          var html = '';
          pager_max = data.count;
          if (data.featured) {
        $.each(data.featured, function(){
          html += '<div class="post yellow">' + 
                     '<span class="headline">' + this.title + '</span>' + 
                     '<br />' + 
                     '<span class="subtitle">by ' + this.author + ' ' + this.rating + ', ' + this.likes + '</span>' + 
                     '<br />' +  
                     '<p>' + this.description + '</p>' + 
                     '</div>' + 
                     '<hr class="space" />';
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
      if (pager < pager_max - 10) {
        pager += 10;
        valid = true;
      }
    } else {
      if (pager > 0) {
        pager -= 10;  
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
  <button class="news_button rounded left button" direction="prev">previous</button>
  <select id="featureMonth" class="button">
    <option value="">Select Month</option>
    <?php foreach ($dateArray as $numDate => $dateString) : ?>
      <option value="<?php echo $numDate; ?>"><?php echo $dateString; ?></option>
    <?php endforeach; ?>
  </select>
  <button class="rounded button">list view</button>
    <input type="text" class="rounded button" style="color:#fff;" id="featured_search" />
  <button class="news_button rounded right button" direction="next">next</button>
</div>
<div id="featured_holder" class="span-62 box-1">
  <?php foreach ($featured as $comic) : ?>
    <div class="post teal rounded box-1">
    <div class="white">
      <span class="headline"><?php echo $comic['title']; ?></span>
      <br />
      <span class="subtitle">by <?php echo $comic['author']; ?> <?php echo $comic['rating']; ?>, <?php echo $comic['likes']; ?></span>
      <br />
      <p><?php echo $comic['description']; ?></p>
    </div>
    </div>
    <hr class="space" />
  <?php endforeach; ?>
</div>
   <button class="featured_button" direction="prev">prev</button>
  <button class="featured_button" direction="next">next</button>

<?php require_once('footer_base.php'); ?>
