<?php

set_time_limit(0);
ini_set("memory_limit", -1);

$conexion = mysqli_connect("192.168.1.161", "root", "70143086") or die("No se ha podido conectar al servidor de Base de datos");
//$conexion = mysqli_connect("190.85.62.78", "root", "70143086") or die("No se ha podido conectar al servidor de Base de datos");
$bdOrigen = mysqli_select_db($conexion, "bdkl") or die("Upps! Pues va a ser que no se ha podido conectar a la base de datos");

$mysqli = new mysqli("192.168.1.200", "administrador", "Nor4m628", "bdlogicuartas");
//$mysqli = new mysqli("localhost", "root", "70143086", "bdlogicuartas");
if ($mysqli->connect_errno) {
    echo "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

// establecer y realizar consulta. guardamos en variable.
$consulta = "
SELECT
novedades.ID, novedades.Guia, novedades.IdNovedad, novedades.Comentarios, novedades.UsuIng, novedades.FHIngreso,
novedades.FhNovedad, novedades.Solucion, novedades.UsuSol, novedades.FhSolucion, novedades.Solucionada, novedades.IdCentroOperaciones
FROM novedades WHERE ID > 0 ORDER BY ID ASC limit 9000000";

$resultado = mysqli_query($conexion, $consulta) or die("Algo ha ido mal en la consulta a la base de datos de consulta");
echo "Numero filas: " . $resultado->num_rows . "<br/>";
$contador = 0;
$contadorGeneral = 0;
$strInsertarEstructura = "INSERT INTO tte_novedad (codigo_guia_fk, codigo_novedad_tipo_fk, descripcion,
solucion, fecha, fecha_reporte, fecha_atencion, fecha_solucion, fecha_registro, estado_solucion, estado_atendido, estado_reporte) 
                        VALUES ";
$sqlInsertar .= $strInsertarEstructura;
while ($columna = mysqli_fetch_array($resultado)) {
    $sqlInsertar .= "(" . $columna['Guia'] . ", '". $columna['IdNovedad'] . "', '" . $columna['Comentarios'] . "', '" . $columna['Solucion'] . "', 
    '" . $columna['FhNovedad'] . "', '" . $columna['FhNovedad'] . "', '" . $columna['FhNovedad'] . "', '" . $columna['FhSolucion'] . "', '" . $columna['FhNovedad'] . "',
    " . $columna['Solucionada'] . ", 1 , 1)";
    $contador++;
    $contadorGeneral++;
    if($contador == 5000) {
        if (!$mysqli->multi_query($sqlInsertar)) {
            echo "Fallo al insertar: (" . $mysqli->errno . ") " . $mysqli->error . " " . $sqlInsertar;
        } else {
            echo "Exitoso " . "<br/>";
            $mysqli->close();
            $mysqli = new mysqli("192.168.1.200", "administrador", "Nor4m628", "bdlogicuartas");
            //$mysqli = new mysqli("localhost", "root", "70143086", "bdlogicuartas");
            $sqlInsertar = $strInsertarEstructura;
            $contador = 0;
        }
    } else {
        if($contadorGeneral != $resultado->num_rows) {
            $sqlInsertar .= ",";
        }
    }
}
if ($contador != 0) {
    if (!$mysqli->multi_query($sqlInsertar)) {
        echo "Fallo al insertar: (" . $mysqli->errno . ") " . $mysqli->error . " " . $sqlInsertar;
    } else {
        echo "Exitoso " . "<br/>";
        $mysqli->close();
    }
}




