<?php
    if(session_id()=="")session_start(); //session_id() vraća ID sesije za trenutnu sesiju ili prazan niz ("") ako ne postoji trenutna sesija
										//session_start() obavezno na početku koda, kako bi se sesija mogla koristiti 


	include_once("zaglavlje.php"); //uključivanje skripte zaglavlje.php, koristimo include_once kako bi izbjegli višestruko uključivanje koda
	include_once("bp.php"); //uključivanje skripte bp.php, koristimo include_once kako bi izbjegli višestruko uključivanje koda
	$veza = spajanjeNaBazu(); //pozivamo funkciju spajanjeNaBazu koja je definirana u skripti bp.php, i rezultat te funkcije spremamo u varijablu $veza
	
	$upit = "SELECT slika.korisnik_id, slika.slika_id, slika.naziv AS naziv, korisnik.prezime, korisnik.ime, slika.datum_vrijeme_slikanja AS datum, slika.url, planina.opis 
			 FROM planina, korisnik, slika 
			 WHERE slika.planina_id=planina.planina_id 
			 AND slika.korisnik_id=korisnik.korisnik_id AND slika.status=1";
		  if(!isset($_GET['reset'])){ //uvjetom if provjeravamo da korisnik nije stisnuo gumb "poništi", reset je argument funkcije isset
			  if(isset($_GET['naziv'])){ //ako nije stisnuo, provjeravamo je li postavljen naziv i to preko superglobalne varijable $_GET, koja je vidljiva bilo gdje u skripti, naziv je argument funkcije isset
				$planina = mysqli_real_escape_string($veza, $_GET['naziv']); /*mysqli_real_escape_string je ugrađena funkcija u PHP-u koja se koristi za izbjegavanje svih posebnih znakova za korisštenje u SQL upit
																			 $veza je objekt koji predstavlja vezu s poslužiteljem, a niz naziv kojeg dobivamo preko sperglobalne varijable je niz u kojem se trebaju izbjeći posebni znakovi */
				$upit = $upit." AND (planina.naziv like '%$planina%')"; /* . je operator spajanja stringova (a .= je operator za pridruživanje stringova), 
																			pomoću operatora LIKE možemo provjeriti podudaranje uzorka, koristeći zamjenske znakove)*/
				}
			  if(isset($_GET['vrijemeOd'])&& strlen($_GET['vrijemeOd']>0)){ //funkcija strlen vraća duljinu niza (u bajtovima) i vraća 0 ako je niz prazan
				$vrijemeOd = strtotime($_GET['vrijemeOd']); //funkcija strtotime analizira engleski tekstualni datum i vrijeme u unix vremensku oznaku
				$vrijemeOd = date('Y-m-d H:i:s', $vrijemeOd); //funkcija date formatira lokalni datum i vrijeme i vraća formatirani datumski niz
				$upit=$upit." AND datum_vrijeme_slikanja > '$vrijemeOd'";
				}	
			  if(isset($_GET['vrijemeDo'])&&strlen($_GET['vrijemeDo']>0)){
				$vrijemeDo = strtotime($_GET['vrijemeDo']);
				$vrijemeDo = date('Y-m-d H:i:s', $vrijemeDo);
				$upit=$upit." AND datum_vrijeme_slikanja IS NOT NULL AND datum_vrijeme_slikanja < '$vrijemeDo'"; // is not null se u sqlu koristi za testiranje vrijednosti koja nije NULL, zatim vraća true ako se pronađe vrijednost koja nije null, u suprotnom se vraća false
				}
			  if(isset($_GET['prezime'])) {
				$prezime=mysqli_real_escape_string($veza, $_GET['prezime']);
				$upit=$upit." AND (korisnik.prezime like '%$prezime%')";
				}
		  }
		   $upit = $upit." ORDER BY datum_vrijeme_slikanja DESC ";
		   $rezultat = izvrsiUpit($veza, $upit);
?>
<body>
	<div id="sadrzaj_stranice"> <!--oznaka div deifnira odjeljak u htmlo dokumentu, koristi se kao spremnik za html elemente, div oznaka ima jednoznačni selektor pod nazivom sadrzaj_stranice -->
		<h1>HRVATSKE PLANINE</h1>
		<img src="./images/planine.jpg" alt="planine" class="planina"> <!--relativna putanja-->
	</div>
		<h2>Dobro došli u galeriju javnih slika Hrvatskih planina</h2>
		<p>Možete filtrirati podatke na temelju naziva planine i/ili vremenskog razdoblja slikanja. Vremensko razdoblje se definira datumom i vremenom od - do (datum je potrebno unesti u formatu dd.mm.yyyy.)</p>
	<form id="filtriranje" method="GET" action="index.php">
				<label style="margin-left:20px;" for="naziv">Naziv planine: </label> 
					<input type="text" name="naziv" id="naziv" value="<?php if(isset($_GET['naziv'])&&!isset($_GET['reset']))echo $_GET['naziv']; ?>"/>
				<label style="margin-left: 20px;" for="vrijemeOd">Vremensko razdoblje slikanja od </label>
					<input type="text" name="vrijemeOd" id="vrijemeOd" value="<?php if(isset($_GET['vrijemeOd'])&&!isset($_GET['reset']))echo $_GET['vrijemeOd']; ?>"/>
				<label for="vrijemeDo">do</label>
					<input type="text" name="vrijemeDo" id="vrijemeDo" value="<?php if(isset($_GET['vrijemeDo'])&&!isset($_GET['reset']))echo $_GET['vrijemeDo']; ?>"/>
				
				<input class="buttons" type="submit" name="submit"  value="Filtriraj" />
				<input class="buttons" type="submit" name="reset"   value="Poništi" />
	 </form>
	 <table class="galerija_slika">
		<thead>
			<tr>
				<th></th>
				<th>Naziv planine</th>
				<th>Datum i vrijeme slikanja</th>
			</tr>
		</thead>
		<tbody>
			  <?php while(list($id, $slikaId, $naziv, $ime, $prezime, $datum, $url, $opis)=mysqli_fetch_array($rezultat)){ //petljom while se prolazi po redcima vraćenog odgovora, a funkciju list koristimo za prijenos vrijednosti iz niza u varijable
					$datum = strtotime($datum);
					$datum = date("d.m.Y. H:i:s", $datum);
						echo "
							<tr>
								<td><a href=\"info_slike.php?id={$slikaId}\"><img id=\"slika\" src=\"{$url}\" width=\"200\" alt={$naziv}></a></td>
								<td>$naziv</td>
								<td>$datum</td>
							</tr>";
					} 
				?>	
		</tbody>
	 </table>
  </body>
</html>
<?php
	zatvaranjeVezeNaBazu($veza);
	include("podnozje.php");
?>



	