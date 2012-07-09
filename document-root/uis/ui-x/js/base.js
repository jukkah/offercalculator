// Localization

// Original code by Petja Touru
// Current version mVChr (http://jsfiddle.net/LJN9Q/3/)

function localize(){
$.each(localization, function(k, v){
    $("span." + k + ", " +
      "a." + k + ", " +
      "div." + k).html(v);

    $("input." + k + "[type=\"submit\"]").val(v);

    $("input." + k + "[type=\"text\"], " +
      "input." + k + "[type=\"password\"], " +
      "input." + k + "[type=\"email\"]").attr("placeholder", v).attr("title", v);

    $("img." + k).attr("title", v)
                 .attr("alt", v);
});
}

function reLocalize(lang){
	$(document).append("<script src=\"locales/"+lang+".js\"></script>");
	localize();
}

function logIn(){
	$("#loginName").attr("disabled","disabled");
	$("#loginPassword").attr("disabled","disabled");
	$("#loginButton").attr("disabled","disabled");
	var loginrequest = $.ajax({
		type: "HEAD",
		url: "../../terminal/index.php?command=login",
		data: [],
		cache: false
	}).done(function(loginResponse) {
		$("#loginName").removeAttr("disabled");
		$("#loginPassword").removeAttr("disabled");
		$("#loginButton").removeAttr("disabled");
		$("#loginError").clearQueue();
		$("#loginError").fadeOut("fast");
		$("#loginError").slideDown("fast");
			if(loginResponse["error-code"]){
				switch(loginResponse["error-code"]){
					case ["1-username",""]:
						$("#loginErrorText").html(localization["noUsername"]);
					break;
					case ["1-password",""]:
						$("#loginErrorText").html(localization["noPassword"]);
					break;
					case ["2",""]:
						$("#loginErrorText").html(localization["incorrectLogin"]);
					break;
					default:
						$("#loginErrorText").html(localization["unknownError"]);
					break;
				}
			}else{
				$("#loginErrorText").html(localization["unknownError"]);
			}
	});
}

$(function(){

	// Menubar
	$("#menuBar").kendoMenu();
	$("#bottomBar").kendoMenu();
	
	localize();
	
	// Localization buttons:
	$(".localizeFi").click(function(){
		reLocalize("fi_FI");
	});
	
	$(".localizeGB").click(function(){
		reLocalize("en_GB");
	});
	
	$(".localizeSv").click(function(){
		reLocalize("sv_SV");
	});

	$(".authorsLink").click(function(){
		authorswindow.open();
	});
	//
	
	loginwindow = 
	$("#loginWindow").kendoWindow({
		actions: [""],
		draggable: false,
		modal: true,
		resizable: false,
		title: localization["LogIn"],
		width: 450,
		height: 300
	}).data("kendoWindow");
	loginwindow.center();
	
	authorswindow = 
	$("#authorsWindow").kendoWindow({
		draggable: false,
		modal: true,
		resizable: false,
		title: localization["Authors"],
		width: 338,
		height: 225
	}).data("kendoWindow");
	authorswindow.center();
	authorswindow.close();
			
	var testlogin = $.ajax({
		type: "HEAD",
		url: "../../terminal/index.php?command=login-test",
		cache: false
	}).done(function(html) {
		if(testlogin.getResponseHeader("logged-in")=="false"){
			loginwindow.open();
		}else{
			loginwindow.close();
		}
	});
	
	$("#loginButton").click(function(){
		logIn();
	});

});