 $(document).ready(function(){
	$('.stockUsage').on('click', function(){
      $('#stockUsage').modal('show');
	  
	  $tr = $(this).closest('tr');
	  var data = $tr.children("td").map(function(){
		return $(this).text();  
	  }).get();
	   console.log(data);
	   
	   $('#itemId1').val(data[0]);
	   $('#itemNumber1').val(data[1]);
	   $('#itemName1').val(data[2]);
	   $('#unit1').val(data[6]);
	   $('#type1').val(data[5]);
	   $('#category1').val(data[4]);
	   $('#quantity1').val(data[7]);
	   $('#stockUsed').val(data[8]);
	   $('#batchNo1').val(data[11]);
	});	 
});