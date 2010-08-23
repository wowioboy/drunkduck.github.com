<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');

if(!function_exists('str_ireplace')) {
   function str_ireplace($search, $replacement, $string){
       $delimiters = array(1,2,3,4,5,6,7,8,14,15,16,17,18,19,20,21,22,23,24,25,
       26,27,28,29,30,31,33,247,215,191,190,189,188,187,186,
       185,184,183,182,180,177,176,175,174,173,172,171,169,
       168,167,166,165,164,163,162,161,157,155,153,152,151,
       150,149,148,147,146,145,144,143,141,139,137,136,135,
       134,133,132,130,129,128,127,126,125,124,123,96,95,94,
       63,62,61,60,59,58,47,46,45,44,38,37,36,35,34);
       foreach ($delimiters as $d) {
           if (strpos($string, chr($d))===false){
               $delimiter = chr($d);
               break;
           }
       }
       if (!empty($delimiter)) {
           return preg_replace($delimiter.quotemeta($search).$delimiter.'i', $replacement, $string);
       }
       else {
           trigger_error('Homemade str_ireplace could not find a proper delimiter.', E_USER_ERROR);
       }
   }
}




if ( isset($_GET['try']) )
{
  $tryName = trim(db_escape_string( $_GET['try'] ));

  if ( strlen($tryName) < 1 ) die;
  if ( strlen($tryName) < 3 ) die("Must be 3 or more characters.");

  $res = db_query("SELECT comic_name FROM comics WHERE comic_name LIKE '%".$tryName."%'");
  while ( $row = db_fetch_object($res) ) {
    ?><a href="#" onClick="insertFindComic('<?=$row->comic_name?>');return false;"><?=str_ireplace($_GET['try'], '<font color="red">'.$_GET['try'].'</font>', $row->comic_name)?></a><br><?
  }
  db_free_result($res);
}


















?>