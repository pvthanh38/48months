<?php
/*function action_woocommerce_new_order( $order_id ) { 
	global $woocommerce;
	$od = new \WC_Order( $order_id );
	
	$order_ob = json_decode($od);
	$order_total = $order_ob->total;
    $items = $woocommerce->cart->get_cart();
	$product_sel = [];
	$price_cost = 0;		
	$orders_ar = [];
	foreach($items as $item => $values) { 
		$product_id = $values['data']->get_id();
		$product = wc_get_product( $product_id );
		$price_cost_item = get_post_meta($product_id, '_price_cost');
		$product_qty = $values['quantity'];
		$price_cost = isset($price_cost_item[0]) ? ($price_cost_item[0] * $product_qty) + $price_cost : 0;
		if (array_key_exists($product_id, $product_sel)) {
			$product_sel[$product_id]= $product_qty + $product_sel[$product_id];
		}else{
			$product_sel[$product_id]= $product_qty;
		}
		
	}
	$orders_ar['order_total'] = $order_total;
	$orders_ar['order_cost'] = $price_cost;
	$orders_ar['income'] = $order_total - $price_cost;
	add_post_meta( $order_id, 'price_cost_'.$order_id, $price_cost);
	
}; 
add_action( 'woocommerce_new_order', 'action_woocommerce_new_order', 1, 1 ); */

add_action( 'woocommerce_before_calculate_totals', 'add_custom_price' );
function add_custom_price( $cart_object ) {
    $custom_price = 10; // This will be your custome price  
	$ar_cart = [];
	foreach ( $cart_object->cart_contents as $key => $value ) {
		$product_id = $value['product_id'];
		$_product = wc_get_product( $product_id );
		$qty = $value['quantity'];
		if (array_key_exists($product_id,$ar_cart)){
			$ar_cart[$product_id] = $qty + $ar_cart[$product_id];
			
		}else{
			$ar_cart[$product_id] = $qty;
		}
		
	}
    foreach ( $cart_object->cart_contents as $key => $value ) {
		
		$product_id = $value['product_id'];
		$_product = wc_get_product( $product_id );
		$price = $_product->get_price();
		$terms = wp_get_post_terms( $product_id, 'product_cat');
		$price_ar = [];
		foreach($terms as $term) {
			//print_r($term); die;
			$t_id = $term->term_id;
			$term_meta = get_option( "taxonomy_$t_id" );
			if(isset($term_meta['custom_term_meta']) && $term_meta['custom_term_meta'] != ""){
				$str_dk = $term_meta['custom_term_meta'];
				$str_dk = str_replace('num', $ar_cart[$product_id], $str_dk);
				$ar_dk = explode(';', $str_dk);
				foreach($ar_dk as $dk) {
					$dks = explode(',', $dk);
					if(isset($dks[0]) && isset($dks[1])){
						$dk_key = $dks[0];
						$dk_value_price = $dks[1];
						$ev = eval("return $dk_key;");
						if($ev){
							$pos = strpos($dk_value_price, '%');
							if($pos === false){							
								$price_ar[] = (int)$dk_value_price;
							}else{
								$percent = str_replace('%', '', $dk_value_price);
								$price_percent = ((int) $percent * $price) / 100;
								$price_ar[] = $price_percent;
							}
							
						}
					}
					
				}
			}
		}
		sort($price_ar);
		if(isset($price_ar[0])){
			$price_total = ($price - $price_ar[0]);
			$value['data']->set_price( $price_total );
		}
    }
}



// Add term page
function custom_product_taxonomy_add_new_meta_field() {
    // this will add the custom meta field to the add new term page
    ?>
    <div class="form-field">
        <label for="term_meta[custom_term_meta]"><?php _e( 'Điều kiện giảm giá', 'my-text-domain' ); ?></label>
        <input type="text" name="term_meta[custom_term_meta]" id="term_meta[custom_term_meta]" value="">
        <p class="description"><?php _e( 'Enter a value for this field','my-text-domain' ); ?></p>
    </div>
<?php
}
add_action( 'product_cat_add_form_fields', 'custom_product_taxonomy_add_new_meta_field', 10, 2 );
// Edit term page
function custom_product_taxonomy_edit_meta_field($term) {

    // put the term ID into a variable
    $t_id = $term->term_id;

    // retrieve the existing value(s) for this meta field. This returns an array
    $term_meta = get_option( "taxonomy_$t_id" ); ?>
    <tr class="form-field">
    <th scope="row" valign="top"><label for="term_meta[custom_term_meta]"><?php _e( 'Điều kiện giảm giá', 'my-text-domain' ); ?></label></th>
        <td>
            <input type="text" name="term_meta[custom_term_meta]" id="term_meta[custom_term_meta]" value="<?php echo esc_attr( $term_meta['custom_term_meta'] ) ? esc_attr( $term_meta['custom_term_meta'] ) : ''; ?>">
            <p class="description"><?php _e( 'Enter a value for this field','my-text-domain' ); ?></p>
        </td>
    </tr>
<?php
}
add_action( 'product_cat_edit_form_fields', 'custom_product_taxonomy_edit_meta_field', 10, 2 );
// Save extra taxonomy fields callback function.
function save_taxonomy_custom_meta( $term_id ) {
    if ( isset( $_POST['term_meta'] ) ) {
        $t_id = $term_id;
        $term_meta = get_option( "taxonomy_$t_id" );
        $cat_keys = array_keys( $_POST['term_meta'] );
        foreach ( $cat_keys as $key ) {
            if ( isset ( $_POST['term_meta'][$key] ) ) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        // Save the option array.
        update_option( "taxonomy_$t_id", $term_meta );
    }
}  
add_action( 'edited_product_cat', 'save_taxonomy_custom_meta', 10, 2 );  
add_action( 'create_product_cat', 'save_taxonomy_custom_meta', 10, 2 );