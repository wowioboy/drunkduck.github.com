<?
/*

+-------------------+--------------+------+-----+---------+----------------+
| Field             | Type         | Null | Key | Default | Extra          |
+-------------------+--------------+------+-----+---------+----------------+
| mail_id           | int(11)      | NO   | PRI | NULL    | auto_increment |
| username_to       | varchar(20)  | NO   | MUL |         |                |
| username_from     | varchar(20)  | NO   | MUL |         |                |
| title             | varchar(255) | NO   |     |         |                |
| message           | text         | YES  |     | NULL    |                |
| time_sent         | int(11)      | NO   |     |         |                |
| viewed            | tinyint(4)   | NO   |     | 0       |                |
| non_username_from | varchar(255) | YES  |     | NULL    |                |
+-------------------+--------------+------+-----+---------+----------------+

*/
function send_system_mail($from, $to, $subject, $body)
{
  db_query("INSERT INTO mailbox (username_to, title, message, time_sent, non_username_from) VALUES ('".db_escape_string($to)."', '".db_escape_string($subject)."', '".db_escape_string($body)."', '".time()."', '".db_escape_string($from)."')");
  db_query("UPDATE users SET pending_mail=pending_mail+1, flags=(flags|".FLAG_NEW_MAIL.") WHERE username='".db_escape_string($to)."'");
}

?>