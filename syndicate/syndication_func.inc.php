<?

function create_client( $email, $password, $comic_id, $url )
{
  db_query("INSERT INTO syndication_clients (client_email, client_password, comic_id, url, position, rerun_position, ymd_date) VALUES ('".db_escape_string($email)."', '".db_escape_string($password)."', '".(int)$comic_id."', '".db_escape_string($url)."', '0', '0', '0')");
  $ID = db_get_insert_id();

  $res = db_query("UPDATE syndication_clients SET client_key='".gen_key($ID)."' WHERE client_id='".$ID."'");

  return gen_key($ID);
}

function gen_key( $client_id )
{
  return md5('dd' . $client_id . 'syndication');
}

function advance_page( $client_id, $comic_id, $current_position )
{
  $res = db_query("SELECT * FROM comic_pages WHERE order_id>'".(int)$current_position."' ORDER BY order_id ASC LIMIT 1");
  if ( $row = db_fetch_object($res) ) {
    db_query("UPDATE syndication_clients SET position='".$row->order_id."', ymd_date='".date("Ymd")."' WHERE client_id='".(int)$client_id."' AND comic_id='".(int)$comic_id."'");
    return $row->order_id;
  }

  // else
  $res = db_query("SELECT * FROM comic_pages ORDER BY order_id ASC LIMIT 1");
  if ( $row = db_fetch_object($res) ) {
    db_query("UPDATE syndication_clients SET position='".$row->order_id."', ymd_date='".date("Ymd")."' WHERE client_id='".(int)$client_id."' AND comic_id='".(int)$comic_id."'");
    return $row->order_id;
  }

  return 0;
}


?>