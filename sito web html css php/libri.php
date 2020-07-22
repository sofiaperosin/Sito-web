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

<title>Libri</title>

<script>
	function validaNumeroGiorni(numero){
		var num=numero.trim();
		var reg=/^[0-9]+$/;
		if(!reg.test(num)){
			alert("Inserisci un numero intero!!");
			return false;
		}
		return true;
	}
</script>

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
	<h2>Libri</h2>
	<?php 
		if(isset($_SESSION["username"])){
			$conteggio=0;
			/*Per minimizzare i permessi uso l'utente che puo' solo leggere*/
			$con=mysqli_connect("localhost","uReadOnly","posso_solo_leggere","biblioteca");
			if(mysqli_connect_errno())
				echo ("<p>Errore connession al DBMS: ".mysqli_connect_errno()."</p>\n");
			else{
				/*Preparo la query*/
				$query = "SELECT * FROM books WHERE prestito='".$_SESSION["username"]."' order by titolo";
				/*Eseguo la query*/
				$result=mysqli_query($con,$query);
				/*Controllo che sia ok*/
				if(!$result)
					echo ("Errore query fallita: ".mysqli_errno($con));
				/*Prelevo*/
				else{
					echo "<table>";
					echo "<caption class='titolo' id='intestazione'>Libri attualmente in prestito all'utente ".$_SESSION["username"]."</caption>";
					echo "<tr><th class='figlie'>TITOLO</th><th class='figlie'>STATO</th></tr>";
					echo "</table>";
					while($row=mysqli_fetch_assoc($result)){
						$conteggio=$conteggio+1;
						echo"<form name='f' action='confermaRestituzione.php' method ='POST'>";
						echo "<table class='seconda'>";
						echo "<tr class='inPrestito'><td class ='figlie'>".$row["titolo"]."</td><td class='figlie'><input type='submit' class='buttonRestituisci' value='RESTITUISCI'></td><td><input type='text' name='identificativo' value='".$row["id"]."'hidden ></td></tr>";
						echo "</table>";
						echo "</form>";
					}
					
				}
	
				/*Rilascio memoria*/
				mysqli_free_result($result);
				/*Ora per i libri in biblioteca*/
				/*Preparo la query*/
				$query2= "SELECT * FROM books";
				/*Eseguo la query*/
				$result2=mysqli_query($con,$query2);
				/*Controllo che sia ok*/
				if(!$result2)
					echo ("Errore query fallita: ".mysqli_errno($con));
				/*Prelevo*/
				else{
					echo "<form name='f' action=libri.php method ='POST' onSubmit='validaNumeroGiorni(quantita.value);'>";
					echo "<table>";
					echo "<caption class='titolo'>Libri disponibili in biblioteca</caption>";
					echo "<tr><th>TITOLO</th><th>AUTORE</th><th>STATO</th></tr>";
					echo "<tbody>";
					while($row2=mysqli_fetch_assoc($result2)){
						$pre=$row2["prestito"];
						$inserisco="";
						if($pre!=""){
							$date1=date_create(date("Y-m-d h:i:sa"));
							$date2=date_create($row2["data"]);
							$diff=date_diff($date2,$date1);
							$tempo=$diff->format("%a");
							$ore=0;
							$ore=$diff->format("%h");
							$minuti=0;
							$minuti=$diff->format("%i");
							$secondi=0;
							$secondi=$diff->format("%s");
							if($tempo>$row2["giorni"])
								$inserisco="PRESTITO SCADUTO";
							elseif($tempo<$row2["giorni"])
								$inserisco="IN PRESTITO";
							elseif ($tempo==$row2["giorni"]){
								if($ore>0)
									$inserisco="PRESTITO SCADUTO";
								else{
									if($minuti>0)
										$inserisco="PRESTITO SCADUTO";
									else{
										if($secondi>0)
											$inserisco="PRESTITO SCADUTO";
										else
											$inserisco="IN PRESTITO";
									}
								}
							}
						}
						
						if($pre=="")
							echo "<tr class='disponibili'><td>".$row2["titolo"]."</td><td>".$row2["autori"]."</td><td><input type='checkbox' class='check' name='selezionati[".$row2['titolo']."]' value='".$row2["titolo"]."'></td></tr>";
						else
							echo "<tr class='nonDisponibili'><td>".$row2["titolo"]."</td><td>".$row2["autori"]."</td><td><label>".$inserisco."</label></td></tr>";
					}
					echo "</tbody>";
					echo "</table>";
					echo "<fieldset><legend> Inserisci la durata del prestito desiderato </legend><input type='text' id='quantita' name='qnt'><input type='submit' class='buttomFieldset' id='buttomFieldsetLibri' value='PRESTITO'></fieldset>";
					echo "<noscript><p class='attenzione'>Per il corretto funzionamento &egrave; necessario abilitare Javascript.</p></noscript>";
					echo "</form>";
				}
				/*Rilascio memoria*/
				mysqli_free_result($result2);
				/*Chiudo connessione*/
				mysqli_close($con);
				/*Faccio controllo nel caso in cui Javascript non sia attivo*/
				if(isset($_REQUEST['qnt'])&&$_REQUEST['qnt']>0&&!isset($_REQUEST['selezionati']))
					echo "<p class='attenzione'>ERRORE: Prima seleziona i libri da prendere in prestito!!</p>";
				elseif(isset($_REQUEST['qnt'])){
					$err=0;
					if(isset($_REQUEST['qnt'])){
						$qnt=trim($_REQUEST['qnt']);
						$reg="/^[0-9]+$/";
						if(!preg_match($reg,$qnt))
							$err=1;
						if($err==1){
							/*Se Javascript non e' attivo il controllo nella funzione non e' stato fatto, lo faccio quindi qui e se c'e' un errore avviso l'utente*/
						echo("<noscript>");
						echo('<p class="attenzione">Inserire un numero intero!!</p>');
						echo("</noscript>");
						}

					}
					if($err==0){
						if($conteggio+count($_REQUEST['selezionati'])>3){
							echo "<p class='attenzione'>Prestito non concesso: Al massimo sono concessi 3 libri simultaneamente</p>";
						}
						else{
							$pro=0;
							$_SESSION['numeroLibri']=$_SESSION['numeroLibri']+count($_REQUEST['selezionati']);
							/*In questo caso devo usare l'utente che puo' scrivere nel db*/
							$con3=mysqli_connect("localhost","uReadWrite","SuperPippo!!!","biblioteca");
							if(mysqli_connect_errno())
								echo ("<p>Errore connession al DBMS: ".mysqli_connect_errno()."</p>\n");
							else{
								/*In questo caso uso il prepared statement dato che uso input dell'utente*/
								/*Preparo il prepared statement*/
											
								foreach($_REQUEST['selezionati'] as $scorrere){
									$query3="UPDATE books SET prestito=?, data=now(), giorni=? where titolo=? ";
									$stmt3=mysqli_prepare($con3,$query3);
									mysqli_stmt_bind_param($stmt3,"sis",$_SESSION["username"],$_REQUEST['qnt'],$scorrere);
									/*Eseguo il prepared statement*/
									$result3=mysqli_stmt_execute($stmt3);
									/*Se la registrazione non e' andata a buon*/
									if(!$result3){
										$pro=1;
									}
									/*Rilascio prepared statement*/
									mysqli_stmt_close($stmt3);
								}
								if($pro==1)
									echo "Problema riscontrato nella registrazione";
								else{
									header ("Location: libri.php");
								}
								/*Chiudo connessione*/
								mysqli_close($con3);
							}
						}
					}
				}
			}
		}
		else{
			echo "Utente non loggato";
			/*Per minimizzare i permessi uso l'utente che puo' solo leggere*/
			$con=mysqli_connect("localhost","uReadOnly","posso_solo_leggere","biblioteca");
			if(mysqli_connect_errno())
				echo ("<p>Errore connession al DBMS: ".mysqli_connect_errno()."</p>\n");
			else{
				/*Preparo la query*/
				$query = "SELECT * FROM (SELECT count(*) as totale from books) as a,(SELECT count(*) as disponibili from books where prestito=\"\") as b";
				/*Eseguo la query*/
				$result=mysqli_query($con,$query);
				/*Controllo che sia ok*/
				if(!$result)
					echo ("Errore query fallita: ".mysqli_errno($con));
				/*Prelevo*/
				else{
					while($row=mysqli_fetch_assoc($result)){
						echo "<p>Numero totale di libri presenti nella biblioteca: ".$row["totale"]."</p>";
						echo "<p>Numero di libri disponibili per il prestito: ".$row["disponibili"]."</p>";
						echo "<p class='indirizzi'>Per poter richiedere un prestito ti invitiamo a fare <a href ='login.php'>login</a></p>";
					}
				}
				/*Rilascio memoria*/
				mysqli_free_result($result);
				/*Chiudo connessione*/
				mysqli_close($con);
			}
		}
	?>
</main>


<!--Importo il footer con php-->
	<?php include("footer.php");
	
	} 
	?>

</body>

</html>
