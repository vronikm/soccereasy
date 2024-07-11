<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\representanteController;

	if(isset($_POST['modulo_repre'])){

		$insRepresentante = new representanteController();

		if($_POST['modulo_repre']=="registrar"){
			echo $insRepresentante->registrarRepresentanteControlador();
		}

		if($_POST['modulo_repre']=="actualizar"){
			echo $insRepresentante->actualizarRepresentanteControlador();
		}
		
		if($_POST['modulo_repre']=="eliminar"){
			echo $insRepresentante->eliminarRepresentanteControlador();
		}
		
		if($_POST['modulo_repre']=="vincularepresentado"){
			echo $insRepresentante->vincularRepresentado();
		}
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}