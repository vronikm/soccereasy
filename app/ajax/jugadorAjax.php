<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\jugadorController;

	if(isset($_POST['modulo_jugador'])){

		$insEquipo = new jugadorController();

		if($_POST['modulo_jugador']=="registrar"){
			echo $insEquipo->registrarEquipoControlador();
		}

		if($_POST['modulo_jugador']=="actualizar"){
			echo $insEquipo->actualizarEquipoControlador();
		}

		if($_POST['modulo_jugador']=="actualizarestado"){
			echo $insEquipo->actualizarEstadoEquipoControlador();
		}		

		if($_POST['modulo_jugador']=="eliminar"){
			echo $insEquipo->eliminarEquipoControlador();
		}		
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}