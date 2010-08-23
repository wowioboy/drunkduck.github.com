<?

$name = '';
$fwd  = false;

switch( (int)$_GET['in'] )
{
  case 1:
    $name = 'Corporate Main Page to DD';
    break;
  case 2:
    $name = 'Corporate Main Page to Hero By Night on DD';
    break;
  default:
      if ( $_GET['name'] ) {
        $name = trim($_GET['name']);
      }
      else {
        return;
      }
    break;
}

db_query("UPDATE click_tracking SET counter=counter+1 WHERE ymd_date='".date("Ymd")."' AND description='".db_escape_string($name)."'");
if ( db_rows_affected() < 1 ) {
  db_query("INSERT INTO click_tracking (ymd_date, description, counter) VALUES ('".date("Ymd")."', '".db_escape_string($name)."', '1')");
}

if ( isset($_GET['fwd']) ) {
  header( "Location: ".trim($_GET['fwd']) );
}
?>