<?php
    $sliders = vc_param_group_parse_atts($atts['sliders']);
	//echo 1; die;
?>
<!--HOME SLIDER-->
<div class="home-banner owl-carousel" >
    <?php
        foreach ($sliders as $item){
            $slider_imgae = wp_get_attachment_image_src($item['slider_imgae'],'full');
            $slogan_image = wp_get_attachment_image_src($item['slogan_image'],'full');
            $alt_slider_image = $item['alt_slider_image'];
            $alt_slogan_image = $item['alt_slogan_image'];
        ?>
        <div class="item">
            <div class="slogan-text">
				<img src="<?php echo $slogan_image[0]; ?>" alt="<?php echo $alt_slogan_image; ?>" title="<?php echo $alt_slogan_image; ?>">
            </div>
            <a href="#">
				<img src="<?php echo $slider_imgae[0]; ?>"  alt="<?php echo $alt_slider_image; ?>" title="<?php echo $alt_slider_image; ?>">
				</a>
        </div>
        <?php
        }
    ?>
</div>
<?php if(is_user_logged_in()){ ?>
<style>
</style>
<?php } ?>
<!--END HOME SLIDER-->