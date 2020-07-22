<footer id="ilFooter">
<!--Linea di separazione-->
<!--potrei cambiare colore, ma lo faccio con css perche atributi color e size di hr sono deprecati-->
<hr>

<!--Specifico il nome dell'autore-->
<p>Autore della pagina: Sofia Perosin (<span class="email"> sofia.perosin@libero.it </span>)</p>

<!--potrei mettere un logo-->

<!--Faccio generare automaticamente il nome della pagina-->
<p><?php echo("Nome della pagina: ".basename($_SERVER['PHP_SELF']));?></p>

<!--Importo loghi validazione css e html validator-->
<p>
        <img style="border:0;width:88px;height:31px"
            src="http://jigsaw.w3.org/css-validator/images/vcss"
            alt="CSS Valido!" />
		<img src="http://users.skynet.be/mgueury/mozilla/tidy_32.gif"
		alt="Validated by HTML Validator (based on Tidy) " height="32" width="78"/>
</p>
</footer>