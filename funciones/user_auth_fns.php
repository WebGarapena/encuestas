<?php
require_once("db_fns.php");
function insertar_encuesta($user, $tipo, $agregar, $titulo, $descripcion, $respuestas, $error){

	// registra una nueva persona en la base de datos
	// devuelve un mensaje true o error
	//conectar a la base de datos
	$conn = db_connect();
	if (!$conn){
		$error = "No se puede conectar a la base de datos - Intentalo mas tarde por favor.";
		return false;
	}
	// Comprobar si el usuario existe por segunda vez.
	//echo $user;
	$result = mysqli_query($conn, "select * from usuarios where nombre='$user'");
	$result=mysqli_fetch_row($result);//recoge los datos de la consulta en una fila
	if (!$result) {
		$error = "Tu usuario no existe, como te has logueado?";
		return false;
	}

	//paso a una variable el id del usuario logueado y comprobado
	$id_usuario = $result[0];

	if(!isset ($_SESSION['poll'] )){ 
		// MIRAR POR QUE NO FUNCIONA SIN EL ISSET INICIANDO LA VARIABLE FUERA, CON UN VALOR Y CAMBIANDOLO AL EJECUTAR LA PRIMERA VEZ 
		$_SESSION['poll'] = 1;
		// si el usuario es correcto, insertar la encuesta
		$result = mysqli_query($conn, "INSERT INTO encuestas values (NULL, '$tipo', '$agregar', '$titulo', '$descripcion', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."','$id_usuario')");

		// compruebo si hay resultado en la insercion
		if (!$result || !mysqli_insert_id($conn)) {
			$error = "No hemos podido insertar la encuesta en la base de datos - Intentalo mas tarde, por favor.";
			return false;
		}else{
			$id_encuesta= mysqli_insert_id($conn);//si la insercion ha tenido exito, su identificador sera el propio de la encuesta recien creada, asi que se guardara en la variable $id_encuesta
			
			//insertamos las opciones de respuestas[]
			for($i=0;$i<count($respuestas);$i++){
				$opcion= $respuestas[$i];
				$result = mysqli_query($conn, "insert into opciones_respuestas values (NULL,'$opcion','$id_encuesta')");

				if (!$result){
					$error = "Hubo un error al guardar";
					return false;
				}
			}//fin for
		}//fin else
	}
	return true;
}//fin function

function mostrar_user_encuestas($tipo, $agregar, $error){//mostrar las encuestas creadas por el usuario

	$user = $_SESSION['valid_user'];
	echo "<h5>Tus encuestas ".$user.":</h5>";

$conn= db_connect ();
	if (!$conn){
		$error= "No se puede conectar a la base de datos.";
		return false;
	}
	//seleccionamos el identioficador del usuario logueado.
	$result = mysqli_query ($conn, "SELECT id_usuario,nombre FROM usuarios WHERE  nombre='$user' " );
	$result= mysqli_fetch_row ($result);//Convierte los resultados de la consulta en un array.
	$id_usuario = $result[0];//Guardamos el identificador, que es el primer elemento del array generado antes.
	if(!$result){
		$error = "Ha habido un problema con tu usuario";
		return false;
	}
	//Guardamos en $result las Id's , Titulos y los campos de respuestas cogiendolos por la id del usuario extraido en consulta anterior.
	$result = mysqli_query ($conn, "SELECT encuestas.id_encuesta, titulo, nombre_opcion FROM encuestas, opciones_respuestas WHERE id_usuario = '$id_usuario' AND encuestas.id_encuesta = opciones_respuestas.id_encuesta");
	//Cada fila es un array individual con los datos de cada encuesta.
	
	//Metemos cada fila (array) del resultado de la consulta anterior en un indice numerico llamado $result2 con lo que generamos un array padre.
	while ($fila= mysqli_fetch_row($result)){
		$result2[]=$fila;
	}
	
	for ($i=0; $i<count($result2); $i++){// Recorremos todos los elementos de la consulta
		if($i==0 || $result2[$i][0] != $result2[$i-1][0]){
			// Si es el primer elemento que entra de la consulta recorrido con el for, o el elemento es el mismo del anterior....
			echo "<b>Titulo:</b>".$result2[$i][1]."<br>";
		}
		echo $result2[$i][2]."<br>";
	}
	
}//fi function

function mostrar_total_encuestas(){
	echo "<br/>Encuestas del sitio";
}

function insertar_voto(){
	
}//fin function

function register($username, $email, $password){
	// registra una nueva persona en la base de datos
	// devuelve un mensaje true o error
	//conectar a la base de datos
	$conn = db_connect();
	if (!$conn){
	return "No se puede conectar al servidor de la base de datos - Intentalo mas tarde por favor.";
	}	
	// comprobar si el nombre del usuario es unico
	$result = mysqli_query($conn, "select * from user where username='$username'");

	if (!$result) {
	return "No se ha podido ejecutar la consulta";
	}	
	if (mysqli_num_rows($result)>0){
	return "El nombre de usuario esta ocupado - Vuelve y elige otro";
	}	
	// si correcto, ponerlo en la base de datos
	$result = mysqli_query($conn, "insert into user values ('$username',password('$password'),'$email')");

	if (!$result) {
	return "No hemos podido registrarte en la base de datos - Intentalo mas tarde, por favor.";
	}
	return true;
}//fin function

function login($nick,$passwd){
//comprobar nombre de usuario y contraseña en la base de datos
//si correcto, devuelve verdadero
//si no, devuelve falso
	//conectar a la base de datos
	$conn = db_connect();
	if (!$conn){
		return 0;
	}
	
	// comprobar si el nombre del usuario es unico
	$resultado = mysqli_query($conn, "select * from usuarios where nombre='$nick' and contrasena='$passwd'");
	
	if (!$resultado){
		return 0;
	}
	if (mysqli_num_rows($resultado)>0){
		return 1;
	}else{
		return 0;
	}
}//fin function

function check_valid_user(){
// ver si alguien esta logged in y notificarselo si no es asi
  global $valid_user;
  if (isset($_SESSION['valid_user'])){
    echo "Bienvenido ".$_SESSION['valid_user'];
    echo "<br>";
  }else{
      // si no esta logged in
      do_html_heading("Problema:");
      echo "No estas identificado.<br>";
      do_html_URL("login.php","Login");
      do_html_footer();
      exit();
      }//fin else
}//fin function

function change_password($username, $old_pasword, $new_password){

// change password for username/old_password to new_password
// return true or false

  // if the old password is right
  // change their password to new_password and return true
  // else return false
  if(login($username, $old_password)){
    if (!($conn = db_connect())){
      return false;
    }
    $result = mysql_query("update user set passwd = password('$new_password') where username = '$username'");
    if (!$result){
      return false; // no cambiado
    }else{
      return true; // cambio correcto	
     }	
  }else{
    return false; // vieja contraseña era erronea
   }
}//fin function

function get_random_word($min_length,$max_length){
// grabar a random word from dictionary between the two lengths
// and return it

    // generate a random word
    $word = "";
    //seguramente la siguiente ruta habra que cambiarla (o crear el archivo words.txt)
    $dictionary = "/web/htdocs/www.illasaron.com/home/cap24_php/usr/dict/words";
    $fp = fopen($dictionary, "r");
    $size = filesize($dictionary);

    // ir a una localizacion aleatoria en el diccionario
    srand ((double) microtime() * 1000000);
    $rand_location = rand(0, $size);
    fseek($fp, $rand_location);

    // tomar la siguiente palabra completa del tamaño correcto en el archivo
    while (strlen($word)< $min_length || strlen($word)>$max_length){
      
      if (feof($fp))
        fseek($fp, 0);		// si llega al final, volver al principio
      $word = fgets($fp, 80);	// saltar la primera palabra que puede ser parcial
      $word = fgets($fp, 80);   // la contraseña potencial
      
      }// fin while

    $word = trim($word); // trim the trailing in from gets
    return $word;

} // fin function

function reset_password($username){
// configura la contraseña para username a un valor aleatorio
// devuelve la nueva contraseña o falso si hay algun error

    // obtener una palabra aleatoria de un diccionario entre 6 y 13 caracteres de tamaño
    $new_password = get_random_word(6, 13);

    // añadirle un numero entre 0 y 999
    // para conseguir un password mas seguro
    srand ((double) microtime() * 1000000);
    $Rand_number = rand(0,999);
    $new_password .=$rand_number;

    // configurar la contraseña del usuario a esta en la base de datos o devolver falso
    if (!($conn = db_connect())){
      return false;
    }	
    $result = mysql_query ("update user set passwd = password('$new_passwrod') where username = '$username'");

    if(!$result){
      return false; // se ha cambiado
    }else{
      return $new_password; // cambio correcto
    }
}//fin function

function notify_password($username, $password){
//notificar al usuario que su contraseña ha sido cambiada

  if (!($conn = db_connect())){
    return false;
  }
  $result = mysql_query("select email from user where username='$username'");

  if(!$result) {
    return false; // no cambiada
  }else if (mysql_num_rows($result)==0){
    return false; // nombre de usuario no esta en la base de datos
  }else{
      $email = mysql_result($result, 0, "email");
      $from = "From: jonmultimedia@gmail.com \r\n Por favor utilizalo la proxima vez que hagas log in. \r\n";
      if (mail($email, "login informacion de CompartElinks", $mesg, $from)){
         return true;
      }else{
         return false;
       }	
  }//fin else

}//fin function
?>
