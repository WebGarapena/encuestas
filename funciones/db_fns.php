<?php
/*
// hacer login en la base de datos
if (!$db_conn = @mysqli_connect("localhost", "root", "")){
    echo "No se puede conectar a la base de datos<br>";
    exit;
}// fin if

@mysqli_select_db($db_conn,"p_encuesta");
*/
function db_connect(){
$host= "localhost";
$user = "root";
$pass="";
$db= "prototipos_encuesta";
//pendiente de introducir los datos correctos
$result = mysqli_connect($host, $user, $pass, $db);
//printf ($result);
/* cambia de  bd */
//mysqli_select_db($result, "prototipos_encuestas");
/* comprueba la conexión */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
if (!$result){
	return false;
}
/* if (!mysqli_select_db($result, "prototipos_encuestas")){
	return false;
}*/
return $result;
mysqli_close($result);
}//fin function

?>
