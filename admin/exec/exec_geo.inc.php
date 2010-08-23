<?
include(WWW_ROOT.'/signup/signup_data.inc.php');

$res = db_query("SELECT country, state FROM demographics");
while($row = db_fetch_object($res))
{
  $TOTAL++;
  $COUNTRY_DATA[$row->country]++;
  $STATE_DATA[$row->state]++;
}

arsort($STATE_DATA, SORT_DESC);
arsort($COUNTRY_DATA, SORT_DESC);

foreach($STATE_DATA as $key=>$value) {
  if ( ++$ct==11) break;
  echo $ARRAY_STATES[$key] . " = " . $value. " (".floor(($value/$TOTAL)*100)."%) <BR>";
}

echo "<BR><BR>";
$ct =0;
foreach($COUNTRY_DATA as $key=>$value) {
  if ( ++$ct==11) break;
  echo $ARRAY_COUNTRIES[$key] . " = " . $value. " (".floor(($value/$TOTAL)*100)."%) <BR>";
}
?>
