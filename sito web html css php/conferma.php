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


<title>Conferma</title>

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
	<h2>Conferma di registrazione</h2>
	<?php if(isset ($_SESSION["erroreQuery"])){ ?>
		<p>Registrazione avvenuta con successo!</p>
		<p>Torna alla <a href ="Home.php">home</a></p>
	<?php }
	else{
			echo "<p class='attenzione'>ERRORE: Effettuare prima la registrazione!!</p>";
			echo "<p>Torna alla <a href ='New.php'>pagina di registrazione</a><!!/p>";
		}
		?>
</main>


<!--Importo il footer con php-->
<?php include("footer.php"); 
}?>

</body>

</html>
