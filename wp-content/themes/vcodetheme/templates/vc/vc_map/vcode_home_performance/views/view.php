<!--Performance Dashboard Open-->
<style>
.filter_div{ margin-bottom: 100px;display: block;}
.filter{color: #fff !important;}
@media (min-width: 320px) and (max-width: 480px) {
  .filter{width: 155px !important;}
  
}
</style>
<section class="performance-block" id="performance-dashboard">
    <div class="container">
        <h2 class="title-heading"><?php echo $atts['title']; ?> <span><?php echo $atts['subtitle']; ?></span></h2>
		<div class="wp-pagenavi filter_div">
			<div class="pagination">
				<a class="filter" href="?f=new#performance-dashboard">Newest</a>
				<a class="filter" href="?f=old#performance-dashboard">Oldest</a>
				
			</div>
			
			
		</div>
        <div class="other-schemes">
            <div class="row">
                <?php
				$oderby = 'DESC';
				if(isset($_GET['f'])){
					if($_GET['f'] == 'new'){
						$oderby = 'DESC';
					}
					if($_GET['f'] == 'old'){
						$oderby = 'ASC';
					}
					
				}
                $type = 'performance_dashboar';
                $args=array(
                    'posts_per_page'   => -1,
					'order' => $oderby,
                    'post_type'        => $type,
                );
                $my_query = null; $my_query = new WP_Query($args);
                if( $my_query->have_posts() ) {
                while ($my_query->have_posts()) : $my_query->the_post();
                $number = get_field('number', get_the_ID());
                $date_start = get_field('date_start', get_the_ID());
                ?>
                <div class="list-block">
                    <div class="scheme-block">
                        <a href="javascript:void(0);" style="cursor: default;">
                            <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="scheme">

                            <div class="title-text"> <?php the_title(); ?> </div>
                        </a>
                        <a href="javascript:void(0);" class="detail" style="cursor: default;">
                            <div class="detail-inner">
                                <div class="scm-text"> <?php the_title(); ?></div>
                                <div class="scm-text">
                                    <span></span><?php echo $number; ?><span></span>
                                </div>
                                <div class="scm-text"><span><?php echo $date_start; ?></span></div>
                            </div>
                        </a>
                        <p><span> </span><span class="Count"><?php echo $number; ?></span><span> </span></p>
                    </div>
                </div>
                <?php
                endwhile;
                }
                ?>
            </div>
        </div>
    </div></section>

<!--Performance Dashboard Closed--> 