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

<title>Errore Registrazione</title>

</head>


<body>
<?php
	if(!$session)
		echo "<p class='attenzione'>SESSIONI DISABILITATE, impossibie proseguire!</p>";
	else{
	?>
<!--Importo l'header con php-->
<?php include("header.php");?>


<main id="ilContenuto">
	<h2>ERRORE</h2>
	
	<?php 
	if(isset($_SESSION["usernameEsistente"])){
		if($_SESSION["usernameEsistente"]==1 || $_SESSION["erroreQuery"]==1){ ?>
			<p>Errore riscontrato in fase di registrazione!</p>
			<?php 
				if($_SESSION["usernameEsistente"]==1)
					echo "Username gi&agrave; esistente!"; 
				elseif($_SESSION["erroreQuery"]==1)
					echo "Query fallita"; 
			//manca da dire l'errore
			echo "<p class='indirizzi'>Torna alla <a href ='new.php'>registrazione</a></p>";
		}
	}
	else{
		echo "<p class='attenzione'>ERRORE: Effettuare prima la registrazione!!</p>";
		echo "<p class='indirizzi'>Torna alla <a href ='New.php'>pagina di registrazione</a><!!/p>";
	}
	
	?>
	
</main>


<!--Importo il footer con php-->
	<?php include("footer.php"); 
}?>

</body>

</html>