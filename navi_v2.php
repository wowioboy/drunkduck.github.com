        <div id="menu" class=" span-73">
            <!-- search bar -->
           <!-- <div style="background-image:url('/search.jpg');width:149px;height:21px;background-repeat:none;display:inline-block;margin-bottom:10px;padding-left:5px;" class="rounded">search</div> -->
            <!-- <div id="searchwrapper" class="rounded" style="background-color:#fff;display:inline-block;width:100px;height:25px;"> -->
            <?php if (!$nosearch) : ?>
            <div style="margin-top:10px;display:inline;background-color:red;background-image:url('/media/images/search.jpg');width:154px;height:21px" class="span-16">
              <style>
      .result {
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
      </style>
              <script>
                 $(document).ready(function(){
                   $('#searchTxt').keyup(function(e){
                   e.preventDefault();
                    if (search = $(this).val()) {
                      $.getJSON('/ajax/search.php', {search: search}, function(data) {
                        if (data) {
                        var html = '';
                        $.each(data, function(){
                          var path = 'http://www.drunkduck.com/comics/' + this.charAt(0) + '/' + this.replace(/ /g, '_') + '/gfx/thumb.jpg';
                          html += '<a class="search_link" href="http://www.drunkduck.com/' + this.replace(/ /g, '_') + '">' + 
                                 '<div class="result">' +
                                '<table width="100%" cellspacing="5">' +
                                  '<tr>' +
                                    '<td width="50"><img width="50" height="50" src="' + path + '" border="1" style="border:1px #000 solid;"/></td>' +
                                    '<td align="left"><b>' + this + '</b>' +
                                  '</td>' + 
                                  '</tr>' +
                                   
                                '</table>' + 
                              '</div>' + 
                               '</a>';
                        });
                        $('#search_results').html(html).show();
                        }
                      });
                    } else {
                      $('#search_results').fadeOut('fast');
                    }
                   });
                    var preventBlur = false;
                    $('#search_results').mouseenter(function() {
                      preventBlur = true;
                    });
                    $('#search_results').mouseleave(function() {
                      preventBlur = false;
                    });
                    $('#searchTxt').blur(function(){
                      if (!preventBlur) {
                        $('#search_results').fadeOut('fast');
                      }
                    });
                    $('#searchTxt').focus(function(){
                      var search = $.trim($(this).val());
                      if (search != '') {
                        $('#search_results').fadeIn('fast');
                      }
                    });
                 });
                 
              </script>
                <form action="search.php" method="get">
                    <input type="text" id="searchTxt" name="searchTxt" class="searchbox" style="border:none;width:130px;" />
                    <input type="image" style="" src="/" class="searchbox_submit" />
                </form>
                <div id="search_results"></div>
                <?php endif; ?>
            </div>
            
            <?php if (!$nosearch) : ?>
            <!-- Advanced Search Link-->
            <a href="/search.php" style="text-decoration:none;color:rgb(100,133,118);font-family:Verdana;font-size:8px;">Advanced Search</a>
            <?php endif; ?>
            
            <!-- menu -->
            <a href="/search.php">browse</a>
            <a href="#">create</a>
            <a href="/news_v2.php">news</a>
            <a href="/signup">tutorials</a>
            <a href="#">videos</a>
            <a href="/community">forums</a>
            <a href="http://store.drunkduck.com">store</a>
</div>