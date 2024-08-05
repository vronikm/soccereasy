<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\jugadorController;

	if(isset($_POST['modulo_jugador'])){

		$insjugador = new jugadorController();

		if($_POST['modulo_jugador']=="registrar"){
			echo $insjugador->guardarListaJugadores();
		}

		if($_POST['modulo_jugador']=="agregar"){
			echo $insjugador->agregarJugador();
		}

		if($_POST['modulo_jugador']=="eliminar"){
			echo $insjugador->eliminarJugador();
		}	

		if($_POST['modulo_jugador']=="actualizar"){
			echo $insjugador->actualizarListaJugadores();
		}

			
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}