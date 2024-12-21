<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\menuController;

	if(isset($_POST['modulo_menu'])){

		$insMenu = new menuController();		
		
		if($_POST['modulo_menu']=="crearMenu"){
			echo $insMenu->crearMenu();
		}

		if($_POST['modulo_menu']=="actualizarMenu"){
			echo $insMenu->actualizarMenu();
		}

        if($_POST['modulo_menu']=="asignar"){
			echo $insMenu->asignarPermiso();
		}
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}