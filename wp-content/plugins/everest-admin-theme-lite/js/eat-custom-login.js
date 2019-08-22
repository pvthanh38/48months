jQuery(document).ready(function($) {

	$('#login').addClass('message-login-wrap');

	if(eat_custom_login_plugin_settings.login_template == 'template-1'){
		$("#rememberme").detach().insertBefore(".forgetmenot>label");
	}

	if(eat_custom_login_plugin_settings.login_template == 'template-2'){
		$('#loginform,#registerform,#lostpasswordform').find('label').each(function( indexInArray , value){
			$(this).find('br').remove();
			if ( $(this).parent().is( "p" ) ) {
				$(this).unwrap();
			}

			if($(this).attr('for') == 'user_login'){
				/*Adding eat custom class*/
				$(value).contents().eq(0).wrap('<span class="eat-login-label"/>');
				$(this).addClass('eat-login-field');

				var login_label = $(this).text();
				$('.eat-login-label').remove();
				$(this).find('#user_login').attr('placeholder',$.trim(login_label));
			}

			if($(this).attr('for') == 'user_pass'){
				$(value).contents().eq(0).wrap('<span class="eat-login-label"/>');
				$(this).addClass('eat-password-field');

				var password_label = $(this).text();
				$('.eat-login-label').remove();
				$(this).find('#user_pass').attr('placeholder',$.trim(password_label));
			}

			if($(this).attr('for') == 'user_email'){
				$(value).contents().eq(0).wrap('<span class="eat-login-label"/>');
				$(this).addClass('eat-user-email');

				var email_label = $(this).text();
				$('.eat-login-label').remove();
				$(this).find('#user_email').attr('placeholder',$.trim(email_label));
			}
		});

		$(".eat-password-field").next('label').wrap('<div class="remem-field">');
		$("#rememberme").prependTo(".remem-field");
		$('#nav').html($('#nav').html().split("|").join(""));
	}
});