 $(document).ready(function(){
	$('.editStock').on('click', function(){
      $('#editStock').modal('show');
	  
	  $tr = $(this).closest('tr');
	  var data = $tr.children("td").map(function(){
		return $(this).text();  
	  }).get();
	   console.log(data);
	   
	   $('#itemId').val(data[0]);
	   $('#itemNumber').val(data[1]);
	   $('#itemName').val(data[2]);
	   $('#description').val(data[3]);
	   $('#category').val(data[4]);
	   $('#type').val(data[5]);
	   $('#unit').val(data[6]);
	   $('#quantity').val(data[7]);
	   $('#price').val(data[9]);
	   $('#expiryDate').val(data[12]);
	   $('#batchNo').val(data[13]);
	   $('#grn').val(data[14]);
	});	 
});