        <?php
/*        var_dump($USER);*/
        ?>
          <script>
          $(document).ready(function(){
            $('a.favorties').click(function(){
              var favoritesDiv = $('div.favorites');
              console.log(favoritesDiv.css('display'));
              if (favoritesDiv.css('display') == 'none') {
                $(this).html('collapse');
                $.getJSON(url, {}, function(data) {
                  favoritesDiv.slideDown();
                });
              } else { 
                $(this).html('expand');
                favoritesDiv.slideUp();
              }
            });
          });
          </script>
        <div class="panel-header yellow" style="float:right;padding:5px;text-transform:uppercase;font-family:helvetica;font-weight:bold;font-size:0.7em">
            <a href="?logout=<?php echo $USER->user_id; ?>">log out</a> | <a href="">help</a>
        </div>

        <div class="span-30 panel-body-right yellow">
          <div class="box-1">
            <div class="span-8">
                <div style="width:65px;height:65px;background-color:#FFF">
                </div>
            </div>
            <div class="span-20" style="font-family:'Yanone Kaffeesatz';font-weight:bold;line-height:30px;margin-top:-5px;margin-bottom:5px;font-size:30px;color:rgb(69,180,185);">Hi, <?php echo $USER->username; ?>!</div>
            <div class="span-20" style="font-size:10px;">User Control Panel</div>
            <div class="span-20" style="font-size:10px;">Personal Quacks</div>
            <div style="clear:both;height:10px"></div>
            
          <div class="drop-list rounded ">
            my favorites  <a class="favorties" href="javascript:">expand</a>
            <div class="favorites" style="display:none;">
            here are the favorites
            </div>
          </div>
          <div style="display:block;height:10px;"></div>
          <div class="drop-list rounded ">
            my webcomics  <a class="webcomics" href="javascript:">expand</a>
            <div class="webcomics" style="display:none;">
            here are the webcomics
            </div>
          </div>
          </div>
        </div>
    