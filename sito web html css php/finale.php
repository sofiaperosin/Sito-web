<!--Gestione delle sessioni-->
<?php
	/*Inizio della sessione*/
	$session=true;
	/*Controllo che le sessioni siano abilitate*/
	if(session_status()===PHP_SESSION_DISABLED)
		$session=false;
	/*Se le sessioni sono abilitate ma non ancora attivate le attivo*/
	elseif (session_status()!==PHP_SESSION_ACTIVE)
		session_start();
?>

<!DOCTYPE html>

<html lang="it">

<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="stile.css">
<meta name="viewport" content="width=320; initial-scale=1.0">
<link rel="icon" type="image/png" href="icon.png">
<meta name="author" content="Sofia Perosin">

<title>Conferma Restituzione</title>

</head>

<body>

<?php
	if(!$session)
		echo "<p class='attenzione'>SESSIONI DISABILITATE, impossibie proseguire!</p>";
	else{
?>

	<!--Importo l'header con php-->
	<?php include("header.php"); ?>

	<!--Importo il menu con php-->
	<?php include("menu.php"); ?>

	<main id="ilContenuto">
	<?php 
	if(isset ($_SESSION["okok"]) && $_SESSION["okok"]==1){
		echo "<p>Il libro '".$_SESSION["title"]."' &egrave; stato preso in prestito dall'utente ".$_SESSION["username"]." per ".$_SESSION["tempo"]." giorni</p>";
		echo "<p class='indirizzi'>Torna alla <a href ='Home.php'>home</a></p>";
		unset($_SESSION["okok"]);
	}
	else{
		echo "<p class='attenzione'>ERRORE: Selezionare prima il libro da restituire!!</p>";
		echo "<p class='indirizzi'>Torna alla <a href ='libri.php'>pagina di restituzione</a><!!/p>";
	}
	?>
	</main>

	<!--Importo il footer con php-->
	<?php include("footer.php"); 
	}
	?>

</body>

</html>