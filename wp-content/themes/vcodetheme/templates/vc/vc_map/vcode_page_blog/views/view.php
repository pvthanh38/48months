<?php
if ( !isset($atts['cat']) || $atts['cat'] == 0 )  return;
$title = isset( $atts['title'] ) ? $atts['title'] : get_cat_name($atts['cat']);
$args = array(
    'post_type' => 'post',
    'posts_per_page' => 8,
    'cat' => $atts['cat']
);
if ( isset($atts['cat']) && $atts['cat'] != 0 ) {
    $args['cat'] = $atts['cat'];
}
$q = new WP_Query($args);
$count = 1;
?>
<section class="picture-media" id="blogs">
    <div class="container">
        <h2 class="title-heading"><?php echo $atts['title']; ?> <span><?php echo $atts['subtitle']; ?></span></h2>
        <div class="pic-gallery clearfix">
            <?php if ( $q->have_posts() ) : ?>
            <div class="row">
                <?php while ( $q->have_posts() ) : $q->the_post(); ?>
                <div class="item-block">
                    <div class="graphics-pic">
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>">
                        </a>
                    </div>
                    <div class="project-info">
                        <h4><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h4>
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