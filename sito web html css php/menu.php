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

<nav id="ilMenu">
	<h2>Men&ugrave;</h2>
	<ul>
		<li><a title="Pagina home del sito" href="home.php">Home</a></li>
		<!--Devo disabilitarlo a seconda che sia loggato-->
		<li><a <?php if(isset($_SESSION["username"])) echo 'class="disabilita" title="Non selezionabile fino a che non si fa logout"'; else echo 'title="Pagina per effettuare il login" href="login.php"'; ?> >Login</a></li>
		<li><a title="Pagina per registrare un nuovo account" href="new.php">New</a></li>
		<li><a title="Pagina per consultare i libri disponibili e a prestito" href="libri.php">Libri</a></li>
		<!--Devo disabilitarlo se non e loggato-->
		<li><a <?php if (!isset($_SESSION["username"])) echo 'class="disabilita" title="Non selezionabile fino a che non si fa login"'; else echo 'title="Clicca qui per effettuare il logout" href="logout.php"'; ?>>Logout</a></li>
	</ul>
</nav>
