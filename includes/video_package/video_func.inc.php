<?
function addVideo( $user_id, $url, $width, $height, $title='', $description='', $type='')
{
  if ( $type == '' ) {
    $type = 'application/x-shockwave-flash';
  }

  if ( strlen($title) == 0 ) {
    $title = 'Untitled';
  }

  db_query("INSERT INTO pool_movies (user_id, url, width, height, title, description, popularity, movie_type) VALUES ('".(int)$user_id."', '".db_escape_string($url)."', '".(int)$width."', '".(int)$height."', '".db_escape_string($title)."', '".db_escape_string($description)."', '0', '".db_escape_string($type)."')");
  $movie_id = (int)db_get_insert_id();
  if ( $movie_id < 1 ) {
    $res = db_query("SELECT id FROM pool_movies WHERE url='".db_escape_string($url)."'");
    if ( $row = db_fetch_object($res) ) {
      $movie_id = $row->id;
    }
    db_free_result($res);
  }

  return $movie_id;
}

function grabVideo($user_id, $id, $title='', $description='')
{
  $ret = true;

  $res = db_query("SELECT id FROM pool_movies WHERE id='".(int)$id."'");
  if ( $row = db_fetch_object($res) )
  {
    db_query("INSERT INTO grabbed_movies (user_id, movie_id) VALUES ('".(int)$user_id."', '".$id."')");

    if ( db_rows_affected() < 1 ) {
      $ret = false;
    }
    else {
      db_query("UPDATE pool_movies SET popularity=popularity+1 WHERE id='".(int)$id."'");
      db_query("UPDATE grabbed_movies SET order_id=order_id+1 WHERE user_id='".(int)$user_id."'");
    }
  }

  if ( $title != '' || $description != '' ) {
    db_query("UPDATE grabbed_movies SET title='".db_escape_string($title)."', description='".db_escape_string($description)."' WHERE user_id='".$user_id."' AND movie_id='".$id."'");
  }

  db_free_result($res);

  return $ret;
}

function ungrabVideo($user_id, $id)
{
  $res = db_query("SELECT * FROM grabbed_movies WHERE user_id='".(int)$user_id."' AND movie_id='".(int)$id."'");
  if ( $row = db_fetch_object($res) )
  {
    db_query("UPDATE pool_movies SET popularity=popularity-1 WHERE id='".(int)$id."'");
    db_query("UPDATE grabbed_movies SET order_id=order_id-1 WHERE user_id='".(int)$user_id."' AND order_id>'".$row->order_id."'");
    db_query("DELETE FROM grabbed_movies WHERE user_id='".(int)$user_id."' AND movie_id='".(int)$id."'");

    db_query("DELETE FROM pool_movies WHERE id='".(int)$id."' AND popularity='0'");

    return true;
  }
  db_free_result($res);

  return false;
}

function scaleVideo( $origDimArr, $targetDimArr, $reference='width' )  // reference either width or height as the base for scaling.
{
  $width  = 0;
  $height = 1;

  $scale = $targetDimArr[$width] / $origDimArr[$width];

  return array(
               0=>floor( $scale * $origDimArr[$width] ),
               1=>floor( $scale * $origDimArr[$height] ),
               'width'=>floor( $scale * $origDimArr[$width] ),
               'height'=>floor( $scale * $origDimArr[$height] ),
               );
}
?>