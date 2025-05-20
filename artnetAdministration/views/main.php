<!DOCTYPE html>
<html>

<head>
  <title><?php echo TITRE_SITE; ?></title>

  <link rel="stylesheet" href="<?php echo URL_PATH; ?>assets/css/bootstrap.css">
  <link rel="stylesheet" href="<?php echo URL_PATH; ?>assets/css/style.css?ver=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.min.css" integrity="sha512-3q8fi8M0VS+X/3n64Ndpp6Bit7oXSiyCnzmlx6IDBLGlY5euFySyJ46RUlqIVs0DPCGOypqP8IRk/EyPvU28mQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.css" integrity="sha512-SZgE3m1he0aEF3tIxxnz/3mXu/u/wlMNxQSnE0Cni9j/O8Gs+TjM9tm1NX34nRQ7GiLwUEzwuE3Wv2FLz2667w==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="<?php echo URL_PATH; ?>assets/js/bootstrap.js"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.js" integrity="sha512-tCkLWlSXiiMsUaDl5+8bqwpGXXh0zZsgzX6pB9IQCZH+8iwXRYfcCpdxl/owoM6U4ap7QZDW4kw7djQUiQ4G2A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js" integrity="sha512-f0VlzJbcEB6KiW8ZVtL+5HWPDyW1+nJEjguZ5IVnSQkvZbwBt2RfCBY0CBO1PsMAqxxrG4Di6TfsCPP3ZRwKpA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>

<body>

  <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <a class="navbar-brand" href="<?php echo URL_PATH; ?>"><?php echo TITRE_SITE; ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URL_PATH; ?>">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URL_PATH; ?>broker">Broker MQTT</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URL_PATH; ?>moduleDMXWiFi">Module DMX WiFi</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URL_PATH; ?>equipementDMX">Équipement DMX</a>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">
        <?php if (isset($_SESSION['is_logged_in'])) : ?>
          <li class="nav-item">
            <span class="navbar-text">Bienvenue, <?php echo $_SESSION['user_data']['name']; ?></span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URL_PATH; ?>operateurs/logout">Logout</a>
          </li>
        <?php else : ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URL_PATH; ?>operateurs/login">Connexion</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URL_PATH; ?>operateurs/register">S'enregistrer</a>
          </li>
        <?php endif; ?>
      </ul>

    </div>
  </nav>

  <main role="main" class="container-fluid">
    <div class="row">
      <?php Messages::display(); ?>
      <?php require($view); ?>
    </div>
  </main><!-- /.container -->

  <script>
    $(document).ready(function() {
      /*$('.slider').slider();*/
      $(".slider").slider({
        reversed: true
      });
      /*$("#canal-132").slider({
        reversed: true
      });*/
    });
  </script>
</body>

</html>