console.log('hssss');
jQuery.noConflict();
var g_email = "";
var g_name = "";
console.log('hssss');

jQuery( document ).ready( function($) {
	console.log('hssss');
});
/*
function checkLoginState() {
	FB.getLoginStatus(function(response) {
		//console.log(response);
	});
	FB.login(function(response) {
		if (response.authResponse) {
			//console.log('Welcome!  Fetching your information.... ');
			FB.api('/me/?fields=email,name', function(response) {
				console.log(response);
				jQuery.ajax({
					url: ajax_object.ajax_url,
					type: 'post',
					data: 'email='+response.email+ '&name='+response.name+'&action=add_user&query_vars='+ajax_object.query_vars,
					success: function( re ) {
						if (re != 500) {	
							window.location.href = re;
						}else{
							console.log("Password error");
						}
					}
				})
			});
		} else {
			console.log('User cancelled login or did not fully authorize.');
		}
	}, {scope: 'public_profile,email'}); 
}

function onSignIn(googleUser) {
	// Useful data for your client-side scripts:
	var profile = googleUser.getBasicProfile();
	g_email = profile.getEmail();
	g_name = profile.getName();	
};

jQuery.noConflict();
jQuery( document ).ready( function($) {
	$(".btn-rollback").on('click', function() {
		var box = $(this);
		var file = box.attr('value');
		swal({
			title: "Are you sure?",
			text: "",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			$(".fakeloader").show();
			if (willDelete) {
				$.ajax({
					url: ajax_object.ajax_url,
					type: 'post',
					data: 'file='+file+'&action=ajax_rollback&query_vars='+ajax_object.query_vars,
					success: function( data ) {
						$(".fakeloader").hide();
						if(data == "success"){
							swal("success", {
							  icon: "success",
							});
						}else{
							swal("error", "error");
						}
						
					}
				})
				
			} else {
				$(".fakeloader").hide();
				//swal("Your imaginary file is safe!");
			}
		});
	});
	$(".btn-delete").on('click', function() {
		var box = $(this);
		var file = box.attr('value');
		swal({
			title: "Are you sure?",
			text: "",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			$(".fakeloader").show();
			if (willDelete) {
				$.ajax({
					url: ajax_object.ajax_url,
					type: 'post',
					data: 'file='+file+'&action=ajax_rollback_delete&query_vars='+ajax_object.query_vars,
					success: function( data ) {
						$(".fakeloader").hide();
						if(data == "success"){
							swal("success", {
							  icon: "success",
							});
							box.parent().parent().hide();
						}else{
							
							swal("error", "error");
						}
						
					}
				})
				
			} else {
				$(".fakeloader").hide();
				//swal("Your imaginary file is safe!");
			}
		});
	});
	/*(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.10&appId=715603241959374";
	  fjs.parentNode.insertBefore(js, fjs);
	  
	}(document, 'script', 'facebook-jssdk'));
	$(document).on ( 'click', '.g-signin2', function( event ) {
		event.preventDefault();
		jQuery.ajax({
			url: ajax_object.ajax_url,
			type: 'post',
			data: 'email='+g_email+ '&name='+g_name+'&action=add_user&query_vars='+ajax_object.query_vars,
			success: function( re ) {
				if (re != 500) {	
					window.location.href = re;
				}else{
					console.log("Password error");
				}
			}
		})
	})
	
	
	
});*/

