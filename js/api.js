/*Api Start*/

function API(){
	this.options = {isAsync: false,	methodType: 'POST',	data: '', callback: null, requestUrl: '', hasModal: false, loader: true };
}
API.prototype.setOptions = function(customOptions){
	$.extend(this.options, this.options, customOptions);
};
API.prototype.validate = function(){
	if (this.options.requestUrl.length === 0)
		return false;
	return true;
};
API.prototype.makeAjaxRequest = function () {
	var options = this.options;
	$.ajax({
		type: options.methodType,
		url: options.requestUrl,
		data: JSON.stringify(options.data),
		async: options.isAsync,
		contentType: "application/json",
		beforeSend: function(){ 
			if(options.loader) 
				$("#myLoader").show();
		},
		complete: function(){
			if(options.loader)
				$("#myLoader").hide();
		},
		success: function (response) {
			if (options.callback)
				options.callback(response);
			else resp = response;
		},
		error: function (XMLHttpRequest) {
			if (XMLHttpRequest.responseText) {
				if (options.callback)
					options.callback(XMLHttpRequest.responseText);
				else resp = XMLHttpRequest.responseText;

			}
		}
	});
}
API.prototype.chiamaServizio = function(customOptions){
	this.setOptions(customOptions);
	if(!this.validate()){
			if(this.options.callback)
				return {error: "URL non definito"};
			else
				alert("URL non definito");
	}
	this.makeAjaxRequest();
	if (this.options.callback === null)
		return resp;
}

/*-API End / SELECT start-----------------------------------------------------------------------------------*/

function SELECT(){
	this.options = {isAsync: true,callback: null, Query: '', hasModal: false, dataType: 'jsonp'}
};
SELECT.prototype.makeAjaxRequest = function () {
	var options = this.options;
	$.ajax({
		type: 'GET',
		url: "http://tweb2015.cs.unibo.it:8080/data/query?query=" + options.Query,
		async: options.isAsync,
		dataType: options.dataType,
		beforeSend: function(){
			/*$("#myLoader").show();*/
		},
		complete: function(){
			/*$("#myLoader").hide();*/
		},
		success: function (response) {
			if (options.callback)
				options.callback(response);
			else resp = response;
		},
		error: function (XMLHttpRequest) {
			if (XMLHttpRequest.responseText) {
				if (options.callback)
					options.callback(XMLHttpRequest.responseText);
				else resp = XMLHttpRequest.responseText;

			}
		}
	});
}
SELECT.prototype.GO = function (customOptions) {
	this.setOptions(customOptions);
	if (!this.validate()) {
		if (this.options.callback)
			return { error: "URL non definito!" };
		else
			alert("URL non definito!");
	}
	this.makeAjaxRequest();
	if (this.options.callback === null)
		return resp;
};
SELECT.prototype.setOptions = function (customOptions) {
	$.extend(this.options, this.options, customOptions);
};
SELECT.prototype.validate = function () {
	if (this.options.Query.length === 0)
		return false;
	return true;
}

/*SELECT End*/