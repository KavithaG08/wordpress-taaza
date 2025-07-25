jQuery.noConflict();
jQuery(document).ready(function($){
    "use strict";

    /**
     * Keyboard navigation links
     */
        if( $("ul.wdt-primary-nav").length ) {
            var $children = $("ul.wdt-primary-nav").find(".menu-item-has-children a");
            var $sub_menu = $("ul.wdt-primary-nav").find(".sub-menu a");
            var $a        = $("ul.wdt-primary-nav > li > a");

            $( $a ).focus(function() {
                $(this).parent("li").addClass('focus');
            }).blur(function(){
                $(this).parent("li").removeClass('focus');
            });

            $( $children ).focus(function(){
                $(this).parents(".menu-item-has-children").addClass('focus');
            }).blur(function(){
                $(this).parents(".menu-item-has-children").removeClass('focus');
            });

            $( $sub_menu ).focus(function(){
                $(this).parent("li").addClass('focus');
            }).blur(function(){
                $(this).parent("li").removeClass('focus');
            });
        }

        jQuery(document).on('keydown', function( event ) {

            if(!jQuery('body').hasClass('nav-is-visible')) {
                return;
            }

            var tabKey = event.keyCode === 9;
			var shiftKey = event.shiftKey;
			var escKey = event.keyCode === 27;

            if(escKey) {
                jQuery('.mobile-menu-overlay').trigger('click');
            }

            var activeItem = jQuery(':focus');
            if(activeItem.parent().hasClass('menu-item')) {

                if( shiftKey && tabKey ) {

                    var activeMenuItem = activeItem.parent('.menu-item');
                    if(activeMenuItem.prev().hasClass('menu-item-has-children')) {
                        event.preventDefault();
                        activeMenuItem.prev().find('a:first').focus();
                    }

                } else if( !shiftKey && tabKey ) {

                    var activeMenuItem = activeItem.parent('.menu-item');
                    if(activeMenuItem.next().hasClass('menu-item-has-children')) {
                        event.preventDefault();
                        activeMenuItem.next().find('a:first').focus();
                    } else if(activeMenuItem.hasClass('menu-item-has-children')) {
                        event.preventDefault();
                        activeMenuItem.next().find('a:first').focus();
                    } else if(activeMenuItem.attr('class') == jQuery('.mobile-menu .menu-item:last').attr('class')) {
                        event.preventDefault();
                        jQuery('.mobile-menu').find('.close-nav:first a').focus();
                    } else if(activeMenuItem.attr('class') == activeMenuItem.parent('.sub-menu').find('.menu-item:last').attr('class')) {
                        event.preventDefault();
                        activeMenuItem.parent('.sub-menu').find('.close-nav:first a').focus();
                    }

                }

            } else {

                if( shiftKey && tabKey ) {

                    if(activeItem.parent('li').hasClass('close-nav')){
                        event.preventDefault();
                        if(activeItem.closest('.sub-menu').length){
                            activeItem.closest('.sub-menu').find('.menu-item:last a').focus();
                        } else {
                            jQuery('.mobile-menu .menu-item-depth-0:last a').focus();
                        }
                    }

                }

            }

        });

        // Header Height
        var header_container_height = $('body > .wrapper > .inner-wrapper > #header-wrapper > header');
        var header_assign_height = $('body');
        if( header_container_height.length ) {
            header_container_height.each(function(){
                var foot_height = $(this).height();
                header_assign_height.css('--header-height', foot_height+'px');
            });
        }

        // Footer Height

        var footer_container_height = $('body.wdt-fixed-footer-enabled > .wrapper > .inner-wrapper > footer');
        var footer_assign_height = $('body.wdt-fixed-footer-enabled > .wrapper > .inner-wrapper');

        if( footer_container_height.length ) {
            footer_container_height.each(function(){
                var head_height = $(this).height();
                footer_assign_height.css('--footer-height', head_height+'px');
            });
        }

    /**
     * Desktop Menu Animation
     */

        $('.animate-menu-item').parents('.menu-item').find('a')
            .mouseenter(function() {
                var animation_class = $(this).parents('.menu-item').find('.animate-menu-item').attr('data-animation');
                $(this).parents('.menu-item').find('.animate-menu-item').addClass(animation_class);
            })
            .mouseleave(function() {
                var animation_class = $(this).parents('.menu-item').find('.animate-menu-item').attr('data-animation');
                $(this).parents('.menu-item').find('.animate-menu-item').removeClass(animation_class);
            });

    /**
     * Mobile Menu
     */

        var clicked = "false";
        $('.menu-trigger').on('click', function( event ){
            event.preventDefault();

            if (clicked == "false") {
                $('html, body').css('overflow', 'hidden');
                clicked = "true";

                var menuItem = $(this).parents('.wdt-header-menu').find('.wdt-primary-nav:not(.wdt-secondary-nav)').clone();

                // Remove animation Class
                $('[data-animation]', menuItem ).each(function(ix, ele ){
                    $(ele).removeClass('animate-menu-item');
                });

                $('<div class="mobile-menu" />').appendTo( $("body") );

                menuItem.appendTo('.mobile-menu');

                $('<div class="mobile-menu-overlay"></div>').appendTo( $("body") );

                $('.mobile-menu').toggleClass('nav-is-visible');

                $('.mobile-menu-overlay').toggleClass('is-visible');

                $('body').toggleClass('nav-is-visible');

                $('.mobile-menu').find('.close-nav:first a').focus();

                // Initializing mobile nav
                initializeMobileNav();

            } else {
                closeMobNav();
            }

        });

        // Closing Mobile Nav
        function closeMobNav() {

            var scrollPos = $(window).scrollTop();

            $('html, body').css('overflow', 'auto');
            clicked = "false";

            $('.mobile-menu, .mobile-menu-overlay').removeClass('is-visible');
            $('body').removeClass('nav-is-visible');

            $('.mobile-menu').remove();
            $('.mobile-menu-overlay').remove();

            $('.menu-trigger').focus();

            $(window).scrollTop(scrollPos);
        }

        // $('.menu-trigger').on('click', function( event ){

        //     var menuItem = $(this).parents('.wdt-header-menu').find('.wdt-primary-nav:not(.wdt-secondary-nav)').clone();

        //     // Remove animation Class
        //     $('[data-animation]', menuItem ).each(function(ix, ele ){
        //         $(ele).removeClass('animate-menu-item');
        //     });

        //     $('<div class="mobile-menu" />').appendTo( $("body") );
        //     menuItem.appendTo('.mobile-menu');
        //     $('<div class="mobile-menu-overlay"></div>').appendTo( $("body") );

        //     $('.mobile-menu').toggleClass('nav-is-visible');
        //     $('.mobile-menu-overlay').toggleClass('is-visible');
        //     $('body').toggleClass('nav-is-visible');

        //     $('.mobile-menu').find('.close-nav:first a').focus();

        //     // Initializing mobile nav
        //     initializeMobileNav();

        // });

        // Closing Mobile Nav
        // function closeMobNav() {
        //     $('body').removeClass('nav-is-visible');

        //     $('.mobile-menu').remove();
        //     $('.mobile-menu-overlay').remove();

        //     $('.menu-trigger').focus();
        // }

        // Initialize mobile nav
        function initializeMobileNav() {

            $('li.close-nav').on('click', function(event) {
                closeMobNav();
            });

            $('.mobile-menu-overlay').on('click', function(event) {
                closeMobNav();
            });

            // Sub Menu in Mobile Menu
            $('.menu-item-has-children > a, .page_item_has_children > a').on('click', function(event) {
                if ( $('body').hasClass('nav-is-visible') ) {
                    event.preventDefault();
                    var a = $(this).clone();
                    $(this).next('.sub-menu').find('.see-all').html(a);
                }

                var selected = $(this);
                if( selected.next('ul').hasClass('is-hidden') ) {
                    selected.next('ul.sub-menu').removeClass('is-hidden');
                } else {
                    selected.next('ul.sub-menu').addClass('is-hidden');
                }
            });

            $('.menu-item-has-children > a, .page_item_has_children > a').on('click', function(event) {

                var selected = $(this);
                selected.next('.sub-menu:not(.is-hidden)').find('a:first').focus();

            });

            // Go Back in Mobile Menu
            $('.go-back').on('click', function(event) {
                $(this).parent('ul:not(.menu)').addClass('is-hidden');
                event.preventDefault();
                $(this).parents('.menu-item').find('a:first').focus();
            });

        }

        // For Video Post
        if( $("div.wdt-video-wrap").length ) {
           $("div.wdt-video-wrap").fitVids();
        }

        // Smart Resize
        $(window).on("resize", function() {
            // Blog Isotope
            if( $(".apply-isotope").length ) {
                $(".apply-isotope").isotope({itemSelector : '.column',transformsEnabled:false,masonry: { columnWidth: '.grid-sizer' } });
            }
        });

        $(window).on('load', function() {

            // Gallery Post Slider
            if( ($("ul.entry-gallery-post-slider").length) && ( $("ul.entry-gallery-post-slider li").length > 1 ) ){
                $("ul.entry-gallery-post-slider").bxSlider({mode: 'fade', auto:false, video:true, pager:'', autoHover:true, adaptiveHeight:false, responsive: true });
            }
        
            // Blog Isotope
            if( $(".apply-isotope").length ) {
                $(".apply-isotope").isotope({itemSelector : '.column',transformsEnabled:false,masonry: { columnWidth: '.grid-sizer' } });
            }
        
            // Blog Equal Height
            if( $('.tpl-blog-holder.apply-equal-height').length ) {
                $(".tpl-blog-holder.apply-equal-height article").matchHeight({ property:"min-height" });
            }
        });

        if( $('.single .entry-thumb.single-preview-img a.mag-pop, a.lightbox-preview-img').length ) {
            $('.single .entry-thumb.single-preview-img a.mag-pop, a.lightbox-preview-img').magnificPopup({
                type: 'image',
                closeOnContentClick: false,
                closeBtnInside: false,
                mainClass: 'mfp-with-zoom mfp-img-mobile',
                image: {
                  verticalFit: true,
                  titleSrc: function(item) {
                    return item.el.attr('title') + ' &middot; <a class="image-source-link" href="'+item.el.attr('href')+'" target="_blank">image source</a>';
                  }
                },
                zoom: {
                  enabled: true,
                  duration: 300, // don't foget to change the duration also in CSS
                  opener: function(element) {
                    return element.find('img');
                  }
                }
            });
        }

        $("select:not(.dt-select-service,.dt-select-staff,.start-time,.end-time,.wdt-sf-field)").each(function() {
            $(this).select2();
        });
        $('form.wpcf7-form input').each(function(){
            $(this).parent("p").addClass('with-spinner');
        });

          // Contact form 7 Date and time picker 
        $('form.wpcf7-form').each(function(){
            flatpickr(".wdtdatetimePicker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                disableMobile: "true"
            });

            flatpickr(".wdtdatePicker", {
                enableTime: false,
                dateFormat: "Y-m-d",
                disableMobile: "true"
            });

            flatpickr(".wdttimePicker", {
                enableTime: true,
                noCalendar: true,
                disableMobile: "true",
                dateFormat: "H:i",
            });
        });

});

/* Live Search*/
jQuery( 'body' ).delegate( '.text_input', 'keypress', function(e) {

    if (jQuery('.text_input').is(":focus")) {

    var this_item = jQuery(this),
    search_val = this_item.val();
        if(search_val == "" ){
            jQuery('.quick_search_results').html("");
            jQuery('.quick_search_results').removeClass('active');
        } else {
                jQuery.ajax({
                    type:"POST",
                    url: taaza_urls.ajaxurl,
                    data: {
                        action:'taaza_search_data_fetch',
                        search_val:search_val,
                        ajax_call: true,
                        function_call: 'taaza_search_data_fetch',
                        security: ajax_object.ajax_nonce
                    },
                    success:function(data){
                        jQuery('.quick_search_results').addClass('active');
                        jQuery('.quick_search_results').html(data);
                    }
                });
            }
    }
});