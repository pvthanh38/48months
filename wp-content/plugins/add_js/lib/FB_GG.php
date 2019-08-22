<?php
add_action( 'wp_enqueue_scripts', 'ajax_check_code_scripts' );
 function ajax_check_code_scripts() {
 	wp_enqueue_script( 'ajax-check-script', plugins_url( '../js/ajax-check.js', __FILE__ ),
 		array( 'jquery' )
 	);
 	global $wp_query;
 	wp_localize_script( 'ajax-check-script', 'ajax_object', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'query_vars' => json_encode( $wp_query->query )

	));
 }
add_shortcode('social_login', 'create_login');
function create_login($ts){
	ob_start();
?>
	<meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="487516480450-3q4uevq331prbdki189bbn7umcfiadm9.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
	<script type="text/javascript" src="<?php echo plugins_url(); ?>/solid_api/js/ajax-check.js"></script>
	
	<div id="fb-root"></div>
	<script>
	</script>
	</div>
	<fb:login-button scope="public_profile,email" onlogin="checkLoginState();"></fb:login-button>
	<div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
<?php
	$sc = ob_get_contents();
	ob_end_clean();
	return $sc;
}


/*
 @ Hàm chứa dữ liệu trả về
*/
add_action( 'wp_ajax_nopriv_add_user', 'set_add_user' );
add_action( 'wp_ajax_add_user', 'set_add_user' );
function set_add_user() {
	extract($_POST);
	$parts = explode("@", "$email");
	$username = $parts[0];
	$id = email_exists($email);
	$pass = '1234@qwer';
	if($id === false){
		$id = wp_create_user( $username, $pass, $email );
	}
	$data = (array)get_userdata($id);
	$data = $data['data'];
	$username = $data->user_login;
	
	if(wp_login($username, $pass) === true){
		wp_set_current_user( $id, $username );
		wp_set_auth_cookie( $id );
		do_action( 'wp_login', $username );
		echo home_url('/'); die;
	}
	echo 500;
	die;

}