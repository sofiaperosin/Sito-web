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

<!--Keywords-->
<meta name="keywords" content="registrazione, nuovo, utente, prestito">

<title>New</title>
<script>
	function validaDati(user,pass1,pass2){
		var user=user;
		var pass1=pass1;
		var pass2=pass2;
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
		if(!regPass1.test(pass1) || !regPass2.test(pass1) || !regPass3.test(pass1)){
			alert("Formato della password errato!!");
			return false;
		}
		if(!regPass1.test(pass2) || !regPass2.test(pass2) || !regPass3.test(pass2)){
			alert("Formato della password errato!!");
			return false;
		}
		if(pass1!=pass2){
			alert("Le due password non corrispondono!!");
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
	<h2>New</h2>
		<form name="f" action=new.php method="POST" onSubmit="validaDati(username.value,password1.value,password2.value);">
			<fieldset>
			<legend>Creazione di un nuovo account</legend>
			<label>Username:</label><input type="text" id="username" name="us">
			<label>Password:</label><input type="password" id="password1" name="pa1">
			<label>Ripeti Password:</label><input type="password" id="password2" name="pa2">
			<input type="submit" class="buttomFieldset" value="REGISTRAMI">
			</fieldset>
			<div id="specNome">
				<p class="titolo">Specifiche del nome</p>
					<ul class="indicazioni">
					<li>pu&ograve; contenere solo caratteri alfanumerici e il simbolo '%';</li>
					<li>deve essere lungo da un minimo di 3 a un massimo di 6 caratteri;</li> 
					<li>deve iniziare con un carattere alfabetico o con il carattere '%';</li>
					<li>deve contenere almeno un carattere numerico e almeno un carattere non numerico.</li>
					</ul>
			</div>
			<div id="specPass">
				<p class="titolo">Specifiche della password</p>
					<ul class="indicazioni">
					<li>pu&ograve; contenere solo caratteri alfabetci;</li>
					<li>deve essere lunga da un minimo di 4 a un massimo di 8 caratteri;</li>
					<li>deve contenere almeno un carattere maiuscolo e almeno un carattere minuscolo.</li>
					</ul>
			</div>
			<!---Avviso l'utente nel caso in cui non abbai abilitato Javascript-->
			<noscript> <p class="attenzione"> Per il corretto funzionamento &egrave; necessario abilitare Javascript.</p></noscript>
			</form>	
	
	<?php
		/*Controllo se inserisco dei dati*/
		if(count($_REQUEST)!=0){
			$err=0;
			if(isset($_REQUEST['us']) && isset($_REQUEST['pa1'])&& isset($_REQUEST['pa2'])){
				$username=$_REQUEST['us'];
				$password1=$_REQUEST['pa1'];
				$password2=$_REQUEST['pa2'];
				$regUser1="/^[A-Za-z%][A-Za-z0-9%]{2,5}$/";
				$regUser2="/[0-9]+/";
				$regUser3="/[A-Za-z%]+/";
				$regPass1="/^[A-Za-z]{4,8}$/";
				$regPass2="/[A-Z]+/";
				$regPass3="/[a-z]+/";
				if(!preg_match($regUser1,$username) || !preg_match($regUser2,$username) || !preg_match($regUser3,$username) || !preg_match($regPass1,$password1) || !preg_match($regPass2,$password1) || !preg_match($regPass3,$password1)|| !preg_match($regPass1,$password2) || !preg_match($regPass2,$password2) || !preg_match($regPass3,$password2) || $password1!=$password2){
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
				/*In questo caso devo usare l'utente che puo' scrivere nel db*/
				$con=mysqli_connect("localhost","uReadWrite","SuperPippo!!!","biblioteca");
				if(mysqli_connect_errno())
					echo ("<p>Errore connession al DBMS: ".mysqli_connect_errno()."</p>\n");
				else
				{
					/*Controllo che non ci sia gia' qualcuno con quello username*/
					$esiste=0;
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
							if($row["username"]==$_REQUEST["us"]){
							/*Esiste gia' uno username cosi*/
								$esiste=1;
							}
						}
					}
					/*Rilascio memoria*/
					mysqli_free_result($result);
					if($esiste==1){
						$_SESSION["usernameEsistente"]=1;
						header('Location: errore.php');
					}
					else{
						$_SESSION["usernameEsistente"]=0;
						/*In questo caso uso il prepared statement dato che uso input dell'utente*/
						/*Preparo il prepared statement*/
						$query2="INSERT INTO users(username,pwd)VALUES(?,?)";
						$stmt=mysqli_prepare($con,$query2);
						mysqli_stmt_bind_param($stmt,"ss",$username,$password1);
						/*Eseguo il prepared statement*/
						$result2=mysqli_stmt_execute($stmt);
						/*Se la registrazione non e' andata a buon fine visualizzo la pagina di errore*/
						if(!$result2){
							$_SESSION["erroreQuery"]=1;
							header('Location: errore.php');
						}
						/*Se la registrazione e' andata a buon fine si visualizza la pagina di conferma*/
						else{
							$_SESSION["erroreQuery"]=0;
							header('Location: conferma.php');
							}
						/*Rilascio prepared statement*/
						mysqli_stmt_close($stmt);
						/*Chiudo connessione*/
					}
				}
				mysqli_close($con);
			}
		} ?>
</main>
<!--Importo il footer con php-->
<?php include("footer.php"); 
	}?>
</body>
</html>
