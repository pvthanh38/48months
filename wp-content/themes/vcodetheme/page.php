<?php get_header(); ?>
<?php if (have_posts()) the_post(); ?>
<?php get_template_part('templates/parts/page-banner') ?>
<?php the_content(); ?>
<?php 
get_footer(); 
wp_footer();
?>
