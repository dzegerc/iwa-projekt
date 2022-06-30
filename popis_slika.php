<?php
	include_once("zaglavlje.php");
	$veza =spajanjeNaBazu();
	
	$upit = "SELECT blokiran FROM korisnik WHERE korisnik_id={$_SESSION['aktivni_korisnik_id']}";
	$rezultat = izvrsiUpit($veza,$upit);
	list($blokiran)=mysqli_fetch_array($rezultat);
	if(!isset($_SESSION['aktivni_korisnik_id'])){
        header("Location:index.php");
        exit();
	  }

	
	 if(isset($_SESSION['aktivni_korisnik_id']) && $blokiran!='1') {
        echo "
			<div class=\"dodaj\">
				<a href=\"dodaj_uredi_sliku.php\">Dodaj sliku</a>
			</div>";
	  }
?>
<?php
	$upit ="SELECT slika.slika_id, slika.korisnik_id, planina.naziv AS planinaNaziv, korisnik.ime, korisnik.prezime, slika.datum_vrijeme_slikanja AS datum, slika.url, planina.opis, slika.status, planina.planina_id 
			FROM planina, korisnik, slika 
			WHERE planina.planina_id=slika.planina_id AND korisnik.korisnik_id=slika.korisnik_id";
	
	if(isset($_GET['planinaNaziv'])) {
        $planina = mysqli_real_escape_string($veza, $_GET['planinaNaziv']);
		$upit = $upit." AND (planina.naziv like '%$planina%')";
    }
	
	$upit = $upit." ORDER BY datum_vrijeme_slikanja DESC ";
    $rezultat = izvrsiUpit($veza, $upit);
?>
<table class="galerija_slika">
	<p class="naslov">
		<?php 
				if(isset($_SESSION['aktivni_korisnik_id'])){
					if($_SESSION['aktivni_korisnik_tip']=='0'){
						echo "Sve slike u galeriji:";
					} else {
						echo "Moje slike u galeriji:";
					}
				}
			?>
		</p>
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
		<?php
			 if(isset($_SESSION['aktivni_korisnik_id'])){
				if($_SESSION['aktivni_korisnik_tip']=='0'){
					while(list($id, $korisnikId, $naziv, $ime, $prezime, $datum, $url, $opis, $status, $planinaId)=mysqli_fetch_array($rezultat)){
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
             }
				if($_SESSION['aktivni_korisnik_tip']=='1'){
					while(list($id, $korisnikId, $naziv, $ime, $prezime, $datum, $url, $opis, $status, $planinaId)=mysqli_fetch_array($rezultat)){
						$datum=strtotime($datum);
						$datum=date("d.m.Y. H:i", $datum);
						if($korisnikId==$_SESSION['aktivni_korisnik_id']){
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
              }
			}
			  else {
				while(list($id, $korisnikId, $naziv, $ime, $prezime, $datum, $url, $opis, $status, $planinaId)=mysqli_fetch_array($rezultat)){
					$datum=strtotime($datum);
					$datum=date("d.m.Y. H:i", $datum);
					if($korisnikId==$_SESSION['aktivni_korisnik_id']){
							echo "
								<tr>
									<td><a href=\"info_slike.php?id={$id}\"><img id=\"slika\" src=\"{$url}\" width=\"200\" alt={$naziv}></a></td>
									<td>$naziv</td>
									<td>$datum</td>
									<td>$status</td>
									<td>$prezime "." $ime</td>
									<td><div class=\"uredi\"><a href=\"dodaj_uredi_sliku.php?slika=$id&planinaId=$planinaId\">Uredi</a></div></td>
								</tr>";
				 }
			   }
			}
         }
?>
	</tbody>
</table>
<?php
	zatvaranjeVezeNaBazu($veza);
	include("podnozje.php");
?>
