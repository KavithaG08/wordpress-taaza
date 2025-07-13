 (function ($) {

    const wdtParallaxCarouselWidgetHandler = function($scope, $) {

		const $parallax_carousel_holder = $scope.find('.wdt-parallax-carousel-holder');
		// const $parallax_carousel_option = $parallax_carousel_holder.data('settings');
		const $swiperItem = $parallax_carousel_holder.find('.swiper');
		const $moduleId = $swiperItem.data('wrapper-class');
		const $module_id = $swiperItem.data('id');

		var swiper = {
			speed: 300,
			// parallax: true,
			loop: true,
			// autoplay: {
				// delay: 50000, // Adjust autoplay delay as needed
			// 	disableOnInteraction: false
			// },
			grabCursor: true,
			effect: "fade",
			// effect: "creative",
			// creativeEffect: {
			// 	prev: {
			// 		shadow: true,
			// 		translate: ["-120%", 0, -500],
			// 	},
			// 	next: {
			// 		shadow: true,
			// 		translate: ["120%", 0, -500],
			// 	},
			// },
			pagination: {
				el: '.swiper-pagination',
				type: 'progressbar',
			},
			navigation: {
				prevEl: '.wdt-arrow-pagination-prev-'+$module_id,
		    	nextEl: '.wdt-arrow-pagination-next-'+$module_id
			},
		};

      	const swiperGallery = new Swiper('.'+$moduleId, swiper);

    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/wdt-parallax-carousel.default', wdtParallaxCarouselWidgetHandler);
    });

  })(jQuery);