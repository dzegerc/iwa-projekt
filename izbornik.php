<?php $trenutnaStranica = basename($_SERVER['PHP_SELF']); ?>
<nav class="izbornik">
		<?php if(isset($_SESSION["aktivni_korisnik_id"]))
			  if(isset($_SESSION["aktivni_korisnik_tip"]) && $_SESSION["aktivni_korisnik_tip"]=="1" || $_SESSION["aktivni_korisnik_tip"]=="2") { ?>
				  <a class="" href="popis_slika.php">MOJE SLIKE</a>
	   	<?php } ?>
		<?php if(isset($_SESSION["aktivni_korisnik_id"]))
			  if(isset($_SESSION["aktivni_korisnik_tip"]) && $_SESSION["aktivni_korisnik_tip"]=="0" || $_SESSION["aktivni_korisnik_tip"]=="1") { ?>
				  <a class="" href="planine.php">POPIS PLANINA</a>
		<?php } ?>
		<?php if(isset($_SESSION["aktivni_korisnik_id"])) 
			  if(isset($_SESSION["aktivni_korisnik_tip"]) && $_SESSION["aktivni_korisnik_tip"]=="0") { ?>
				  <a class="" href="popis_slika.php">POPIS SLIKA</a>
		<?php } ?>
		<?php if(isset($_SESSION["aktivni_korisnik_id"])) 
			  if(isset($_SESSION["aktivni_korisnik_tip"]) && $_SESSION["aktivni_korisnik_tip"]=="0") { ?>
				 <a class="" href="popis_korisnika.php">KORISNICI</a>
		<?php } ?>
		<?php if(isset($_SESSION["aktivni_korisnik_id"])) 
			  if(isset($_SESSION["aktivni_korisnik_tip"]) && $_SESSION["aktivni_korisnik_tip"]=="0") { ?>
				 <a class="" href="statistika_slika.php">STATISTIKA</a>
		<?php } ?>	
</nav>
