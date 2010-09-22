        <?php
/*        var_dump($USER);*/
        ?>
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
            
            <div class="drop-list rounded " style="text-transform:uppercase;padding-left:20px;">my favorites</div>
            <div style="display:block;height:10px;"></div>
            <div class="drop-list rounded " style="text-transform:uppercase;padding-left:20px;">my webcomics</div>
          </div>
        </div>