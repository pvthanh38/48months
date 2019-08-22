<?php
global $post;
$post_id = $post->ID;
?>
<?php if ( get_field('show_banner', $post_id) ) : ?>
    <?php
    $banner = get_field('page_banner', $post_id);
    if ( !$banner ) {
        $banner = get_field('banner', 'option');
    }

    ?>
    <div class="banner">
        <img src="<?php echo $banner; ?>" alt="">
        <div class="banner-nav">
            <div class="container">
                <?php if ( get_field('show_menu_scroll', $post_id) ) {?>
                <nav class="theme-menu">
                    <ul class="fixed">
                        <li class="smooth-scroll"><a href="#blogs">Blogs </a></li>
                        <li class="smooth-scroll"><a href="#infographics">Infographics</a></li>
                        <li class="smooth-scroll"><a href="#voi">Voice of India</a></li>
                    </ul>
                </nav>
                <?php } ?>
            </div>
        </div>
    </div>

<?php endif; ?>