<div class="card col col-lg-5 mx-3 mx-lg-auto p-0">
	<div class="card-header">
		<h3 class="card-title">Ajouter un équipement DMX</h3>
	</div>
	<div class="card-body">
		<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
			<div class="form-group">
				<label for="nom">Nom</label>
				<input type="text" name="nom" class="form-control" id="nom" required />
			</div>
			<div class="form-group">
				<label for="type">Type</label>
				<select name="type" id="type" class="form-control" required>
					<option value="" disabled selected>-- Sélectionnez un type d'équipement --</option>
					<?php if (!empty($datas['typeEquipements'])): ?>
						<?php foreach ($datas['typeEquipements'] as $type): ?>
							<option value="<?= htmlspecialchars($type['idTypeEquipement']) ?>">
								<?php echo htmlspecialchars($type['typeEquipement']) . " (" . $type['nbCanaux'] . " canaux)";  ?>
							</option>
						<?php endforeach; ?>
					<?php else: ?>
						<option value="" disabled>Aucun type disponible</option>
					<?php endif; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="univers">Univers</label>
				<select name="univers" id="univers" class="form-control" required>
					<option value="" disabled selected>-- Sélectionnez un univers --</option>
					<?php if (!empty($datas['universModuleDMXWiFi'])): ?>
						<?php foreach ($datas['universModuleDMXWiFi'] as $univers): ?>
							<option value="<?= htmlspecialchars($univers['univers']) ?>">
								<?php echo htmlspecialchars($univers['univers']) . " (" . $univers['nomBoitier'] . ")";  ?>
							</option>
						<?php endforeach; ?>
					<?php else: ?>
						<option value="" disabled>Aucun univers disponible</option>
					<?php endif; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="canalInitial">Canal initial</label>
				<input type="number" name="canalInitial" class="form-control" id="canalInitial" required />
			</div>
			<input class="btn btn-primary" name="submit" type="submit" value="Envoyer" />
			<a class="btn btn-danger" href="<?php echo ROOT_PATH; ?>equipementDMX">Annuler</a>
		</form>
	</div>
</div>