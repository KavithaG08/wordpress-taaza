( function( $ ) {

	var wdtShopProducts = function($scope, $){

		//Product Pagination 

		jQuery( 'body' ).delegate( '.wdt-product-pagination a', 'click', function(e) {

			var this_item = jQuery(this);

			// Pagination Data
			if(this_item.parent().hasClass('prev-post')) {
				var current_page = parseInt(this_item.attr('data-currentpage'), 10)-1;
			} else if(this_item.parent().hasClass('next-post')) {
				var current_page = parseInt(this_item.attr('data-currentpage'), 10)+1;
			} else {
				var current_page = this_item.text();
			}

			var post_per_page = this_item.parents('.wdt-product-pagination').attr('data-postperpage');

			if(current_page == 1) {
				var offset = 0;
			} else if(current_page > 1) {
				var offset = ((current_page-1)*post_per_page);
			}

			var function_call = this_item.parents('.wdt-product-pagination').attr('data-functioncall');
			var output_div = this_item.parents('.wdt-product-pagination').attr('data-outputdiv');

			var shortcodeattrs = this_item.parents('.wdt-product-pagination').attr('data-shortcodeattrs');

			var productpagination_nonce = this_item.parents('.wdt-product-pagination').attr('data-productpagination-nonce');


			// Ajax call
			jQuery.ajax({
				type: "POST",
				url: wdtShopScObjects.ajaxurl,
				data:
				{
					action: function_call,
					current_page: current_page,
					offset: offset,
					post_per_page: post_per_page,
					function_call: function_call,
					output_div: output_div,
					shortcodeattrs: shortcodeattrs,
					productpagination_nonce: productpagination_nonce
				},
				beforeSend: function(){
					this_item.parents('.'+output_div).prepend( '<div class="wdt-product-loader"><i class="fa fa-spinner fa-spin"></i></div>' );
				},
				success: function (response) {
					this_item.parents('.'+output_div).replaceWith(response);
				},
				complete: function(){
					this_item.parents('.'+output_div+' .wdt-product-loader').remove();
				}
			});

			e.preventDefault();

		});


		// Product carousel
		
		const $carouselItem = $scope.find('.wdt-products-container.woocommerce.swiper-container');
		const $carouseleffect = $carouselItem.data('carouseleffect');
		const $carouselslidesperview = $carouselItem.data('carouselslidesperview');
		const $carouselslidespercolumn = $carouselItem.data('carouselslidespercolumn');
		const $carouselloopmode = $carouselItem.data('carouselloopmode');
		const $carouselmousewheelcontrol = $carouselItem.data('carouselmousewheelcontrol');
		const $carouselbulletpagination = $carouselItem.data('carouselbulletpagination');
		const $carouselarrowpagination = $carouselItem.data('carouselarrowpagination');
		const $carouselscrollbar = $carouselItem.data('carouselscrollbar');
		const $carouselspacebetween = $carouselItem.data('carouselspacebetween');
		const $carouselresponsive = $carouselItem.data('carouselresponsive');
		const $moduleId = $carouselItem.data('id');
		

		if($carouselresponsive === undefined) {
			return;
		  }

		const wooswiperOptions = {
			initialSlide: 0,
			simulateTouch: true,
			// roundLengths: true,
			keyboardControl: true,
			paginationClickable: true,
			autoHeight: autoheight,
			grabCursor: true,

			slidesPerView: $carouselslidesperview,
			// slidesPerColumn: $carouselslidespercolumn,
			// fill: 'row',
			loop: $carouselloopmode,
			// loopFillGroupWithBlank: $carouselloopmode,
			direction: 'horizontal',
		}

		if ($carouselspacebetween != ''){
			wooswiperOptions.spaceBetween = $carouselspacebetween;
		}

		if ($carouselmousewheelcontrol != ''){
			wooswiperOptions.mousewheel = $carouselmousewheelcontrol;
		}

		if ($carouseleffect != ''){
			wooswiperOptions.effect = $carouseleffect;
		}

		 // Update breakpoints
		 const $responsiveSettings = $carouselresponsive['responsive'];
		 const $responsiveData = {};
		 $.each($responsiveSettings, function (index, value) {
		   $responsiveData[value.breakpoint] = {
			 slidesPerView: value.toshow,
		   };
		 });
		 wooswiperOptions['breakpoints'] = $responsiveData;



		//auto height
		var autoheight = true;
		if($carouseleffect == 'multirow') {
			autoheight = false;
		}

		//Multiple row
		if ($carouseleffect == 'multirow') {
			wooswiperOptions.grid = {
				rows: $carouselslidespercolumn,
				fill: 'row',
			};
		}

		// Arrow pagination
		if ($carouselarrowpagination == true) {
			wooswiperOptions.navigation = {
				prevEl: '.wdt-products-arrow-prev-'+$moduleId,
				nextEl: '.wdt-products-arrow-next-'+$moduleId
			};
		}

		// Bullets pagination
		if ($carouselbulletpagination == true) {
			wooswiperOptions.pagination = {
				el: ".wdt-products-bullet-pagination",
				type: 'bullets',
				clickable: true,
			};
		}

		// Scrollbar pagination
		if ($carouselscrollbar == true) {
			wooswiperOptions.scrollbar = {
				el: ".wdt-products-scrollbar",
				dragSize: 30,
				draggable: true,
				hide: false,
			};
		}
		
		const swiperWoo = new Swiper('.wdt-products-carousel-'+$moduleId, wooswiperOptions);

		if($scope.hasClass('elementor-element-edit-mode')) {

			// Loading option CSS near html element
			var customCSS = jQuery('body', parent.document).find('style[id="taaza-woo-non-archive-inline-css"]').text();
			jQuery('body', parent.document).find('style[id="taaza-woo-non-archive-inline-css"]').remove();
			jQuery('.elementor-widget-wdt-shop-products.elementor-element-edit-mode').find('.wdt-products-container').append('<style id="wdt-edit-mode-shop-style">'+customCSS+'</style>');

			// Loading option JS near html element
			var customJS = jQuery('body', parent.document).find('script[id="taaza-woo-non-archive-js-after"]').text();
			jQuery('body', parent.document).find('script[id="taaza-woo-non-archive-js-after"]').remove();
			jQuery('.elementor-widget-wdt-shop-products.elementor-element-edit-mode').find('.wdt-products-container').append('<script id="wdt-edit-mode-shop-script">'+customJS+'</script>');

			// On window resize
			jQuery(window).on('resize', function() {
				// Product Listing Isotope
				$scope.find('.products-apply-isotope').each(function() {
					if(!jQuery(this).hasClass('swiper-wrapper')) {
						jQuery(this).isotope({itemSelector : '.wdt-col', transformsEnabled:false });
					}
				});
			});

			if($scope.find('.products-apply-isotope').length) {
				window.dispatchEvent(new Event('resize'));
			}

		}

	};

    $(window).on('elementor/frontend/init', function(){
		elementorFrontend.hooks.addAction('frontend/element_ready/wdt-shop-products.default', wdtShopProducts);
		elementorFrontend.hooks.addAction('frontend/element_ready/tabs.default', wdtShopProducts);
		elementorFrontend.hooks.addAction('frontend/element_ready/jet-tabs.default', wdtShopProducts);
		elementorFrontend.hooks.addAction('frontend/element_ready/text-editor.default', wdtShopProducts);
    });

} )( jQuery );