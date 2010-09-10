<?
// TODO
function thumb($filename, $destination, $th_width, $th_height, $forcefill)
{    
   list($width, $height) = getimagesize($filename);

   preg_match("'^(.*)\.(gif|jpe?g|png)$'i", $filename, $ext);
   
   switch (strtolower($ext[2])) {
       case 'jpg': 
       case 'jpeg': 
        $source  = imagecreatefromjpeg ($imagePath);
       break;
       case 'gif': 
        $source  = imagecreatefromgif  ($imagePath);
       break;
       case 'png': 
        $source  = imagecreatefrompng  ($imagePath);
       break;
       default: 
        die('error, img type: '.$ext[2]);
       break;
   }
   
   $source = imagecreatefromjpeg($filename);

   if($width > $th_width || $height > $th_height){
     $a = $th_width/$th_height;
     $b = $width/$height;

     if(($a > $b)^$forcefill)
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
//     imagecopyresized($thumb, $source, 0, 0, $src_rect_xoffset, $src_rect_yoffset, $th_width, $th_height, $src_rect_width, $src_rect_height);

     switch (strtolower($ext[2])) {
         case 'jpg': 
         case 'jpeg': 
          imagejpeg($thumb, $destination);
         break;
         case 'gif': 
          imagegif($thumb,$destination);
         break;
         case 'png': 
          imagepng($thumb,$destination);
         break;
     }
   
   }
}
?>