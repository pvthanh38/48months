<!--Infographics Open-->
<?php
$graphics_cat = get_terms( array(
    'taxonomy' => 'graphic_cat',
    'hide_empty' => true,
));
$button = vc_build_link($atts['button']);
?>
<section class="picture-media" id="info-graphics">
    <div class="container">
        <h2 class="title-heading">Infographics <span>Information and Facts You Can Share</span></h2>
        <div class="pic-gallery clearfix">
            <div class="row">
                <?php
                    foreach ($graphics_cat as $cat) {
                        $id = $cat->term_id;
                        $name = $cat->name;
                        $slug = $cat->slug;
                        $image = get_field('graphics_cat_image', 'graphic_cat_' . $id);
                        ?>
                        <div class="item-block">
                            <div class="graphics-pic">
                                <a href="<?php echo get_site_url(); ?>/<?php echo $slug; ?>/#infographics"
                                   title="<?php echo $name; ?>">
                                    <img src="<?php echo $image; ?>"
                                         alt="<?php echo $name; ?>">
                                </a>
                            </div>
                            <div class="project-info">
                                <h4>
                                    <a href="<?php echo get_site_url(); ?>/<?php echo $slug; ?>/#infographics">
                                        <?php echo $name; ?></a></h4>
                            </div>
                        </div>
                        <?php
                    }
                ?>

            </div>
        </div>
        <div class="center-text view-more">
            <a href="<?php echo $button['url']; ?>" class="more-btn"><?php echo $button['title']; ?></a>
        </div>
    </div>
</section>

<!--Infographics Closed-->