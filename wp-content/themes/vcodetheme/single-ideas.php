<?php get_header(); ?>
<?php if (have_posts()) the_post(); ?>
<div class="banner">
	<img src="<?php the_post_thumbnail_url('full'); ?>" alt="">
	
</div>

<div class="main">
    <?php get_template_part('templates/parts/page-banner') ?>
    <section class="picture-media">
        <div class="container">
            <h2 class="title-heading"><?php the_title(); ?></h2>
            <div class="pic-gallery clearfix">
                <?php the_content(); ?>
				<div class="vote-div" post_id="<?php the_ID(); ?>" login="<?php if(is_user_logged_in()){ echo 1; } ?>">
				<div>
				<?php 
				$arr_vote = get_post_meta(get_the_ID(),'vote');
				$result = array_unique($arr_vote);
				$class = "like.png";
				if(is_user_logged_in()){
					$user_id = get_current_user_id();
					foreach($result as $u){
						if($user_id == $u){
							$class = "liked.png";
						}
					}
				}
				
				?>
				<img class="btn-vote" num="<?php echo count($result); ?>" post_id="<?php the_ID(); ?>" login="<?php if(is_user_logged_in()){ echo 1; } ?>" style="width: 40px;cursor: pointer;float: left; margin-right: 10px;" src="../../files/<?php echo $class; ?>" />
                <span style="line-height: 57px;display: block;"><span class="vote">
				<?php
				echo count($result);
				?>
				</span> Vote</span>
				</div>
				</div>
				<?php 
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

            </div>
        </div>
    </section>
</div>

<?php get_footer();
wp_footer(); ?>
