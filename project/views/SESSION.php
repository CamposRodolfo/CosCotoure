<!-- Ideas para sesiones -->

<?php
session_start();

if (!isset($_SESSION['Nombre'])) {
    $_SESSION['Nombre'] = '';
}

if (!isset($_SESSION['correo'])) {
    $_SESSION['correo'] = '';
}

if (!isset($_SESSION['contrasena'])) {
    $_SESSION['contrasena'] = '';
}

if (!isset($_SESSION['imagen_perfil'])) {
    $_SESSION['imagen_perfil'] = '';
}

if (!isset($_SESSION['telefono'])) {
    $_SESSION['telefono'] = '';
}

if (!isset($_SESSION['direccion1'])) {
    $_SESSION['direccion1'] = '';
}

if (!isset($_SESSION['direccion2'])) {
    $_SESSION['direccion2'] = '';
}

if (!isset($_SESSION['ciudad'])) {
    $_SESSION['ciudad'] = '';
}

if (!isset($_SESSION['pais'])) {
    $_SESSION['pais'] = '';
}

if (!isset($_SESSION['codigo_postal'])) {
    $_SESSION['codigo_postal'] = '';
}

if (!isset($_SESSION['metodo_pago'])) {
    $_SESSION['metodo_pago'] = '';
}

if (!isset($_SESSION['tipo_direccion'])) {
    $_SESSION['tipo_direccion'] = '';
}

?>