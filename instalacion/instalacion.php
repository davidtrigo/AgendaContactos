<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Instalar BD Contactos</title>

    <link rel="stylesheet" type="text/css" href="../Agenda/css/estilos.css">
</head>
<body>
<h1>Instalación de la BD Contactos</h1>

<?php
include '../config.php';

/**
 * Conexion al servidor
 */


$host = $datas["host"];
$username = $datas["user"];
$password = $datas["password"];

try {

    $conex = new PDO("mysql:host=$host", "root", "$password");

    echo "<p class='ok'>Servidor conectado</p>";
} catch (Exception $exception) {

    echo "<p class='error'>({$exception->getMessage()})</p>";
}


/**
 * Creación  de la base de datos
 */


$sql = " CREATE DATABASE IF NOT EXISTS `CONTACTOS`;";


$ok = $conex->exec($sql);

if ($ok) {
    echo "<p class='ok'>Base de datos creada</p>";
} else {
    echo "<p class='error'>No se ha podido crear la BD, revisa el administrador de phpmyadmin por si ya estuviera creada</p>";
    echo "<p class='error'>{$conex->errorInfo()[2]}</p>";
}

$sql = "USE `CONTACTOS`;";

$ok = $conex->exec($sql);

if ($ok !== false) {
    echo "<p class='ok'>Base de datos conectada</p>";
} else {
    echo "<p class='error'>No se ha podido conectar la BD</p>";
    echo "<p class='error'>{$conex->errorInfo()[2]}</p>";
}


/**
 * Creacion de las tablas
 */

$sql = "CREATE TABLE CONTACTOS(
      ID INT PRIMARY KEY AUTO_INCREMENT,
      NOMBRE VARCHAR(30) NOT NULL,
      MAIL VARCHAR(40) NOT NULL UNIQUE
);";

$ok = $conex->exec($sql);


if ($ok !== false) {
    echo "<p class='ok'>Tabla <i>Contactos</i> creada</p>";

} else {
    echo "<p class='error'>No se ha podido crear la tabla <i>Contactos</i></p>";
    echo "<p class='error'>{$conex->errorInfo()[2]}</p>";
}

$sql = "CREATE TABLE RELACION(
        ID_CONOCEDOR INT ,
        ID_CONOCIDO INT ,
        PRIMARY KEY(ID_CONOCEDOR,ID_CONOCIDO),
        FOREIGN KEY(ID_CONOCEDOR) REFERENCES CONTACTOS(ID) ON DELETE CASCADE
                                                            ON UPDATE CASCADE,
        FOREIGN KEY(ID_CONOCIDO) REFERENCES CONTACTOS(ID) ON DELETE CASCADE
                                                            ON UPDATE CASCADE
);";

$ok = $conex->exec($sql);


if ($ok !== false) {
    echo "<p class='ok'>Tabla <i>Relacion</i> creada</p>";

} else {
    echo "<p class='error'>No se ha podido crear la tabla <i>Relacion</i></p>";
    echo "<p class='error'>{$conex->errorInfo()[2]}</p>";
}


/**
 * Inserción datos de ejemplo
 */

$sql ="INSERT INTO CONTACTOS (NOMBRE,MAIL) VALUES ('LUKE','LUKESKYWALKER@JEDI.COM');
       INSERT INTO CONTACTOS (NOMBRE,MAIL) VALUES ('LEIA ORGANA','PRINCESITA@RESISTENCE.COM');
       INSERT INTO CONTACTOS (NOMBRE,MAIL) VALUES ('DARTH VADER','MALOTE@DEATHSTAR.COM');
       INSERT INTO CONTACTOS (NOMBRE,MAIL) VALUES ('YODA','COM@THEFORCE.YODA');
       INSERT INTO CONTACTOS (NOMBRE,MAIL) VALUES ('HAN','TOYSOLO@ISHOTFIRST.COM');
       INSERT INTO CONTACTOS (NOMBRE,MAIL) VALUES ('CHEWBACCA','CHEW@FALCON.COM');
       INSERT INTO CONTACTOS (NOMBRE,MAIL) VALUES ('C3P0','C3P0@CYBOT.COM');
       INSERT INTO CONTACTOS (NOMBRE,MAIL) VALUES ('R2D2','R2D2@.AUTOMATION.COM');
       INSERT INTO CONTACTOS (NOMBRE,MAIL) VALUES ('OBI WAN KENOBI','TITOBEN@HERMIT.COM');
       INSERT INTO CONTACTOS (NOMBRE,MAIL) VALUES ('FINN','FN2187@STORMTROOPER.COM');";

$ok = $conex->exec($sql);

if (!$ok == false) {
    echo "<p class='ok'>Datos  insertados correctamente</p>";

} else {
    echo "<p class='error'>No se ha podido insertar los datos de la tabla <i>Contactos</i></p>";
    echo "<p class='error'>{$conex->errorInfo()[2]}</p>";
}


/**
 * Inserción datos de ejemplo
 */

$sql = "
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (1,2);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (1,2);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (1,3);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (1,4);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (1,5);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (1,6);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (1,7);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (1,8);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (1,9);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (2,1);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (2,3);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (2,4);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (3,1);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (3,2);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (3,4);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (3,7);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (3,9);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (4,1);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (4,6);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (4,8);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (5,1);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (6,7);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (6,8);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (7,8);
        INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (8,7);";

$ok = $conex->exec($sql);

if (!$ok == false) {
    echo "<p class='ok'>Datos  insertados correctamente</p>";

} else {
    echo "<p class='error'>No se ha podido insertar los datos de la tabla <i>Relacion</i></p>";
    echo "<p class='error'>{$conex->errorInfo()[2]}</p>";
}
?>

</body>