/**
 * Requires jQuery.
 * Alvin Tang
 *
*/

$(document).ready(function(){
	$("#shortenThis").keyup(function(){
		if (isValidUrl($(this).val())==1) {
			$("#submitLongUrl").removeClass("btn-secondary");
			$("#submitLongUrl").addClass("btn-outline-primary");
			$("#submitLongUrl").prop("disabled", false);
		} else {
			$("#submitLongUrl").removeClass("btn-outline-primary");
			$("#submitLongUrl").addClass("btn-secondary");
			$("#submitLongUrl").prop("disabled", true);
		}
	});
});

function isValidUrl(url){

 var myVariable = url;
    if(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(myVariable)) {
      return 1;
    } else {
      return -1;
    }   
}