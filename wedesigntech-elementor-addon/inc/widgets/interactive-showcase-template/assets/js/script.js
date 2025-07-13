(function ($) {
  
    const wdtInteractiveShowcaseTemplateWidgetHandler = function($scope, $) {

        const $scopeItemId = $scope.data('id');
        const $scopeItem = $scope.find('.wdt-grid');

        // Isotope for gallery items

        if( $($scopeItem).length ) {
            $scopeItem.isotope({
                itemSelector : '.wdt-grid-item',
                masonry: { 
                    columnWidth: '.wdt-gallery-item' 
                } 
            });

            window.setTimeout(function(){
				$scopeItem.isotope();
			}, 1400);

            $(window).on("resize", function() {
                
                $scopeItem.isotope({
                    itemSelector : '.wdt-grid-item',
                    masonry: { 
                        columnWidth: '.wdt-gallery-item' 
                    } 
                });
                
            });
        }

        // Magnific Popup for gallery images

        // $scope.find('.wdt-gallery-item').each(function() {
        //     $(this).magnificPopup({
        //         type: 'image',
        //         gallery: {
        //             enabled: true
        //         }
        //     });
        // });

  
    };
  
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/wdt-interactive-showcase-template.default', wdtInteractiveShowcaseTemplateWidgetHandler);
    });
  
  })(jQuery);