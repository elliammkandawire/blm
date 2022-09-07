$(document).ready(function(){
	 $(document).on('click', '.deleteUser', function(){
		var id = $(this).attr("id");
		var el = this;
		if(confirm("Are you sure you want to delete this user?")){
			$.ajax({
				url:"delete-user.php",
				method:"POST",
				dataType: "json",
				data:{id:id, action:'delete_user'},				
				success:function(response){
					if(response.status == 1){
						$(el).closest("tr").css('background', 'tomato');
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