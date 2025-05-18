<div class="card col col-lg-5 mx-3 mx-lg-auto p-0">
	<div class="card-header">
		<h3 class="card-title">Éditer un équipement DMX</h3>
	</div>
	<div class="card-body">
		<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
			<div class="form-group">
				<label for="nom">Nom</label>
				<input type="text" name="nom" class="form-control" id="nom" value="<?php echo $datas['nomEquipement']; ?>" />
			</div>
			<div class="form-group">
				<label for="type">Type</label>
				<select name="type" id="type" class="form-control" required>
					<option value="" disabled selected>-- Sélectionnez un type d'équipement --</option>
					<?php if (!empty($datas['typeEquipements'])): ?>
						<?php foreach ($datas['typeEquipements'] as $type): ?>
							<?php if ($type['idTypeEquipement'] == $datas['idTypeEquipement']): ?>
								<option value="<?= htmlspecialchars($type['idTypeEquipement']) ?>" selected>
									<?php echo htmlspecialchars($type['typeEquipement']) . " (" . $type['nbCanaux'] . " canaux)";  ?>
								</option>
							<?php else: ?>
								<option value="<?= htmlspecialchars($type['idTypeEquipement']) ?>">
									<?php echo htmlspecialchars($type['typeEquipement']) . " (" . $type['nbCanaux'] . " canaux)";  ?>
								</option>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php else: ?>
						<option value="" disabled>Aucun type disponible</option>
					<?php endif; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="univers">Univers</label>
				<input type="number" name="univers" class="form-control" id="univers" value="<?php echo $datas['univers']; ?>" />
			</div>
			<div class="form-group">
				<label for="canalInitial">Canal initial</label>
				<input type="number" name="canalInitial" class="form-control" id="canalInitial" value="<?php echo $datas['canalInitial']; ?>" />
			</div>
			<div class="form-group">
				<label for="nbCanaux">Nombre Canaux</label>
				<input type="number" name="nbCanaux" class="form-control" id="nbCanaux" value="<?php echo $datas['nbCanaux']; ?>" />
			</div>
			<input type="hidden" name="idEquipement" value="<?php echo $datas['idEquipement']; ?>" />
			<input class="btn btn-primary" name="submit" type="submit" value="Envoyer" />
			<a class="btn btn-danger" href="<?php echo ROOT_PATH; ?>equipementDMX">Annuler</a>
		</form>
	</div>
</div>