<div class="card col col-md-6 mx-3 mx-md-auto p-3 text-center">
	<div class="card-text">
		<p>Êtes-vous sûr de vouloir supprimer cet équipement ? Cela ne peut pas être annulé.
		</p>
	</div>
	<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
		<input type="hidden" name="idEquipement" value="<?php echo $datas; ?>" />
		<input type="submit" name="submit" class="btn btn-danger" value="Oui" />
		<a class="btn btn-light" href="<?php echo ROOT_PATH; ?>equipementDMX">Non</a>
	</form>
</div>