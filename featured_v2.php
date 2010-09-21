<?php require_once('header_v2.php'); ?>

<?php
$db = new DB();
$query = "select c.comic_name as title, u.username as author, c.rating_symbol as rating, c.total_pages as pages, c.description, count(l.page_id) as likes
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
?>
<script>
var pager = 0;
var pager_max = <?php echo $featuredCount; ?>;
var search = '';
  
$(document).ready(function(){
  
  function getFeatured() 
  {
    $.getJSON('/ajax/featured.php', {offset: pager, search: search}, function(data){
          var html = '';
          pager_max = data.count;
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
});
</script>
<div class="span-16">
  featured archive header
  <button class="featured_button" direction="prev">prev</button>
  <button class="featured_button" direction="next">next</button>
  search: <input id="featured_search" />
</div>
<div id="featured_holder" class="span-16">
  <?php foreach ($featured as $comic) : ?>
    <div class="post yellow">
      <span class="headline"><?php echo $comic['title']; ?></span>
      <br />
      <span class="subtitle">by <?php echo $comic['author']; ?> <?php echo $comic['rating']; ?>, <?php echo $comic['likes']; ?></span>
      <br />
      <p><?php echo $comic['description']; ?></p>
    </div>
    <hr class="space" />
  <?php endforeach; ?>
</div>
   <button class="featured_button" direction="prev">prev</button>
  <button class="featured_button" direction="next">next</button>

<?php require_once('footer_v2.php'); ?>