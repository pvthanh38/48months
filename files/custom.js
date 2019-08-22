jQuery(document).ready(function($) {
    headerMenu();
    $(window).resize(function() {
        headerMenu();
    })

    function headerMenu() {
        if ($(window).width() > 1023) {
            var bannerH = $(window).height();
            $('.home-banner').css('height', bannerH);
            //var offsetNav = $(".navigation").offset().top ;
            $(window).scroll(function() {
                var lh = $('.header').height();
                // console.log(lh)
                var scrollVal = $(window).scrollTop();
                if (scrollVal > bannerH) {
                    $('.header').addClass('fixed');
                } else {
                    $('.header').removeClass('fixed');

                }
            })
        }
    }
    $('.imenu').click(function(e) {
        $('.menu').slideToggle();
		$(this).toggleClass('explored');
		e.stopPropagation();
    })
	$(document).click(function (e) {
    if (!$(e.target).hasClass("imenu") && $(e.target).parents(".menu").length === 0) 
    {
        $(".menu").hide();
		$('.imenu').removeClass('explored');
		
    }
   });
	


    var cmslider = $("#wcarousel").waterwheelCarousel({
        flankingItems: 3,
        autoPlay: 3000,
        movedToCenter: function($item) {
            $('#voice-slider-title').html($item.attr('title'));
			$('#theme-title').html($item.attr('data-title'));
        },
        clickedCenter: function() {
            $('.fancybox').fancybox();
        }
    })
    $('#voice-slider-title').html($("#wcarousel a:first img").attr('title'));
	$('#theme-title').html($("#wcarousel a:first img").attr('title'));
	$('#theme-title').html($("#wcarousel a:first img").attr('data-title'));
    $('#prev').bind('click', function() {
        cmslider.prev();
        return false
    });

    $('#next').bind('click', function() {
        cmslider.next();
        return false;
    });
    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 0,
        nav: false,
        dots: true,
        items: 1,
        loop: true,
        autoplay: true,
        autoplayHoverPause: true,
        video: true,
		autoplayTimeout:3000
    })


    $(window).resize(function() {
        if ($(window).width() > 767) {
            stricky();
        }

        newOptions = {
            flankingItems: 3,
            autoPlay: 3000,
            movedToCenter: function($item) {
                $('#voice-slider-title').html($item.attr('title'));
				$('#theme-title').html($item.attr('data-title'));
            },
            clickedCenter: function() {
                $('.fancybox').fancybox();
            }
        };
        cmslider.reload(newOptions);

    })

    function stricky() {
        var fH = $('.footer').height();
        // $('body').css('margin-bottom',fH);
        //alert('welcome');	 
    }
    $('.show-themes > a').click(function(e) {
        $('.theme-option').slideToggle();
		e.stopPropagation();
    })
	
	$(document).click(function (e) {
    if (!$(e.target).is(".show-themes a,.theme-option") && $(e.target).parents(".theme-option").length === 0) 
       {
        $(".theme-option").fadeOut();
	   }
   });
	
	$('.close-theme').click(function() {
        $('.theme-option').slideUp();
    })

    $(window).scroll(function() {
        var bannerH = $('.banner').height();
        var lh = $('.header').height();
        // console.log(lh)
        var scrollVal = $(window).scrollTop();
        if (scrollVal > bannerH) {
            $('.banner-nav ul').addClass('fixed');
			$('body').addClass('float-menu');
        } else {
            $('.banner-nav ul').removeClass('fixed');
            $('body').removeClass('float-menu');
        }
    })
    $('.floating-menu').click(function() {
        $('.theme-menu ul').slideToggle();
		
    })

    jQuery('.smooth-scroll a').click(function(event) {
        var target = this.hash;
        var pos1 = jQuery(target).offset().top;
		var hH = $('.header').height();
        jQuery('html, body').animate({
            scrollTop: pos1 - hH
        });
		jQuery('.smooth-scroll a').removeClass('active');
		jQuery(this).addClass('active');
		if($(window).width()<767){
			$('.menu').hide();
			$('.imenu').removeClass('explored');			
		 }
    });

    jQuery('.quiz a,#menu-item-96 a').click(function() {
        $('.pop_overlay').fadeIn('fast');
        $('.quiz-model').slideDown();
    })
	$(document).keydown(function(event) { 
	  if (event.keyCode == 27) { 
		$('.quiz-model,.pop_overlay').hide();
	  }
	});

    jQuery('.model-close,.pop_overlay').click(function() {
        $('.quiz-model').slideUp();
        $('.pop_overlay').fadeOut();
    })

    $(window).load(function() {

        $(".filters .btn").click(function(e) {
            e.preventDefault();

            var filter = $(this).attr("data-filter");
            $(".filters .btn").removeClass('active');
            $(this).addClass('active');
            $container.masonryFilter({
                filter: function() {
                    if (!filter) return true;
                    return $(this).attr("data-filter") == filter;
                }
            });
        });

    });

    if($('.wp-pagenavi').length > 0){

        $('.wp-pagenavi a').each(function () {

            var href = $(this).attr('href');
            $(this).attr('href',href);

        })

    }

    if(window.location.hash){

        var hash = window.location.hash;

        var offset = $(hash).offset().top;

        var headerHeight = $('.header').height();

         var scrollTop = offset - headerHeight;
        //var scrollTop = offset;

        if($('body.tax-themes').length == 0) {

            if ($('.title-heading').length > 0) {

                scrollTop -= $('.title-heading').height() + 60;
            }

            if ($('.download-filter').length > 0) {

                scrollTop -= $('.download-filter').height()
            }

           

        }

        $('html,body').animate({
                scrollTop: scrollTop,
            });


    }

    /* Fancybox Customization*/

    $.fancybox.defaults.btnTpl.zoom = '<button data-fancybox-zoom class="fancybox-button fancybox-button--zoom" title="Zoom">' +
        '<i class="flaticon-zoom-in"></i></button>';

    $.fancybox.defaults.btnTpl.fb = '<button data-fancybox-fb class="fancybox-button fancybox-button--fb" title="Facebook">' +
        '<i class="flaticon-facebook-logo-in-circular-button-outlined-social-symbol"></i></button>';

    $.fancybox.defaults.btnTpl.tw = '<button data-fancybox-tw class="fancybox-button fancybox-button--tw" title="Twitter">'+
        '<i class="flaticon-twitter-circular-button"></i></button>';

    $.fancybox.defaults.btnTpl.wapp = '<button data-fancybox-wapp class="onlyMobile fancybox-button fancybox-button--tw" title="Whatsapp">'+
        '<i class="flaticon-whatsapp-logo"></i></button>';

    // Make it clickable using event delegation
    $('body').on('click', '[data-fancybox-zoom]', function() {
        var f = $.fancybox.getInstance();

        if ( f ) {
            f[ f.isScaledDown() ? 'scaleToActual' : 'scaleToFit' ]();
        }
    });

    $('body').on('click', '[data-fancybox-fb]', function(event) {

        event.preventDefault();
        var href = $.fancybox.getInstance().current.opts.$orig.attr('data-href');
        var title = $.fancybox.getInstance().current.opts.$orig.attr('data-fb-title');
        var target = "https://www.facebook.com/sharer/sharer.php?u="+encodeURIComponent(href)+"&t="+encodeURIComponent(title);
        window.open(target, '','left=0,top=0,width=600,height=300,menubar=no,toolbar=no,resizable=yes,scrollbars=yes');
    });

    $('body').on('click', '[data-fancybox-tw]', function(event) {

        event.preventDefault();
        var href = $.fancybox.getInstance().current.opts.$orig.attr('data-href');
        var title = $.fancybox.getInstance().current.opts.$orig.attr('data-twt-title');
        var target = 'https://twitter.com/share?url=' + encodeURIComponent(href)+'&text='+encodeURIComponent(title)+'&hashtags=48MonthsofTransformingIndia,SaafNiyatSahiVikas';
        window.open(target, '', 'left=0,top=0,width=600,height=300,menubar=no,toolbar=no,resizable=yes,scrollbars=yes');

    });

    $('body').on('click', '[data-fancybox-wapp]', function(event) {

        event.preventDefault();
        var href = $.fancybox.getInstance().current.opts.$orig.attr('data-wapp-href');
        var title = $.fancybox.getInstance().current.opts.$orig.attr('data-wapp-title');
        var target = "whatsapp://send?text="+encodeURIComponent(title) + " - " + encodeURIComponent(href);
        //window.open(target, '', 'left=0,top=0,width=600,height=300,menubar=no,toolbar=no,resizable=yes,scrollbars=yes');
        window.location.href = target;

    });
    
    $( '[data-fancybox*="images"]' ).fancybox({
        buttons : [
            'slideShow',
            'fullScreen',
            'thumbs',
            'zoom',
            'fb',
            'tw',
            'wapp',
            'close'
        ]
    });

    //$('.fancybox').fancybox();
	
	$('.Count').counterUp({
            delay: 13,
            time: 1700
    });

    if($('.theme-menu').length == 0){
        $('.floating-menu').hide();
    }

    $("form#subscribe-us").on('submit',function (event) {

        event.preventDefault();

        var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var email_field = $(this).find('input');
        var email = email_field.val();
        var do_submit = true;

        if(email == ''){

            alert('Please enter your valid Email Id to get updates in your inbox');
            do_submit = false;

        }else if(!regex.test(email)){

            alert('Please enter a valid email id');
            do_submit = false;
        }

        if(do_submit){

            $.ajax({
                url: Ajax48Months.ajax_url,
                data: {'email':email,'action': 'subscribe_user'},
                method: 'post',
                dataType:'JSON',
                success : function (result) {

                    if(result.status == 1){
                        alert('You have successfully subscribed');
                    }else if(result.status == 2){
                        alert('You have already subscribed');
                    }else if(result.status == 3){
                        alert('Subscription failed! Maximum limit reached');
                    }else {
                        alert('Subscription failed! Please try after some time');
                    }
                    email_field.val('');
                }

            })

        }


    })
});

function changeClass(x) {
    x.classList.toggle("close-menu");
}
