<?php
	include_once("zaglavlje.php");
	$veza =spajanjeNaBazu();
	
	if(!isset($_SESSION['aktivni_korisnik_id'])) {
    header("Location: prijava.php");
        exit();
    }
	$upit = "SELECT blokiran FROM korisnik WHERE korisnik_id={$_SESSION['aktivni_korisnik_id']}";
	$rezultat = izvrsiUpit($veza,$upit);
		list($blokiran) = mysqli_fetch_array($rezultat);
?>
<?php
	$error = "";
	if(isset($_POST['submit'])){
		foreach($_POST as $kljuc => $vrijednost){
			if(strlen($vrijednost)==0){
				$error = "Potrebno je popuniti sva polja!";
			}
	    }
		if(empty($error)) {
            $id =$_GET['slika'];
            $planinaId =$_POST['planinaNaziv'];
            $korisnikId =$_SESSION['aktivni_korisnik_id'];
            $naziv =$_POST['naziv'];
            $url =$_POST['url'];
            $opis =$_POST['opis'];
            $datum =$_POST['datum'];
            $datum =strtotime($datum);
            $datum =date("Y-m-d H:i:s", $datum);
            $status =$_POST['status'];
				if($id==0){
					$upit = "INSERT INTO slika (planina_id, korisnik_id, naziv, URL, opis, datum_vrijeme_slikanja, status) VALUES ('$planinaId', '$korisnikId', '$naziv', '$url', '$opis', '$datum', '1');";
				}
				else {
				 	$upit = "UPDATE slika SET planina_id = '$planinaId', korisnik_id = '$korisnikId', naziv = '$naziv', URL = '$url', opis = '$opis', datum_vrijeme_slikanja = '$datum',
					      status = '$status' WHERE slika_id='$id'";
			    }
				
			izvrsiUpit($veza,$upit);
				header("Location: galerija_slika.php");
	  }
    }
		if(isset($_GET['slika'])){
			$id = $_GET['slika'];
			$upit = "SELECT planina_id, korisnik_id, naziv, URL, datum_vrijeme_slikanja, opis, status FROM slika WHERE slika_id='$id'";
			$rezultat = izvrsiUpit($veza, $upit);
			list($planinaId, $korisnikId, $naziv, $url, $datum, $opis, $status)=mysqli_fetch_array($rezultat);
			$datum = strtotime($datum);
			$datum = date("d.m.Y. H:i:s", $datum);
		}
		else {
			$naziv="";
			$url="";
			$datum="";
			$opis="";
			$planinaId="";
		}
		if(isset($_POST["reset"])){
			header("Location: dodaj_uredi_sliku.php");
		}
?>
<form action="<?php if(isset($_GET['slika']))echo "dodaj_uredi_sliku.php?slika=$id&planinaId=$planinaId";else echo "dodaj_uredi_sliku.php";?>" method="POST">
        <p class="naslov">
            <?php
                if(isset($_GET['slika']))echo "Uredi informacije o slici";
					else echo "Dodaj novu sliku";
            ?>
        </p>
        <input type="hidden" name="novaSlika" value="<?php if(!empty($id))echo $id; else echo 0; ?>"/>
			<label class="greska"><?php if($error!="")echo $error; ?></label>
			
		<div class="dodaj_sliku">			
			<label for="planina"><strong>Naziv planine:</strong></label>
				<select name="planinaNaziv" id="planinaNaziv">
					<?php
						$upit = "SELECT planina_id, naziv FROM planina";
                        $rezultat = izvrsiUpit($veza,$upit);
							while(list($planinaId, $planinaNaziv)=mysqli_fetch_array($rezultat)){
                                if(isset($_GET['planinaId'])){
                                    if($_GET['planinaId']==$planinaId){
                                        echo "<option value='$planinaId' selected='selected'>$planinaNaziv</option>";
                                    } else {
                                        echo "<option value='$planinaId'>$planinaNaziv</option>";
                                    	}
									} else {
										echo "<option value='$planinaId'>$planinaNaziv</option>";
                                    }
                            } 
                      ?>
				 </select>
			<br>
			<label for="url"><strong>URL slike:</strong></label>
				<input type="url" name="url" id="url" placeholder="Unesite link na sliku (npr.https://media-cdn.tripadvisor.com/media/photo-s/12/ba/02/6b/dinara.jpg)" 
					   value="<?php if(!isset($_POST['url']))echo $url; else echo $_POST['url']; ?>"/>
			<br>
		    <label for="datum"><strong>Datum i vrijeme slikanja:</strong></label>
		        <input style="width: 54%;" type="text" name="datum" id="datum" placeholder="Unesite datum (npr. 10.01.2022. 13:20:00)" 
					   value="<?php if(!isset($_POST['datum']))echo $datum; else echo $_POST['datum']; $datum = strtotime($datum); $datum = date("Y-m-d H:i:s", $datum); ?>"/>
			<br>
			<label for="naziv"><strong>Naziv slike:</strong></label>
				 <input type="text" name="naziv" id="naziv"  
				        value="<?php if(!isset($_POST['naziv']))echo $naziv; else echo $_POST['naziv']; ?>"/>
			<br>
			<label for="opis"><strong>Opis slike:</strong></label>
				 <input type="text" name="opis" id="opis"  
				 value="<?php if(!isset($_POST['opis']))echo $opis; else echo $_POST['opis']; ?>"/>
			
			<?php if(isset($id) && $blokiran!='1'){?>
			<br>
			<label for="status"><strong>Status slike (0-privatna, 1-javna):</strong></label>
				 <input style="width: 50px;" type="number" name="status" id="status" min="0" max="1" 
					    value="<?php if(!isset($_POST['status']))echo $status; else echo $_POST['status'];?>">
			<?php } ?>
			<br>
			<?php 
				if(isset($id)&&$_SESSION['aktivni_korisnik_id']==$korisnikId||!empty($id)){
					echo '<input class="slika_btn" style="width: 100px;" type="submit" name="submit" value="Uredi"/>';
					}
					else {
						echo '<input class="slika_btn" style="width: 100px;" type="submit" name="submit" value="Dodaj"/>
							  <input class="slika_btn" style="width: 100px;" type="submit" name="reset" value="Poništi"/>';
					}
			?>
		 </div>			
</form>
<?php
	zatvaranjeVezeNaBazu($veza);
	include("podnozje.php");
?>



