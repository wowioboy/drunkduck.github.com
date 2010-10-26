jQuery(document).ready(function(){
                   jQuery('#searchTxt').keyup(function(){
                    if (search = jQuery(this).val()) {
                      jQuery.getJSON('/ajax/search.php', {search: search}, function(data) {
                        if (data) {
                        var html = '';
                        jQuery.each(data, function(){
                          var path = 'http://images.drunkduck.com/process/comic_' + this.id + '_0_T_0_sm.jpg';
                          html += '<a style="padding:0;" class="search_link" href="/' + this.title.replace(/ /g, '_') + '/">' + 
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