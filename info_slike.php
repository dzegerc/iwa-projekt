<?php 
	if(session_id()=="")session_start();
	include_once("zaglavlje.php");
	$veza = spajanjeNaBazu();
	
	$id = $_GET['id'];
	$upit = "SELECT slika.slika_id, slika.naziv, planina.naziv, korisnik.ime, korisnik.prezime, slika.url, slika.datum_vrijeme_slikanja, slika.opis
			 FROM planina, korisnik, slika WHERE planina.planina_id=slika.planina_id AND korisnik.korisnik_id=slika.korisnik_id AND slika.slika_id=$id";
	$rezultat = izvrsiUpit($veza,$upit);
	list($id, $slika_naziv, $naziv, $ime, $prezime, $url, $datum, $opis)=mysqli_fetch_array($rezultat);
		echo "
			<p class=\"slika_naziv\"> Informacije o slici: {$slika_naziv}</p>
				<div class=\"info_frame\">
					<div class=\"image\">
					<img src=\"{$url}\" width='270' height='170'  alt=\"{$naziv}\"/>
					</div>
				<div class=\"text\">
					<p><b>Planina u koju pripada:</b>$naziv</p>
					<p><b>Naziv:</b> <a href=\"index.php?naziv={$naziv}\">$slika_naziv</a></p>
					<p><b>Autor: </b><a class=\"link\" href=\"index.php?prezime={$prezime}\"> ".$prezime." </a> ".$ime."</p>
					<p><b>Opis: </b>$opis</p>
					</div>
				</div>";
?>
<?php
	include("podnozje.php");
?>






















