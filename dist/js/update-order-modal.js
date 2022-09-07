 $(document).ready(function(){
	$('.updateOrder').on('click', function(){
      $('#updateOrder').modal('show');
	  
	  $tr = $(this).closest('tr');
	  var data = $tr.children("td").map(function(){
		return $(this).text();  
	  }).get();
	   console.log(data);
	   
	   $('#orderId').val(data[0]);
	   $('#orderNumber').val(data[1]);
	   $('#supplierName').val(data[2]);
	   $('#tax').val(data[4]);
	   $('#taxAmount').val(data[5]);
	   $('#subTotal').val(data[6]);
	   $('#grandTotal').val(data[7]);
	   $('#amountPaid').val(data[8]);
	   $('#balance').val(data[9]);
	   $('#orderDate').val(data[11]);
	});	 
});