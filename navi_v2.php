            <?php if (!$nosearch) : ?>
            <div style="float:left;display:inline;background-image:url('/media/images/search.png');width:153px;height:20px" class="span-16">
              <link type="text/css" rel="stylesheet" href="/css/search.css" />
              <script type="text/javascript" src="/js/search.js"></script>
                <form id="dd-navigation" action="/search.php" method="get" style="padding:0px;height:20px;border:0px;vertical-align:top;position:relative;left:-2px;">
                    <input type="text" id="searchTxt"  autocomplete="off" name="searchTxt" class="searchbox" style="position:absolute;top:0px;left:10px;margin:0;height:20px;border:0px;width:120px;outline:none;" />
                    <input type="image" style="background:none;outline:none;padding:0;border:none;position:absolute;top:0px;right:0px;" src="/media/images/search-placeholder.gif" class="searchbox_submit" />
                </form>
                <div id="search_results"></div>
            </div>

                <?php endif; ?>
            
            <!-- menu -->
            <div style="float:left;height:10px;line-height:12px;padding:0;text-decoration:none;vertical-align:baseline;width:20px;">
            
            </div>
            <a href="/search.php">comics</a>
            <a href="/account/overview/add_comic.php">create</a>
            <a href="/news.php">news</a>
            <a href="/tutorials/">tutorials</a>
            <!--<a href="#">videos</a>-->
            <a href="/community">forums</a>
            <a href="http://store.drunkduck.com">store</a>
            
            <?php if ($nosearch) : ?>
            <a href="/games">games</a>
            <?php endif; ?>
