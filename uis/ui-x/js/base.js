// Localization
function localize(){
	$.each(localization,function(i,val){
		$("." + i).html(val);
	});
}
function reLocalize(lang){
	$(document).append("<script src=\"locales/"+lang+".js\"></script>"); // Lataa sanaston
	localize(); // Ottaa sanaston käyttöön
}

$(function(){

	// Menubar
	$("#menuBar").kendoMenu();
	
	localize(); // Jos kieltä vaihdetaan, kutsu funktiota reLocalize("kieli")

});