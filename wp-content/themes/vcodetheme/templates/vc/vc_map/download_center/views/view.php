<?php 
$args = array(
	'posts_per_page'   => -1,
	'post_type' => 'downloads',
);
if(isset($_GET['cat'])){
	$args['tax_query'] = array(
		array(
		'taxonomy' => 'download_cat',
		'field' => 'term_id',
		'terms' => $_GET['cat']
		 )
	);
}else{
	$args['tax_query'] = array(
		array(
		'taxonomy' => 'download_cat',
		'field' => 'term_id',
		'terms' => 17,
		'operator' => 'NOT IN'
		 )
	);
}
$my_query = new WP_Query($args);
$count = $my_query->post_count;
$q = get_terms( array(
	'taxonomy' => 'download_cat',
	'hide_empty' => false,
));
$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$uri_parts[0]}";
?>
<div class="main">
    <section class="picture-media">
        <div class="container">
            <h2 class="title-heading"> Download Center<span>Publications of the Government</span></h2>
            <div class="download-filter">
                <div class="filter-link">
					<?php $i = 1; foreach($q as $c){
						$v = (array) $c; ?>
                    <a class="" href="<?php echo $actual_link.'?cat='.$v['term_id']; ?>" title="<?php echo $v['name'] ?>"><?php echo $v['name'] ?></a>
                    <?php $i++; } ?>
                </div>
            </div>
            <div id="scrolltothis"></div>
            <div class="filter-count">Showing <?php echo $count; ?> results </div>
            <div class="massonary-wrapper clearfix">
				<?php if( $my_query->have_posts() ) {
					$arr = [];
					while ($my_query->have_posts()) { $my_query->the_post();
					$id = get_the_ID();
					$file = get_field("file",$id);
					$youtube = get_field("youtube",$id);
					if($file == false || $file == ""){
						$file = $youtube;
					}
					
					$image = get_the_post_thumbnail_url();
					if ($image) {
					if (!in_array($image, $arr)) {
    
					$arr[] = $image;
					$thumb = str_replace(get_home_url(),'..',$file);
    
				?>
                <div class="list-item">
                    <div class="graphics-pic">
                        <a <?php if($youtube != ""){ ?> data-fancybox="videos" <?php }else{ ?> data-fancybox="e-books" <?php } ?> href="<?php echo $file; ?>" target="_blank">
                            <img style="max-height: 173px;" src="<?php the_post_thumbnail_url('full'); ?>" alt="download">
                        </a>
                    </div>
					<?php if($youtube == ""){ ?>
                    <div class="share-this">
                        <a href="<?php echo $thumb; ?>" download title="Download"><i class="flaticon-arrows"></i></a>
                    </div>
					<?php } ?>
                </div>
				<?php
                }}}}
				?>
            </div>
        </div>
    </section>

</div>