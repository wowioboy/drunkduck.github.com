<?

if ( isset($TOPIC_VIEWING_ROW) ) {
  // Mark this time as viewed.
  $COMMUNITY_SESSION->viewed_topics[$TOPIC_VIEWING_ROW->category_id][$TOPIC_VIEWING_ROW->topic_id] = time();
}


$ENCODED_SESSION = gzcompress(serialize($COMMUNITY_SESSION), 1);
if ( $ENCODED_SESSION != $COMMUNITY_SESSION_CKSUM )
{
  $ENCODED_SESSION = gzcompress(serialize($COMMUNITY_SESSION), 1);

  if ( $USER ) {
    db_query("UPDATE community_sessions SET encoded_data='".db_escape_string($ENCODED_SESSION)."' WHERE user_id='".$USER->user_id."'");
    if ( db_rows_affected() < 1 ) {
      db_query("INSERT INTO community_sessions (user_id, encoded_data) VALUES ('".$USER->user_id."', '".db_escape_string($ENCODED_SESSION)."')");
    }

    ?><!--Encoded Session Length = <?=strlen($ENCODED_SESSION)?>--><?
  }
}
?>
</div>