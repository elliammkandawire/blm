 $(document).ready(function(){
	$('.editTeam').on('click', function(){
      $('#editTeam').modal('show');
	  
	  $tr = $(this).closest('tr');
	  var data = $tr.children("td").map(function(){
		return $(this).text();  
	  }).get();
	   console.log(data);
	   
	   $('#teamId').val(data[0]);
	    $('#teamCode').val(data[1]); 
	   $('#teamName').val(data[2]);
	});	 
});