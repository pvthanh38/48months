<?phpadd_action( 'wp_ajax_nopriv_ajax_ajax_rollback', 'set_ajax_rollback' );add_action( 'wp_ajax_ajax_rollback', 'set_ajax_rollback' );function set_ajax_rollback() {	set_time_limit (0);	extract($_POST);	$user_id = get_current_user_id();	add_post_meta($post_id,'vote',$user_id);	$arr_vote = get_post_meta($post_id,'vote');	$result = array_unique($arr_vote);	echo count($result); die;}