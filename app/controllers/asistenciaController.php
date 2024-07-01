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
					,CASE WHEN H.hora_estado = 'A' THEN 'Activo' ELSE 'Inactivo' END ESTADO 
					,H.* 
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
						<td>'.$rows['ESTADO'].'</td>
						<td>
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/asistenciaAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_asistencia" value="eliminar">
								<input type="hidden" name="hora_id" value="'.$rows['hora_id'].'">						
								<button type="submit" class="btn float-right btn-danger btn-sm" style="margin-right: 5px;">Eliminar</button>
							</form>							

							<a href="'.APP_URL.'asistenciaHora/'.$rows['hora_id'].'/" class="btn float-right btn-actualizar btn-sm" style="margin-right: 5px;" >Editar</a>
							
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
			$estado			= $this->limpiarCadena($_POST['estado']);
			
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
					"campo_valor"=>$estado
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
					,CASE WHEN L.lugar_estado = 'A' THEN 'Activo' ELSE 'Inactivo' END ESTADO 
					,L.*
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
						<td>'.$rows['ESTADO'].'</td>
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

		public function listarOptionProfesor(){
			
			$option="";
			$consulta_datos="SELECT usuario_id, usuario_nombre FROM seguridad_usuario WHERE usuario_estado = 'A' AND usuario_rolid = '3'";						
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$option.='<option value='.$rows['usuario_id'].'>'.$rows['usuario_nombre'].'</option>';				
			}
			return $option;		
		}

		public function listarOptionSede(){
			$option="";

			$consulta_datos="SELECT sede_id, sede_nombre FROM general_sede";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
					$option.='<option value='.$rows['sede_id'].'>'.$rows['sede_nombre'].'</option>';
			}
			return $option;
		}

		public function listarOptionSedebusqueda($sedeid){
			$option="";

			$consulta_datos="SELECT sede_id, sede_nombre FROM general_sede";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($sedeid == $rows['sede_id']){
					$option.='<option value='.$rows['sede_id'].' selected>'.$rows['sede_nombre'].'</option>';
				}else{
					$option.='<option value='.$rows['sede_id'].'>'.$rows['sede_nombre'].'</option>';	
				}
					
			}
			return $option;
		}

		public function listarOptionLugar($sedeid){
			$option="";
			$consulta_datos="SELECT lugar_id, lugar_sedeid, lugar_nombre 
								FROM asistencia_lugar 
								WHERE  lugar_estado = 'A' 
									AND lugar_sedeid  = ".$sedeid;									
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($sedeid==$rows['lugar_sedeid']){
					$option.='<option value='.$rows['lugar_id'].' selected="selected">'.$rows['lugar_nombre'].'</option>';
				}else{
					$option.='<option value='.$rows['lugar_id'].'>'.$rows['lugar_nombre'].'</option>';	
				}
			}
			return $option;
		}

		public function listarOptionHora(){
			$option="";
			$consulta_datos="SELECT hora_id, CONCAT(hora_detalle, ' | ', hora_inicio, ' - ', hora_fin) AS HORA FROM asistencia_hora WHERE hora_estado = 'A'";						
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$option.='<option value='.$rows['hora_id'].'>'.$rows['HORA'].'</option>';				
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

		public function listarAlumnos($identificacion, $apellidopaterno, $primernombre, $ano, $sede){
					
			if($identificacion!=""){
				$identificacion .= '%'; 
			}
			if($primernombre!=""){
				$primernombre .= '%';
			} 
			if($apellidopaterno!=""){
				$apellidopaterno .= '%';
			} 					

			$tabla="";
			$consulta_datos="SELECT * FROM sujeto_alumno 
								WHERE (alumno_primernombre LIKE '".$primernombre."' 
								OR alumno_identificacion LIKE '".$identificacion."' 
								OR alumno_apellidopaterno LIKE '".$apellidopaterno."') ";			
			if($ano!=""){
				$consulta_datos .= " and YEAR(alumno_fechanacimiento) = '".$ano."'"; 
			}

			if($identificacion=="" && $primernombre=="" && $apellidopaterno==""){
				$consulta_datos="SELECT * FROM sujeto_alumno WHERE YEAR(alumno_fechanacimiento) = '".$ano."'";
			}
			
			if($identificacion=="" && $primernombre=="" && $apellidopaterno=="" && $ano == ""){
				$consulta_datos = "SELECT * FROM sujeto_alumno WHERE alumno_primernombre <> '' ";
			}

			if($sede!=""){
				if($sede == 0){
					$consulta_datos .= " and alumno_sedeid <> '".$sede."'"; 
				}else{
					$consulta_datos .= " and alumno_sedeid = '".$sede."'"; 
				}
			}else{
				$consulta_datos = "SELECT * FROM sujeto_alumno WHERE alumno_primernombre = '' ";
			}			

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				
				$tabla.='
					<tr>
						<td>'.$rows['alumno_identificacion'].'</td>
						<td>'.$rows['alumno_primernombre'].' '.$rows['alumno_segundonombre'].'</td>
						<td>'.$rows['alumno_apellidopaterno'].' '.$rows['alumno_apellidomaterno'].'</td>
						<td>'.$rows['alumno_fechanacimiento'].'</td>
						<td>
							<a href="invoice-print.html" rel="noopener" target="_blank" class="btn float-right btn-danger btn-sm">Eliminar</a>
							<a href="'.APP_URL.'alumnoUpdate/'.$rows['alumno_id'].'/" target="_blank" class="btn float-right btn-actualizar btn-sm" style="margin-right: 5px;">Actualizar</a>							
							<a href="'.APP_URL.'alumnoProfile/'.$rows['alumno_id'].'/" target="_blank" class="btn float-right btn-ver btn-sm" style="margin-right: 5px;">Ver</a>
						</td>
					</tr>';	
			}
			return $tabla;			
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
			$lugar_estado	= $this->limpiarCadena($_POST['estado']);
			
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
					"campo_valor"=>$lugar_estado
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
					"texto"=>"Los datos del lugar ".$lugar_nombre." se actualizaron correctamente",
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
			