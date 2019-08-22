<?php
if ( !isset($atts['cat']) || $atts['cat'] == 0 )  return;
$title = isset( $atts['title'] ) ? $atts['title'] : get_cat_name($atts['cat']);
$args = array(
    'post_type' => 'graphic',
    'posts_per_page' => 8
);
if ( isset($atts['cat']) && $atts['cat'] != 0 ) {
	$args['tax_query'] = array(
		array(
		'taxonomy' => 'graphic_cat',
		'field' => 'term_id',
		'terms' => $atts['cat']
		 )
	);
}
$q = new WP_Query($args);
$count = 1;
?>
<section class="picture-media" id="infographics">
    <div class="container">
        <h2 class="title-heading"><?php echo $atts['title']; ?> <span><?php echo $atts['subtitle']; ?></span></h2>
        <div class="pic-gallery clearfix">
            <?php if ( $q->have_posts() ) : ?>
            <div class="row">
                <?php while ( $q->have_posts() ) : $q->the_post();
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
                <?php $count++; endwhile; wp_reset_query(); ?>

            </div>
            <?php endif; ?>
        </div>
        <?php if($count >= 8 ){?>
        <div class="center-text view-more">
            <?php $button = vc_build_link($atts['button']); ?>
            <a href="<?php echo $button['url']; ?>" class="more-btn"><?php echo $button['title']; ?></a>
        </div>
        <?php } ?>
    </div>
</section>