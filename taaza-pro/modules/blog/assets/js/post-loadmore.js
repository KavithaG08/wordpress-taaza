(function ($) {
    "use strict";
	$(document).ready(function () {
        //Ajax masonry load more items
        $('.wdt-posts-list-wrapper').each(function(){
            var $page = 1;
            var $T = $(this);
            var $data = "", $content = $T.find('.tpl-blog-holder');

            // When load more button click...
            $('.wdt-posts-list-wrapper').on( "click", ".loadmore-btn.more-items", function() {

              var $this = $(this);
              var $count = $this.attr('data-count'), $cats = $this.attr('data-cats'), $style = $this.attr('data-style'),
              $layout = $this.attr('data-layout'), $column = $this.attr('data-column'), $list_type = $this.attr('data-listtype'),
              $hover = $this.attr('data-hover'), $overlay = $this.attr('data-overlay'), $align = $this.attr('data-align'),
              $mpages = $this.attr('data-maxpage'), $pos = $this.attr('data-pos'), $eheight = $this.attr('data-eheight'),
              $meta = $this.attr('data-meta'), $blogpostloadmore_nonce = $this.attr('data-blogpostloadmore-nonce');

              if( $meta != '' ) {
                  $meta = JSON.parse( $meta );
              }

              $content.addClass('loading');

              if( $this.hasClass('more-items') ) {
                $page++;
              }

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
                        $T.find('.loadmore-btn').attr('data-pos', $res[1]);

                        var newbx = $content.find('ul.entry-gallery-post-slider');
                        if( newbx !== null ) {
                            newbx.bxSlider({mode: 'fade', auto:false, video:true, pager:'', autoHover:true, adaptiveHeight:false, responsive: true});
                        }

                        if( $eheight == null || $eheight == false ) {
                            $content.css({overflow:'hidden'}).isotope( 'reloadItems' ).isotope();
                            $(window).trigger( 'resize' );
                        }

                        if( parseInt( $page ) >= parseInt( $mpages ) ) {
                            $T.find('.loadmore-btn').removeClass('more-items').html('All Posts Loaded');
                        } else {
                            $T.find('.loadmore-btn').addClass('more-items');
                        }
                    }
                    $content.removeClass('loading');
                 },
                 error: function (jqXHR, textStatus, errorThrown) {
                    $content.html('No More Posts Found');
                 }
              });
              return false;
            });
        });
    });
})(jQuery);