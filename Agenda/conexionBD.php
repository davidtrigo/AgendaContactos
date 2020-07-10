<?php
/**
 * Conexion al servidor
 */

$host = "localhost";
$dbname = "contactos";
$username = "root";
$password = "";

try {

    $conex = new PDO("mysql:host=$host;dbname=$dbname", "root", "$password");
    $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $exception) {

    echo "<p class='error'>({$exception->getMessage()})</p>";
}
