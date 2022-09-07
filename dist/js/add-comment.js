$(document).ready(function(){
	 $('table td').focusout(function(){
	    var id = $(this).attr("id");
		var data = $(this).html();
		
		$.ajax({
			url:"add-requisition-comment.php",
			method:"POST",
			dataType: "json",
			data:{id:id, data:data, action:'add_requisition_comment'},				
			success:function(response){
		   if(response.success == true)
		   {
		 	   $('#alert-message').append('<div class="alert alert-success">'+
		       '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
	           '<strong><i class="fa fa-check-circle"></i></strong> ' + response.messages +
		       '</div>');
		   }
		   // remove the mesages
             $(".alert-success").delay(500).show(10, function() {
             $(this).delay(5000).hide(10, function() {
             $(this).remove();
              });
            }); // /.alert
			
		  }
		});
	});
});