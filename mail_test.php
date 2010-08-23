<?php
//mwright: I created this to test DNS and relay issues with mail not going through.
$headers = 'From: support@drunkduck.com'; 
//mail("mlwtheta@adelphia.net", "testing 4", "blah", $headers); 
mail("enovak@wowio.com", "testing 4", "blah", $headers); 

?>

