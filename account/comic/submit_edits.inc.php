<?
if ( !isset($_POST['cid']) ) return;

$CID = (int)$_POST['cid'];

if ( !isset($COMIC_STYLES[$_POST['comicType']]) ) return;
$description      = strip_tags(db_escape_string($_POST['comicDescription']));
$description_long = strip_tags(db_escape_string($_POST['comicDescription_long']));

if ( !isset($COMIC_ART_STYLES[$_POST['search_style']]) ) return;
if ( !isset($COMIC_CATEGORIES[$_POST['search_category']]) ) return;
if ( !isset($COMIC_CATEGORIES[$_POST['search_category_2']]) ) return;

db_query("UPDATE comics SET comic_type='".$_POST['comicType']."', description='".$description."', description_long='".$description_long."', writer_name='".db_escape_string($_POST['writer_name'])."', illustrator_name='".db_escape_string($_POST['illustrator_name'])."', editor_name='".db_escape_string($_POST['editor_name'])."', language='".db_escape_string($_POST['lang'])."', subcategory='".(int)$_POST['newComicSubCat']."', search_style='".(int)$_POST['search_style']."', search_category='".(int)$_POST['search_category']."', search_category_2='".(int)$_POST['search_category_2']."' WHERE comic_id='".$CID."' AND (user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."')");

header('Location: http://'.DOMAIN.'/account/comic/?cid='.$CID);
?>