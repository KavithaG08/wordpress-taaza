<?php
if ( ! class_exists( 'TaazaPlusBackendMenuWalker' ) ) {

	class TaazaPlusBackendMenuWalker {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
        	add_filter( 'wp_edit_nav_menu_walker', array( $this, 'taaza_edit_nav_menu' ) );
        	add_action( 'wp_update_nav_menu_item', array( $this, 'taaza_update_menu_item'), 10, 2 );
   			add_filter( 'wp_setup_nav_menu_item', array( $this, 'taaza_add_custom_nav_fields' ) );
		}

        function taaza_edit_nav_menu( $walker ) {
            return 'Taaza_Walker_Nav_Menu_Edit';
		}

        function taaza_update_menu_item( $menu_id, $menu_item_db_id ) {
            if ( is_array( $_REQUEST['menu-item-wdt-menu-icon']) ) {
                $image_value = taaza_sanitization($_REQUEST['menu-item-wdt-menu-icon'][$menu_item_db_id]);
                update_post_meta( $menu_item_db_id, '_wdt-menu-icon', $image_value );
            }

            if ( is_array( $_REQUEST['menu-item-wdt-menu-image']) ) {
                $image_value = taaza_sanitization($_REQUEST['menu-item-wdt-menu-image'][$menu_item_db_id]);
                update_post_meta( $menu_item_db_id, '_wdt-menu-image', $image_value );
            }

            if ( is_array( $_REQUEST['menu-item-wdt-menu-image-position']) ) {
                $image_value = taaza_sanitization($_REQUEST['menu-item-wdt-menu-image-position'][$menu_item_db_id]);
                update_post_meta( $menu_item_db_id, '_wdt-menu-image-position', $image_value );
            }

            if ( is_array( $_REQUEST['wdt-menu-item-child-animation']) ) {
                $animation = taaza_sanitization($_REQUEST['wdt-menu-item-child-animation'][$menu_item_db_id]);
                update_post_meta( $menu_item_db_id, '_wdt-child-menu-animation', $animation );
            }

            if ( is_array( $_REQUEST['menu-item-wdt-menu-custom-label']) ) {
                $animation = taaza_sanitization($_REQUEST['menu-item-wdt-menu-custom-label'][$menu_item_db_id]);
                update_post_meta( $menu_item_db_id, '_wdt-menu-custom-label', $animation );
            }

            if ( is_array( $_REQUEST['menu-item-wdt-menu-custom-label-type']) ) {
                $animation = taaza_sanitization($_REQUEST['menu-item-wdt-menu-custom-label-type'][$menu_item_db_id]);
                update_post_meta( $menu_item_db_id, '_wdt-menu-custom-label-type', $animation );
            }
        }

        function taaza_add_custom_nav_fields( $menu_item ) {
			$menu_item->icon                 = get_post_meta( $menu_item->ID, '_wdt-menu-icon', true );
			$menu_item->image                = get_post_meta( $menu_item->ID, '_wdt-menu-image', true );
			$menu_item->icon_position        = get_post_meta( $menu_item->ID, '_wdt-menu-image-position', true );
			$menu_item->child_menu_animation = get_post_meta( $menu_item->ID, '_wdt-child-menu-animation', true );

			$menu_item->custom_label         = get_post_meta( $menu_item->ID, '_wdt-menu-custom-label', true );
			$menu_item->custom_label_type    = get_post_meta( $menu_item->ID, '_wdt-menu-custom-label-type', true );

            return $menu_item;
        }
	}
}

TaazaPlusBackendMenuWalker::instance();

if( ! class_exists( 'Taaza_Walker_Nav_Menu_Edit' ) ) {

	class Taaza_Walker_Nav_Menu_Edit extends Walker_Nav_Menu {

		public function start_lvl( &$output, $depth = 0, $args = array() ) {}

		public function end_lvl( &$output, $depth = 0, $args = array() ) {}

		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			global $_wp_nav_menu_max_depth;
			$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

			ob_start();
			$item_id      = esc_attr( $item->ID );
			$removed_args = array(
				'action',
				'customlink-tab',
				'edit-menu-item',
				'menu-item',
				'page-tab',
				'_wpnonce',
			);

			$original_title = false;

			if ( 'taxonomy' == $item->type ) {
				$original_object = get_term( (int) $item->object_id, $item->object );
				if ( $original_object && ! is_wp_error( $original_title ) ) {
					$original_title = $original_object->name;
				}
			} elseif ( 'post_type' == $item->type ) {
				$original_object = get_post( $item->object_id );
				if ( $original_object ) {
					$original_title = get_the_title( $original_object->ID );
				}
			} elseif ( 'post_type_archive' == $item->type ) {
				$original_object = get_post_type_object( $item->object );
				if ( $original_object ) {
					$original_title = $original_object->labels->archives;
				}
			}

			$classes = array(
				'menu-item menu-item-depth-' . esc_attr($depth),
				'menu-item-' . esc_attr( $item->object ),
				'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == taaza_sanitization($_GET['edit-menu-item']) ) ? 'active' : 'inactive' ),
			);

			$title = $item->title;

			if ( ! empty( $item->_invalid ) ) {
				$classes[] = 'menu-item-invalid';
				/* translators: %s: Title of an invalid menu item. */
				$title = sprintf( esc_html__( '%s (Invalid)','taaza-plus' ), $item->title );
			} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
				$classes[] = 'pending';
				/* translators: %s: Title of a menu item in draft status. */
				$title = sprintf( esc_html__( '%s (Pending)','taaza-plus' ), $item->title );
			}

			$title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

			$submenu_text = '';
			if ( 0 == $depth ) {
				$submenu_text = 'style="display: none;"';
			}

			?>
			<li id="menu-item-<?php echo esc_attr( $item_id ); ?>" class="<?php echo implode( ' ', $classes ); ?>">
				<div class="menu-item-bar">
					<div class="menu-item-handle">
						<span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo taaza_html_output($submenu_text); ?>><?php _e( 'sub item','taaza-plus' ); ?></span></span>
						<span class="item-controls">
							<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
							<span class="item-order hide-if-js">
								<?php
								printf(
									'<a href="%s" class="item-move-up" aria-label="%s">&#8593;</a>',
									wp_nonce_url(
										add_query_arg(
											array(
												'action'    => 'move-up-menu-item',
												'menu-item' => $item_id,
											),
											remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) )
										),
										'move-menu_item'
									),
									esc_attr__( 'Move up', 'taaza-plus' )
								);
								?>
								|
								<?php
								printf(
									'<a href="%s" class="item-move-down" aria-label="%s">&#8595;</a>',
									wp_nonce_url(
										add_query_arg(
											array(
												'action'    => 'move-down-menu-item',
												'menu-item' => $item_id,
											),
											remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) )
										),
										'move-menu_item'
									),
									esc_attr__( 'Move down', 'taaza-plus' )
								);
								?>
							</span>
							<?php
							if ( isset( $_GET['edit-menu-item'] ) && $item_id == taaza_sanitization($_GET['edit-menu-item']) ) {
								$edit_url = admin_url( 'nav-menus.php' );
							} else {
								$edit_url = add_query_arg(
									array(
										'edit-menu-item' => $item_id,
									),
									remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . esc_attr($item_id) ) )
								);
							}

							printf(
								'<a class="item-edit" id="edit-%s" href="%s" aria-label="%s"><span class="screen-reader-text">%s</span></a>',
								$item_id,
								$edit_url,
								esc_attr__( 'Edit menu item', 'taaza-plus' ),
								__( 'Edit', 'taaza-plus' )
							);
							?>
						</span>
					</div>
				</div>

				<div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo esc_attr( $item_id ); ?>">
					<?php if ( 'custom' == $item->type ) : ?>
						<p class="field-url description description-wide">
							<label for="edit-menu-item-url-<?php echo esc_attr( $item_id ); ?>">
								<?php esc_html_e( 'URL','taaza-plus' ); ?><br />
								<input type="text" id="edit-menu-item-url-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
							</label>
						</p>
					<?php endif; ?>
					<p class="description description-wide">
						<label for="edit-menu-item-title-<?php echo esc_attr( $item_id ); ?>">
							<?php esc_html_e( 'Navigation Label', 'taaza-plus' ); ?><br />
							<input type="text" id="edit-menu-item-title-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
						</label>
					</p>
					<p class="field-title-attribute field-attr-title description description-wide">
						<label for="edit-menu-item-attr-title-<?php echo esc_attr( $item_id ); ?>">
							<?php esc_html_e( 'Title Attribute', 'taaza-plus' ); ?><br />
							<input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
						</label>
					</p>
					<p class="field-link-target description">
						<label for="edit-menu-item-target-<?php echo esc_attr( $item_id ); ?>">
							<input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr( $item_id ); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr( $item_id ); ?>]"<?php checked( $item->target, '_blank' ); ?> />
							<?php esc_html_e( 'Open link in a new tab','taaza-plus' ); ?>
						</label>
					</p>
					<p class="field-css-classes description description-thin">
						<label for="edit-menu-item-classes-<?php echo esc_attr( $item_id ); ?>">
							<?php esc_html_e( 'CSS Classes (optional)','taaza-plus' ); ?><br />
							<input type="text" id="edit-menu-item-classes-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( implode( ' ', $item->classes ) ); ?>" />
						</label>
					</p>
					<p class="field-xfn description description-thin">
						<label for="edit-menu-item-xfn-<?php echo esc_attr( $item_id ); ?>">
							<?php esc_html_e( 'Link Relationship (XFN)','taaza-plus' ); ?><br />
							<input type="text" id="edit-menu-item-xfn-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
						</label>
					</p>
					<p class="field-description description description-wide">
						<label for="edit-menu-item-description-<?php echo esc_attr( $item_id ); ?>">
							<?php esc_html_e( 'Description','taaza-plus' ); ?><br />
							<textarea id="edit-menu-item-description-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr( $item_id ); ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
							<span class="description"><?php esc_html_e( 'The description will be displayed in the menu if the current theme supports it.','taaza-plus' ); ?></span>
						</label>
					</p>
					<!-- Custom Fields -->

						<?php $menu_custom_label = get_post_meta( $item->ID, "_wdt-menu-custom-label",true);?>
						<p class="field-wdt-menu-custom-label description description-wide">
							<label for="edit-menu-item-wdt-menu-custom-label-<?php echo esc_attr( $item_id ); ?>">
								<?php esc_html_e( 'Custom Label','taaza-plus' ); ?><br />
								<input type="text" id="edit-menu-wdt-menu-custom-label-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-wdt-menu-custom-label" name="menu-item-wdt-menu-custom-label[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $menu_custom_label ); ?>" />
							</label>
						</p>

						<?php $menu_custom_label_type = get_post_meta( $item->ID, "_wdt-menu-custom-label-type",true);?>
						<p class="field-wdt-menu-custom-label-type description description-wide">
							<label for="edit-menu-item-wdt-menu-custom-label-type-<?php echo esc_attr( $item_id ); ?>">
								<?php esc_html_e( 'Custom Label Type','taaza-plus' ); ?><br />
								<select id="edit-menu-item-wdt-menu-custom-label-type-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-wdt-menu-custom-label-type" name="menu-item-wdt-menu-custom-label-type[<?php echo esc_attr($item_id);?>]">
			                        <option value="menu-custom-label-red" <?php selected( $menu_custom_label_type, 'menu-custom-label-red' ); ?>><?php esc_html_e('BG Red Text White ','taaza-plus');?></option>
			                        <option value="menu-custom-label-green" <?php selected( $menu_custom_label_type, 'menu-custom-label-green' ); ?>><?php esc_html_e('BG Green Text White','taaza-plus');?></option>
			                        <option value="menu-custom-label-blue" <?php selected( $menu_custom_label_type, 'menu-custom-label-blue' ); ?>><?php esc_html_e('BG Blue Text White','taaza-plus');?></option>
			                        <option value="menu-custom-label-white" <?php selected( $menu_custom_label_type, 'menu-custom-label-white' ); ?>><?php esc_html_e('BG White Text Black','taaza-plus');?></option>
			                        <option value="menu-custom-label-black" <?php selected( $menu_custom_label_type, 'menu-custom-label-black' ); ?>><?php esc_html_e('BG Black Text White','taaza-plus');?></option>
								</select>
							</label>
						</p>

						<?php $menu_icon = get_post_meta( $item->ID, "_wdt-menu-icon",true);?>
						<p class="field-wdt-menu-icon description description-wide">
							<label for="edit-menu-item-wdt-menu-icon-<?php echo esc_attr( $item_id ); ?>">
								<?php esc_html_e( 'Menu Icon','taaza-plus' ); ?><br />
								<input type="text" id="edit-menu-wdt-menu-icon-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-wdt-menu-icon" name="menu-item-wdt-menu-icon[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $menu_icon ); ?>" />
							</label>
						</p>

						<?php $menu_image = get_post_meta( $item->ID, "_wdt-menu-image",true);?>
						<p class="field-wdt-menu-image description description-wide">
							<label for="edit-menu-item-wdt-menu-image-<?php echo esc_attr( $item_id ); ?>">
								<?php esc_html_e( 'Menu Image','taaza-plus' ); ?><br />
								<input type="text" id="edit-menu-wdt-menu-image-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-wdt-menu-image" name="menu-item-wdt-menu-image[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $menu_image ); ?>" />
								<span class="description"><?php esc_html_e('Please use image url',  'taaza-plus'); ?></span>
							</label>
						</p>

						<?php $menu_image_pos = get_post_meta( $item->ID, "_wdt-menu-image-position",true);?>
						<p class="field-wdt-menu-image-position description description-wide">
							<label for="edit-menu-item-wdt-menu-position-<?php echo esc_attr( $item_id ); ?>">
								<?php esc_html_e( 'Menu Icon / Image Position','taaza-plus' ); ?><br />
								<select id="edit-menu-item-wdt-menu-image-position-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-wdt-menu-image-position" name="menu-item-wdt-menu-image-position[<?php echo esc_attr($item_id);?>]">
			                        <option value="left" <?php selected( $menu_image_pos, 'left' ); ?>><?php esc_html_e('Left','taaza-plus');?></option>
			                        <option value="top-left" <?php selected( $menu_image_pos, 'top-left' ); ?>><?php esc_html_e('Top - Left','taaza-plus');?></option>
			                        <option value="right" <?php selected( $menu_image_pos, 'right' ); ?>><?php esc_html_e('Right','taaza-plus');?></option>
			                        <option value="top-right" <?php selected( $menu_image_pos, 'top-right' ); ?>><?php esc_html_e('Top - Right','taaza-plus');?></option>
			                        <option value="top-center" <?php selected( $menu_image_pos, 'top-center' ); ?>><?php esc_html_e('Top - Center','taaza-plus');?></option>
								</select>
								<span class="description"><?php esc_html_e('Please select image position',  'taaza-plus'); ?></span>
							</label>
						</p>

						<?php $sub_menu_anim = get_post_meta( $item->ID, "_wdt-child-menu-animation", true);?>
						<p class="field-wdt-menu-child-animation description description-wide">
							<label for="edit-menu-item-wdt-menu-child-animation-<?php echo esc_attr( $item_id ); ?>">
								<?php esc_html_e( 'Child Menu Animation','taaza-plus' ); ?><br />
								<select id="edit-menu-item-wdt-menu-child-animation-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-wdt-menu-item-child-animation" name="wdt-menu-item-child-animation[<?php echo esc_attr($item_id);?>]"><?php
									$animations = apply_filters( 'taaza_child_menu_animations', array( '' => esc_html__('None','taaza-plus'),
										"animate wdt_bigEntrance"        =>  esc_html__("bigEntrance",'taaza-plus'),
										"animate wdt_bounce"             =>  esc_html__("bounce",'taaza-plus'),
										"animate wdt_bounceIn"           =>  esc_html__("bounceIn",'taaza-plus'),
										"animate wdt_bounceInDown"       =>  esc_html__("bounceInDown",'taaza-plus'),
										"animate wdt_bounceInLeft"       =>  esc_html__("bounceInLeft",'taaza-plus'),
										"animate wdt_bounceInRight"      =>  esc_html__("bounceInRight",'taaza-plus'),
										"animate wdt_bounceInUp"         =>  esc_html__("bounceInUp",'taaza-plus'),
										"animate wdt_bounceOut"          =>  esc_html__("bounceOut",'taaza-plus'),
										"animate wdt_bounceOutDown"      =>  esc_html__("bounceOutDown",'taaza-plus'),
										"animate wdt_bounceOutLeft"      =>  esc_html__("bounceOutLeft",'taaza-plus'),
										"animate wdt_bounceOutRight"     =>  esc_html__("bounceOutRight",'taaza-plus'),
										"animate wdt_bounceOutUp"        =>  esc_html__("bounceOutUp",'taaza-plus'),
										"animate wdt_expandOpen"         =>  esc_html__("expandOpen",'taaza-plus'),
										"animate wdt_expandUp"           =>  esc_html__("expandUp",'taaza-plus'),
										"animate wdt_fadeIn"             =>  esc_html__("fadeIn",'taaza-plus'),
										"animate wdt_fadeInDown"         =>  esc_html__("fadeInDown",'taaza-plus'),
										"animate wdt_fadeInDownBig"      =>  esc_html__("fadeInDownBig",'taaza-plus'),
										"animate wdt_fadeInLeft"         =>  esc_html__("fadeInLeft",'taaza-plus'),
										"animate wdt_fadeInLeftBig"      =>  esc_html__("fadeInLeftBig",'taaza-plus'),
										"animate wdt_fadeInRight"        =>  esc_html__("fadeInRight",'taaza-plus'),
										"animate wdt_fadeInRightBig"     =>  esc_html__("fadeInRightBig",'taaza-plus'),
										"animate wdt_fadeInUp"           =>  esc_html__("fadeInUp",'taaza-plus'),
										"animate wdt_fadeInUpBig"        =>  esc_html__("fadeInUpBig",'taaza-plus'),
										"animate wdt_fadeOut"            =>  esc_html__("fadeOut",'taaza-plus'),
										"animate wdt_fadeOutDownBig"     =>  esc_html__("fadeOutDownBig",'taaza-plus'),
										"animate wdt_fadeOutLeft"        =>  esc_html__("fadeOutLeft",'taaza-plus'),
										"animate wdt_fadeOutLeftBig"     =>  esc_html__("fadeOutLeftBig",'taaza-plus'),
										"animate wdt_fadeOutRight"       =>  esc_html__("fadeOutRight",'taaza-plus'),
										"animate wdt_fadeOutUp"          =>  esc_html__("fadeOutUp",'taaza-plus'),
										"animate wdt_fadeOutUpBig"       =>  esc_html__("fadeOutUpBig",'taaza-plus'),
										"animate wdt_flash"              =>  esc_html__("flash",'taaza-plus'),
										"animate wdt_flip"               =>  esc_html__("flip",'taaza-plus'),
										"animate wdt_flipInX"            =>  esc_html__("flipInX",'taaza-plus'),
										"animate wdt_flipInY"            =>  esc_html__("flipInY",'taaza-plus'),
										"animate wdt_flipOutX"           =>  esc_html__("flipOutX",'taaza-plus'),
										"animate wdt_flipOutY"           =>  esc_html__("flipOutY",'taaza-plus'),
										"animate wdt_floating"           =>  esc_html__("floating",'taaza-plus'),
										"animate wdt_hatch"              =>  esc_html__("hatch",'taaza-plus'),
										"animate wdt_hinge"              =>  esc_html__("hinge",'taaza-plus'),
										"animate wdt_lightSpeedIn"       =>  esc_html__("lightSpeedIn",'taaza-plus'),
										"animate wdt_lightSpeedOut"      =>  esc_html__("lightSpeedOut",'taaza-plus'),
										"animate wdt_pullDown"           =>  esc_html__("pullDown",'taaza-plus'),
										"animate wdt_pullUp"             =>  esc_html__("pullUp",'taaza-plus'),
										"animate wdt_pulse"              =>  esc_html__("pulse",'taaza-plus'),
										"animate wdt_rollIn"             =>  esc_html__("rollIn",'taaza-plus'),
										"animate wdt_rollOut"            =>  esc_html__("rollOut",'taaza-plus'),
										"animate wdt_rotateIn"           =>  esc_html__("rotateIn",'taaza-plus'),
										"animate wdt_rotateInDownLeft"   =>  esc_html__("rotateInDownLeft",'taaza-plus'),
										"animate wdt_rotateInDownRight"  =>  esc_html__("rotateInDownRight",'taaza-plus'),
										"animate wdt_rotateInUpLeft"     =>  esc_html__("rotateInUpLeft",'taaza-plus'),
										"animate wdt_rotateInUpRight"    =>  esc_html__("rotateInUpRight",'taaza-plus'),
										"animate wdt_rotateOut"          =>  esc_html__("rotateOut",'taaza-plus'),
										"animate wdt_rotateOutDownRight" =>  esc_html__("rotateOutDownRight",'taaza-plus'),
										"animate wdt_rotateOutUpLeft"    =>  esc_html__("rotateOutUpLeft",'taaza-plus'),
										"animate wdt_rotateOutUpRight"   =>  esc_html__("rotateOutUpRight",'taaza-plus'),
										"animate wdt_shake"              =>  esc_html__("shake",'taaza-plus'),
										"animate wdt_slideDown"          =>  esc_html__("slideDown",'taaza-plus'),
										"animate wdt_slideExpandUp"      =>  esc_html__("slideExpandUp",'taaza-plus'),
										"animate wdt_slideLeft"          =>  esc_html__("slideLeft",'taaza-plus'),
										"animate wdt_slideRight"         =>  esc_html__("slideRight",'taaza-plus'),
										"animate wdt_slideUp"            =>  esc_html__("slideUp",'taaza-plus'),
										"animate wdt_stretchLeft"        =>  esc_html__("stretchLeft",'taaza-plus'),
										"animate wdt_stretchRight"       =>  esc_html__("stretchRight",'taaza-plus'),
										"animate wdt_swing"              =>  esc_html__("swing",'taaza-plus'),
										"animate wdt_tada"               =>  esc_html__("tada",'taaza-plus'),
										"animate wdt_tossing"            =>  esc_html__("tossing",'taaza-plus'),
										"animate wdt_wobble"             =>  esc_html__("wobble",'taaza-plus'),
										"animate wdt_fadeOutDown"        =>  esc_html__("fadeOutDown",'taaza-plus'),
										"animate wdt_fadeOutRightBig"    =>  esc_html__("fadeOutRightBig",'taaza-plus'),
										"animate wdt_rotateOutDownLeft"  =>  esc_html__("rotateOutDownLeft",'taaza-plus')
									) );

									foreach( $animations as $key => $value ) { ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $sub_menu_anim, $key, true ); ?>><?php echo esc_html ( $value ); ?></option><?php
									}?>
								</select>
								<span class="description"><?php esc_html_e('Please select child menu animation.',  'taaza-plus'); ?></span>
							</label>
						</p>

					<!-- Custom Fields -->

					<?php do_action( 'wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args ); ?>

					<fieldset class="field-move hide-if-no-js description description-wide">
						<span class="field-move-visual-label" aria-hidden="true"><?php esc_html_e( 'Move','taaza-plus' ); ?></span>
						<button type="button" class="button-link menus-move menus-move-up" data-dir="up"><?php esc_html_e( 'Up one','taaza-plus' ); ?></button>
						<button type="button" class="button-link menus-move menus-move-down" data-dir="down"><?php esc_html_e( 'Down one','taaza-plus' ); ?></button>
						<button type="button" class="button-link menus-move menus-move-left" data-dir="left"></button>
						<button type="button" class="button-link menus-move menus-move-right" data-dir="right"></button>
						<button type="button" class="button-link menus-move menus-move-top" data-dir="top"><?php esc_html_e( 'To the top','taaza-plus' ); ?></button>
					</fieldset>

					<div class="menu-item-actions description-wide submitbox">
						<?php if ( 'custom' !== $item->type && false !== $original_title ) : ?>
							<p class="link-to-original">
								<?php
								/* translators: %s: Link to menu item's original object. */
								printf( esc_html__( 'Original: %s','taaza-plus' ), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' );
								?>
							</p>
						<?php endif; ?>

						<?php
						printf(
							'<a class="item-delete submitdelete deletion" id="delete-%s" href="%s">%s</a>',
							$item_id,
							wp_nonce_url(
								add_query_arg(
									array(
										'action'    => 'delete-menu-item',
										'menu-item' => $item_id,
									),
									admin_url( 'nav-menus.php' )
								),
								'delete-menu_item_' . esc_attr($item_id)
							),
							__( 'Remove', 'taaza-plus' )
						);
						?>
						<span class="meta-sep hide-if-no-js"> | </span>
						<?php
						printf(
							'<a class="item-cancel submitcancel hide-if-no-js" id="cancel-%s" href="%s#menu-item-settings-%s">%s</a>',
							$item_id,
							esc_url(
								add_query_arg(
									array(
										'edit-menu-item' => $item_id,
										'cancel'         => time(),
									),
									admin_url( 'nav-menus.php' )
								)
							),
							$item_id,
							__( 'Cancel','taaza-plus' )
						);
						?>
					</div>

					<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item_id ); ?>" />
					<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
					<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
					<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
					<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
					<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
				</div><!-- .menu-item-settings-->
				<ul class="menu-item-transport"></ul>
			<?php
			$output .= ob_get_clean();
		}
	}
}