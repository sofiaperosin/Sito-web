<header id="lHeader">
<?php		
	
	if(isset($_SESSION['username'])){
		$conto=0;
		/*Per minimizzare i permessi uso l'utente che puo' solo leggere*/
		$con=mysqli_connect("localhost","uReadOnly","posso_solo_leggere","biblioteca");
		if(mysqli_connect_errno())
			echo ("<p>Errore connession al DBMS: ".mysqli_connect_errno()."</p>\n");
		else{
			/*Preparo la query*/
			$query2="SELECT count(*) from books where prestito ='".$_SESSION["username"]."'";
			/*Eseguo la query*/
			$result2=mysqli_query($con,$query2);
			/*Controllo che sia ok*/
			if(!$result2)
				echo ("Errore query fallita: ".mysqli_errno($con));
			/*Prelevo*/
			else{
				while($row2=mysqli_fetch_assoc($result2))
					$conto=$row2["count(*)"];
				$_SESSION["numeroLibri"]=$conto;
			}
		}
		/*Rilascio memoria*/
		mysqli_free_result($result2);
		/*Chiudo connessione*/
		mysqli_close($con);
		
		echo "<p class='utente'>UTENTE: ".$_SESSION['username'].", LIBRI A PRESTITO: ".$_SESSION['numeroLibri']."</p>";
	}
	else
		echo "<p class='utente'>UTENTE: Anonimo, LIBRI A PRESTITO: 0</p>";
	echo "<h1 id='titolo'>PRESTITO DEI LIBRI</h1>";
?>

<hr>
</header>