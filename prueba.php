<?php

$guia = $_REQUEST['guia'];

$datos = array('estado' => 1, 'guias' => 4);

echo json_encode($datos);

mysqli_close( $conexion );
