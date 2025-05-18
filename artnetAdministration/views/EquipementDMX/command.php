<div class="card col col-lg-5 mx-3 mx-lg-auto p-0">
    <div class="card-header">
        <h3 class="card-title">Commander un équipement DMX</h3>
    </div>
    <div class="card-body">
        <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">

            <div class="form-group">
                Équipement : <?php echo $datas['nomEquipement']; ?>
            </div>
            <div class="form-group">
                Type : <?php echo htmlspecialchars($datas['typeEquipement']) . " (" . $datas['nbCanaux'] . " canaux)";  ?>
            </div>
            <div class="form-group">
                Canaux : TODO
            </div>
            <input type="hidden" name="idEquipement" value="<?php echo $datas['idEquipement']; ?>" />
            <input class="btn btn-primary" name="submit" type="submit" value="Publier" />
            <a class="btn btn-danger" href="<?php echo ROOT_PATH; ?>equipementDMX">Annuler</a>
        </form>
    </div>
</div>