(function ($) {
    "use strict";
	$(document).ready(function () {
        //Ajax masonry load more items
        $('.wdt-posts-list-wrapper').each(function(){
            var $page = 1;
            var $T = $(this);
            var $data = "", $content = $T.find('.tpl-blog-holder');

            $(window).scroll(function(){
                var $c = $T.find('.infinite-btn.more-items');

                var ST = $(window).scrollTop();
                var DH = $(document).height();
                var WH = $(window).height();

                if( ( parseInt(ST) == parseInt(DH) - parseInt(WH) ) && $c.length > 0 ){

                    var $count = $c.attr('data-count'), $cats = $c.attr('data-cats'), $style = $c.attr('data-style'), $layout = $c.attr('data-layout'),
                    $column = $c.attr('data-column'), $list_type = $c.attr('data-listtype'), $hover = $c.attr('data-hover'),
                    $overlay = $c.attr('data-overlay'), $align = $c.attr('data-align'), $mpages = $c.attr('data-maxpage'),
                    $pos = $c.attr('data-pos'), $eheight = $c.attr('data-eheight'), $meta = $c.attr('data-meta'),
                    $blogpostloadmore_nonce = $c.attr('data-blogpostloadmore-nonce');

                    if( $meta != '' ) {
                        $meta = JSON.parse( $meta );
                    }

                    $content.addClass('loading');

                    $page++;

                    $.ajax({
                       type : "post",
                       dataType : "html",
                       url : taaza_urls.ajaxurl,
                       data : { action: "blog_archive_load_more_post", count: $count, cats: $cats, pos: $pos, style: $style, layout: $layout, column: $column, pageNumber: $page, listtype: $list_type, hover: $hover, overlay: $overlay, align: $align, meta: $meta, blogpostloadmore_nonce: $blogpostloadmore_nonce },
                       cache: true,
                       success: function (data) {
                          var $res = data.split('#####$$$$$');
                          if ( $res.length > 0 ) {

                              $content.append($res[0]);
                              $T.find('.infinite-btn').attr('data-pos', $res[1]);

                              var newbx = $content.find('ul.entry-gallery-post-slider');
                              if( newbx !== null ) {
                                  newbx.bxSlider({mode: 'fade', auto:false, video:true, pager:'', autoHover:true, adaptiveHeight:false, responsive: true});
                              }

                              if( $eheight == null || $eheight == false ) {
                                  $content.css({overflow:'hidden'}).isotope( 'reloadItems' ).isotope();
                                  $(window).trigger( 'resize' );
                              }

                              if( parseInt( $page ) >= parseInt( $mpages ) ) {
                                  $c.removeClass('more-items');
                              }
                          }
                          $content.removeClass('loading');
                       },
                       error: function (jqXHR, textStatus, errorThrown) {
                          $content.html('No More Posts Found');
                       }
                    });
                    return false;
                }
            });
        });
    });
})(jQuery);