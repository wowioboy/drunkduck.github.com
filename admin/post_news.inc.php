CREATE TABLE dd_news
(
  post_id        INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id        INT(11) NOT NULL,
  news_type      INT(11) NOT NULL DEFAULT '0',
  post_timestamp INT(11) NOT NULL,
  post_ymd_date  INT(8)  NOT NULL,
  post_body      TEXT,
  INDEX(post_timestamp)

);





1. A way to limit the number of newsposts on the main page.
2. A link to old news posts.
3. A way for users to comment on the news post?
4. A way for editors or admins to approve/disapprove article submissions from users.

DrunkDuck News
Featured Comic (more on that in a different post)
Comic Under Review (more on that in a second)
Comic Milestone (50, 100, 200, 300, etc strips... 1 year, 2 years, etc)
Drunk Duck News (fairly obvious)
Webcomic News (things like cons, Child's Play, other important things)
Article (general articles people write, the kind of stuff that shows up on comixpedia and stuff)
Quick Note (general stuff where we want to make a comment but kind of a miscellaneous category?)







<DIV STYLE='WIDTH:400px;' ALIGN='CENTER' CLASS='container'>
  <DIV CLASS='header' ALIGN='CENTER'>DrunkDuck Admin:</DIV>

  <A HREF='http://<?=DOMAIN?>/admin/pageviews.php'>Statistics</A>
  |
  <A HREF='http://<?=DOMAIN?>/admin/user_view.php'>User List</A>
  |
  <A HREF='http://<?=DOMAIN?>/admin/reported_comments.php'>Comment Reports</A>
  |
  <A HREF='http://<?=DOMAIN?>/admin/ip_ban.php'>IP Ban</A>
</DIV>





















