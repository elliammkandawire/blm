 $(document).ready(function(){
	$(document).on('click', '#checkAll', function() {          	
		$(".itemRow").prop("checked", this.checked);
	});	
	$(document).on('click', '.itemRow', function() {  	
		if ($('.itemRow:checked').length == $('.itemRow').length) {
			$('#checkAll').prop('checked', true);
		} else {
			$('#checkAll').prop('checked', false);
		}
	});  
	var count = $(".itemRow").length;
	$(document).on('click', '#addRows', function() { 
		count++;
		var htmlRows = '';
		htmlRows += '<tr>';
		htmlRows += '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input itemRow" id="itemRow_'+count+'"><label class="custom-control-label" for="itemRow_'+count+'"></label></div></td>';
		htmlRows += '<td><input type="text" name="productName[]" id="productName_'+count+'" class="form-control" data-rule-required="true" data-msg-required="Please enter item name"></td>';
		htmlRows += '<td><input type="text" name="specification[]" id="specification_'+count+'" class="form-control" data-rule-required="true" data-msg-required="Please enter specification"></td>';
		htmlRows += '<td><select class="form-control" name="category[]" id="category_'+count+'" data-rule-required="true" data-msg-required="Please select a category"><option value="">Choose a category...</option><option>Eye/Ear Preparation</option><option>Family Planning Products</option><option>Inhalers</option><option>Injectables</option><option>IV Fluids</option><option>Liquid (Oral) Preparations</option><option>Other Laboratory Suppliers</option><option>Pessaries and Suppositories</option><option>Solutions</option><option>Tablets/Capsules(Oral)</option><option>Topical Preparations</option></select></td>';
		htmlRows += '<td><input type="text" name="unit[]" id="unit'+count+'" class="form-control unit" data-rule-required="true" data-msg-required="Please enter unit"></td>'; 
		htmlRows += '<td><input type="number" name="quantity[]" id="quantity_'+count+'" class="form-control quantity" data-rule-required="true" data-msg-required="Please enter quantity"></td>';   		
		htmlRows += '<td><input step="any" type="number" name="price[]" id="price_'+count+'" class="form-control price" data-rule-required="true" data-msg-required="Please enter price"></td>';		 
		htmlRows += '<td><input step="any" type="number" name="total[]" id="total_'+count+'" class="form-control total disabled" readonly></td>';          
		htmlRows += '</tr>';
		$('#invoiceItem').append(htmlRows);
		
		// submit Order form
        /*$('#submitOrder').unbind('submit').bind('submit', function(){
			//Form Validations
			var productName = $("#productName_1").val();
			var category = $("#category_1").val();
			var unit = $("#unit").val();
			var quantity = $("#quantity_1").val();
			var price_1 = $("#price_1").val();
			
			if(productName == "") {
				$("#productName_1").after('<p class="text-danger">Product name field is required</p>');
				$('#productName_1').closest('.form-group').addClass('has-error');
			}	else {
				// remov error text field
				$("#productName_1").find('.text-danger').remove();
				// success out for form 
				$("#productName_1").closest('.form-group').addClass('has-success');	  	
			}	// /else
		});*/
	
		/*$(document).on('click', '#addOrderNo', function() {
			var now = new Date();
		    var randomNum = '';
		    randomNum += Math.round(Math.random() * 9);
            randomNum += Math.round(Math.random() * 9);
            randomNum += now.getTime().toString().slice(-4);
		    $('.orderNumber').val(randomNum);
	    });*/
	}); 
	$(document).on('click', '#removeRows', function(){
		$(".itemRow:checked").each(function() {
			$(this).closest('tr').remove();
		});
		$('#checkAll').prop('checked', false);
		calculateTotal();
	});		
	$(document).on('input', "[id^=quantity_]", function(){
		calculateTotal();
	});	
	$(document).on('input', "[id^=price_]", function(){
		calculateTotal();
	});
	$(document).on('input', "[id^=taxRate]", function(){
		calculateTotal();
	});
});	
function calculateTotal(){
	var totalAmount = 0; 
	$("[id^='price_']").each(function() {
		var id = $(this).attr('id');
		id = id.replace("price_",'');
		var price = parseFloat($('#price_'+id).val());
		var quantity  = parseInt($('#quantity_'+id).val());
		if(!quantity) {
			quantity = 1;
		}
		var total = price*quantity;
		$('#total_'+id).val(parseFloat(total).toFixed(2));
		totalAmount += total;			
	});
	$('#subTotal').val(parseFloat(totalAmount).toFixed(2));	
	var taxRate = parseFloat($("#taxRate").val());
	var subTotal = $('#subTotal').val();	
	if(subTotal) {
		var taxAmount = parseFloat(subTotal*taxRate/100).toFixed(2);
		$('#taxAmount').val(taxAmount);
		subTotal = parseFloat(subTotal)+parseFloat(taxAmount);
		$('#totalAftertax').val(parseFloat(subTotal).toFixed(2));
		//var totalAftertax = $('#totalAftertax').val();
	}
}