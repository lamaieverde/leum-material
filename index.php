<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">
	<title>Raschietto</title>
	<link rel="stylesheet" type="text/css" href="css/normalize.css" /><link rel="stylesheet" type="text/css" href="css/demo.css" /><link rel="stylesheet" type="text/css" href="css/component.css" /><link rel="stylesheet" type="text/css" href="css/login.css" /><link rel="stylesheet" href="css/jquery.mCustomScrollbar.css" /><link href="css/bootstrap.css" rel="stylesheet"><link rel="stylesheet" href="css/jquery-ui.css"><link href="css/flat-ui.min.css" rel="stylesheet"><link rel="stylesheet" href="css/style.css">
	<!-- NOTE: The styles were added inline because Prefixfree needs access to your styles and they must be inlined if they are on local disk! -->
	<script src="js/prefixfree.min.js"></script>
	<script src="js/modernizr.custom.js"></script>
	<script src="js/classie.js"></script>
	<script src="js/gnmenu.js"></script>
	<script src="js/jquery-1.11.2.js"></script>
	<script src="js/api.js"></script>
	<script src="js/app.js"></script>
	<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/select2.full.min.js"></script>
	</head>
<body>
	<input type="text" name="URL" id="URL" style="display:none;">
	<input type="text" name="GRAPH" id="GRAPH" style="display:none;">
	<div class="container" id="container">
		<?php include('menu.php');?>
		<?php include('ann-menu.php');?>
		<div class="content">

			<div id="tabs" class="row">
				<div class="content2 col-md-offset-4 col-md-8">
					<?php include('home.php');?>
				</div>
			</div>
		</div>
	</div>
	<!-- <div align="center" style="color: white;"><p>©<span class="makers">L.E.U.M.</span> 2015</p></div> -->
	<div class="modal" id="myLoader"><?php include('loader.php');?></div>
	<div id="modalBox" class="modalBox" style="display:none;"></div>
	<div id="modalBoxView" class="modalBox" style="display:none;"><div id="view" class="ann-details ann-shower"></div></div>
	<a href="#0" class="cd-top">Top</a>
	<script src="js/back_to_top.js"></script>
<style type="text/css">
			/*GENERIC ONES*/
			@media all and (min-width: 1024px) {
				/*.content2{
					max-width: 67%;
			    	float: right;
				}*/
				#filter-list{
			  	display: none;
				}
			}
			@media all and (max-width: 1023px) {
			  .allow-scroll.gn-menu-wrapper.gn-open-all.latest_tweets{
			  	display: none;
			  }
			}
			@media screen and (max-width: 768px) {

			}
			@media screen and (max-width: 540px ){
				#change-target{
				 width: 5em;
				}
				.ann-details.ann-shower{
					min-width: 300px;
   					max-width: 400px;
				}
				#iperTextArea{
					width: 85%;
				}
				#imgLogo {
				    width: 15em;
				}

			}
			@media screen and (max-width:440px) {
			 .ann-details.ann-shower {
    			min-width: 50%;
    			max-width: 85%;
			}
			@media screen and (max-width:380px) {
				#change-target{
				DISPLAY: NONE;
				}
				.gn-icon.gn-icon-ann-edit,
				.gn-icon.gn-icon-ann-ex,
				.gn-icon.gn-icon-ann-title,
				.gn-icon.gn-icon-ann-autore,
				.gn-icon.gn-icon-ann-doi,
				.gn-icon.gn-icon-ann-annop,
				.gn-icon.gn-icon-ann-url,
				.gn-icon.gn-icon-ann-commento,
				.gn-icon.gn-icon-ann-retorica,
				.gn-icon.gn-icon-ann-cites
				{
					display: none;
				}
				#longLogo .content h2 {
					font-size: 1.3em;
				}

			}
			/*END GENERIC ONES*/
			@media all and (min-width: 1220px) {
				/*.content2{
					margin-left: 30%;
					float: left;
				}*/
			}
			@media screen and (max-width: 422px) {
				.gn-menu-wrapper.gn-open-all {
					-webkit-transform: translateX(0px);
					-moz-transform: translateX(0px);
					transform: translateX(0px);
					width: 100%;
				}

				.gn-menu-wrapper.gn-open-all .gn-scroller {
					width: 130%
				}
			}
</style>


</body>
<script type="text/javascript" src="js/readRDF.js"></script>
<script type="text/javascript" src="js/ann-menu.js"></script>
<script type="text/javascript" src="js/register.js">//script register che deve comparire nel app</script>
<script type="text/javascript">
var globalLoader = true;
new gnMenu(document.getElementById( 'gn-menu' ));
$('#hasTitle').change(function(){Scrap.ShowArray("hasTitle", this);});
$('#hasAuthor').change(function(){Scrap.ShowArray("hasAuthor",this);});
$('#hasDOI').change(function(){Scrap.ShowArray("hasDOI",this);});
$('#hasPublicationYear').change(function(){Scrap.ShowArray("hasPublicationYear",this);});
$('#hasURL').change(function(){Scrap.ShowArray("hasURL",this);});
$('#hasComment').change(function(){Scrap.ShowArray("hasComment",this);});
$('#hasIntro').change(function(){Scrap.ShowArray("deo:Introduction",this);});
$('#hasConcept').change(function(){Scrap.ShowArray("skos:Concept",this);});
$('#hasAbstr').change(function(){Scrap.ShowArray("sro:Abstract",this);});
$('#hasMateria').change(function(){Scrap.ShowArray("deo:Materials",this);});
$('#hasMeth').change(function(){Scrap.ShowArray("deo:Methods",this);});
$('#hasRes').change(function(){Scrap.ShowArray("deo:Results",this);});
$('#hasDisc').change(function(){Scrap.ShowArray("sro:Discussion",this);});
$('#hasConc').change(function(){Scrap.ShowArray("sro:Conclusion",this);});

$('#chasBody').change(function(){Scrap.ShowArray("cites", this, 0);});
$('#chasTitle').change(function(){Scrap.ShowArray("hasTitle", this, 1);});
$('#chasAuthor').change(function(){Scrap.ShowArray("hasAuthor",this, 1);});
$('#chasDOI').change(function(){Scrap.ShowArray("hasDOI",this, 1);});
$('#chasPublicationYear').change(function(){Scrap.ShowArray("hasPublicationYear",this, 1);});
$('#chasURL').change(function(){Scrap.ShowArray("hasURL",this, 1);});

// $('#MenuTrigger').on('touchstart', function(){Login.Remove();});
// document.querySelector( "#MenuTrigger" ).addEventListener( "click", function() {
//   this.classList.toggle( "active" );
// });
$(document).on('touchstart click', '#login-open', function(event){
	event.stopPropagation();
	event.preventDefault();
	if(event.handled !== true) {
		Login.Show();
		event.handled = true;
	} else {
		return false;
	}
});
	//Nascondiamo il login se si click fuori dalla form o dal botton
	$(document).mousedown(function (e){
		var container = $('#login-form');
		var btnLogin = $('#login-open');
		if (!container.is(e.target) && container.has(e.target).length === 0 && !btnLogin.is(e.target) && btnLogin.has(e.target).length === 0)
			Login.Remove();
	});

	$('#logout').on({click: function(){Login.LogOut();}});
	$('#login-button').on({click: function(){Login.Try();}});
	$('#salvaReg').on({click: function(){Singin.Try()}});
	$('#register-button').on({click: function(){
			//crea modale per registrazione
			$("#modalBox").show();
			$("#modalBox").append('<div id="modalReg" class="ann-details ann-shower" style ="display: block"><div class ="commnet-desc"><div class="login_1 login_2"><ul class="various-grid accout-login2 login_3"><form><li><input type="text" class="text" placeholder="Nome e Cognome" id="newname"><a class="icon user"></a></li><li><input type="password" placeholder="Password" id="newpass"><a class="icon lock"></a></li><li><input type="password" placeholder="Conferma Password" id="newconfpass"> <a class=" icon confirm"></a></li><li><input type="text" class="text" placeholder ="E-mail" id="newemail"><a class=" icon mail"></a></li></form></ul></div><span id="erroremail" class="gn-icon gn-icon-regalert">E-mail già registrato. Prova con una nuova</span></div><div class ="commnet-separator"><ul class ="edit-delete commnet-user"><li class="gn-icon gn-icon-register"></li><li style="display: table-cell;"><input style="position: absolute;right: 1em;" id="cancellaReg" class="azzuro grey grey1" type="button" onclick="Scrap.HideModal(\'modalReg\')" value="Anulla"></li><li style="display: table-cell;"><input style="position: absolute;right: 7em;"id="salvaReg" class="azzuro azzuro2" type="button" value="Registrati" onclick="Singin.Try()"></li></ul></div></div>');
		}});
	var Page = (function (){
		var self = {};
		self.Search = function(){
			//Cerca il link e se non esiste lo agiunge
			var link = document.getElementById("iptSearch");
			if(link.value.indexOf("http://") != -1 || link.value.indexOf("www.") != -1)
				self.makeSearch(link.value, "Search...", "1", "http://vitali.web.cs.unibo.it/raschietto/graph/ltw1516");
			else
				alert("Solo URI ammessi.");
			link.value = "";
		};
		self.makeSearch = function(link, titolo, scrap, from){
			$('#URL').val(link);
			$('#GRAPH').val(from);
			window.scrollTo(0,0);
			var myData = {link: link, StartScrapper: scrap};
			var str = "";
			var process1 = new API();
			process1.chiamaServizio({requestUrl: 'pages/GetPageOnly.php', data: myData, isAsync: true, callback: function(str){
				self.DisableCheckBox();
				self.WriteData(str);
			}});
			
			var process2 = new API();
			process2.chiamaServizio({requestUrl: 'pages/pageScrapper.php', data: myData, isAsync: true, loader: false, callback: function(str){
				readRDF.GetMenu();
				readRDF.GetData(from, link);
				self.Uncheck();
				self.EnableCheckBox();
			}});

		};
		self.GetData = function(link, titolo, scrap, from){
			$('#URL').val(link);
			$('#GRAPH').val(from);
			window.scrollTo(0,0);
			var myData = {link: link, StartScrapper: scrap};
			var api = new API();
			api.chiamaServizio({requestUrl: 'pages/GetPageOnly.php', data: myData, isAsync: true, callback: function(str){
				self.WriteData(str);
			}});
			readRDF.GetData(from, link);
			self.Uncheck();
		};
		self.WriteData = function (data){
			$(".content2").html(data);
		};
		self.DisableCheckBox = function(){
			var api =  new API();
			api.chiamaServizio({requestUrl: 'loader.php', isAsync: true, callback: function(str){
				$('.check-boxs').parent().append("<div class='modal' style='display:block' id='removeMePlease'>"+str+"</div>");
			}});
			
		}
		self.EnableCheckBox = function(){
			$('#removeMePlease').remove();
		}
		self.Uncheck = function(){
			$('.check-boxs input:checkbox').removeAttr('checked');
		}
		return self;
	}());
	//Caricamento menu
	window.onload = function() {readRDF.GetMenu();};

	var str=null;
	function AnnotaClick(){
		var str=manualAnn();
		if (str != null)
			Scrap.EditOpen(null, str, "I");
	};
	$('#save-ann').click(function() {var annotazione=$('#s').val();	annota(str,annotazione);});

	 window.onbeforeunload = function(){
	 	var ann = sessionStorage.getItem('ann');
	   if(!ann == undefined || !ann == "")
			return 'Ci sono modifiche non salvate!';
	 };
	 $(window).on('unload', function(){
	 	sessionStorage.setItem('ann', "");
	 });
$("#iptSearch").keypress(function(){
	if ( event.which == 13 ) {Page.Search();}
});
</script>
</html>
