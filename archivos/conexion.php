<?php
  $bd_host = 'localhost';
  $bd_usuario = 'root';
  $bd_clave = '';
  $bd_nombre = 'clubes';

  $conexion = mysqli_connect($bd_host, $bd_usuario, $bd_clave, $bd_nombre);
  if ($conexion->connect_errno) {
    echo "Falló al conectar. ".$conexion->connect_errno;
    exit();
  }
?>
