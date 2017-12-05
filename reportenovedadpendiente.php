<?php
set_time_limit(0);
ini_set("memory_limit", -1);
$empresa = $_REQUEST['empresa'];

$usuario = "root";
$password = "70143086";
$servidor = "181.49.169.98";
$servidor = "192.168.1.104";
$basededatos = "bdkl";

// creación de la conexión a la base de datos con mysql_connect()
$conexion = mysqli_connect($servidor, $usuario, $password) or die("No se ha podido conectar al servidor de Base de datos");

// Selección del a base de datos a utilizar
$db = mysqli_select_db($conexion, $basededatos) or die("Upps! Pues va a ser que no se ha podido conectar a la base de datos");

// establecer y realizar consulta. guardamos en variable.
$consulta = "SELECT
                novedades.ID,
                novedades.Guia,
                novedades.IdNovedad,
                novedades.Comentarios,
                novedades.UsuIng,
                novedades.FHIngreso,
                DATE_FORMAT(novedades.FhNovedad, '%Y-%m-%d') as FhNovedad,
                novedades.Solucion,
                novedades.UsuSol,
                novedades.FhSolucion,
                guias.Cuenta,
                causalesnovedad.NmNovedad,
                novedades.Solucionada,
                novedades.IdCentroOperaciones
            FROM
                novedades
            LEFT JOIN guias ON novedades.Guia = guias.Guia
            LEFT JOIN causalesnovedad ON novedades.IdNovedad = causalesnovedad.IdNovedad 
            WHERE Cuenta = $empresa AND novedades.Solucionada = 0";


$resultado = mysqli_query($conexion, $consulta) or die("Algo ha ido mal en la consulta a la base de datos:" . $consulta);

$arrNovedades = array();    
while ($columna = mysqli_fetch_array( $resultado )) {
    $arrNovedades[] = array(
        'ID' => $columna['ID'],
        'FhNovedad' => $columna['FhNovedad'],
        'NmNovedad' => $columna['NmNovedad'],
        'Guia' => $columna['Guia'],
        );
}
$datos = array('estado' => 1, 'novedades' => $arrNovedades);

$json = json_encode($datos);
if ($json)
    echo $json;
else
    echo json_last_error_msg();
//echo json_encode($datos);

mysqli_close( $conexion );
