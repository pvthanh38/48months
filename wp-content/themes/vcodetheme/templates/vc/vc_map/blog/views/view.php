<?php 
$q = get_terms( array(
	'taxonomy' => 'category',
	'hide_empty' => false,
));
$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$uri_parts[0]}";
$args = array(
	'posts_per_page'   => 20,
	'post_type' => 'post',
);
if(isset($_GET['cat'])){
	$args['tax_query'] = array(
		array(
		'taxonomy' => 'category',
		'field' => 'term_id',
		'terms' => $_GET['cat']
		 )
	);
}
if(isset($_GET['pag'])){
	$args['paged'] = $_GET['pag'];
}
$my_query = new WP_Query($args);
$count = $my_query->post_count;
?>
<div class="main">

    <section class="main-content">
        <div class="container">
            <h2 class="title-heading">Blogs <span>Union Ministers write about the progress in 48 months</span></h2>
            <div class="download-filter">
                <div class="search-box">
                    <div class="filter-select">
                        <select name="ministry_filter" onchange="javascript:location.href = this.value;">
                            <option value="?">All Focus Areas</option>
                            <?php foreach($q as $c){
								$v = (array) $c; ?>
                            <option value="?cat=<?php echo $v['term_id'] ?>" <?php echo isset($_GET['cat']) && $_GET['cat'] == $v['term_id'] ? "selected":""; ?>><?php echo $v['name'] ?></option>
                        <?php } ?>
						</select>
                    </div>

                </div>
            </div>

            <div class="pic-gallery clearfix">
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
							<h4><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h4>
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