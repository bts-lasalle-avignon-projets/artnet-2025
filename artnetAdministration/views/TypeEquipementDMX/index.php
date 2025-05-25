<div class="col-12">
	<a class="btn btn-primary btn-share mt-0" href="<?php echo ROOT_PATH; ?>typeEquipementDMX/add">Ajouter</a>
	<?php foreach ($datas as $item) : ?>
		<div class="card mb-4">
			<h3 class="card-header">Type d'équipement : <?php echo $item['typeEquipement']; ?></h3>
			<div class="card-body">
				<div>
					<small class="card-subtitle">Nombre de canaux : <?php echo $item['nbCanaux']; ?></small>
				</div>
				<hr />
				<small class=" card-subtitle">
					<a class="card-link text-primary" href="<?php echo ROOT_PATH; ?>typeEquipementDMX/edit/<?php echo $item['idTypeEquipement']; ?>">Modifier</a>
					<a class="card-link text-danger" href="<?php echo ROOT_PATH; ?>typeEquipementDMX/delete/<?php echo $item['idTypeEquipement']; ?>">Supprimer</a>
				</small>
			</div>
		</div>
	<?php endforeach; ?>
</div>