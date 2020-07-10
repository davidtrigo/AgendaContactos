<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agenda</title>
</head>
<body>
<link rel="stylesheet" type="text/css" href="../Agenda/css/estilos.css">


<?php

require_once "conexionBD.php";

/**
 * En caso que tenga valor el id , se obtiene el nombre del contacto para mostrarlo posteriormente
 */
if (isset($_GET['id'])) {

    $sql = "SELECT NOMBRE
               FROM CONTACTOS 
               WHERE  ID =:id; ";
    $statement = $conex->prepare($sql);
    $id = $_GET['id'];

    $statement->bindParam('id', $id);
    $statement->execute();

    $contacto = $statement->fetchAll(PDO::FETCH_ASSOC);


    foreach ($contacto as $valor) {
        $dato = $valor['NOMBRE'];
    }

}
?>

<h1>Relacionar contactos de <?php  echo "$dato "; ?></h1>

<div class="container">
    <nav>
        <a href="nuevoContacto.php"><img src="img/add-user.png" height="50" width="50" title="nuevo contactos" alt=nuevo
                                         contacto"/></a>
        <a href="index.php"><img src="./img/search-user.png" height="50" width="50" title="buscar contactos" alt=buscar
                                 contactos"></a>

    </nav>
</div>
<?php

/*
 * Si se ha presionado al boton 'Guardar cambios' se procederá a guardar los datos de la nueva relacion
 */

if (isset($_POST['guardarcambios'])) {

    try {
        $id = $_GET['id'];
        if (!empty($_POST['contacto_list'])) {

            foreach ($_POST['contacto_list'] as $selected) {
                $sql = "INSERT INTO RELACION (ID_CONOCEDOR,ID_CONOCIDO) VALUES (:id,:selected);";
                $statement = $conex->prepare($sql);
                $statement->execute([":id" => $id, ":selected" => $selected]);
            }
        }
        echo "<p class='ok'>relacion realizada</p>";
    } catch (PDOException $exception) {

        echo "<p class='error'>No se ha podido actualizar</p>";
        echo "<p class='error'>{$exception->getMessage()}</p>";

    }
}

/**
 * se muestra en una tabla los datos a relacionar
 */

if (isset($_GET['id'])) {
    try {


        $sql = "SELECT  ID, NOMBRE, MAIL
               FROM CONTACTOS 
               WHERE  ID<>:id and id NOT IN (
                                      SELECT ID_CONOCIDO FROM RELACION 
                                      WHERE ID_CONOCEDOR =:id); ";

        $statement = $conex->prepare($sql);
        $id = $_GET['id'];

        $statement->bindParam('id', $id);
        $statement->execute();

        $contactos = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($contactos != null) {
            mostrarTabla($contactos);

        } else {
            echo "<p class='info'>No hay datos a relacionar</p>";
         //   mostrarTabla($contactos);

        }

    } catch (PDOException $exception) {
        echo "<p class='error'>{$exception->getMessage()}</p>";
    }
}


/** Metodo que genera una tabla de contactos a partir de un resultado
 * @param $contactos
 */
function mostrarTabla($contactos)
{
    ?>
    <table class="tg" width="1530">
        <form action="" method='post'>
            <tr>
                <th class="tg-yw4l">Contactos</th>

            </tr>
            <?php
            foreach ($contactos as $c) {
                echo "<tr> ";
                echo " <td><input type='checkbox'  name='contacto_list[]' value=' {$c['ID']}'> {$c['NOMBRE']} , {$c['MAIL']}</td>";
                echo "</tr>";
            }
            echo "<td><button type='submit' name='guardarcambios' id= 'guardarcambios' />Guardar cambios </td>";
            ?>
        </form>
    </table>
    <?php
}

?>
</body>
</html>

