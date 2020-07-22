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
	
	/*MANCA DA AZZERARE SESSIONE SUL NUMERO DEI LIBRI*/
	if(isset($_SESSION["username"])){
		/*Cancello la sessione contente lo username*/
		unset($_SESSION["username"]);
		/*Canecello la sessione su numero libri*/
		unset($_SESSION["numeroLibri"]);
		/*Distruggo la sessione*/
		session_destroy();
	}
	
	/*Reindirizzo l'utente a un nuovo login*/
	header('Location: login.php');
?>

