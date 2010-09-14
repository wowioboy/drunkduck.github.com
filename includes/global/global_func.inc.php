<?php

function track_comic_view($comic_id, $multiplier=10)
{
  if ( dice(1, $multiplier) == 1 )
  {
    db_query("UPDATE comic_pageviews SET counter=counter+1 WHERE comic_id='".(int)$comic_id."' AND ymd_date='".date("Ymd")."'");
    if ( db_rows_affected() < 1 ) {
      db_query("INSERT INTO comic_pageviews (comic_id, ymd_date, counter, multiplier) VALUES ('".(int)$comic_id."', '".date("Ymd")."', '1', '".(int)$multiplier."')");
    }
  }
}

function give_trophy( $user_id, &$trophy_string, $trophy_id )
{
  $tArr = explode(',', $trophy_string);
  if ( in_array($trophy_id, $tArr, false) ) return;

  db_query("INSERT INTO trophy_records (user_id, trophy_id) VALUES ('".(int)$user_id."', '".(int)$trophy_id."')");
  if ( db_rows_affected() < 1 ) return;

  $trophy_string = reform_trophy_string($user_id);
}

function take_trophy( $user_id, &$trophy_string, $trophy_id )
{
  if ( $trophy_id == 'all' ) {
    db_query("DELETE FROM trophy_records WHERE user_id='".(int)$user_id."'");
  }
  else {
    db_query("DELETE FROM trophy_records WHERE user_id='".(int)$user_id."' AND trophy_id='".(int)$trophy_id."'");
  }

  if ( db_rows_affected() < 1 ) return;

  $trophy_string = reform_trophy_string($user_id);
}

function reform_trophy_string($user_id)
{
  $tArr = array();

  $res = db_query("SELECT trophy_id FROM trophy_records WHERE user_id='".(int)$user_id."'");
  while( $row = db_fetch_object($res) ) {
    $tArr[] = $row->trophy_id;
  }
  db_free_result($res);

  asort( $tArr, SORT_NUMERIC );

  $trophy_string = trim(implode(',', $tArr));

  db_query("UPDATE users SET trophy_string='".$trophy_string."' WHERE user_id='".(int)$user_id."'");

  return $trophy_string;
}




function pickNonEmpty() {
  $args = func_get_args();
  if ( count($args) ) {
    foreach( func_get_args() as $arg ) {
      if ( strlen($arg)>0 ) return $arg;
    }
  }
  return '';
}

function misc_track($name)
{
  db_query("UPDATE misc_tracking SET counter=counter+1 WHERE track_title='".db_escape_string($name)."' AND ymd_date='".date("Ymd")."'");
  if ( db_rows_affected() < 1 ) {
    db_query("INSERT INTO misc_tracking (track_title, ymd_date) VALUES ('".db_escape_string($name)."', '".date("Ymd")."')");
  }
}

function homepage_image($comic_name)
{
  foreach($ALLOWED_UPLOADS as $ext) {
    if ( file_exists( WWW_ROOT.'/comics/'.$FOLDER{0}.'/'.$FOLDER.'/gfx/comic_title.'.$ext) ) {
      return 'http://'.DOMAIN.'/'.$FOLDER.'/gfx/comic_title.'.$ext;
    }
  }
  return false;
}




function isBot()
{
  if ( strlen($_SERVER["HTTP_USER_AGENT"]) == 0 ) return true;

  $botlist = array( "bot",
                    "spider",
                    "crawler",
                    "teoma",
                    "alexa",
                    "froogle",
                    "inktomi",
                    "looksmart",
                    "archiver",
                    "firefly",
                    "nationaldirectory",
                    "ask jeeves",
                    "tecnoseek",
                    "infoseek",
                    "galaxy.com",
                    "scooter",
                    "slurp",
                    "appie",
                    "fast",
                    "webbug",
                    "spade",
                    "zyborg",
                    "rabaz",
                    "arachnoidea",
                    "kit-fireball",
                    "backrub",
                    "infoseek sidewinder",
                    "gulliver",
                    "pingdom.com"
                    );

  $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
  foreach($botlist as $bot) {
    if ( strstr($agent, $bot) ) {
      return true;
    }
  }

  return false;
}


function thumb_processor( $comic_row )
{
  global $USER;

  $USER_AGE = $USER->age;
  if ( $USER_AGE == 0 ) $USER_AGE = 0;
  else if ( $USER_AGE < 13 ) $USER_AGE = 12;
  else if ( $USER_AGE < 18 ) $USER_AGE = 17;
  else $USER_AGE = 18;

  return IMAGE_HOST.'/process/comic_'.(int)$comic_row->comic_id.'_'.$USER_AGE.'_'.strtoupper($comic_row->rating_symbol).'_'.(int)$comic_row->category.'_sm.jpg';
}

function clearCache( $path )
{
  if  ( !defined('CACHE_ROOT') ) {
    die('error, could not find cache');
  }
  if ( substr($path, 0, strlen(CACHE_ROOT)) != CACHE_ROOT ) {
    die("ERROR: $path doesn't start with ".CACHE_ROOT);
  }

  $dp = opendir( $path );
  if ( $dp !== false )
  {

    while( ( $file = readdir($dp) ) !== false )
    {

      if ( $file != '.' && $file != '..' )
      {

        if ( is_dir($path.'/'.$file) ) {
          clearCache($path.'/'.$file);
        }
        else {
          unlink($path.'/'.$file);
        }

      }

    }

  }

  closedir($dp);
}

function cleanThumbs($COMIC_ID)
{
  $COMIC_ID = (string)str_pad($COMIC_ID, 2, '0', STR_PAD_LEFT); // ID of comic.
  $PATH     = '/var/www/html/drunkduck.com/gfx/_cache/thumbnails/'.$COMIC_ID{0}.'/'.$COMIC_ID{1}.'/';

  if ( ($dp = opendir($PATH)) !== false )
  {
    while( ($file = readdir($dp)) !== false )
    {
      if ( $file != '.' && $file != '..' ) {
        if ( substr($file, 0, strlen('thumb_'.$COMIC_ID.'_')) == 'thumb_'.$COMIC_ID.'_' ) {
          unlink( $PATH .'/'. $file );
        }
      }
    }
  }
}

function generate_graph( $amtArray, $filePath=false, $amtArray2=false, $color1Name=false, $color2Name=false )
{
    // Get the total number of columns we are going to plot
    $columns  = count($amtArray);

    // Get the height and width of the final image
    $total_width  = 300;
    $width        = $total_width - 30; // 30 pixesl = 6 characters of space?
    $total_height = 170;
    $height       = $total_height-20;

    // Set the amount of space between each column
    $padding = 5;

    // Get the width of 1 column
    $column_width = $width / $columns ;

    // Generate the image variables
    $im        = imagecreate($total_width, $total_height);

    $COLORS                   = array();
    $COLORS['background']     = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
    $COLORS['graph_line_1']   = imagecolorallocate($im, 0x00, 0x00, 0xFF);
    $COLORS['graph_line_2']   = imagecolorallocate($im, 0xFF, 0x00, 0xFF);
    $COLORS['graph_border']   = imagecolorallocate($im, 0x00, 0x00, 0x00);
    $COLORS['text']           = imagecolorallocate($im, 0x00, 0x00, 0x00);
    $COLORS['gray_line']      = imagecolorallocate($im, 0xCE, 0xCE, 0xCE);

    // Fill in the background of the image
    imagefilledrectangle( $im, 0, 0, $width, $height, $COLORS['background'] );

    $maxv = 0;

    // Calculate the maximum value we are going to plot
    for( $i=0; $i<$columns; $i++)
    {
      $maxv = max($amtArray[$i], $maxv);

      if ( count($amtArray) == count($amtArray2) ) {
        $maxv = max($amtArray2[$i], $maxv);
      }
    }

    // Draw guide amt lines
    $x = $width + 3;
    $tickCt = 10;
    $spacing = floor($height/$tickCt);
    $chunk = floor($maxv / $tickCt);
    for($y=0; $y<$tickCt; $y++)
    {
      imageline( $im, $width, ($y*$spacing), 0, ($y*$spacing), $COLORS['gray_line'] );
    }

    // Now plot each line
    $oldX = 0;
    $oldY = $height;
    for($i=0;$i<$columns;$i++)
    {
      $y = $height - floor( ( $amtArray[$i] / $maxv) * $height);
      $x = ($i+1) * $column_width;
      imageline($im, $oldX, $oldY, $x, $y, $COLORS['graph_line_1']);
      $oldX = $x;
      $oldY = $y;
    }

    if ( count($amtArray) == count($amtArray2) )
    {
      $oldX = 0;
      $oldY = $height;
      for($i=0;$i<$columns;$i++)
      {
        $y = $height - floor( ( $amtArray2[$i] / $maxv) * $height);
        $x = ($i+1) * $column_width;
        imageline($im, $oldX, $oldY, $x, $y, $COLORS['graph_line_2']);
        $oldX = $x;
        $oldY = $y;
      }
    }


    // Now spell out amts on right side.
    $x = $width + 3;
    $tickCt = 10;
    $spacing = floor($height/$tickCt);
    $chunk = floor($maxv / $tickCt);

    for($y=0; $y<$tickCt; $y++)
    {
      $amt = $maxv - ($chunk * $y);
      $str = $amt;

      if ( $str > 1000000000 ) {
        $str = floor( $str/1000000000 ).'B';
      }
      else if ( $str > 1000000 ) {
        $str = floor( $str/1000000 ).'M';
      }
      else if ( $str > 1000 ) {
        $str = floor( $str/1000 ).'k';
      }

      $str = (string)$str;
      for($i=0; $i<strlen($str); $i++)
      {
        imagechar( $im, 1, $x+($i*5), $y*$spacing, $str{$i}, $COLORS['text']);
      }
    }

    // Draw a border
    imageline( $im, 0,        0,         $width, 0,         $COLORS['graph_border'] );
    imageline( $im, $width, 0,         $width, $height, $COLORS['graph_border'] );
    imageline( $im, $width, $height, 0,        $height, $COLORS['graph_border'] );
    imageline( $im, 0,        $height, 0,        0,         $COLORS['graph_border'] );


    // Finally, lets put a key at the bottom!
    $y = $height + 3;
    $x = 10;
    if ( $color1Name )
    {
      for($i=0; $i<strlen($color1Name); $i++)
      {
        imagechar( $im, 1, $x, $y, $color1Name{$i}, $COLORS['graph_line_1']);
        $x += 5;
      }
    }
    if ( $color2Name )
    {
      if ( $color1Name ) {
        $x += 20;
      }
      for($i=0; $i<strlen($color2Name); $i++)
      {
        imagechar( $im, 1, $x, $y, $color2Name{$i}, $COLORS['graph_line_2']);
        $x += 5;
      }
    }



    // Send the PNG header information. Replace for JPEG or GIF or whatever
    if ( !$filePath ) {
      header("Content-type: image/png");
      imagepng($im);
    }
    else {
      imagepng($im, $filePath);
    }
}


function get_current_thumbnail($comic_id, $comic_name, $url_only=false)
{
  $num = (string)$comic_id{0};

  if ( !file_exists(WWW_ROOT.'/gfx/comic_thumbnails/'.$num.'/comic_'.$comic_id.'.jpg') )
  {
    $pRES = db_query("SELECT * FROM comic_pages WHERE comic_id='".$comic_id."' ORDER BY order_id ASC LIMIT 1");
    if ( db_num_rows($pRES) == 0 ) return false;
    $pROW = db_fetch_object($pRES);
    db_free_result($pRES);

    $N = $comic_name;

    if ( $pROW->file_ext != 'swf' )
    {
      $FILE = WWW_ROOT.'/comics/'.$N{0}.'/'.str_replace(' ', '_', $N).'/pages/'.md5($pROW->comic_id.$pROW->page_id).'.'.$pROW->file_ext;
      thumb($FILE, WWW_ROOT.'/gfx/comic_thumbnails/'.$num.'/comic_'.$comic_id.'.jpg', 80, 100, true);

      if ( $url_only ) {
        return IMAGE_HOST."/comic_thumbnails/".$num."/comic_".$comic_id.".jpg";
      }
      else {
        return "<img src='".IMAGE_HOST."/comic_thumbnails/".$num."/comic_".$comic_id.".jpg' style='border:1px solid black;'>";
      }
    }
    else
    {
      if ( $url_only ) {
        return false;
      }
      else {
        return "<DIV STYLE='width:100px;height:100px;border:1px solid black;'>No Preview for SWF Files</DIV>";
      }
    }
  }
  else
  {
    if ( $url_only ) {
      return IMAGE_HOST."/comic_thumbnails/".$num."/comic_".$comic_id.".jpg";
    }
    else {
      return "<img src='".IMAGE_HOST."/comic_thumbnails/".$num."/comic_".$comic_id.".jpg' style='border:1px solid black;'>";
    }
  }
}

function thumb($filename, $destination, $th_width, $th_height, $forcefill)
{
   list($width, $height) = getimagesize($filename);

   preg_match("'^(.*)\.(gif|jpe?g|png)$'i", $filename, $ext);

   switch (strtolower($ext[2])) {
       case 'jpg':
       case 'jpeg':
        $source  = imagecreatefromjpeg ($filename);
       break;
       case 'gif':
        $source  = imagecreatefromgif  ($filename);
       break;
       case 'png':
        $source  = imagecreatefrompng  ($filename);
       break;
       default:
        return;
       break;
   }

   //$source = imagecreatefromjpeg($filename);


//   if($width >= $th_width || $height >= $th_height)
   {
     $a = $th_width/$th_height;
     $b = $width/$height;

     if( ($a > $b)^$forcefill )
     {
         $src_rect_width  = $a * $height;
         $src_rect_height = $height;
         if(!$forcefill)
         {
           $src_rect_width = $width;
           $th_width = $th_height/$height*$width;
         }
     }
     else
     {
         $src_rect_height = $width/$a;
         $src_rect_width  = $width;
         if(!$forcefill)
         {
           $src_rect_height = $height;
           $th_height = $th_width/$width*$height;
         }
     }

     $src_rect_xoffset = ($width - $src_rect_width)/2*intval($forcefill);
     $src_rect_yoffset = ($height - $src_rect_height)/2*intval($forcefill);

     $thumb  = imagecreatetruecolor($th_width, $th_height);
     imagecopyresampled($thumb, $source, 0, 0, $src_rect_xoffset, $src_rect_yoffset, $th_width, $th_height, $src_rect_width, $src_rect_height);

     imagejpeg($thumb, $destination);
   }
}

function myHash($str, $base=1000)
{
  $str   = trim(strtolower($str));
  $total = 0;
  for($i=0; $i<strlen($str); $i++) {
    $total += floor( ord($str{$i}) * $i );
  }
  return $total % $base;
}

function getAd($size)
{
  if ( !defined('TEMPLATE_PAGE') )
  {
   // $TEXT_AD = "<FONT COLOR='#0000FF' STYLE='font-family:verdana;font-size:12px;'>Sponsors: </FONT><A STYLE='font-family:verdana;color:#000000;font-size:11px;' HREF='http://www.thegigcast.com'>The Gigcast</A> - <A STYLE='font-family:verdana;color:#000000;font-size:11px;' HREF='http://www.tblog.com'>tBlog.com</A> - <A STYLE='font-family:verdana;color:#000000;font-size:11px;' HREF='http://www.inflash.com'>InFlash.com</A>";
   // $RET_START = "<DIV STYLE='width:728px;background:#FFFFFF;border:1px solid black;' ALIGN='LEFT'>";
   // $RET_END   = "</DIV>";
  }
  /*
  $RET_EXTRA = "<div><!-- FASTCLICK.COM POP-UNDER CODE v1.8 for drunkduck.com (12 hour) -->
<script language=\"javascript\"><!--
var dc=document; var date_ob=new Date();
dc.cookie='h2=o; path=/;';var bust=date_ob.getSeconds();
if(dc.cookie.indexOf('e=llo') <= 0 && dc.cookie.indexOf('2=o') > 0){
dc.write('<scr'+'ipt language=\"javascript\" src=\"http://media.fastclick.net');
dc.write('/w/pop.cgi?sid=11415&m=2&tp=2&v=1.8&c='+bust+'\"></scr'+'ipt>');
date_ob.setTime(date_ob.getTime()+43200000);
dc.cookie='he=llo; path=/; expires='+ date_ob.toGMTString();} // -->
</script>
<!-- FASTCLICK.COM POP-UNDER CODE v1.8 for drunkduck.com --></div>";
  */
  $RET_EXTRA = $TEXT_AD . $RET_EXTRA;

  if ( true )
  {
    return "<script language='JavaScript' type='text/javascript' src='http://ads.platinumstudios.com/adx.js'></script>
<script language='JavaScript' type='text/javascript'>
<!--
   if (!document.phpAds_used) document.phpAds_used = ',';
   phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);

   document.write (\"<\" + \"script language='JavaScript' type='text/javascript' src='\");
   document.write (\"http://ads.platinumstudios.com/adjs.php?n=\" + phpAds_random);
   document.write (\"&amp;what=zone:4&amp;target=_self\");
   document.write (\"&amp;exclude=\" + document.phpAds_used);
   if (document.referrer)
      document.write (\"&amp;referer=\" + escape(document.referrer));
   document.write (\"'><\" + \"/script>\");
//-->
</script><noscript><a href='http://ads.platinumstudios.com/adclick.php?n=a0ded0d0' target='_self'><img src='http://ads.platinumstudios.com/adview.php?what=zone:4&amp;n=a0ded0d0' border='0' alt=''></a></noscript>".$RET_EXTRA.$RET_END;
  }

  switch($size)
  {
    case "728x90":
      return $RET_START."<!-- FASTCLICK.COM 468x60 and 728x90 Banner CODE for drunkduck.com -->
<script language=\"javascript\" src=\"http://media.fastclick.net/w/get.media?sid=11415&m=1&tp=5&d=j&t=n\"></script>
<noscript><a href=\"http://media.fastclick.net/w/click.here?sid=11415&m=1&c=1\" target=\"_blank\">
<img src=\"http://media.fastclick.net/w/get.media?sid=11415&m=1&tp=5&d=s&c=1\"
width=728 height=90 border=1></a></noscript>
<!-- FASTCLICK.COM 468x60 and 728x90 Banner CODE for drunkduck.com -->".$RET_EXTRA.$RET_END;
    case "468x60":
    case "480x60":
      return $RET_START."<!-- FASTCLICK.COM 468x60 Banner v1.4 for drunkduck.com -->
<script language=\"Javascript\"><!--
var i=j=p=t=u=x=z=dc='';var id=f=0;var f=Math.floor(Math.random()*7777);
id=11415; dc=document;u='ht'+'tp://media.fastclick.net/w'; x='/get.media?t=n';
z=' width=468 height=60 border=0 ';t=z+'marginheight=0 marginwidth=';
i=u+x+'&sid='+id+'&m=1&tp=1&f=b&v=1.4&c='+f+'&r='+escape(dc.referrer);
u='<a  hr'+'ef=\"'+u+'/click.here?sid='+id+'&m=1&c='+f+'\"  target=\"_blank\">';
dc.writeln('<ifr'+'ame src=\"'+i+'&d=f\"'+t+'0 hspace=0 vspace=0 frameborder=0 scrolling=no>');
if(navigator.appName.indexOf('Mic')<=0){dc.writeln(u+'<img src=\"'+i+'&d=n\"'+z+'></a>');}
dc.writeln('</iframe>'); // --></script><noscript>
<a href=\"http://media.fastclick.net/w/click.here?sid=11415&m=1&c=1\"  target=\"_blank\">
<img src=\"http://media.fastclick.net/w/get.media?sid=11415&m=1&tp=1&d=s&c=1&f=b&v=1.4\"
width=468 height=60 border=1></a></noscript>
<!-- FASTCLICK.COM 468x60 Banner v1.4 for drunkduck.com -->".$RET_EXTRA.$RET_END;
    case "cat":
      return "<A HREF='http://www.lulu.com/content/221840' TARGET='_parent'><IMG SRC='/gfx/cat_banner.png' BORDER='0'></A>".$RET_EXTRA;
    break;
    case "ethanku":
      return "<A HREF='http://www.ethanku.com' TARGET='_parent'><IMG SRC='/gfx/ethanku_banner.jpg' BORDER='0'></A>".$RET_EXTRA;
    break;
    case "ronson":
      return "<A HREF='http://www.rmcomics.com/Store/A_-_Graphic_Novels_and_Collections.html' TARGET='_parent'><IMG SRC='/The_Gods_of_ArrKelaan/gfx/GOAMLBANNER.jpg' BORDER='0'></A>".$RET_EXTRA;
    break;
    default:
      return $RET_START."<!-- FASTCLICK.COM 468x60 Banner v1.4 for drunkduck.com -->
<script language=\"Javascript\"><!--
var i=j=p=t=u=x=z=dc='';var id=f=0;var f=Math.floor(Math.random()*7777);
id=11415; dc=document;u='ht'+'tp://media.fastclick.net/w'; x='/get.media?t=n';
z=' width=468 height=60 border=0 ';t=z+'marginheight=0 marginwidth=';
i=u+x+'&sid='+id+'&m=1&tp=1&f=b&v=1.4&c='+f+'&r='+escape(dc.referrer);
u='<a  hr'+'ef=\"'+u+'/click.here?sid='+id+'&m=1&c='+f+'\"  target=\"_blank\">';
dc.writeln('<ifr'+'ame src=\"'+i+'&d=f\"'+t+'0 hspace=0 vspace=0 frameborder=0 scrolling=no>');
if(navigator.appName.indexOf('Mic')<=0){dc.writeln(u+'<img src=\"'+i+'&d=n\"'+z+'></a>');}
dc.writeln('</iframe>'); // --></script><noscript>
<a href=\"http://media.fastclick.net/w/click.here?sid=11415&m=1&c=1\"  target=\"_blank\">
<img src=\"http://media.fastclick.net/w/get.media?sid=11415&m=1&tp=1&d=s&c=1&f=b&v=1.4\"
width=468 height=60 border=1></a></noscript>
<!-- FASTCLICK.COM 468x60 Banner v1.4 for drunkduck.com -->".$RET_EXTRA.$RET_END;
  }
}

function recalcPageScore($page_id)
{
  $TOTAL_VOTES = 0;
  $TOTAL_SCORE = 0;
  $res = db_query("SELECT vote_rating, vote_weight FROM page_comments WHERE page_id='".$page_id."'");
  while ($row = db_fetch_object($res))
  {
    if (($row->vote_rating > 0) && ($row->vote_weight > 0)) {
      $TOTAL_VOTES++;
      $TOTAL_SCORE += floor($row->vote_rating * $row->vote_weight);
    }
  }
  db_free_result($res);
  db_query("UPDATE comic_pages SET page_score='".round($TOTAL_SCORE/$TOTAL_VOTES)."' WHERE page_id='".$page_id."'");
}

function comicNameToFolder($name)
{
  return str_replace(' ', '_', $name);
}

function get_karma_object($user_id, $recursive=false) {
  if ( is_object($user_id) ) {
    $user_id = $user_id->user_id;
  }
  $res = db_query("SELECT * FROM karma_tracking WHERE user_id='".$user_id."'");
  if ( $row = db_fetch_object($res) ) {
    db_free_result($res);
    return $row;
  }

  if ( !$recursive )
  {
    db_query("INSERT INTO karma_tracking (user_id, comments_made, comments_muted, comments_reported, comments_erased) VALUES ('".$user_id."', '0', '0', '0', '0')");
    return get_karma_object($user_id, true);
  }

  die("ERROR: Please report this page and what you are doing to an administrator for correction.");
}

function add_comment_karma($user_id, $amt=1)
{
  if ( is_object($user_id) ) {
    $user_id = $user_id->user_id;
  }
  $user_id = (int)$user_id;
  if ( !$user_id ) return;

  $res = db_query("UPDATE karma_tracking SET comments_made=comments_made+".$amt." WHERE user_id='".$user_id."'");
  if ( db_rows_affected($res) == 0 ) {
    db_query("INSERT INTO karma_tracking (user_id, comments_made, comments_muted, comments_reported, comments_erased) VALUES ('".$user_id."', '".$amt."', '0', '0', '0')");
  }
}

function isValidEmail($email)
{
  if( !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[_a-z0-9-]+)*(\.[a-z]{2,3})$", $email) ) {
    return false;
  }
  return true;
}

// Displays the username as passed in.
function comic_name($comic, $page=false)
{
  global $USER;

  if ( !($USER->flags&FLAG_OVER_12) && ($comic->flags&FLAG_ADULT) )
  {
    // if it's just a string or whatever.
    if ( !is_object($comic) ) {
      return $comic;
    }
    return $comic->comic_name;
  }

  //$OMO = "onMouseover=\"ajaxGetDescription('".$comic->comic_name."');return true\"; onMouseout=\"hideddrivetip()\"";

  // if it's just a string or whatever.
  if ( !is_object($comic) ) {
    if ( $page ) {
      return "<A HREF='http://".DOMAIN."/".comicNameToFolder($comic)."/?p=".$page."' $OMO>".$comic."</A>";
    }
    return "<A HREF='http://".DOMAIN."/".comicNameToFolder($comic)."/' $OMO>".$comic."</A>";
  }
  if ( $page ) {
    return "<A HREF='http://".DOMAIN."/".comicNameToFolder($comic->comic_name)."/?p=".$page."' $OMO>".$comic->comic_name."</A>";
  }
  return "<A HREF='http://".DOMAIN."/".comicNameToFolder($comic->comic_name)."/' $OMO>".$comic->comic_name."</A>";
}

// Displays the username as passed in.
function username($user)
{
  global $USER;
  $EXTRAS = '';
  // if it's just a string or whatever.
  if ( !is_object($user) )
  {
    if ( $USER && ($USER->flags & FLAG_IS_ADMIN) ) {
      $EXTRAS .= " <A HREF='http://".DOMAIN."/admin/warn_user.php?u=".$user."'><FONT STYLE='FONT-SIZE:9px;COLOR:#FF0000;'>warn</FONT></A>";
    }
    return "<A HREF='http://".USER_DOMAIN."/".rawurlencode($user)."/'>".$user."</A>".$EXTRAS;
  }
  else
  {

    if ( $USER && ($USER->flags & FLAG_IS_ADMIN) ) {
      $EXTRAS .= " <A HREF='http://".DOMAIN."/admin/warn_user.php?u=".$user->user_id."'><FONT STYLE='FONT-SIZE:9px;COLOR:#FF0000;'>warn</FONT></A>";
    }
    return "<A HREF='http://".USER_DOMAIN."/".rawurlencode($user->username)."/'>".$user->username."</A>".$EXTRAS;
  }
}


function getExt($fileName)
{
  $pos = strrpos($fileName, '.');
  if ( $pos === false ) return false;

  $ext = substr($fileName, $pos+1);
  return strtolower($ext);
}


// Returns the file extension in lower case.
function getFileExt($filename)
{
  $filePieces = explode('.', $filename);
  return strtolower($filePieces[count($filePieces)-1]);
}

// Builds selects...
function getKeyValueSelect($ARRAY, $valueNow=null)
{
  if ( !is_array($ARRAY) ) {
    return;
  }

  $retStr = '';
  foreach ( $ARRAY as $key=>$value ) {
    $SELECTED = "";
    if ( (string)$key === (string)$valueNow ) $SELECTED = "SELECTED";
    $retStr .= "<OPTION VALUE='$key' $SELECTED>$value</OPTION>";
  }
  return $retStr;
}

// Builds selects...
function getValueKeySelect($ARRAY, $valueNow=null)
{
  if ( !is_array($ARRAY) ) {
    return;
  }

  $retStr = '';
  foreach ( $ARRAY as $key=>$value ) {
    $SELECTED = "";
    if ( (string)$value === (string)$valueNow ) {
      echo $value ."=".$valueNow."<BR>";
      $SELECTED = "SELECTED";
    }
    $retStr .= "<OPTION VALUE='$value' $SELECTED>$key</OPTION>";
  }
  return $retStr;
}

// .png files have full alpha blending ability.
// Unfortunately, IE will not render appropriately as of this writing.
// Thus, we use a special set of code/css/etc. to call a DX method for IE
// and if they don't have IE, it shows as regular for mozilla.
function getPNGCode($imgPath, $border=0, $clickURL=false)
{
  $mouseOver = '';
  if ( $clickURL ) {
    $mouseOver = "onMouseOver=\"this.style.cursor='hand';\" onMouseOut=\"this.style.cursor='';\"";
  }

  $r_val  = "<span $mouseOver style=\"width:100%;height:100%;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='".$imgPath."');\">" .
            "<img style=\"filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);\" src=\"".$imgPath."\" border=\"".$border."\">" .
            "</span>";
  return $r_val;
}

define('FLASH_SCALE_SHOWALL',  'showall');
define('FLASH_SCALE_EXACTFIT', 'exactfit');
define('FLASH_SCALE_NOSCALE',  'noscale');
class FlashMovie
{
  var $fileURL   = '';
  var $width     = 0;
  var $height    = 0;
  var $quality   = 'high';
  var $flashVars = array();
  var $flashVarString = false; // Optional string override for flash vars.

  var $bgcolor   = false;

  var $transparent = false;

  var $scale  = 'showall'; //$scale = 'exactfit';
  var $salign = 'LT';

  function FlashMovie( $url, $w, $h )
  {
    // Append a random number to prevent caching while developing.
    if ( DEBUG_MODE == 1 ) {
      if ( strstr($url, '?') ) $url .= '&rand='.dice(1,99999);
      else $url .= '?rand='.dice(1,99999);
    }

    $this->fileURL                    = $url;
    $this->width                      = $w;
    $this->height                     = $h;
    $this->flashVars['unixTimeStamp'] = time();
  }

  function addVar($name, $value) {
    $this->flashVars[$name] = rawurlencode($value);
  }

  function setSAlign($type) {
    $this->salign = $type;
  }

  function setVarString($fullString) {
    $this->flashVarString = $fullString;
  }

  function setTransparent($bool) {
    if ( $bool ) $this->transparent = true;
    else $this->transparent = false;
  }

  function setScale($type) {
    if ( ($type==FLASH_SCALE_SHOWALL) || ($type==FLASH_SCALE_EXACTFIT) || ($type==FLASH_SCALE_NOSCALE) ) {
      $this->scale = $type;
    }
    else {
      die("setScale('".$type."') BAD");
    }
  }

  function getFlashVars()
  {
    if ( $this->flashVarString ) {
      return $this->flashVarString;
    }
    $ret = '';
    $ct = 0;
    foreach($this->flashVars as $name=>$value) {
      $ret .= $name.'='.$value;
      if ( ++$ct != count($this->flashVars) ) {
        $ret .= '&';
      }
    }
    return $ret;
  }

  function setBackground($hexcolor)
  {
    if ( $hexcolor{0} == '#' ) {
      $this->bgcolor = $hexcolor;
      return;
    }
    $this->bgcolor = '#'.$hexcolor;
  }

  function getHTML($embedCode=false)
  {
    if ( !$embedCode ) {
      $embedCode = $this->getEmbedCode();
    }

    $ret = "<script type=\"text/javascript\">embedCode = '".$embedCode."';</script>";

    return $ret."<script src=\"".HTTP_JAVASCRIPT."/FlashEmbed.js\"></script>";
  }

  function getEmbedCode()
  {
    $args = $this->getFlashVars();
    $ret = "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"".$this->width."\" height=\"".$this->height."\">".
           "<param name=\"movie\" value=\"".$this->fileURL."\">".
           "<param name=\"quality\" value=\"".$this->quality."\">".
           "<param name=\"menu\" value=\"false\">".
           (($this->transparent)?"<param name=\"wmode\" value=\"transparent\" />":"").
           "<param name=\"scale\" value=\"".$this->scale."\">".
           "<param name=\"salign\" value=\"".$this->salign."\" />".
           "<param name=\"align\" value=\"lt\" />".
           "<PARAM NAME=\"FlashVars\" VALUE=\"".$args."\">".
           "<param name=\"allowScriptAccess\" value=\"always\" />".
           ( ($this->bgcolor)?"<param name=\"bgcolor\" value=\"".$this->bgcolor."\" />":"" ).
           "<embed ".
              "width=\"".$this->width."\" ".
              "height=\"".$this->height."\" ".
              "src=\"".$this->fileURL."\" ".
              "quality=\"".$this->quality."\" ".
              "menu=\"false\" ".
              (($this->transparent)?"wmode=\"transparent\"":"")." ".
              "scale=\"".$this->scale."\" ".
              "salign=\"".$this->salign."\" ".
              "FlashVars=\"".$args."\" ".
              "allowScriptAccess=\"always\" ".
              ( ($this->bgcolor)?"bgcolor=\"".$this->bgcolor."\"":"")." ".
              "pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" ".
              "type=\"application/x-shockwave-flash\">".
           "</embed>".
           "</object>";

     return $ret;
  }

  function showHTML() {
    echo $this->getHTML();
  }
}

Function get_flash_movie( $url, $width, $height, $args="", $quality="high", $trans=false )
{
  $swf = new FlashMovie( $url, $width, $height );

  if ( strlen($args) ) {
    $swf->setVarString($args);
  }

  $swf->setTransparent($trans);
  return $swf->getHTML();
}



function write_file($data, $path, $method='w')
{
  $fp = fopen($path, $method);
  if ( $fp ) {
    fwrite($fp, $data);
    fclose($fp);
  }
}

/**
 * This generates a number using a set of dice, like 1d6, or 5d2
 * @param int $number
 * @param int $value
 * @return int
 */
function dice( $number, $value )
{
  srand((double)microtime()*1000000);
  $total = 0;
  for ( $i = 0 ; $i < $number ; $i++ )
  {
    $total += rand(1, $value);
  }
  return $total;
}

function sendMail($to, $subject, $body, $from=null)
{
  if ( !$from ) $from = 'support@drunkduck.com';
  // FIXME: Blow off the queueing fu until we can figure out how it's working
  //db_query("INSERT INTO email_queue (from_email, to_email, subject_txt, body_txt) VALUES ('".db_escape_string($from)."', '".db_escape_string($to)."', '".db_escape_string($subject)."', '".db_escape_string($body)."')");
  // Just send the e-mail inline here for how
  mail($to, $subject, $body, 'From: '.$from);
}

function timestampToYears($TS)
{
  $SECONDS = time()-$TS;
  $DAYS    = $SECONDS/86400;
  $YEARS   = $DAYS/365.25;
  return $YEARS;
}

/**
 * Returns true if the user has the supplied flag(s).
 *
 * @param  INT  $flag
 * @param  BOOL $exact
 * @return BOOL
 */
function hasFlag( $fNow, $flag, $exact=true ) {

  if ( $exact )
    { return( $flag == ($fNow & $flag) ); }
  return( $fNow & $flag );
}

/**
 * Adds to the users flags and marks as dirty.
 *
 * @param INT $flags
 */
function giveFlag( $fNow, $flag )
{
  if ( !($fNow & $flag) )
    { $fNow |= $flag; }
  return $fNow;
}

/**
 * Removes the flag from the user and marks as dirty.
 *
 * @param INT $flags
 */
function takeFlag( $fNow, $flag )
{
  if ( $fNow & $flag )
    {$fNow &= ~$flag;}
  return $fNow;
}

/**
 * Toggles the state of a specific flag on the user and marks as dirty.
 *
 * @param INT $flag
 */
function toggleFlag( $fNow, $flag )
{
  if ( $fNow & $flag )
    { $fNow &= ~$flag; }
  else
    { $fNow |= $flag; }
  return $fNow;
}

function BBCode ($string)
{
  /*
   $search = array(
       '`\[quote\](.*?)\[\/quote\]`ms',
       '`\[b\](.*?)\[\/b\]`ms',
       '`\[i\](.*?)\[\/i\]`ms',
       '`\[u\](.*?)\[\/u\]`ms',
       '`\[img\](.*?)\[\/img\]`m',
       '`\[url\=(.*?)\](.*?)\[\/url\]`m',
       '`\[url\](.*?)\[\/url\]`m',
       '`\[user\](.*?)\[\/user\]`m',
       '`\[code\](.*?)\[\/code\]`ms'
   );
   $replace = array(
       '<div align="left" style="padding-left:125px;padding-top:25px;"><div align="left" style="padding:5px;border:1px solid #000000;"><B>QUOTE:</B><br><br>\\1</div></div>',
       '<b>\\1</b>',
       '<i>\\1</i>',
       '<u>\\1</u>',
       '<img src="\\1">',
       '<a href="\\1" target="_blank">\\2</a>',
       '<a href="\\1" target="_blank">\\1</a>',
       '<a href="http://'.DOMAIN.'/user_lookup.php?u=\\1">\\1</A>',
       '<code>\\1</code>'
   );
   return preg_replace($search, $replace, $string);
   */

    $search = array(
                    '`\[quote\](.*?)\[\/quote\]`msi',
                    '`\[quote\](.*?)\[\/quote\]`msi',
                    '`\[quote\](.*?)\[\/quote\]`msi',
                    '`\[quote\](.*?)\[\/quote\]`msi',
                    '`\[quote\](.*?)\[\/quote\]`msi',
                    '`\[quote\](.*?)\[\/quote\]`msi',

                    '`\[quote=(.*?)\](.*?)\[\/quote\]`msi',
                    '`\[quote=(.*?)\](.*?)\[\/quote\]`msi',
                    '`\[quote=(.*?)\](.*?)\[\/quote\]`msi',
                    '`\[quote=(.*?)\](.*?)\[\/quote\]`msi',
                    '`\[quote=(.*?)\](.*?)\[\/quote\]`msi',
                    '`\[quote=(.*?)\](.*?)\[\/quote\]`msi',

                    '`\[b\](.*?)\[\/b\]`msi',
                    '`\[i\](.*?)\[\/i\]`msi',
                    '`\[u\](.*?)\[\/u\]`msi',
                    '`\[img\](.*?)\[\/img\]`msi',
                    '`\[img width=(.*?) height=(.*?)\](.*?)\[\/img\]`msi',
                    '`\[img height=(.*?) width=(.*?)\](.*?)\[\/img\]`msi',
                    '`\[url=(.*?)\](.*?)\[\/url\]`msi',
                    '`\[url\](.*?)\[\/url\]`msi',
                    '`\[code\](.*?)\[\/code\]`msi',
                    '`\[color=(.*?)\](.*?)\[\/color\]`msi',
                    '`\[size=(.*?)\](.*?)\[\/size\]`msi',

                    '`\[swf width=(.*?) height=(.*?)\](.*?)\[\/swf\]`msi',
                    '`\[swf height=(.*?) width=(.*?)\](.*?)\[\/swf\]`msi',

                    '`\[spoiler\](.*?)\[\/spoiler\]`msi',
                    '`\[spoiler=(.*?)\](.*?)\[\/spoiler\]`msi'
                    );

    $QUOTE_STYLE = 'style="padding:5px;border-top: 1px solid; border-left: 1px solid ; border-right: 2px solid ; border-bottom: 2px solid;"';

    $replace = array(
                    '<div align="center"><div align="left" '.$QUOTE_STYLE.'> <b>QUOTE:</b> <br> \\1 </div></div>',
                    '<div align="center"><div align="left" '.$QUOTE_STYLE.'> <b>QUOTE:</b> <br> \\1 </div></div>',
                    '<div align="center"><div align="left" '.$QUOTE_STYLE.'> <b>QUOTE:</b> <br> \\1 </div></div>',
                    '<div align="center"><div align="left" '.$QUOTE_STYLE.'> <b>QUOTE:</b> <br> \\1 </div></div>',
                    '<div align="center"><div align="left" '.$QUOTE_STYLE.'> <b>QUOTE:</b> <br> \\1 </div></div>',
                    '<div align="center"><div align="left" '.$QUOTE_STYLE.'> <b>QUOTE:</b> <br> \\1 </div></div>',

                    '<div align="center"><div align="left" '.$QUOTE_STYLE.'> <b>\\1 Said:</b> <br> \\2 </div></div>',
                    '<div align="center"><div align="left" '.$QUOTE_STYLE.'> <b>\\1 Said:</b> <br> \\2 </div></div>',
                    '<div align="center"><div align="left" '.$QUOTE_STYLE.'> <b>\\1 Said:</b> <br> \\2 </div></div>',
                    '<div align="center"><div align="left" '.$QUOTE_STYLE.'> <b>\\1 Said:</b> <br> \\2 </div></div>',
                    '<div align="center"><div align="left" '.$QUOTE_STYLE.'> <b>\\1 Said:</b> <br> \\2 </div></div>',
                    '<div align="center"><div align="left" '.$QUOTE_STYLE.'> <b>\\1 Said:</b> <br> \\2 </div></div>',

                    '<b>\\1</b>',
                    '<i>\\1</i>',
                    '<u>\\1</u>',
                    '<img src="\\1" border="0" style="max-width:600px;">',
                    '<img src="\\3" height="\\2" width="\\1">',
                    '<img src="\\3" height="\\1" width="\\2">',
                    '<a href="\\1" target="_blank">\\2</a>',
                    '<a href="\\1" target="_blank">\\1</a>',
                    '<code>\\1</code>',
                    '<font color="\\1">\\2</font>',
                    '<font style="font-size:\\1px;">\\2</font>',

                    '<embed enableJavascript="false" allowScriptAccess="never" allownetworking="internal" wmode="transparent" type="application/x-shockwave-flash" src="\\3" width="\\1" height="\\2"></embed>',
                    '<embed enableJavascript="false" allowScriptAccess="never" allownetworking="internal" wmode="transparent" type="application/x-shockwave-flash" src="\\3" width="\\2" height="\\1"></embed>',

                    '<div style="color:#000000;background:#000000;" onMouseOut="this.style.background=\'#000000\';" onMouseOver="this.style.background=\'\';"><div align="center" style="color:#ff0000"><b>WARNING: *CONTAINS SPOILER*</b> - <i>Mouse over black box to read.</i></div>\\1</div>',
                    '<div style="color:#000000;background:#000000;" onMouseOut="this.style.background=\'#000000\';" onMouseOver="this.style.background=\'\';"><div align="center" style="color:#ffffff">\\1</div><div align="center" style="color:#ff0000"><b>WARNING: *CONTAINS SPOILER*</b> - <i>Mouse over black box to read.</i></div>\\2</div>'
                    );

     return preg_replace($search, $replace, $string);
}
?>
