<?php get_header(); ?>
<?php if (have_posts()) the_post(); ?>
<div class="main">
    <?php get_template_part('templates/parts/page-banner') ?>
    <section class="picture-media">
        <div class="container">
            <h2 class="title-heading"><?php the_title(); ?></h2>
            <div class="pic-gallery clearfix">
                <?php the_content(); ?>
                <div class="share-this">
                    <a href="#" data-href="<?php echo $actual_link ?>" data-title="Here's an interesting infographic on 48 Months of Transforming India: <?php the_title();?>" onclick="return fb_click(this)" title="Facebook"><i class="flaticon-facebook-logo-button"></i></a>
                    <a href="#" data-href="<?php echo $actual_link ?>" data-title="Here's an interesting infographic on 48 Months of Transforming India: <?php the_title();?>" onclick="return tweet_click(this)" title="Twitter"><i class="flaticon-twitter-logo-button"></i></a>
                    <a class="onlyMobile" href="whatsapp://send?text=Here's an interesting infographic on 48 Months of Transforming India: <?php the_title();?> - https://48months.mygov.in/wp-content/uploads/2018/05/1000000000143356587.png" title="Share with Whatsapp"><i class="flaticon-whatsapp"></i></a>
                </div>

            </div>
        </div>
    </section>
</div>
<?php get_footer(); ?>
