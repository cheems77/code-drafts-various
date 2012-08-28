// Performs an asynchronous lookup of pin numbers already in a database and returns available
// random pins

$(document).ready(function() {
	$("form#pingenerate").submit(function() {

		// we want to store the values from the form input, then send via ajax below
		var pinquantity = $('#sel_pinquantity').attr('value');
		var output = "";

		// Disable the Submit button until the entire operation is complete
		$("#btnSubmit").attr("disabled", true);
		
		$.ajax({
			type : "POST",
			url : "/index.php/?rt=ajax&pinquantity=" + pinquantity,
			dataType: 'json',
			success : function(e, txtStatus) {
				$.each(e, function (item, v){
					output += '<p>' + v.value + '</p>';
				});
				$('div.success').html(output);
			},
			error: function(jqXHR, txtStatus){
				
        		alert(jqXHR.statusText + ": Unable to generate pin! Please try again.");
		    },
        	complete: function () {		
				// Process complete - re-enable the Submit button
				$("#btnSubmit").attr("disabled", false);
        	}

		});

				
		return false;
	});
}); 