<?php

function conectar() {
    $servidor = new mysqli("192.168.1.104", "root", "70143086", "bdkl");
    //$servidor = new mysqli("181.49.169.98", "root", "70143086", "bdkl");
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
