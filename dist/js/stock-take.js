$(document).ready(function(){
	 $('table td').focusout(function(){
	    var id = $(this).attr("id");
		var data = $(this).html();
		
		$.ajax({
			url:"update-stock-take.php",
			method:"POST",
			dataType: "json",
			data:{id:id, data:data, action:'update_stock_take'},				
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
             $(this).delay(500).hide(10, function() {
             $(this).remove();
              });
            }); // /.alert
			
		  }
		});
	});
});