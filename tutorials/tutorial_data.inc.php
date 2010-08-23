<?
$PER_PAGE = 25;


function delete_tutorial( $tutorial_id )
{
  $tutorial_id = (int)$tutorial_id;

  $tRes = db_query("SELECT * FROM tutorials WHERE tutorial_id='".$tutorial_id."'");
  if ( $tRow = db_fetch_object($tRes) )
  {

    delete_tags( $tutorial_id );

    $imageRes = db_query("SELECT * FROM tutorial_images WHERE tutorial_id='".$tutorial_id."'");
    while( $imageRow = db_fetch_object($imageRes) ) {
      delete_tutorial_image( $imageRow->image_id );
    }
    db_free_result($imageRes);



    db_query("DELETE FROM tutorial_votes WHERE tutorial_id='".$tutorial_id."'");

    db_query("DELETE FROM tutorials WHERE tutorial_id='".$tutorial_id."'");
  }
}

function delete_tutorial_image( $image_id )
{
  $imageRes = db_query("SELECT * FROM tutorial_images WHERE image_id='".(int)$image_id."'");
  if( $imageRow = db_fetch_object($imageRes) )
  {
    $padded_id = str_pad( $imageRow->image_id, 2, '0', STR_PAD_LEFT);
    unlink( WWW_ROOT.'/gfx/tutorials/content/'.(int)($padded_id{0}).'/'.(int)($padded_id{1}).'/'.$imageRow->tutorial_id.'_'.$imageRow->image_id.'.'.$imageRow->file_ext );
    db_query("DELETE FROM tutorial_images WHERE image_id='".(int)$image_id."'");
  }
}

function delete_tags( $tutorial_id )
{
  $tutorial_id = (int)$tutorial_id;
  $tagRes = db_query("SELECT * FROM tutorial_tags WHERE tutorial_id='".$tutorial_id."'");
  while( $tagRow = db_fetch_object($tagRes) )
  {
    db_query("UPDATE tutorial_tags_used SET counter=counter-1 WHERE tag='".db_escape_string($tagRow->tag)."'");
  }
  db_free_result($tagRes);
  db_query("DELETE FROM tutorial_tags WHERE tutorial_id='".$tutorial_id."'");
}

?>