 $(document).ready(function(){
	$('.stockTake').on('click', function(){
      $('#stockTake').modal('show');
	  
	  $tr = $(this).closest('tr');
	  var data = $tr.children("td").map(function(){
		return $(this).text();  
	  }).get();
	   console.log(data);
	   
	   $('#stockTakeId').val(data[0]);
	   $('#itemNumber').val(data[1]);
	   $('#itemName').val(data[2]);
	   $('#category').val(data[4]);
	   $('#type').val(data[5]);
	   $('#unit').val(data[6]);
	   $('#expiry').val(data[9]);
	   $('#quantity').val(data[12]);
	   $('#openingStock').val(data[13]);
	   $('#closingStock').val(data[14]);
	   $('#quantityUsed').val(data[15]);
	   $('#physicalStock').val(data[16]);
	   $('#remarks').val(data[18]);
	});	 
});