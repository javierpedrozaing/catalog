<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';

	if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
		require 'inc/nux/class-storefront-nux-starter-content.php';
	}
}


function storefront_scripts() {

	if(is_page('catalogo')) {
		wp_enqueue_style( 'bootstrap_css',  get_stylesheet_directory_uri() . '/assets/css/bootstrap.min.css',  array(),  '4.1.3' ); 		
		wp_enqueue_style( 'sweet_css',  get_stylesheet_directory_uri() . '/assets/css/sweetalert2.min.css',  array(),  '1.0.0' ); 
		wp_enqueue_script( 'bootstrap_js',  get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js',  array('jquery'),  '4.1.3',  true);
		wp_enqueue_script( 'sweetalert_js',  get_stylesheet_directory_uri() . '/assets/js/sweetalert2.min.js',  array('jquery'),  '4.1.3',  true);
	}
		
	wp_enqueue_style( 'main_css',  get_stylesheet_directory_uri() . '/assets/css/main.css',  array(),  '1.0.0' ); 
	wp_enqueue_script( 'main_js',  get_stylesheet_directory_uri() . '/assets/js/main.js',  array('jquery'),  '1.0.0',  true); 	

	global $wp_query;
	$storefront = array(        
		'_ajax_url' => admin_url( 'admin-ajax.php' ),
		'_ajax_nonce'=>wp_create_nonce( 'dhvc_form_ajax_nonce' ),
		'_themes_url'=>  get_template_directory_uri(),
		'_site_url'=> get_site_url(),
		'_current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
		'_max_page' => $wp_query->max_num_pages,
		
	);

	wp_localize_script('main_js', 'storefront_ajax', $storefront);
}

add_action( 'wp_enqueue_scripts', 'storefront_scripts');

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */

function get_data_product($product) {
	$ID_PRODUCT = $product->get_id();
	$thumbnail = $product->get_image();
	$image = $thumbnail[0];
	$title = $product->get_name();
	$categories = $product->get_categories();
	$ref = $product->get_sku();
	$public_price = $product->get_price();
	$wholesale_price = get_post_meta($product->get_id(), '_price', true);

	$data = array(
		'image' => $image,
		'title' => $title,
		'categories' => $categories,
		'ref' => $ref,
		'talla' => $talla,
		'public_price' => $public_price,
		'wholesale_price' => $wholesale_price
	);

	return $data;
	
}
// Get Woocommerce variation price based on product ID
function get_variation_price_by_id($product_id, $variation_id) {
	$currency_symbol = get_woocommerce_currency_symbol();
	$product = new WC_Product_Variable($product_id);
	$variations = $product->get_available_variations();
	$var_data = [];
	foreach ($variations as $variation) {
		if($variation['variation_id'] == $variation_id){
			$display_wholesale_price = '<span class="currency">'. $currency_symbol .'</span>'.$variation['wholesale_price_raw'];			
		}
	}

	$priceArray = array(
		'display_wholesale_price' => $display_wholesale_price,
		
	);
	$priceObject = (object)$priceArray;
	return $priceObject;
}


function render_products_table($products = "") { ?>
			
		<table class="table">
			<thead class="thead-dark">
				<tr>
				<th scope="col">#</th>
				<th scope="col">Imagen</th>
				<th scope="col">Producto</th>
				<th scope="col">Categoría</th>
				<th scope="col">Ref.</th>
				<th scope="col">Talla</th>
				<th scope="col">Precio al público</th>
				<th scope="col">Precio al por mayor</th>
				<th scope="col">Cantidad</th>
				<th scope="col">Total</th>
				</tr>
			</thead>
			<tbody>		
			<?php if(!empty($products) && is_array($products)) :  
				$tallas = [];
				$variation_wholesale_price = [];
				$variationsID = [];
				$variation_public_price = [];
				$currency_symbol = get_woocommerce_currency_symbol();
			?>
			
			<?php foreach ($products as $keyprod => $product) : 
				$myProduct = get_data_product($product); 		
				$available_variations = $product->get_available_variations();		
				

				foreach ($available_variations as $key => $variation) {
					$tallas[$keyprod][] = $variation['attributes']['attribute_talla'];
				}

				foreach ($tallas[$keyprod] as $key => $talla) {
					//var_dump($available_variations[$key]);exit;
					$variation_wholesale_price[$talla] =  wc_get_price_to_display($product, array( 'price' => $available_variations[$key]['wholesale_price'] ));					
					$variation_public_price[$talla] = wc_get_price_to_display($product, array( 'price' => $available_variations[$key]['display_regular_price'] ));
					$variationsID[$talla] =  $available_variations[$key]['variation_id'];
				}				
				
				?>	
				<tr data-id_product="<?= $product->get_id(); ?>">
					<th scope="row"><?= $keyprod+1 ?></th>
					<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'single-post-thumbnail' );?>
					<td><img src="<?= $image[0]; ?>" alt=""></td>
					<td><?= $myProduct['title']; ?></td>
					<td>					
						<span data-id-category="<?= $category->term_id; ?>"><?= $myProduct['categories']; ?></span>					
					</td>
					<td><?= $myProduct['ref']; ?></td>
					<td>		
						<select name="producto" class="product-talla">		
							<option value="">Talla</option>					
							<?php  foreach ($tallas[$keyprod] as $key => $talla) : ?>
								<option data-wholesale_price="<?= $variation_wholesale_price[$talla]; ?>" data-public_price="<?=  $variation_public_price[$talla] ?>" value="<?= $variationsID[$talla].'&'.$talla.'&'.$product->get_id().'&'.$variation_wholesale_price[$talla] ?>"><?= $talla ?></option>
							<?php endforeach; ?>							
						</select>
					</td>
					<td class="public-price"><span class="message-price">Seleccionar talla</span><span class="variation-price"></span></td>
					<td class="wholesale-price"><span class="message-price">Seleccionar talla</span><span class="variation-price"></span></td>
					<td><input disabled  class="quantity" name="quantity_<?= $product->get_id() ?>" min="0" value="0" type="number"></td>
					<td><span class="subtotal-price"></span> </td>
				</tr>
			<?php endforeach; else:  ?>
				<div class="alert alert-warning" role="alert">
					No se encontraron productos para el filtro seleccionado.
				</div>
			<?php endif; ?>
			<tr class="totals-row">
				<td colspan="7"></td>
				<td><strong>Total: </strong></td>
				<td><input disabled class="total-quantity" type="text"></td>
				<td><span class="total-price"> </span></td>
			</tr>
			</tbody>
		</table>	
<?php	
wp_die();
}


function get_all_products() {
	// Get draft products.
	$args = array(
		'status' => 'publish',
	);
	$products = wc_get_products( $args );
	render_products_table($products);
}
add_action( 'wp_ajax_nopriv_get_all_products', 'get_all_products' );
add_action( 'wp_ajax_get_all_products', 'get_all_products' );


/**
 * * Get values for each filter type
*/
 
function get_type_filter_values() {
	$filterType = $_POST['filter_type'];	
	$args = array(
		'orderby'    => $orderby,
		'order'      => $order,
		'hide_empty' => $hide_empty,
	);	
	$html = "";

	$options = [];

	switch ($filterType) {
		case 'product_tag':
			$options = get_terms( 'product_tag', $args );
			break;
		
		case 'product_cat':
			$options = get_terms( 'product_cat', $args );
			break;
		default: // cateogry
			$options =  "no_filter";
			break;
	}


	if ($options == 'no_filter') {
		echo 'no_filter';wp_die();
	}
	$html .= '<option>Seleccionar categoría</option>';
	foreach ($options as $key => $option) {
		$html .= '<option value='. $option->slug . '>' . $option->name . '</option>';
	}
	//echo wp_json_encode($response);

	echo $html;

	wp_die();

}

add_action( 'wp_ajax_nopriv_get_type_filter_values', 'get_type_filter_values' );
add_action( 'wp_ajax_get_type_filter_values', 'get_type_filter_values' );


function filter_products() {
	$type = $_POST['type'];
	$value = $_POST['value'];


	if ($type == 'category' ) {		
		$args = array(       
			'state' 			=> 'publish',
            'posts_per_page'    =>  -1,            
            'product_cat'       => $value,          
        );
	} else {
		$args = array(
			'state' 			=> 'publish',
            'posts_per_page'    =>  -1,            
            'product_tag'       => $value,          
        );
	}
	
	$products = wc_get_products( $args );	
	render_products_table($products);


}
add_action( 'wp_ajax_nopriv_filter_products', 'filter_products' );
add_action( 'wp_ajax_filter_products', 'filter_products' );

/**
 * save order datails
 */


 add_action('woocommerce_checkout_process', 'create_order');

function create_order() {
	global $woocommerce;
	// here we are creating the users address.  All of these variables have been assigned data previously in the submission process
	$user = _wp_get_current_user();	
	$customerData = $_POST['customerData'];
	$productsData = $_POST['products'];

	$firstname = $customerData[0]['value'];
	$cedula = $customerData[1]['value'];
	$email = $customerData[2]['value'];
	$phone = $customerData[3]['value'];
	$address1 = $customerData[4]['value'];

	$address = array(
		'first_name' => $firstname,
		'last_name'  => "",
		'company'    => '',
		'email'      => $email,
		'phone'      => $phone,
		'address_1'  => $address1,
		'address_2'  => '',
		'city'       => 'Bogotá',
		'state'      => 'Colombia',
		'postcode'   => '',
		'country'    => 'CO'
	);


	if (!empty($productsData) && !empty($customerData)) {			
		$order = wc_create_order(array('customer_id' => $user->ID));

		if (!is_wp_error($order)) {	
			$variationsID = [];
			$variationsItem = [];
			$productsID = [];
			$whosalesPrice = [];
			$quantities  = [];
			$variations = [];
			foreach ($productsData as $key => $product) {

				if($product['name'] != "producto") {			
					//$theProduct = explode("_", $product['name']);
					$quantities[] =  $product['value'];
				}			

				if ($product['name'] == "producto" && !empty($product['value'])) {
					$myProduct = explode("&", $product['value']); // explode => $idVariation.'&'.$talla.'&'.$product->get_id().'&'.$variation_wholesale_price[$talla]
					$variationsID[] = $myProduct[0];
					$variationsItem[] = $myProduct[1];
					$productsID[] = $myProduct[2];
					$whosalesPrice[] = $myProduct[3];	
				}			
			}

			foreach ($productsID as $key => $product) {
				$variableProduct = new WC_Product_Variable($product);
				//$productVariations = $variableProduct->get_available_variations();
				$varProduct = new WC_Product_Variation($variationsID[$key]);			
								
				foreach ($varProduct->get_variation_attributes() as $attribute=>$attribute_value) {
					$variations['variation'][$attribute]=$attribute_value;
				}				
				$varProduct->set_price($whosalesPrice[$key]);
				$quantity = $quantities[$key];				
				$order->add_product($varProduct, $quantity, $variations);
				$order->calculate_totals();
		
				$shipping_tax = array(); 
				// Here we're going to assign a custom shipping method.
			
				//$shipping_rate = new WC_Shipping_Rate( '', 'Flat Rate', '5.95', $shipping_tax, 'custom_shipping_method' );
				//$order->add_shipping($shipping_rate);
				$order->set_address( $address, 'billing' );
				$order->set_address( $address, 'shipping' );
				$order->update_status("processing", 'Imported Order From Funnel', TRUE);
				echo $productID . "  agregado ";
			}

				echo "Tu pedido fue realizado exitosamente";
		} else {
			echo $order->get_error_message();
		}
	
	}
	
	wp_die();
	
	
}

 add_action( 'wp_ajax_nopriv_create_order', 'create_order' );
 add_action( 'wp_ajax_create_order', 'create_order' );
 


 add_filter( 'woocommerce_return_to_shop_redirect', 'redirect_to_catalog' );
/**
 * Redirect WooCommerce Shop URL
 */

function redirect_to_catalog(){

return site_url() . '/catalogo';

}