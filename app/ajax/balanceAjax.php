<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\balanceController;

	if(isset($_POST['modulo_ingreso'])){

		$insIngreso = new balanceController();

		if($_POST['modulo_ingreso']=="registrar"){
			echo $insIngreso->registrarIngreso();
		}		
		if($_POST['modulo_ingreso']=="actualizar"){
			echo $insIngreso->actualizarIngreso();
		}
		if($_POST['modulo_ingreso']=="eliminar"){
			echo $insIngreso->eliminarIngreso();
		}

	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}