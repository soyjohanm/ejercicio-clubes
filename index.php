<?php
  require './archivos/conexion.php';
  if (!empty($_COOKIE['serie'])) {
    unset($_COOKIE['serie']);
  }
  if (isset($_POST['guardar'])) {
    $nombre = mb_strtoupper($_POST['nombreSerie']);
    $sexos = $_POST['genero'];
    foreach ($sexos as $sexo) {
      $sql = "INSERT INTO series(nombre,sexo) VALUES ('$nombre','$sexo')";
      $resultado = $conexion->query($sql) or die ('Error en el query database');
    }
    if ($resultado) echo "<script>window.onload = function(){ M.toast({html: 'Registro guardado correctamente.', classes: 'green'}); }</script>";
  }
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clubes</title>
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/materialize.js"></script>
    <link rel="stylesheet" href="./css/materialize.css">
    <link rel="stylesheet" href="./css/estilos.css">
  </head>
  <body>
    <main id="cuerpo">
      <div class="container center">
        <a href="./"><h1 style="text-transform: uppercase; text-align: center;">Módulo prueba</h1></a>
        <button type="button" class="btn-flat right modal-trigger" data-target="nuevaSerie">+ Nueva serie</button><br><br>
        <hr style="background-color: black; height: 15px;">
        <div class="row">
          <?php foreach ($conexion->query('SELECT * FROM series ORDER BY nombre') as $serie): ?>
            <div class="col l3 m4 s6">
              <div class="card grey darken-4" id="serie" data-id="<?php echo $serie['id']; ?>">
                <div class="card-content white-text center">
                  <svg style="width: 5rem; height: 8rem;"><use href="./iconos.svg#escudo"/></svg>
                  <div class="divider"></div>
                  <h5 class="card-tittle"><?php echo $serie['nombre']; echo ($serie['sexo'] == 'F') ? ' Femenino' : ' Masculino'; ?></h5>
                  <div class="divider"></div>
                  <span><?php echo "(".$serie['jugadores'].") ".(($serie['jugadores']>1) ? "Jugadores" : "Jugador"); ?></span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </main>
  </body>

  <div class="modal" id="nuevaSerie">
    <form method="post" role="form" autocomplete="off" action="<?php $_SERVER['PHP_SELF']; ?>">
      <div class="modal-content">
        <h4 style="text-transform: uppercase;">Añadir nueva serie<span class="right modal-close" title="Cerrar">&times;</span></h4>
        <div class="divider"></div><br>
        <div class="container" style="width: 90% !important;">
          <div class="row">
            <div class="input-field col l6 m12 s12">
              <fieldset style="padding-top: 5% !important; padding-bottom: 9% !important;">
                <legend>Nombre de la serie</legend>
                <input name="nombreSerie" type="text" placeholder="Nombre de la serie." maxlength="20" required>
              </fieldset>
            </div>
            <div class="input-field col l6 m12 s12">
              <fieldset style="padding-top: 5% !important;">
                <legend>Género</legend>
                <div class="row genero">
                  <div class="col l6 m6 s6">
                    <p>
                      <label><input type='checkbox' name='genero[]' value='F' class="filled-in" required><span>Femenino</span></label>
                    </p>
                  </div>
                  <div class="col l6 m6 s6">
                    <p>
                      <label><input type='checkbox' name='genero[]' value='M' class="filled-in" required><span>Masculino</span></label>
                    </p>
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="container">
          <button type="submit" name="guardar" id="guardar" class="btn-flat">Guardar</button>
        </div>
      </div>
    </form>
  </div>

  <script type="text/javascript">
    $(document).ready(function(){
      $('.modal').modal();
    });
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
    $(function(){
      var requiredCheckBox = $('.genero :checkbox[required]');
      requiredCheckBox.change(function(){
        if (requiredCheckBox.is(':checked')) { requiredCheckBox.removeAttr('required'); }
        else { requiredCheckBox.attr('required', 'required'); }
      });
    });
  </script>
</html>
