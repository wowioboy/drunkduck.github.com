<?
if ( !isset($_POST['cid']) ) return;

$CID = (int)$_POST['cid'];

if ( !isset($COMIC_CATS[$_POST['comicCat']]) ) return;
if ( !isset($COMIC_SUBCATS[$_POST['comicSubCat']]) ) return;
if ( !isset($COMIC_STYLES[$_POST['comicType']]) ) return;

$description      = strip_tags(db_escape_string($_POST['comicDescription']));
$description_long = strip_tags(db_escape_string($_POST['comicDescription_long']));

db_query("UPDATE comics SET comic_type='".$_POST['comicType']."', category='".$_POST['comicCat']."', subcategory='".$_POST['comicSubCat']."', description='".$description."', description_long='".$description_long."', writer_name='".db_escape_string($_POST['writer_name'])."', illustrator_name='".db_escape_string($_POST['illustrator_name'])."', editor_name='".db_escape_string($_POST['editor_name'])."' WHERE comic_id='".$CID."' AND (user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."')");

header('Location: http://'.DOMAIN.'/account/edit_comic.php?cid='.$CID);
?>