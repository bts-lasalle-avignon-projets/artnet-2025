<div class="card col col-lg-5 mx-3 mx-lg-auto p-0">
	<div class="card-header">
		<h3 class="card-title">Test Broker MQTT</h3>
	</div>
	<div class="card-body">
		<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">

			<div class="form-group">
				Hostname/IP : <?php echo $datas['hostname']; ?>
			</div>
			<div class="form-group">
				Port : <?php echo $datas['port']; ?>
			</div>
			<div class="form-group">
				Topic : <?php echo $datas['topic'] . "/test"; ?>
			</div>
			<input type="hidden" name="idBrokerMQTT" value="<?php echo $datas['idBrokerMQTT']; ?>" />
			<input class="btn btn-primary" name="submit" type="submit" value="Connecter" />
			<input class="btn btn-primary" name="submit" type="submit" value="Publier" />
			<input class="btn btn-primary" name="submit" type="submit" value="Souscrire" />
			<input class="btn btn-primary" name="submit" type="submit" value="Recevoir" />
			<a class="btn btn-danger" href="<?php echo ROOT_PATH; ?>broker">Annuler</a>
		</form>
	</div>
</div>