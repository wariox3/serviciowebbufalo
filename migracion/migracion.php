<?php

$conexion = mysqli_connect("190.85.62.78", "root", "70143086") or die("No se ha podido conectar al servidor de Base de datos");
$bdOrigen = mysqli_select_db($conexion, "bdkl") or die("Upps! Pues va a ser que no se ha podido conectar a la base de datos");

$mysqli = new mysqli("localhost", "root", "70143086", "bdcromo");
if ($mysqli->connect_errno) {
    echo "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

// establecer y realizar consulta. guardamos en variable.
$consulta = "SELECT Guia, Unidades, KilosReales, KilosFacturados, KilosVolumen, VrDeclarado, VrFlete, VrManejo, Recaudo,
              Abonos 
            FROM guias ORDER BY Guia DESC LIMIT 3";
$resultado = mysqli_query($conexion, $consulta) or die("Algo ha ido mal en la consulta a la base de datos");
$arrGuias = array();    
while ($columna = mysqli_fetch_array( $resultado )) {
    //echo $columna['guia'] . "<br />";
    $strInsertarGuia = "INSERT INTO tte_guia (numero, unidades, peso_real, peso_facturado, peso_volumen, vr_declara, vr_flete, vr_manejo, vr_recaudo, vr_abono,
                                    codigo_operacion_ingreso_fk, codigo_operacion_cargo_fk) 
                        VALUES(". $columna['Guia'] . ",". $columna['Unidades'] . ",". $columna['KilosReales'] . ",". $columna['KilosFacturados'] . ",". $columna['KilosVolumen'] . "
                        ,". $columna['VrDeclarado'] . ",". $columna['VrFlete'] . ",". $columna['VrManejo'] . ",". $columna['Recaudo'] . ",". $columna['Abonos'] . ", 'MED', 'MED')";
    if (!$mysqli->query($strInsertarGuia)) {
        echo "Fallo al insertar: (" . $mysqli->errno . ") " . $mysqli->error . " " . $strInsertarGuia;
        break;
    }
    //$bdDestino->close();
}
