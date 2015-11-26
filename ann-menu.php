<!--ann-menu-filter-->
<div id="filter-menu" class ="allow-scroll gn-menu-wrapper gn-open-all latest_tweets ">
	<div class="gn-scroller mCustomScrollbar _mCS_1 mCS-autoHide" data-mcs-theme="minimal-dark" style="overflow: visible;">
		<div id="mCSB_1" class="mCustomScrollBox mCS-minimal-dark mCSB_vertical mCSB_outside" tabindex="0">
			<div id="mCSB_1_container" class="mCSB_container" style="position: relative; top: 0px; left: 0px;" dir="ltr">
				<div class ="check-boxs">
					<div width ="100%">
						<div class ="checkbox-grid-left">
							<ul class ="gn-menu ann">
								<li class ="gn-menu-slide-menu-w">
									<input id ="hasTitle" class="css-checkbox1" type="checkbox">
									<label for ="hasTitle" name="demo_lbl_1" class="css-label1"></label>
									<a class ="">Titolo</a>
								</li>
								<li class ="gn-menu-slide-menu-w">
									<input id ="hasAuthor" class="css-checkbox1" type="checkbox">
									<label for ="hasAuthor" name="demo_lbl_1" class="css-label1"></label>
									<a class ="">Autore</a>
								</li>
								<li class ="gn-menu-slide-menu-w">
									<input id ="hasDOI" class="css-checkbox1" type="checkbox">
									<label for ="hasDOI" name="demo_lbl_1" class="css-label1"></label>
									<a class ="">DOI</a>
								</li>
								<li class ="gn-menu-slide-menu-w">
									<input id ="hasPublicationYear" class="css-checkbox1" type="checkbox">
									<label for ="hasPublicationYear" name="demo_lbl_1" class="css-label1"></label>
									<a class ="">Anno di pubblicazione</a>
								</li>
								<li class ="gn-menu-slide-menu-w">
									<input id ="hasURL" class="css-checkbox1" type="checkbox">
									<label for ="hasURL" name="demo_lbl_1" class="css-label1"></label>
									<a class ="">URL</a>
								</li>
								<li class ="gn-menu-slide-menu-w">
									<input id ="hasComment" class="css-checkbox1" type="checkbox">
									<label for ="hasComment" name="demo_lbl_1" class="css-label1"></label>
									<a class ="">Commenti</a>
								</li>
								<li class ="gn-menu-slide-menu-w" id="gn-menu-slide-menu-retoric">
									<a class ="gn-icon gn-icon-opendown" id="gn-icon-open-main-retoric" onclick="ToogleSlideRetorica()">Retorica</a>
									<div id ="retoric-checkbox" style="display:none;">
										<!--style="display: none;"-->
										<ul class ="gn-menu retorica">
											<li>
												<input id ="hasIntro" class="css-checkbox1" type="checkbox">
												<label for ="hasIntro" name="demo_lbl_1" class="css-label1"></label>
												<a class ="">Introduzione</a>
											</li>
											<li>
												<input id ="hasConcept" class="css-checkbox1" type="checkbox">
												<label for ="hasConcept" name="demo_lbl_1" class="css-label1"></label>
												<a class ="">Concetto</a>
											</li>
											<li>
												<input id ="hasAbstr" class="css-checkbox1" type="checkbox">
												<label for ="hasAbstr" name="demo_lbl_1" class="css-label1"></label>
												<a class ="">Astratto</a>
											</li>
											<li>
												<input id ="hasMateria" class="css-checkbox1" type="checkbox">
												<label for ="hasMateria" name="demo_lbl_1" class="css-label1"></label>
												<a class ="">Materiali</a>
											</li>
											<li>
												<input id ="hasMeth" class="css-checkbox1" type="checkbox">
												<label for ="hasMeth" name="demo_lbl_1" class="css-label1"></label>
												<a class ="">Metodi</a>
											</li>
											<li>
												<input id ="hasRes" class="css-checkbox1" type="checkbox">
												<label for ="hasRes" name="demo_lbl_1" class="css-label1"></label>
												<a class ="">Risultati</a>
											</li>
											<li>
												<input id ="hasDisc" class="css-checkbox1" type="checkbox">
												<label for ="hasDisc" name="demo_lbl_1" class="css-label1"></label>
												<a class ="">Discussione</a>
											</li>
											<li>
												<input id ="hasConc" class="css-checkbox1" type="checkbox">
												<label for ="hasConc" name="demo_lbl_1" class="css-label1"></label>
												<a class ="">Conclusione</a>
											</li>
										</ul>
									</div>
								</li>
								<li class ="gn-menu-slide-menu-w" id="gn-menu-slide-menu-cites">
									<a class ="gn-icon gn-icon-opendown" id="gn-icon-open-main-cites" onclick="ToogleSlideCitazione()">Citazioni</a>
									<div id ="cites-checkbox" style="display:none;">
										<ul class ="gn-menu">
											<li>
												<input id ="chasBody" class="css-checkbox1" type="checkbox">
												<label for ="chasBody" name="demo_lbl_1" class="css-label1"></label>
												<a class ="">Frammento</a>
											</li>
											<li>
												<input id ="chasTitle" class="css-checkbox1" type="checkbox">
												<label for ="chasTitle" name="demo_lbl_1" class="css-label1"></label>
												<a class ="">Titolo</a>
											</li>
											<li>
												<input id ="chasAuthor" class="css-checkbox1" type="checkbox">
												<label for ="chasAuthor" name="demo_lbl_1" class="css-label1"></label>
												<a class ="">Autore</a>
											</li>
											<li>
												<input id ="chasDOI" class="css-checkbox1" type="checkbox">
												<label for ="chasDOI" name="demo_lbl_1" class="css-label1"></label>
												<a class ="">DOI</a>
											</li>
											<li>
												<input id ="chasPublicationYear" class="css-checkbox1" type="checkbox">
												<label for ="chasPublicationYear" name="demo_lbl_1" class="css-label1"></label>
												<a class ="">Anno di pubblicazione</a>
											</li>
											<li>
												<input id ="chasURL" class="css-checkbox1" type="checkbox">
												<label for ="chasURL" name="demo_lbl_1" class="css-label1"></label>
												<a class ="">URL</a>
											</li>
										</ul>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
