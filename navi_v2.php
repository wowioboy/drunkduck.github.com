        <div id="menu" class=" span-73">
            <!-- search bar -->
           <!-- <div style="background-image:url('/search.jpg');width:149px;height:21px;background-repeat:none;display:inline-block;margin-bottom:10px;padding-left:5px;" class="rounded">search</div> -->
            <!-- <div id="searchwrapper" class="rounded" style="background-color:#fff;display:inline-block;width:100px;height:25px;"> -->
            <div style="margin-top:10px;display:inline;background-color:red;background-image:url('/media/images/search.jpg');width:154px;height:21px" class="span-16">
            
              <script>
                 $(document).ready(function(){
                   $('#searchTxt').keyup(function(){
                     //console.log('boom doggy');
//                   var search = $(this).val();
                     $.getJSON('/ajax/search.php', {search: search}, function(data) {
                     });
                   });
                 });
              </script>
                <form action="search.php">
                    <input type="text" id="searchTxt" name="searchTxt" class="searchbox" value="" style="border:none;width:130px;" />
                    <input type="image" style="" src="/" class="searchbox_submit" value="" />
                </form> 
            </div>
            
            <!-- Advanced Search Link-->
            <a href="#" style="text-decoration:none;color:rgb(100,133,118);font-family:Verdana;font-size:8px;">Advanced Search</a>
            
            <!-- menu -->
            <a href="/search.php">browse</a>
            <a href="#">create</a>
            <a href="/news_v2.php">news</a>
            <a href="/signup">tutorials</a>
            <a href="#">videos</a>
            <a href="/community">forums</a>
            <a href="http://store.drunkduck.com">store</a>
</div>