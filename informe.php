<?php
  require './archivos/conexion.php';
  session_start();
  if (!empty($_POST['id'])) {
    $_SESSION['jugador'] = $_POST['id'];
  }
  $sql = "SELECT nombre, apellido1, apellido2 FROM jugadores WHERE id=".$_SESSION['jugador']."";
  $resultado = mysqli_query($conexion, $sql) or die ("Error en el query database.");
  $fila = mysqli_fetch_array($resultado);
  mysqli_free_result($resultado);
?>
<div class="container center">
  <h1 style="text-align: center"><?php echo ucwords($fila['nombre']." ".$fila['apellido1']." ".$fila['apellido2']); ?></h1>
  <a href="#" id="volver" class="btn-flat left">VOLVER</a>
  <button type="button" class="btn-flat right modal-trigger" data-target="nuevoInforme">+ Nuevo tratamiento</button><br><br>
  <hr style="background-color: black; height: 15px;">
  <div class="row">
    <table class="striped centered">
      <thead style="background-color: black; color: white">
        <tr>
          <th>#</th>
          <th>Fecha</th>
          <th>Profesional</th>
          <th>Patología</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($conexion->query('SELECT * FROM informes WHERE jugador='.$_SESSION['jugador'].'') as $informe): ?>
          <tr>
            <td><?php echo $informe['id']; ?></td>
            <td><?php echo date("d-m-Y", strtotime($informe['fecha'])); ?></td>
            <td><?php echo $informe['doctor']; ?></td>
            <td><?php echo $informe['patologia']; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal" id="nuevoInforme">
  <form method="post" role="form" autocomplete="off" id="formularioInforme" name="formularioInforme">
    <div class="modal-content">
      <h4 style="text-transform: uppercase;">Añadir nuevo informe<span class="right modal-close" title="Cerrar">&times;</span></h4>
      <div class="divider"></div><br>
      <div class="container" style="width: 90% !important;">
        <div class="row">
          <div class="input-field col l9 m8 s7">
            <fieldset>
              <legend>Patología</legend>
              <input name="informePatologia" type="text" placeholder="Patología del jugador." required>
            </fieldset>
          </div>
          <div class="input-field col l3 m4 s5">
            <fieldset>
              <legend>Fecha</legend>
              <input name="informeFecha" type="date" value="<?php echo date('Y-m-d'); ?>" required>
            </fieldset>
          </div>
          <div class="col l8 m12 s12">
            <div class="input-field col l12 m6 s12" style="width: 104%; left: -2%;">
              <fieldset>
                <legend>Anamnesis</legend>
                <textarea name="informeAnamnesis" class="materialize-textarea" placeholder="Anamnesis." required></textarea>
              </fieldset>
            </div>
            <div class="input-field col l12 m6 s12" style="width: 104%; left: -2%;">
              <fieldset>
                <legend>Descripción del tratamiento</legend>
                <textarea name="informeDescripcion" class="materialize-textarea" placeholder="Descripción." required></textarea>
              </fieldset>
            </div>
          </div>
          <div class="input-field col l4 m12 s12">
            <fieldset>
              <legend>Fisioterapia</legend>
              <div class="row">
                <div class="col l6 m4 s12">
                  <p><label><input type="checkbox" class="filled-in" name="informeFisioterapia[]"><span>US</span></label></p>
                  <p><label><input type="checkbox" class="filled-in" name="informeFisioterapia[]"><span>TENS</span></label></p>
                  <p><label><input type="checkbox" class="filled-in" name="informeFisioterapia[]"><span>TIF</span></label></p>
                  <p><label><input type="checkbox" class="filled-in" name="informeFisioterapia[]"><span>RUSA</span></label></p>
                  <p><label><input type="checkbox" class="filled-in" name="informeFisioterapia[]"><span>MICRO</span></label></p>
                </div>
                <div class="col l6 m4 s12">
                  <p><label><input type="checkbox" class="filled-in" name="informeFisioterapia[]"><span>TECAR</span></label></p>
                  <p><label><input type="checkbox" class="filled-in" name="informeFisioterapia[]"><span>CHC</span></label></p>
                  <p><label><input type="checkbox" class="filled-in" name="informeFisioterapia[]"><span>CRIO</span></label></p>
                  <p><label><input type="checkbox" class="filled-in" name="informeFisioterapia[]"><span>NEUM</span></label></p>
                  <p><label><input type="checkbox" class="filled-in" name="informeFisioterapia[]"><span>SWT</span></label></p>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="input-field col l12 m12 s12">
            <fieldset>
              <legend>Ejercicios</legend>
            </fieldset>
          </div>
          <div class="input-field col l12 m12 s12">
            <fieldset>
              <legend>Elongaciones</legend>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <div class="container">
        <button type="submit" name="guardar" id="guardar" class="btn-flat modal-close">Guardar</button>
      </div>
    </div>
  </form>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('.modal').modal();
    $("#volver").click(function(event){
      event.preventDefault();
      $("#cuerpo").load("serie.php");
    });
  });
  $('#agrega').submit(function(event) {
    var parametros = $(this).serialize() + '&funcion=agrega';
    $.ajax({
      type: "POST",
      url: "archivo/funciones.php",
      data: parametros,
      success: function(data) {
        M.toast({html: data});
      }
    });
    event.preventDefault();
  });
</script>
