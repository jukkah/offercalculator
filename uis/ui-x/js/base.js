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
	$("#bottomBar").kendoMenu();
	
	localize();
	
	$("#localizeFi").click(function(){
		reLocalize("fi_FI");
	});
	
	$("#localizeGB").click(function(){
		reLocalize("en_GB");
	});
	
	$("#localizeSv").click(function(){
		reLocalize("sv_SV");
	});

});