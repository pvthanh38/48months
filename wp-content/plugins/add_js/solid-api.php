<?php
/*
Plugin Name: Include JS
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: Matt Mullenweg
Version: 1.6
Author URI: http://ma.tt/
*/
//echo __FILE__; die;
/*require_once ('lib/Upload.php');

require_once( ABSPATH . 'wp-load.php' );


use SolidApi\Upload;


class SolidApi{
	protected $allowActions;
	function __construct(){
		global $woocommerce;
		$this->allowActions = [];
		add_action('wp_ajax_solid_import', array($this, 'doIt'));
		add_action('wp_ajax_nopriv_solid_import', array($this, 'doIt'));
	}
	function getRequest(){
		if ($_POST && isset($_POST['cmd'])){
			return $_POST;
		}else{
			return $_GET;
		}
		return false;
	}

	function doIt(){
		$data = $this->getRequest();
		if (!$data) exit();
		$upload = new Upload();
		switch ($data['cmd']){
			
			case 'download.drive':
				$upload->Drive($data);
				break;
			case 'get.drive':
				$upload->Get_Drive($data);
				break;
			case 'download.drive.xlsx':
				$upload->Drive_xlsx($data);
				break;
			case 'unzip':
				$upload->Unzip($data);
				break;
			case 'cronjob':
				$upload->Cronjob($data);
				break;
			case 'download.edit':
				$upload->Files_Edit($data);
				break;
			case 'edit.collections':
				$upload->Edit_Collections($data);
				break;
			case 'enter.products':
				$upload->Enter_Products();
				break;
			case 'update.products.color':
				$upload->Update_Products_Color();
				break;
			case 'remove.products.color':
				$upload->Remove_Products_Color_Not_Image();
				break;
			
			default:
				echo 'Hello world!';
				break;
		}
		exit();
	}
}

$solid = new SolidApi();
//include 'import_log.php';
//include 'rollback.php';
*/
function insert_my_footer() {
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		if (strpos($actual_link, 'login') == false && strpos($actual_link, 'register') == false) {
  ?>
  <link rel="stylesheet" type="text/css" href="<?php echo get_site_url(); ?>/wp-content/plugins/add_js/css/sweetalert.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo get_site_url(); ?>/wp-content/plugins/add_js/css/fakeLoader.css" />
	<script type="text/javascript" src="<?php echo get_site_url(); ?>/wp-content/plugins/add_js/js/jquery-1.12.0.min.js"></script>
	<script type="text/javascript" src="<?php echo plugins_url(); ?>/add_js/js/sweetalert.min.js"></script>
	<script type="text/javascript" src="<?php echo get_site_url(); ?>/wp-content/plugins/add_js/js/fakeLoader.min.js"></script>
	<style>select{min-height: 35px;}</style>
	<div class="fakeloader"></div>
	<?php if(is_user_logged_in()){ ?>
	<style>
	#menu-item-1725, #menu-item-1726, .item1722, .item1723{ display:none !important;}
	</style>
	<script>
	var str = '<li  class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1725"><a href="<?php echo get_home_url().'/kpi'; ?>">My KPI</a></li>';
	jQuery('ul.menu').append(str);
	</script>
  <?php
	}
		}
}

add_action('wp_footer', 'insert_my_footer');


function css_admin() {
	if( is_user_logged_in() ) {
		$user = wp_get_current_user();
		$roles = ( array ) $user->roles;
		
    
		if($roles[0] == 'subscriber'){
			?>
			<style>
			#menu-posts{display:none;}
			#menu-media{display:none;}
			#menu-comments{display:none;}
			#menu-posts-graphic{display:none;}
			#menu-posts-voice{display:none;}
			#menu-posts-downloads{display:none;}
			#menu-posts-performance_dashboar{display:none;}
			
			#toplevel_page_wpcf7{display:none;}
			#menu-users{display:none;}
			#menu-tools{display:none;}
			.update-nag{display:none;}
			
			#dashboard_right_now{display:none;}
			#wp-admin-bar-new-content{display:none;}
			#toplevel_page_theme-general-settings{display:none;}
			#toplevel_page_vc-welcome{display:none;}
			
			</style>
			
			<?php
		}
		?>
		<script>
			setTimeout(function(){ jQuery('#authordiv h2 span').text("User"); }, 3000);
			
			</script>
		<?php
	}
}
add_action( 'admin_head', 'css_admin' );
?>

<?php
add_action( 'wp_ajax_nopriv_ajax_ajax_rollback', 'set_ajax_rollback' );
add_action( 'wp_ajax_ajax_rollback', 'set_ajax_rollback' );
function set_ajax_rollback() {
	set_time_limit (0);
	extract($_POST);
	$user_id = get_current_user_id();
	$class = 0;
	$arr_vote = get_post_meta($post_id,'vote');
	foreach($arr_vote as $u){
		if($user_id == $u){
			$class = 1;
			delete_post_meta($post_id, 'vote', $u);
		}
	}
	if($class == 0){
		add_post_meta($post_id,'vote',$user_id);
	}
	$arr_votes = get_post_meta($post_id,'vote');
	$result = array_unique($arr_votes);
	echo count($result); die;
}
//include script
add_action( 'wp_enqueue_scripts', 'ajax_check_code_scripts' );
add_action( 'admin_enqueue_scripts', 'ajax_check_code_scripts' );
function ajax_check_code_scripts() {
 	wp_enqueue_script( 'ajax-check-script', plugins_url( '/js/ajax-check.js', __FILE__ ),
 		array( 'jquery' )
 	);
 	global $wp_query;
 	wp_localize_script( 'ajax-check-script', 'ajax_object', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'query_vars' => json_encode( $wp_query->query )

	));
}

//require_once 'import.php';
