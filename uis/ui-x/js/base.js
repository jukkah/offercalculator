// Localization
function localize(){
	$.each(localization,function(i,val){
		$("." + i).html(val);
	});
}
function reLocalize(lang){
	$(document).append("<script src=\"locales/"+lang+".js\"></script>"); // Lataa sanaston
	localize(); // Ottaa sanaston k�ytt��n
}

$(function(){

	// Menubar
	$("#menuBar").kendoMenu();
	
	localize(); // Jos kielt� vaihdetaan, kutsu funktiota reLocalize("kieli")

});