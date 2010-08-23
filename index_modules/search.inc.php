<form action="http://<?=DOMAIN?>/browse.php" method="POST">
<div class="searchbox" style="background-image: url(<?=IMAGE_HOST?>/site_gfx_new/searchbox.gif);	background-repeat: no-repeat;">
  <input name="find" value="enter your search here" type="text" size="40" maxlength="200" class="searchform" onFocus="if ( this.value == 'enter your search here' ){this.value='';}" /><label><input type="submit" name="search" value="Go!" class="searchbutton" /></label>
  <a href="http://<?=DOMAIN?>/browse.php" class="searchlink">Advanced Search</a>
</div>
</form>