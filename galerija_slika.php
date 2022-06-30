<?php 
include_once("zaglavlje.php");
	include_once("bp.php");
	$veza = spajanjeNaBazu();

	if(isset($_SESSION['aktivni_korisnik_id'])) {
        $upit ="SELECT blokiran FROM korisnik WHERE korisnik_id={$_SESSION['aktivni_korisnik_id']}";
        $rezultat=izvrsiUpit($veza, $upit);
        list($blokiran)=mysqli_fetch_array($rezultat);
        }

        if(isset($_SESSION['aktivni_korisnik_id'])) {
            if($_SESSION['aktivni_korisnik_tip']=='1' || $_SESSION['aktivni_korisnik_tip']=='0'){
                if(isset($_GET['prezime'])){
                echo "
                    <div class=\"dodaj\">
					<a href=\"galerija_slika.php?korisnikId={$_GET['korisnikId']}&prezime={$_GET['prezime']}&blokiran=1\">Blokiraj korisnika</a>
                    </div>";
                } else {
                    echo "
                    <div class=\"dodaj\">
                        <a href=\"dodaj_uredi_sliku.php\">Dodaj sliku</a>
                    </div>";
                }
            } else if($blokiran!='1') {
                echo "<div class=\"dodaj\">
                         <a href=\"dodaj_uredi_sliku.php\">Dodaj sliku</a>
                    </div>";
            }
        }
            if(isset($_GET['blokiran'])){
                $prezime=$_GET['prezime'];
                $korisnikId=$_GET['korisnikId'];
                $blokiran=$_GET['blokiran'];
                $upit ="UPDATE korisnik SET blokiran = '$blokiran' WHERE korisnik_id='$korisnikId'";
                $rezultat =izvrsiUpit($veza, $upit);
                $upit ="UPDATE slika SET status = '0' WHERE korisnik_id='$korisnikId'";
                $rezultat =izvrsiUpit($veza, $upit);
                    header("location: galerija_slika.php");
            }
?>
<?php 
	 	$upit = "SELECT slika.slika_id, slika.korisnik_id, planina.naziv AS planinaNaziv, korisnik.prezime, korisnik.ime, slika.datum_vrijeme_slikanja AS datum, slika.url, planina.opis, slika.status, planina.planina_id
		 FROM planina, korisnik, slika WHERE planina.planina_id=slika.planina_id AND korisnik.korisnik_id=slika.korisnik_id AND slika.status=1";
			if(!isset($_GET['reset'])){
				if(isset($_GET['planinaNaziv'])){
					$planina = mysqli_real_escape_string($veza, $_GET['planinaNaziv']);
					$upit = $upit." AND (planina.naziv like '%$planina%')";
					echo "<h2>Galerija  slika za planinu: $planina</h2>";
				}
				if(isset($_GET['prezime'])) {
					$prezime = mysqli_real_escape_string($veza, $_GET['prezime']);
					$upit = $upit." AND (korisnik.prezime like '%$prezime%')";
					echo "<h2>Galerija slika korisnika: $prezime</h2>";
				}
			}
			   $upit = $upit." ORDER BY datum_vrijeme_slikanja DESC ";
			   $rezultat = izvrsiUpit($veza, $upit);
?>
 <table class="galerija_slika">
		<thead>
			<tr>
				<th></th>
				<th>Naziv planine</th>
				<th>Datum i vrijeme fotografiranja</th>
				<th>Status slike (0-privatna, 1-javna)</th>
				<th>Autor</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			  <?php while(list($id, $korisnikId, $naziv, $prezime, $ime, $datum, $url, $opis, $status, $planinaId)=mysqli_fetch_array($rezultat)){
					$datum = strtotime($datum);
					$datum = date("d.m.Y. H:i:s", $datum);
						echo "
						<tr>
							<td><a href=\"info_slike.php?id={$id}\"><img id=\"slika\" src=\"{$url}\" width=\"200\" alt={$naziv}></a></td>
							<td>$naziv</td>
							<td>$datum</td>
							<td>$status</td>
							<td><a class=\"link\" href=\"galerija_slika.php?korisnikId={$korisnikId}&prezime={$prezime}\"> ".$prezime." </a> ".$ime."</td>
							<td><div class=\"uredi\"><a href=\"dodaj_uredi_sliku.php?slika=$id&planinaId=$planinaId\">Uredi</a></div></td>
						</tr>";
					} 
				?>	
		</tbody>
	 </table>
<?php
	zatvaranjeVezeNaBazu($veza);
	include("podnozje.php");
?>



