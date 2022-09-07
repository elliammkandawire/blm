$(document).ready(function(){
	$("#stockUsageForm").on('submit',function(event) {
		event.preventDefault();
	    var isInvalid=false;
		var quantity1 = $("#quantity1").val();
	    var quantity2 = $("#quantity2").val();
		 
		if(parseInt(quantity2) > parseInt(quantity1))
		{
		   $("#quantity2").after('<strong><p class="text-danger">The specified quantity is more than what is in stock</p></strong>');
		   $('#quantity2').parents('.form-group').addClass('has-warning');
		   isInvalid = true;
		}
		else{
			// remove error text field
			$("#quantity2").find('.text-danger').remove();
			// success out for form 
			$("#quantity2").closest('.form-group').addClass('has-success');
		}
		if(parseInt(quantity2) < 1)
		{
		   $("#quantity2").after('<strong><p class="text-danger">The specified quantity should be 1 or above</p></strong>');
		   $('#quantity2').closest('.form-group').addClass('has-warning');
		   isInvalid = true;
		}
		else{
			// remove error text field
			$("#quantity2").find('.text-danger').remove();
			// success out for form 
			$("#quantity2").closest('.form-group').addClass('has-success');
		}
		if (isInvalid){
			return false;
		}
	
	 
	var form = $(this);
	var formData = new FormData(this);	
	
    $.ajax({
	url : form.attr('action'),
	type: form.attr('method'),
	data: formData,
	dataType: 'json',
	cache: false,
	contentType: false,
	processData: false,
	success:function(response) {
	console.log(response);
	if(response.success == true) {
	
    //$("#orderForm")[0].reset();
	
	//scrolling the modal to top to display the success message
    $("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
	// shows a successful message after update operation
	$('#success-message').append('<div class="alert alert-success">'+
		'<button type="button" class="close" data-dismiss="alert">&times;</button>'+
	    '<strong><i class="fa fa-check-circle"></i></strong> ' + response.messages +
		'</div>');
		
	// remove the mesages
    $(".alert-success").delay(500).show(10, function() {
    $(this).delay(5000).hide(10, function() {
    $(this).remove();
    });
    }); // /.alert
	
	  } // /if response.success

	 } // /success function
       }); // /ajax function 
    });
	$('#stockUsage').on('hidden.bs.modal', function(){
		window.location.reload();
	});
});// document.ready fucntion