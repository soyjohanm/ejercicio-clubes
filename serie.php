<?php
  require './archivos/conexion.php';
  session_start();
  if (!empty($_POST['id'])) {
    $_SESSION['serie'] = $_POST['id'];
  }
  $sql = "SELECT nombre FROM series WHERE id=".$_SESSION['serie']."";
  $resultado = mysqli_query($conexion, $sql) or die ("Error en el query database.");
  $fila = mysqli_fetch_array($resultado);
  mysqli_free_result($resultado);
?>
<div class="container center">
  <h1 style="text-transform: uppercase; text-align: center;"><?php echo $fila['nombre']; ?></h1>
  <a href="./" class="btn-flat left">VOLVER</a>
  <button type="button" class="btn-flat right modal-trigger" data-target="nuevoJugador">+ NUEVO JUGADOR</button><br><br>
  <hr style="background-color: black; height: 15px;">
  <div class="row">
    <div class="input-field col l12 m12 s12">
      <input id="filtro" type="text" onkeyup="filtrar()">
      <label for="filtro">Ingrese un nombre para comenzar a filtrar.</label>
    </div>
    <table class="striped centered" id='tabla'>
      <thead style="background-color: black; color: white;">
        <tr>
          <th>#</th>
          <th>Posición</th>
          <th>Jugador</th>
          <th class="hide-on-med-and-down">RUT</th>
          <th class="hide-on-med-and-down">Fecha de nacimiento</th>
          <th>Edad</th>
          <th>Informes</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($conexion->query('SELECT * FROM jugadores INNER JOIN posicion ON jugadores.id=posicion.id
          WHERE serieActual='.$_SESSION['serie'].'') as $serie): ?>
          <tr>
            <td><?php echo $serie['id']; ?></td>
            <td><?php echo $serie['posicion']; ?></td>
            <td><?php echo ucwords($serie['nombre'])." ".ucwords($serie['apellido1'])." ".ucwords($serie['apellido2']); ?></td>
            <td class="hide-on-med-and-down"><?php echo $serie['rut']; ?></td>
            <td class="hide-on-med-and-down"><?php echo date("d-m-Y", strtotime($serie['fechaNacimiento'])); ?></td>
            <?php $edad = date_diff(date_create($serie['fechaNacimiento']), date_create(date("Y-m-d"))); ?>
            <td><?php echo $edad->format('%y'); ?></td>
            <td><?php echo "(".$serie['informes'].") ".(($serie['informes']>1) ? "informes" : "informe"); ?></td>
            <td>
              <button class='btn-flat' id='editar' data-id="<?php echo $serie['id']; ?>">
                <?php echo (($serie['informes']==0) ? "Agregar" : "Editar"); ?>
              </button>
              <button class='btn-flat' id='eliminar' data-id="<?php echo $serie['id']; ?>">Eliminar</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal" id="nuevoJugador">
  <div class="modal-content">
    <center>
      <h4 style="text-transform: uppercase;">Añadir nuevo jugador<span class="right modal-close" title="Cerrar">&times;</span></h4>
    </center>
  </div>
  <div class="divider"></div>
  <form method="post" role="form">
    <div class="container" style="width: 90% !important;">
      <div class="row">
        <div class="col l6 m6 s12">
          <fieldset>
            <legend>Nombre</legend>
            <input name="nombreJugador" type="text" placeholder="Nombre del jugador.">
          </fieldset>
        </div>
        <div class="col l6 m6 s12">
          <fieldset>
            <legend>Apellidos</legend>
            <input name="apellidoJugador" type="text" placeholder="Apellidos del jugador.">
          </fieldset>
        </div>
        <div class="col l4 m6 s12">
          <fieldset>
            <legend>RUT</legend>
            <input name="rutJugador" type="number" min="0" placeholder="RUT del jugador.">
          </fieldset>
        </div>
        <div class="col l4 m6 s12">
          <fieldset>
            <legend>Fecha de nacimiento</legend>
            <input name="nacimientoJugador" type="date" min="0">
          </fieldset>
        </div>
        <div class="col l4 m12 s12">
          <fieldset>
            <legend>Posición</legend>
            <input name="posicionJugador" type="text" placeholder="Posición del jugador.">
          </fieldset>
        </div>
      </div>
    </div>
  </form>
</div>

<script type="text/javascript">
  function filtrar() {
    var input, filtro, table, tr, td, i, txtValor;
    input = document.getElementById('filtro');
    filtro = input.value.toUpperCase();
    table = document.getElementById('tabla');
    tr = document.getElementsByTagName('tr');
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName('td')[2];
      if (td) {
        txtValor = td.textContent || td.innerText;
        if (txtValor.toUpperCase().indexOf(filtro) > -1) { tr[i].style.display = ''; }
        else { tr[i].style.display = 'none'; }
      }
    }
  };
  $(document).ready(function(){
    $('.modal').modal();
  });
  $(document).on('click', '#editar', function() {
    $.ajax({
      type: 'POST',
      url: 'informe.php',
      data: { id: $(this).attr('data-id') },
      success: function(data) {
        $('#cuerpo').html(data);
      }
    });
  });
</script>
