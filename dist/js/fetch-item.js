function getItem(val) {
            $.ajax({
                url: 'fetch-item.php',
                type: 'POST',
                data: 'itemNumber='+val,
                dataType: 'json',
                success:function(data){
                    var len = data.length;
                    if(len > 0){
                        var itemName = data[0]['product_name'];
                        var specification = data[0]['specification'];
                        var type = data[0]['type'];
						var category = data[0]['category'];

                        document.getElementById('itemName').value = itemName;
						document.getElementById('description').value = specification;
                        document.getElementById('type').value = type;
                        document.getElementById('category').value = category;						
                    }
					else{
						alert('The entered product code does not exist');
						return false;
					}
                }
            });
        }