<?php

function conectar() {
    $servidor = new mysqli("192.168.1.104", "root", "70143086", "bdklprueba");
    //$servidor = new mysqli("181.49.169.98", "root", "70143086", "bdklprueba");
    if ($servidor->connect_error) {
        die("Connection failed: " . $servidor->connect_error);
    }
    return $servidor;
}

function validarGuia($servidor, $guia) {
    $resultado = false;
    $sql = "SELECT Guia FROM guias WHERE Guia = " . $guia;
    if ($ar = $servidor->query($sql)) {
        if ($ar->num_rows == 0) {
            $resultado = true;
        }
    }
    return $resultado;
}

function consecutivo($servidor, $consecutivo) {
    $resultado = "";
    $sql = "SELECT $consecutivo FROM consecutivos";
    if ($ar = $servidor->query($sql)) {
        $arConsecutivo = $ar->fetch_assoc();
        $resultado = $arConsecutivo[$consecutivo];
    }
    return $resultado;
}

function incrementarConsecutivo($servidor, $consecutivo) {
    $resultado = false;
    $sql = "UPDATE consecutivos set $consecutivo = $consecutivo+1";
    if ($ar = $servidor->query($sql)) {
        $resultado = true;
    }
    return $resultado;
}
