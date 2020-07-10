
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda</title>

    <link rel="stylesheet" type="text/css" href="../Agenda/css/estilos.css">
</head>
<body>

<h1>Nuevo contacto</h1>

<div class="container">
<nav>
    <a href="nuevoContacto.php"><img src="img/add-user.png" height="50" width="50" title="nuevo contactos" alt="nuevo
                                     contacto"/></a>
    <a href="index.php"><img src="./img/search-user.png" height="50" width="50" title="buscar contactos" alt="buscar
                             contactos"></a>
</nav>


    <form action="" method="post">
        <ul class="flex-outer">

            <li>
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" required placeholder="Introduce un nombre"/>
            </li>
            <li>
                <label for="mail">e-mail</label>
                <input type="text" name="mail" required placeholder="Introduce un email"/>
            </li>
            <li>

                <button type="submit" id="btnAdd">Añadir</button>
            </li>
        </ul>
    </form>


<?php

require_once "conexionBD.php";

if (isset($_POST['nombre']) && isset($_POST['mail'])) {

    try {

        $query = "INSERT INTO CONTACTOS (NOMBRE, MAIL) VALUES (:nombre,:mail)";
        $statement = $conex->prepare($query);
        $nombre = $_POST['nombre'];
        $mail = $_POST['mail'];

        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':mail', $mail);
        $statement->execute();

        echo "<p class='ok'>Dato insertado correctamente</p>";

    } catch (Exception $exception) {
        //echo "<p class='error'>{$exception->getMessage()}</p>";
        echo "<p class='error'>ERROR: Ha ocurrido un error al insertar un nuevo contacto</p>";

    }
}

?>
</div>