<?php 
$REQUIRE_LOGIN = false;
$ADMIN_ONLY    = false;
$TITLE         = 'News';
require_once('header_base.php'); ?>

<?php
$db = new DB();
$query = "select b.title, u.username as author, b.body, from_unixtime(b.timestamp_date) as created_on
from admin_blog b 
left join users u 
on u.user_id = b.user_id 
where b.body is not null
order by b.timestamp_date desc 
limit 10";
$news = $db->fetchAll($query);
$query = "select count(1) from admin_blog";
$newsCount = $db->fetchOne($query);
$query = "select date_format(from_unixtime(max(timestamp_date)), '%Y-%m-01') as max, date_format(from_unixtime(min(timestamp_date)), '%Y-%m-01') as min 
          from admin_blog";
$newsmaxmin = $db->fetchRow($query);
$min = new DateTime($newsmaxmin['min']);
$max = new DateTime($newsmaxmin['max']);
$dateArray = array($min->format('Y-m') => $min->format('M Y'));
do {
  $min->modify('+1 month');
  $dateArray[$min->format('Y-m')] = $min->format('M Y');
} while ($min->format('Y-m') != $max->format('Y-m'));
$dateArray = array_reverse($dateArray);
?>
<script type="text/javascript">
var pager = 0;
var pager_max = <?php echo $newsCount; ?>;
var search = '';
var month = '';
  
$(document).ready(function(){
 //$('select').selectmenu();
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
    $.getJSON('/ajax/news.php', {offset: pager, search: search, month: month}, function(data){
          var i = 0;
          var html = '';
          pager_max = data.count;
          if (data.news) {
        $.each(data.news, function(){
          html += '<div class="post yellow rounded box-1">' + 
                      '<a href="javascript:" class="expand-button teal" entry="' + i + '">expand</a>' + 
                      '<span class="headline">' + this.title + '</span>' + 
                      '<br />' + 
                      '<span class="subtitle">' + this.author + '</span>' + 
                      '<br />' + 
                      '<span>' + this.created_on + '</span>' + 
                      '<p style="display:none;" entry="' + i + '">' + this.body + '</p>' + 
                      '</div>' + 
                      '<div style="height:10px;display:block;"></div>';
          i++;
        });
          }
        $('#news_holder').html(html);
      });
  }
  
  $('.news_search').keyup(function(){
    search = $(this).val();
    $('.news_search').val(search);
    pager = 0;
    getNews();
  });
  
  $('.news_search').live('click', function(){
    $('.news_search').val('');
    $('.news_search').die('click');
  });
  
  $('.news_button').click(function() {
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
      getNews();
    }
  });
  
  $('.newsMonth').change(function(){
    month = $(this).val();
    $('.newsMonth').val(month);
    getNews();
  });
});
</script>
<style>
.news_search {
  background-image:url('/media/images/blue-search-box.png');
  width:135px;
}
</style>
        <div class="rounded canary span-63 box-1 pull-1">
            <div class="span-63 dark-green rounded header">
            <img src="/media/images/news.png" />
            </div>
<div class="span-61 box-1 header-menu">
  <div style="float:left;">
  <button class="news_button rounded left button" direction="prev">previous</button>
  <select class="button rounded newsMonth" style="border:none;">
    <option value="">select month</option>
    <?php foreach ($dateArray as $numDate => $dateString) : ?>
      <option value="<?php echo $numDate; ?>"><?php echo $dateString; ?></option>
    <?php endforeach; ?>
  </select>
  </div>
  <div style="float:right;">
    <input class="rounded button news_search" style="color:#fff;" value="search news archive" />
  <button class="news_button rounded right button" direction="next">next</button>
  </div>
</div>
        </div>
<div id="news_holder" class="span-60 box-2">
  <?php foreach ($news as $i => $entry) : ?>
    <?php
    $date = new DateTime($entry['created_on']);
    ?>
    <div class="post yellow rounded box-1">
      <a href="javascript:" class="expand-button teal" entry="<?php echo $i; ?>">expand</a>
      <span class="headline"><?php echo $entry['title']; ?></span>
      <br />
      <span class="subtitle">posted by <?php echo $entry['author']; ?></span>
      <br />
      <span><?php echo $date->format('F j, Y - g:ia'); ?></span>
      <p style="display:none;" entry="<?php echo $i; ?>"><?php echo bbcode2html($entry['body']); ?></p>
    </div>
    <div style="height:10px;display:block;"></div>
  <?php endforeach; ?>
</div>
<div class="box-2">
<div style="float:left;">
  <button class="news_button rounded left button" direction="prev">previous</button>
  </div>
  <div style="float:right;">
  <button class="news_button rounded right button" direction="next">next</button>
  </div>
  <div style="clear:both;"></div>
</div>

<?php require_once('footer_base.php'); ?>
