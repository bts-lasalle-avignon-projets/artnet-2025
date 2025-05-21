<div class="col-12">
	<a class="btn btn-primary btn-share mt-0" href="<?php echo ROOT_PATH; ?>broker/add">Ajouter</a>
	<?php foreach ($datas as $item) : ?>
		<div class="card mb-4">
			<h3 class="card-header">Broker MQTT : <?php echo $item['hostname'] . ":" . $item['port']; ?></h3>
			<div class="card-body">
				<div>
					<small class="card-subtitle">Actif : <?php echo $item['actif']; ?></small>
				</div>
				<!-- Affichage de l'utilisateur -->
				<?php if (!empty($item['username'])) : ?>
					<img src="data:<?php echo htmlspecialchars($item['username']); ?>
				<?php endif; ?>
				<hr />
				<small class=" card-subtitle">
					<a class="card-link text-success" href="<?php echo ROOT_PATH; ?>broker/test/<?php echo $item['idBrokerMQTT']; ?>">Tester</a>
					<a class="card-link text-primary" href="<?php echo ROOT_PATH; ?>broker/edit/<?php echo $item['idBrokerMQTT']; ?>">Modifier</a>
					<a class="card-link text-danger" href="<?php echo ROOT_PATH; ?>broker/delete/<?php echo $item['idBrokerMQTT']; ?>">Supprimer</a>
					</small>
			</div>
		</div>
	<?php endforeach; ?>
</div>