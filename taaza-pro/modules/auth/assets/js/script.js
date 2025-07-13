(function ($) {

    var taazaAuthValidation = {

        init : function() {
            jQuery( 'body' ).delegate( '.taaza-pro-login-link', 'click', function(e){

                jQuery.ajax({
                    type: "POST",
                    url: taaza_urls.ajaxurl,
                    data:
                    {
                        action: 'taaza_pro_show_login_form_popup',
                    },
                    success: function (response) {
    
                        jQuery('body').addClass('wdt-overflow-hidden');
                        jQuery('body').find('.taaza-pro-login-form-container').remove();
                        jQuery('body').find('.taaza-pro-login-form-overlay').remove();
                        jQuery('body').append(response);
    
                        jQuery('#user_login').focus();

                        taazaAuthValidation.addPlaceholder();
    
                    }
                });
    
                e.preventDefault();
    
            });
    
            jQuery( 'body' ).delegate( '.close-login-form', 'click', function(e){
    
                jQuery('body').removeClass('wdt-overflow-hidden');
                jQuery('body').find('.taaza-pro-login-form-container').fadeOut();
                jQuery('body').find('.taaza-pro-login-form-overlay').fadeOut();
    
                e.preventDefault;
    
            });

            jQuery( 'body' ).delegate( '#taaza-custom-auth-register-button', 'click', function(e) {
                jQuery('#loginform').on('submit', function(e) {
                    var first_name = jQuery('#first_name').val();
                    var last_name  = jQuery('#last_name').val();
                    var user_name  = jQuery('#user_name').val();
                    var password   = jQuery('#password').val();
                    var user_email = jQuery('#user_email').val();
                        jQuery.ajax({
                            type: "POST",
                            url: taaza_urls.ajaxurl,
                            data:
                            {
                                action: 'taaza_pro_register_user_front_end',
                                first_name: first_name,
                                last_name: last_name,
                                user_name: user_name,
                                password: password,
                                user_email: user_email
                            },
                            success: function(results){

                                if (results.includes("Error creating user")) {
                                    jQuery('.taaza-custom-auth-register-alert').text(results).show();
                                    jQuery('.taaza-custom-auth-register-alert').addClass('invalid');
                                } else {
                                    jQuery('.taaza-custom-auth-register-alert').text(results).show();
                                    jQuery('.taaza-custom-auth-register-alert').addClass('success');
                                }
                            },
                            error: function(results) {
                                jQuery('.taaza-custom-auth-register-alert').addClass('invalid');
                            }

                        });

                        e.preventDefault();
                });
            });

        },

        addPlaceholder : function() {

            // Login Form Scripts
            $('#loginform input[id="user_login"]').attr('placeholder', 'Username');
            $('#loginform input[id="user_pass"]').attr('placeholder', 'Password');
            
            $('#loginform label[for="user_login"]').contents().filter(function() {
                return this.nodeType === 3;
            }).remove();
            $('#loginform label[for="user_pass"]').contents().filter(function() {
                return this.nodeType === 3;
            }).remove();
            
            $('input[type="checkbox"]').click(function() {
                $(this+':checked').parent('label').css("background-position","0px -20px");
                $(this).not(':checked').parent('label').css("background-position","0px 0px");
            });
        },

        validateLogin: function(formData, isWpDashboard = false) {

            formData.is_wp_dashboard = isWpDashboard;
            $.ajax({
                type: "POST",
                url: taaza_pro_ajax_object.ajax_url,
                data: {
                    action: 'taaza_pro_validate_login',
                    data: formData,
                    is_wp_dashboard: isWpDashboard.toString(),
                },
                // dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        if (response.data.redirect_url) {
                            window.location.href = response.data.redirect_url;
                        } else {
                            $('#login-message').html('<div class="error">Redirect URL not found.</div>');
                        }
                    } else {
                        $('#login-message').html('<div class="error">' + response.data.message + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    $('#login-message').html('<div class="error">An error occurred: ' + error + '</div>');
                },
            });
        }

    }

    "use strict";
    $(document).ready(function () {   
        taazaAuthValidation.init();

        // Custom register page
        if( ($('#signup-content').length) || ($('#signup-content').length) > 1 ) {
            $('body').addClass('wdt-custom-auth-form');
            $('.wrapper').addClass('wdt-custom-auth-form');
        }
    });

    window.customLogin = function (event) {
        event.preventDefault();
        var user_name = $('#user_login').val();
        var user_password = $('#user_pass').val();
        var rememberme = $('#rememberme').is(':checked') ? 'forever' : '';

        if (user_name === '') {
            $('#login-message').text('Please enter your username').show();
            $('#login-message').addClass('invalid');
        } else if (user_password === '') {
            $('#login-message').text('Please enter your password').show();
            $('#login-message').addClass('invalid');
        } else {
            var formData = {
                user_name: user_name,
                user_password: user_password,
                rememberme: rememberme
            };
            taazaAuthValidation.validateLogin(formData);
        }
    };

})(jQuery);