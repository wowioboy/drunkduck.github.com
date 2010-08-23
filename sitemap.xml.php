<?
define('DEBUG_MODE', 0);
include('includes/global.inc.php');
echo '<';
?>?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
 <url>
  <loc>http://<?=DOMAIN?></loc>
  <lastmod><?=date("Y-m-d")?></lastmod>
  <changefreq>always</changefreq>
  <priority>1.0</priority>
 </url>
 <url>
  <loc>http://<?=DOMAIN?>/community/</loc>
  <lastmod><?=date("Y-m-d")?></lastmod>
  <changefreq>always</changefreq>
  <priority>1.0</priority>
 </url>
<?
$res = db_query("SELECT * FROM comics WHERE total_pages>0 ORDER BY comic_id ASC");
while( $row = db_fetch_object($res) )
{
  ?><url>
  <loc>http://<?=DOMAIN?>/<?=comicNameToFolder($row->comic_name)?>/</loc>
  <lastmod><?=date("Y-m-d", $row->last_update)?></lastmod>
  <changefreq>hourly</changefreq>
  <priority>0.9</priority>
 </url><?
}

$res = db_query("SELECT * FROM users ORDER BY user_id ASC");
while ($row = db_fetch_object($res) )
{
  ?><url>
  <loc>http://<?=DOMAIN?>/user/<?=rawurlencode($row->username)?></loc>
  <lastmod><?=date("Y-m-d")?></lastmod>
  <changefreq>daily</changefreq>
  <priority>0.8</priority>
 </url><?
}
?>
</urlset>