jQuery('#wp-admin-bar-my-account').hide();
jQuery(document).ready(function($) {
    jQuery('#wp-admin-bar-my-account').show();
    var $container = $('.eat-admin-main-wrapper');

    // http://stackoverflow.com/questions/9374894/can-codemirror-find-textareas-by-class
    function codeMirrorDisplay() {
        var $codeMirrorEditors = $('.eat-textarea-code-texts');
        $codeMirrorEditors.each(function(i, el) {
            var $active_element = $(el);
            if ($active_element.data('cm')) {
                $active_element.data('cm').doc.cm.toTextArea();
            }
            var codeMirrorEditor = CodeMirror.fromTextArea(el, {
                lineNumbers: true,
                lineWrapping: true,
                theme: 'eclipse'
            });
            $active_element.data('cm', codeMirrorEditor);
        });
    }

    $container.on('click', '.eat-tab', function(){
        var $this = $(this),
        id        = $this.attr('id');
        $('.eat-tab').removeClass('eat-active');
        $this.addClass('eat-active');
        $('.eat-tab-content').removeClass('eat-tab-content-active').hide();
        $('.'+id).addClass('eat-tab-content-active').show();
        codeMirrorDisplay();
    });

    $container.on('change', '.eat-general-template-selection', function(){
        var $this   = $(this);
        var selected_img = $(this).find('option:selected').data('img');
        $this.closest('.eat-options-wrap').find('.eat-img-selector-media img').attr('src', selected_img);
    });

    $container.on('change', '.eat-background-selector', function(){
        var $this  = $(this);
        var val    = $this.val();
        var parent = $this.closest('.eat-options-wrap-outer').find('.eat-background-select-content');
        if( val =='' ){
            parent.find('.eat-common-content-wrap').hide();
            parent.find('.eat-common-content-wrap-all').hide();
        }else if( val == 'background-color' ){
            parent.find('.eat-common-content-wrap').hide();
            parent.find('.eat-common-content-wrap-all').hide();
            parent.find('.eat-'+val).show();
        }else{
            parent.find('.eat-common-content-wrap').hide();
            parent.find('.eat-common-content-wrap-all').show();
            parent.find('.eat-'+val).show();
        }
    });

    $container.on( 'change', '.eat-select-options', function(){
        var $this  = $(this);
        var val    = $this.val();
        var parent = $this.closest('.eat-select-options-wrap').find('.eat-select-content-wrap');
        if( val =='' ){
            parent.find('.eat-common-content-wrap').hide();
        }else{
            parent.find('.eat-common-content-wrap').hide();
            parent.find('.eat-'+val).show();
            parent.find('.eat-background').show();
        }
    });

    $container.find( '.eat-color-picker').wpColorPicker();

    $container.on( 'click', '.eat-image-upload-button', function (e) {
        e.preventDefault();
        var $this = $(this);
        var image = wp.media({
            title: 'Upload Image',
            multiple: false
        }).open()
                .on('select', function (e) {
                    var uploaded_image = image.state().get('selection').first();
                    var el_img_url     = uploaded_image.toJSON().url;
                    $this.closest('.eat-image-selection-wrap').find('.eat-image-upload-url').val(el_img_url);
                    $this.closest('.eat-image-selection-wrap').find('.eat-image-preview img').attr('src', el_img_url);
                });
    });

    $container.on('click', '.eat-footer-info-custom-texts', function(){
        if ($(this).is(':checked')) {
            $(this).closest('.eat-custom-texts-options-wrap').find(".eat-custom-texts-content-wrap").fadeIn();
        }else{
            $(this).closest('.eat-custom-texts-options-wrap').find(".eat-custom-texts-content-wrap").fadeOut();
        }
    });

    $container.on( 'change', '.eat-options-select-wrap', function(){
        var $this  = $(this);
        var parent = $this.closest('.eat-select-wrap').find('.eat-options-select-content-wrap');
        var val    = $this.val();
        parent.find('.eat-common-content-wrap').removeClass('eat-active').hide();
        parent.find('.eat-'+val+'-content-wrap').addClass('eat-active').show();
    });

    $('.eat-tabs-header').theiaStickySidebar({
      // Settings
      additionalMarginTop: 30
    });

    //for dropdown selection
    $('.eat-selectbox-wrap').selectbox();
});