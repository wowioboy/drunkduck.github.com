#!/usr/bin/php -q
<?
set_time_limit(0);
passthru('rsync -avz --delete /var/www/html/drunkduck.com/comics/* mwright@74.208.46.186:/var/www/html/drunkduck.com/comics');
?>