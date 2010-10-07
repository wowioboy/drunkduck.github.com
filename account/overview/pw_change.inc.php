<?


if ( $USER->password == $_POST['pw_now'] )
{
  if ( ($_POST['pw_change_confirm'] == $_POST['pw_change']) && (strlen($_POST['pw_change']) <= 20) )
  {
    db_query("UPDATE users SET password='".db_escape_string($_POST['pw_change'])."' WHERE username='".db_escape_string($USER->username)."'");
  }
}

header("Location: /account/overview/");
?>