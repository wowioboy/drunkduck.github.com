<?php

    
    $entries = array();
    
        
    $game = new Game();
    $rankings = $game->getLatestSalesRanks(25);
     
    foreach ($rankings AS $ranking) {
        $entry = array(
           'title'       => "{$ranking->title}
                            ({$ranking->platform})",
           'link'        => "http://www.gamenomad.com/games/
                             {$ranking->asin}",
           'description' => "Sales Rank: #{$ranking->rank}",
        );
        array_push($entries, $entry);
    }
    
    $rss = array(
      'title'   => 'GameNomad: Popular Games',
      'link'    => 'http://www.gamenomad.com/games/ranks',
      'charset' => 'ISO-8859-1',
      'entries' => $entries
    );
    
    $feed = Zend_Feed::importArray($rss, 'rss');
    
    $rssFeed = $feed->saveXML();
    echo $rssFeed;
    
    
    
    
    
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

$query = "select c.comic_name as title 
from comics c 
inner join featured_comics f 
on f.comic_id = c.comic_id 
where f.approved = '1'
order by feature_id desc 
limit 24";
$featured = $db->fetchCol($query);
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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    