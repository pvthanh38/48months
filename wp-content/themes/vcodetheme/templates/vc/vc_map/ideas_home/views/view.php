<?php 
$number_item = 20;
if(isset($atts['number_item'])){$number_item = $atts['number_item'];}
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
	'posts_per_page'   => $number_item,
	'order' => $oderby,
	'post_type' => 'ideas',
);

if(isset($_GET['pag'])){
	$args['paged'] = $_GET['pag'];
}
$my_query = new WP_Query($args);
$count = $my_query->post_count;
?>
<style>
.filter_div{ margin-bottom: 30px;display: block;}
</style>
<div class="main">

    <section class="main-content">
        <div class="container">
            <h2 class="title-heading">Idea <span>Testimonials from Beneficiaries of flagship Government schemes</span></h2>
            <div class="wp-pagenavi filter_div">
				<div class="pagination">

					<div class="wpdiscuz-sort-buttons" style="font-size:14px; color: #777;">
						<i class="fas fa-caret-up" aria-hidden="true"></i>
						<a class="wpdiscuz-sort-button wpdiscuz-vote-sort-up" href="?f=new">Newest</a> 
						<i class="fas fa-caret-up" aria-hidden="true"></i>
						<a class="wpdiscuz-sort-button wpdiscuz-vote-sort-up" href="?f=old">Oldest</a>
						<i class="fas fa-caret-up" aria-hidden="true"></i> 
						<a class="wpdiscuz-sort-button wpdiscuz-vote-sort-up" href="?f=vote">Vote</a>
					</div>
				</div>
				
				
			</div>
			<div style="clear: both;"></div>
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
						<a class="view_more" href="idea/">View more</a>
					</div>
                </div>
            </div>
        </div>
    </section>

</div>