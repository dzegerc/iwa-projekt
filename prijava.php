<?php
	include_once("zaglavlje.php");
	$veza =spajanjeNaBazu();
?>
<?php
	if(isset($_GET["odjava"])){ //ukoliko je odjava postavljena tada se sesija brise
		unset($_SESSION["aktivni_korisnik"]); //sa unset brisemo vrijednost pod tim kljucem "aktivni_korisnik"
        unset($_SESSION["aktivni_korisnik_ime"]);
		unset($_SESSION["aktivni_korisnik_tip"]);
        unset($_SESSION["aktivni_korisnik_id"]);
		session_destroy();
		header("Location:prijava.php");
	}

	if(isset($_POST["submit"])){
		$error = "";
        if(isset($_POST["kIme"]) && !empty($_POST["kIme"]) && isset($_POST["lozinka"]) && !empty($_POST["lozinka"])) {
			$kIme = $_POST["kIme"];
			$lozinka = $_POST["lozinka"];
			$upit="SELECT korisnik_id, tip_korisnika_id, ime, prezime 
				  FROM korisnik 
				  WHERE korisnicko_ime='$kIme' 
				  AND lozinka='$lozinka'";
		    $rezultat=izvrsiUpit($veza,$upit);
			if(mysqli_num_rows($rezultat)!=0) {  //mysqli_num_rows vraća broj redaka u skupu reztultata
                list($id, $tip, $ime, $prezime)=mysqli_fetch_array($rezultat);
                $_SESSION["aktivni_korisnik"] = $kIme;
				$_SESSION["aktivni_korisnik_tip"] = $tip;
                $_SESSION["aktivni_korisnik_id"] = $id;
				
				if(isset($_SESSION['aktivni_korisnik_id'])){
					if($_SESSION['aktivni_korisnik_tip']!='0' && $_SESSION['aktivni_korisnik_tip']!='1'){
                        $_SESSION["aktivni_korisnik_ime"] = $ime . " " . $prezime;
					}
					else {
						$_SESSION["aktivni_korisnik_ime"] = $ime;
					}
				}
					header("Location:index.php");
					exit();
					}
					else {
						$error = "Korisničko ime i/ili lozinka se ne podudaraju!";
					}
				}
					else {
						$error = "Potrebno je unesti tražene podatke!";
					}
				}
?>
<h2>Prijava u sustav Hrvatske planine</h2>
<form class="prijava"  action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
	<label  for="kIme"><strong>Korisničko ime:</strong></label>
		<input type="text" id="kIme" name="kIme"/>
		<br>
		<br>
	<label for="lozinka"><strong>Lozinka:</strong></label>
		<input type="password" id="lozinka" name="lozinka"/>
		<br>
	<input class="button_prijava" type="submit" name="submit" value="Prijavi se"/>
	<div>
		<?php 
			if (isset($error)) {
                 echo "<p style=\"color:red; text-align:center;\">$error</p>";
                }
		?>
	</div>	
</form>
<?php
	zatvaranjeVezeNaBazu($veza);
?>