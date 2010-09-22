<?php require_once('header_base.php'); ?>

<?php
$db = new DB();
$query = "select b.title, u.username as author, b.body, from_unixtime(b.timestamp_date) as created_on
from admin_blog b 
left join users u 
on u.user_id = b.user_id 
order by created_on desc 
limit 5";
$news = $db->fetchAll($query);
$query = "select count(1) from admin_blog";
$newsCount = $db->fetchOne($query);
?>
<script>
var pager = 0;
var pager_max = <?php echo $newsCount; ?>;
var search = '';
  
$(document).ready(function(){
  $('.expand-button').live('click', function(){
    var entry = $(this).attr('entry');
    var p = $('p[entry=' + entry + ']');
    if (p.css('display') == 'none') {
      $(this).html('collapse');
      p.slideDown();
    } else {
      $(this).html('expand');
      p.slideUp();
    }
  });
  
  function getNews() 
  {
    $.getJSON('/ajax/news.php', {offset: pager, search: search}, function(data){
          var i = 0;
          var html = '';
          pager_max = data.count;
        $.each(data.news, function(){
          html += '<div class="post yellow rounded">' + 
                     '<span class="headline">' + this.title + 
                     '<a href="javascript:" class="expand-button teal" entry="' + i + '">expand</a></span>' + 
                     '<br />' + 
                     '<span class="subtitle">posted by ' + this.author + '</span>' + 
                     '<br />' + 
                     '<span>' + this.created_on + '</span>' + 
                     '<p style="display:none;" entry="' + i + '">' + this.body + '</p>' + 
                     '</div>' + 
                     '<hr class="space" />';
          i++;
        });
        $('#news_holder').html(html);
      });
  }
  
  $('#news_search').keyup(function(){
    search = $(this).val();
    pager = 0;
    getNews();
  });
  
  $('.news_button').click(function() {
    var valid = false;
    var dir = $(this).attr('direction');
    if (dir == 'next') {
      if (pager < pager_max - 5) {
        pager += 5;
        valid = true;
      }
    } else {
      if (pager > 0) {
        pager -= 5;  
        valid = true;
      }
    }
    if (valid) {
      getNews();
    }
  });
});
</script>
        <div class="rounded canary span-63 box-1 pull-1">
            <div class="span-63 green rounded header">
            News Archive
            </div>
        </div>
<div class="span-64 box-1 header-menu">
  <button class="news_button rounded left button" direction="prev">previous</button>
  <button class="rounded button dropdown">September 2010</button>
  <button class="rounded button">article view</button>
  <button class="rounded button" style="padding-top:0;padding-bottom:0">
    <input style="background-color:transparent;border:0;color:#FFF" id="news_search" value="search articles" onfocus="this.value=''" onblur="this.value='search articles'"/>
  </button>
  <button class="news_button rounded right button" direction="next">next</button>
</div>
<div id="news_holder" class="span-62 box-1">
  <?php foreach ($news as $i => $entry) : ?>
    <div class="post yellow rounded">
      <a href="javascript:" class="expand-button teal" entry="<?php echo $i; ?>">expand</a>
      <span class="headline"><?php echo $entry['title']; ?></span>
      <br />
      <span class="subtitle">posted by <?php echo $entry['author']; ?></span>
      <br />
      <span><?php echo $entry['created_on']; ?></span>
      <p style="display:none;" entry="<?php echo $i; ?>"><?php echo bbcode2html($entry['body']); ?></p>
    </div>
    <div style="height:10px;display:block;"></div>
  <?php endforeach; ?>
</div>
   <button class="news_button" direction="prev">prev</button>
  <button class="news_button" direction="next">next</button>

<?php require_once('footer_base.php'); ?>
