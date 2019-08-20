/*global jQuery*/

jQuery( document ).ready(function() {
	jQuery('#parler-tabs').hide();
	jQuery('#parler-add-toggle').hide();
	jQuery("#parler-all").css("border","none");
	setTimeout(function(){
	    jQuery(findElementByText("Parler").filter).hide();
	    //alert('hid');
    }, 3000);
    //var parlerPublish = jQuery('#parlerPublish').val();
   // alert("yo");
    alert(parlerPublish);
});

function findElementByText(text) {
    var jSpot = jQuery("b:contains(" + text + ")")
        .filter(function() { return jQuery(this).children().length === 0;})
        .parent();  // because you asked the parent of that element
    return jSpot;
}