<?php
	include_once("zaglavlje.php");
	$veza = spajanjeNaBazu();

    if(isset($_SESSION['aktivni_korisnik_id'])){
        if($_SESSION['aktivni_korisnik_tip']=='0'){
			echo "
				<div class=\"dodaj\">
					<a href=\"popis_moderatora.php\">Moderatori</a>
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

    if(isset($_POST['submit'])){
        $planinaId=$_GET['planina'];
        $planina=$_POST['planina'];
        $moderator=$_POST['moderator'];
            for ($i=0; $i<=sizeof($moderator);$i++){ //funkcija sizeof vraća broj elemenata u nizu
                $upit ="INSERT IGNORE INTO moderator (korisnik_id, planina_id) VALUES ('$moderator', '$planina')"; //Ako zapis ne duplicira postojeći zapis, MySQL ga umeće kao i obično. Ako je zapis duplikat, ključna riječ IGNORE govori MySQL-u da ga tiho odbaci bez generiranja pogreške.
                    izvrsiUpit($veza, $upit);
                $upit ="UPDATE korisnik SET tip_korisnika_id='1' WHERE korisnik_id='$moderator'";
                    izvrsiUpit($veza, $upit);
            }
                header("Location:popis_moderatora.php");
            }
            if(isset($_POST['reset'])){
                header("Location:dodaj_moderatora.php");
            }
?>
<form action="<?php if(isset($_GET['planina']))echo "dodaj_moderatora.php?planinaId=$planinaId&planinaNaziv=$planinaNaziv&moderator=$korisnikId";
                    else echo "dodaj_moderatora.php";?>" method="POST">
        <p class="naslov">
        <?php
                if(isset($_GET['planina'])) echo "Uređivanje moderatora za planinu: {$_GET['planinaNaziv']}";
                    else echo "Dodavanje moderatora";
        ?>
        </p>
        <div class="dodaj_sliku">			
			<label for="planina"><strong>Naziv planine:</strong></label>
				<select name="planina" id="planina">
					<?php
						$upit = "SELECT planina.planina_id, planina.naziv FROM planina";
                        $rezultat = izvrsiUpit($veza,$upit);
							while(list($planinaId, $planinaNaziv)=mysqli_fetch_array($rezultat)){
                                if(isset($_GET['planina'])){
                                    if($_GET['planina']==$planinaId){
                                        echo'<option value="'.$_GET['planina'].'" selected="selected">'.$_GET['planinaNaziv'].'</option>';
                                    } 
									else {
                                        echo "<option value=\"$planinaId\">$planinaNaziv</option>";
                                    }
									}
									else {
										echo "<option value=\"$planinaId\">$planinaNaziv</option>";
                                }
                            } 
                      ?>
				 </select>
            <br>
            <label for="moderator"><strong>Moderator:</strong></label>
                    <br>
                    <?php
                        $upit = "SELECT korisnik.korisnik_id, korisnik.ime, korisnik.prezime, moderator.planina_id 
                                 FROM korisnik LEFT JOIN moderator ON korisnik.korisnik_id=moderator.korisnik_id";
                        $rezultat = izvrsiUpit($veza,$upit);
                            while(list($korisnikId, $ime, $prezime, $planinaId)=mysqli_fetch_array($rezultat)){
                                 if(isset($_GET['planina'])){
                                    if($_GET['planina']==$planinaId){
                                        echo '<label for="moderator">'.$ime.' '.$prezime.'</label><input type="checkbox" name="moderator" value='.$korisnikId.'><br>';
                                    } 
                                    else {
                                        echo '<label for="moderator">'.$ime.' '.$prezime.'</label><input type="checkbox" name="moderator" value='.$korisnikId.'><br>';
                                    } 
                                    }
                                    else {
                                        echo '<label for="moderator">'.$ime.' '.$prezime.'</label><input type="checkbox" name="moderator" value='.$korisnikId.'><br>';
                                    } 
                            } 
                    ?>
        <br>
            <?php 
                if(isset($_GET['planina']) && !empty($_GET['planina'])){		
                    echo '<input class="slika_btn" style="width: 100px;" type="submit" name="submit" value="Dodaj"/>';
                } else {
                    echo '<input class="slika_btn" style="width: 100px;" type="submit" name="submit" value="Dodaj"/>
                          <input class="slika_btn" style="width: 100px;" type="submit" name="reset" value="Očisti"/>';
                       }
            ?>
        </div>
</form>

