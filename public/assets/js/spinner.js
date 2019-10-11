$(document).ready( function() {
	
	$(document).on({
		ajaxStart: function() { $('#modalLoad').modal("show");   },
		ajaxStop:  function() { $('#modalLoad').modal("hide")}    
	}); 
});