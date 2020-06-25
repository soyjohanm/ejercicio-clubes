<?php
  require './archivos/conexion.php';
  if (!empty($_COOKIE['serie'])) {
    unset($_COOKIE['serie']);
  }
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Clubes</title>
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/materialize.js"></script>
    <link rel="stylesheet" href="./css/materialize.css">
    <link rel="stylesheet" href="./css/estilos.css">
  </head>
  <body>
    <main id="cuerpo">
      <div class="container center">
        <h1 style="text-transform: uppercase; text-align: center;">Módulo prueba</h1>
        <hr style="background-color: black; height: 15px;">
        <div class="row">
          <?php foreach ($conexion->query('SELECT * FROM series') as $serie): ?>
            <div class="col l3">
              <div class="card grey darken-4" id="serie" data-id="<?php echo $serie['id']; ?>">
                <div class="card-content white-text">
                  <h5 class="card-tittle"><?php echo $serie['nombre']; ?></h5><br>
                  <span>(<?php echo $serie['jugadores']; ?>) Jugadores</span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </main>
  </body>
  <script type="text/javascript">
    $(document).on('click', '#serie', function() {
      $.ajax({
        type: 'POST',
        url: 'serie.php',
        data: { id: $(this).attr('data-id') },
        success: function(data) {
          $('#cuerpo').html(data);
        }
      });
    });
  </script>
</html>
