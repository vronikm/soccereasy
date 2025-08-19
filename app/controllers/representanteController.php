<?php
	namespace app\controllers;
	use app\models\mainModel;

	class representanteController extends mainModel{

		/*----------  Matriz de representantes con opciones Ver, Actualizar, Eliminar  ----------*/
		public function listarRepresentantes($identificacion, $apellidopaterno, $primernombre){
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
			$consulta_datos="SELECT * FROM alumno_representante
								WHERE repre_estado in ('A','I')
									AND (repre_primernombre LIKE '".$primernombre."' 
										OR repre_identificacion LIKE '".$identificacion."' 
										OR repre_apellidopaterno LIKE '".$apellidopaterno."')";			
			
			if($identificacion=="" && $primernombre=="" && $apellidopaterno==""){
				$consulta_datos = "SELECT * FROM alumno_representante WHERE repre_primernombre <> '' AND repre_estado in ('A','I') ";
			}			
										
			$datos = $this->ejecutarConsulta($consulta_datos);
		
			if($datos->rowCount()>0){
				$datos = $datos->fetchAll();
			}

			foreach($datos as $rows){	
				if($rows['repre_firmado']=='S'){
					$estado = "N";
					$texto = "Firmado";
					$boton = "btn-secondary";
				}else{
					$estado = "S";
					$texto = "Pendiente";
					$boton = "btn-info";
				}
			
				$tabla.='				
					<tr>
						<td>'.$rows['repre_identificacion'].'</td>
						<td>'.$rows['repre_primernombre'].' '.$rows['repre_segundonombre'].'</td>
						<td>'.$rows['repre_apellidopaterno'].' '.$rows['repre_apellidomaterno'].'</td>
						<td>							
							<a href="'.APP_URL.'alumnoNew/'.$rows['repre_id'].'/" class="btn float-right btn-secondary btn-xs" style="margin-right: 5px;">Nuevo Alumno</a>	
							<a href="'.APP_URL.'representanteVinc/'.$rows['repre_id'].'/" class="btn float-right btn-warning btn-xs" style="margin-right: 5px;">Vincular alumno</a>
						</td>
						<td>
							<a href="'.APP_URL.'representanteFLPD/'.$rows['repre_id'].'/" target="_blank" class="nav-icon far fa-file float-right" title="Formulario LPD" style="margin-right: 5px;"></a>
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/representanteAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_repre" value="estadofirmado">
								<input type="hidden" name="repre_id" value="'.$rows['repre_id'].'">						
								<button type="submit" class="btn float-right '.$boton.' btn-xs" style="margin-right: 5px;""> '.$texto.' </button>
							</form>	
						</td>
						<td>
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/representanteAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_repre" value="eliminar">
								<input type="hidden" name="repre_id" value="'.$rows['repre_id'].'">						
								<button type="submit" class="btn float-right btn-danger btn-xs" style="margin-right: 5px;">Eliminar</button>
							</form>
													
							<a href="'.APP_URL.'representanteUpdate/'.$rows['repre_id'].'/" class="btn float-right btn-actualizar btn-xs" style="margin-right: 5px;">Actualizar</a>							
							<a href="'.APP_URL.'representanteProfile/'.$rows['repre_id'].'/" class="btn float-right btn-ver btn-xs" style="margin-right: 5px;">Ver</a>							
						</td>
					</tr>';	
			}
			return $tabla;			
		}
		
		public function registrarRepresentanteControlador(){	
			/*---------------Variables para el registro del tab Representante del alumno----------------*/
			$repre_tipoidentificacion 	= $this->limpiarCadena($_POST['repre_tipoidentificacion']);
			$repre_identificacion 	  	= $this->limpiarCadena($_POST['repre_identificacion']);
			$repre_primernombre		  	= $this->limpiarCadena($_POST['repre_primernombre']);
			$repre_segundonombre 	 	= $this->limpiarCadena($_POST['repre_segundonombre']);
			$repre_apellidopaterno 	  	= $this->limpiarCadena($_POST['repre_apellidopaterno']);
			$repre_apellidomaterno 	 	= $this->limpiarCadena($_POST['repre_apellidomaterno']);
			$repre_direccion 		  	= $this->limpiarCadena($_POST['repre_direccion']);
			$repre_correo 			  	= $this->limpiarCadena($_POST['repre_correo']);
			$repre_celular 			  	= $this->limpiarCadena($_POST['repre_celular']);
			$repre_parentesco 		  	= $this->limpiarCadena($_POST['repre_parentesco']);
			$repre_sexo 			  	= "";
			$repre_factura 			  	= "";

			if (isset($_POST['repre_sexo'])){$repre_sexo = $_POST['repre_sexo'];}	
			if (isset($_POST['repre_factura'])){$repre_factura = $_POST['repre_factura'];}	

			if($repre_identificacion=="" || $repre_primernombre=="" || $repre_apellidopaterno=="" || 
				$repre_direccion=="" || $repre_correo=="" || $repre_celular==""){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"No ha completado los campos obligatorios del representante del alumno",
						"icono"=>"error"
					];
					return json_encode($alerta);
			}

			$consultarepre=$this->seleccionarDatos("Unico","alumno_representante","repre_identificacion",$repre_identificacion);
			if($consultarepre->rowCount()>0){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Representante ya existe",
					"texto"=>"El representante ya se encuentra creado, por favor revisar.",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}

			/*---------------Registro del tab Representante del alumno----------------*/
			$representante_reg=[		
				[
					"campo_nombre"=>"repre_tipoidentificacion",
					"campo_marcador"=>":TipoIdentificacionRep",
					"campo_valor"=>$repre_tipoidentificacion
				],
				[
					"campo_nombre"=>"repre_identificacion",
					"campo_marcador"=>":IdnetificacionRep",
					"campo_valor"=>$repre_identificacion
				],
				[
					"campo_nombre"=>"repre_primernombre",
					"campo_marcador"=>":PrimerNombreRep",
					"campo_valor"=>$repre_primernombre
				],						
				[
					"campo_nombre"=>"repre_segundonombre",
					"campo_marcador"=>":SegundoNombreRep",
					"campo_valor"=>$repre_segundonombre
				],						
				[
					"campo_nombre"=>"repre_apellidopaterno",
					"campo_marcador"=>":ApellidoPatRep",
					"campo_valor"=>$repre_apellidopaterno
				],
				[
					"campo_nombre"=>"repre_apellidomaterno",
					"campo_marcador"=>":ApellidoMaternoRep",
					"campo_valor"=>$repre_apellidomaterno
				],
				[
					"campo_nombre"=>"repre_direccion",
					"campo_marcador"=>":DireccionRep",
					"campo_valor"=>$repre_direccion
				],						
				[
					"campo_nombre"=>"repre_correo",
					"campo_marcador"=>":CorreoRep",
					"campo_valor"=>$repre_correo
				],						
				[
					"campo_nombre"=>"repre_celular",
					"campo_marcador"=>":CelularRep",
					"campo_valor"=>$repre_celular
				],
				[
					"campo_nombre"=>"repre_sexo",
					"campo_marcador"=>":SexoRep",
					"campo_valor"=>$repre_sexo
				],
				[
					"campo_nombre"=>"repre_parentesco",
					"campo_marcador"=>":ParentescoRep",
					"campo_valor"=>$repre_parentesco
				],
				[
					"campo_nombre"=>"repre_factura",
					"campo_marcador"=>":RepFactura",
					"campo_valor"=>$repre_factura
				]
			];

			$registrar_alumno_representante=$this->guardarDatos("alumno_representante",$representante_reg);
			if($registrar_alumno_representante->rowCount()>0){
				$obtener_repreid=$this->ejecutarConsulta("SELECT repre_id FROM alumno_representante WHERE repre_identificacion='$repre_identificacion'");
				if($obtener_repreid->rowCount()==1){
					$repre=$obtener_repreid->fetchAll(); 					
					foreach( $repre as $rows ){
						$repreid = $rows['repre_id'];
					}
				}

				$alerta=[
					"tipo"=>"redireccionar",			
					"url"=>APP_URL.'alumnoNew/'.$repreid,					
					"titulo"=>"Representante registrado",
					"texto"=>"El representante ".$repre_identificacion." | ".$repre_primernombre." ".$repre_apellidopaterno." se registró correctamente",
					"icono"=>"success"	
				];

				/*---------------Obtengo campo repreid para la tabla alumno_representanteconyuge-------------*/
				$check_representanteid=$this->ejecutarConsulta("SELECT repre_id FROM alumno_representante WHERE repre_identificacion='$repre_identificacion'");
		
				if($check_representanteid->rowCount()==1){
					$representante=$check_representanteid->fetchAll(); 					
					foreach( $representante as $rows ){
						$representanteid = $rows['repre_id'];
					}				

					/*---------------Registro de la información del cónyuge del representante del alumno---------*/
					
					/*---------------Variables para el registro del cónyuge del representante del alumno----------------*/
					$conyuge_tipoidentificacion = $this->limpiarCadena($_POST['conyuge_tipoidentificacion']);
					$conyuge_identificacion 	= $this->limpiarCadena($_POST['conyuge_identificacion']);
					$conyuge_primernombre		= $this->limpiarCadena($_POST['conyuge_primernombre']);
					$conyuge_segundonombre 	 	= $this->limpiarCadena($_POST['conyuge_segundonombre']);
					$conyuge_apellidopaterno 	= $this->limpiarCadena($_POST['conyuge_apellidopaterno']);
					$conyuge_apellidomaterno 	= $this->limpiarCadena($_POST['conyuge_apellidomaterno']);
					$conyuge_direccion 		  	= $this->limpiarCadena($_POST['conyuge_direccion']);
					$conyuge_correo 			= $this->limpiarCadena($_POST['conyuge_correo']);
					$conyuge_celular 		  	= $this->limpiarCadena($_POST['conyuge_celular']);
					$conyuge_sexo 			  	= "";

					if (isset($_POST['conyuge_sexo'])){$conyuge_sexo = $_POST['conyuge_sexo'];}else{$conyuge_sexo = "";}
								
					if($conyuge_identificacion!="" || $conyuge_primernombre!="" || $conyuge_segundonombre!="" || $conyuge_apellidopaterno!=""||
						$conyuge_apellidomaterno!="" || $conyuge_direccion!="" || $conyuge_correo!="" || $conyuge_celular!=""){

						$conyuge_reg=[
							[
								"campo_nombre"=>"conyuge_repid",
								"campo_marcador"=>":Representanteid",
								"campo_valor"=>$representanteid
							],					
							[
								"campo_nombre"=>"conyuge_tipoidentificacion",
								"campo_marcador"=>":TipoIdentificacionCRep",
								"campo_valor"=>$conyuge_tipoidentificacion
							],
							[
								"campo_nombre"=>"conyuge_identificacion",
								"campo_marcador"=>":IdentificacionCRep",
								"campo_valor"=>$conyuge_identificacion
							],
							[
								"campo_nombre"=>"conyuge_primernombre",
								"campo_marcador"=>":PrimerNombreCRep",
								"campo_valor"=>$conyuge_primernombre
							],						
							[
								"campo_nombre"=>"conyuge_segundonombre",
								"campo_marcador"=>":SegundoNombreCRep",
								"campo_valor"=>$conyuge_segundonombre
							],						
							[
								"campo_nombre"=>"conyuge_apellidopaterno",
								"campo_marcador"=>":ApellidoPatCRep",
								"campo_valor"=>$conyuge_apellidopaterno
							],
							[
								"campo_nombre"=>"conyuge_apellidomaterno",
								"campo_marcador"=>":ApellidoMaternoCRep",
								"campo_valor"=>$conyuge_apellidomaterno
							],
							[
								"campo_nombre"=>"conyuge_direccion",
								"campo_marcador"=>":DireccionCRep",
								"campo_valor"=>$conyuge_direccion
							],						
							[
								"campo_nombre"=>"conyuge_correo",
								"campo_marcador"=>":CorreoCRep",
								"campo_valor"=>$conyuge_correo
							],						
							[
								"campo_nombre"=>"conyuge_celular",
								"campo_marcador"=>":CelularCRep",
								"campo_valor"=>$conyuge_celular
							],
							[
								"campo_nombre"=>"conyuge_sexo",
								"campo_marcador"=>":SexoCRep",
								"campo_valor"=>$conyuge_sexo
							]
						];

						$registrar_conyuge_rep=$this->guardarDatos("alumno_representanteconyuge",$conyuge_reg);
						if($registrar_conyuge_rep->rowCount()>0){
							$alerta=[
								"tipo"=>"redireccionar",			
								"url"=>APP_URL.'alumnoNew/'.$repreid,					
								"titulo"=>"Representante registrado",
								"texto"=>"El representante ".$repre_identificacion." | ".$repre_primernombre." ".$repre_apellidopaterno." se registró correctamente",
								"icono"=>"success"	
							]; 
						}else{
							$alerta=[
								"tipo"=>"simple",
								"titulo"=>"Ocurrió un error",
								"texto"=>"No fue posible registrar la información de cónyuge del representante del alumno, por favor intente nuevamente",
								"icono"=>"error"
							];
						}
					}				
				}/*---------------Fin de registro de la información del cónyuge del representante del alumno*/ 					
			}	
			return json_encode($alerta);
		}	
		public function listarCatalogoTipoDocumento(){
			$option="";

			$consulta_datos="SELECT C.catalogo_valor, C.catalogo_descripcion 
								FROM general_tabla_catalogo C
								INNER JOIN general_tabla T on T.tabla_id = C.catalogo_tablaid
								WHERE T.tabla_nombre = 'tipo_documento'";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';	
			}
			return $option;
		}
		
		public function listarCatalogoParentesco($repre_parentesco){
			$option ='<option value=0> Seleccione una opción</option>';


			$consulta_datos="SELECT C.catalogo_valor, C.catalogo_descripcion 
								FROM general_tabla_catalogo C
								INNER JOIN general_tabla T on T.tabla_id = C.catalogo_tablaid
								WHERE T.tabla_nombre = 'parentesco'";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($repre_parentesco == $rows['catalogo_valor']){
					$option.='<option value='.$rows['catalogo_valor'].' selected="selected">'.$rows['catalogo_descripcion'].'</option>';
				}else{
					$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';	
				}
			}
			return $option;
		}
		
		/*----------  Obtener el tipo de documento guardado  ----------*/
		public function listarOptionTipoIdentificacion($tipoidentificacion){
			$option="";

			$consulta_datos="SELECT C.catalogo_valor, C.catalogo_descripcion 
								FROM general_tabla_catalogo C
								INNER JOIN general_tabla T on T.tabla_id = C.catalogo_tablaid
								WHERE T.tabla_nombre = 'tipo_documento'";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($tipoidentificacion == $rows['catalogo_valor']){
					$option.='<option value='.$rows['catalogo_valor'].' selected="selected">'.$rows['catalogo_descripcion'].'</option>';	
				}else{
					$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';	
				}
			}
			return $option;
		}

		/*----------  Matriz de representados  ----------*/
		public function listarRepresentados($repreid){
			$tabla="";
			$consulta_datos="SELECT alumno_identificacion, alumno_primernombre, alumno_segundonombre, alumno_apellidopaterno, 
									alumno_apellidomaterno, alumno_fechanacimiento, alumno_fechaingreso, sede_nombre
								 FROM sujeto_alumno 
								 INNER JOIN general_sede on alumno_sedeid = sede_id
								 WHERE alumno_estado in ('A','I')
									AND (alumno_repreid = ".$repreid.") 
								ORDER BY alumno_fechaingreso";			
			
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$tabla.='
					<tr>
						<td>'.$rows['alumno_identificacion'].'</td>
						<td>'.$rows['alumno_primernombre'].' '.$rows['alumno_segundonombre'].'</td>
						<td>'.$rows['alumno_apellidopaterno'].' '.$rows['alumno_apellidomaterno'].'</td>
						<td>'.$rows['alumno_fechanacimiento'].'</td>
						<td>'.$rows['alumno_fechaingreso'].'</td>
						<td>'.$rows['sede_nombre'].'</td>
					</tr>';	
			}
			return $tabla;			
		}

		/*----------  Controlador actualizar representante  ----------*/
		public function actualizarRepresentanteControlador(){
	
			$repreid=$this->limpiarCadena($_POST['repre_id']);
			
			# Verificando existencia de representante #
			$representante=$this->ejecutarConsulta("SELECT * FROM alumno_representante WHERE repre_id='$repreid'");
			if($representante->rowCount()<=0){	
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"El representante no se encuentra en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);		    
		    }else{
		    	$representante=$representante->fetch();
		    }	

			/*---------------Variables para el registro del tab Representante del alumno----------------*/
			$repre_tipoidentificacion 	= $this->limpiarCadena($_POST['repre_tipoidentificacion']);
			$repre_identificacion 	  	= $this->limpiarCadena($_POST['repre_identificacion']);
			$repre_primernombre		  	= $this->limpiarCadena($_POST['repre_primernombre']);
			$repre_segundonombre 	 	= $this->limpiarCadena($_POST['repre_segundonombre']);
			$repre_apellidopaterno 	  	= $this->limpiarCadena($_POST['repre_apellidopaterno']);
			$repre_apellidomaterno 	 	= $this->limpiarCadena($_POST['repre_apellidomaterno']);
			$repre_direccion 		  	= $this->limpiarCadena($_POST['repre_direccion']);
			$repre_correo 			  	= $this->limpiarCadena($_POST['repre_correo']);
			$repre_celular 			  	= $this->limpiarCadena($_POST['repre_celular']);
			$repre_parentesco 		  	= $this->limpiarCadena($_POST['repre_parentesco']);
			$repre_sexo 			  	= "";
			$repre_factura			  	= "";

			# Verificando campos obligatorios #
			if($repre_identificacion=="" || $repre_primernombre=="" || $repre_apellidopaterno=="" || 
				$repre_direccion=="" || $repre_correo=="" || $repre_celular==""){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No ha completado los campos obligatorios del representante del alumno",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}		
						
			if (isset($_POST['repre_sexo'])) {
				$repre_sexo = $_POST['repre_sexo'];
			}else{
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No ha completado los campos obligatorios del representante",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}

			if (isset($_POST['repre_factura'])) {
				$repre_factura = $_POST['repre_factura'];
			}else{
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No ha completado el campo obligatorio requiere factura",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}
				$representante_reg=[										
					[
						"campo_nombre"=>"repre_tipoidentificacion",
						"campo_marcador"=>":TipoIdentificacionRep",
						"campo_valor"=>$repre_tipoidentificacion
					],
					[
						"campo_nombre"=>"repre_identificacion",
						"campo_marcador"=>":IdnetificacionRep",
						"campo_valor"=>$repre_identificacion
					],
					[
						"campo_nombre"=>"repre_primernombre",
						"campo_marcador"=>":PrimerNombreRep",
						"campo_valor"=>$repre_primernombre
					],						
					[
						"campo_nombre"=>"repre_segundonombre",
						"campo_marcador"=>":SegundoNombreRep",
						"campo_valor"=>$repre_segundonombre
					],						
					[
						"campo_nombre"=>"repre_apellidopaterno",
						"campo_marcador"=>":ApellidoPatRep",
						"campo_valor"=>$repre_apellidopaterno
					],
					[
						"campo_nombre"=>"repre_apellidomaterno",
						"campo_marcador"=>":ApellidoMaternoRep",
						"campo_valor"=>$repre_apellidomaterno
					],
					[
						"campo_nombre"=>"repre_direccion",
						"campo_marcador"=>":DireccionRep",
						"campo_valor"=>$repre_direccion
					],						
					[
						"campo_nombre"=>"repre_correo",
						"campo_marcador"=>":CorreoRep",
						"campo_valor"=>$repre_correo
					],						
					[
						"campo_nombre"=>"repre_celular",
						"campo_marcador"=>":CelularRep",
						"campo_valor"=>$repre_celular
					],
					[
						"campo_nombre"=>"repre_sexo",
						"campo_marcador"=>":SexoRep",
						"campo_valor"=>$repre_sexo
					],
					[
						"campo_nombre"=>"repre_parentesco",
						"campo_marcador"=>":ParentescoRep",
						"campo_valor"=>$repre_parentesco
					],
					[
						"campo_nombre"=>"repre_factura",
						"campo_marcador"=>":RepFactura",
						"campo_valor"=>$repre_factura
					]
				];
				$condicion=[
					"condicion_campo"=>"repre_id",
					"condicion_marcador"=>":Repreid",
					"condicion_valor"=>$repreid
				];				

				if($this->actualizarDatos("alumno_representante",$representante_reg,$condicion)){
					$alerta=[
						"tipo"=>"recargar",
						"titulo"=>"Representante actualizado",
						"texto"=>"El representante ".$repre_identificacion." | ".$repre_primernombre." ".$repre_apellidopaterno." se actualizó correctamente",
						"icono"=>"success"
					];
	
					/*---------------Registro de la información del cónyuge del representante del alumno---------*/
				
					/*---------------Variables para el registro del cónyuge del representante del alumno----------------*/
					$check_representanteid=$this->ejecutarConsulta("SELECT repre_id FROM alumno_representante WHERE repre_identificacion='$repre_identificacion'");
					if($check_representanteid->rowCount()>0){
						$representante=$check_representanteid->fetchAll(); 					
						foreach( $representante as $rows ){
							$representanteid = $rows['repre_id'];							
						}

						$conyuge_tipoidentificacion = $this->limpiarCadena($_POST['conyuge_tipoidentificacion']);
						$conyuge_identificacion 	= $this->limpiarCadena($_POST['conyuge_identificacion']);
						$conyuge_primernombre		= $this->limpiarCadena($_POST['conyuge_primernombre']);
						$conyuge_segundonombre 	 	= $this->limpiarCadena($_POST['conyuge_segundonombre']);
						$conyuge_apellidopaterno 	= $this->limpiarCadena($_POST['conyuge_apellidopaterno']);
						$conyuge_apellidomaterno 	= $this->limpiarCadena($_POST['conyuge_apellidomaterno']);
						$conyuge_direccion 		  	= $this->limpiarCadena($_POST['conyuge_direccion']);
						$conyuge_correo 			= $this->limpiarCadena($_POST['conyuge_correo']);
						$conyuge_celular 		  	= $this->limpiarCadena($_POST['conyuge_celular']);
						$conyuge_sexo 			  	= "";

						if (isset($_POST['conyuge_sexo'])){$conyuge_sexo = $_POST['conyuge_sexo'];}	

						$conyuge=$this->ejecutarConsulta("SELECT * FROM alumno_representanteconyuge WHERE conyuge_repid='$representanteid'");
						if($conyuge->rowCount()>0){				
							
							$conyuge_reg=[
								[
									"campo_nombre"=>"conyuge_repid",
									"campo_marcador"=>":Representanteid",
									"campo_valor"=>$representanteid
								],					
								[
									"campo_nombre"=>"conyuge_tipoidentificacion",
									"campo_marcador"=>":TipoIdentificacionCRep",
									"campo_valor"=>$conyuge_tipoidentificacion
								],
								[
									"campo_nombre"=>"conyuge_identificacion",
									"campo_marcador"=>":IdentificacionCRep",
									"campo_valor"=>$conyuge_identificacion
								],
								[
									"campo_nombre"=>"conyuge_primernombre",
									"campo_marcador"=>":PrimerNombreCRep",
									"campo_valor"=>$conyuge_primernombre
								],						
								[
									"campo_nombre"=>"conyuge_segundonombre",
									"campo_marcador"=>":SegundoNombreCRep",
									"campo_valor"=>$conyuge_segundonombre
								],						
								[
									"campo_nombre"=>"conyuge_apellidopaterno",
									"campo_marcador"=>":ApellidoPatCRep",
									"campo_valor"=>$conyuge_apellidopaterno
								],
								[
									"campo_nombre"=>"conyuge_apellidomaterno",
									"campo_marcador"=>":ApellidoMaternoCRep",
									"campo_valor"=>$conyuge_apellidomaterno
								],
								[
									"campo_nombre"=>"conyuge_direccion",
									"campo_marcador"=>":DireccionCRep",
									"campo_valor"=>$conyuge_direccion
								],						
								[
									"campo_nombre"=>"conyuge_correo",
									"campo_marcador"=>":CorreoCRep",
									"campo_valor"=>$conyuge_correo
								],						
								[
									"campo_nombre"=>"conyuge_celular",
									"campo_marcador"=>":CelularCRep",
									"campo_valor"=>$conyuge_celular
								],
								[
									"campo_nombre"=>"conyuge_sexo",
									"campo_marcador"=>":SexoCRep",
									"campo_valor"=>$conyuge_sexo
								]
							];

							$condicion=[
								"condicion_campo"=>"conyuge_repid",
								"condicion_marcador"=>":Representanteid",
								"condicion_valor"=>$representanteid
							];
							
							$this->actualizarDatos("alumno_representanteconyuge",$conyuge_reg,$condicion);

						}else{			

							if($conyuge_identificacion!="" || $conyuge_primernombre!="" || $conyuge_segundonombre!="" || $conyuge_apellidopaterno!=""||
							$conyuge_apellidomaterno!="" || $conyuge_direccion!="" || $conyuge_correo!="" || $conyuge_celular!=""){

								$conyuge_reg=[
									[
										"campo_nombre"=>"conyuge_repid",
										"campo_marcador"=>":Representanteid",
										"campo_valor"=>$representanteid
									],					
									[
										"campo_nombre"=>"conyuge_tipoidentificacion",
										"campo_marcador"=>":TipoIdentificacionCRep",
										"campo_valor"=>$conyuge_tipoidentificacion
									],
									[
										"campo_nombre"=>"conyuge_identificacion",
										"campo_marcador"=>":IdentificacionCRep",
										"campo_valor"=>$conyuge_identificacion
									],
									[
										"campo_nombre"=>"conyuge_primernombre",
										"campo_marcador"=>":PrimerNombreCRep",
										"campo_valor"=>$conyuge_primernombre
									],						
									[
										"campo_nombre"=>"conyuge_segundonombre",
										"campo_marcador"=>":SegundoNombreCRep",
										"campo_valor"=>$conyuge_segundonombre
									],						
									[
										"campo_nombre"=>"conyuge_apellidopaterno",
										"campo_marcador"=>":ApellidoPatCRep",
										"campo_valor"=>$conyuge_apellidopaterno
									],
									[
										"campo_nombre"=>"conyuge_apellidomaterno",
										"campo_marcador"=>":ApellidoMaternoCRep",
										"campo_valor"=>$conyuge_apellidomaterno
									],
									[
										"campo_nombre"=>"conyuge_direccion",
										"campo_marcador"=>":DireccionCRep",
										"campo_valor"=>$conyuge_direccion
									],						
									[
										"campo_nombre"=>"conyuge_correo",
										"campo_marcador"=>":CorreoCRep",
										"campo_valor"=>$conyuge_correo
									],						
									[
										"campo_nombre"=>"conyuge_celular",
										"campo_marcador"=>":CelularCRep",
										"campo_valor"=>$conyuge_celular
									],
									[
										"campo_nombre"=>"conyuge_sexo",
										"campo_marcador"=>":SexoCRep",
										"campo_valor"=>$conyuge_sexo
									]
								];

								$this->guardarDatos("alumno_representanteconyuge",$conyuge_reg);						
							}
						}				
					}
				}else{
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Representante no actualizado",
						"texto"=>"No fue posible actualizar los datos del representante ".$repre_identificacion." | ".$repre_primernombre." ".$repre_apellidopaterno.", por favor intente nuevamente",
						"icono"=>"success"
					];
				}
			return json_encode($alerta);
		}

		
		/*----------  Controlador eliminar representante  ----------*/
		public function eliminarRepresentanteControlador(){

			$repre_id=$this->limpiarCadena($_POST['repre_id']);

			# Verificando existencia de representante #
		    $datos=$this->ejecutarConsulta("SELECT * FROM alumno_representante WHERE repre_id='$repre_id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Representante no se encuentra en el sistema",
					"texto"=>"El representante no se encuentra en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }else{
		    	$datos=$datos->fetch();
				
				# Validar los representados
				$alumno_estado=$this->ejecutarConsulta("SELECT alumno_id, alumno_repreid, alumno_primernombre, alumno_apellidopaterno, repre_identificacion
															FROM alumno_representante, sujeto_alumno 
															WHERE alumno_repreid = repre_id
																AND alumno_estado in ('A','I')
																AND repre_id='$repre_id'");
				if($alumno_estado->rowCount()>0){
					$alerta=[
						"tipo"	=>"simple",
						"titulo"=>"Acción no permitida",
						"texto"	=>"El representante mantiene alumnos vigentes, por favor revisar",
						"icono"	=>"error"
					];
					return json_encode($alerta);
				}
		    }
			
			$estadoR = '';
			if($datos['repre_estado']=='A' || $datos['repre_estado']=='I'){
				$estadoR = 'E';
			}
			
            $repre_datos_up=[
				[
					"campo_nombre"=>"repre_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=> $estadoR
				]
			];
			$condicion=[
				"condicion_campo"=>"repre_id",
				"condicion_marcador"=>":Repreid",
				"condicion_valor"=>$repre_id
			];

			if($this->actualizarDatos("alumno_representante",$repre_datos_up,$condicion)){

				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Representante eliminado",
					"texto"=>"El representante ".$datos['repre_primernombre']." | ".$datos['repre_apellidopaterno']." fue eliminado correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No hemos podido eliminar el representante ".$datos['repre_primernombre']." ".$datos['repre_apellidopaterno'].", por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
		}

		/*----------  Búsqueda de alumno para asociar a un representante  ----------*/
		public function buscarRepresentado($identificacion, $apellidopaterno, $primernombre, $repreid){
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
								WHERE alumno_estado in ('A','I')
									AND (alumno_primernombre LIKE '".$primernombre."' 
									OR alumno_identificacion LIKE '".$identificacion."' 
									OR alumno_apellidopaterno LIKE '".$apellidopaterno."') ";			
			
			if($identificacion=="" && $primernombre=="" && $apellidopaterno==""){
				$consulta_datos = "SELECT * FROM sujeto_alumno WHERE alumno_primernombre <> '' AND alumno_estado in ('A','I')";
			}
			
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$tabla.='
					<tr>
						<td>'.$rows['alumno_identificacion'].'</td>
						<td>'.$rows['alumno_primernombre'].' '.$rows['alumno_segundonombre'].'</td>
						<td>'.$rows['alumno_apellidopaterno'].' '.$rows['alumno_apellidomaterno'].'</td>
						<td>
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/representanteAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_repre" value="vincularepresentado">
								<input type="hidden" name="alumno_id" value="'.$rows['alumno_id'].'">		
								<input type="hidden" name="alumno_repreid" value="'.$repreid.'">
								<button type="submit" href="'.APP_URL.'representanteList/" class="btn float-right btn-success btn-xs" style="margin-right: 5px;""> Vincular </button>							

								</form>
						</td>
					</tr>';	
			}
			return $tabla;			
		}

		# Vincular representante con alumno
		public function vincularRepresentado(){

			$alumnoid 		= $this->limpiarCadena($_POST['alumno_id']);
			$alumnorepreid 	= $this->limpiarCadena($_POST['alumno_repreid']);

			# Verificando pago #
			$datos = $this->ejecutarConsulta("SELECT * FROM sujeto_alumno WHERE alumno_id = '$alumnoid'");			
			if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el alumnoen el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }else{
		    	$datos=$datos->fetch();				
		    }
			$vinculado_reg=[
				[										
					"campo_nombre"=>"alumno_repreid",
					"campo_marcador"=>":RepreAlumno",
					"campo_valor"=>$alumnorepreid
				],

			];

			$condicion=[
				"condicion_campo"=>"alumno_id",
				"condicion_marcador"=>":Alumnoid",
				"condicion_valor"=>$alumnoid
			];			

			if($this->actualizarDatos("sujeto_alumno",$vinculado_reg,$condicion)){
				$alerta=[
					"tipo"=>"redireccionar",
					"titulo"=>"Representante vinculado",
					"texto"=>"El representante fue vinculado correctamente",
					"icono"=>"success"
				];
				
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Representante no vinculado",
					"texto"=>"No fue posible vincular el representante al alumno, por favor intente nuevamente",
					"icono"=>"success"
				];
			}
			return json_encode($alerta);
		}

		# Consultar datos del representante para la vista vincular alumno
		public function datosRepresentante($repreid){			
			$consulta_repre = "SELECT 
									repre_identificacion,
									CONCAT(repre_primernombre, ' ', repre_segundonombre, ' ', repre_apellidopaterno, ' ', repre_apellidomaterno) AS REPRESENTANTE,
									(SELECT alumno_sedeid 
										FROM sujeto_alumno 
										WHERE alumno_repreid = $repreid 
										ORDER BY alumno_fechaingreso DESC 
										LIMIT 1) AS SEDE
								FROM alumno_representante 
								WHERE repre_id = $repreid";				
			$datos = $this->ejecutarConsulta($consulta_repre);		
			return $datos;
		}

		public function informacionSede($sedeid){		
			$consulta_datos="SELECT * FROM general_sede WHERE sede_id  = $sedeid";
			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
		}

		public function actualizarEstadoFormulario(){
			$repre_id=$this->limpiarCadena($_POST['repre_id']);

			# Verificando usuario #
		    $datos=$this->ejecutarConsulta("SELECT * FROM alumno_representante WHERE repre_id='$repre_id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"El representante no se encuentra en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }else{
		    	$datos=$datos->fetch();
		    }
			if($datos['repre_firmado']=='N'){
				$estadoF = 'S';
			}else{
				$estadoF = 'N';
			}
            $firmado_datos_up=[
				[
					"campo_nombre"=>"repre_firmado",
					"campo_marcador"=>":Firmado",
					"campo_valor"=> $estadoF
				]
			];
			$condicion=[
				"condicion_campo"=>"repre_id",
				"condicion_marcador"=>":Repreid",
				"condicion_valor"=>$repre_id
			];

			if($this->actualizarDatos("alumno_representante",$firmado_datos_up,$condicion)){

				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Estado actualizado correctamente",
					"texto"=>"El estado del formulario fue actualizado correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar el estado del formulario por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
		}
	}