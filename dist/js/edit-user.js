 $(document).ready(function(){
	$('.editUser').on('click', function(){
      $('#editUser').modal('show');
	  
	  $tr = $(this).closest('tr');
	  var data = $tr.children("td").map(function(){
		return $(this).text();  
	  }).get();
	   console.log(data);
	   
	   $('#userId').val(data[0]);
	   $('#firstname').val(data[1]);
	   $('#surname').val(data[2]);
	   $('#username').val(data[3]);
	   $('#userType').val(data[4]);
	   $('#password').val(data[5]);
	   $('#teamCode').val(data[7]); 
	});	 
});