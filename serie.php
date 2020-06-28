<?php
  require './archivos/conexion.php';
  session_start();
  if (!empty($_POST['id'])) {
    $_SESSION['serie'] = $_POST['id'];
  }
  $sql = "SELECT nombre, sexo FROM series WHERE id=".$_SESSION['serie']."";
  $resultado = mysqli_query($conexion, $sql) or die ("Error en el query database.");
  $fila = mysqli_fetch_array($resultado);
  mysqli_free_result($resultado);
  $_SESSION['sexo'] = $fila['sexo'];
  $i = 0;
?>
<div class="container center">
  <h1 style="text-transform: uppercase; text-align: center;">
    <?php echo $fila['nombre']; echo ($_SESSION['sexo'] == 'F') ? ' Femenino' : ' Masculino'; ?>
  </h1>
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
          <?php $i++; ?>
          <tr id="<?php echo $serie['id']; ?>">
            <td><?php echo $i; ?></td>
            <td><?php echo $serie['posicion']; ?></td>
            <td><?php echo ucwords($serie['nombre'])." ".ucwords($serie['apellido1'])." ".ucwords($serie['apellido2']); ?></td>
            <td class="hide-on-med-and-down"><?php echo $serie['rut']; ?></td>
            <td class="hide-on-med-and-down"><?php echo date("d-m-Y", strtotime($serie['fechaNacimiento'])); ?></td>
            <?php $edad = date_diff(date_create($serie['fechaNacimiento']), date_create(date("Y-m-d"))); ?>
            <td><?php echo $edad->format('%y'); ?></td>
            <td><?php echo "(".$serie['informes'].") ".(($serie['informes']>1) ? "informes" : "informe"); ?></td>
            <td>
              <button class="btn-flat" id='agregar' data-id="<?php echo $serie['id']; ?>"><svg style="width: 2.2rem; height: 2.6rem;"><use href="./iconos.svg#agrega"/></svg></button>
              <button class="btn-flat" id='eliminar' data-id="<?php echo $serie['id']; ?>"><svg style="width: 2.5rem; height: 2.6rem;"><use href="./iconos.svg#elimina"/></svg></button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal" id="nuevoJugador">
  <form method="post" role="form" autocomplete="off" id="formularioJugador" name="formularioJugador">
    <div class="modal-content">
      <h4 style="text-transform: uppercase;">Añadir nuevo jugador<span class="right modal-close" title="Cerrar">&times;</span></h4>
      <div class="divider"></div><br>
      <div class="container" style="width: 90% !important;">
        <div class="row">
          <div class="input-field col l6 m6 s12">
            <fieldset>
              <legend>Nombre</legend>
              <input name="nombreJugador" type="text" placeholder="Nombre del jugador." required>
            </fieldset>
          </div>
          <div class="input-field col l6 m6 s12">
            <fieldset>
              <legend>Apellidos</legend>
              <input name="apellidoJugador" type="text" placeholder="Apellidos del jugador." required>
            </fieldset>
          </div>
          <div class="input-field col l4 m6 s12">
            <fieldset>
              <legend>RUT</legend>
              <input name="rutJugador" type="number" min="0" placeholder="RUT del jugador." required>
            </fieldset>
          </div>
          <div class="input-field col l4 m6 s12">
            <fieldset>
              <legend>Fecha de nacimiento</legend>
              <input name="nacimientoJugador" type="date" max="<?php echo date('Y-m-d'); ?>" required>
            </fieldset>
          </div>
          <div class="input-field col l4 m12 s12">
            <fieldset>
              <legend>Posición</legend>
              <input name="posicionJugador" type="text" class="autocomplete" placeholder="Posición del jugador." required>
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
    $(function() {
      $.ajax({
        type: 'GET',
        url: './archivos/funciones.php',
        data: { funcion: 'datos' },
        success: function(response) {
          var posicionArray = response;
          var datosPosicion = {};
          for (var i = 0; i < posicionArray.length; i++) {
            datosPosicion[posicionArray[i].posicion] = posicionArray[i].flag;
          }
          $('input.autocomplete').autocomplete({
            data: datosPosicion,
            limit: 1
          });
        }
      });
    });
  });
  $(document).on('click', '#agregar', function() {
    $.ajax({
      type: 'POST',
      url: 'informe.php',
      data: { id: $(this).attr('data-id') },
      success: function(data) {
        $('#cuerpo').html(data);
      }
    });
  });
  $(document).on('click', '#eliminar', function() {
    id = $(this).attr('data-id');
    var opcion = confirm("¿Realmente desea eliminar este registro? Esta opción no se puede revertir.");
    if (opcion == true) {
      $.ajax({
        type: 'POST',
        url: 'archivos/funciones.php',
        data: { id: $(this).attr('data-id'), funcion: 'eliminaJugador' },
        success: function(data) {
          $('#'+id).html(data);
        }
      });
    } else {}
  });
  $('#formularioJugador').submit(function(event) {
    var parametros = $(this).serialize() + '&funcion=nuevoJugador';
    $.ajax({
      type: "POST",
      url: "archivos/funciones.php",
      data: parametros,
      success: function(data) {
        if (data == 'correcto') {
          M.toast({html: 'Jugador añadido correctamente.', classes: 'green'});
          $("#cuerpo").load("serie.php");
        }
      }
    });
    event.preventDefault();
  });
</script>
