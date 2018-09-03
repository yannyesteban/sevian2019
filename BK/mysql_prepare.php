<?php
$mysqli = new mysqli("localhost", "root", "123456", "sevian");

/* comprobar la conexión */
if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

$ciudad = "action_4";

/* crear una sentencia preparada */
$sentencia =  $mysqli->stmt_init();
if ($sentencia->prepare("SELECT title FROM cfg_actions WHERE action=?")) {

    /* vincular los parámetros para los marcadores */
    $sentencia->bind_param("s", $ciudad);

    /* ejecutar la consulta */
    //
	$sentencia->execute();

    /* vincular las variables de resultados */
    $sentencia->bind_result($title);

    /* obtener el valor */
    $sentencia->fetch();

    printf("%s está en el distrito de %s\n", $ciudad, $title);

    /* cerrar la sentencia */
    $sentencia->close();
}

/* cerrar la conexión */
$mysqli->close();
?> 