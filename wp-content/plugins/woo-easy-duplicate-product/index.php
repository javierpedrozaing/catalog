<?php

/**

 * Plugin name: WooCommerce Easy Duplicate Product
 * Author: WPGem.com
 * Plugin URI: http://wpgem.com/woo-easy-duplicate
 * Description: An easy and convenient way for you to duplicate a product.
 * Version: 0.1.4.0
 * Author: WPGem.com
 * Author URI: http://WPGem.com
 * 
**/


function wedp_show_the_duplicate_link ($post){
	
	$url = '<a target="_blank" href="' . wp_nonce_url( admin_url( 'edit.php?post_type=product&action=duplicate_product&amp;post=' . $post->ID ), 'woocommerce-duplicate-product_' . $post->ID ) . '" aria-label="' . esc_attr__( 'Make a duplicate from this product', 'woocommerce' )
			. '" rel="permalink">' . __( 'Duplicate', 'woocommerce' ) . '</a>';

	$url .= '<p><small><em><a style="color: #444;" href="https://bit.ly/wpgem-go" target="_blank">(Slow site?)</a></em></<small></p>';

	echo $url;
}

function wedp_add_the_metabox($_post){
	
	global $post;	

	$post_type = $post->post_type;

	if('product' != $post_type){
		return;
	}

	add_meta_box( 'woocommerce-easy-product-duplicate', __( 'Duplicate this product', 'woocommerce' ), 'wedp_show_the_duplicate_link', 'product', 'side', 'high' );
}

add_action( 'add_meta_boxes', 'wedp_add_the_metabox', 30 );


function wedb_duplicate_product_bulk_action($bulk_actions)
{	
	$bulk_actions['wedp_duplicate_product'] = 'Duplicate product';

	return $bulk_actions;

}

add_filter('bulk_actions-edit-product', 'wedb_duplicate_product_bulk_action');
add_filter('handle_bulk_actions-edit-product', 'wedb_handle_duplicate_product_bulk_action',10, 3);

function wedb_handle_duplicate_product_bulk_action($redirect_to, $doaction, $post_ids)
{	

	$duplicated = [];
	
	$WC_Duplicate = new WC_Admin_Duplicate_Product;
	foreach ($post_ids as $product_id) {
		
		$product = wc_get_product( $product_id );

		if($product){
		
			$duplicate = $WC_Duplicate->product_duplicate( $product );
			do_action( 'woocommerce_product_duplicate', $duplicate, $product );

			$duplicated[] = $product;
		}
	}

	$total_updated = count($duplicated);

	$redirect_to .= '&wedp_duplicated='.$total_updated;
	return $redirect_to;

}


function wedp_custom_bulk_admin_notices() {

  if(isset($_GET['wedp_duplicated'])){

  	$total_updated = $_GET['wedp_duplicated'];

  	$message = "{$total_updated} products duplicated.";

  	echo  '<div class="updated"><p>'. $message .'</p></div>';
  }
}

add_action('admin_notices', 'wedp_custom_bulk_admin_notices');
