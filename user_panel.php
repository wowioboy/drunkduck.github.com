<?php
        $db = DB::getInstance();
        $query = "select count(1) 
          from mailbox 
          where username_to = '{$USER->username}' 
          and username_from is not null 
          and viewed = '0'";
        $quackCount = $db->fetchOne($query);
        ?>
        <style type="text/css">
        .sidetitle {
          color:#3db7b8;
          font-size:8pt;
        }
        .sidedate {
          color:#000;
          font-size:8pt;
        }
        </style>
          <script>
          jQuery(document).ready(function(){
            var user_id = '<?php echo $USER->user_id; ?>';

            function getWebcomics()
            {
              var object = {};
              object.user_id = user_id;
              object.sort = jQuery('select.webcomics').val();
              jQuery.getJSON('/ajax/webcomics.php', object, function(data) {
                  var html = '';
                  jQuery.each(data, function(){
                    var date = this.updated_on;
                    var title = this.title;
                    var new_title = title;
                    if (title.length > 20) {
                      new_title = jQuery.trim(title.substring(0, 20)) + '...';
                    }
                    html += '<div class="table fill">' + 
                            '<div class="cell">' + 
                            '<a class="sidetitle" href="/' + title.replace(/ /g, '_') + '">' + new_title + '</a>' +
                            '</div>' + 
                            '<div class="cell right">' + 
                            '<a class="sidedate" href="/account/comic/?cid=' + this.comic_id + '">edit</a>' + 
                            '</div>' + 
                            '<div class="cell right" style="width:100px;">' +
                            '<span class="sidedate">' + date + '</span>' + 
                            '</div>' + 
                            '</div>';
                  });
                  jQuery('div.webcomics_display').html(html);
                });
            }
            
            function getFavorites() 
            {
              var object = {};
              object.user_id = user_id;
              object.sort = jQuery('select.favorites').val();
              jQuery.getJSON('/ajax/favorites.php', object, function(data) {
                  var html = '';
                  jQuery.each(data, function(){
                    var date = this.updated_on;
                    var title = this.title;
                    var new_title = title;
                    if (title.length > 20) {
                      new_title = jQuery.trim(title.substring(0, 20)) + '...';
                    }
                    html += '<div class="table fill">' + 
                            '<div class="cell">' + 
                            '<a class="sidetitle" href="/' + title.replace(/ /g, '_') + '">' + new_title + '</a>' +
                            '</div>' + 
                            '<div class="cell right" style="width:100px;">' +
                            '<span class="sidedate">' + date + '</span>' + 
                            '</div>' + 
                            '</div>'; 
                  });
                  jQuery('div.favorites_display').html(html);
               });
            }

            jQuery('img.favorties').click(function(){
              var favoritesDiv = jQuery('div.favorites');
              if (favoritesDiv.css('display') == 'none') {
                jQuery(this).attr('src', '/media/images/triangle-down.gif');
                getFavorites();
                  favoritesDiv.slideDown();
              } else { 
                jQuery(this).attr('src', '/media/images/triangle.gif');
                favoritesDiv.slideUp();
              }
            });
            
            
            jQuery('img.webcomics').click(function(){
              var webcomicsDiv = jQuery('div.webcomics');
              if (webcomicsDiv.css('display') == 'none') {
                jQuery(this).attr('src', '/media/images/triangle-down.gif');
                  getWebcomics();
                  webcomicsDiv.slideDown();
              } else { 
                 jQuery(this).attr('src', '/media/images/triangle.gif');
                webcomicsDiv.slideUp();
              }
            });
            
            jQuery('select.favorites').change(function(){
              getFavorites();
            });
            
           jQuery('select.webcomics').change(function(){
              getWebcomics();
            });
          });
          </script>
        <div class="panel-header yellow" style="float:right;padding:5px;text-transform:uppercase;font-family:helvetica;font-weight:bold;font-size:0.7em">
            <a href="?logout=<?php echo $USER->user_id; ?>">log out</a> | <a href="/community/view_category.php?cid=229">help</a>
        </div>

        <div class="span-30 panel-body-right yellow ">
          <div class="box-1">
            <div class="span-8">
                <div style="width:65px;height:65px;background-color:#FFF">
                 <img src="<?php echo 'http://drunkduck.com/gfx/avatars/avatar_'.$USER->user_id.".".$USER->avatar_ext; ?>" height="65" width="65" />
                </div>
            </div>
            <div class="span-20" style="font-family:'Yanone Kaffeesatz';font-weight:bold;line-height:30px;margin-top:-5px;margin-bottom:5px;font-size:30px;color:rgb(69,180,185);">Hi, <?php echo $USER->username; ?>!</div>
            <div class="span-20" style="font-size:10px;"><a href="/control_panel/account.php">User Control Panel</a></div>
            <div class="span-20" style="font-size:10px;"><a href="/control_panel/quacks.php">Personal Quacks<?php echo ($quackCount) ? " ($quackCount)" : ''; ?></a></div>
            <div style="clear:both;height:10px"></div>
            
          <div class="drop-list rounded ">
            <div class="table fill">
              <div class="cell">my favorites</div>
              <div class="cell right">
              <img class="favorties" src="/media/images/triangle.gif" />
              </div>
            </div>
            <div class="favorites" style="display:none;">
            <div class="table fill">
                <div class="cell right">
            <select class="favorites button" style="border:none;">
              <option value="">sort by</option>
              <option value="alpha">alphabetically</option>
              <option value="update">last update</option>
            </select>
                </div>
            </div>
              <div class="favorites_display">
              </div>
            </div>
          </div>
          <div style="display:block;height:10px;"></div>
          <div class="drop-list rounded ">
            <div class="table fill">
              <div class="cell">my webcomics</div>
              <div class="cell right">
              <img class="webcomics" src="/media/images/triangle.gif" />
              </div>
            </div>
            <div class="webcomics" style="display:none;">
              <div class="table fill">
                <div class="cell right">
            <select class="webcomics button" style="border:none;">
              <option value="">sort by</option>
              <option value="alpha">alphabetically</option>
              <option value="update">last update</option>
            </select>
                </div>
              </div>
              <div class="webcomics_display">
              </div>
            </div>
          </div>
          </div>
        </div>
    