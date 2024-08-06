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
					"titulo"=>"Error",
					"texto"=>"No ha completado todos los campos obligatorios",
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
					"titulo"=>"Registro exitoso",
					"texto"=>"La hora se registró correctamente",
					"icono"=>"success"
				];				
			
			}else{				

				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No fue posible registrar la hora, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
		}

		public function listarHoras(){ 			
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
								<button type="submit" class="btn float-right btn-danger btn-xs" style="margin-right: 5px;">Eliminar</button>
							</form>	
							<a href="'.APP_URL.'asistenciaHora/'.$rows['hora_id'].'/" class="btn float-right btn-actualizar btn-xs" style="margin-right: 5px;" >Editar</a>							
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
					"titulo"=>"Error",
					"texto"=>"No existe la hora en el sistema: ".$horaid,
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
					"titulo"=>"Error",
					"texto"=>"No ha completado todos los campos obligatorios",
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
					"titulo"=>"Error",
					"texto"=>"No fue posible actualizar los datos de la hora ".$horaid.", por favor intente nuevamente",
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
					"titulo"=>"Error",
					"texto"=>"No fue posible eliminar la hora, por favor intente nuevamente",
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
						<td><a href="'.$rows['lugar_detalle'].'" target="_blank">'.$rows['lugar_detalle'].'</a></td>
						<td>'.$rows['ESTADO'].'</td>
						<td>
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/asistenciaAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_asistencia" value="eliminar_lugar">
								<input type="hidden" name="lugar_id" value="'.$rows['lugar_id'].'">						
								<button type="submit" class="btn float-right btn-danger btn-xs" style="margin-right: 5px;">Eliminar</button>
							</form>							

							<a href="'.APP_URL.'asistenciaLugar/'.$rows['lugar_id'].'/" class="btn float-right btn-success btn-xs" style="margin-right: 5px;" >Editar</a>
							
						</td>
					</tr>';	
			}
			return $tabla;			
		}
		public function listarOptionProfesor($lugar_sedeid, $profesorid){			
			$option="";
			$consulta_datos="SELECT profesor_id, profesor_nombre 
				FROM sujeto_profesor
				WHERE profesor_estado = 'A'";
							
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($profesorid == $rows["profesor_id"]){
					$option.='<option value='.$rows['profesor_id'].' selected>'.$rows['profesor_nombre'].'</option>';	
				}else{
					$option.='<option value='.$rows['profesor_id'].'>'.$rows['profesor_nombre'].'</option>';	
				}			
			}
			return $option;
		}

		public function listarDetalleHorario($horario_id){			
			$option="";
			$consulta_datos="SELECT  lugar_id, lugar_sedeid, detalle_horaid, profesor_id, detalle_dia	  
							FROM asistencia_horario_detalle
							LEFT JOIN asistencia_lugar ON lugar_id = detalle_lugarid
							LEFT JOIN asistencia_hora ON hora_id = detalle_horaid 
							LEFT JOIN sujeto_profesor ON profesor_id = detalle_profesorid	 
							WHERE detalle_horarioid = ".$horario_id
							.' ORDER BY detalle_dia';
							
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$LU = "";
				$MA = "";
				$MI = "";
				$JU = "";
				$VI = "";
				$SA = "";
				$DO = "";		

				switch ($rows["detalle_dia"]) {
					case 1:
						$dia = 'Lunes';
						$LU = "selected";
						break;
					case 2:
						$dia = 'Martes';
						$MA = "selected";
						break;
					case 3:
						$dia = 'Miércoles';
						$MI = "selected";
						break;
					case 4:
						$dia = 'Jueves';
						$JU = "selected";
						break;
					case 5:
						$dia = 'Viernes';
						$VI = "selected";
						break;
					case 6:
						$dia = 'Sábado';
						$SA = "selected";
						break;
					case 7:
						$dia = 'Domingo';
						$DO = "selected";
						break;
					default:
						$dia = 'Día desconocido';
				}

				// Columna 1: Días de la semana
				$column1 = "<select class='form-control' name='dia[]'>
							<option value='1' ".$LU.">Lunes</option>
							<option value='2' ".$MA.">Martes</option>
							<option value='3' ".$MI.">Miércoles</option>
							<option value='4' ".$JU.">Jueves</option>
							<option value='5' ".$VI.">Viernes</option>
							<option value='6' ".$SA.">Sábado</option>
							<option value='7' ".$DO.">Domingo</option>
							</select>";

				// Columna 2: Lugares de entrenamiento con PHP
				$column2 = "<select class='form-control' id='lugar' name='lugar[]'>".$this->listarOptionLugar($rows['lugar_sedeid'], $rows['lugar_id'])."</select>";
				
				// Columna 3: Horarios con PHP
				$column3 = "<select class='form-control' id='hora' name='hora[]'>".$this->listarOptionHora($rows['detalle_horaid'])."</select>";
				
				// Columna 4: Profesores con PHP
				$column4 = "<select class='form-control' id='profesor' name='profesor[]'>".$this->listarOptionProfesor($rows['lugar_sedeid'], $rows['profesor_id'])."</select>";
				
				$option.=		
					"<tr><td>".$column1."</td>
					<td>".$column2."</td>
					<td>".$column3."</td>
					<td>".$column4."</td>                  
					<td><button type='button' class='btn btn-danger btn-xs btn-icon icon-left btn_remove float-right'>Eliminar<i class='entypo-trash'></i></button></td></tr>";	
			}
			return $option;
		}
		public function informacionEscuela(){		
			$consulta_datos="SELECT * FROM general_escuela WHERE escuela_id  = 1";
			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
		}

		public function generarHorario($horario_id){			
			$tabla="";
			$consulta_datos = "SELECT  
								'Horario' AS Categoria,
								MAX(CASE WHEN detalle_dia = 1 THEN CONCAT(hora_inicio, ' - ', hora_fin) END) AS Lunes,
								MAX(CASE WHEN detalle_dia = 2 THEN CONCAT(hora_inicio, ' - ', hora_fin) END) AS Martes,
								MAX(CASE WHEN detalle_dia = 3 THEN CONCAT(hora_inicio, ' - ', hora_fin) END) AS Miercoles,
								MAX(CASE WHEN detalle_dia = 4 THEN CONCAT(hora_inicio, ' - ', hora_fin) END) AS Jueves,
								MAX(CASE WHEN detalle_dia = 5 THEN CONCAT(hora_inicio, ' - ', hora_fin) END) AS Viernes							
							FROM asistencia_horario 
							INNER JOIN asistencia_horario_detalle ON detalle_horarioid = horario_id 
							LEFT JOIN asistencia_hora ON hora_id = detalle_horaid 
							WHERE horario_id = ".$horario_id."
							GROUP BY Categoria
							
							UNION ALL
							
							SELECT 
								'Cancha' AS Categoria,
								MAX(CASE WHEN detalle_dia = 1 THEN lugar_nombre END) AS Lunes,
								MAX(CASE WHEN detalle_dia = 2 THEN lugar_nombre END) AS Martes,
								MAX(CASE WHEN detalle_dia = 3 THEN lugar_nombre END) AS Miercoles,
								MAX(CASE WHEN detalle_dia = 4 THEN lugar_nombre END) AS Jueves,
								MAX(CASE WHEN detalle_dia = 5 THEN lugar_nombre END) AS Viernes								
							FROM asistencia_horario 
							INNER JOIN asistencia_horario_detalle ON detalle_horarioid = horario_id 
							LEFT JOIN asistencia_lugar ON lugar_id = detalle_lugarid
							WHERE horario_id = ".$horario_id."
							GROUP BY Categoria
							
							UNION ALL
							
							SELECT 
								'Profesor' AS Categoria,
								MAX(CASE WHEN detalle_dia = 1 THEN profesor_nombre END) AS Lunes,
								MAX(CASE WHEN detalle_dia = 2 THEN profesor_nombre END) AS Martes,
								MAX(CASE WHEN detalle_dia = 3 THEN profesor_nombre END) AS Miercoles,
								MAX(CASE WHEN detalle_dia = 4 THEN profesor_nombre END) AS Jueves,
								MAX(CASE WHEN detalle_dia = 5 THEN profesor_nombre END) AS Viernes
							FROM asistencia_horario 
							INNER JOIN asistencia_horario_detalle ON detalle_horarioid = horario_id 
							LEFT JOIN sujeto_profesor ON profesor_id = detalle_profesorid	 
							WHERE horario_id = ".$horario_id."
							GROUP BY Categoria";
		
							
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$tabla.="	<tr style='font-size: 14px'>					
								<th>".$rows['Categoria']."</th>	
								<td>".$rows['Lunes']."</td>
								<td>".$rows['Martes']."</td>
								<td>".$rows['Miercoles']."</td>
								<td>".$rows['Jueves']."</td>
								<td>".$rows['Viernes']."</td>																														
							</tr>";
			}
			return $tabla;
		}

		public function HorarioPDF($horario_id){		
			$consulta_datos = "SELECT  
								'Horario' AS Categoria,
								MAX(CASE WHEN detalle_dia = 1 THEN CONCAT(hora_inicio, ' - ', hora_fin) END) AS Lunes,
								MAX(CASE WHEN detalle_dia = 2 THEN CONCAT(hora_inicio, ' - ', hora_fin) END) AS Martes,
								MAX(CASE WHEN detalle_dia = 3 THEN CONCAT(hora_inicio, ' - ', hora_fin) END) AS Miercoles,
								MAX(CASE WHEN detalle_dia = 4 THEN CONCAT(hora_inicio, ' - ', hora_fin) END) AS Jueves,
								MAX(CASE WHEN detalle_dia = 5 THEN CONCAT(hora_inicio, ' - ', hora_fin) END) AS Viernes							
							FROM asistencia_horario 
							INNER JOIN asistencia_horario_detalle ON detalle_horarioid = horario_id 
							LEFT JOIN asistencia_hora ON hora_id = detalle_horaid 
							WHERE horario_id = ".$horario_id;		
							
			$datos = $this->ejecutarConsulta($consulta_datos);			
			return $datos;
		}

		public function CanchaPDF($horario_id){
			$consulta_datos = "SELECT 
								'Cancha' AS Categoria,
								MAX(CASE WHEN detalle_dia = 1 THEN lugar_nombre END) AS Lunes,
								MAX(CASE WHEN detalle_dia = 2 THEN lugar_nombre END) AS Martes,
								MAX(CASE WHEN detalle_dia = 3 THEN lugar_nombre END) AS Miercoles,
								MAX(CASE WHEN detalle_dia = 4 THEN lugar_nombre END) AS Jueves,
								MAX(CASE WHEN detalle_dia = 5 THEN lugar_nombre END) AS Viernes								
							FROM asistencia_horario 
							INNER JOIN asistencia_horario_detalle ON detalle_horarioid = horario_id 
							LEFT JOIN asistencia_lugar ON lugar_id = detalle_lugarid
							WHERE horario_id = ".$horario_id;		
							
			$datos = $this->ejecutarConsulta($consulta_datos);			
			return $datos;
		}

		public function ProfesorPDF($horario_id){			
			$consulta_datos = "SELECT 
								'Profesor' AS Categoria,
								MAX(CASE WHEN detalle_dia = 1 THEN profesor_nombre END) AS Lunes,
								MAX(CASE WHEN detalle_dia = 2 THEN profesor_nombre END) AS Martes,
								MAX(CASE WHEN detalle_dia = 3 THEN profesor_nombre END) AS Miercoles,
								MAX(CASE WHEN detalle_dia = 4 THEN profesor_nombre END) AS Jueves,
								MAX(CASE WHEN detalle_dia = 5 THEN profesor_nombre END) AS Viernes
							FROM asistencia_horario 
							INNER JOIN asistencia_horario_detalle ON detalle_horarioid = horario_id 
							LEFT JOIN sujeto_profesor ON profesor_id = detalle_profesorid	 
							WHERE horario_id = ".$horario_id;		
							
			$datos = $this->ejecutarConsulta($consulta_datos);	
			return $datos;
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

		public function listarOptionLugar($sedeid, $lugarid){
			$option="";
			$consulta_datos="SELECT lugar_id, lugar_sedeid, lugar_nombre 
								FROM asistencia_lugar 
								WHERE  lugar_estado = 'A' 
									AND lugar_sedeid  = ".$sedeid;		

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($lugarid != 0){
					if($lugarid==$rows['lugar_id']){
						$option.='<option value='.$rows['lugar_id'].' selected="selected">'.$rows['lugar_nombre'].'</option>';
					}else{
						$option.='<option value='.$rows['lugar_id'].'>'.$rows['lugar_nombre'].'</option>';	
					}
				}else{	
					if($sedeid==$rows['lugar_sedeid']){
						$option.='<option value='.$rows['lugar_id'].' selected="selected">'.$rows['lugar_nombre'].'</option>';
					}else{
						$option.='<option value='.$rows['lugar_id'].'>'.$rows['lugar_nombre'].'</option>';	
					}
				}
			}
			return $option;
		}

		public function listarOptionHora($horaid){
			$option="";
			$consulta_datos="SELECT hora_id, CONCAT(hora_detalle, ' | ', hora_inicio, ' - ', hora_fin) AS HORA FROM asistencia_hora WHERE hora_estado = 'A'";						
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){				
				if($horaid==$rows['hora_id']){
					$option.='<option value='.$rows['hora_id'].' selected>'.$rows['HORA'].'</option>';
				}else{
					$option.='<option value='.$rows['hora_id'].'>'.$rows['HORA'].'</option>';	
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
					"titulo"=>"Error",
					"texto"=>"No ha completado todos los campos obligatorios",
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
					"titulo"=>"Registro exitoso",
					"texto"=>"Lugar de entrenamiento registrado correctamente",
					"icono"=>"success"
				];				
			
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No se pudo registrar el lugar de entrenamiento, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
		}

		public function listarHorarios($horario_nombre, $horario_detalle, $horario_sedeid){					
			if($horario_nombre!=""){
				$horario_nombre .= '%'; 
			}
			if($horario_detalle!=""){
				$horario_detalle .= '%';
			} 			

			$tabla="";
			$consulta_datos="SELECT * FROM asistencia_horario 
								WHERE (horario_nombre LIKE '".$horario_nombre."' 
								OR horario_detalle LIKE '".$horario_detalle."') ";			

			if($horario_nombre=="" && $horario_detalle=="" ){
				$consulta_datos="SELECT * FROM asistencia_horario WHERE horario_nombre <> '' ";
			}

			if($horario_sedeid!=""){
				if($horario_sedeid == 0){
					$consulta_datos .= " and horario_sedeid  <> '".$horario_sedeid."'"; 
				}else{
					$consulta_datos .= " and horario_sedeid  = '".$horario_sedeid."'"; 
				}
			}else{
				$consulta_datos = "SELECT * FROM asistencia_horario WHERE horario_nombre = '' ";
			}			

			$consulta_datos .= " AND horario_estado <> 'E'"; 
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if ($rows['horario_estado'] == 'A'){
					$estado = 'Activo';
					$class = '';
				}elseif($rows['horario_estado'] == 'E'){
					$estado = '<span class="badge bg-danger">ELIMINADO';
					$class = 'class="text-danger"';
				}elseif($rows['horario_estado'] == 'I'){
					$estado = 'Inactivo';
					$class = 'class="text-primary"';
				}				
				$tabla.='
					<tr '.$class.'>
						<td>'.$rows['horario_nombre'].'</td>
						<td>'.$rows['horario_detalle'].'</td>
						<td>'.$estado.'</td>
						<td>
							<a href="invoice-print.html" rel="noopener" class="btn float-right btn-danger btn-xs">Eliminar</a>							
							<a href="'.APP_URL.'asistenciaHorario/'.$rows['horario_id'].'/" target="_blank" class="btn float-right btn-actualizar btn-xs" style="margin-right: 5px;">Editar</a>
							<a href="'.APP_URL.'asistenciaVerHorario/'.$rows['horario_id'].'/" target="_blank" class="btn float-right btn-ver btn-xs" style="margin-right: 5px;">Ver</a>
							<a href="'.APP_URL.'asistenciaHorarioJugador/'.$rows['equipo_id'].'/" class="btn float-right btn-warning btn-xs" style="margin-right: 5px;">Asignar</a>
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
					"titulo"=>"Error",
					"texto"=>"No se encuentra la hora en el sistema: ".$lugarid,
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
					"titulo"=>"Error",
					"texto"=>"No ha completado todos los campos obligatorios",
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
					"titulo"=>"Error",
					"texto"=>"No fue posible actualizar los datos de la hora ".$lugarid.", por favor intente nuevamente",
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
					"titulo"=>"Error",
					"texto"=>"No fue posible eliminar el lugar, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
		}

		public function registrarHorario(){				
			# Almacenando datos 			
			$lugar_sedeid		= $this->limpiarCadena($_POST['lugar_sedeid']);
			$horario_nombre		= $this->limpiarCadena($_POST['horario_nombre']);
			$horario_detalle	= $this->limpiarCadena($_POST['horario_detalle']);

			# Verificando campos obligatorios #
		    if($lugar_sedeid=="" || $horario_nombre=="" ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No ha completado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);       
		    }		

			$horario_datos_reg=[
				[
					"campo_nombre"=>"horario_sedeid",
					"campo_marcador"=>":Sedeid",
					"campo_valor"=>$lugar_sedeid
				],
				[
					"campo_nombre"=>"horario_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$horario_nombre
				],				
				[
					"campo_nombre"=>"horario_detalle",
					"campo_marcador"=>":Detalle",
					"campo_valor"=>$horario_detalle
				],				
				[
					"campo_nombre"=>"horario_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>'A'
				]
			];   	    		

			$registrar_hora=$this->guardarDatos("asistencia_horario",$horario_datos_reg);

			if($registrar_hora->rowCount()>0){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Registro correcto",
					"texto"=>"El horario se registró correctamente",
					"icono"=>"success"
				];	

				$detalle=$this->ejecutarConsulta("SELECT horario_id FROM asistencia_horario WHERE horario_nombre='$horario_nombre'");
				if($detalle->rowCount()>0){
					$detalle=$detalle->fetch();
					$horario_id=$detalle['horario_id'];

					$dias = $_POST['dia'];
					$lugares = $_POST['lugar'];
					$horas = $_POST['hora'];
					$profesores = $_POST['profesor'];
			
					for ($i = 0; $i < count($dias); $i++) { 

						$horario_detalle_reg = [
							[
								"campo_nombre" => "detalle_horarioid",
								"campo_marcador" => ":Horarioid",
								"campo_valor" => $horario_id
							],
							[
								"campo_nombre" => "detalle_lugarid",
								"campo_marcador" => ":Lugarid",
								"campo_valor" => $lugares[$i]
							],
							[
								"campo_nombre" => "detalle_horaid",
								"campo_marcador" => ":Horaid",
								"campo_valor" => $horas[$i]
							],
							[
								"campo_nombre" => "detalle_profesorid",
								"campo_marcador" => ":Profesorid",
								"campo_valor" => $profesores[$i]
							],
							[
								"campo_nombre" => "detalle_dia",
								"campo_marcador" => ":Dia",
								"campo_valor" => $dias[$i]
							]
						];				
						$this->guardarDatos("asistencia_horario_detalle",$horario_detalle_reg);
					}	
				}
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No fue posible registrar el horario, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
		}

		public function actualizarHorario(){			
			# Almacenando datos 			
			$lugar_sedeid		= $this->limpiarCadena($_POST['lugar_sedeid']);
			$horario_nombre		= $this->limpiarCadena($_POST['horario_nombre']);
			$horario_detalle	= $this->limpiarCadena($_POST['horario_detalle']);
			$horario_id			= $this->limpiarCadena($_POST['horario_id']);	
			
			# Verificando campos obligatorios #
		    if($lugar_sedeid=="" || $horario_nombre=="" ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No ha completado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);       
		    }		

			$horario_datos_reg=[
				[
					"campo_nombre"=>"horario_sedeid",
					"campo_marcador"=>":Sedeid",
					"campo_valor"=>$lugar_sedeid
				],
				[
					"campo_nombre"=>"horario_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$horario_nombre
				],				
				[
					"campo_nombre"=>"horario_detalle",
					"campo_marcador"=>":Detalle",
					"campo_valor"=>$horario_detalle
				],				
				[
					"campo_nombre"=>"horario_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>'A'
				]
			];   

			$condicion=[
				"condicion_campo"=>"horario_id",
				"condicion_marcador"=>":Horarioid",
				"condicion_valor"=>$horario_id
			];
			if($this->actualizarDatos("asistencia_horario", $horario_datos_reg, $condicion)){				
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Horario actualizado",
					"texto"=>"El horario: ".$horario_id." se actualizó correctamente",
					"icono"=>"success"
				];

				$detalle=$this->ejecutarConsulta("SELECT detalle_horarioid FROM asistencia_horario_detalle WHERE detalle_horarioid='$horario_id'");
				if($detalle->rowCount()>0){
					$this->eliminarRegistro("asistencia_horario_detalle","detalle_horarioid",$horario_id);					
				}	

				$dias = $_POST['dia'];
				$lugares = $_POST['lugar'];
				$horas = $_POST['hora'];
				$profesores = $_POST['profesor'];
		
				for ($i = 0; $i < count($dias); $i++) {
					$horario_detalle_reg = [
						[
							"campo_nombre" => "detalle_horarioid",
							"campo_marcador" => ":Horarioid",
							"campo_valor" => $horario_id
						],
						[
							"campo_nombre" => "detalle_lugarid",
							"campo_marcador" => ":Lugarid",
							"campo_valor" => $lugares[$i]
						],
						[
							"campo_nombre" => "detalle_horaid",
							"campo_marcador" => ":Horaid",
							"campo_valor" => $horas[$i]
						],
						[
							"campo_nombre" => "detalle_profesorid",
							"campo_marcador" => ":Profesorid",
							"campo_valor" => $profesores[$i]
						],
						[
							"campo_nombre" => "detalle_dia",
							"campo_marcador" => ":Dia",
							"campo_valor" => $dias[$i]
						]
					];			
					$this->guardarDatos("asistencia_horario_detalle",$horario_detalle_reg);
				}

			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No fue posible actualizar los datos del horario: ".$horario_id.", por favor intente nuevamente",
					"icono"=>"success"
				];
			}
			return json_encode($alerta);
		}
		
	}
			