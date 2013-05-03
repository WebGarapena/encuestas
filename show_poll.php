<?php

//TENDREMOS QUE RECOGER LOS RESULTADOS POR LA ID DE LA POLL
$vote = $_POST['vote'];

if(!empty($vote)){//si se ha realizado un voto
	$vote = addslashes($vote);
	$query = "update poll_results
					set num_votes = num_votes+1
					where candidate = '$vote'";

    if(!$result = @mysqli_query($db_conn,$query)){
        echo "No has podido conectar a la base de datos<br>";
        exit;
    }//fin if
}//fin if

// obtener los resultados actuales de la encuesta, independientemente de lo que haya votado la gente
$query = "select * from poll_results";
if(!($result = @mysqli_query($db_conn,$query))){
    echo "No se puede conectar a la base de datos";
    exit;
}//fin if

$num_candidates = mysqli_num_rows($result);

//calcular el numero total de votos hasta ahora
$total_votes = 0;

while ($row = mysqli_fetch_object($result)){
    $total_votes += $row->num_votes;
}//fin while

mysqli_data_seek($result,0); //resetear el resultado


/************************************************
    calculo inicial para el grafico
 ************************************************/

//configurar constantes
$width = 500;       //ancho de la imagen en pixeles - encajara en 640x640
$left_margin = 50; // espacio a dejar a la izquierda de la imagen
$right_margin = 50; // lo mismo para la derecha
$bar_height = 40;
$bar_spacing = $bar_height/2;
$font = "./arial.ttf";
$title_size = 16;//puntos
$main_size = 12;//puntos
$small_size = 12;//puntos
$text_indent = 10; //posicion para las etiquetas de texto a la izquierda

// configurar el punto inicial desde el cual dibujar
$x = $left_margin + 60; //colocar la linea de base para dibujar el grafico (linea vertical al comienzo de las tres barras)
$y = 50;                //lo mismo
$bar_unit = ($width-($x+$right_margin)) /100; //un "punto" en el grafico

//calcula el alto del grafico - barras más espacios más el margen
$height = $num_candidates * ($bar_height + $bar_spacing) + 50;		// 230

/************************************************
    configurar la imagen base
 ************************************************/

// crear un lienzo en blanco
$im = imagecreate($width,$height);

//asignar colores
$white = imagecolorallocate($im,255,255,255); 	// 0
$blue = imagecolorallocate($im,0,64,128);	// 1
$black = imagecolorallocate($im,0,0,0);		// 2
$pink = imagecolorallocate($im,255,78,243);	// 3

$text_color = $black;		// 2
$percent_color = $black;	// 2
$bg_color = $white;		// 0
$line_color = $black;		// 2
$bar_color = $blue;		// 1
$number_color = $pink;		// 3

// crear "lienzo" para dibujar (crea zona rectangular con cierto color de fondo)
// Para dibujarlo, se toma su diagonal como referencia, con el punto inicial (0,0)
// y el punto final ($width,height)
imagefilledrectangle($im,0,0,$width,$height,$bg_color);	// 1

// dibujar borde en torno al lienzo (la linea que lo encierra)
// Al poner -1 l oque se consigue es que esa linea vaya por
// dentro del lienzo
imagerectangle($im,0,0,$width-1,$height-1,$line_color); // 1

// añadir titulo
$title = "Resultado Sondeo";					// Resultado Sondeo
$title_dimensions = imagettfbbox($title_size, 0, $font, $title);
$title_length = $title_dimensions[2] - $title_dimensions[0];			// 0
$title_height = abs($title_dimensions[7] - $title_dimensions[1]);		// 0
$title_above_line = abs($title_dimensions[7]);					// 0
$title_x = ($width-$title_length)/2;    //centrarlo en x			// 250
$title_y = ($y - $title_height)/2 + $title_above_line;  //centrarlo en y	// 25
imagettftext($im,$title_size,0,$title_x,$title_y,$text_color,$font,$title);

// Dibujar una linea de base un poco por encima de la primera localizacion de la barra
// a un poco por debajo de la ultima
imageline($im,$x,$y-5,$x,$height-15,$line_color);	// 1

/************************************************
    dibujar los datos en el grafico
 ************************************************/
//obtener cada linea de los datos de la base de datos y dibujar las barras correspondientes
// cada linea se obtiene como un objeto (cada una sera una barra para cada sistema, tres en total)
while ($row = mysqli_fetch_object($result)){
    if($total_votes > 0){
        $percent = intval(round(($row->num_votes/$total_votes)*100));
    }else{
        $percent = 0;
    }//fin else

    //muestra el tanto por ciento para este valor
    imagettftext($im,$main_size,20,$width-30,$y+($bar_height/2),$percent_color,$font,$percent."%");
    if($total_votes > 0){
        $right_value = intval(round(($row->num_votes/$total_votes)*100));
    }else{
        $right_value = 0;
    }//fin else

    // tamaño de barra para este valor
    $bar_length = $x + ($right_value * $bar_unit);	// 110 / 110 / 110

    // dibujar barra para este valor
    imagefilledrectangle($im,$x,$y-2,$bar_length,$y+$bar_height,$bar_color);

    // dibujar titulo para este valor
    imagettftext($im,$main_size,0,$text_indent,$y+($bar_height/2),$text_color,$font,$row->candidate);

    //dibujar contorno mostrando 100%
    imagerectangle($im,$bar_length+1,$y-2,($x+(100*$bar_unit)),$y+$bar_height,$line_color);

    //mostrar numeros
    imagettftext($im, $small_size,0,$x+(100*$bar_unit)-50, $y+($bar_height/2),
                $number_color,$font,$row->num_votes."/".$total_votes);

    //hacia abajo a la siguiente barra
    $y = $y+($bar_height+$bar_spacing); // 110 / 170 / 230	

}//fin while


/************************************************
    mostrar imagenes
 ************************************************/
header("Content-type: image/png");
imagepng($im);


/************************************************
    limpiar
 ************************************************/
imagedestroy($im);

?>
