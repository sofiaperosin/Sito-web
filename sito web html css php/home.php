<!--Gestione delle sessioni-->
<?php
	/*Inizio della sessione*/
	$session=true;
	/*Controllo che le sessioni siano abilitate*/
	if(session_status()===PHP_SESSION_DISABLED )
		$session=false;
	/*Se le sessioni sono abilitate ma non ancora attivate le attivo*/
	elseif (session_status()!==PHP_SESSION_ACTIVE )
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

<!--Keywords-->
<meta name="keywords" content="registrazione, nuovo, utente, prestito">

<title>Home</title>

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
	<?php include("menu.php");?>

	<main id="ilContenuto">
		<h2>Presentazione del sito</h2>
		<p>Salve! Benvenuto nella home page del sito per il prestito dei libri!
		<img src="biblioteca.jpg" alt="Foto di una libreria" id="foto"></p>
		<p class="citazione">"Leggere è andare incontro a qualcosa che sta per essere e ancora nessuno sa cosa sarà."<br><a href="https://it.wikipedia.org/wiki/Italo_Calvino">Italo Calvino</a></p>
	</main>

	<!--Importo il footer con php-->
	<?php include("footer.php");
	
	} 
	?>

</body>

</html>
