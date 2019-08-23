<?php 
$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$uri_parts[0]}";
$oderby = 'DESC';
if(isset($_GET['f'])){
	if($_GET['f'] == 'new'){
		$oderby = 'DESC';
	}
	if($_GET['f'] == 'old'){
		$oderby = 'ASC';
	}
	if($_GET['f'] == 'vote'){
		$oderby = 'DESC';
	}
}


$args = array(
	'posts_per_page'   => 20,
	'order' => $oderby,
	'post_type' => 'ideas',
);
if(isset($_GET['f'])){
	if($_GET['f'] == 'vote'){
		$args['meta_key'] = 'my_vote_count';
		$args['orderby'] = 'meta_value';
	}
}
//print_r($args); die;
if(isset($_GET['pag'])){
	$args['paged'] = $_GET['pag'];
}
$my_query = new WP_Query($args);
$count = $my_query->post_count;
?>
<style>
.popup{    width: 400px;
    margin: 0 auto;
    background: #69002f;
    padding: 10px 25px;
    border-radius: 8px;
    position: absolute;
    top: 5%;
    left: 0;
    right: 0;
    z-index: 9999999;display:none;}
	.gform_wrapper div.validation_error{    line-height: 20px;}
@media (min-width: 320px) and (max-width: 480px) {
  .popup{    width: 100%;}
  .filter{width: 105px !important;}
  
}
.gform_wrapper ul.gform_fields li.gfield {
     padding-right: 0 !important;
}
.gform_wrapper .top_label .gfield_label, .gform_wrapper legend.gfield_label {
    color: #fff !important;
}
body .gform_wrapper .top_label div.ginput_container, .gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]) {
    width: 100% !important;
}
.gform_wrapper .gform_footer input.button, .gform_wrapper .gform_footer input[type=submit]{    margin: 0 auto !important;
    display: block !important;    background: none !important;
    border: 1px #ccc solid;
    padding: 10px 20px;}
	.over-black{    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 999999;
    overflow: hidden;
    position: fixed;
    background: #0b0b0b63;
    transition: opacity .25s;
    display: none;}
	.filter {
    margin: 0 auto !important;
    display: block !important;
    padding: 15px 25px !important;
    border-radius: 25px;
    color: #000;
    width: 200px;
    border: 1px solid #ccc;
    text-align: center;
	    float: left;
}
.filter_div .pagination{    margin: 0 auto;
    display: block;
    text-align: center;
    position: relative;
    width: fit-content;
    top: 10px;}
	.gform_wrapper div.validation_error, .gform_wrapper .validation_message {
    color: #fff !important;
	}
	.gform_wrapper.gform_validation_error .gform_body ul li.gfield.gfield_error:not(.gf_left_half):not(.gf_right_half) {
     max-width: auto !important;
}
.gform_wrapper li.gfield.gfield_error{background-color: #69002f !important;border-top:0 !important;border-bottom:0 !important;}
.gform_wrapper div.validation_error{    margin-bottom: 0 !important;}
body .gform_wrapper ul li.gfield {
    margin-top: 0 !important;     padding-top: !important; }
}
</style>
<div class="over-black"></div>
<div class="popup">
<?php 
echo do_shortcode('[gravityform id=1 title=false description=false ajax=true tabindex=49]');
?>
</div>
<div class="main">

    <section class="main-content">
        <div class="container">
            <h2 class="title-heading">Idea <span>Testimonials from Beneficiaries of flagship Government schemes</span></h2>
            <?php
				if(is_user_logged_in()){
			?>
			<div class="wp-pagenavi">
				<div class="pagination">
					<a class="view_more" href="javascript:void(0);">Create new Idea</a>
				</div>
				
				
			</div>
			<div class="wp-pagenavi filter_div">
				<div class="pagination">
					<a class="filter" href="?f=new">Newest</a>
					<a class="filter" href="?f=old">Oldest</a>
					<a class="filter" href="?f=vote">Vote</a>
				</div>
				
				
			</div>
			<div style="clear: both;"></div>
			<br/>
			<br/>
				<?php } ?>
			<div class="voice-slider">
                <div class="row">
				<?php if( $my_query->have_posts() ) {
					$arr = [];
					while ($my_query->have_posts()) { $my_query->the_post();
					$id = get_the_ID();
					?>
					<div class="item-block">
						<div class="graphics-pic">
							<a href="<?php the_permalink(); ?>">
								<img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php the_title();?>">
							</a>
						</div>
						<div class="project-info">
							<h4><a href="<?php the_permalink(); ?>"><?php the_title();?></a>
							
							</h4>
							<p style="font-weight: bold;">
							<?php
								$arr_vote = get_post_meta(get_the_ID(),'vote');
								$result = array_unique($arr_vote);
								add_post_meta( get_the_ID(), 'my_vote_count', count($result), true );
								//add_post_meta( $post_id, 'my_vote_count', count($result), true );
								echo count($result).' vote';
								?>
							</p>
						</div>
					</div>
                    
					<?php
					}}
					?>
                </div>
                <div class="wp-pagenavi">
                    <div class="pagination">
						<?php
						if($my_query->max_num_pages > 1){
							$curent_page = $_GET['pag'] ? $_GET['pag']:1;
							$cat = $_GET['cat'] ? "&cat=".$_GET['cat']:"";
							$pagLink = "<div class='pagination'>";
							$pre = $curent_page - 1;
							$nex = $curent_page + 1;
							if($curent_page > 1){
								$pagLink .= "<a href='?pag=".$pre.$cat."'>« Previous</a>";  
							}
							for ($i=1; $i<=$my_query->max_num_pages; $i++) {  
										 $pagLink .= "<a href='?pag=".$i.$cat."'>".$i."</a>";  
							};  
							if($curent_page < $my_query->max_num_pages){
								$pagLink .= "<a href='?pag=".$nex.$cat."'>Next »</a>";  
							}
							echo $pagLink . "</div>"; 
						}
						?>
					</div>
                </div>
            </div>
        </div>
    </section>

</div>
</style>
<script>
jQuery('.view_more').on('click', function(e) {
	jQuery('.popup').show();
	jQuery('.over-black').show();
})
jQuery('.over-black').on('click', function(e) {
  if (e.target !== this){
		return;
	}
	jQuery('.popup').hide();
	jQuery('.over-black').hide();
})
</script>