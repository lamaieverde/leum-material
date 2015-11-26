function isValidEmailAddress(emailAddress) {
	var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
	return pattern.test(emailAddress);
};
$( document ).ready(function(){DetectChange()});
function DetectChange(){
	$("#newname").change(function(){
		HideErrorReg()
	});
	$("#newpass").change(function(){
		HideErrorReg()
	});
	$("#newconfpass").change(function(){
		HideErrorReg()
	});
	$("#newemail").change(function(){
		HideErrorReg()
	});
}
function HideErrorReg (){
	$("#erroremail").fadeOut('fast');
};
var Singin = (function (){
	var self = {};
	self.Try = function(){
		var pData = {
			newname: document.getElementById('newname').value,
			newpass: document.getElementById('newpass').value,
			newconfpass:document.getElementById('newconfpass').value,
			newemail:document.getElementById('newemail').value
		};
		if(pData.newname.trim().length == 0 || pData.newpass.trim().length == 0 || pData.newconfpass.trim().length == 0 || pData.newemail.trim().length == 0){
			$("#erroremail").text("I campi non possono essere vuoti");$("#erroremail").slideDown();
		}
		else{
			if( !isValidEmailAddress(pData.newemail.trim()) ) { $("#erroremail").text("L'e-mail inserito  inserito è errato");$("#erroremail").slideDown(); }
			else{
				if (pData.newpass == pData.newconfpass) {
					var api =  new API();
					var risposta = api.chiamaServizio({requestUrl: "pages/register.php", data: pData});
					if(risposta.trim() == ""){
						Login.Try(true, pData.newemail, pData.newpass);
						location.reload();
					}
					else 
						alert(risposta);
				}
				else{
					$("#erroremail").text("La password non coincide");$("#erroremail").slideDown();
				}
			}
		}
		return;
	};
	return self;
}());