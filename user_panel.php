        <?php
/*        var_dump($USER);*/
        ?>
          <script>
          $(document).ready(function(){
            var user_id = '<?php echo $USER->user_id; ?>';

            function getWebcomics()
            {
              var object = {};
              object.user_id = user_id;
              object.sort = $('select.webcomics').val();
              $.getJSON('/ajax/webcomics.php', object, function(data) {
                  var html = '';
                  $.each(data, function(){
                    var date = this.updated_on;
                    var title = this.title;
                    html += '<a class="subtitle" href="/' + title.replace(/ /g, '_') + '">' + title + '</a>' + ' ' + '<a href="/account/comic/?cid=' + this.comic_id + '">edit</a>' + ' ' + date + '<br />';
                  });
                  $('div.webcomics_display').html(html);
                });
            }
            
            function getFavorites() 
            {
              var object = {};
              object.user_id = user_id;
              object.sort = $('select.favorites').val();
              $.getJSON('/ajax/favorites.php', object, function(data) {
                  var html = '';
                  $.each(data, function(){
                    var date = this.updated_on;
                    var title = this.title;
                    html += '<a class="subtitle" href="/' + title.replace(/ /g, '_') + '">' + title + '</a>' + ' ' + date + '<br />'; 
                  });
                  $('div.favorites_display').html(html);
               });
            }

            $('img.favorties').click(function(){
              var favoritesDiv = $('div.favorites');
              if (favoritesDiv.css('display') == 'none') {
                $(this).attr('src', '/media/images/triangle-down.gif');
                getFavorites();
                  favoritesDiv.slideDown();
              } else { 
                $(this).attr('src', '/media/images/triangle.gif');
                favoritesDiv.slideUp();
              }
            });
            
            
            $('img.webcomics').click(function(){
              var webcomicsDiv = $('div.webcomics');
              if (webcomicsDiv.css('display') == 'none') {
                $(this).attr('src', '/media/images/triangle-down.gif');
                  getWebcomics();
                  webcomicsDiv.slideDown();
              } else { 
                 $(this).attr('src', '/media/images/triangle.gif');
                webcomicsDiv.slideUp();
              }
            });
            
            $('select.favorites').change(function(){
              getFavorites();
            });
            
           $('select.webcomics').change(function(){
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
            <div class="span-20" style="font-size:10px;"><a href="/control_panel/quacks.php">Personal Quacks</a></div>
            <div style="clear:both;height:10px"></div>
            
          <div class="drop-list rounded ">
            <div class="table fill">
              <div class="cell">my favorites</div>
              <div class="cell right">
              <img class="favorties" src="/media/images/triangle.gif" />
              </div>
            </div>
            <div class="favorites" style="display:none;">
            <select class="favorites">
              <option value="">sort by</option>
              <option value="alpha">alphabetically</option>
              <option value="update">last update</option>
            </select>
              <div class="favorites_display">
                here are the favorites
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
            <select class="webcomics">
              <option value="">sort by</option>
              <option value="alpha">alphabetically</option>
              <option value="update">last update</option>
            </select>
              <div class="webcomics_display">
                here are the webcomics
              </div>
            </div>
          </div>
          </div>
        </div>
    