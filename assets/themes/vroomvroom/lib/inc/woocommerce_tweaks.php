<?php
add_filter('woocommerce_subcategory_count_html','msdlab_remove_count');
function msdlab_remove_count($count){
    //ts_data($count);
}
add_filter('get_product_search_form','msdlab_product_search_form');
function msdlab_product_search_form($content){
    $content = preg_replace('@value="Search"@i','value="&#xF002;"',$content);
    return $content;
}
add_filter('woocommerce_output_related_products_args','msdlab_output_related_products_args');
function msdlab_output_related_products_args($defaults) {
    $args = array(
            'posts_per_page' => 3,
            'columns' => 3,
            'orderby' => 'rand'
        );
    return wp_parse_args( $args, $defaults );
}

add_action('woocommerce_before_shop_loop_item_title','msdlab_add_imgwrap_open',5);
add_action('woocommerce_before_shop_loop_item_title','msdlab_add_imgwrap_close',20);
add_action('woocommerce_before_subcategory_title','msdlab_add_imgwrap_open',5);
add_action('woocommerce_before_subcategory_title','msdlab_add_imgwrap_close',20);

if( ! function_exists('msdlab_add_imgwrap_open')){
    function msdlab_add_imgwrap_open(){
        print '<div class="img-wrap">';
    }
}
if( ! function_exists('msdlab_add_imgwrap_close')){
    function msdlab_add_imgwrap_close(){
        print '</div>';
    }
}

if ( ! function_exists( 'woocommerce_subcategory_thumbnail' ) ) {

    /**
     * Show subcategory thumbnails.
     *
     * @access public
     * @param mixed $category
     * @subpackage  Loop
     * @return void
     */
    function woocommerce_subcategory_thumbnail( $category ) {
        $small_thumbnail_size   = apply_filters( 'single_product_small_thumbnail_size', 'shop_catalog' );
        $dimensions             = function_exists('wc_get_image_size')?wc_get_image_size( $small_thumbnail_size ):array('width'=>'','height'=>'');
        $thumbnail_id           = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );
        if(!$thumbnail_id){$args = array(
                'posts_per_page' => 1,
                'product_cat' => $category->slug,
                'post_type' => 'product',
            );
            $products = get_posts($args);
            $thumbnail_id = get_post_thumbnail_id($products[0]->ID);
        }

        if ( $thumbnail_id ) {
            $image = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size  );
            $image = $image[0];
        } else {
            $image = wc_placeholder_img_src();
        }

        if ( $image ) {
            // Prevent esc_url from breaking spaces in urls for image embeds
            // Ref: http://core.trac.wordpress.org/ticket/23605
            $image = str_replace( ' ', '%20', $image );
            echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" />';
        }
    }
}
add_filter('loop_shop_columns','msdlab_columns');
function msdlab_columns($columns){
    return 3;
}
