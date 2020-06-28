<?php
  require 'conexion.php';
  if (isset($_GET['funcion']) && !empty($_GET['funcion'])) {
    foreach ($conexion->query("SELECT DISTINCT(posicion) FROM posicion ORDER BY posicion") as $posicion) {
      $datos[] = array("posicion" => $posicion['posicion']);
    }
    header( 'Content-type: application/json' );
    echo json_encode($datos);
  }
  if (isset($_POST['funcion']) && !empty($_POST['funcion'])) {
    $funcion = $_POST['funcion'];
    switch ($funcion) {
      case 'nuevoJugador':
        session_start();
        $nombre = ucfirst($_POST['nombreJugador']);
        $apellidos = explode(' ', $_POST['apellidoJugador'], 2);
        $apellido1 = ucfirst($apellidos[0]);
        $apellido2 = (!empty($apellidos[1]) ? ucfirst($apellidos[1]) : "");
        $RUT = $_POST['rutJugador'];
        $fNacimiento = $_POST['nacimientoJugador'];
        $sexo = $_SESSION['sexo'];
        $serie = $_SESSION['serie'];
        $posicion = ucfirst(mb_strtolower($_POST['posicionJugador']));
        $sql = "INSERT INTO jugadores(nombre,apellido1,apellido2,rut,fechaNacimiento,sexo,serieActual)
                     VALUES ('$nombre','$apellido1','$apellido2',$RUT,'$fNacimiento','$sexo',$serie)";
        $resultado = $conexion->query($sql) or die ('Error en el query database');
        $ultimo = $conexion->insert_id;
        $sql = "INSERT INTO posicion(id,posicion) VALUES ($ultimo,'$posicion')";
        $resultado = $conexion->query($sql) or die ('Error en el query database');
        $sql = "UPDATE series SET jugadores=jugadores+1 WHERE id=$serie";
        $resultado = $conexion->query($sql) or die ('Error en el query database');
        if ($resultado) { echo "correcto"; }
        break;
      case 'eliminaJugador':
        session_start();
        $id = $_POST['id'];
        $serie = $_SESSION['serie'];
        $sql = "DELETE FROM posicion WHERE id=$id";
        $resultado = $conexion->query($sql) or die ('Error en el query database');
        $sql = "DELETE FROM jugadores WHERE id=$id";
        $resultado = $conexion->query($sql) or die ('Error en el query database');
        $sql = "UPDATE series SET jugadores=jugadores-1 WHERE id=$serie";
        $resultado = $conexion->query($sql) or die ('Error en el query database');
        if ($resultado) { echo "<td colspan='8' style='text-align: center;'>Se han eliminado los datos.</td>"; }
        break;
    }
  }
?>
