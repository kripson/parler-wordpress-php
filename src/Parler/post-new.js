/*global jQuery*/

jQuery( document ).ready(function() {
    //alert('jQuery');
	jQuery('#parler-tabs').hide();
	jQuery('#parler-add-toggle').hide();
	jQuery("#parler-all").css("border","none");
	setTimeout(
  function() 
  {
	jQuery(findElementByText("Parler").filter).hide();
	alert('hid');
  }, 3000);

	
});

function findElementByText(text) {
	//alert('yo');
    var jSpot = jQuery("b:contains(" + text + ")")
                .filter(function() { return $(this).children().length === 0;})
                .parent();  // because you asked the parent of that element

    return jSpot;
}
//components-panel__body-title