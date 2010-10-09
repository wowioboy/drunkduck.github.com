    </div>
    
    <div id="loginbox" class="span-30 canary box-1" >
        <div class="">
            <?php require_once('auth.php') ?>
              
        </div>
    </div>
    <div class="span-30"><div class="box-1">
        <? include(WWW_ROOT.'/ads/ad_includes/main_template/300x250.html'); ?>
        <div style="height:10px;"></div>
        <? include(WWW_ROOT.'/ads/ad_includes/main_template/300x250.html'); ?>
    </div></div>
    
    <div class="span-96">&nbsp;</div>
    <div class="span-96 green rounded">
        <div class="prepend-33" style="position:relative;padding-bottom:50px;">
            <div id="menu" class="span-73">
            <?php 
            $nosearch = 1;
            require('navi_v2.php'); 
            ?>
            </div>
        </div>
        <br />
    </div>
    

</div>
</body>
</html>