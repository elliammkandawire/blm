$(document).ready(function(){
	$("#orderForm").on('submit',function(event) {
        event.preventDefault(); 
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
	
	//scrolling the modal to top to display the success message
    $("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
	// shows a successful message after update operation
	$('#update-success-message').append('<div class="alert alert-success">'+
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
	$('#updateOrder').on('hidden.bs.modal', function(){
		window.location.reload();
	});
});// document.ready function