Your Account has been FROZEN.

<BR>

Do not pass go.

<BR>

Do not collect $200.
<br>
<?

if ( ($GLOBALS['USER']->flags & FLAG_BANNED_PC) || $_GET['clearMe'] )
{
  $swf = new FlashMovie(IMAGE_HOST.'/swf/banMgr.swf', 30, 30);
  if ( $_GET['clearMe'] ) {
    $swf->addVar('banned', 'clearMe');
  }
  else {
    $swf->addVar('banned', 'true');
  }
  $swf->setTransparent(true);
  $swf->showHTML();
  unset($INCLUDE_BAN_SWF);
}
?>