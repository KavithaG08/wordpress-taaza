 (function ($) {

    const wdtRedeemCouponWidgetHandler = function($scope, $) {
        
      const $coupon_option = $scope.find('.wdt-redeem-coupon-container');
      const $copy_coupon= $coupon_option.find('#wdt-copy-coupon-code');
      const $copy_coupon_option = $coupon_option.find('.wdt-copy-code');

      $copy_coupon_option.on('click', function() {
        navigator.clipboard.writeText($copy_coupon.text());
        $copy_coupon_option.text("Copied");
        $copy_coupon_option.addClass("wdt-copy");
        setTimeout(function() {
          $copy_coupon_option.text("Copy Code");
          $copy_coupon_option.removeClass("wdt-copy");
        }, 2000);
      });
      
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/wdt-redeem-coupon.default', wdtRedeemCouponWidgetHandler);
    });

  })(jQuery);