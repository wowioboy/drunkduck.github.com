<?
  if ( !($USER->flags & FLAG_VERIFIED) )
  {
    echo "<SPAN CLASS='alert'>You must verify your account before you can add comics. <A HREF='JavaScript:history.back();'>Click here to go back and try again.</A></SPAN>";
    return;
  }

  if ( !isset($_POST['newComicName']) ) {
    echo("<SPAN CLASS='alert'>There was an error in your request. <A HREF='JavaScript:history.back();'>Click here to go back and try again.</A></SPAN>");
    return;
  }

  if ( preg_match('/([^a-zA-Z0-9 ])+/', $_POST['newComicName']) ) {
    echo("<SPAN CLASS='alert'>Your comic name had invalid characters. Please only use A-Z, 0-9, and SPACES. <A HREF='JavaScript:history.back();'>Click here to go back and try again.</A></SPAN>");
    return;
  }

  if ( !isset($_POST['newComicDescription']) ) {
    echo("<SPAN CLASS='alert'>There was an error in your request. <A HREF='JavaScript:history.back();'>Click here to go back and try again.</A></SPAN>");
    return;
  }

  if ( !isset($_POST['newComicType']) || !isset($COMIC_STYLES[$_POST['newComicType']]) ) {
    echo("<SPAN CLASS='alert'>There was an error in your request. <A HREF='JavaScript:history.back();'>Click here to go back and try again.</A></SPAN>");
    return;
  }

  if ( !isset($_POST['newComicSubCat']) || !isset($COMIC_SUBCATS[$_POST['newComicSubCat']]) ) {
    echo("<SPAN CLASS='alert'>There was an error in your request. <A HREF='JavaScript:history.back();'>Click here to go back and try again.</A></SPAN>");
    return;
  }

  $comic_name   = db_escape_string( trim($_POST['newComicName']) );
  $folder_name  = comicNameToFolder($comic_name);
  $description  = strip_tags( db_escape_string( trim($_POST['newComicDescription']) ) );
  $comic_type   = $_POST['newComicType'];
  $comic_cat    = $_POST['newComicCat'];
  $comic_subcat = $_POST['newComicSubCat'];
  if ( isset($RATINGS[$_POST['newRating']]) ) {
    $comic_rating = $_POST['newRating'];
  }
  else {
    $comic_rating = 'E';
  }

  if ( strlen($comic_name) < 3 ) {
    echo("<SPAN CLASS='alert'>The comic name must be at least 3 characters long! <A HREF='JavaScript:history.back();'>Click here to go back and try again.</A></SPAN>");
    return;
  }

  $res = db_query("SELECT comic_name FROM comics WHERE comic_name='".$comic_name."'");
  if ( db_num_rows($res) > 0 ) {
    db_free_result($res);
    echo("<SPAN CLASS='alert'>That comic name is already taken! <A HREF='JavaScript:history.back();'>Click here to go back and try again.</A></SPAN>");
    return;
  }
  db_free_result($res);

  if ( file_exists(WWW_ROOT.'/'.$folder_name) || file_exists(WWW_ROOT.'/comics/'.$folder_name{0}.'/'.$folder_name) ) {
    echo("<SPAN CLASS='alert'>\"".$folder_name . "\" is a taken or reserved name. <A HREF='JavaScript:history.back();'>Click here to go back and try again.</A></SPAN>");
    return;
  }

  if ( !mkdir(WWW_ROOT.'/comics/'.$folder_name{0}.'/'.$folder_name) )
  {
    echo("<SPAN CLASS='alert'>There was an error in your request. Please report this to an administrator for correction. <A HREF='JavaScript:history.back();'>Click here to go back and try again.</A></SPAN>");
    return;
  }
  mkdir(WWW_ROOT.'/comics/'.$folder_name{0}.'/'.$folder_name.'/pages');
  mkdir(WWW_ROOT.'/comics/'.$folder_name{0}.'/'.$folder_name.'/gfx');
  mkdir(WWW_ROOT.'/comics/'.$folder_name{0}.'/'.$folder_name.'/html');

  copy(WWW_ROOT.'/comics/resource_files/index.bak', WWW_ROOT.'/comics/'.$folder_name{0}.'/'.$folder_name.'/index.php');

  db_query("INSERT INTO comics (user_id, comic_name, comic_type, category, subcategory, description, created_timestamp, search_style, search_category, search_category_2) VALUES ('".$USER->user_id."', '".$comic_name."', '".$comic_type."', '".$comic_cat."', '".$comic_subcat."', '".$description."', '".time()."', '".(int)$_POST['search_style']."', '".(int)$_POST['search_category']."', '".(int)$_POST['search_category_2']."')");
  header('Location: http://'.DOMAIN.'/account/overview/');


  echo "The new comic has been added to your account! Head back to start adding to it!";
?>