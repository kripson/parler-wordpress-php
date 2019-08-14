jQuery( document ).ready(function() {
	//alert('post-new.js!');
	
	
	setTimeout(
			  function() 
			  {
				    //alert( "ready!" );
				  
					var term1 = jQuery('#parler-comments-term-id').val();
					term1 = "#editor-post-taxonomies-hierarchical-term-" + term1;
					
					var term2 = jQuery('#parler-publish-term-id').val();
					term2 = "#editor-post-taxonomies-hierarchical-term-" + term2;
					
						
					jQuery( term1 ).prop( "checked", true );
					jQuery( term2 ).prop( "checked", true );
			  
			  
			  }, 2000);
	
});