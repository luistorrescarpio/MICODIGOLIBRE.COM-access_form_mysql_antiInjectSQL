<?php 
//Script para conexión con base de datos en Mysql
include("db_controller/mysql_script.php");
$obj = (object)$_REQUEST;

//Aplicamos protección anti inyección SQL
$obj->email = injectProtected_text($obj->email);	
$obj->password = injectProtected_text($obj->password);	

//validación de acceso en DB
$r = query("SELECT * FROM account WHERE ac_email='$obj->email' AND ac_password='$obj->password'");

if( count($r)>0 ){

  session_start();
  $_SESSION['user'] = $r[0]; //Pasamos todos los datos del usuario en la variable de sessión user
                             // Esto permitira tener los datos del usuario en cualquier pagina que tenga 
                             // la sessión iniciada (Esto solo lo almacenara hasta que la sessión sea destruida)

  //Reenviamos a la cuenta del Usuario logeado correctamente
  header("Location: my_account.php");

}else{
  //Si uno de los campos no coincide, el acceso es denegado y retornado al inicio
  header("Location: login.php?message=Correo o Contraseña Incorrecta");
}

function injectProtected_text($text){
	//Funcción que permitira realizar un filtro de los caracteres que permitan la modificación de las consultas SQL (Filtrado de protección SQL)
	$textFiltrado = str_replace(
	   ["'","|","‘","’",'“','”']
	  ,['\"',"","&#8216;","&#8217;","&#8220;","&#8221;"]
	  ,$text
	);
	return $textFiltrado;
}

?>