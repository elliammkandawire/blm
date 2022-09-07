$(document).ready(function(){ 
	   $("#orderForm").on('submit','#deny', function(event) {
	   var form = $(this);
       var formData = new formData(this);
        $.ajax({
	    url : form.attr('formaction'),
	    type: form.attr('method'),
	    data: formData,
	    dataType: 'json',
	    cache: false,
	    contentType: false,
	    processData: false,
	    success:function(response) {
	    console.log(response);
	    if(response.success == true) {
	      // shows a successful message after update operation
	     $('#alert-message').append('<div class="alert alert-success">'+
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
    });//end of click event function
});// document.ready function