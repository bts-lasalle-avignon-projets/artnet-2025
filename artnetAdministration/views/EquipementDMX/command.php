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
                <label for="canaux">Canaux : </label>
                <div class="container border h-100 pb-2 justify-content-center align-items-center" name="canaux">
                    <div class="d-flex flex-wrap">
                        <?php for ($i = $datas['canalInitial']; $i < ($datas['canalInitial'] + $datas['nbCanaux']); $i++): ?>
                            <?php echo "<div class=\"p-2 ml-auto text-center\" style=\"width: " . (100 / $datas['nbCanaux']) . "%;\">Canal " . $i . "</div>";  ?>
                        <?php endfor; ?>
                    </div>
                    <div class="d-flex flex-wrap justify-content-center">
                        <?php for ($i = $datas['canalInitial']; $i < ($datas['canalInitial'] + $datas['nbCanaux']); $i++): ?>
                            <?php echo "<div class=\"p-2 ml-auto text-center\" style=\"width: " . (100 / $datas['nbCanaux']) . "%;\"><input id=\"canal-" . $i . "\" name=\"canal-" . $i . "\" class=\"slider\" type=\"text\" data-slider-min=\"0\" data-slider-max=\"255\" data-slider-step=\"1\" data-slider-value=\"0\" data-slider-orientation=\"vertical\" /></div>";  ?>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
            <input type="hidden" name="idEquipement" value="<?php echo $datas['idEquipement']; ?>" />
            <input class="btn btn-primary" name="submit" type="submit" value="Publier" />
            <a class="btn btn-danger" href="<?php echo ROOT_PATH; ?>equipementDMX">Annuler</a>
        </form>
    </div>
</div>