<?php
if(!isset($_POST)){
	echo "No se ha podido procesar la encuesta";
}else{
	//RECOGER EL ID DEL USUARIO QUE INICIA LA ENCUESTA
	$titulo= $_POST['titulo'];
	$descripcion=$_POST['descripcion'];
	//RECOGER EL ARRAY DE RESPUESTAS E INSERTARLO CON UN BUCLE y en cada iteracción meterle RESPUESTA1=x , RESPUESTA2=X, ETC 

	//INSERTAR LA ENCUESTA EN LA BASE DE DATOS
}
?>
