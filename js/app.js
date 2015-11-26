var Login = (function (){
	var self = {};
	self.toggle = function (){
		var loginForm = document.getElementById('login-form');
		classie.toggle(loginForm, 'initLogin');
		classie.toggle(loginForm, 'changeLogin');
	};
	self.Remove = function (){
		var loginForm = document.getElementById('login-form');
		if(classie.has(loginForm, 'changeLogin')){
			classie.remove(loginForm, 'changeLogin');
			classie.add(loginForm, 'initLogin');
		}
	};
	self.Show = function (){
		var loginForm = document.getElementById('login-form');
		if(classie.has(loginForm, 'initLogin')){
			classie.remove(loginForm, 'initLogin');
			classie.add(loginForm, 'changeLogin');
		}
	};
	self.Try = function(ok, user, pass){
		ok = ok || false;
		var pData = {};
		if(!ok)
			pData = {
				user: document.getElementById('txtuser').value,
				password: document.getElementById('txtpass').value
			}
		else
			pData = {
				user: user,
				password: pass
			}
		if(pData.user.trim().length == 0) alert("Username can't be blank.");
		else{
			if(pData.password.trim().length == 0) alert("Password can't be blank.");
			else{
				var api = new API();
				var risposta = api.chiamaServizio({requestUrl: "pages/login.php?user="+pData.user+"&password="+pData.password, methodType: "GET"});
				 if(risposta.trim() == "")
				 	location.reload();
				 else alert(risposta);

			}
		}
		return;
	}
	self.LogOut = function(){
		window.location="pages/logout.php";
	}
	return self;
}());

var Scrap = (function(){
	var self = {};
	var DataObject = {};
	self.GetNew = function(what, index){
		var ann = sessionStorage.getItem('ann');
		if(ann == undefined || ann == "") return null;
		var control = "ver1";
		if(index != 0) control = "cited";

		if(typeof ann == "object") ann = [ann];
		else{
			ann = ann.replace(/\|/g, ",");
			ann = "[" + ann + "]";
			ann = JSON.parse(ann);
		}
		var arr = [];
		for(var i = 0; i < ann.length; i++){

			if(self.CheckRet(what))
			{
				//Se Retorica fai questo
				if(ann[i].predicate.value == "http://www.ontologydesignpatterns.org/cp/owl/semiotics.owl#denotes" && ann[i].object.value == what && ann[i].azione.value != 'D')
					arr.push(ann[i]);
			}
			else{
				if(ann[i].predicate.value == what && what == "http://purl.org/dc/terms/creator" && ann[i].azione.value != 'D' && ann[i].subject.value.slice(-8).indexOf("cited") == -1 && control != "cited")
					arr.push(ann[i]);
				else
					if(ann[i].predicate.value == what && what == "http://schema.org/comment")
						arr.push(ann[i]);
					else
						if(ann[i].predicate.value == what && ann[i].azione.value != 'D' && ann[i].subject.value.slice(-8).indexOf(control) != -1)
							arr.push(ann[i]);
			}
		}
		return arr.length == 0 ? null : arr;
	};
	self.Execute = function(what, array, index){
		var json = JSON.parse(sessionStorage.getItem('annotation'));
		if(json == null) return;
		var control = "ver1";
		if(index != 0) control = "cited";
		return self.GetArray(json.results.bindings, what, control);
	}
	self.ShowArray = function(what, chk, index){
		index = index || 0;
		var id = what + index;
		if(chk.checked){
		what = self.Decode(what);
		var content = self.Execute(what, true, index);
		var content2 = self.GetNew(what, index);
		var content2 = null;
		if(content2 != null) content = content.concat(content2);
		if (content != null && content != undefined)
			for(var i = 0; i< content.length; i++)
				if(content[i] != null){
						self.Highlight(content[i], self.CheckID(id));
				}
		}
		else{
			$("span." + self.CheckID(id)).contents().unwrap();
			$("span." + self.CheckID(id)).remove();
			$("span.gn-icon-show." + self.CheckID(id)).remove();
			}
	}
	self.Highlight = function(elements, style){
		function guid() {
		  function s4() {
			return Math.floor((1 + Math.random()) * 0x10000)
			  .toString(16)
			  .substring(1);
		  }
		  return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
			s4() + '-' + s4() + s4() + s4();
		}
		var id = self.GetPrefixID(elements.subject.value, elements.id.value);


		var element = $("#" + id);
		if(element.html() == undefined) element = $("#" + id + "1");
		var html = element.html();
		var text = element.text();
		if(html == undefined || text == "") return;
		var before, after;
		var origin = text.substring(elements.start.value, elements.end.value);
		var guid = guid();
		var span = $(document.createElement("span")).addClass(style).addClass('gn-icon-show').attr("name", guid);
		span.attr("data-info", JSON.stringify(elements));
		span.attr("onclick", "Scrap.OnClick(this, '"+ style +"'); return false;");
		//se non ci sono tag figli in mezzo, semplice.
		var start = html.lastIndexOf(htmlEntities(origin));
		var end = start + htmlEntities(origin).length;
		var startSpan = "<span name='"+guid+"' class='" + style + "'>";
		if(start != -1){
			before = html.substring(0, start);
			after = html.substring(end);
			origin = html.substring(start, end);
			origin = startSpan + origin + "</span>";
			element.html(before + span[0].outerHTML + origin + after);
			return;
		}
		var index = 0;
		var trovato = false;
		var stop = false;
		document.getElementById($(element)[0].id).normalize();
		Recursive(element);

		function WRAP(el, first){
			el.contents().each(function(){
				if($(this).nodeType === 8) return;
				else
					if($(this)[0].nodeType === 1) ASD($(this, first));
					else {
						first = false;
						$(this).wrap(startSpan + "</span>");
					}
			});
		}

		function Recursive(el){
			el.contents().each(function(){
				if($(this)[0].nodeType === 8) return;
				if($(this)[0].nodeType === 1) {return Recursive($(this));}
				if(stop) return false;
				index += $(this).text().length;
				if(index == elements.end.value) stop = true;
				if(index >= elements.start.value && index <= elements.end.value) { //se troviamo il primo nodo
					if(!trovato){
						var last = index - elements.start.value;
						start = $(this).text().length - last;
						before = $(this).text().substr(0, start);
						origin = $(this).text().substr(start);
						if(origin.trim() != "")
						{
							$($(this)[0]).replaceWith(before + span[0].outerHTML + startSpan + origin + "</span>");
							trovato = true;
						}
					}
					else
						$(this).wrap(startSpan + "</span>"); //se tutto il nodo in mezzo � il nostro testo
				}
				else
				{
					if(index > elements.end.value){
						end = index - elements.end.value;
						end = $(this).text().length - end - 4;
						after = $(this).text().substr(end);
						origin = $(this).text().substr(0, end);
						$($(this)[0]).replaceWith(startSpan + origin + "</span>" + after);
						stop = true;
					}
				}
			});
		}
		var ciao = "";
		self.RemoveBlankSpan(guid);
	}
	self.GetArray = function(from, what, control){
		var array = [];
		for(var i = 0; i< from.length; i++){
			if(self.CheckRet(what))
			{	//Se Retorica fai questo
				if(from[i].predicate.value == "http://www.ontologydesignpatterns.org/cp/owl/semiotics.owl#denotes" && from[i].object.value == what)
					array.push(self.CheckAnnotation(from[i]));
			}
			else
			{
				if(from[i].predicate.value == what && what == "http://purl.org/dc/terms/creator" && from[i].subject.value.slice(-8).indexOf("cited") == -1 && control != "cited")
					array.push(self.CheckAnnotation(from[i]));
				else
					if(from[i].predicate.value == what && what == "http://schema.org/comment")
						array.push(self.CheckAnnotation(from[i]));
					else
						if(from[i].predicate.value == what && from[i].subject.value.slice(-8).indexOf(control) != -1)
							array.push(self.CheckAnnotation(from[i]));
			}
		}
		return array;
	}
	self.Check = function(doc, pers){
		doc = doc || "";
		pers = pers || "";
			if(Normalize(doc.substring(0,5)) == pers.substring(0,5))
				return 0;
			else
				return 1;
	};
	self.OnClick = function(arg, id){
		$(arg).attr('id', 'OpenedSpan');
		var el = $(arg).attr('data-info');
		var idToRem = $(arg).attr('name');
		el = JSON.parse(el);
			var str = "";
			var box = {};
			switch(el.predicate.value){
				case "http://purl.org/dc/terms/title":
					self.CreateBox(el, id, 1, 'gn-icon-ann-title',idToRem);
				break;
				case "http://purl.org/dc/terms/creator":
					self.CreateBox(el, id, 1, 'gn-icon-ann-autore',idToRem);
				break;
				case "http://prismstandard.org/namespaces/basic/2.0/doi":
					self.CreateBox(el, id, 1, 'gn-icon-ann-doi',idToRem);
				break;
				case "http://purl.org/spar/fabio/hasPublicationYear":
					self.CreateBox(el, id, 1, 'gn-icon-ann-annop',idToRem);
				break;
				case "http://purl.org/spar/fabio/hasURL":
					self.CreateBox(el, id, 1, 'gn-icon-ann-url',idToRem);
				break;
				case "http://schema.org/comment":
					self.CreateBox(el, id, 1, 'gn-icon-ann-commento',idToRem);
				break;
				case "http://www.ontologydesignpatterns.org/cp/owl/semiotics.owl#denotes":
					self.CreateBox(el, 'show-retorica', 1, 'gn-icon-ann-retorica',idToRem);
				break;
				case "http://purl.org/spar/cito/cites":
					self.CreateBox(el, 'show-cites', 1, 'gn-icon-ann-cites',idToRem);
				break;
			}
	};
	self.CreateBox = function(el, id, version,  icon, idToRemove){
		var father = $('#modalBox');
		var annotator = "";
		var label = "";
		var box = "";
		switch(version){
			case 1:
			if(el.name == undefined)
				annotator = " da anonymus ";
			else
				annotator = el.email.value == "http://server/unset-base/anonymus" ? " da anonymus " : " " + el.name.value;

				try{
					label = !self.NoLiteralObject(el.predicate.value) ? el.object.value : el.key.value;
					if(self.CheckRet(el.object.value)) label = self.DecodeRetorica(el.object.value);
					}catch(e){
					if(el.predicate.value == "http://www.ontologydesignpatterns.org/cp/owl/semiotics.owl#denotes")
						label = self.DecodeRetorica(el.object.value);
				};
				var mod = '<li style="float: right"><input id ="delete-ann" class="azzuro red red1" type="button" value="Cancella" onclick="Scrap.AddToFile(\''+id+'\', \'D\', \''+idToRemove+'\')"></li>\
						   <li style="float: right"><input id ="edit-ann" class="azzuro green green1" type="button" value="Modifica" onclick="Scrap.EditOpen(\''+id+'\', \'\', \'U\', \''+idToRemove+'\')"></li>';
				if($("#GRAPH").val() != "http://vitali.web.cs.unibo.it/raschietto/graph/ltw1516" || getCookie("email") == "") mod = "";
				box = $(document.createElement('div'))
					.attr("id", id)
					.addClass("ann-details")
					.addClass("ann-shower")
					.attr("style","display:block;")
					.attr("data-info", JSON.stringify(el));
				box.append('<div class ="commnet-desc" style="overflow:auto; max-height:100px;">\
								<p id="a-lable">'+ label +'</p>\
							</div>\
							<div class ="commnet-desc">\
								<span class ="time">Annotato il ' + el.at.value + annotator + '  </span>\
							</div>\
							<div class ="commnet-separator">\
								<ul class ="edit-delete commnet-user">\
									<li class ="gn-icon '+icon+'" style="float: left">' + el.label.value + '</li>'+mod+'\
									<li style="float: right"><a id ="hide-ann" class ="gn-icon gn-icon-hide" onclick="Scrap.HideModal(\''+id+'\')"></a></li>\
								</ul>\
							</div>');
			break;
		};
		father.append(box);
		father.fadeIn('fast');
	};
	self.HideModal = function(id){
		$("#modalBox").fadeOut('fast');
		$("#" + id).remove();
	};
	self.AddToFile = function(id, azione, idToRemove){
		var json = $("#" + id).attr("data-info");
		var el = JSON.parse(json);
		var predicate = "", ob = $("#" + id), retObject = "";
		if(azione == "D"){
			el.azione = {value: "D"};
			self.TryScrap(el);
			$("span[name="+idToRemove+"]").contents().unwrap();
			$("span[name="+idToRemove+"]").remove();
			self.HideModal(id);
			return;
		}

		if(id == "idDiMerda"){
			try{predicate = self.Decode(ob.find("#iperSelector").val().substr(0, ob.find("#iperSelector").val().length - 1));}catch(e){predicate = self.Decode("hasTitle");}
			if(self.CheckRet(predicate))
			{
				retObject = predicate;
				predicate = "http://www.ontologydesignpatterns.org/cp/owl/semiotics.owl#denotes";
			}
			if(azione == "U"){
				el.azione = {value:"D"};
				self.TryScrap(el);
			}
		}
		if(self.NoLiteralObject(el.predicate.value) && el.predicate.value != predicate && !self.NoLiteralObject(predicate))
			el.object = {value:el.key.value};
		else
			if(!self.NoLiteralObject(el.predicate.value) && el.predicate.value != predicate && self.NoLiteralObject(predicate))
				el.key = {value: el.object.value};

		el.azione = {value:"I"};
		if(id == "idDiMerda"){
			if(self.CheckRet(retObject))
			{
				el.key = {value:el.object.value};
				el.object = {value: retObject};
			}
			el.predicate = {value:predicate};
			el.label = {value:ob.find("#iperSelector option:selected").text()};
			el.bLabel = {value:ob.find("#iperTextArea").val()};

			try{
				var pos=$('#testo_selezionato').attr('data-info'); //mi prendo la nuova posizione e il nuovo testo che si trovano in un data-info del testo selezionato  nel box
					var j=JSON.parse(pos);
					if(j.start.value != el.start.value && j.end.value != el.end.value){
						el.id={value:j.id.value}; //rimpiazza con i nuovi valori
						el.start={value:j.start.value};
						el.end={value:j.end.value};
						if(!self.NoLiteralObject(el.predicate.value))
							el.object={value:j.object.value};
						else
							el.key = {value:j.object.value};
					}
			}
			catch(e){}
		}
		if(azione == "I"){
			var index = ob.find("#iperSelector").val().slice(-1);
			var control = "ver1";
			if(index != 0) control = "cited";
			el.subject = {value: control};
		}
		self.TryScrap(el);
		self.HideModal(id);

		if(azione == "I"){
			var id = ob.find("#iperSelector").val();
			var index = id.substring(id.length - 1);
			id = id.substring(0, id.length - 1);
			id = index == 0 ? id : "c"+id;
			if(document.getElementById(self.CheckID( id)).checked)
				self.Highlight(el, self.CheckID(ob.find("#iperSelector").val())); //ATTENZIONE QUI
		}
		else
		{
			//cancello vecchio data-info
			$("span#OpenedSpan").removeAttr('data-info');
			$("span#OpenedSpan").attr('data-info', JSON.stringify(el));
		}
	}
	self.CheckAnnotation = function(from){
		var ann = sessionStorage.getItem('ann');
		if(ann == undefined || ann == "") return from;
		if(typeof ann == "object") ann = [ann];
		else{
			ann = ann.replace(/\|/g, ",");
			ann = "[" + ann + "]";
			ann = JSON.parse(ann);
		}
		var arr = [];
		for(var i = 0; i < ann.length; i++){
			if(ann[i].id.value != undefined)
				if(ann[i].id.value == from.id.value)
					if(ann[i].start.value == from.start.value)
						if(ann[i].end.value == from.end.value){
								return null;
							}
		}
		return from;
	}
	self.EditOpen = function(id, altro, azione){
			var dati = "";
			if(id != undefined && id != null){
				dati = $("#" + id).attr("data-info");
				dati = JSON.parse(dati);
				$("#" + id).remove();
			}
			else
				dati = altro;
			var disp = id == undefined || id == null ? 'none' : 'block';
			var blockid = "idDiMerda";
			var bodyLabel = "";
			var bodyObject = "";

			bodyLabel = dati.bLabel == undefined ? "" : dati.bLabel.value;
			try{bodyObject = !self.NoLiteralObject(dati.predicate.value) ? dati.object.value : dati.key.value;}catch(e){}

			var box = $(document.createElement('div'))
				.attr("id", blockid)
				.addClass("ann-details")
				.addClass("ann-shower")
				.attr("style", "display:block;")
				.attr("data-info", JSON.stringify(dati))
				.attr("name", "inverse-dropdown");
			box.append('<div class ="commnet-desc">\
						<form>\
							<select id="iperSelector" data-toggle="select" name="searchfield" class="form-control select select-info mrs mbm">\
								<option value="hasTitle0">Titolo</option>\
								<option value="hasAuthor0">Autore</option>\
								<option value="hasDOI0">DOI</option>\
								<option value="hasPublicationYear0">Anno di pubblicazione</option>\
								<option value="hasURL0">URL</option>\
								<option value="hasComment0">Commenti</option>\
								<optgroup label="Retorica">\
									<option value="deo:Introduction0">Introduzione</option>\
									<option value="skos:Concept0">Concetto</option>\
									<option value="sro:Abstract0">Astratto</option>\
									<option value="deo:Materials0">Materiali</option>\
									<option value="deo:Methods0">Metodi</option>\
									<option value="deo:Results0">Risultati</option>\
									<option value="sro:Discussion0">Discussione</option>\
									<option value="sro:Conclusion0">Conclusione</option>\
								</optgroup>\
								<optgroup label="Citazione">\
									<option value="cites0">Frammento Cit.</option>\
									<option value="hasTitle1">Titolo Cit.</option>\
									<option value="hasAuthor1">Autore Cit.</option>\
									<option value="hasDOI1">DOI Cit.</option>\
									<option value="hasPublicationYear1">Anno di pubblicazione Cit.</option>\
									<option value="hasURL1">URL Cit.</option>\
								</optgroup>\
							</select>\
							<div style="float: right; display: '+ disp +';">\
								<a id="change-target" class="azzuro azzuro1 form-control gn-icon gn-icon-ann-target" onclick="modificaPosizione();">Cambia Posizione</a>\
							</div>\
							<div>\
								<p id="testo_selezionato" style="text-align:center; margin:0px; overflow:auto; max-height:100px;">' + bodyObject +'</p>\
							</div>\
							<div>\
								<textarea id="iperTextArea" name="text-label" cols="40" style="margin: 5%;width:90%;" class="normal-input-white deactive-input-white">'+bodyLabel+'</textarea>\
							</div>\
						</form>\
					</div>\
					<div class ="commnet-separator">\
						<ul class ="edit-delete commnet-user">\
							<li class ="gn-icon gn-icon-ann-edit" style="float: left">Modifica</li>\
							<li style="float: right"><input id ="save-ann" class="azzuro azzuro1" type="button" value="Salva" onclick="Scrap.AddToFile(\''+blockid+'\', \''+azione+'\')"></li>\
							<li style="float: right"><input class="azzuro grey" type="azzuro grey" value="Annulla" onclick="Scrap.HideModal(\'idDiMerda\')"> </li>\
						</ul>\
					</div>');
			var father = $('#modalBox');
			father.append(box);
			if(!($(father).is(":visible"))) father.fadeIn('fast');
			var neWelements = {id:{value:dati.id.value},start:{value:dati.start.value},end:{value:dati.end.value},object:{value:dati.object.value}};
			$("p#testo_selezionato").attr("data-info", JSON.stringify(neWelements));
			AddClassBox();
			SelectBox(dati.predicate.value, dati.subject.value, dati.object.value);
	};
	self.Decode = function(what){
		switch(what){
			case "hasTitle": return "http://purl.org/dc/terms/title";
			case "hasAuthor": return "http://purl.org/dc/terms/creator";
			case "hasDOI": return "http://prismstandard.org/namespaces/basic/2.0/doi";
			case "hasPublicationYear": return "http://purl.org/spar/fabio/hasPublicationYear";
			case "hasURL": return "http://purl.org/spar/fabio/hasURL";
			case "hasComment": return "http://schema.org/comment";
			case "deo:Introduction": return "http://purl.org/spar/deo/Introduction";
			case "skos:Concept": return "http://www.w3.org/2004/02/skos/core#Concept";
			case "sro:Abstract": return "http://salt.semanticauthoring.org/ontologies/sro#Abstract";
			case "deo:Materials": return "http://purl.org/spar/deo/Materials";
			case "deo:Methods": return "http://purl.org/spar/deo/Methods";
			case "deo:Results": return "http://purl.org/spar/deo/Results";
			case "sro:Discussion": return "http://salt.semanticauthoring.org/ontologies/sro#Discussion";
			case "sro:Conclusion": return "http://salt.semanticauthoring.org/ontologies/sro#Conclusion";
			case "cites": return "http://purl.org/spar/cito/cites";
		}
		return "";
	};
	self.Encode = function(what){
		switch(what){
		    case "http://purl.org/dc/terms/title": return "hasTitle";
			case "http://purl.org/dc/terms/creator": return "hasAuthor";
			case "http://prismstandard.org/namespaces/basic/2.0/doi": return "hasDOI";
			case "http://purl.org/spar/fabio/hasPublicationYear": return "hasPublicationYear";
			case "http://purl.org/spar/fabio/hasURL": return "hasURL";
			case "http://schema.org/comment": return "hasComment";
			case "http://purl.org/spar/deo/Introduction": return "deo:Introduction";
			case "http://www.w3.org/2004/02/skos/core#Concept": return "skos:Concept";
			case "http://salt.semanticauthoring.org/ontologies/sro#Abstract": return "sro:Abstract";
			case "http://purl.org/spar/deo/Materials": return "deo:Materials";
			case "http://purl.org/spar/deo/Methods": return "deo:Methods";
			case "http://purl.org/spar/deo/Results": return "deo:Results";
			case "http://salt.semanticauthoring.org/ontologies/sro#Discussion": return "sro:Discussion";
			case "http://salt.semanticauthoring.org/ontologies/sro#Conclusion": return "sro:Conclusion";
			case "http://purl.org/spar/cito/cites": return "cites";
		}
		return "";
	};
	self.CheckRet = function(what){
		switch(what){
			case "http://purl.org/spar/deo/Introduction":
			case "http://www.w3.org/2004/02/skos/core#Concept":
			case "http://salt.semanticauthoring.org/ontologies/sro#Abstract":
			case "http://purl.org/spar/deo/Materials":
			case "http://purl.org/spar/deo/Methods":
			case "http://purl.org/spar/deo/Results":
			case "http://salt.semanticauthoring.org/ontologies/sro#Discussion":
			case "http://salt.semanticauthoring.org/ontologies/sro#Conclusion": return true;
		}
		return false;
	}
	self.DecodeRetorica = function(what){
		switch(what){
			case "http://purl.org/spar/deo/Introduction": return "Introduzione";
			case "http://www.w3.org/2004/02/skos/core#Concept":  return "Concetto";
			case "http://salt.semanticauthoring.org/ontologies/sro#Abstract": return "Astratto";
			case "http://purl.org/spar/deo/Materials": return "Materiale";
			case "http://purl.org/spar/deo/Methods": return "Metodo";
			case "http://purl.org/spar/deo/Results": return "Risultato";
			case "http://salt.semanticauthoring.org/ontologies/sro#Discussion": return "Discusione";
			case "http://salt.semanticauthoring.org/ontologies/sro#Conclusion":  return "Conclusione";
		}
		 return "Retorica";
	}
	self.CheckID = function(id){
		switch(id){
			case "deo:Introduction":
			case "deo:Introduction0": return "hasIntro";
			case "skos:Concept":
			case "skos:Concept0": return "hasConcept";
			case "sro:Abstract":
			case "sro:Abstract0": return "hasAbstr";
			case "deo:Materials":
			case "deo:Materials0": return "hasMateria";
			case "deo:Methods":
			case "deo:Methods0": return "hasMeth";
			case "deo:Results":
			case "deo:Results0": return "hasRes";
			case "sro:Discussion":
			case "sro:Discussion0": return "hasDisc";
			case "sro:Conclusion":
			case "sro:Conclusion0":	return "hasConc";
			case "cites": return "chasBody";
		}
		return id;
	}
	self.GetIndex = function(what){
		switch(what){
			case "hasTitle0": return 0;
			case "hasAuthor0": return 1;
			case "hasDOI0": return 2;
			case "hasPublicationYear0": return 3;
			case "hasURL0": return 4;
			case "hasComment0": return 5;
			case "deo:Introduction0": return 6;
			case "skos:Concept0": return 7;
			case "sro:Abstract0": return 8;
			case "deo:Materials0": return 9;
			case "deo:Methods0": return 10 ;
			case "deo:Results0": return 11;
			case "sro:Discussion0": return 12;
			case "sro:Conclusion0": return 13;
			case "cites1": case "cites0": return 14;
			case "hasTitle1": return 15;
			case "hasAuthor1": return 16;
			case "hasDOI1": return 17;
			case "hasPublicationYear1": return 18;
			case "hasURL1": return 19;
		}
		return "";
	};
	self.GetPrefixID = function(subject, id)	{
		var prefix = "";
		if(id.indexOf('form1') == 0 || id.indexOf('div1') == 0) return id;
		if(subject.indexOf("www.dlib.org") != -1)
			prefix = "dlib";
		else
			if(subject.indexOf("rivista-statistica.unibo.it") != -1)
				prefix = "RS";
			else
				if (subject.indexOf("almatourism.unibo.it") != -1)
					prefix = "AM";
				else
					if (subject.indexOf("antropologiaeteatro.unibo.it") != -1)
						prefix = "AT";
					else
						if (subject.indexOf("dlib.org/dlib/march14") != -1)
							prefix = "dlib";
							else
								if(subject.indexOf("http://eqa.unibo.it/") != -1)
									prefix = "eqa";


		id = Clear(id);
		switch(prefix)
		{
			case "dlib":
				return "form1_table3_tr1_td1_" + Crea(id.substring(id.indexOf("table5")));
			case "AT":
			case "AM":
			case "RS":
			case "eqa":
				return Crea(id.substring(id.indexOf("div1")));
		}
		function Clear(string){
			string = string.replace(/\[/g,'');
			string = string.replace(/]/g,'');
			string = string.replace(/\//g,'_');
			return string;
		}
		function Crea(string){
			var asd = string.split("_");
			for(var i = 0; i< asd.length; i++){
					if(isNaN(asd[i].slice(-1)))
						asd[i] += "1";
			}
			return asd.join("_");
		}
	}
	self.NoLiteralObject = function(what){
		switch(what){
			case "http://purl.org/spar/cito/cites":
			case "http://www.ontologydesignpatterns.org/cp/owl/semiotics.owl#denotes":
			case "http://purl.org/dc/terms/creator": return true;
		}
		return false;
	}
	self.SalvaTutto = function(){
		pData = {link: $("#URL").val()};
		var api =  new API();
		api.chiamaServizio({requestUrl: "pages/saveAll.php", data: pData, isAsync:true});
		$('#view').text("");
		document.getElementById("modalBoxView").style.display="none";
	}
	self.RemoveBlankSpan = function(id){
		$("span[name="+id+"]").each(function(){
			if (!$(this).text().trim().length && !($(this).hasClass("gn-icon-show"))) {
				$(this).remove();
			}
		});
	};
	self.TryScrap = function (arg) {
		if (arg.trim() == "") return ;
		var content = sessionStorage.getItem("ann");
		//$data = mb_convert_encoding($data, 'HTML-ENTITIES', "UTF-8");
		if(content.trim() == "")
			sessionStorage.setItem("ann",arg);
		else {
			var data = JSON.parse(arg);
			var vars = arg.split('|');
			var newvar = "";
			for (var i = 0; i < vars.length-1; i++) {
				var obj = JSON.parse(vars[i]);
				if (obj.azione.value != "D" && obj.predicate.value == data.predicate.value && obj.value.value == data.value.value && obj.start.value == data.start.value && obj.end.value == data.end.value )
					continue ;
				newvar += "|"+vars[i];
			}
			sessionStorage.setItem("ann",arg + newvar)
		}

	}

	return self;
}());

function Normalize(str){
var defaultDiacriticsRemovalMap = [
    {'base':'A', 'letters':/[\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F]/g},
    {'base':'AA','letters':/[\uA732]/g},
    {'base':'AE','letters':/[\u00C6\u01FC\u01E2]/g},
    {'base':'AO','letters':/[\uA734]/g},
    {'base':'AU','letters':/[\uA736]/g},
    {'base':'AV','letters':/[\uA738\uA73A]/g},
    {'base':'AY','letters':/[\uA73C]/g},
    {'base':'B', 'letters':/[\u0042\u24B7\uFF22\u1E02\u1E04\u1E06\u0243\u0182\u0181]/g},
    {'base':'C', 'letters':/[\u0043\u24B8\uFF23\u0106\u0108\u010A\u010C\u00C7\u1E08\u0187\u023B\uA73E]/g},
    {'base':'D', 'letters':/[\u0044\u24B9\uFF24\u1E0A\u010E\u1E0C\u1E10\u1E12\u1E0E\u0110\u018B\u018A\u0189\uA779]/g},
    {'base':'DZ','letters':/[\u01F1\u01C4]/g},
    {'base':'Dz','letters':/[\u01F2\u01C5]/g},
    {'base':'E', 'letters':/[\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E]/g},
    {'base':'F', 'letters':/[\u0046\u24BB\uFF26\u1E1E\u0191\uA77B]/g},
    {'base':'G', 'letters':/[\u0047\u24BC\uFF27\u01F4\u011C\u1E20\u011E\u0120\u01E6\u0122\u01E4\u0193\uA7A0\uA77D\uA77E]/g},
    {'base':'H', 'letters':/[\u0048\u24BD\uFF28\u0124\u1E22\u1E26\u021E\u1E24\u1E28\u1E2A\u0126\u2C67\u2C75\uA78D]/g},
    {'base':'I', 'letters':/[\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197]/g},
    {'base':'J', 'letters':/[\u004A\u24BF\uFF2A\u0134\u0248]/g},
    {'base':'K', 'letters':/[\u004B\u24C0\uFF2B\u1E30\u01E8\u1E32\u0136\u1E34\u0198\u2C69\uA740\uA742\uA744\uA7A2]/g},
    {'base':'L', 'letters':/[\u004C\u24C1\uFF2C\u013F\u0139\u013D\u1E36\u1E38\u013B\u1E3C\u1E3A\u0141\u023D\u2C62\u2C60\uA748\uA746\uA780]/g},
    {'base':'LJ','letters':/[\u01C7]/g},
    {'base':'Lj','letters':/[\u01C8]/g},
    {'base':'M', 'letters':/[\u004D\u24C2\uFF2D\u1E3E\u1E40\u1E42\u2C6E\u019C]/g},
    {'base':'N', 'letters':/[\u004E\u24C3\uFF2E\u01F8\u0143\u00D1\u1E44\u0147\u1E46\u0145\u1E4A\u1E48\u0220\u019D\uA790\uA7A4]/g},
    {'base':'NJ','letters':/[\u01CA]/g},
    {'base':'Nj','letters':/[\u01CB]/g},
    {'base':'O', 'letters':/[\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C]/g},
    {'base':'OI','letters':/[\u01A2]/g},
    {'base':'OO','letters':/[\uA74E]/g},
    {'base':'OU','letters':/[\u0222]/g},
    {'base':'P', 'letters':/[\u0050\u24C5\uFF30\u1E54\u1E56\u01A4\u2C63\uA750\uA752\uA754]/g},
    {'base':'Q', 'letters':/[\u0051\u24C6\uFF31\uA756\uA758\u024A]/g},
    {'base':'R', 'letters':/[\u0052\u24C7\uFF32\u0154\u1E58\u0158\u0210\u0212\u1E5A\u1E5C\u0156\u1E5E\u024C\u2C64\uA75A\uA7A6\uA782]/g},
    {'base':'S', 'letters':/[\u0053\u24C8\uFF33\u1E9E\u015A\u1E64\u015C\u1E60\u0160\u1E66\u1E62\u1E68\u0218\u015E\u2C7E\uA7A8\uA784]/g},
    {'base':'T', 'letters':/[\u0054\u24C9\uFF34\u1E6A\u0164\u1E6C\u021A\u0162\u1E70\u1E6E\u0166\u01AC\u01AE\u023E\uA786]/g},
    {'base':'TZ','letters':/[\uA728]/g},
    {'base':'U', 'letters':/[\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244]/g},
    {'base':'V', 'letters':/[\u0056\u24CB\uFF36\u1E7C\u1E7E\u01B2\uA75E\u0245]/g},
    {'base':'VY','letters':/[\uA760]/g},
    {'base':'W', 'letters':/[\u0057\u24CC\uFF37\u1E80\u1E82\u0174\u1E86\u1E84\u1E88\u2C72]/g},
    {'base':'X', 'letters':/[\u0058\u24CD\uFF38\u1E8A\u1E8C]/g},
    {'base':'Y', 'letters':/[\u0059\u24CE\uFF39\u1EF2\u00DD\u0176\u1EF8\u0232\u1E8E\u0178\u1EF6\u1EF4\u01B3\u024E\u1EFE]/g},
    {'base':'Z', 'letters':/[\u005A\u24CF\uFF3A\u0179\u1E90\u017B\u017D\u1E92\u1E94\u01B5\u0224\u2C7F\u2C6B\uA762]/g},
    {'base':'a', 'letters':/[\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250]/g},
    {'base':'aa','letters':/[\uA733]/g},
    {'base':'ae','letters':/[\u00E6\u01FD\u01E3]/g},
    {'base':'ao','letters':/[\uA735]/g},
    {'base':'au','letters':/[\uA737]/g},
    {'base':'av','letters':/[\uA739\uA73B]/g},
    {'base':'ay','letters':/[\uA73D]/g},
    {'base':'b', 'letters':/[\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253]/g},
    {'base':'c', 'letters':/[\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184]/g},
    {'base':'d', 'letters':/[\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A]/g},
    {'base':'dz','letters':/[\u01F3\u01C6]/g},
    {'base':'e', 'letters':/[\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD]/g},
    {'base':'f', 'letters':/[\u0066\u24D5\uFF46\u1E1F\u0192\uA77C]/g},
    {'base':'g', 'letters':/[\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F]/g},
    {'base':'h', 'letters':/[\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265]/g},
    {'base':'hv','letters':/[\u0195]/g},
    {'base':'i', 'letters':/[\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131]/g},
    {'base':'j', 'letters':/[\u006A\u24D9\uFF4A\u0135\u01F0\u0249]/g},
    {'base':'k', 'letters':/[\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3]/g},
    {'base':'l', 'letters':/[\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747]/g},
    {'base':'lj','letters':/[\u01C9]/g},
    {'base':'m', 'letters':/[\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F]/g},
    {'base':'n', 'letters':/[\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5]/g},
    {'base':'nj','letters':/[\u01CC]/g},
    {'base':'o', 'letters':/[\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275]/g},
    {'base':'oi','letters':/[\u01A3]/g},
    {'base':'ou','letters':/[\u0223]/g},
    {'base':'oo','letters':/[\uA74F]/g},
    {'base':'p','letters':/[\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755]/g},
    {'base':'q','letters':/[\u0071\u24E0\uFF51\u024B\uA757\uA759]/g},
    {'base':'r','letters':/[\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783]/g},
    {'base':'s','letters':/[\u0073\u24E2\uFF53\u00DF\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B]/g},
    {'base':'t','letters':/[\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787]/g},
    {'base':'tz','letters':/[\uA729]/g},
    {'base':'u','letters':/[\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289]/g},
    {'base':'v','letters':/[\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C]/g},
    {'base':'vy','letters':/[\uA761]/g},
    {'base':'w','letters':/[\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73]/g},
    {'base':'x','letters':/[\u0078\u24E7\uFF58\u1E8B\u1E8D]/g},
    {'base':'y','letters':/[\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF]/g},
    {'base':'z','letters':/[\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763]/g}
];
    for(var i=0; i<defaultDiacriticsRemovalMap.length; i++)
        str = str.replace(defaultDiacriticsRemovalMap[i].letters, defaultDiacriticsRemovalMap[i].base);
    return str;
}

function ToggleSibling(arg){
	var next= $(arg).next();
	next.slideToggle('fast');
}

function SearchID(id){
	if(id == null || id == undefined) return false;
	var a = id.split(" ");
	for(var i = 0; i < a.length; i++){
		switch(a[i]){
			case "hasAuthor0": case "hasAuthor1": case "hasPublicationYear0": case "hasPublicationYear1": case "hasTitle0": case"hasTitle1":
			case "hasDOI0": case "hasDOI1": case "hasURL0": case "hasURL1": case "hasComment0": case "hasComment1": case "cites0":
			case "hasIntro": case "hasConcept": case "hasAbstr": case "hasMateria": case "hasMeth": case "hasRes": case "hasDisc": case "hasConc":
			return true;
		}
	}
	return false;
}

function manualAnn() {
    var selezione = document.getSelection();
	if(selezione == "" || selezione == null || selezione.anchorNode == null || selezione.focusNode == null) {alert("Selezionare qualcosa."); return null;};
	var nodeStart = selezione.anchorNode.parentNode; //nodo nel quale � avvenuta la selezione
	var nodeEnd = selezione.focusNode.parentNode;	//nodo nel quale finisce la selezione
	var target = nodeStart.getAttribute('id');
	var nameElement = nodeStart.nodeName;
	var parentNode = nodeStart;
	var NodeToSearch = selezione.anchorNode;
	/*Se ci sono nodi in mezzo calcola*/
	var StartSearch = selezione.anchorNode;
	var boolForEnd = false;
	var StartOffset = 0;
	if(nodeStart != nodeEnd){
		parentNode = selezione.getRangeAt(0).commonAncestorContainer;
		target = parentNode.getAttribute('id');
		NodeToSearch = nodeEnd;
		if(selezione.anchorNode.nodeName == "#text") StartSearch = selezione.anchorNode.parentNode;
		StartOffset+= $(nodeStart).text().indexOf($(selezione.anchorNode).text());
	}
	else
		if(nodeStart.childNodes.length > 0){
			NodeToSearch = selezione.focusNode.previousSibling;
			boolForEnd = true;
			StartOffset+= $(nodeStart).text().indexOf($(selezione.anchorNode).text());
		}

	StartOffset += getOffset(parentNode, StartSearch, false);
	var EndOffset = getOffset(parentNode, NodeToSearch, boolForEnd);


	var start = selezione.anchorOffset + StartOffset;
	var end = start + selezione.toString().length + selezione.toString().split("\n").length - 1;
	if(start > end){
		var aux = start;
		start = end;
		end = aux;
	}
	var selected=selezione.toString();
	var autore = getCookie("email") != "" ? getCookie("email") : "http://server/unset-base/anonymus";
	while(SearchID(nodeStart.getAttribute('class')))
		nodeStart = nodeStart.parentNode;
	if(target == "" || target == undefined || target == null)
		target = nodeStart.getAttribute('id');
	var array = {
				id :{value: target},
				start :{value: start},
				end :{value: end},
				azione:{value:"I"},
				object: {value:selected},
				email : {value: autore},
				at :{value: timeGet()},
				label: {value : ""},
				subject : {value: ""},
				predicate: {value : ""},
				bLabel: {value: ""},
				key: {value:""},
				name: {value: getCookie("name")}
				};

	//var array = {azione:"i", body: {object:selected}, target:{id:target,
	//start:start, end:end}, annotazione:"" ,
	//provenance :{name: getCookie("name"),
	//email: getCookie("email"), time: timeGet()}};
	//var json = JSON.stringify(array);
	//var frammento=JSON.parse(json);
	return array;
	}

function annota(str, annotazione){  //str � l'array con i valori che ci servono
	str.annotazione=annotazione;	//aggiungiamo all'array l'annotazione che abbiamo fatto
	var pData = str;
	self.TryScrap(pData);

}

function AddClassBox(){
	$('select[name="inverse-dropdown"], select[name="inverse-dropdown-optgroup"], select[name="inverse-dropdown-disabled"]').select2({dropdownCssClass: 'select-inverse-dropdown'});
	$('select[name="searchfield"]').select2({dropdownCssClass: 'show-select-search'});
	$('select[name="inverse-dropdown-searchfield"]').select2({dropdownCssClass: 'select-inverse-dropdown show-select-search'});
	$('.tootlip.ann-shower').draggable();
}
function SelectBox(predicate, subject, object){
	var index = 0;
	if(subject != "" && subject != undefined && subject.indexOf("cited") != -1) index = 1;
	ob = $("#idDiMerda");
	if(predicate == undefined || predicate == "")
		predicate = "http://purl.org/dc/terms/title";
	if(predicate == "http://www.ontologydesignpatterns.org/cp/owl/semiotics.owl#denotes") predicate = object;
	var what = Scrap.Encode(predicate);
	ob.find("#iperSelector").val(what + index);
	ob.find("span.select2-chosen").text(ob.find("#iperSelector option").eq(Scrap.GetIndex(what+index)).text());
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length).replace("+", " ");
    }
    return "";
}
function timeGet(){
var currentDate = new Date();
var day = currentDate.getDate();
var month = currentDate.getMonth() + 1;
var year = currentDate.getFullYear();
return (day + "/" + month + "/" + year);
}

function getOffset(element, elToFind, end){
	var len = "";
	for(var i = 0; i < element.childNodes.length; i++){
		if(element.childNodes[i] != elToFind)	len += $(element.childNodes[i]).text();
		else
		{
			if(end) len += $(element.childNodes[i]).text();
			return len.length;
		}
	}
	return 0;
}

function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function ViewAnnotation(){
	var ann = sessionStorage.getItem('ann');
	if(ann == undefined || ann == ""){ alert("Annotare qualcosa, grazie."); return null;}
	else{
		if(typeof ann == "object") ann = [ann];
		else{
			ann = ann.replace(/\|/g, ",");
			ann = "[" + ann + "]";
			ann = JSON.parse(ann);
		}
		onSuccess(ann); //se control � 1 procedi con la visione della tabella di tutte le annotazioni
		/*else if(control2==1){
			var x=confirm("Hai eliminato tutte le annotazioni.\nVuoi eliminarle definitivamente?");
			if (r == true) { //ok
			}
			else { //annulla
			}

		}*/
		//else alert("Hai eliminato tutte le annotazioni.");
	}
}

function onSuccess(json){

	document.getElementById("modalBoxView").style.display="block";
	$('#view').append("<div class ='commnet-separator'>\
		<ul class ='edit-delete commnet-user'>\
		<li class ='gn-icon gn-icon-ann-ex' style='float: left'>Annotazioni non salvate</li>\
		<li style='float: right'>\
		<button id='exit' class='azzuro grey'>Esci</button>\
		</li>\
		<li style='float: right'>\
		<input id='save' class='azzuro green green1' type='button' value='Salva Tutto' onclick='Scrap.SalvaTutto()'>\
		</li>\
		</ul>\
		</div>\
		<div class ='allow-scroll commnet-desc'>\
		<div id='riepilogo_ann' class='mCustomScrollbar'>\
		</div>\
		</div>");
	for(i=0;i<json.length; i++){

		if(json[i].azione.value=="I"){
			if(  json[i].predicate.value=="http://www.ontologydesignpatterns.org/cp/owl/semiotics.owl#denotes" || json[i].predicate.value=="http://schema.org/comment"){
				$('#riepilogo_ann').append("<p id='ann_"+i+"'><span id='ann_"+i+"' style='color:green'>ANNOTAZIONE</span><br><br><b>Tipo</b>: <i>"+json[i].label.value+"</i><br><b>Annotazione</b>: <i>"+json[i].bLabel.value+"</i></p><span style='float:right'><button id='butt_ann_"+i+"'  class='azzuro red red1' onclick=elimina(\'butt_ann_"+i+"\',\'D\',\'ann_"+i+"\')>Elimina</button></span><br><br><hr>");
			}
			else{
				$('#riepilogo_ann').append("<p id='ann_"+i+"'><span id='ann_"+i+"' style='color:green' float:'left'>ANNOTAZIONE</span><br><br><b>Tipo</b>: <i>"+json[i].label.value+"</i><br><b>Testo selezionato</b>: <i>"+json[i].object.value+"</i><br><b>Annotazione</b>: <i>"+json[i].bLabel.value+"</i></p><span style='float:right'><button id='butt_ann_"+i+"'  class='azzuro red red1' onclick=elimina(\'butt_ann_"+i+"\',\'D\',\'ann_"+i+"\')>Elimina</button></span><br><br><hr>");   //quando faccio un' annotazione che contiene le virgolette si incazzava(ora non pi??
				}
		}
		else if(json[i].azione.value=="D"){
			if(  json[i].predicate.value=="http://www.ontologydesignpatterns.org/cp/owl/semiotics.owl#denotes" || json[i].predicate.value=="http://schema.org/comment"){
				$('#riepilogo_ann').append("<p id='ann_"+i+"'><span id='ann_"+i+"' style='color:red'>ANNOTAZIONE (eliminata)</span><br><br><b>Tipo</b>: <i>"+json[i].label.value+"</i><br><b>Annotazione</b>: <i>"+json[i].bLabel.value+"</i><span style='float:right'><button id='butt_ann_"+i+"' class='azzuro red red1'  onclick=ripristina(\'butt_ann_"+i+"\',\'I\',\'ann_"+i+"\')>Ripristina</button></span><br><br><hr>");
			}
			else{
				$('#riepilogo_ann').append("<p id='ann_"+i+"'><span id='ann_"+i+"' style='color:red'>ANNOTAZIONE (eliminata)</span><br><br><b>Tipo</b>: <i>"+json[i].label.value+"</i><br><b>Testo selezionato</b>: <i>"+json[i].object.value+"</i><br><b>Annotazione</b>: <i>"+json[i].bLabel.value+"</i><span style='float:right'><button id='butt_ann_"+i+"' class='azzuro red red1' onclick=ripristina(\'butt_ann_"+i+"\',\'I\',\'ann_"+i+"\')>Ripristina</button></span><br><br><hr>");   //quando faccio un' annotazione che contiene le virgolette si incazzava(ora non pi??
				}
		}
		$("#butt_ann_"+i).attr("data-info", JSON.stringify(json[i]));
	} //chiude for

	$('#exit').click(function(){$('#view').text(""); document.getElementById("modalBoxView").style.display="none";}); //elimino la tabella senn??pend sempre
}
function elimina(id, azione, id_ann){ //non ho passato come parametro direttamente il file json perch� con onclick nel button "cancella" si incazzava-.//quando 									faccio un'annotazione che contiene le virgolette si incazza! //quindi ho passato l'id del bottone che ha come data-info il file json
		var x=confirm("Sicuro di voler eliminare l'annotazione?");
		if (x == true) {

			var json=$('#'+id).attr('data-info');
			var el = JSON.parse(json);
			el.azione = {value:azione};
			el.name = {value:getCookie("name")};
			el.email = {value:getCookie("email")};
			el.at = {value:timeGet()};
			self.TryScrap(el);
			$("span#"+id_ann).text("ANNOTAZIONE (eliminata)");
			$("span#"+id_ann).attr("style", "color:red");
			$("#"+id).text("Ripristina");
			$("#"+id).attr("onclick", "ripristina('"+id+"','I','"+id_ann+"')");
		}
		/*if (($('#riepilogo_ann').text()=="")){ //se hai cancellato l'ultima annotazione chiudi la tabella
			$('#view').text("");
			document.getElementById("modalBoxView").style.display="none";
		}*/
return 0;
}


function ripristina(id, azione, id_ann){ //non ho passato come parametro direttamente il file json perch� con onclick nel button "cancella" si incazzava-.//quando 									faccio un'annotazione che contiene le virgolette si incazza! //quindi ho passato l'id del bottone che ha come data-info il file json
		var json=$('#'+id).attr('data-info');
		var el = JSON.parse(json);
		el.azione = {value:azione};
		el.name = {value:getCookie("name")};
		el.email = {value:getCookie("email")};
		el.at = {value:timeGet()};
		self.TryScrap(el);
	//	$("span#OpenedSpan").next().contents().unwrap(); 	// non lo trova, ci vorrebbe un collegamento tra l'occhio e la tabella delle annotazioni...data-info??
	//	$("span#OpenedSpan").remove();
		$("span#"+id_ann).text("ANNOTAZIONE (ripristinata)");
		$("span#"+id_ann).attr("style", "color:yellow");
		$("#"+id).text("Elimina");
		$("#"+id).attr("onclick", "elimina('"+id+"','D','"+id_ann+"')");

		/*if (($('#riepilogo_ann').text()=="")){ //se hai cancellato l'ultima annotazione chiudi la tabella
			$('#view').text("");
			document.getElementById("modalBoxView").style.display="none";
		}*/
return 0;
}

function modificaPosizione(){ //per il pulsante modifica: farlo uscire solo quando si clicca modifica posizione, e poi dal momento in cui si fa la nuova selezione al 									momento in cui si clicca modifica si dovrebbe fare che non si pu� fare nient'altro senn� sballa le annotazioni magari, boh!!!
	var css = document.createElement("style");
	css.type = "text/css";
	css.innerHTML = "#gn-menu{ opacity: 0.7; pointer-events:none;} #filter-menu{opacity: 0.7; pointer-events:none;} .content2{box-shadow: 0 0 20px 20px red ;} .content2:hover{box-shadow: 0 0 20px 4px red ;}";
	document.body.appendChild(css);
	var old=$('.gn-icon-show').attr("onclick");  //salvo vecchio valore onclick
	$('.gn-icon-show').attr("onclick", null); 	//evito il click sull'occhiolino mentre selezioni senn� fa casini
	document.getElementById("modalBox").style.display="none";


	$('.content2').first().click(function(){
					var str=manualAnn();
					if(str.object.value!=""){
					css.innerHTML ="";
					str=JSON.stringify(str);
					var json=JSON.parse(str);
					var neWelements = {id:{value:json.id.value},start:{value:json.start.value},end:{value:json.end.value},object:{value:json.object.value}};
					$("p#testo_selezionato").attr("data-info", JSON.stringify(neWelements));
					$('#testo_selezionato').text(json.object.value);
					document.getElementById("modalBox").style.display="block";
					$('.content2').first().unbind("click"); //disaccoppio il click al .content2
					$('.gn-icon-show').attr("onclick", old);
				}
		});
}
