<?
include('community_header.inc.php');


if ( !$id = create_post($_POST['tid'], $USER->user_id, $_POST['reply_txt']) )
{
  echo 'Your post did not go through. Please try again later.';
}
else
{
  updatePostCt($USER->user_id);
  echo 'Thank you for your post. Click <a href="view_topic.php?'.passables_query_string().'&pid='.$id.'#'.$id.'">here</a> to read your post.';

  include_once(WWW_ROOT.'/includes/trophies/trophy_data.inc.php');
  user_update_trophies( $USER, 13 );
}

?><meta http-equiv="refresh" content="2;url=view_topic.php?<?=passables_query_string()?>&pid=<?=$id?>#<?=$id?>">