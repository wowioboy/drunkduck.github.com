<?php
/***********************************************
*                                              *
* Here we list all available servers/databases *
*                                              *
***********************************************/
// APP8 Server

// OLD APP9 server.

//$Main       = new DataBase('69.7.183.56', 'drunkduck',             'drunkduck',      'ice22hdi5m');
//$Syndicate  = new DataBase('69.7.183.56', 'drunkduck_syndication', 'drunkduck',      'ice22hdi5m');
//$Games      = new DataBase('69.7.183.56', 'drunkduck_games',       'drunkduck',      'ice22hdi5m');
//$Forum      = new DataBase('69.7.183.56', 'drunkduck_community',   'drunkduck',      'ice22hdi5m');
//$Track      = new DataBase('69.7.183.56', 'tracking_drunkduck',    'drunkduck',      'ice22hdi5m');


// NEW APP9 server.
$Main       = new DataBase('173.201.18.129', 'drunkduck',             'drunkduck',      'ice22hdi5m');
$Syndicate  = new DataBase('173.201.18.129', 'drunkduck_syndication', 'drunkduck',      'ice22hdi5m');
$Games      = new DataBase('173.201.18.129', 'drunkduck_games',       'drunkduck',      'ice22hdi5m');
$Forum      = new DataBase('173.201.18.129', 'drunkduck_community',   'drunkduck',      'ice22hdi5m');
$Track      = new DataBase('173.201.18.129', 'tracking_drunkduck',    'drunkduck',      'ice22hdi5m');

// $BF         = new DataBase('localhost',    'brokenfr_website',      'brokenfr_user',  'Website291');
$CBC        = new DataBase('173.201.18.129',    'comicBookChallenge',    'cbc',            'ice22hdi3m');


$GLOBALS['QUERIES_ON_CONNECT'] = array('SET auto_commit=1');

/***********************************************
*                                              *
* Here we point tablenames to databases        *
* Keep alphabetic.                             *
*                                              *
***********************************************/
$GLOBALS['TABLE_TO_DB'] = array(
                                'admin_blog'            => &$Main,
                                'alt_accounts'          => &$Main,
                                'bf_headlines'          => &$BF,
                                'cbc_entries'           => &$CBC,
                                'cbc_entrants'          => &$CBC,
                                'click_path_file_to_file'     => &$Track,
                                'click_path_file_to_folder'   => &$Track,
                                'click_path_folder_to_file'   => &$Track,
                                'click_path_folder_to_folder' => &$Track,
                                'click_tracking'        => &$Main,
                                'cowboys_waw_pageviews' => &$Main,
                                'cowboys_waw_uniques'   => &$Main,
                                'comic_chapters'        => &$Main,
                                'comic_favs'            => &$Main,
                                'comic_favs_tally'      => &$Main,
                                'comic_gallery_images'  => &$Main,
                                'comic_html_pages'      => &$Main,
                                'comic_pages'           => &$Main,
                                'comic_pageviews'       => &$Main,
                                'comic_ring_comics'     => &$Main,
                                'comic_rings'           => &$Main,
                                'comics'                => &$Main,
                                'comics_in_need'        => &$Main,
                                'comics_tooltip_cache'  => &$Main,
                                'comment_reports'       => &$Main,
                                'community_categories'  => &$Forum,
                                'community_topics'      => &$Forum,
                                'community_posts'       => &$Forum,
                                'community_sessions'    => &$Forum,
                                'credits'               => &$Main,
                                'demographics'          => &$Main,
                                'downloads'             => &$Main,
                                'duckcam_watchers'      => &$Main,
                                'ecards_sent'           => &$Main,
                                'email_queue'           => &$Main,
                                'faq'                   => &$Main,
                                'featured_comics'       => &$Main,
                                'friends'               => &$Main,
                                'game_sessions'         => &$Games,
                                'game_info'             => &$Games,
                                'game_plays'            => &$Games,
                                'game_launches'         => &$Games,

                                'geoip_cc'              => &$Main,
                                'geoip_ip'              => &$Main,

                                'global_pageviews'      => &$Main,
                                'global_pageviews_filtered' => &$Main,
                                'grabbed_movies'        => &$Main,
                                'help_answers'          => &$Main,
                                'help_q_to_a'           => &$Main,
                                'help_q_to_q'           => &$Main,
                                'help_queries'          => &$Main,
                                'help_questions'        => &$Main,
                                'highscore_top_100'     => &$Games,
                                'index_modules'         => &$Main,
                                'karma_tracking'        => &$Main,
                                'load_tracker'          => &$Main,
                                'mailbox'               => &$Main,
                                'misc_tracking'         => &$Main,
                                'nontest_entries'       => &$Main,
                                'oekaki_entry'          => &$Main,
                                'page_comments'         => &$Main,
                                'page_statistics'       => &$Main,
                                'poll_answers'          => &$Main,
                                'poll_questions'        => &$Main,
                                'poll_votes'            => &$Main,
                                'pool_movies'           => &$Main,
                                'popular_tell_a_friends'=> &$Main,
                                'profile_comments'      => &$Main,
                                'pqs_sent'              => &$Main,
                                'publisher_links'       => &$Main,
                                'referral_tracking'     => &$Main,
				'reported_mail'		=> &$Main,
                                'rss_calls'             => &$Main,
                                'rss_calls_desktop_app' => &$Main,
                                'syndication_clients'   => &$Syndicate,
                                'tags_by_comic'         => &$Main,
                                'tags_by_page'          => &$Main,
                                'tags_counter_daily'    => &$Main,
                                'tags_counter_all_time' => &$Main,
                                'tags_used'             => &$Main,
                                'tell_a_friend'         => &$Main,
                                'trophy_records'        => &$Main,
                                'tutorials'             => &$Main,
                                'tutorial_images'       => &$Main,
                                'tutorial_tags'         => &$Main,
                                'tutorial_tags_used'    => &$Main,
				'tutorial_votes'	=> &$Main,
                                'unique_comic_tracking' => &$Main,
                                'unique_comic_tracking_archive' => &$Main,
                                'unique_tracking'       => &$Main,
                                'unique_tracking_filtered' => &$Main,
                                'user_highscores'       => &$Games,
                                'users'                 => &$Main,
                                'zipcode_data'          => &$Main
                               );

// Connections are remembered in this array.
$GLOBALS['CONNECTIONS'] = array();

// To avoid switching databases within hosts unnecessarily,
// this will keep track of the current database selected on the host.
$GLOBALS['HOST_TO_CURRENT_DB'] = array();

// Whatever the last connection to be queried was is stored here.
$GLOBALS['LAST_QUERY_RES'] = null;

// Globally Useful Data:
$GLOBALS['TOTAL_QUERIES']    = 0;
$GLOBALS['TOTAL_QUERY_TIME'] = 0;
/**********************
* Database Definition *
**********************/
Class DataBase {
  var $host, $database;
  var $user, $pass;

  function DataBase($host, $db, $dbName=null, $dbPass=null){
    $this->host     = $host;
    $this->database = $db;
    $this->user     = $dbName;
    $this->pass     = $dbPass;
  }
}
?>
