<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaProAuth' ) ) {

	class TaazaProAuth {
		
		private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
			add_filter ( 'theme_page_templates', array( $this, 'taaza_auth_template_attribute' ) );
			add_filter ( 'template_include', array( $this, 'taaza_registration_template' ) );

			$this->load_modules();
			$this->frontend();

			add_action('wp_ajax_taaza_pro_register_user_front_end', array( $this, 'taaza_pro_register_user_front_end', 0 ) );
			add_action('wp_ajax_nopriv_taaza_pro_register_user_front_end', array( $this, 'taaza_pro_register_user_front_end' ) );

        }

		/**
		 * Add Custom Templates to page template array
		*/
		function taaza_auth_template_attribute($templates) {
			$templates = array_merge($templates, array(
				'tpl-registration.php' => esc_html__('Registration Page Template', 'taaza-pro') ,
			));
			return $templates;
		}

		/**
		 * Include Custom Templates page from plugin
		*/
		function taaza_registration_template($template){

			global $post;
			$id = get_the_ID();
			$file = get_post_meta($id, '_wp_page_template', true);
			if ('tpl-registration.php' == $file){
				$template = TAAZA_PRO_DIR_PATH . 'modules/auth/templates/tpl-registration.php';
			}
			return $template;

		}

		function load_modules() {
			include_once TAAZA_PRO_DIR_PATH.'modules/auth/customizer/index.php';
		}

		function frontend() {
			add_action( 'taaza_after_main_css', array( $this, 'enqueue_css_assets' ), 30 );
			add_action( 'taaza_before_enqueue_js', array( $this, 'enqueue_js_assets' ) );
		}

		function enqueue_css_assets() {
			wp_enqueue_style( 'taaza-pro-auth', TAAZA_PRO_DIR_URL . 'modules/auth/assets/css/style.css', false, TAAZA_PRO_VERSION, 'all');
		}

		function enqueue_js_assets() {
			wp_enqueue_script( 'taaza-pro-auth', TAAZA_PRO_DIR_URL . 'modules/auth/assets/js/script.js', array(), TAAZA_PRO_VERSION, true );
			wp_localize_script('taaza-pro-auth', 'taaza_pro_ajax_object', array(
				'ajax_url' => admin_url('admin-ajax.php')
			));
		}

		/**
		 * User Registration Save Data
		 */

		function taaza_pro_register_user_front_end() {

			$first_name = isset( $_POST['first_name'] ) ? taaza_sanitization($_POST['first_name']) : '';
			$last_name  = isset( $_POST['last_name'] )  ? taaza_sanitization($_POST['last_name'])  : '';
			$password   = isset( $_POST['password'] )   ? taaza_sanitization($_POST['password'] )  : '';
			$user_name  = isset( $_POST['user_name'] )  ? taaza_sanitization($_POST['user_name'])  : '';
			$user_email = isset( $_POST['user_email'] ) ? taaza_sanitization($_POST['user_email']) : '';

			$user = array(
				'user_login'  =>  $user_name,
				'user_email'  =>  $user_email,
				'user_pass'   =>  $password,
				'first_name'  =>  $first_name,
				'last_name'   =>  $last_name
			);

			$result = wp_insert_user( $user );
			if (!is_wp_error($result)) {
				echo 'Your registration is completed successfully! To get your credential please check you mail!.';
				$taaza_to = $user_email;
				$taaza_subject = 'Welcome to Our Website';

			   // Email content
			   $taaza_body =  "Hello $user_name, <br><br>";
			   $taaza_body .= "Welcome to our website! Here are your account details: <br>";
			   $taaza_body .= "Username: $user_name <br>";
			   $taaza_body .= "Password: $password <br>";
			   $taaza_body .= "Please log in using this information and consider changing your password for security reasons. <br><br>";
			   $taaza_body .= "Thank you for joining us! <br>";
			   $taaza_body .= "Best regards, <br>";
			   $taaza_body .= get_site_url();
			   $taaza_headers = array('Content-Type: text/html; charset=UTF-8');

				wp_mail($taaza_to, $taaza_subject, $taaza_body, $taaza_headers);
			} else {
				echo 'Error creating user: ' . $result->get_error_message();
			}
			exit();
		}	
		
	}

	add_action( 'wp_ajax_taaza_pro_show_login_form_popup', 'taaza_pro_show_login_form_popup' );
	add_action( 'wp_ajax_nopriv_taaza_pro_show_login_form_popup', 'taaza_pro_show_login_form_popup' );
	function taaza_pro_show_login_form_popup() {
		echo taaza_pro_login_form();

		die();
	}

	// Login form
	if(!function_exists('taaza_pro_login_form')) {
		function taaza_pro_login_form() {

			$out = '<div class="taaza-pro-login-form-container">';

				$out .= '<div class="close-login-form"><i class="wdticon-close"></i></div>';
				$out .= '<div class="taaza-pro-login-form">';

					$out .= '<div class="taaza-pro-login-form-wrapper">';
						$out .= '<div class="taaza-pro-title taaza-pro-login-title"><h2><span class="taaza-pro-login-title"><strong>'.esc_html__('Create Your Account', 'taaza-pro').'</strong></span></h2>
							<span class="taaza-pro-login-description">'.esc_html__('Please enter your login credentials to access your account.', 'taaza-pro').'</span></div>';
						$social_logins = (taaza_customizer_settings( 'enable_social_logins' ) !== null) && !empty(taaza_customizer_settings( 'enable_social_logins' )) ? taaza_customizer_settings( 'enable_social_logins' ) : 0;
						$enable_facebook_login = (taaza_customizer_settings( 'enable_facebook_login' ) !== null) && !empty(taaza_customizer_settings( 'enable_facebook_login' )) ? taaza_customizer_settings( 'enable_facebook_login' ) : 0;
						$facebook_app_id = (taaza_customizer_settings( 'facebook_app_id' ) !== null) && !empty(taaza_customizer_settings( 'facebook_app_id' )) ? taaza_customizer_settings( 'facebook_app_id' ) : '';
						$facebook_app_secret = (taaza_customizer_settings( 'facebook_app_secret' ) !== null) && !empty(taaza_customizer_settings( 'facebook_app_secret' )) ? taaza_customizer_settings( 'facebook_app_secret' ) : '';
						$enable_google_login = (taaza_customizer_settings( 'enable_google_login' ) !== null) && !empty( taaza_customizer_settings( 'enable_google_login' ) ) ? taaza_customizer_settings( 'enable_google_login' ) : 0;

						
						$out .= '<div class="taaza-pro-login-form-holder">';
						$out .= '<div id="login-message"></div>';
						$is_admin = is_admin() || is_super_admin();
						$redirect_url = $is_admin ? admin_url() : home_url();

							$out .= '<form id="loginform" method="post" onsubmit="return customLogin(event)">';

							// $out .= '<label for="user_login">' . esc_html__('Username', 'taaza-pro') . '</label>';
							$out .= '<input type="text" name="log" id="user_login">';

							// $out .= '<label for="user_pass">' . esc_html__('Password', 'taaza-pro') . '</label>';
							$out .= '<input type="password" name="pwd" id="user_pass">';

							$out .= '<label for="rememberme">';
							$out .= '<input name="rememberme" type="checkbox" id="rememberme" value="forever">';
							$out .= esc_html__(' Remember Me', 'taaza-pro');
							$out .= '</label>';

							$out .= '<button type="submit" id="wp-submit">' . esc_html__('Sign In', 'taaza-pro') . '</button>';

							$out .= '</form>';

						$out .= '<p class="tpl-forget-pwd"><a href="' . esc_url(wp_lostpassword_url(get_permalink())) . '">' . esc_html__('Forgot password ?', 'taaza-pro') . '</a></p>';
						
						$out .= '</div>';

						$out .= '<div class="taaza-pro-social-logins-container">';
							$out .= '<div class="taaza-pro-social-logins-divider">'.esc_html__('Or', 'taaza-pro').'</div>';
						$out .= '</div>';

						if( $social_logins ) {
							if( $enable_facebook_login || $enable_google_login ) {
								$out .= '<div class="taaza-pro-social-logins-container">';
								$out .= '<a href="'.taaza_pro_google_login_url().'" class="taaza-pro-social-google-connect"><i><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">

								<g>
									<path class="google-color-1" d="M95,51.1c0-3.1-0.3-6.3-0.8-9.3H50.9v17.7h24.8c-1,5.7-4.3,10.7-9.2,13.9l14.8,11.5C90,76.8,95,65,95,51.1   L95,51.1z"></path>
									<path class="google-color-2" d="M50.9,95.9c12.4,0,22.8-4.1,30.4-11.1L66.5,73.4c-4.1,2.8-9.4,4.4-15.6,4.4c-12,0-22.1-8.1-25.8-18.9L9.9,70.6   C17.7,86.1,33.5,95.9,50.9,95.9z"></path>
									<path class="google-color-3" d="M25.1,58.8c-1.9-5.7-1.9-11.9,0-17.6L9.9,29.4c-6.5,13-6.5,28.3,0,41.2L25.1,58.8z"></path>
									<path class="google-color-4" d="M50.9,22.3c6.5-0.1,12.9,2.4,17.6,6.9L81.6,16C73.3,8.2,62.3,4,50.9,4.1c-17.4,0-33.2,9.8-41,25.3l15.2,11.8   C28.8,30.3,38.9,22.3,50.9,22.3z"></path>
								</g>
								</svg></i>'.esc_html__('Login with Google', 'taaza-pro').'</a>';
								$out .= '</div>';
							}
						}
						
						if( $enable_facebook_login) {
							
								if($enable_facebook_login) {
									if(!session_id()) {
										session_start();
									}

									include_once TAAZA_PRO_DIR_PATH.'modules/auth/apis/facebook/Facebook/autoload.php';

									$appId     = $facebook_app_id; //Facebook App ID
									$appSecret = $facebook_app_secret; // Facebook App Secret
	
									$fb = new Facebook\Facebook([
										'app_id' => $appId,
										'app_secret' => $appSecret,
										'default_graph_version' => 'v2.10',
									]);
	
									$helper = $fb->getRedirectLoginHelper();
									$permissions = ['email'];
									$loginUrl = $helper->getLoginUrl( site_url('wp-login.php') . '?dtLMSFacebookLogin=1', $permissions);
	
									$out .= '<a href="'.htmlspecialchars($loginUrl).'" class="taaza-pro-social-facebook-connect"><i class="wdticon-facebook"></i>'.esc_html__('Login with Facebook', 'taaza-pro').'</a>';
								}
	
						}
					$out .= '</div>';
				$out .= '</div>';

			$out .= '</div>';

			$out .= '<div class="taaza-pro-login-form-overlay"></div>';

			return $out;

		}
	}

	function taaza_pro_validate_login() {
		$data = $_POST['data'];
		$username = $data['user_name'];
		$password = $data['user_password'];

		$user = get_user_by('login', $username);
		if (!$user) {
			wp_send_json_error(array('message' => __('Username does not exist.')));
		}
		if (!wp_check_password($password, $user->user_pass, $user->ID)) {
			wp_send_json_error(array('message' => __('Incorrect password.')));
		}
	
		wp_set_current_user($user->ID, $user->user_login);
		wp_set_auth_cookie($user->ID);
		do_action('wp_login', $user->user_login, $user);
		if (isset($_POST['is_wp_dashboard']) && $_POST['is_wp_dashboard'] === 'true') {
			$redirect_url = admin_url();
		} else {
			$redirect_url = home_url('/my-account');
		}
		
		wp_send_json_success(array('message' => __('Login successful, redirecting...'), 'redirect_url' => $redirect_url));
	}

	add_action('wp_ajax_nopriv_taaza_pro_validate_login', 'taaza_pro_validate_login');
	add_action('wp_ajax_taaza_pro_validate_login', 'taaza_pro_validate_login');

	/* ---------------------------------------------------------------------------
	* Google login utils
	* --------------------------------------------------------------------------- */

	if( !function_exists( 'taaza_pro_google_login_url' ) ) {
		function taaza_pro_google_login_url() {
			return site_url('wp-login.php') . '?dtLMSGoogleLogin=1';
		}
	}

	function taaza_pro_google_login() {

		$dtLMSGoogleLogin = isset($_REQUEST['dtLMSGoogleLogin']) ? taaza_sanitization($_REQUEST['dtLMSGoogleLogin']) : '';
		if ($dtLMSGoogleLogin == '1') {
			taaza_pro_google_login_action();
		}
	
	}
	add_action('login_init', 'taaza_pro_google_login');

	if( !function_exists('taaza_pro_google_login_action') ) {
		function taaza_pro_google_login_action() {

			require_once TAAZA_PRO_DIR_URL.'modules/auth/apis/google/Google_Client.php';
			require_once TAAZA_PRO_DIR_URL.'modules/auth/apis/google/contrib/Google_Oauth2Service.php';
			
			$google_client_id = (taaza_customizer_settings( 'google_client_id' ) !== null) && !empty(taaza_customizer_settings( 'google_client_id' )) ? taaza_customizer_settings( 'google_client_id' ) : '';
			$google_client_secret = (taaza_customizer_settings( 'google_client_secret' ) !== null) && !empty(taaza_customizer_settings( 'google_client_secret' )) ? taaza_customizer_settings( 'google_client_secret' ) : '';

			$clientId     = $google_client_id; //Google CLIENT ID
			$clientSecret = $google_client_secret; //Google CLIENT SECRET
			$redirectUrl  = taaza_pro_google_login_url();  //return url (url to script)
		
			$gClient = new Google_Client();
			$gClient->setApplicationName(esc_html__('Login To Website', 'taaza-pro'));
			$gClient->setClientId($clientId);
			$gClient->setClientSecret($clientSecret);
			$gClient->setRedirectUri($redirectUrl);
		
			$google_oauthV2 = new Google_Oauth2Service($gClient);
		
			if(isset($google_oauthV2)){
		
				$gClient->authenticate();
				$_SESSION['token'] = $gClient->getAccessToken();
		
				if (isset($_SESSION['token'])) {
					$gClient->setAccessToken($_SESSION['token']);
				}
		
				$user_profile = $google_oauthV2->userinfo->get();
		
				$args = array(
					'meta_key'     => 'google_id',
					'meta_value'   => $user_profile['id'],
					'meta_compare' => '=',
				 );
				$users = get_users( $args );
		
				if(is_array($users) && !empty($users)) {
					$ID = $users[0]->data->ID;
				} else {
					$ID = NULL;
				}
		
				if ($ID == NULL) {
		
					if (!isset($user_profile['email'])) {
						$user_profile['email'] = $user_profile['id'] . '@gmail.com';
					}
		
					$random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
		
					$username = strtolower($user_profile['name']);
					$username = trim(str_replace(' ', '', $username));
		
					$sanitized_user_login = sanitize_user('google-'.$username);
		
					if (!validate_username($sanitized_user_login)) {
						$sanitized_user_login = sanitize_user('google-' . $user_profile['id']);
					}
		
					$defaul_user_name = $sanitized_user_login;
					$i = 1;
					while (username_exists($sanitized_user_login)) {
					  $sanitized_user_login = $defaul_user_name . $i;
					  $i++;
					}
		
					$ID = wp_create_user($sanitized_user_login, $random_password, $user_profile['email']);
		
					if (!is_wp_error($ID)) {
		
						wp_new_user_notification($ID, $random_password);
						$user_info = get_userdata($ID);
						wp_update_user(array(
							'ID' => $ID,
							'display_name' => $user_profile['name'],
							'first_name' => $user_profile['name'],
						));
		
						update_user_meta($ID, 'google_id', $user_profile['id']);
		
					}
		
				}
		
				// Login
				if ($ID) {
		
				  $secure_cookie = is_ssl();
				  $secure_cookie = apply_filters('secure_signon_cookie', $secure_cookie, array());
				  global $auth_secure_cookie;
		
				  $auth_secure_cookie = $secure_cookie;
				  wp_set_auth_cookie($ID, false, $secure_cookie);
				  $user_info = get_userdata($ID);
				  update_user_meta($ID, 'google_profile_picture', $user_profile['picture']);
				  do_action('wp_login', $user_info->user_login, $user_info, 10, 2);
				  update_user_meta($ID, 'google_user_access_token', $_SESSION['token']);
		
				//   wp_redirect(taaza_pro_get_login_redirect_url($user_info));
				wp_redirect(home_url());
		
				}
		
			} else {
		
				$authUrl = $gClient->createAuthUrl();
				header('Location: ' . $authUrl);
				exit;
		
			}
		
		}
	}

	/* if( !function_exists( 'taaza_pro_get_login_redirect_url' ) ) {
		function taaza_pro_get_login_redirect_url($user_info) {

			$dtlms_redirect_url = '';
			if(isset($user_info->data->ID)) {
				$current_user = $user_info;

			}

		}
	} */

}

TaazaProAuth::instance();