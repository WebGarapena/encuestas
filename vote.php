<?php
require_once ("funciones.php");
if($_POST){ // Si hay login...
	if (isset($_POST['nick']) && (isset($_POST['passwd']))){
		$valid_user=$_POST['nick'];
		$passwd=$_POST['passwd'];
		if (login($valid_user,$passwd)){
			session_start();
			// si el user id esta en la base de datos
			$_SESSION['valid_user'] = $valid_user;
			
			do_html_header("Crear encuesta");
			display_encuesta_form();
			do_html_footer();
		}else {
			echo "Disculpa, el login ha fallado";
			exit;
		}
	}else{
		echo "No has rellenado los dos campos.";
	}
}else{
	do_html_header("Accede a Encuestas");
	display_login_form();
}
?>
