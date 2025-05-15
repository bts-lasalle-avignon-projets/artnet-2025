<div class="card col col-lg-5 mx-3 mx-lg-auto p-0">
	<div class="card-header">
		<h3 class="card-title">Éditer un équipement DMX</h3>
	</div>
	<div class="card-body">
		<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
			<div class="form-group">
				<label for="nom">Nom</label>
				<input type="text" name="nom" class="form-control" id="nom" required />
			</div>
			<div class="form-group">
				<label for="type">Type</label>
				<input type="text" name="type" class="form-control" id="type" required />
			</div>
			<div class="form-group">
				<label for="univers">Univers</label>
				<input type="number" name="univers" class="form-control" id="univers" required />
			</div>
			<div class="form-group">
				<label for="canalInitial">Canal initial</label>
				<input type="number" name="canalInitial" class="form-control" id="canalInitial" required />
			</div>
			<div class="form-group">
				<label for="nbCanaux">Nombre Canaux</label>
				<input type="number" name="nbCanaux" class="form-control" id="nbCanaux" required />
			</div>
			<input type="hidden" name="idEquipement" value="<?php echo $datas['idEquipement']; ?>" />
			<input class="btn btn-primary" name="submit" type="submit" value="Envoyer" />
			<a class="btn btn-danger" href="<?php echo ROOT_PATH; ?>equipementDMX">Annuler</a>
		</form>
	</div>
</div>