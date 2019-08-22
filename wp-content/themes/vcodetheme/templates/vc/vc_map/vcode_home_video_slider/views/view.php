<!--Voice of India Open-->
<?php
$args = array(
	'posts_per_page'   => 7,
	'post_type' => 'voice',
);
if(isset($atts['cat_voice'])){
	//print_r($atts['cat_voice']); die;
	$args['tax_query'] = array(
		array(
		'taxonomy' => 'voice_cat',
		'field' => 'term_id',
		'terms' => $atts['cat_voice']
		 )
	);
}
$my_query = new WP_Query($args);
$count = $my_query->post_count;
?>
<section class="voice-india" id="voi">
    <div class="container">
        <h2 class="title-heading">Voice of India <span>Testimonials from Beneficiaries of flagship Government schemes</span></h2>
        <div class="voice-slider">

            <div id="wcarousel">
				<?php if( $my_query->have_posts() ) {
					$arr = [];
					while ($my_query->have_posts()) { $my_query->the_post();
					$id = get_the_ID();
					$voice_url = get_field("voice_url",$id);
					parse_str( parse_url( $voice_url, PHP_URL_QUERY ), $ids );
					$image = 'https://img.youtube.com/vi/'.$ids['v'].'/hqdefault.jpg';
					?>
                <!--<a class="fancybox" data-fancybox href=""><img title="" src="https://img.youtube.com/vi//hqdefault.jpg" alt="No Image"></a>-->
                <a class="fancybox" data-fancybox href="<?php echo $voice_url; ?>">
                    <img title="<?php the_title();?>" data-title="" src="<?php echo $image; ?>" alt="voice of india" style="" class=""></a><!--maxresdefault.jpg-->
				<?php }} ?>
            </div>
            <div id="theme-title"><a href="https://www.mygov.in/48months/themes/the-world-sees-a-new-india/index.html#voice-of-india">Women Led Development</a></div>
            <div id="voice-slider-title">Cooking becomes easy, takes less time – Kamini of Madhya Pradesh on Ujjwala Yojana</div>
            <a href="javascript:void(0)" id="prev"><i class="flaticon-left-arrow"></i></a> <a href="javascript:void(0)" id="next"><i class="flaticon-right-arrow"></i></a>
        </div>

        <div class="center-text">
            <a href="voice-of-india" class="more-btn">More Videos</a>
        </div>
    </div>
</section>

<!--Voice of India Closed-->