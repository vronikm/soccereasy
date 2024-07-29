<?php	
	namespace app\models;

	class viewsModel{
		/*---------- Modelo obtener vista ----------*/
		protected function obtenerVistasModelo($vista){
			$listaBlanca=["dashboard","userNew","userList","userUpdate","userSearch","userPhoto"
						  ,"logOut","roList","escuelaNew","alumnoList","alumnoNew","alumnoUpdate"
						  ,"pagos","userProfile","escuelaNew","sedeList","sedeNew","sedeProfile"
						  ,"sedeUpdate","pagosList","pagosNew","alumnoProfile","pagosUpdate"
						  ,"dashboard","pagosPendiente","pagospendienteUpdate","pagosRecibo"
						  ,"pagospendienteRecibo","pagosDescuento","pagosReciboPDF","pagospendienteReciboPDF"
						  ,"pagosReciboEnvio","reportePagos","reportePendientes","pagospendienteReciboEnvio"
						  ,"asistenciaHora","asistenciaLugar","asistenciaHorario","tablasNew"
						  ,"catalogosNew",'asistenciaListHorario',"representanteList", "representanteNew"
						  ,"representanteProfile","representanteUpdate","representanteVinc"
						  ,"profesorNew","profesorList","profesorProfile","profesorUpdate", "torneosList"
						  ,"equiposList","asistenciaVerHorario"];

			if(in_array($vista, $listaBlanca)){
				if(is_file("./app/views/content/".$vista."-view.php")){
					$contenido="./app/views/content/".$vista."-view.php";
				}else{
					$contenido="404";
				}
			}elseif($vista=="login" || $vista=="index"){
				$contenido="login";
			}else{
				$contenido="404";
			}
			return $contenido;
		}
	}