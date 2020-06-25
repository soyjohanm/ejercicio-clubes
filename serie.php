<?php
  require './archivos/conexion.php';
  session_start();
  if (!empty($_POST['id'])) {
    $_SESSION['serie'] = $_POST['id'];
  }
?>
<div class="container center">
  <h1 style="text-transform: uppercase; text-align: center;">Módulo prueba</h1>
  <div class="row">
    <div class="col l6">
      <a href="#" class="btn-flat right">NUEVO JUGADOR</a>
    </div>
    <div class="col l6">
      <a href="./" class="btn-flat left">VOLVER</a>
    </div>
  </div>
  <hr style="background-color: black; height: 15px;">
  <div class="row">
    <div class="input-field col l12">
      <input id="filtro" type="text" onkeyup="filtrar()">
      <label for="filtro">Ingrese un nombre para comenzar a filtrar.</label>
    </div>
    <table class="striped centered" id='tabla'>
      <thead style="background-color: black; color: white;">
        <tr>
          <th>#</th>
          <th>Posición</th>
          <th>Jugador</th>
          <th>RUT</th>
          <th>Fecha de nacimiento</th>
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
            <td><?php echo $serie['rut']; ?></td>
            <td><?php echo date("d-m-Y", strtotime($serie['fechaNacimiento'])); ?></td>
            <?php $edad = date_diff(date_create($serie['fechaNacimiento']), date_create(date("Y-m-d"))); ?>
            <td><?php echo $edad->format('%y'); ?></td>
            <td><?php echo "(".$serie['informes'].") ".(($serie['informes']>1) ? "informes" : "informe"); ?></td>
            <td>
              <?php
                echo (($serie['informes']==0) ? "<button class='btn btn-flat' id='editar' data-id='".$serie['id']."'>Agregar</button>" : "<button class='btn btn-flat' id='editar' data-id='".$serie['id']."'>Editar</button>");
              ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
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
        if (txtValor.toUpperCase().indexOf(filtro) > -1) {
          tr[i].style.display = '';
        } else {
          tr[i].style.display = 'none';
        }
      }
    }
  };
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
