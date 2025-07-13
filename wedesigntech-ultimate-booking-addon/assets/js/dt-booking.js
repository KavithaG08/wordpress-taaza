// Room Search 

(function ($) {
    "use strict";

    var RoomReservationForm = {
        init: function () {
            var $form = $('.dt-sc-reservation-form');

            if ($form.length) {
                this.initDatePickers($form);
                this.initGuestsDropdown($form);
                this.initRoomPrice($form);
                this.handleFormSubmission($form);
				this.initExtraServices($form);
				this.initRoomSelection($form);
            }
        },

        initDatePickers: function ($form) {
			var $checkIn = $form.find('#roomcheckin');
			var $checkOut = $form.find('#roomcheckout');
		
			var today = new Date();
			var tomorrow = new Date();
			tomorrow.setDate(today.getDate() + 1);
		
			var formatDate = function (date) {
				var year = date.getFullYear();
				var month = ('0' + (date.getMonth() + 1)).slice(-2);
				var day = ('0' + date.getDate()).slice(-2);
				return year + '-' + month + '-' + day;
			};
		
			if (!$checkIn.val()) $checkIn.val(formatDate(today));
			if (!$checkOut.val()) $checkOut.val(formatDate(tomorrow));
		
			$checkIn.datepicker({
				minDate: 0, 
				numberOfMonths: 2,
				dateFormat: 'yy-mm-dd',
				onSelect: function (selectedDate) {
					var minDate = new Date(selectedDate);
					minDate.setDate(minDate.getDate() + 1); 
					$checkOut.datepicker('option', 'minDate', minDate);
				}
			});
		
			$checkOut.datepicker({
				minDate: 1, 
				numberOfMonths: 2,
				dateFormat: 'yy-mm-dd'
			});
		},

		updateGuestsField: function ($form) {
			var $guestsField = $form.find('.dt--guests');
			var $persons = $form.find('.dt-sc-field-persons');
			var totalGuests = 0;
			var summary = [];
		
			$persons.find('select').each(function () {
				var $select = $(this);
				var value = parseInt($select.val(), 10) || 0;
				var singular = $select.data('singular-label');
				var plural = $select.data('plural-label');
		
				if (value > 0) {
					totalGuests += value;
					summary.push(value + ' ' + (value > 1 ? plural : singular));
				}
			});
		
			$guestsField.val(summary.join(', ') || 'Select Guests');
		},
		
		initGuestsDropdown: function ($form) {
			var $guestsField = $form.find('.dt--guests');
			var $persons = $form.find('.dt-sc-field-persons');
		
			RoomReservationForm.updateGuestsField($form);
		
			$guestsField.on('click', function () {
				$persons.toggleClass('dt--opened');
			});
		
			$(document).on('click', function (e) {
				if (!$(e.target).closest($persons).length && !$(e.target).closest($guestsField).length) {
					$persons.removeClass('dt--opened');
				}
			});
		
			$(document).on('keyup', function (e) {
				if (e.key === 'Escape') {
					$persons.removeClass('dt--opened');
				}
			});
		
			$persons.find('select').on('change', function () {
				RoomReservationForm.updateGuestsField($form);
			});
		
			$persons.find('.dt-sc-button').on('click', function () {
				$persons.removeClass('dt--opened');
			});
		},

		initRoomPrice: function ($form) {
			var $adultSelect = $form.find('select[name="adult"]');
			var $childrenSelect = $form.find('select[name="children"]');
			var $extraServices = $form.find('.extra-services li input:checkbox');
			var $checkInDate = $form.find('input[name="check_in"]');
			var $checkOutDate = $form.find('input[name="check_out"]');
			// var roomMinPeople = parseInt($form.find('input[name="room_min_people"]').val() || 0, 10);
		
			function calculateTotalPrice() {
				var adultsNumber = parseInt($adultSelect.val() || 0, 10);
				var childrenNumber = parseInt($childrenSelect.val() || 0, 10);
				var roomPricePerNight = parseFloat($form.find('input[name="room_price_per_night"]').val() || 0);
				var roomAdultPrice = parseFloat($form.find('input[name="room-adult-price"]').val() || 0);
				var roomChildrenPrice = parseFloat($form.find('input[name="room-children-price"]').val() || 0);
				var roomMinPeople = parseInt($form.find('input[name="room_min_people"]').val() || 0, 10);
			
				var checkIn = new Date($checkInDate.val());
				var checkOut = new Date($checkOutDate.val());
				var timeDiff = checkOut - checkIn;
				var days = Math.max(Math.ceil(timeDiff / (1000 * 60 * 60 * 24)), 1);
			
				var totalPrice = roomPricePerNight * days;
			
				if (adultsNumber > roomMinPeople) {
					var extraAdults = adultsNumber - roomMinPeople;
					totalPrice += (extraAdults * roomAdultPrice * days);
				}
			
				if (childrenNumber > 0) {
					if (adultsNumber < roomMinPeople) {
						var shortfall = roomMinPeople - adultsNumber;
						if (childrenNumber > shortfall) {
							totalPrice += roomChildrenPrice * (childrenNumber - shortfall) * days;
						}
					} else {
						totalPrice += roomChildrenPrice * childrenNumber * days;
					}
				}
			
				$extraServices.each(function () {
					var $service = $(this);
					if ($service.is(':checked')) {
						var price = parseFloat($service.data('price') || 0);
						var pack = $service.data('pack');
			
						if (pack === 'price-per-person') {
							totalPrice += price * (adultsNumber + childrenNumber) * days;
						} else if (pack === 'add-price') {
							totalPrice += price * days; 
						}
					}
				});
			
				var $priceField = $form.find('.dt-sc-m-price-value');
				$priceField.text(totalPrice.toFixed(2));
				$priceField.data('room-price', totalPrice.toFixed(2));
			
				$form.find('input[name="dt_room_price"]').val(totalPrice.toFixed(2));
			}
			
			$adultSelect.on('change', calculateTotalPrice);
			$childrenSelect.on('change', calculateTotalPrice);
			$extraServices.on('change', calculateTotalPrice);
			$checkInDate.on('change', calculateTotalPrice);
			$checkOutDate.on('change', calculateTotalPrice);
		
			calculateTotalPrice();
		},
			
		initExtraServices: function ($form) {
			var checkboxes = $form.find('.extra-services input[type="checkbox"]');
	
			checkboxes.each(function () {
				var checkbox = $(this);
	
				if (checkbox.prop('checked')) {
					checkbox.closest('li').addClass('option--checked');
				}
	
				checkbox.on('change', function () {
					var listItem = checkbox.closest('li');
					if (checkbox.prop('checked')) {
						listItem.addClass('option--checked');
					} else {
						listItem.removeClass('option--checked');
					}
				});
			});
		},

		initRoomSelection: function ($form) {
            $form.find('#rooms').on('change', function () {
                var selectedRooms = $(this).val(); 
                $form.find('#quantity').val(selectedRooms);
            });
        },

		handleFormSubmission: function () {
			$(document).on('click', '.dt-sc--booking input[name="roombook"]', function (e) {
		
				e.preventDefault();
		
				var $this = $(this);
				var $form = $this.closest('form'); 
		
				var ajaxData = {
					action: 'dt_validate_booking',
					add_to_cart: $form.find('#add-to-cart').val(),
					room_id: $form.find('#add-to-cart').val(),
					check_in: $form.find('#roomcheckin').val(),
					check_out: $form.find('#roomcheckout').val(),
					adults: $form.find('#adult').val(),
					children: $form.find('#children').val(),
					extra_services: $form.find('input[name="extra_services[]"]:checked').map(function () {
						return $(this).val();
					}).get(),
					quantity: $form.find('#quantity').val() || 1,
					dt_room_price: $form.find('#dt_room_price').val() || 1,
					security: dtBooking.validation_nonce, 
				};
		
				$.ajax({
					url: dtBooking.ajax_url, 
					method: 'POST',
					data: ajaxData,
					beforeSend: function(){
						$('.dt-sc--booking').find('.dt-sc--booking-wrapper').addClass('loading');
					},
					success: function (response) {
						if (response.success) {
							$('.dt-sc-room-response').html('<span class="dt-booking-success">' + response.data.message + '</span>');
							setTimeout(function () {
								window.location.href = response.data.cart_url; 
							}, 2000); 
						} else {
							$('.dt-sc-room-response').html('<span class="dt-booking-error">' + response.data.message + '</span>');
							
						}
					},
					error: function () {
						alert('An error occurred while processing your request.');
					},
					complete: function(){
						$('.dt-sc--booking').find('.dt-sc--booking-wrapper').removeClass('loading');
					}
				});
			});
		}
			
    };

    $(document).ready(function () {
        RoomReservationForm.init();
    });

})(jQuery);

