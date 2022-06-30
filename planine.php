<?php
	include_once("zaglavlje.php");
	$veza = spajanjeNaBazu();
	
	 if(isset($_SESSION['aktivni_korisnik_id'])){
        if($_SESSION['aktivni_korisnik_tip']=='0'){
			echo "
				<div class=\"dodaj\">
					<a href=\"dodaj_planinu.php\">Dodaj planinu</a>
				</div>
				<div class=\"dodaj\">
					<a href=\"popis_moderatora.php\">Popis moderatora</a>
				</div> ";
		}
		if($_SESSION['aktivni_korisnik_tip']=='2') {
            header("Location:index.php");
            exit();
        }
	 }	
		else {
			header("Location:index.php");
			exit();
		}
?>
<table class="popis_planina">
	<p class="naslov">
		<?php
			if(isset($_SESSION['aktivni_korisnik_id'])){
				if($_SESSION['aktivni_korisnik_tip']=='0'){
					echo "Popis svih planina";
					}
					else {
						echo "Popis mojih planina";
					}
			}
		?>
	</p>
	<thead>
		<tr>
			<th>Naziv planine:</th>
			<th>Opis:</th>
			<th>Lokacija:</th>
			<th>Geografska širina:</th>
			<th>Geografska dužina:</th>
		 </tr>
	</thead>
	<tbody>
	<?php
		if(isset($_SESSION['aktivni_korisnik_id'])){
			if($_SESSION['aktivni_korisnik_tip']=='0'){
				$upit = "SELECT planina.planina_id, planina.naziv, planina.opis, planina.lokacija, planina.geografska_sirina, planina.geografska_duzina 
						 FROM planina LEFT JOIN moderator ON planina.planina_id=moderator.planina_id";
				$rezultat = izvrsiUpit($veza, $upit);
					while(list($id, $naziv, $opis, $lokacija, $geografska_s , $geografska_d)=mysqli_fetch_array($rezultat)){
						echo "
							<tr>
								<td><a href=\"popis_slika.php?planinaNaziv=$naziv\">$naziv</a></td>
								<td>$opis</td>
								<td>$lokacija</td>
								<td>$geografska_s</td>
								<td>$geografska_d</td>
								<td><div class=\"uredi\"><a href=\"dodaj_planinu.php?planina=$id\">Uredi</a></div></td>
						    </tr>";
					}
			}
			else { 
				$upit = "SELECT planina.planina_id, planina.naziv, planina.opis, planina.lokacija, planina.geografska_sirina, planina.geografska_duzina 
						 FROM planina LEFT JOIN moderator ON planina.planina_id=moderator.planina_id 
						 WHERE moderator.korisnik_id={$_SESSION['aktivni_korisnik_id']}";
				$rezultat = izvrsiUpit($veza, $upit);
					while(list($id, $naziv, $opis, $lokacija, $geografska_s, $geografska_d)=mysqli_fetch_array($rezultat)){
						echo "
							<tr>
								<td><a href=\"galerija_slika.php?planinaNaziv=$naziv\">$naziv</a></td>
								<td>$opis</td>
								<td>$lokacija</td>
								<td>$geografska_s</td>
								<td>$geografska_d</td>
							</tr>";
				}
			}
		}
?>
		</tbody>
</table>
<?php
	include("podnozje.php");
?>