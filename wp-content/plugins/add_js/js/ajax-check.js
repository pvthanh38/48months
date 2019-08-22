var g_email = "";
var g_name = "";
console.log("hehe");
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
*/
jQuery.noConflict();
jQuery( document ).ready( function($) {
	jQuery('.btn-vote').click(function(){
		console.log("abc");
		var _this = $(this);
		var post_id = $(this).attr('post_id');
		var num = $(this).attr('num');
		var login = $(this).attr('login');
		if(login == 1){
			$.ajax({
				url: ajax_object.ajax_url,
				type: 'post',
				data: 'post_id='+post_id+'&action=ajax_rollback&query_vars='+ajax_object.query_vars,
				success: function( data ) {
					data = parseInt(data);
					$('.vote').text(data);
					//../../files/
					_this.attr('num',data);
					if(num < data){
						_this.attr('src','../../files/liked.png');
						swal("Thanks",'Vote','success');
						
					}else{
						_this.attr('src','../../files/like.png');
						swal("Vote Down",'Vote','success');
					}
				},
				error: function (error) {
					swal("Voted",'','warning');
				}
			})
		}else{
			swal("Login before vote",'','warning');
		}
		
	})
	
	
	
	
	
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
				/*swal("Poof! Your imaginary file has been deleted!", {
				  icon: "success",
				});*/
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
				/*swal("Poof! Your imaginary file has been deleted!", {
				  icon: "success",
				});*/
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
	})*/
	$("input:checkbox").on('click', function() {
		// in the handler, 'this' refers to the box clicked on
		var $box = $(this);
		if ($box.is(":checked")) {
		// the name of the box is retrieved using the .attr() method
		// as it is assumed and expected to be immutable
		var group = "input:checkbox[name='type']";
		// the checked state of the group/box on the other hand will change
		// and the current value is retrieved using .prop() method
			$(group).prop("checked", false);
			$box.prop("checked", true);
		} else {
		$box.prop("checked", false);
		}
	});
	
	$(document).on ( 'click', '.btn-display', function( event ) {
		event.preventDefault();
		$(".fakeloader").show();
		var form = $( "#condition" ).serialize();
		var url = $("#url").val();
		var atLeastOneIsChecked = $('input[name="type"]:checked').length > 0;
		if(atLeastOneIsChecked != 1 && atLeastOneIsChecked != true){
			
			$(".fakeloader").hide();
			swal("Error", "Chọn import bài viết hoặc sản phẩm", "error"); return false;
		}
		var type = $('input[name="type"]:checked').val()
		
		$.ajax({
			url: ajax_object.ajax_url,
			type: 'post',
			data: form+'&url='+url+'&type='+type+'&action=ajax_condition_imp_dis&query_vars='+ajax_object.query_vars,
			success: function( data ) {
				$(".fakeloader").hide();
				console.log(data);
				if(data == 'success'){
					$.ajax({
						url: ajax_object.ajax_url,
						type: 'post',
						data: form+'&url='+url+'&type='+type+'&action=ajax_unzip&query_vars='+ajax_object.query_vars,
						success: function( data ) {
							$(".fakeloader").hide();
							if(data == 'success'){
								swal("Success", 'Thành công, Vui lòng chờ nhập product trong quá trình cron', "success");
							} else {
								swal("Error", data, "error");
							}
						}
					})
				} else {
					swal("Error", data, "error");
				}
			}
		})
	});
	
	
});

