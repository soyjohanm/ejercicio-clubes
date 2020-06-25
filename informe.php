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
  <center><a href="#" id="volver" class="btn-flat">VOLVER</a></center>
  <hr style="background-color: black; height: 15px;">
  <div class="row">
    <button type="button" class="btn btn-flat right modal-trigger" data-target="modal1">+ Nuevo tratamiento</button><br>
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
