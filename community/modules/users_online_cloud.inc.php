<?

$CLOUD_SIZES     = array();
$CLOUD_SIZES[0]  = 'font-size:9px;font-weight:normal;';
$CLOUD_SIZES[1]  = 'font-size:9px;font-weight:bold;';
$CLOUD_SIZES[2]  = 'font-size:10px;font-weight:bold;';

$CLOUD_SIZES[3]  = 'font-size:12px;font-weight:normal;';
$CLOUD_SIZES[4]  = 'font-size:12px;font-weight:bold;';
$CLOUD_SIZES[5]  = 'font-size:13px;font-weight:bold;';

$CLOUD_SIZES[6]  = 'font-size:15px;font-weight:normal;';
$CLOUD_SIZES[7]  = 'font-size:15px;font-weight:bold;';
$CLOUD_SIZES[8]  = 'font-size:16px;font-weight:bold;';

$CLOUD_SIZES[9]  = 'font-size:18px;font-weight:bold;text-transform:uppercase;';

$res = db_query("SELECT user_id, username, flags, forum_post_ct FROM users WHERE last_seen>=".(time()-(60*15))." ORDER BY user_id ASC");
?>
<div align="center" class="user_list">
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td bgcolor="#001D37" style="color:white;">
        <?
        $LARGEST_COUNT = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

        $ADMINS     = array();
        $ADMINS[ '<a href="http://'.USER_DOMAIN.'/Volte6">Volte6</a>' ] = 1;
        $ADMINS[ '<a href="http://'.USER_DOMAIN.'/Black_Kitty">Black_Kitty</a>' ] = 1;
        $ADMINS[ '<a href="http://'.USER_DOMAIN.'/SpANG">SpANG</a>' ] = 1;
        $ADMINS[ '<a href="http://'.USER_DOMAIN.'/Skoolmunkee">Skoolmunkee</a>' ] = 1;
        $ADMINS[ '<a href="http://'.USER_DOMAIN.'/Ronson">Ronson</a>' ] = 1;
        $ADMINS[ '<a href="http://'.USER_DOMAIN.'/ozoneocean">ozoneocean</a>' ] = 1;


        $MODERATORS = array();
        $USERS      = array();
        while($row = db_fetch_object($res))
        {
          if ( ($row->flags & FLAG_IS_ADMIN) ) {
            //$ADMINS[ '<a href="http://'.USER_DOMAIN.'/'.$row->username.'">'.$row->username.'</a>' ] = $row->forum_post_ct;
            //$USERS[ $row->username ] = $row->forum_post_ct;
          }
          else if ( ($row->flags & FLAG_IS_MOD) ) {
            $MODERATORS[ '<a href="http://'.USER_DOMAIN.'/'.$row->username.'">'.$row->username.'</a>' ] = $row->forum_post_ct;
          }
          else
          {
            $USERS[ $row->username ] = $row->forum_post_ct;
            foreach( $LARGEST_COUNT as $key=>$amt )
            {
              if ( $row->forum_post_ct > $amt )
              {
                $LARGEST_COUNT[$key] = $row->forum_post_ct;
                break;
              }
            }
          }
        }



          if ( count($ADMINS) )
          {
            ?>
            <div align="center">
              <?=number_format(count($ADMINS))?> Admin Contacts:
              <br>
              <?=implode(", ", array_keys($ADMINS) )?>
            </div>
            <?
          }



          if ( count($MODERATORS) )
          {
            ?>
            <br>
            <div align="center">
              <?=number_format(count($MODERATORS))?> Moderators Online:
              <br>
              <?=implode(", ", array_keys($MODERATORS) )?>
            </div>
            <?
          }

          ?>
          <br>
          <div align="center">
            <?=number_format(count($USERS))?> Users Online:
            <br>
            <?
            foreach( $USERS as $u=>$posts)
            {

              $spot = 0;
              foreach( $LARGEST_COUNT as $key=>$amt )
              {
                if ( $amt <= $posts )
                {
                  $spot = count($CLOUD_SIZES)-1-$key;
                  break;
                }
              }
              ?><a href="http://<?=USER_DOMAIN?>/<?=$u?>" style="<?=$CLOUD_SIZES[$spot]?>" title="<?=number_format($posts)?> posts" alt="<?=number_format($posts)?> posts"><?=$u?></a> &nbsp;&nbsp;&nbsp;<?
            }
            ?>
          </div>
      </td>
    </tr>
  </table>
</div>