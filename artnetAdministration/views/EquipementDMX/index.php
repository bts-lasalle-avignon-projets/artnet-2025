<div class="col-12">
	<a class="btn btn-primary btn-share mt-0" href="<?php echo ROOT_PATH; ?>equipementDMX/add">Ajouter</a>
	<?php foreach ($datas as $item) : ?>
		<div class="card mb-4">
			<h3 class="card-header">Equipement : <?php echo $item['nomEquipement']; ?></h3>
			<div class="card-body">
				<div>
					<small class="card-subtitle">Univers : <?php echo $item['univers']; ?></small>
					<br>
					<small class="card-subtitle">Type : <?php echo $item['typeEquipement']; ?></small>
					<br>
					<small class="card-subtitle">Canal Initial : <?php echo $item['canalInitial']; ?></small>
					<br>
					<small class="card-subtitle">Nombre Canaux : <?php echo $item['nbCanaux']; ?></small>
				</div>
				<!-- Affichage de l'utilisateur -->
				<?php if (!empty($item['username'])) : ?>
					<img src="data:<?php echo htmlspecialchars($item['username']); ?>
				<?php endif; ?>
				<hr />
                <small class=" card-subtitle">
					<a class="card-link text-success" href="<?php echo ROOT_PATH; ?>equipementDMX/command/<?php echo $item['idEquipement']; ?>">Commander</a>
					<a class="card-link text-primary" href="<?php echo ROOT_PATH; ?>equipementDMX/edit/<?php echo $item['idEquipement']; ?>">Modifier</a>
					<a class="card-link text-danger" href="<?php echo ROOT_PATH; ?>equipementDMX/delete/<?php echo $item['idEquipement']; ?>">Supprimer</a>
					</small>
			</div>
		</div>
	<?php endforeach; ?>
</div>