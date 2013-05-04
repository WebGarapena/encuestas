<?php
require_once ("funciones.php");
require_once ("funciones/data_valid_fns.php");

session_start();

//RECOGER EL ID DEL USUARIO QUE INICIA LA ENCUESTA
$tipo = $_POST['radio'];
$agregar = $_POST['agregar'];
$user = $_SESSION['valid_user'];
$titulo= $_POST['titulo'];
$descripcion=$_POST['descripcion'];
//RECOGER EL ARRAY DE RESPUESTAS E INSERTARLO CON UN BUCLE y en cada iteracción meterle RESPUESTA1=x , RESPUESTA2=X, ETC 
$respuestas = $_POST['respuestas'];

if(!$tipo || !$agregar || $titulo || !$descripcion){
	do_html_header("Problema:");
	echo "No se han recibido algunos datos";
}elseif(!$respuestas || !filled_out($respuestas)){
	do_html_header("Problema:");
	echo "No se han recibido algunos datos";
}else{
	$reg_result = insertar_encuesta($user, $tipo, $agregar, $titulo, $descripcion, $respuestas);
	if ($reg_result == "true"){
		do_html_header("Zorionak:");
		display_user_encuestas();
		do_html_footer();
	}else{
		echo "No se ha podido insertar la encuesta";
	}
}
?>
