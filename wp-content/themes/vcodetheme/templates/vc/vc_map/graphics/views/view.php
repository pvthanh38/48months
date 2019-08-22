<?php 
$q = get_terms( array(
	'taxonomy' => 'graphic_cat',
	'hide_empty' => false,
));
$l = get_terms( array(
	'taxonomy' => 'lang_graphics',
	'hide_empty' => false,
));
$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$uri_parts[0]}";
$args = array(
	'posts_per_page'   => 20,
	'post_type' => 'graphic',
);
if(isset($_GET['cat'])){
	$args['tax_query'] = array(
		array(
		'taxonomy' => 'graphic_cat',
		'field' => 'term_id',
		'terms' => $_GET['cat']
		 )
	);
	//print_r($args['tax_query']); die;
}
if(isset($_GET['lan'])){
	$args['tax_query'][] = 
		array(
		'taxonomy' => 'lang_graphics',
		'field' => 'term_id',
		'terms' => $_GET['lan']
	);
	//print_r($args['tax_query']); die;
}
if(isset($_GET['pag'])){
	$args['paged'] = $_GET['pag'];
}
$my_query = new WP_Query($args);
$count = $my_query->post_count;
$cat = $_GET['cat'] ? "&cat=".$_GET['cat']:"";
$lan = $_GET['lan'] ? "&lan=".$_GET['lan']:"";
?>
<div class="main">
    <section class="picture-media">
        <div class="container">
            <h2 class="title-heading">Infographics <span>Information and Facts You Can Share</span></h2>
            <div class="download-filter">

                <div class="language-filter">
                    <a <?php echo $_GET['lan'] ?  '': 'class="active"'; ?> href="?<?php echo $cat; ?>">All</a>
					<?php foreach($l as $c){
						$v = (array) $c; ?>
						<a href="?lan=<?php echo $v['term_id'].$cat; ?>" <?php echo $_GET['lan'] == $v['term_id'] ? "class='active'":""; ?>><?php echo $v['name'] ?></a>
					<?php } ?>
                    
                </div>

                <div class="search-box">
                    <div class="filter-select">
                        <select name="ministry_filter" onchange="javascript:location.href = this.value;">
                            <option value="?">All Focus Areas</option>
						<?php foreach($q as $c){
								$v = (array) $c; ?>
                            <option value="?cat=<?php echo $v['term_id'].$lan ?>" <?php echo $_GET['cat'] == $v['term_id'] ? "selected":""; ?>><?php echo $v['name'] ?></option>
                        <?php } ?>
                        </select>
                    </div>

                </div>
            </div>
            <div id="scrolltothis"></div>
            <div class="pic-gallery clearfix">
                <div class="row">
					<?php if( $my_query->have_posts() ) {
					$arr = [];
					while ($my_query->have_posts()) { $my_query->the_post();
					$id = get_the_ID();
					$voice_url = get_field("voice_url",$id);
					$img = get_the_post_thumbnail_url();
					$thumb = str_replace(get_home_url(),'..',$img);
					?>
                    <div class="item-block">
                        <div class="graphics-pic">
                            <a data-fancybox="images" title="<?php the_title();?>" href="<?php echo $thumb; ?>"  >
                                <img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php the_title();?>">
                            </a>
                        </div>
                        <div class="project-info">
                            <h4><?php the_title();?></h4>
                            <div class="share-this">
                                <a href="#" data-href="<?php echo $actual_link ?>" data-title="Here's an interesting infographic on 48 Months of Transforming India: <?php the_title();?>" onclick="return fb_click(this)" title="Facebook"><i class="flaticon-facebook-logo-button"></i></a>
                                <a href="#" data-href="<?php echo $actual_link ?>" data-title="Here's an interesting infographic on 48 Months of Transforming India: <?php the_title();?>" onclick="return tweet_click(this)" title="Twitter"><i class="flaticon-twitter-logo-button"></i></a>
                                <a class="onlyMobile" href="whatsapp://send?text=Here's an interesting infographic on 48 Months of Transforming India: <?php the_title();?> - https://48months.mygov.in/wp-content/uploads/2018/05/1000000000143356587.png" title="Share with Whatsapp"><i class="flaticon-whatsapp"></i></a>
                                <a href="<?php echo $thumb; ?>" download="" title="Download"><i class="flaticon-arrows"></i></a>
                            </div>
                        </div>
                    </div>
					<?php
					}}
					?>
                    <div class="wp-pagenavi">
                        <div class="pagination">
							<?php
							if($my_query->max_num_pages > 1){
								$curent_page = $_GET['pag'] ? $_GET['pag']:1;
								
								$pagLink = "<div class='pagination'>";
								$pre = $curent_page - 1;
								$nex = $curent_page + 1;
								if($curent_page > 1){
									$pagLink .= "<a href='?pag=".$pre.$cat.$lan."'>« Previous</a>";  
								}
								for ($i=1; $i<=$my_query->max_num_pages; $i++) { 
								
											 $pagLink .= "<a href='?pag=".$i.$cat.$lan."'>".$i."</a>";  
								};  
								if($curent_page < $my_query->max_num_pages){
									$pagLink .= "<a href='?pag=".$nex.$cat.$lan."'>Next »</a>";  
								}
								echo $pagLink . "</div>"; 
							}
							?>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>