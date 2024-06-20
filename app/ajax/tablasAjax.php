<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\tablasController;

	if(isset($_POST['modulo_tablas'])){

		$insTabla = new tablasController();

		if($_POST['modulo_tablas']=="registrar"){
			echo $insTabla->registrarTablaControlador();
		}		
		if($_POST['modulo_tablas']=="actualizar"){
			echo $insTabla->actualizarTablaControlador();
		}
		if($_POST['modulo_tablas']=="eliminar"){
			echo $insTabla->eliminarTablaControlador();
		}
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}