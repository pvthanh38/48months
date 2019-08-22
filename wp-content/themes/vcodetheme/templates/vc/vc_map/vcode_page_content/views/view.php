<?php
    $title = $atts['title'];
    $link_video = $atts['link_video'];
    $arr_link = explode('=', $link_video);
    ?>
<div class="theme-info">
    <div class="container">
        <h1><?php echo $title; ?></h1>
        <p><?php echo $content; ?></p>
    </div>
</div>
<?php if($link_video){?>
<section class="theme-video">
    <div class="container">
        <div class="video-wrapper">
            <iframe width="600" height="350" src="https://www.youtube.com/embed/<?php echo $arr_link[1]; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
</section>
<?php }?>