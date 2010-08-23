<?
define('LEAN_AND_MEAN', 1);
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('includes/global.inc.php');

switch( (int)$_GET['fid'] )
{
  case 1:
    $F_NAME = 'DDAlerter_v1.zip';
  break;

  case 2:
    $F_NAME = 'DDAlerter_v1.msi';
  break;
  case 3:
    $F_NAME = 'DDGames.gadget';
  break;
  default:
    $F_NAME = 'DDAlerter_v1.msi';
  break;
}

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$F_NAME."\";");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize(WWW_ROOT."/downloadables/".$F_NAME));
readfile(WWW_ROOT."/downloadables/".$F_NAME);

db_query("UPDATE downloads SET counter=counter+1 WHERE filename='".$F_NAME."' AND ymd_date='".YMD."'");
if ( db_rows_affected() == 0 ) {
  db_query("INSERT INTO downloads (ymd_date, filename, counter) VALUES ('".YMD."', '".$F_NAME."', '1')");
}
?>