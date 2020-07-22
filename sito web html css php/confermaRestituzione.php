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
<?php include("header.php");?>

<!--Importo il menu con php-->
<?php include("menu.php");?>

<main id="ilContenuto">	
	<h2>Conferma di restituzione</h2>
	<?php
		if(isset($_REQUEST["identificativo"])){
			$ok=0;
			/*In questo caso devo usare l'utente che puo' scrivere nel db*/
			$con=mysqli_connect("localhost","uReadWrite","SuperPippo!!!","biblioteca");
			if(mysqli_connect_errno())
				echo ("<p>Errore connession al DBMS: ".mysqli_connect_errno()."</p>\n");
			else{
				/*Preparo il prepared statement*/
				$query="UPDATE books SET prestito=\"\" WHERE id=? ";
				$stmt=mysqli_prepare($con,$query);
				mysqli_stmt_bind_param($stmt,"s",$_REQUEST["identificativo"]);
				/*Eseguo il prepared statement*/
				$result=mysqli_stmt_execute($stmt);
				/*Se la restituzione non e' andata a buon fine visualizzo la pagina di errore*/
				if(!$result){
					echo "Errore!!";
				}
				/*Se la restituzione e' andata a buon fine si visualizza la conferma*/
				else{
					/*Preparo la query*/
					$query2="SELECT * FROM books";
					/*Eseguo la query*/
					$result2=mysqli_query($con,$query2);
					/*Controllo che sia ok*/
					if(!$result2)
						echo ("Errore query fallita: ".mysqli_errno($con));
					/*Prelevo*/
					else{
						while($row2=mysqli_fetch_assoc($result2)){
							if($row2["id"]==$_REQUEST["identificativo"]){
									$trovato=$row2["data"];
									$_SESSION["title"]=$row2["titolo"];

								}
							}
						$date1=date_create(date("Y-m-d h:i:sa"));
						$date2=date_create($trovato);
						$diff=date_diff($date2,$date1);
						$tempo=$diff->format("%a");
						$_SESSION["tempo"]=$tempo;
						/*Rilascio memoria*/
						mysqli_free_result($result2);
						$ok=1;
					}
				}
				/*Rilascio prepared statement*/
				mysqli_stmt_close($stmt);
				/*Chiudo connessione*/
				mysqli_close($con);	
			}
			if($ok==1){
				$_SESSION["okok"]=1;
				header('Location: finale.php');
			}
		}
		else{
			echo "<p class='attenzione'>ERRORE: Selezionare prima il libro da restituire!!</p>";
			echo "<p class='indirizzi'>Torna alla <a href ='Libri.php'>pagina di restituzione</a><!!/p>";
		}
	?>
</main>


<!--Importo il footer con php-->
<?php include("footer.php"); 
}?>

</body>

</html>