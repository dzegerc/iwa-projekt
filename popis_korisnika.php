<?php
	if(session_id()=="")session_start();
	include_once("zaglavlje.php");
	$veza = spajanjeNaBazu();
	
	if (isset($_SESSION['aktivni_korisnik_id'])){
        if($_SESSION['aktivni_korisnik_tip']!='0'){
            header("Location:index.php");
            exit();
        }
    }
	if(isset($_SESSION['aktivni_korisnik_id'])) {
        if($_SESSION['aktivni_korisnik_tip']=='0'){
            echo "
				<div class=\"dodaj\">
					<a href=\"dodaj_uredi_korisnika.php\">Dodaj korisnika</a>
				</div>
				<div class=\"dodaj\">
					<a href=\"blokirani_korisnici.php\">Blokirani korisnici</a>
				</div>";
        } 
		
    }
		$upit = "SELECT * FROM korisnik ORDER BY korisnik_id ASC";
		$rezultat = izvrsiUpit($veza,$upit);
?>
<table class="galerija_slika">
    <thead>
        <tr>
            <th>Ime</th>
            <th>Prezime</th>
            <th>E-mail</th>
            <th>Tip</th>
            <th>Blokiran</th>
            <th>Slika</th>
            <th></th>
        </tr>
    </thead>
	<tbody>
        <?php 
			while(list($korisnikId, $tipId, $korisnickoIme, $lozinka, $ime, $prezime, $email, $blokiran, $slika)=mysqli_fetch_array($rezultat)){
				echo "
					<tr>
						<td>$ime</td>
						<td>$prezime</td>
						<td>$email</td>
						<td>";
							switch($tipId){
								case "0":
									echo "Administrator";
									break;
								case "1": 
									echo "Voditelj";
									break;
								case "2": 
									echo "Korisnik";
									break;
							}
						echo "</td>
						<td>$blokiran</td>
						<td><img src='{$slika}' width='120' height='150'></td>
						<td><div class=\"uredi\"><a href=\"dodaj_uredi_korisnika.php?korisnik=$korisnikId\">Uredi</a></div></td>
				    </tr>";
        }?>
    </tbody>
</table>
<?php
	zatvaranjeVezeNaBazu($veza);
	include("podnozje.php");
?>

