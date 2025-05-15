<div class="col-12">
	<?php foreach ($datas as $item) : ?>
		<div class="card mb-4">
			<h3 class="card-header">Module DMX WiFi : <?php echo $item['nomBoitier']; ?></h3>
			<div class="card-body">
				<div>
					<small class="card-subtitle">Univers : <?php echo $item['univers']; ?></small>
					<br>
					<small class="card-subtitle">IP : <?php echo $item['adresseIP']; ?></small>
					<br>
					<small class="card-subtitle">MAC : <?php echo $item['adresseMAC']; ?></small>
					<br>
					<small class="card-subtitle">RSSI : <?php echo $item['rssi']; ?></small>
					<br>
					<small class="card-subtitle">Actif : <?php echo $item['actif']; ?></small>
				</div>
				<!-- Affichage de l'utilisateur -->
				<?php if (!empty($item['username'])) : ?>
					<img src="data:<?php echo htmlspecialchars($item['username']); ?>
				<?php endif; ?>
				<hr />
				<small class=" card-subtitle">
					<input class="btn btn-primary" name="submit" type="submit" value="Test connection" />
					</small>
			</div>
		</div>
	<?php endforeach; ?>
</div>