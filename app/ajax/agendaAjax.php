<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\agendaController;

	if(isset($_POST['modulo_agenda'])){

		$insAgenda = new agendaController();

		if($_POST['modulo_agenda']=="registrar"){
			echo $insAgenda->registrarEvento();
		}
        if ($_POST['modulo_agenda'] == "listar") {
            echo $insAgenda->obtenerEventos();
        }
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}