<div id="longLogo">
	<img id="imgLogo" src="img/logoRaschetto.svg">
	<h2>Applicazione per l'annotazione semantica</h2>
	<div class="content">
		<h2>
			Adesso puoi annotare articoli delle diverse riviste a disposizione e guardare quello che hanno fatto gli altri utenti.
		</h2>
		<p>
			Si può accedere ai articoli preferiti  dalla sezione a sinistra o usando la ricerca<span class="gn-icon gn-icon-search"></span>. Sempre a sinistra si trova la sezione filtro <span class="codrops-icon codrops-icon-ann-filter"></span>
			che ti permete in ogni momento la posibilita di vedere le annotazioni a scelta.
		</p>
		<p>
			Le annotazioni già esistente gli distingui così:
			<table align="center">
				<tr>
					<td class="hasTitle0">
						Titolo
					</td>
					<td class="hasIntro">
						Introduzione
					</td>
				</tr>
				<tr>
				    <td class="hasAuthor0">
						Autore
					</td>
					<td class="hasConcept">
						Concetto
					</td>
				</tr>
				<tr>
					<td class="hasDOI0">
						Doi
					</td>
					<td class="hasAbstr">
						Astratto
					</td>
				</tr>
				<tr>
					<td class="hasURL0">
						URL
					</td>
					<td class="hasMateria">
						Materiali
					</td>					
				</tr>
				<tr>
					<td class="hasComment0">
						Commenti
					</td>
					<td class="hasMeth">
						Metodi
					</td>
				</tr>
				<tr>
					<td class="cites0">
						Frammenti di citazione
					</td>
					<td class="hasRes">
						Risultati
					</td>
				</tr>
				<tr>
				<td class="hasDisc">
						Discussione
					</td>
					<td class="hasConc">
						Conclusione
					</td>
				</tr>
			</table>
		</p>
		<?php if(isset($_COOKIE["email"])){ ?>
		<p>
			Con il pulsante <span class="codrops-icon codrops-icon-ann-add"></span> si può creare un annotazione nuova e nel pannello <span class="codrops-icon codrops-icon-ann-see"></span> Guarda Annotazione poi gestire le nuove modifiche.
		</p>
		<?php } ?>
		<p style="font-size: 1.6em;margin-top: 19%;margin-bottom: -15%;">
			Realizzato dal team <span class="makers">L.E.U.M.</span> che ha come componenti <span class="makers">antonio LAGANA</span>, <span class="makers">florin EPUREANU</span> e <span class="makers">ion URSACHI</span>.
		</p>
	</div>
</div>