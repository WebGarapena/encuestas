<?php
require_once("db_fns.php");

function get_user_urls($valid_user){// OK //
  //extract from the database all the URLs this user has stored
  if (!($conn = db_connect())){
    return false;
  }
  $result = mysqli_query($conn,"select bm_URL from bookmark where username = '$valid_user'");
  if(!$result){
    return false;
  }
  //create an array of the URLs
  $url_array = array();
  for($count = 1; $row = mysqli_fetch_row($result); ++$count){
    $url_array[$count] = $row[0];
  }//fin for

  return $url_array;

}//fin function


function add_bm($new_url){ // OK //
//Añade un nuevo marcador a la base de datos
	echo "Intentando añadir ".htmlspecialchars($new_url)."<BR>";
	global $valid_user; //en este momento esta vacia
	$valid_user = $_SESSION['valid_user'];
	if (!($conn = db_connect())){
		return false;
	}
	//comprueba que el marcador no este repetido
	$consulta = mysqli_query($conn,"select * from bookmark where username='$valid_user' and bm_URL='$new_url'");
	//print_r ($consulta);
	
	//Se puede usar cualquiera de las siguientes lineas para averiguar el numero de filas que devuelve la consulta
	//echo $consulta->num_rows;
	//echo mysqli_num_rows($consulta);
	//es orientado a objetos, asi que en vez de usar mysql_num_rows, sacamos el numero de filas del atributo num_rows
	if (isset($consulta) && ($consulta->num_rows>0)){ // Si la persona ya tiene el marcador nos da false.
		return false;
	}
	if (!mysqli_query($conn,"insert into bookmark values('$valid_user', '$new_url')")){
		return false;
	}
	return true;
}//fin function


function delete_bm($user, $url){

  //delete one URL from the database
  if(!($result = db_connect())){
    return false;
  }
  //delete the bookmark
  if (!mysqli_query($conn, "delete from bookmark where username='$user' AND bm_url='$url'")){
    return false;
  }

  return true;

}//fin function


//CREO QUE LA SIGUIENTE FUNCION VA BIEN AQUI
function recommend_urls($valid_user, $popularity = 1){
  // Con ella enviaremos recomendaciones semi inteligentes a la gente
  // Si tienen una URL en comun con otros usuarios,
  // puede que tambien les gusten las otras URLs de esa gente
  if(!($conn = db_connect())){
    return false;
  }
  // encontrar otros usuarios que coincidan
  // with alguna url igual que la suya

  if (!($result = mysqli_query($conn, "select distinct(b2.username) from bookmark b1, bookmark b2 where 
			b1.username='$valid_user'and b1.username != b2.username and b1.bm_URL = b2.bm_URL"))){
    return false;
  }
  if (mysqli_num_rows($result)==0){
    return false;
  }
  // crear conjunto de usuarios con urls en comun
  // para usar en clausula IN
  $row = mysqli_fetch_object($result);
  $sim_users = "('".($row->username)."'";
  
  while ($row = mysqli_fetch_object($result)){
    $sim_users .= ", '".($row->username)."'";
  }//fin while
  $sim_users .= ")";

  // crear lista de urls de usuarios
  // para evitar replicar aquellas que ya conozcan
  if (!($result = mysqli_query($conn,"select bm_URL from bookmark where username='$valid_user'"))){
    return false;
  }
  //crear conjunto de urls de usuarios para en clausula IN
  $row = mysqli_fetch_object($result);
  $user_urls = "('".($row->bm_URL)."'";
  while ($row = mysqli_fetch_object($result)){
    $user_urls .= ", '".($row->bm_URL)."'";
  }//fin while
  $user_urls .= ")";

  // como un metodo simple de excluir las paginas privadas de la gente, e
  // incrementar las posibilidades de recomendar URLs interesantes,
  // especificaremos un nivel minimo de popularidad
  // si $popularity = 1, entonces mas de una persona debe tener
  // una URL antes de que sea recomendada

  // encontrar el numero maximo de posibles URLs
  if (!($result = mysqli_query( $conn, "select bm_URL from bookmark where username in $sim_users and bm_URL not in $user_urls
			group by bm_URL having count(bm_URL)>$popularity "))){
    return false;
  } 
  if (!($num_urls = mysqli_num_rows($result))){
    return false;
  }
  $urls = array();

  //construir un array de las urls relevantes
  for ($count=0; $row = mysqli_fetch_object($result); $count++){
    $urls[$count] = $row->bm_URL;
  }//fin for

}//fin function

?>
