<div class="card col col-lg-5 mx-3 mx-lg-auto p-0">
	<div class="card-header">
		<h3 class="card-title">Éditer un type d'équipement</h3>
	</div>
	<div class="card-body">
		<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
			<div class="form-group">
				<label for="nom">Nom</label>
				<input type="text" name="nom" class="form-control" id="nom" value="<?php echo $datas['typeEquipement']; ?>" />
			</div>
			<div class="form-group">
				<label for="nbCanaux">Nombre de canaux</label>
				<input type="number" name="nbCanaux" class="form-control" id="nbCanaux" value="<?php echo $datas['nbCanaux']; ?>" />
			</div>
			<input type="hidden" name="idTypeEquipement" value="<?php echo $datas['idTypeEquipement']; ?>" />
			<input class="btn btn-primary" name="submit" type="submit" value="Envoyer" />
			<a class="btn btn-danger" href="<?php echo ROOT_PATH; ?>typeEquipementDMX">Annuler</a>
		</form>
	</div>
</div>