<?php

	namespace app\controllers;
	use app\models\mainModel;

	class jugadorController extends mainModel{
        
		public function listarSedebusqueda($sedeid){
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

        /*----------  Matriz de alumnos con opciones Ver, Actualizar, Eliminar  ----------*/
		public function listarAlumnos($equipo_id, $identificacion, $apellidopaterno, $primernombre, $anio, $sede){
			$estado = "";
			$texto = "";
			$boton = "";

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
			if($anio!=""){
				$consulta_datos .= " and YEAR(alumno_fechanacimiento) = '".$anio."'"; 
			}

			if($identificacion=="" && $primernombre=="" && $apellidopaterno==""){
				$consulta_datos="SELECT * FROM sujeto_alumno WHERE YEAR(alumno_fechanacimiento) = '".$anio."'";
			}
			
			if($identificacion=="" && $primernombre=="" && $apellidopaterno=="" && $anio == ""){
				$consulta_datos = "SELECT * FROM sujeto_alumno WHERE alumno_primernombre <> '' ";
			}

			if($sede!=""){
				if($sede == 0){
					$consulta_datos .= " and alumno_sedeid <> '".$sede."'"; 
				}else{
					$consulta_datos .= " and alumno_sedeid = '".$sede."'"; 
				}
			}else{
				$consulta_datos = "SELECT * FROM sujeto_alumno WHERE alumno_primernombre = ''";
			}			

			$consulta_datos .= " AND alumno_estado <> 'E'"; 
			
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
            $posicion = "<select class='form-control' id='posicion' name='posicion[]'>".$this->listarCatalogoPosicion()."</select>";
            $tipo = "<select class='form-control' id='tipo' name='tipo[]'>".$this->listarCatalogoTipo()."</select>";
			foreach($datos as $rows){
				$tabla.='
					<tr>
						<td><input type="hidden" name="alumno_id[]" value="'.$rows['alumno_id'].'">'.$rows['alumno_identificacion'].'</td>
						<td>'.$rows['alumno_primernombre'].' '.$rows['alumno_segundonombre'].' '.$rows['alumno_apellidopaterno'].' '.$rows['alumno_apellidomaterno'].'</td>
						<td>'.$rows['alumno_fechanacimiento'].'</td>
                        <td>'.$posicion.'</td>
                        <td>'.$tipo.'</td>
                        <td>
                            <input class="col-sm-1 form-check-input" type="checkbox" id="jugador_selected" name="jugador_selected[]">
                        </td>
					</tr>';	
			}
			return $tabla;			
		}
        			
		public function listarCatalogoPosicion(){
			$option="";

			$consulta_datos="SELECT C.catalogo_valor, C.catalogo_descripcion 
								FROM general_tabla_catalogo C
								INNER JOIN general_tabla T on T.tabla_id = C.catalogo_tablaid
								WHERE T.tabla_nombre = 'posicion_juego'";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';	
			}
			return $option;
		}

        public function listarCatalogoTipo(){
			$option="";

			$consulta_datos="SELECT C.catalogo_valor, C.catalogo_descripcion 
								FROM general_tabla_catalogo C
								INNER JOIN general_tabla T on T.tabla_id = C.catalogo_tablaid
								WHERE T.tabla_nombre = 'tipo_participacion'";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';	
			}
			return $option;
		}
        
		public function BuscarEquipo($equipo_id){		
			$consulta_datos=("SELECT equipo_id, equipo_nombre, equipo_torneoid, equipo_categoria, equipo_foto,
								CASE WHEN equipo_estado = 'A' THEN 'Activo' 
									 WHEN equipo_estado = 'I' THEN 'Inactivo' 
									 ELSE equipo_estado 
								END AS ESTADO 
							 FROM torneo_equipo
							 WHERE equipo_estado IN ('A','I')
							 	AND equipo_id =".$equipo_id);	

			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
		}

        public function registrarPosicion($equipo_id){		
            /*---------------Variables para el registro de los jugadores configurados----------------*/
            $alumno_repreid 			= $this->limpiarCadena($_POST['alumno_repreid']);
            $alumno_repreid 			= $this->limpiarCadena($_POST['alumno_repreid']);
            
			$consulta_datos=("SELECT equipo_id, equipo_nombre, equipo_torneoid, equipo_categoria, equipo_foto,
								CASE WHEN equipo_estado = 'A' THEN 'Activo' 
									 WHEN equipo_estado = 'I' THEN 'Inactivo' 
									 ELSE equipo_estado 
								END AS ESTADO 
							 FROM torneo_equipo
							 WHERE equipo_estado IN ('A','I')
							 	AND equipo_id =".$equipo_id);	

			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
		}
    }