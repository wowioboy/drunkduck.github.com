#!/usr/bin/php -q
<?
set_time_limit(0);

passthru('rsync -avz --delete --exclude-from=/var/www/html/drunkduck.com/cronjobs/SPECIAL/EXCLUSIONS.txt /var/www/html/drunkduck.com/gfx/* mwright@74.208.46.186:/var/www/html/drunkduck.com/gfx');
?>