<div class="col col-lg-8 mt-5 mx-3 mx-lg-auto text-center">
	<h1><?php echo TITRE_SITE; ?></h1>
	
	<div>
		<form method="POST" action="<?php $_SERVER['PHP_SELF'];?>">
			<label>Hostname/IP :</label>
			<input type="text" name="hostname" required><br>

			<label>Port :</label>
			<input type="number" name="port" required><br>

			<button type="submit">Ajouter Broker</button>
		</form>
	</div>
</div>