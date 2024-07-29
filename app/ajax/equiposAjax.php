<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\equiposController;

	if(isset($_POST['modulo_equipos'])){

		$insEquipo = new equiposController();

		if($_POST['modulo_equipos']=="registrar"){
			echo $insEquipo->registrarEquipoControlador();
		}

		if($_POST['modulo_equipos']=="actualizar"){
			echo $insEquipo->actualizarEquipoControlador();
		}

		if($_POST['modulo_equipos']=="actualizarestado"){
			echo $insEquipo->actualizarEstadoEquipoControlador();
		}		

		if($_POST['modulo_equipos']=="eliminar"){
			echo $insEquipo->eliminarEquipoControlador();
		}		
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}