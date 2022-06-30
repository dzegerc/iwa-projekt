<?php
	include_once("zaglavlje.php");
	$veza = spajanjeNaBazu();

    if(isset($_SESSION['aktivni_korisnik_id'])){
        if($_SESSION['aktivni_korisnik_tip']=='0'){
			echo "
				<div class=\"dodaj\">
					<a href=\"dodaj_moderatora.php\">Dodaj moderatora</a>
				</div> ";
		} else {
            header("Location:index.php");
            exit();
        }
	 }	
		else {
			header("Location:index.php");
			exit();
		}

        $upit = "SELECT korisnik.korisnik_id, korisnik.ime, korisnik.prezime, planina.naziv, planina.planina_id 
                 FROM korisnik LEFT JOIN moderator ON korisnik.korisnik_id=moderator.korisnik_id 
                 LEFT JOIN planina ON moderator.planina_id=planina.planina_id 
                 WHERE planina.naziv IS NOT NULL ORDER BY planina.naziv ASC";
        $rezultat = izvrsiUpit($veza, $upit);
?>

<table class="popis_planina">
	<p class="naslov">Popis moderatora</p>
    <thead>
		<tr>
			<th>Ime i prezime:</th>
			<th>Planina:</th>
		 </tr>
	</thead>
    <tbody>
        <?php
            while(list($korisnikId, $ime, $prezime, $naziv, $planinaId)=mysqli_fetch_array($rezultat)){
                            echo "
                                <tr>
                                    <td>$ime "." $prezime</td>
                                    <td>$naziv</td>
                                </tr>";
                        }
        ?>
	</tbody>
</table>
<?php
	include("podnozje.php");
?>