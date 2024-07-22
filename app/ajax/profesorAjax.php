<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\profesorController;

	if(isset($_POST['modulo_profesor'])){

		$insProfesor = new profesorController();

		if($_POST['modulo_profesor']=="registrar"){
			echo $insProfesor->registrarProfesorControlador();
		}

		if($_POST['modulo_profesor']=="actualizar"){
			echo $insProfesor->actualizarProfesorControlador();
		}

		if($_POST['modulo_profesor']=="actualizarestado"){
			echo $insProfesor->actualizarEstadoProfesorControlador();
		}		

		if($_POST['modulo_profesor']=="eliminar"){
			echo $insProfesor->eliminarProfesorControlador();
		}		
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}