            <?php if (!$nosearch) : ?>
            <div style="display:inline;background-image:url('/media/images/search.jpg');width:154px;height:20px" class="span-16">
              <style type="text/css">
      .result {
        margin:0;
        padding:5px;
        background-color:#e5e5e5;
        border:1px solid #000;
        background:-webkit-gradient(
                linear,
                left bottom,
                left top,
                color-stop(0.08, rgb(200,210,212)),
                color-stop(0.77, rgb(255,255,255))
            );
        background:-moz-linear-gradient(
                  center bottom,
                  rgb(200,210,212) 8%,
                  rgb(255,255,255) 77%
              );
        background:filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#c8d2d4, endColorstr=#ffffff);
        background:-ms-filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#c8d2d4, endColorstr=#ffffff, GradientType=1);
        color:#000;
      }
      .topless {
        border-top-style:none;
      }
      .result img {
        border:none;
      }
      #search_results {
        width:300px;
        position:absolute;
        z-index:9999;
        display:none;
      }
      a.search_link {
        padding:0;
        margin:0;
      }
      </style>
              <script type="text/javascript">
                 jQuery(document).ready(function(){
                   jQuery('#searchTxt').keyup(function(){
                    if (search = jQuery(this).val()) {
                      jQuery.getJSON('/ajax/search.php', {search: search}, function(data) {
                        if (data) {
                        var html = '';
                        jQuery.each(data, function(){
                          var path = 'http://images.drunkduck.com/process/comic_' + this.id + '_0_T_0_sm.jpg';
                          html += '<a style="padding:0;" class="search_link" href="http://www.drunkduck.com/' + this.title.replace(/ /g, '_') + '">' + 
                                 '<div class="result">' +
                                    '<div style="display:inline-block;width:50px;height:50px;"><img width="50" height="50" src="' + path + '" border="1" style="border:1px #000 solid;"/></div>' +
                                    '&nbsp;&nbsp;<span style="font-size:18px;font-style:helvetica;">' + this.title + '</span>' +
                              '</div>' + 
                               '</a>';
                        });
                        jQuery('#search_results').html(html).show();
                        }
                      });
                    } else {
                      jQuery('#search_results').fadeOut('fast');
                    }
                   });
                    var preventBlur = false;
                    jQuery('#search_results').mouseenter(function() {
                      preventBlur = true;
                    });
                    jQuery('#search_results').mouseleave(function() {
                      preventBlur = false;
                    });
                    jQuery('#searchTxt').blur(function(){
                      if (!preventBlur) {
                        jQuery('#search_results').fadeOut('fast');
                      }
                    });
                    jQuery('#searchTxt').focus(function(){
                      var search = jQuery.trim(jQuery(this).val());
                      if (search != '') {
                        jQuery('#search_results').fadeIn('fast');
                      }
                    });
                 });
                 
              </script>
                <form action="/search.php" method="get" style="padding:0px;height:20px;border:0px;vertical-align:top;position:relative;left:-2px;">
                    <input type="text" id="searchTxt"  autocomplete="off" name="searchTxt" class="searchbox" style="position:absolute;top:0px;left:10px;margin:0;height:20px;border:0px;width:120px;outline:none;" />
                    <input type="image" style="position:absolute;top:0px;right:0px;" src="/media/images/search-placeholder.gif" class="searchbox_submit" />
                </form>
                <div id="search_results"></div>
            </div>

                <?php endif; ?>
            
            <!-- menu -->
            <a href="/search.php">comics</a>
            <a href="/account/overview/add_comic.php">create</a>
            <a href="/news_v2.php">news</a>
            <a href="/tutorials/">tutorials</a>
            <!--<a href="#">videos</a>-->
            <a href="http://www.drunkduck.com/community">forums</a>
            <a href="http://store.drunkduck.com">store</a>
