jQuery.noConflict();

jQuery( document ).ready( function($) {
	$('head').append('<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">');
	$(document).on ( 'click', '.btn-export', function( event ) {
		event.preventDefault();
		var file_option = $(this).attr('value');
		var file_type = $(this).attr('value_type');
		var value_a = $(this).attr('value_a');
		var auction = $('#auction').val();
		var file_name = $('#file_name').val();
		console.log(auction);
		reset_table();
		$('#tableID-exp').css('opacity','1');
		$('#tableID-exp').css('position','relative');
		$('#tableID').hide();
		
		if(auction === null){
			add_table();
			
			$('#tableID-exp').tableExport({type:file_type,escape:'false'});
		} else {
			for(i=0; i<auction.length; i++) {
				try {
					$( "."+$.trim(auction[i]) ).appendTo( "#tr-exp" );
				}
				catch(err) {					
				}
				
			}
			$('#tableID-exp').tableExport({type:file_type,escape:'false'});
		}
		if(file_name == "") {
			saveAs($('.'+value_a).attr('href'), 'customers.'+file_option);
		} else {
			saveAs($('.'+value_a).attr('href'), file_name+'.'+file_option);
		}
		
	});
	
	$(document).on ( 'click', '.btn-view', function( event ) {
		event.preventDefault();
		var auction = $('#auction').val();
		var file_name = $('#file_name').val();
		console.log(auction);
		reset_table();
		$('#tableID-exp').css('opacity','1');
		$('#tableID-exp').css('position','relative');
		$('#tableID').hide();
		
		if(auction === null){
			add_table();
			$('#tableID-exp').tableExport({type:'excel',escape:'false'});
		} else {
			for(i=0; i<auction.length; i++) {
				try {
					$( "."+$.trim(auction[i]) ).appendTo( "#tr-exp" );
				}
				catch(err) {					
				}
				
			}
			$('#tableID-exp').tableExport({type:'excel',escape:'false'});
		}
		
		
	});
	
});
function reset_table(){
	jQuery( "#tr-exp tr" ).each(function() {
		jQuery( this ).appendTo( "#tr-view" );
	});
	
}
function add_table(){
	jQuery( "#tr-view tr" ).each(function() {
		jQuery( this ).appendTo( "#tr-exp" );
	});
	
}

function saveAs(uri, filename) {
    var link = document.createElement('a');
    if (typeof link.download === 'string') {
        document.body.appendChild(link); // Firefox requires the link to be in the body
        link.download = filename;
        link.href = uri;
        link.click();
        document.body.removeChild(link); // remove the link when done
    } else {
        location.replace(uri);
    }
}