<?
/*
Passed In Vars:
tickerDuration_ms - 1000;
tickerPacket - "Buy beef today!|Fish is the new pink!|Buy merchandise here!=http://www.cafepress.com/drunkduck";
*/
$swf = new FlashMovie(IMAGE_HOST.'/swf/Ticker_static.swf', 640, 15);
$swf->setTransparent(true);
$swf->addVar('tickerDuration_ms', 3000);
if ( $USER )
{
  $TICKER_MESSAGES = array("Buy merchandise here!=http://www.cafepress.com/drunkduck",
                           "Tags allow readers or writers to use short words, phrases, or terms to describe a comic and its pages!");
}
else
{
  $TICKER_MESSAGES = array("- Over 3,000 comics! -",
                           "You must be registered as a member to comment, rank or post in the forums. Membership is FREE!=http://www.drunkduck.com/signup/");
}
$swf->addVar('tickerPacket', implode("|", $TICKER_MESSAGES));
$swf->showHTML();
?>