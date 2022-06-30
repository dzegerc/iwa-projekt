<?php
	include_once("bp.php");
    if(session_id()=="")session_start();
?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Hrvatske planine</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
	<body>
		<header >
			<a href="index.php"><img src="./images/logo.jpg" class="logo"></a>
			<div class="wraper">
				<a class="button" href="o_autoru.html">O AUTORU</a>
				<?php 
					if(isset($_SESSION["aktivni_korisnik_id"])){ ?>
					<a class="button" href="prijava.php?odjava=1">ODJAVA</a>	
					<?php } else { ?>
					<a class="button"  href="prijava.php">PRIJAVA</a>
						
						<br>
					<?php } ?>
			</div>
		</header>
	</body>
	<hr>
	<?php
		include("izbornik.php");
	?>
</html>

