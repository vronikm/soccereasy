<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\torneosController;

	if(isset($_POST['modulo_torneos'])){

		$insTorneo = new torneosController();

		if($_POST['modulo_torneos']=="registrar"){
			echo $insTorneo->registrarTorneoControlador();
		}

		if($_POST['modulo_torneos']=="actualizar"){
			echo $insTorneo->actualizarTorneoControlador();
		}

		if($_POST['modulo_torneos']=="actualizarestado"){
			echo $insTorneo->actualizarEstadoTorneoControlador();
		}		

		if($_POST['modulo_torneos']=="eliminar"){
			echo $insTorneo->eliminarTorneoControlador();
		}		
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}