<?php
namespace SolidApi;
//require_once 'D:\xampp\htdocs\nam-kha\wp-content\plugins\woocommerce\includes/class-wc-cart.php';
class Cart {
	
	function addCoupon($coupon_code) {
		session_start(); 
		
		//print_r($_SESSION["cart_item"]); die
		//$a = woocommerce_get_template_part( 'cart');
		/*$html = '
		<div class="shopcart">
			<a class="cart-contents" href="'. WC()->cart->get_cart_url().'" title="'. _e( 'Giỏ hàng ' ) .'">
				'. sprintf (_n( "Giỏ hàng (%d)", "Giỏ hàng (%d)", WC()->cart->cart_contents_count ), WC()->cart->cart_contents_count ) .'
			</a>
		</div>';
		*/
		//print_r($html); die;
		//$array = WC_Cart::get_cart();
		//print_r($array);
//die;
		$k = WC()->cart->get_cart();
		print_r($k);
		global $woocommerce;
		
		//$items = WC()->cart->get_cart();
		//print_r($items); die;
		$items = $woocommerce->cart->get_cart();
		print_r($items); die;
		$coupon_code = "ww";
		if (!$woocommerce->cart->add_discount( sanitize_text_field( $coupon_code ))) {
            $woocommerce->show_messages();
        }

		die;
	}
}