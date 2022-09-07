$('#quantity, #price').on('input',function() {
    var quantity = parseInt($('#quantity').val());
	
    var price = parseFloat($('#price').val());
	
    $('#totalPrice').val((quantity * price ? quantity * price : 0).toFixed(2));

});