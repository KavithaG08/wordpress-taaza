<?php
/**
 * Wishlist page template - Standard Layout
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 3.0.0
 */

/**
 * Template variables:
 *
 * @var $wishlist                      \YITH_WCWL_Wishlist Current wishlist
 * @var $wishlist_items                array Array of items to show for current page
 * @var $wishlist_token                string Current wishlist token
 * @var $wishlist_id                   int Current wishlist id
 * @var $users_wishlists               array Array of current user wishlists
 * @var $current_page                  int Current page
 * @var $page_links                    array Array of page links
 * @var $is_user_owner                 bool Whether current user is wishlist owner
 * @var $show_price                    bool Whether to show price column
 * @var $show_dateadded                bool Whether to show item date of addition
 * @var $show_stock_status             bool Whether to show product stock status
 * @var $show_add_to_cart              bool Whether to show Add to Cart button
 * @var $show_remove_product           bool Whether to show Remove button
 * @var $show_price_variations         bool Whether to show price variation over time
 * @var $show_variation                bool Whether to show variation attributes when possible
 * @var $show_cb                       bool Whether to show checkbox column
 * @var $show_quantity                 bool Whether to show input quantity or not
 * @var $show_ask_estimate_button      bool Whether to show Ask an Estimate form
 * @var $show_last_column              bool Whether to show last column (calculated basing on previous flags)
 * @var $move_to_another_wishlist      bool Whether to show Move to another wishlist select
 * @var $move_to_another_wishlist_type string Whether to show a select or a popup for wishlist change
 * @var $additional_info               bool Whether to show Additional info textarea in Ask an estimate form
 * @var $price_excl_tax                bool Whether to show price excluding taxes
 * @var $enable_drag_n_drop            bool Whether to enable drag n drop feature
 * @var $repeat_remove_button          bool Whether to repeat remove button in last column
 * @var $available_multi_wishlist      bool Whether multi wishlist is enabled and available
 * @var $no_interactions               bool
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>

<!-- WISHLIST TABLE -->
<table class="shop_table cart wishlist_table wishlist_view traditional responsive" data-pagination="<?php echo esc_attr( $pagination )?>" data-per-page="<?php echo esc_attr( $per_page )?>" data-page="<?php echo esc_attr( $current_page )?>" data-id="<?php echo esc_attr( $wishlist_id ); ?>" data-token="<?php echo esc_attr( $wishlist_token ); ?>">

    <?php $column_count = 2; ?>

    <thead>
    <tr>
        <?php if( $show_cb ) : $column_count ++; ?>
            <th class="product-checkbox">
                <input type="checkbox" value="" name="" id="bulk_add_to_cart"/>
            </th>
        <?php endif; ?>



        <th class="product-thumbnail">
            <span class="nobr">
                <?php echo apply_filters( 'yith_wcwl_wishlist_view_name_heading', esc_html__( 'Product', 'taaza-pro' ) ) ?>
            </span>
        </th>

        <th class="product-name"></th>

        <?php if( $show_price || $show_price_variations ) : $column_count ++; ?>
            <th class="product-price">
                <span class="nobr">
                    <?php echo apply_filters( 'yith_wcwl_wishlist_view_price_heading', esc_html__( 'Unit price', 'taaza-pro' ) ) ?>
                </span>
            </th>
        <?php endif; ?>

        <?php if( $show_quantity ) : $column_count ++; ?>
            <th class="product-quantity">
                <span class="nobr">
                    <?php echo apply_filters( 'yith_wcwl_wishlist_view_quantity_heading', esc_html__( 'Quantity', 'taaza-pro' ) ) ?>
                </span>
            </th>
        <?php endif;?>

        <?php if( $show_stock_status ) : $column_count ++; ?>
            <th class="product-stock-status">
                <span class="nobr">
                    <?php echo apply_filters( 'yith_wcwl_wishlist_view_stock_heading', esc_html__( 'Stock status', 'taaza-pro' ) ) ?>
                </span>
            </th>
        <?php endif; ?>

        <?php if( $show_last_column ) : $column_count ++; ?>
            <th class="product-add-to-cart"></th>
        <?php endif; ?>

        <?php if( $enable_drag_n_drop ): $column_count ++; ?>
            <th class="product-arrange">
                <span class="nobr">
                    <?php echo apply_filters( 'yith_wcwl_wishlist_view_arrange_heading', esc_html__( 'Arrange', 'taaza-pro' ) )?>
                </span>
            </th>
        <?php endif; ?>

        <?php if( $show_remove_product ): $column_count ++; ?>
            <th class="product-remove"></th>
        <?php endif; ?>

    </tr>
    </thead>

    <tbody class="wishlist-items-wrapper">
    <?php
    if( $wishlist && $wishlist->has_items() ) :
        foreach( $wishlist_items as $item ) :
            /**
             * @var $item \YITH_WCWL_Wishlist_Item
             */
            global $product;

            $product = $item->get_product();
            $availability = $product->get_availability();
            $stock_status = isset( $availability['class'] ) ? $availability['class'] : false;

            if( $product && $product->exists() ) :
                ?>
                <tr id="yith-wcwl-row-<?php echo esc_attr($item->get_product_id()); ?>" data-row-id="<?php echo esc_attr($item->get_product_id()); ?>">
                    <?php if( $show_cb ) : ?>
                        <td class="product-checkbox">
                            <input type="checkbox" value="yes" name="items[<?php echo esc_attr( $item->get_product_id() ) ?>][cb]" />
                        </td>
                    <?php endif ?>

                    <td class="product-thumbnail">
                        <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item->get_product_id() ) ) ) ?>">
                            <?php echo taaza_html_output($product->get_image()); ?>
                        </a>
                    </td>

                    <td class="product-name">
                        <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item->get_product_id() ) ) ) ?>"><?php echo apply_filters( 'woocommerce_in_cartproduct_obj_title', $product->get_title(), $product ) ?></a>

                        <?php
                        if( $show_variation && $product->is_type( 'variation' ) ){
                            /**
                             * @var $product \WC_Product_Variation
                             */
                            echo wc_get_formatted_variation( $product );
                        }
                        ?>

                        <?php do_action( 'yith_wcwl_table_after_product_name', $item ); ?>
                    </td>

                    <?php if( $show_price || $show_price_variations ) : ?>
                        <td class="product-price">
                            <?php
                            if( $show_price ) {
                                echo taaza_html_output($item->get_formatted_product_price());
                            }

                            if( $show_price_variations ){
                                echo taaza_html_output($item->get_price_variation());
                            }
                            ?>
                        </td>
                    <?php endif ?>

                    <?php if( $show_quantity ) : ?>
                        <td class="product-quantity">
                            <?php if( ! $no_interactions && $is_user_owner ): ?>
                                <input type="number" min="1" step="1" name="items[<?php echo esc_attr( $item->get_product_id() )?>][quantity]" value="<?php echo esc_attr( $item->get_quantity() )?>" />
                            <?php else: ?>
                                <?php echo taaza_html_output($item->get_quantity()); ?>
                            <?php endif; ?>
                        </td>
                    <?php endif;?>

                    <?php if( $show_stock_status ) : ?>
                        <td class="product-stock-status">
                            <?php echo esc_attr($stock_status) == 'out-of-stock' ? '<span class="wishlist-out-of-stock">' . apply_filters( 'yith_wcwl_out_of_stock_label', esc_html__( 'Out of stock', 'taaza-pro' ) ) . '</span>' : '<span class="wishlist-in-stock">' . apply_filters( 'yith_wcwl_in_stock_label', esc_html__( 'In Stock', 'taaza-pro' ) ) . '</span>'; ?>
                        </td>
                    <?php endif ?>

                    <?php if( $show_last_column ): ?>
                        <td class="product-add-to-cart">
                            <!-- Date added -->
                            <?php
                            if( $show_dateadded && $item->get_date_added() ):
                                echo '<span class="dateadded">' . sprintf( esc_html__( 'Added on: %s', 'taaza-pro' ), $item->get_date_added_formatted() ) . '</span>';
                            endif;
                            ?>

                            <!-- Add to cart button -->
                            <?php if( $show_add_to_cart && isset( $stock_status ) && $stock_status != 'out-of-stock' ): ?>
                                <?php woocommerce_template_loop_add_to_cart( array( 'quantity' => $show_quantity ? $item->get_quantity() : 1 ) ); ?>
                            <?php endif ?>

                            <!-- Change wishlist -->
                            <?php if( $move_to_another_wishlist && $available_multi_wishlist && count( $users_wishlists ) > 1 ): ?>
                                <?php if( 'select' == $move_to_another_wishlist_type ): ?>
                                    <select class="change-wishlist selectBox">
                                        <option value=""><?php _e( 'Move', 'taaza-pro' ) ?></option>
                                        <?php
                                        foreach( $users_wishlists as $wl ):
                                            /**
                                             * @var $wl \YITH_WCWL_Wishlist
                                             */
                                            if( $wl->get_token() == $wishlist_token ){
                                                continue;
                                            }
                                        ?>
                                            <option value="<?php echo esc_attr( $wl->get_token() ) ?>">
                                                <?php echo sprintf( '%s - %s', $wl->get_formatted_name(), $wl->get_formatted_privacy() ); ?>
                                            </option>
                                        <?php
                                        endforeach;
                                        ?>
                                    </select>
                                <?php else: ?>
                                    <a href="#move_to_another_wishlist" class="move-to-another-wishlist-button" data-rel="prettyPhoto[move_to_another_wishlist]">
                                        <?php echo apply_filters( 'yith_wcwl_move_to_another_list_label', esc_html__( 'Move to another list &rsaquo;', 'taaza-pro' ) ) ?>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Remove from wishlist -->
                            <?php if( $repeat_remove_button ): ?>
                                <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item->get_product_id() ) ) ?>" class="remove_from_wishlist button" title="<?php echo apply_filters( 'yith_wcwl_remove_product_wishlist_message_title',esc_html__( 'Remove this product', 'taaza-pro' )); ?>"><?php _e( 'Remove', 'taaza-pro' ) ?></a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>

                    <?php if( $enable_drag_n_drop ): ?>
                        <td class="product-arrange ">
                            <i class="fa fa-arrows"></i>
                            <input type="hidden" name="items[<?php echo esc_attr( $item->get_product_id() )?>][position]" value="<?php echo esc_attr( $item->get_position() )?>" />
                        </td>
                    <?php endif; ?>

                    <?php if( $show_remove_product ): ?>
                        <td class="product-remove">
                            <div>
                                <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item->get_product_id() ) ) ?>" class="remove remove_from_wishlist" title="<?php echo apply_filters( 'yith_wcwl_remove_product_wishlist_message_title', esc_html__( 'Remove this product', 'taaza-pro' ) ); ?>">&times;</a>
                            </div>
                        </td>
                    <?php endif; ?>


                </tr>
            <?php
            endif;
        endforeach;
    else: ?>
        <tr>
            <td colspan="<?php echo esc_attr( $column_count ) ?>" class="wishlist-empty"><?php echo apply_filters( 'yith_wcwl_no_product_to_remove_message', esc_html__( 'No products added to the wishlist', 'taaza-pro' ) ) ?></td>
        </tr>
    <?php
    endif;

    if( ! empty( $page_links ) ) : ?>
        <tr class="pagination-row">
            <td colspan="<?php echo esc_attr( $column_count ) ?>"><?php echo taaza_html_output($page_links) ?></td>
        </tr>
    <?php endif ?>
    </tbody>

</table>