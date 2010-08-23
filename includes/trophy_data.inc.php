<?
$GLOBALS['TROPHIES'] = array();

$TROPHIES = &$GLOBALS['TROPHIES'];

$TROPHIES[1] = array( 'name'      =>  '1 Comic Page',
                      'criteria'  =>  'Upload at least 1 comic page to DrunkDuck.' );

$TROPHIES[2] = array( 'name'      =>  '100 Comic Pages',
                      'criteria'  =>  'Upload at least 100 comic pages to DrunkDuck.' );

$TROPHIES[3] = array( 'name'      =>  '500 Comic Pages',
                      'criteria'  =>  'Upload at least 500 comic pages to DrunkDuck.' );

$TROPHIES[4] = array( 'name'      =>  '1000 Comic Pages',
                      'criteria'  =>  'Upload at least 1,000 comic pages to DrunkDuck.' );

$TROPHIES[5] = array( 'name'      =>  'Top 1,000 Comics',
                      'criteria'  =>  'Get into the Top 1,000 comics overall on DrunkDuck.' );

$TROPHIES[6] = array( 'name'      =>  'Top 100 Comics',
                      'criteria'  =>  'Get into the Top 100 comics overall on DrunkDuck.' );

$TROPHIES[7] = array( 'name'      =>  'Top 10 Comics',
                      'criteria'  =>  'Get into the Top 10 comics overall on DrunkDuck.' );

$TROPHIES[8] = array( 'name'      =>  'Top 5 Comics',
                      'criteria'  =>  'Get into the Top 5 comics overall on DrunkDuck.' );

$TROPHIES[9] = array( 'name'      =>  'Favorite',
                      'criteria'  =>  'Favorite at least 1 comic.' );

$TROPHIES[10] = array( 'name'      =>  '100 Visitors',
                       'criteria'  =>  'Get 100 Unique Visitors' );

$TROPHIES[11] = array( 'name'      =>  '1,000 Visitors',
                       'criteria'  =>  'do something' );

$TROPHIES[12] = array( 'name'      =>  '10,000 Visitors',
                       'criteria'  =>  'Get 10,000 Unique Visitors' );

$TROPHIES[13] = array( 'name'      =>  'Forum Poster',
                       'criteria'  =>  'Post in the forum.' );

$TROPHIES[14] = array( 'name'      =>  'Active Forum Poster',
                       'criteria'  =>  'Post 100 times in the forum.' );

$TROPHIES[15] = array( 'name'      =>  'Super Forum Poster',
                       'criteria'  =>  'Post 1,000 times in the forum.' );

$TROPHIES[16] = array( 'name'      =>  'Forum Maniac',
                       'criteria'  =>  'Post 2,500 times in the forum!' );

$TROPHIES[17] = array( 'name'      =>  'Featured Story',
                       'criteria'  =>  'One of your Comic Stories must be featured.' );

$TROPHIES[18] = array( 'name'      =>  'Featured Strip',
                       'criteria'  =>  'One of your Comic Strips must be featured.' );

$TROPHIES[19] = array( 'name'      =>  '100 Friends',
                       'criteria'  =>  'Make 100 Friends on your profile page.' );

$TROPHIES[20] = array( 'name'      =>  '1,000 Friends',
                       'criteria'  =>  'Make 1,000 Friends on your profile page.' );

$TROPHIES[29] = array( 'name'      =>  'DrunkDuck Member',
                       'criteria'  =>  'That\'s right, folks. Everyone gets this trophy.' );

$TROPHIES[30] = array( 'name'      =>  'Custom Profile',
                       'criteria'  =>  'Fill out the \'About Me\' section of your profile.' );

/*
  MEMBERSHIP DURATION TROPHIES BEGIN AT 500
*/

$TROPHIES[500] = array( 'name'      =>  'Tin Member',
                        'criteria'  =>  'Be on the site at least 1 year.' );

$TROPHIES[501] = array( 'name'      =>  'Bronze Member',
                        'criteria'  =>  'Be on the site at least 2 years.' );

/*
  GAME TROPHIES BEGIN AT 1000
  Leave 10 spots in between just in case we add other things in games like 2nd and 3rd place.
*/

// CAT!
$TROPHIES[1000] = array( 'name'      =>  'CAT! Game Trophy',
                         'criteria'  =>  'Reach the target score in CAT!' );

$TROPHIES[1001] = array( 'name'      =>  'CAT! 1st Place.',
                         'criteria'  =>  'Hold the 1st Place spot for CAT! when scores reset.' );

// C&A Line-Up
$TROPHIES[1010] = array( 'name'      =>  'C&A Line-Up Game Trophy',
                         'criteria'  =>  'Reach the target score in C&A Line-Up.' );

$TROPHIES[1011] = array( 'name'      =>  'C&A Line-Up 1st Place.',
                         'criteria'  =>  'do something' );

// HBN
$TROPHIES[1020] = array( 'name'      =>  'HBN Game Trophy',
                         'criteria'  =>  'Reach the target score in Hero by Night.' );

$TROPHIES[1021] = array( 'name'      =>  'Hero by Night 1st Place.',
                         'criteria'  =>  'Hold the 1st Place spot for Hero by Night when scores reset.' );

// Monkey Deathbot
$TROPHIES[1030] = array( 'name'      =>  'Monkey Deathbot Roundup Game Trophy.',
                         'criteria'  =>  'Reach the target score in Monkey Deathbot Roundup.' );

$TROPHIES[1031] = array( 'name'      =>  'Monkey Deathbot Roundup 1st Place.',
                         'criteria'  =>  'Hold the 1st Place spot for Monkey Deathbot Roundup when scores reset.' );




























function user_update_trophies( &$Uobj, $trophy_id )
{
  switch( $trophy_id )
  {
    case 1:

    break;

    default:
    break;
  }
}
?>