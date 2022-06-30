<?php
	include_once("zaglavlje.php");
	$veza = spajanjeNaBazu();
	
	  if (isset($_SESSION['aktivni_korisnik_id'])){
        if($_SESSION['aktivni_korisnik_tip']!='0'){
            header("Location:index.php");
            exit();
        }
	  }
	$error = "";
	 if(isset($_POST['submit'])){
        foreach($_POST as $kljuc => $vrijednost){
            if(strlen($vrijednost)==0){
                $error = "Sva polja za unos su obavezna";
            }
        }
		if(empty($error)) {
            $id = $_GET['korisnik'];
            $ime = $_POST['ime'];
            $prezime = $_POST['prezime'];
            $korisnickoIme = $_POST['korisnickoIme'];
            $lozinka = $_POST['lozinka'];
            $email = $_POST['email'];
            $tipId = $_POST['tipId'];
            $blokiran = $_POST['blokiran'];
            $slika = $_POST['slika'];
				if($id==0){
					$upit ="INSERT INTO korisnik (tip_korisnika_id, korisnicko_ime, lozinka, ime, prezime, email, blokiran, slika) VALUES ('$tipId', '$korisnickoIme', '$lozinka', '$ime', '$prezime', '$email', '0', '$slika');";
				}
				  else{
					$upit="UPDATE korisnik SET tip_korisnika_id = '$tipId',korisnicko_ime = '$korisnickoIme', lozinka = '$lozinka', ime = '$ime', prezime = '$prezime', email = '$email', blokiran = '$blokiran', slika = '$slika' WHERE korisnik_id='$id'
					";
				}
				izvrsiUpit($veza,$upit);
					header("Location: popis_korisnika.php");
			}
		}
		 if(isset($_GET['korisnik'])){
			$id = $_GET['korisnik'];
			$upit = "SELECT tip_korisnika_id, korisnicko_ime, lozinka, ime, prezime, email, blokiran, slika FROM korisnik WHERE korisnik_id='$id'";
			$rezultat=izvrsiUpit($veza, $upit);
			list($tipId, $korisnickoIme, $lozinka, $ime, $prezime, $email, $blokiran, $slika)=mysqli_fetch_array($rezultat);
		}
			else {
				$ime="";
				$prezime="";
				$korisnickoIme="";
				$lozinka="";
				$email="";
				$tipId="";
				$blokiran="";
				$slika="";
		}
		  if(isset($_POST["reset"])){
			header("Location: dodaj_uredi_korisnika.php");
		}
		  if(isset($_POST['delete'])){
			$upit ="DELETE FROM korisnik WHERE korisnik_id='$id'";
			$rezultat=izvrsiUpit($veza, $upit);
			header("Location: popis_korisnika.php");
		}
?>
<form action="<?php if(isset($_GET['korisnik']))echo "dodaj_uredi_korisnika.php?korisnik=$id";else echo "dodaj_uredi_korisnika.php";?>" method="POST">
	<p class="naslov">
		<?php
			if(isset($_GET['korisnik']))echo "Uredi informacije o korisniku";
                else echo "Dodaj korisnika";
        ?>
    </p>
    <input type="hidden" name="novi" value="<?php if(!empty($id))echo $id; else echo 0; ?>"/>
		<label class="greska"><?php if($error!="")echo $error; ?></label>
     <div class="dodaj_sliku">
		<label for="ime"><strong>Ime:</strong></label>
			<input type="text" name="ime" id="ime" value="<?php if(!isset($_POST['ime']))echo $ime; else echo $_POST['ime']; ?>"/>
        <br>  
        <label for="prezime"><strong>Prezime:</strong></label>
			<input type="text" name="prezime" id="prezime" value="<?php if(!isset($_POST['prezime']))echo $prezime; else echo $_POST['prezime']; ?>"/>
        <br>
        <label for="korIme"><strong>Korisničko ime:</strong></label>
			<input type="text" name="korisnickoIme" id="korisnickoIme" value="<?php if(!isset($_POST['korisnickoIme']))echo $korisnickoIme; else echo $_POST['korisnickoIme']; ?>"/>
		<br>
        <label for="lozinka"><strong>Lozinka:</strong></label>
			<input type="text" name="lozinka" id="lozinka" value="<?php if(!isset($_POST['lozinka']))echo $lozinka; else echo $_POST['lozinka']; ?>"/>
        <br>
        <label for="email"><strong>E-mail:</strong></label>
			<input type="text" name="email" id="email" value="<?php if(!isset($_POST['email']))echo $email; else echo $_POST['email']; ?>"/>
		<br>
        <label for="tipId"><strong>Tip korisnika:</strong></label>
			<select name="tipId" id="tipId">
				<?php
					if(isset($_POST['tipId'])){
						echo '<option value="0"';
                    if($_POST['tipId']==0)echo " selected='selected'";
                        echo'>Administrator</option>';
						echo '<option value="1"';
                    if($_POST['tipId']==1)echo " selected='selected'";
                        echo'>Voditelj</option>';
						echo '<option value="2"';
                    if($_POST['tipId']==2)echo " selected='selected'";
						echo'>Korisnik</option>';
							}
           		    else{
						echo '<option value="0"';
                    if($tipId==0)echo " selected='selected'";
                        echo'>Administrator</option>';
		       			echo '<option value="1"';
					if($tipId==1)echo " selected='selected'";
                        echo'>Voditelj</option>';
						echo '<option value="2"';
                    if($tipId==2)echo " selected='selected'";
						echo'>Korisnik</option>';
							}
                        ?>
             </select>
		<br>
<?php
	if(isset($_GET['korisnik'])){
?>
		<label for="blokiran"><strong>Blokiran (1=blokiran, 0=odblokiran):</strong></label>
			<select name="blokiran" id="blokiran">
				<?php
					if(isset($_POST['tipId'])){
						echo '<option value="0"';
                    if($_POST['blokiran']==0)echo " selected='selected'";
                        echo'>Odblokiran</option>';
						echo '<option value="1"';
                    if($_POST['blokiran']==1)echo " selected='selected'";
                        echo'>Blokiran</option>';
					}
					else{
						echo '<option value="0"';
                    if($blokiran==0)echo " selected='selected'";
                        echo'>0</option>';
						echo '<option value="1"';
                    if($blokiran==1)echo " selected='selected'";
                        echo'>1</option>';
					}
                 ?>
            </select>
   <?php } ?>
		<br>
          <label for="slika"><strong>Slika:</strong></label>
			<?php
				$dir=scandir("korisnici");
					echo '<select id="slika" name="slika">';
					foreach($dir as $kljuc => $vrijednost){
						if($kljuc<2)continue;
						else if(strcmp((isset($_POST['slika'])?$_POST['slika']:$slika),"popis_korisnika/".$vrijednost)==0){
							echo '<option value="'."popis_korisnika/".$vrijednost.'"';
							echo ' selected="selected">'."popis_korisnika/".$vrijednost;
							echo '</option>';
							}
						else{
							echo '<option value="'."popis_korisnika/".$vrijednost.'">';
							echo "popis_korisnika/".$vrijednost;
							echo '</option>';
							}
							}
						echo '</select>';
             ?>
			 <br>
             <?php 
				if(isset($id)&&!empty($id)){
					echo "<input class=\"slika_btn\" style=\"width: 100px;\" type=\"submit\" name=\"submit\" value=\"Uredi\"/>
                          <input class=\"slika_btn\" style=\"width: 100px;\" type=\"submit\" name=\"delete\" value=\"Obriši korisnika\"/>";
                   }
				   else {
                    echo "<input class=\"slika_btn\" style=\"width: 100px;\" type=\"submit\" name=\"submit\" value=\"Dodaj\"/>
						  <input class=\"slika_btn\" style=\"width: 100px;\" type=\"submit\" name=\"reset\" value=\"Očisti\"/>";
                   }
              ?>
	</div>
</form>
<?php
	zatvaranjeVezeNaBazu($veza);
	include("podnozje.php");
?>