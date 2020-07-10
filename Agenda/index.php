<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda</title>


    <link rel="stylesheet" type="text/css" href="../Agenda/css/estilos.css">

    <style>


    </style>
</head>
<body>

<h1>BÚSQUEDA DE CONTACTOS</h1>

<div class="container">
    <nav>
        <a href="nuevoContacto.php"><img src="img/add-user.png" height="50" width="50" title="nuevo contactos" alt="nuevo
                                     contacto"/></a>
        <a href="index.php"><img src="./img/search-user.png" height="50" width="50" title="buscar contactos" alt="buscar
                             contactos"></a>
    </nav>
    <form method='post'>
        <ul class="flex-outer">
            <li>
                <label for="buscar">Buscar</label>
                <input type="text" id="buscar" name="buscar" placeholder="Introduce una busqueda aquí">
            </li>
            <li>
                <button type="submit" id="btnBuscar">Buscar</button>
            </li>
        </ul>
    </form>
</div>

<?php

require_once "conexionBD.php";

/**
 * En caso de que venga datos desde el formulario
 */
if (!isset($_POST['buscar']) && isset($_SESSION['busqueda'])) {
    $sql = "SELECT ID,NOMBRE,MAIL
                FROM CONTACTOS
                WHERE NOMBRE LIKE :busqueda  OR MAIL LIKE :busqueda;";

    $statement = $conex->prepare($sql);

    $busqueda = $_SESSION['busqueda'];
    $statement->bindParam('busqueda', $busqueda);
    $statement->execute();
    $contactos = $statement->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST['buscar'])) {
    $_SESSION['busqueda'] = $_POST['buscar'];

    try {
        $sql = "SELECT ID,NOMBRE,MAIL
                FROM CONTACTOS
                WHERE NOMBRE LIKE :busqueda  OR MAIL LIKE :busqueda;";

        $statement = $conex->prepare($sql);

        $busqueda = "%{$_POST['buscar']}%";

        $statement->bindParam('busqueda', $busqueda);
        $statement->execute();

        $contactos = $statement->fetchAll(PDO::FETCH_ASSOC);


        $_SESSION['busqueda'] = $busqueda;
    } catch (PDOException $exception) {

        echo "<p class='error'>{$exception->getMessage()}</p>";
    }
} else {
    /**Sino viene datos desde formulario se mostrará por defecto todos los registros*/

    if (!isset($_SESSION['busqueda'])) {
        try {

            $sql = "SELECT ID,NOMBRE,MAIL
                FROM CONTACTOS;";

            $statement = $conex->prepare($sql);
            $statement->execute();
            $contactos = $statement->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $exception) {
            echo "<p class='error'>{$exception->getMessage()}</p>";
        }
    }
}

/**Se ha presionado el boton eliminar y se procede a eliminar el registro elegido*/

if (isset($_POST['eliminar'])) {

    try {
        $id = $_POST['eliminar'];

        $sql = "DELETE FROM CONTACTOS WHERE ID =$id";
        $statement = $conex->prepare($sql);
        $statement->execute();
        if(isset( $_SESSION['busqueda'])){
            $busqueda = $_SESSION['busqueda'];
        }

        echo "<p class='ok'><img src='img/wait-icon.gif' height='50'/> Eliminando contacto correctamente</p>";

        ?>
        <script>
            setTimeout(function () {
                window.location.href = 'index.php'; // the redirect goes here

            }, 1000); // half second
        </script>
        <?php
    } catch (PDOException $exception) {

        echo "<p class='error'>No se ha podido eliminar el contacto</p>";
        echo "<p class='error'>{$exception->getMessage()}</p>";
    }
}

/**
 * Comprueba si existe $contactos  sino existe mostrará un mensaje que no hay datos
 */

if ($contactos != null) {
    mostrarTabla($contactos);

} else {
    echo "<p class='error'>No hay datos a mostrar</p>";
}

/** Método que devuelve un array de conocidos a partir del id del conocedor
 * @param $idconocedor
 * @return $datos
 */
function contactoDe($idconocedor)
{
    $conex = new PDO("mysql:host=localhost;dbname=contactos", "root", "");
    $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $sql = "SELECT nombre FROM contactos, relacion where relacion.ID_CONOCEDOR=:idconocedor and contactos.ID=relacion.ID_CONOCIDO";
    $statement = $conex->prepare($sql);

    $statement->bindParam('idconocedor', $idconocedor);
    $statement->execute();
    $contactos = $statement->fetchAll(PDO::FETCH_ASSOC);
    $datos = [];

    foreach ($contactos as $valor) {
        $datos[] = $valor['nombre'];
    }

    return $datos;
}

/** Metodo que genera una tabla de contactos a partir de un resultado
 * @param $contactos
 */
function mostrarTabla($contactos)
{

    ?>
    <table class="tg" width="1530">

        <form action="" method='post' id="form">

            <tr>
                <th class="tg-yw4l">Nombre</th>
                <th class="tg-yw4l">E-Mail</th>
                <th class="tg-yw4l">Conocidos</th>
                <th colspan="2" class="tg-yw4l"></th>

            </tr>
            <?php
            foreach ($contactos as $c) {
                $lista = contactoDe($c['ID']);
                if ($lista == null) $lista = [];
                echo "<tr> ";
                echo "<td> {$c['NOMBRE']}</td>";
                echo "<td>{$c['MAIL']}</td>";
                echo "<td>";
                echo implode(" , ", $lista);
                echo "</td>";
                echo "<td ><button type='submit' name='eliminar' id= 'eliminar' value ='{$c['ID']}' /><img src='./img/delete-user.png' height='50'/> </td>";
                echo "<td><a href='relacionar.php?id={$c['ID']}'>   <img src='./img/relation-user.png' height='50' border='1'/></a>   </td>";
                echo "</tr>";
            }
            ?>
        </form>
    </table>
<?php } ?>
</body>
</html>