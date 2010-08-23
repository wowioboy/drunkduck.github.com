<?
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../../../includes/global.inc.php');

if ( !$USER ) return;


$res = db_query("SELECT * FROM comics WHERE comic_id='".(int)$_POST['comic_id']."'");
if ( !$COMIC_ROW = db_fetch_object($res) ) return;


$GLOBALS['PDF_TITLE'] = $_POST['pdf_title'];


$HTML = '<html><body style="font-size:18px;" align="left">
<script type="text/php">
  if ( isset($pdf) ) {

    $font = Font_Metrics::get_font("verdana", "normal");
    $pdf->page_text(0, 0, "'.$_POST['pdf_title'].'     Page {PAGE_NUM} of {PAGE_COUNT}     -     (c)'.date("Y").' Platinum Studios, Inc.", $font, 8, array(0,0,0));

  }
</script>';



$res = db_query("SELECT * FROM comic_pages WHERE comic_id='".$COMIC_ROW->comic_id."' AND page_id IN ('".implode("','", $_POST['pages'])."') ORDER BY order_id ASC");

while( $PAGE_ROW = db_fetch_object($res) ) {
  $HTML .= '<div align="left"><img src="http://'.COMIC_DOMAIN.'/'.comicNameToFolder($COMIC_ROW->comic_name).'/pages/'.md5($COMIC_ROW->comic_id.$PAGE_ROW->page_id).'.'.$PAGE_ROW->file_ext.'"></div>';
  if ( $_POST['new_page_every_image'] == 1 ) {
    $HTML .= '<div style="page-break-before: always;"></div> ';
  }
}

$HTML .= '</body></html>';


write_file($HTML, dirname(__FILE__).'/bin/tmp/pdf_file.html');


exec('/usr/local/php5/bin/php '.dirname(__FILE__).'/bin/generate_pdf.bin.php5', $return);


$F_NAME               = 'pdf_file.pdf';
$F_PATH               = dirname(__FILE__).'/bin/tmp/'.$F_NAME;


if ( !file_exists($F_PATH) ) {
  die($F_PATH.' does not exist.');
}

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$F_NAME."\";");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($F_PATH));
readfile($F_PATH);

unlink($F_PATH);
?>