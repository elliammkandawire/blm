$(document).ready(function(){
	 $(document).on('click', '.deleteUploads', function(){
		var id = $(this).attr("id");
		var el = this;
		if(confirm("Are you sure you want to delete this file?")){
			$.ajax({
				url:"delete-uploads.php",
				method:"POST",
				dataType: "json",
				data:{id:id, action:'delete_uploads'},				
				success:function(response){
					if(response.status == 1){
						$(el).closest("tr").css('background', 'red');
						$(el).closest("tr").fadeOut(800, function(){
						$('#'+id).closest("tr").remove();
						});
					}
				}
			});
		} else {
			return false;
		}
	});
});