<?php
	include_once("zaglavlje.php");
	$veza =spajanjeNaBazu();
	
	$upit = "SELECT k.ime as ime, k.prezime as prezime, COUNT(*) as broj_slika 
			 FROM korisnik k, slika s WHERE status=1 and k.korisnik_id=s.korisnik_id 
			 GROUP BY k.korisnik_id ORDER BY k.prezime";
	$rezultat = izvrsiUpit($veza,$upit);
	
		if(!isset($_SESSION['aktivni_korisnik_id'])){
			header("Location: index.php");
			exit();
		}
?>
<table class="galerija_slika">
    <p class="naslov">Statistika javnih slika korisnika u galeriji</p>
    <thead>
        <tr>
            <th>Ime</th>
            <th>Prezime</th>
            <th>Broj slika u javnoj galeriji:</th>
        </tr>
    </thead>
    <tbody>
        <?php
			while(list($ime, $prezime, $brojSlika)=mysqli_fetch_array($rezultat)){
				echo "
					<tr>
						<td>$ime</td>
						<td>$prezime</td>
						<td>$brojSlika</td>
					</tr>";
        }?>
    </tbody>
</table>
<?php
	zatvaranjeVezeNaBazu($veza);
	include("podnozje.php");
?>