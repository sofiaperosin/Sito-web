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
<?php setcookie("test_cookie","test",time()+3600,'/'); ?>
<html lang="it">

<head>

<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="stile.css">
<meta name="viewport" content="width=320; initial-scale=1.0">
<link rel="icon" type="image/png" href="icon.png">
<meta name="author" content="Sofia Perosin">

<!--Keywords-->
<meta name="keywords" content="registrazione, nuovo, utente, prestito">

<title>Login</title>

<script>
	
	function validaDati(user,pass){
		var user=user;
		var pass=pass;
		var regUser1=/^[A-Za-z%][A-Za-z0-9%]{2,5}$/;
		var regUser2=/[0-9]+/;
		var regUser3=/[A-Za-z%]+/;
		if(!regUser1.test(user) || !regUser2.test(user) || !regUser3.test(user) ){
			alert("Formato dello username errato!!");
			return false;
		}
		var regPass1=/^[A-Za-z]{4,8}$/;
		var regPass2=/[A-Z]+/;
		var regPass3=/[a-z]+/;
		if(!regPass1.test(pass) || !regPass2.test(pass) || !regPass3.test(pass)){
			alert("Formato della password errato!!");
			return false;
		}
	}
	
	<!--Faccio una funzione perche' con reset mi fa tornare alla situazione iniziale: non svuota i campi-->
	function pulisciCampi(event){
		var us=document.getElementById("username");
		var ps=document.getElementById("password");
		us.value="";
		ps.value="";
	}
	
</script>

<!--Se non esiste, creo il cookie nel quale poi salvaro' l'utente-->
<?php
	if(!isset($_COOKIE["utente"])){
		setcookie("utente","");
	}
?>

</head>

<body>
<?php 
	if(count($_COOKIE)==0){
		echo "<p class='attenzione'>ATTENZIONE!! I COOKIE SONO DISABILITATI, ABILITARLI PER EFFETTUARE IL LOGIN!</p>";
		echo"<p class='indirizzi'>Torna alla <a href ='Home.php'>home</a></p>";
	}
		if(!$session){
			echo "<p class='attenzione'>SESSIONI DISABILITATE, impossibie proseguire!</p>";
		}
		else{
?>
	<!--Importo l'header con php-->
	<?php include("header.php");?>

	<!--Importo il menu con php-->
	<?php include("menu.php");?>

	<main id="ilContenuto">
		<h2>Login</h2>
		<?php 
		/*Controllo che non ci sia gia' un utente loggato*/
		if(!isset($_SESSION["username"])){
		?>
			<form name="f" action=login.php method="POST" onSubmit="validaDati(username.value,password.value);">
				<fieldset>
				<legend>Inserisci le tue credenziali per effettuare il login</legend>
				<label>Username:</label><input type="text" id="username" name="us" value='<?php 
					//se esiste il cookie allora il campo username e' precompilato
					if(isset($_COOKIE["utente"]))
						echo $_COOKIE["utente"];
					else
						echo "";
					?>'>
				<label>Password:</label><input type="password" id="password" name="pa" >
				<input type="button" value="PULISCI" class="buttomFieldset" onClick="pulisciCampi(this)"><input type="submit" class="buttomFieldset" id="buttomFieldsetLogin"  value="OK">
				</fieldset>
			<p class='indirizzi'>Se non disponi delle credenziali di accesso registrati alla <a href ='New.php'>pagina di registrazione</a></p>
			<!---Avviso l'utente nel caso in cui non abbai abilitato Javascript-->
			<noscript> <p class="attenzione"> Per il corretto funzionamento &egrave; necessario abilitare Javascript.</p></noscript>
			</form>	
	
		<?php
			/*Controllo se inserisco dei dati*/
			if(count($_REQUEST)!=0){
				$err=0;
				if(isset($_REQUEST['us']) && isset($_REQUEST['pa'])){
					$username=$_REQUEST['us'];
					$password=$_REQUEST['pa'];
					$regUser1="/^[A-Za-z%][A-Za-z0-9%]{2,5}$/";
					$regUser2="/[0-9]+/";
					$regUser3="/[A-Za-z%]+/";
					$regPass1="/^[A-Za-z]{4,8}$/";
					$regPass2="/[A-Z]+/";
					$regPass3="/[a-z]+/";
					if(!preg_match($regUser1,$username) || !preg_match($regUser2,$username) || !preg_match($regUser3,$username) || !preg_match($regPass1,$password) || !preg_match($regPass2,$password) || !preg_match($regPass3,$password)){
						$err=1;
						if($err==1){
						/*Se Javascript non e' attivo il controllo nella funzione non e' stato fatto, lo faccio quindi qui e se c'e' un errore avviso l'utente*/
							echo("<noscript>");
							echo('<p class="attenzione">I valori inseriti non rispettano il formato indicato!!</p>');
							echo("</noscript>");
						}
					}
				}
				/*Se i dati inseriti sono corretti allora mi connetto al db*/
				if($err==0){
					/*Per minimizzare i permessi uso l'utente che puo' solo leggere*/
					$con=mysqli_connect("localhost","uReadOnly","posso_solo_leggere","biblioteca");
					if(mysqli_connect_errno())
						echo ("<p>Errore connession al DBMS: ".mysqli_connect_errno()."</p>\n");
					else{
						/*Preparo la query*/
						$query="SELECT * FROM users";
						/*Eseguo la query*/
						$result=mysqli_query($con,$query);
						/*Controllo che sia ok*/
						if(!$result)
							echo ("Errore query fallita: ".mysqli_errno($con));
						/*Prelevo*/
						else{
							while($row=mysqli_fetch_assoc($result)){
								/*Se le credenziali sono corrette*/
								if($row["username"]==$_REQUEST["us"] && $row["pwd"]==$_REQUEST["pa"]){
									/*Salvo in sessione lo username*/
									$_SESSION["username"]=$row["username"];
									/*Salvo nel cookie lo username*/
									$cookieName="utente";
									$cookieValue=$row["username"];
									$scadenza=time()+3600*48;
									setcookie($cookieName,$cookieValue,$scadenza);
									/*Se l'autenticazione e' andata a buon fine lo indirizzo alla pagina di gestione dei prestiti*/
									header('Location: libri.php');
								}
							}
							/*Se non e' salvato nessun username vuol dire che le credenziali non erano corrette*/
							if(!isset($_SESSION["username"])){
								echo ('<p class="attenzione">Credenziali non corrette</p>');
							}
						}
						/*Rilascio memoria*/
						mysqli_free_result($result);
						/*Chiudo connessione*/
						mysqli_close($con);
					}	
				}
			}
		}
		//se c'e' gia' un utente loggato non puo' fare login, deve prima fare logout
		//questa serve nel caso in cui l'utente acceda alla pagine digitando direttamente l'uri
		else{
			echo("<p>C'&egrave; gi&agrave; un utente loggato. Per fare un nuovo login prima bisogna effettuare il logout</p>");
			echo('<p><a href ="logout.php">Logout</a></p>');
		}
	?>
	</main>


	<!--Importo il footer con php-->
	<?php include("footer.php"); 
		}
	
	?>

</body>

</html>