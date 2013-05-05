<?php
function do_html_header($title){
?>
<!DOCTYPE HTML>
<html lang="es">
<head>
    <title><?php echo $title; ?></title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <style>
    </style>
	<link rel="stylesheet" href="estilo.css" type="text/css"/>
	<script type="text/javascript" language="javascript" src="jquery.2.0.0.js"></script>
	<script type="text/javascript" language="javascript" src="jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" language="javascript" src="datepicker-es.js"></script>
	<!--<script type="text/javascript" language="javascript" src="jquery.ptTimeSelect.js"></script>->
</head>
		<body>
			<!--<img src="../style/marcador.gif" alt="PHPbookmark logo" border=0 align=left valign=bottom height= 50 width= 150>-->
<?php
	if($title){
		do_html_heading($title);
	}
}//FIN FUNCTION do_html_header

function do_html_footer(){
?>
	</body>
	</html>
<?php
}
function do_html_heading($heading){
?>
	<h3><?php echo $heading; ?></h3>
	<hr>
<?php
}// FIN FUNCTION do_html_heading
function display_login_form(){
?>
	<form method="post" name="login" action="<?php $_SELF ?>">
		Usuario:<input type="text" name="nick" required><br/>
		Contraseña <input type="password" name="passwd" required><br/>
		<input type="submit" value="Login">
	</form>
<?php
}
function display_encuesta_form(){
?>
	<script type="text/javascript" language="javascript">
	</script>
	<form method='post' action='recoge_poll.php'>
		<div id="condiciones">
			<label><b>Tipo de encuesta:</b> </label><br/>
			<input type='radio' name='tipo'  value='0' checked /><span>Simple</span>
			<input type='radio' name='tipo'  value='1'/><span>Multiple</span>
			<input type='radio' name='tipo'  value='2'/><span>Cantidad</span><br/>
			<label><b>El usuario puede añadir respuestas?</b> </label><br/>
			<input type='radio' name='agregar'  value='1'/><span>Si</span>
			<input type='radio' name='agregar'  value='0' checked /><span>No</span><br/>
			<label><b>Fecha y hora de fin de la encuesta?</b> </label><br/>
			<input type="text" id="date" size="10" placeholder="Fecha"/>
			a las <input type="text"  id="hora" size="10" placeholder="Hora" /><br/>
			<input type="button" id="continuar" value="Continuar"/>
		</div>
	</form>
	<script type="text/javascript" language="javascript">
	$(document).ready(function(){
			$( "#date" ).datepicker({ dateFormat: "dd-mm-yy" });
			var dateFormat = $( "#date" ).datepicker( "option", "dateFormat" );
			$( "#date" ).datepicker( "option", "dateFormat", "dd-mm-yy" ); 
			
			var titulo = $("<label>Titulo:</label><br/><input type='text' name='titulo' maxlength='100' placeholder='Introduce la pregunta' required/><br/>");
			var descripcion = $("<label>Descripción:</label><br/><textarea name='descripcion' cols='30' placeholder='Puedes agregar información a la encuesta' name='descripcion'></textarea><br/>");
			var respuesta1 = $("<label>Respuesta 1:</label><br/><input type='text' name='respuestas[]' maxlength='100' placeholder='Respuesta 1' required/><br/>");
			var respuesta2 = $("<label>Respuesta 2:</label><br/><input type='text' name='respuestas[]' maxlength='100' placeholder='Respuesta 2' required/>"+
										"<input type='button' class='boton' onclick='agrega_campos()' value='+' />" );
										
			$("#continuar").click(function(){
				$("form").append(titulo, descripcion, respuesta1, respuesta2, enviar);
				$("#condiciones").hide();
			});
			
			var contador = 3;
					function agrega_campos(){
						$("input:last").before("<label class='"+contador+"'>Respuesta "+contador+":</label><br class='"+contador+"'/><input type='text' name='respuestas[]' maxlength='100' class='"+contador+"' placeholder='Respuesta "+contador+"'/>"+
													 "<input type='button'  class='"+contador+"' onclick='elimina_me("+contador+")' value='X' ><br class='"+contador+"'>");
						contador++;
					}
			function elimina_me(campo){
				$("."+campo).remove();
				contador--;
			}
			var enviar = $("<br/><input type='submit'  value='Crear encuesta'/>");
	});
	</script>
<?php
}
//fin function

function display_user_encuestas(){
	echo "Encuestas de ".$user;
	
}

function display_registration_form(){
?>
	<form method="POST" action="register_new.php">
		<table bgcolor="#cccccc">
			<tr>
				<td>Dirección email:</td>
				<td><input type="text" name="email" size="30" maxlength="100"></td>
			</tr>
			<tr>
				<td>Nombre de usuario <br>(max. 16 caracteres):</td>
				<td valign="top"><input type="text" name="username" size="16" maxlength="16"></td>
			</tr>
			<tr>
				<td>Contraseña <br>(entre 6 y 16 caracteres):</td>
				<td valign="top"><input type="password" name="password" size="16" maxlength="16"></td>
			</tr>
			<tr>
				<td>Confirmar contraseña:</td>
				<td><input type="password" name="password2" size="16" maxlength="16"></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" value="Registrarse"></td>
			</tr>
		</table>
	</form>
	
<?php
}
function display_password_form(){
	//display html change password form
?>
	<br />
	<form action="change_passwd.php" method="POST">
		<table width="250" cellspacing="0" cellpadding="2" bgcolor="#cccccc">
			<tr>
				<td>Vieja contraseña:</td>
				<td><input type="password" name="old_passwd" size="16" maxlength="16"></td>
			</tr>
			<tr>
				<td>Nueva contraseña:</td>
				<td><input type="password" name="new_passwd" size="16" maxlength="16"></td>
			</tr>
			<tr>
				<td>Repite Nueva contraseña:</td>
				<td><input type="password" name="new_passwd2" size="16" maxlength="16"></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" value="Cambiar contraseña"></td>
			</tr>
			
		</table>
	</form>
	<br />
<?php
}
function display_forgot_form(){
// muestra formulario HTML para resetear y enviar contraseña por email
?>
  <br>
  <form action="forgot_passwd.php" method="post">
  <table width="250" cellpadding="2" cellspacing="0" bgcolor="#cccccc">
  <tr><td>Enter yout username</td>
      <td><input type="text" name="username" size="16" maxlength="16"></td>
  </tr>
  <tr><td colspan="2" align="center"><input type="submit" value="Change password">
  </td></tr>
  </table>
  <br>
<?php
}//fin function
?>
