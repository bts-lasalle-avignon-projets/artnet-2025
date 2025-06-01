<div class="card col col-lg-5 mx-3 mx-lg-auto p-0">
	<div class="card-header">
		<h3 class="card-title">Ajouter un broker MQTT</h3>
	</div>
	<div class="card-body">
		<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
			<div class="form-group">
				<label for="hostname">Hostname/IP</label>
				<input type="text" name="hostname" class="form-control" id="hostname" required />
			</div>
			<div class="form-group">
				<label for="port">Port</label>
				<input type="number" name="port" class="form-control" id="port" required />
			</div>
			<input class="btn btn-primary" name="submit" type="submit" value="Envoyer" />
			<a class="btn btn-danger" href="<?php echo ROOT_PATH; ?>broker">Annuler</a>
		</form>
	</div>
</div>