<?php
	include_once("zaglavlje.php");
	$veza =spajanjeNaBazu();

        if(!isset($_SESSION['aktivni_korisnik_id'])){
			header("Location: prijava.php");
			exit();
		 }
		if($_SESSION['aktivni_korisnik_tip']=='1') {
			header("Location: prijava.php");
			exit();
		 }
?>
<?php 
	$error = "";
	if(isset($_POST['submit'])){
		foreach($_POST as $kljuc => $vrijednost){
			if(strlen($vrijednost)==0){
				$error = "Sva polja su obavezna!";
			}
		}
			if(empty($error)){
				$id = $_GET['planina'];
				$naziv = $_POST['naziv'];
				$opis = $_POST['opis'];
				$lokacija = $_POST['lokacija'];
				$geografska_s = $_POST['geografska_s'];
				$geografska_d = $_POST['geografska_d'];
				$korisnik_id = $_POST['moderator'];	
					if($id==0){
						$upit ="INSERT INTO planina (naziv, opis, lokacija, geografska_sirina, geografska_duzina) VALUES ('$naziv', '$opis', '$lokacija', '$geografska_s', '$geografska_d');";
					}
					else {
						$upit.= "UPDATE planina SET naziv = '$naziv', opis = '$opis', lokacija = '$lokacija', geografska_duzina = '$geografska_d', geografska_sirina = '$geografska_s',
								 WHERE planina_id='$id';";
						}
					izvrsiUpit($veza,$upit);
					header("Location:planine.php");
			}
		}
			if(isset($_GET['planina'])){
				$id = $_GET['planina'];
				$upit = "SELECT naziv, opis, lokacija, geografska_sirina, geografska_duzina FROM planina WHERE planina_id='$id'";
				$rezultat=izvrsiUpit($veza, $upit);
					list($naziv, $opis, $lokacija,  $geografska_s, $geografska_d)=mysqli_fetch_array($rezultat);
				}
				else {
					$naziv="";
					$lokacija="";
					$opis="";
					$geografska_s="";
					$geografska_d="";
					$korisnikId="";	
				}
				if(isset($_POST['delete'])){
					$upit = "DELETE FROM planina WHERE planina_id='$id'";
					$rezultat = izvrsiUpit($veza, $upit);
						header("Location:dodaj_planinu.php");
				}
				if(isset($_POST['reset'])){
					header("Location:dodaj_planinu.php");
				}
?>
<form action="<?php if(isset($_GET['planina']))echo "dodaj_planinu.php?planina=$id";else echo "dodaj_planinu.php";?>" method="POST">
		<p class="naslov">
			 <?php
                if(isset($_GET['planina']))echo "Uredi informacije o planini";
                else echo "Dodaj planinu";
            ?>
		</p>
		<input type="hidden" name="nova" value="<?php if(!empty($id))echo $id; else echo 0; ?>"/>
			<label class="error"><?php if($error!="")echo $error; ?></label>
		<div class="dodaj_sliku">
			<label for="naziv"><strong>Naziv planine:</strong></label>
				<input type="text" name="naziv" id="naziv"  value="<?php if(!isset($_POST['naziv']))echo $naziv; else echo $_POST['naziv']; ?>"/>
			<br>
            <label for="opis"><strong>Opis:</strong></label>
				<input type="text" name="opis" id="opis" value="<?php if(!isset($_POST['opis']))echo $opis; else echo $_POST['opis']; ?>"/>
           <br>
           <label for="lokacija"><strong>Županija u kojoj se nalazi planina:</strong></label>
				<input type="text" name="lokacija" id="lokacija" value="<?php if(!isset($_POST['lokacija']))echo $lokacija; else echo $_POST['lokacija']; ?>"/>
		   <br>
           <label for="geografska_s"><strong>Geografska širina:</strong></label>
                <input type="number" name="geografska_s" id="geografska_s" step="any" value="<?php if(!isset($_POST['geografska_s']))echo $geografska_s; else echo $_POST['geografska_s']; ?>"/>
		   <br>
           <label for="geografska_d"><strong>Geografska dužina:</strong></label>
                <input type="number" name="geografska_d" id="geografska_d" step="any" value="<?php if(!isset($_POST['geografska_d']))echo $geografska_d; else echo $_POST['geografska_d']; ?>"/>
           <br>
		   <?php
				if(isset($id) && !empty($id)){
					echo '<input class="slika_btn" style="width: 100px;" type="submit" name="submit" value="Uredi"/>
                          <input class="slika_btn" style="width: 100px;" type="submit" name="delete" value="Obriši planinu"/>';
                    } 
					else {
						echo '<input class="slika_btn" style="width: 100px;" type="submit" name="submit" value="Dodaj"/>
							  <input class="slika_btn" style="width: 100px;" type="submit" name="reset" value="Očisti"/>';
                    }
			?>
	</div>
</form>
<?php
	zatvaranjeVezeNaBazu($veza);
	include("podnozje.php");
?>
