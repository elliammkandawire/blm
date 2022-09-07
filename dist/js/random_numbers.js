        now = new Date();
        randomNum = '';
        randomNum += Math.round(Math.random() * 9);
        randomNum += Math.round(Math.random() * 9);
        randomNum += now.getTime().toString().slice(-4);
        window.onload = function () {
            document.getElementById("orderNumber1").value = randomNum;
        }
		
		/*$(document).on('click', '#addOrderNo', function() {
			var now = new Date();
		    var randomNum = '';
		    randomNum += Math.round(Math.random() * 9);
            randomNum += Math.round(Math.random() * 9);
            randomNum += now.getTime().toString().slice(-4);
		    $('.orderNumber').val(randomNum);
			
	    });*/
