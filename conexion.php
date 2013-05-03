<?php
// hacer login en la base de datos
if (!$db_conn = @mysqli_connect("localhost", "root", "")){
    echo "No se puede conectar a la base de datos<br>";
    exit;
}// fin if
@mysqli_select_db($db_conn,"p_encuesta");
?>
