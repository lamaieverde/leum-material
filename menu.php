<ul id="gn-menu" class="gn-menu-main">
	<li class="gn-trigger">
		<a class="gn-icon gn-icon-menu" id="MenuTrigger"><span></span></a>
		<nav class="allow-scroll gn-menu-wrapper">
			<div class="gn-scroller mCustomScrollbar" data-mcs-theme="minimal-dark">
				<ul class="gn-menu" id = "mainMenu">
					<li class="gn-search-item">
						<input placeholder="Cerca" type="search" class="gn-search" id="iptSearch">
						<a class="gn-icon gn-icon-search" href="#" onclick="Page.Search();"></a>
					</li>
					<li>
						<a class="gn-icon gn-icon-lib" onclick="ToggleSibling(this)">Libreria</a>
						<ul class="gn-submenu doc-annotati"style="display:none;">
						</ul>
					</li>
				</ul>
			</div><!-- /gn-scroller -->
		</nav>
	</li>
	<?php if(isset($_COOKIE["email"])){ ?>
	<li>
		<a class="gn-icon gn-icon-ann-wrapper-mobile"><button id="annota" class="codrops-icon codrops-icon-ann-add" onclick="AnnotaClick()">ANNOTA</button></a>
	</li>
	<li>
		<a class="gn-icon gn-icon-ann-wrapper-mobile"><button id="view-ann" class="codrops-icon codrops-icon-ann-see" onclick="ViewAnnotation()">GUARDA CAMBIAMENTI</button></a>
	</li>
	<?php } ?>
	<li id="filter-list">
		<a class="gn-icon gn-icon-ann-wrapper-mobile"><button id="filter" class="codrops-icon codrops-icon-ann-filter" onclick="ToggleFilter()">FILTRO</button></a>
	</li>
	<?php if(isset($_COOKIE["email"])){ ?>

	<?php } ?>
	<?php if(!isset($_COOKIE["email"])){ ?>
	<li  id="login-open">
		<a id="loginjohnny"class="codrops-icon codrops-icon-johnny-user login-open">Login</a>
	</li>
	<?php } else { ?>
	<li  id="logout">
		<a id="logoutjohnny" class="codrops-icon icon-fontawesome-exit ">Logout, <?php echo $_COOKIE["name"]; ?></a>
	</li>

	<?php } ?>
</ul>
<!--start-Login-Form-->
<div class="login-form initLogin" id="login-form">
	<div class="login">
		<div class="inset">

			<div class="login_1 login_2">
				<ul class="various-grid accout-login2 login_3">
					<form>
						<li>
							<input type="text" class="text" placeholder="E-mail" id="txtuser">
							<a class="icon user">
							</a>
						</li>
						<li>
							<input type="password" placeholder="Password" id="txtpass">
							<a class="icon lock">
							</a>
						</li>
					</form>
				</ul>
			</div>
			<div class="sign">
				<div class="submit">
					<input class="azzuro azzuro1" type="button" value="Login" id="login-button">
					<input class="azzuro azzuro2" type="button" value="Register" id="register-button">
				</div>
				<div class="clear"> </div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$( window ).ready(
	function (){
		var screenwidth =$( window ).width();
		if (screenwidth <=1023) {
			$(".content2").removeClass().addClass("content2 col-md-12")
		}
		if (screenwidth <=643) {
			$("#annota").empty();
			$("#view-ann").empty();
			$("#filter").empty();
			$("#loginjohnny").empty();
			$("#logoutjohnny").empty();
		};
		$( window ).resize(function() {
			screenwidth =window.innerWidth;
			if (screenwidth <=1023) {
				$(".content2").removeClass().addClass("content2 col-xs-12")
			}
			else {
				$(".content2").removeClass().addClass("content2 col-md-offset-4 col-md-8")
			}
			if (screenwidth <=689) {
				$("#annota").empty();
				$("#view-ann").empty();
				$("#filter").empty();
				$("#loginjohnny").empty();
				$("#logoutjohnny").empty();
			}
			else{
				$("#annota").text("ANNOTA");
				$("#view-ann").text("GUARDA CAMBIAMENTI");
				$("#filter").text("FILTRO");
				var CookieSet = getCookie("name");
				if (CookieSet == "") {
					$("#loginjohnny").text("Login");
				}
				else {
					$("#logoutjohnny").text("Logout, " + CookieSet);
				};
			}

		});

	});

</script>
