<?php

	namespace app\controllers;
	use app\models\mainModel;

	class asistenciaController extends mainModel{

		
		public function registrarHoraControlador(){							
			
			# Almacenando datos#
			$hora_inicio 	= $this->limpiarCadena($_POST['hora_inicio']);
			$hora_fin		= $this->limpiarCadena($_POST['hora_fin']);
			$hora_detalle	= $this->limpiarCadena($_POST['detalle']);
			
			# Verificando campos obligatorios #
		    if($hora_inicio=="" || $hora_fin=="" ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);       
		    }		

			$hora_datos_reg=[
				[
					"campo_nombre"=>"hora_inicio",
					"campo_marcador"=>":Horainicio",
					"campo_valor"=>$hora_inicio
				],
				[
					"campo_nombre"=>"hora_fin",
					"campo_marcador"=>":Horafin",
					"campo_valor"=>$hora_fin
				],				
				[
					"campo_nombre"=>"hora_detalle",
					"campo_marcador"=>":Horadetalle",
					"campo_valor"=>$hora_detalle
				],				
				[
					"campo_nombre"=>"hora_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>'A'
				]
			];		

			$registrar_hora=$this->guardarDatos("asistencia_hora",$hora_datos_reg);

			if($registrar_hora->rowCount()>0){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Registro de horass",
					"texto"=>"La hora se registró correctamente",
					"icono"=>"success"
				];				
			
			}else{				

				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar la hora, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);

		}

		public function listarHoras(){ //29052024
			
			$tabla="";
		
			$consulta_datos="SELECT ROW_NUMBER() OVER (ORDER BY hora_id) AS fila_numero
					, H.* 
				FROM asistencia_hora H  
				where H.hora_estado != 'E'
				ORDER BY hora_id ASC";		

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
			
				$tabla.='
					<tr>
						<td>'.$rows['fila_numero'].'</td>
						<td>'.$rows['hora_inicio'].'</td>
						<td>'.$rows['hora_fin'].'</td>
						<td>'.$rows['hora_detalle'].'</td>
						<td>
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/asistenciaAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_asistencia" value="eliminar">
								<input type="hidden" name="hora_id" value="'.$rows['hora_id'].'">						
								<button type="submit" class="btn float-right btn-danger btn-sm" style="margin-right: 5px;">Eliminar</button>
							</form>							

							<a href="'.APP_URL.'asistenciaHora/'.$rows['hora_id'].'/" class="btn float-right btn-success btn-sm" style="margin-right: 5px;" >Editar</a>
							
						</td>
					</tr>';	
			}
			return $tabla;			
		}

		public function actualizarHoraControlador(){
			
			$horaid=$this->limpiarCadena($_POST['hora_id']);

			# Verificando pago #

			$datos = $this->ejecutarConsulta("SELECT * FROM asistencia_hora WHERE hora_id = '$horaid'");			
			if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurró un error inesperado",
					"texto"=>"No hemos encontrado la hora en el sistema: ".$horaid,
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }else{
		    	$datos=$datos->fetch();				
		    }				

			# Almacenando datos#
			$hora_inicio 	= $this->limpiarCadena($_POST['hora_inicio']);
			$hora_fin		= $this->limpiarCadena($_POST['hora_fin']);
			$hora_detalle	= $this->limpiarCadena($_POST['detalle']);
			
			# Verificando campos obligatorios #
		    if($hora_inicio=="" || $hora_fin=="" ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        
		    }			

			$hora_datos_reg=[
				[
					"campo_nombre"=>"hora_inicio",
					"campo_marcador"=>":Horainicio",
					"campo_valor"=>$hora_inicio
				],
				[
					"campo_nombre"=>"hora_fin",
					"campo_marcador"=>":Horafin",
					"campo_valor"=>$hora_fin
				],				
				[
					"campo_nombre"=>"hora_detalle",
					"campo_marcador"=>":Horadetalle",
					"campo_valor"=>$hora_detalle
				]
			];			
		
			$condicion=[
				"condicion_campo"=>"hora_id",
				"condicion_marcador"=>":Horaid",
				"condicion_valor"=>$horaid
			];			

			if($this->actualizarDatos("asistencia_hora",$hora_datos_reg,$condicion)){				
				
				$alerta=[
					"tipo"=>"redireccionar",			
					"url"=>APP_URL.'asistenciaHora/',					
					"titulo"=>"Hora actualizada",
					"texto"=>"Los datos de la hora ".$horaid." se actualizaron correctamente",
					"icono"=>"success"	
				];								

			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar los datos de la hora ".$horaid.", por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}

		public function BuscarHora($horaid){
		
			$consulta_datos="SELECT H.* 
					FROM asistencia_hora H										
					WHERE hora_id = ".$horaid;	

			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
		}

		public function eliminarHoraControlador(){
			
			$horaid=$this->limpiarCadena($_POST['hora_id']);

			$hora_datos=[
				[
					"campo_nombre"=>"hora_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=> 'E'
				]
			];

			$condicion=[
				"condicion_campo"=>"hora_id",
				"condicion_marcador"=>":Horaid",
				"condicion_valor"=>$horaid
			];

			if($this->actualizarDatos("asistencia_hora", $hora_datos, $condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Hora eliminada",
					"texto"=>"La hora fue eliminada correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido eliminar la hora, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}

		//-------------------------------------------------lugar--------------------------------------
		public function BuscarLugar($lugarid){
		
			$consulta_datos="SELECT L.* 
					FROM asistencia_lugar L									
					WHERE L.lugar_id = ".$lugarid;	

			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
		}

		public function listarLugar(){
			
			$tabla="";
		
			$consulta_datos="SELECT ROW_NUMBER() OVER (ORDER BY L.lugar_id) AS fila_numero
					, L.*
					,S.sede_nombre
				FROM asistencia_lugar L  
					INNER JOIN general_sede S ON S.sede_id = L.lugar_sedeid 
				where L.lugar_estado != 'E'
				ORDER BY L.lugar_id ASC";		

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
			
				$tabla.='
					<tr>
						<td>'.$rows['fila_numero'].'</td>
						<td>'.$rows['sede_nombre'].'</td>
						<td>'.$rows['lugar_nombre'].'</td>
						<td>'.$rows['lugar_direccion'].'</td>
						<td>'.$rows['lugar_detalle'].'</td>
						<td>
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/asistenciaAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_asistencia" value="eliminar_lugar">
								<input type="hidden" name="lugar_id" value="'.$rows['lugar_id'].'">						
								<button type="submit" class="btn float-right btn-danger btn-sm" style="margin-right: 5px;">Eliminar</button>
							</form>							

							<a href="'.APP_URL.'asistenciaLugar/'.$rows['lugar_id'].'/" class="btn float-right btn-success btn-sm" style="margin-right: 5px;" >Editar</a>
							
						</td>
					</tr>';	
			}
			return $tabla;			
		}

		public function listarOptionSede($lugarid){
			$option="";

			$consulta_datos="SELECT sede_id, sede_nombre FROM general_sede";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($lugarid == $rows['sede_id']){
					$option.='<option value='.$rows['sede_id'].' selected="selected">'.$rows['sede_nombre'].'</option>';	
				}else{
					$option.='<option value='.$rows['sede_id'].'>'.$rows['sede_nombre'].'</option>';	
				}
			}
			return $option;
		}

		public function registrarLugarControlador(){		

			# Almacenando datos#			
			$lugar_sedeid  	= $this->limpiarCadena($_POST['lugar_sedeid']);
			$lugar_nombre	= $this->limpiarCadena($_POST['lugar_nombre']);
			$lugar_direccion= $this->limpiarCadena($_POST['lugar_direccion']);
			$lugar_detalle	= $this->limpiarCadena($_POST['lugar_detalle']);			
			
			# Verificando campos obligatorios #
		    if($lugar_sedeid=="" || $lugar_nombre=="" || $lugar_direccion==""){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        
		    }			

			$lugar_datos_reg=[
				[
					"campo_nombre"=>"lugar_sedeid",
					"campo_marcador"=>":Sedeid",
					"campo_valor"=>$lugar_sedeid
				],
				[
					"campo_nombre"=>"lugar_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$lugar_nombre
				],				
				[
					"campo_nombre"=>"lugar_direccion",
					"campo_marcador"=>":Direccion",
					"campo_valor"=>$lugar_direccion
				],	
				[
					"campo_nombre"=>"lugar_detalle",
					"campo_marcador"=>":Detalle",
					"campo_valor"=>$lugar_detalle
				],			
				[
					"campo_nombre"=>"lugar_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>"A"
				]
			];		

			$registrar_hora=$this->guardarDatos("asistencia_lugar",$lugar_datos_reg);

			if($registrar_hora->rowCount()>0){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Registro lugar de entrenamiento",
					"texto"=>"Lugar de entrenamiento registrado correctamente",
					"icono"=>"success"
				];				
			
			}else{				

				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar el lugar de entrenamiento, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}

		public function actualizarLugarControlador(){
			
			$lugarid =$this->limpiarCadena($_POST['lugar_id']);

			# Verificando pago #

			$datos = $this->ejecutarConsulta("SELECT lugar_id FROM asistencia_lugar WHERE lugar_id = '$lugarid '");			
			if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurró un error inesperado",
					"texto"=>"No hemos encontrado la hora en el sistema: ".$lugarid,
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }else{
		    	$datos=$datos->fetch();				
		    }				

			# Almacenando datos#
			$lugar_sedeid  	= $this->limpiarCadena($_POST['lugar_sedeid']);
			$lugar_nombre	= $this->limpiarCadena($_POST['lugar_nombre']);
			$lugar_direccion= $this->limpiarCadena($_POST['lugar_direccion']);
			$lugar_detalle	= $this->limpiarCadena($_POST['lugar_detalle']);
			
			# Verificando campos obligatorios #
		    if($lugar_sedeid=="" || $lugar_nombre=="" || $lugar_direccion==""){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        
		    }			

			$lugar_datos_reg=[
				[
					"campo_nombre"=>"lugar_sedeid",
					"campo_marcador"=>":Sedeid",
					"campo_valor"=>$lugar_sedeid
				],
				[
					"campo_nombre"=>"lugar_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$lugar_nombre
				],				
				[
					"campo_nombre"=>"lugar_direccion",
					"campo_marcador"=>":Direccion",
					"campo_valor"=>$lugar_direccion
				],	
				[
					"campo_nombre"=>"lugar_detalle",
					"campo_marcador"=>":Detalle",
					"campo_valor"=>$lugar_detalle
				],			
				[
					"campo_nombre"=>"lugar_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>"A"
				]
			];			
		
			$condicion=[
				"condicion_campo"=>"lugar_id",
				"condicion_marcador"=>":Lugarid",
				"condicion_valor"=>$lugarid
			];			

			if($this->actualizarDatos("asistencia_lugar",$lugar_datos_reg,$condicion)){				
				
				$alerta=[
					"tipo"=>"redireccionar",			
					"url"=>APP_URL.'asistenciaLugar/',					
					"titulo"=>"Lugar actualizado",
					"texto"=>"Los datos del lugar ".$lugarid." se actualizaron correctamente",
					"icono"=>"success"	
				];								

			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar los datos de la hora ".$lugarid.", por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}

		public function eliminarLugarControlador(){
			
			$lugarid=$this->limpiarCadena($_POST['lugar_id']);

			$lugar_datos=[
				[
					"campo_nombre"=>"lugar_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=> 'E'
				]
			];

			$condicion=[
				"condicion_campo"=>"lugar_id",
				"condicion_marcador"=>":Lugarid",
				"condicion_valor"=>$lugarid
			];

			if($this->actualizarDatos("asistencia_lugar", $lugar_datos, $condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Lugar eliminado",
					"texto"=>"El lugar fue eliminado correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido eliminar el lugar, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}
		
	}
			