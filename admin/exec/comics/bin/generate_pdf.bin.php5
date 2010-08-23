<?
ini_set('memory_limit',       '128M');
ini_set('max_execution_time', 0);

include_once('/home/mwright/sites/drunkduck.com/includes/packages/pdf_package/load.inc.php');

$dompdf = new DOMPDF();

$dompdf->load_html( implode('', file(dirname(__FILE__).'/tmp/pdf_file.html')) );

$dompdf->render();


$pdf = $dompdf->output();


file_put_contents(dirname(__FILE__).'/tmp/pdf_file.pdf', $pdf);





?>