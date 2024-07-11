<?php

	namespace app\controllers;
	use app\models\mainModel;

	class alumnoController extends mainModel{

		/*----------  Controlador registrar alumno  ----------*/		
		public function registrarAlumnoControlador(){						
			/*---------------Variables para el registro del tab del alumno----------------*/
			$alumno_repreid 			= $this->limpiarCadena($_POST['alumno_repreid']);
			$alumno_identificacion 		= $this->limpiarCadena($_POST['alumno_identificacion']);
			$alumno_apellidopaterno 	= $this->limpiarCadena($_POST['alumno_apellido1']);
			$alumno_apellidomaterno 	= $this->limpiarCadena($_POST['alumno_apellido2']);
			$alumno_tipoidentificacion 	= $this->limpiarCadena($_POST['alumno_tipoidentificacion']);			
			$alumno_primernombre 		= $this->limpiarCadena($_POST['alumno_nombre1']);
			$alumno_segundonombre 		= $this->limpiarCadena($_POST['alumno_nombre2']);
			$alumno_nacionalidadid		= $this->limpiarCadena($_POST['alumno_nacionalidadid']);
			$alumno_fechanacimiento 	= $this->limpiarCadena($_POST['alumno_fechanacimiento']);
			$alumno_direccion 			= $this->limpiarCadena($_POST['alumno_direccion']);	
			$alumno_fechaingreso		= $this->limpiarCadena($_POST['alumno_fechaingreso']);
			$alumno_sedeid 				= $this->limpiarCadena($_POST['alumno_sedeid']);
			$alumno_nombrecorto 		= ""; //$this->limpiarCadena($_POST['alumno_nombrecorto']);
			$alumno_posicionid			= ""; //$this->limpiarCadena($_POST['alumno_posicionid']);					
			$alumno_numcamiseta 		= $_POST['alumno_numcamiseta'];
			$alumno_estado 				= "A";
			$alumno_genero 				= "";
			$alumno_hermanos 			= "";

			if ($alumno_numcamiseta == "" ){$alumno_numcamiseta = 0;}

			if (isset($_POST['alumno_genero']) && isset($_POST['alumno_hermanos'])) {
				$alumno_genero 				= $_POST['alumno_genero'];
				$alumno_hermanos 			= $_POST['alumno_hermanos'];

			}else{
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No ha completado los campos obligatorios del alumno",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}			
			
		    # Verificando campos obligatorios #
		    if($alumno_identificacion=="" || $alumno_primernombre=="" || $alumno_apellidopaterno=="" || $alumno_fechanacimiento==""){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No ha completado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }

		    # Verificando integridad de los datos #
		    if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ]{3,40}",$alumno_primernombre)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"El nombre ingresado no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }	    

            # Verificando identificacion #
		    $check_alumno=$this->ejecutarConsulta("SELECT alumno_identificacion FROM sujeto_alumno WHERE alumno_identificacion='$alumno_identificacion'");
		    if($check_alumno->rowCount()>0){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"La identificación ingresada ya se encuentra registrada, por favor verificar",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }

		    # Directorio de imagenes #
    		$img_dir="../views/imagenes/fotos/alumno/";
			$codigo=rand(0,100);

    		# Comprobar si se selecciono una imagen #
    		if($_FILES['alumno_foto']['name']!="" && $_FILES['alumno_foto']['size']>0){

    			# Creando directorio #
		        if(!file_exists($img_dir)){
		            if(!mkdir($img_dir,0777)){
		            	$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error",
							"texto"=>"No fue posible crear el directorio",
							"icono"=>"error"
						];
						return json_encode($alerta);
		            } 
		        }

		        # Verificando formato de imagenes #
		        if(mime_content_type($_FILES['alumno_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['alumno_foto']['tmp_name'])!="image/png"){
		        	$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"La imagen que ha seleccionado es de un formato no permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
		        }

		        # Verificando peso de imagen #
		        if(($_FILES['alumno_foto']['size']/1024)>4000){
		        	$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"La imagen que ha seleccionado supera el peso permitido 4MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
		        }

		        # Nombre de la foto #
		        $foto=str_ireplace(" ","_",$alumno_identificacion);
		        $foto=$foto."_".$codigo;

		        # Extension de la imagen #
		        switch(mime_content_type($_FILES['alumno_foto']['tmp_name'])){
		            case 'image/jpeg':
		                $foto=$foto.".jpg";
		            break;
		            case 'image/png':
		                $foto=$foto.".png";
		            break;
		        }
				$maxWidth = 800;
    			$maxHeight = 600;

				chmod($img_dir,0777);
				$inputFile = ($_FILES['alumno_foto']['tmp_name']);
       			$outputFile = $img_dir.$foto;

				# Moviendo imagen al directorio #
				//if(!move_uploaded_file($_FILES['alumno_foto']['tmp_name'],$img_dir.$foto)){
				if ($this->resizeImageGD($inputFile, $maxWidth, $maxHeight, $outputFile)) {
					
				}else{
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"No es posible subir la imagen al sistema en este momento",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

    		}else{
    			$foto="";
    		}

			/*---------------Registro del tab Cedula del alumno----------------*/

			# Directorio de imagenes #
			$img_cedula="../views/imagenes/cedulas/";

			# Comprobar si seleccionó el Anverso de la cédula #
			if($_FILES['alumno_cedulaA']['name']!="" && $_FILES['alumno_cedulaA']['size']>0){

				# Creando directorio #
				if(!file_exists($img_cedula)){
					if(!mkdir($img_cedula,0777)){
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error",
							"texto"=>"No fue posible crear el directorio para almacenar las imágenes de la cédula",
							"icono"=>"error"
						];
						return json_encode($alerta);
					} 
				}

				# Verificando formato de imagenes #
				if(mime_content_type($_FILES['alumno_cedulaA']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['alumno_cedulaA']['tmp_name'])!="image/png"){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"La imagen que ha seleccionado es de un formato no permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Verificando peso de imagen #
				if(($_FILES['alumno_cedulaA']['size']/1024)>4000){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"La imagen que ha seleccionado supera el peso permitido 4MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Nombre de la foto #
				$cedulaA=str_ireplace(" ","_",$alumno_identificacion);
				$cedulaA=$cedulaA."_A".$codigo;

				# Extension de la imagen #
				switch(mime_content_type($_FILES['alumno_cedulaA']['tmp_name'])){
					case 'image/jpeg':
						$cedulaA=$cedulaA.".jpg";
					break;
					case 'image/png':
						$cedulaA=$cedulaA.".png";
					break;
				}
				$maxWidth = 800;
				$maxHeight = 600;

				chmod($img_cedula,0777);
				$inputFile = ($_FILES['alumno_cedulaA']['tmp_name']);
				$outputFile = $img_cedula.$cedulaA;

				# Moviendo imagen al directorio #
				//if(!move_uploaded_file($_FILES['alumno_foto']['tmp_name'],$img_dir.$foto)){
				if ($this->resizeImageGD($inputFile, $maxWidth, $maxHeight, $outputFile)) {
					
				}else{
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"No es posible subir la imagen al sistema en este momento",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

			}else{
				$cedulaA="";
			}

			# Comprobar si seleccionó el reverso de la cédula #
			if($_FILES['alumno_cedulaR']['name']!="" && $_FILES['alumno_cedulaR']['size']>0){

				# Creando directorio #
				if(!file_exists($img_cedula)){
					if(!mkdir($img_cedula,0777)){
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error",
							"texto"=>"No fue posible crear el directorio para almacenar las imágenes de la cédula",
							"icono"=>"error"
						];
						return json_encode($alerta);
					} 
				}

				# Verificando formato de imagenes #
				if(mime_content_type($_FILES['alumno_cedulaR']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['alumno_cedulaR']['tmp_name'])!="image/png"){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"La imagen que ha seleccionado es de un formato no permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Verificando peso de imagen #
				if(($_FILES['alumno_cedulaR']['size']/1024)>4000){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"La imagen que ha seleccionado supera el peso permitido 4MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Nombre de la foto #
				$cedulaR=str_ireplace(" ","_",$alumno_identificacion);
				$cedulaR=$cedulaR."_R".$codigo;

				# Extension de la imagen #
				switch(mime_content_type($_FILES['alumno_cedulaR']['tmp_name'])){
					case 'image/jpeg':
						$cedulaR=$cedulaR.".jpg";
					break;
					case 'image/png':
						$cedulaR=$cedulaR.".png";
					break;
				}
				$maxWidth = 800;
				$maxHeight = 600;

				chmod($img_cedula,0777);
				$inputFile = ($_FILES['alumno_cedulaR']['tmp_name']);
				$outputFile = $img_cedula.$cedulaR;

				# Moviendo imagen al directorio #
				//if(!move_uploaded_file($_FILES['alumno_foto']['tmp_name'],$img_dir.$foto)){
				if ($this->resizeImageGD($inputFile, $maxWidth, $maxHeight, $outputFile)) {
					
				}else{
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"No es posible subir la imagen al sistema en este momento",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

			}else{
				$cedulaR="";
			}
				
		    $alumno_datos_reg=[
				[
					"campo_nombre"=>"alumno_repreid",
					"campo_marcador"=>":Repreid",
					"campo_valor"=>$alumno_repreid
				],
				[
					"campo_nombre"=>"alumno_sedeid",
					"campo_marcador"=>":Sedeid",
					"campo_valor"=>$alumno_sedeid
				],
				[
					"campo_nombre"=>"alumno_posicionid",
					"campo_marcador"=>":Posicionid",
					"campo_valor"=>$alumno_posicionid
				],
				[
					"campo_nombre"=>"alumno_nacionalidadid",
					"campo_marcador"=>":Nacionalidadid",
					"campo_valor"=>$alumno_nacionalidadid
				],
				[
					"campo_nombre"=>"alumno_tipoidentificacion",
					"campo_marcador"=>":Tipoidentificacion",
					"campo_valor"=>$alumno_tipoidentificacion
				],
				[
					"campo_nombre"=>"alumno_identificacion",
					"campo_marcador"=>":Identificacion",
					"campo_valor"=>$alumno_identificacion
				],				
				[
					"campo_nombre"=>"alumno_primernombre",
					"campo_marcador"=>":Primernombre",
					"campo_valor"=>$alumno_primernombre
				],
				[
					"campo_nombre"=>"alumno_segundonombre",
					"campo_marcador"=>":Segundonombre",
					"campo_valor"=>$alumno_segundonombre
				],				
				[
					"campo_nombre"=>"alumno_apellidopaterno",
					"campo_marcador"=>":Apellidopaterno",
					"campo_valor"=>$alumno_apellidopaterno
				],
				[
					"campo_nombre"=>"alumno_apellidomaterno",
					"campo_marcador"=>":Apellidomaterno",
					"campo_valor"=>$alumno_apellidomaterno
				],
				[
					"campo_nombre"=>"alumno_nombrecorto",
					"campo_marcador"=>":Nombrecorto",
					"campo_valor"=>$alumno_nombrecorto
				],
				[
					"campo_nombre"=>"alumno_direccion",
					"campo_marcador"=>":Direccion",
					"campo_valor"=>$alumno_direccion
				],
				[
					"campo_nombre"=>"alumno_fechanacimiento",
					"campo_marcador"=>":Fechanacimiento",
					"campo_valor"=>$alumno_fechanacimiento
				],
				[
					"campo_nombre"=>"alumno_fechaingreso",
					"campo_marcador"=>":Fechaingreso",
					"campo_valor"=>$alumno_fechaingreso
				],
				[
					"campo_nombre"=>"alumno_genero",
					"campo_marcador"=>":Genero",
					"campo_valor"=>$alumno_genero
				],
				[
					"campo_nombre"=>"alumno_hermanos",
					"campo_marcador"=>":Hermanos",
					"campo_valor"=>$alumno_hermanos
				],
				[
					"campo_nombre"=>"alumno_estado",
					"campo_marcador"=>":Activo",
					"campo_valor"=>$alumno_estado
				],
				[
					"campo_nombre"=>"alumno_imagen",
					"campo_marcador"=>":Foto",
					"campo_valor"=>$foto
				],
				[
					"campo_nombre"=>"alumno_numcamiseta",
					"campo_marcador"=>":Camiseta",
					"campo_valor"=>$alumno_numcamiseta
				],
				[
					"campo_nombre"=>"alumno_cedulaA",
					"campo_marcador"=>":CedulaA",
					"campo_valor"=>$cedulaA
				],
				[
					"campo_nombre"=>"alumno_cedulaR",
					"campo_marcador"=>":CedulaR",
					"campo_valor"=>$cedulaR
				]
			];

			$registrar_alumno=$this->guardarDatos("sujeto_alumno",$alumno_datos_reg);

			/*---------------Inicio de registro de Información de los tabs*/
			if($registrar_alumno->rowCount()==1){
				$alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Alumno registrado",
					"texto"=>"El alumno ".$alumno_identificacion." | ".$alumno_primernombre." ".$alumno_apellidopaterno." se registró correctamente",
					"icono"=>"success"
				];

				$infomedic_tiposangre 	= $this->limpiarCadena($_POST['infomedic_tiposangre']);
				$infomedic_peso		  	= $this->limpiarCadena($_POST['infomedic_peso']);
				$infomedic_talla 	  	= $this->limpiarCadena($_POST['infomedic_talla']);
				$infomedic_enfermedad 	= $this->limpiarCadena($_POST['infomedic_enfermedad']);
				$infomedic_medicamentos = $this->limpiarCadena($_POST['infomedic_medicamentos']);
				$infomedic_alergia1 	= $this->limpiarCadena($_POST['infomedic_alergia1']);
				$infomedic_alergia2 	= $this->limpiarCadena($_POST['infomedic_alergia2']);
				$infomedic_cirugias 	= $this->limpiarCadena($_POST['infomedic_cirugias']);
				$infomedic_observacion	= $this->limpiarCadena($_POST['infomedic_observacion']);

				if ($infomedic_peso ==""){$infomedic_peso = 0;}
				if ($infomedic_talla ==""){$infomedic_talla = 0;}

				if(isset($_POST['infomedic_covid'])){ $infomedic_covid  = $_POST['infomedic_covid']; }else {$infomedic_covid="";}
				if(isset($_POST['infomedic_vacunas'])){ $infomedic_vacunas  = $_POST['infomedic_vacunas']; }else {$infomedic_vacunas="";}

				/*---------------Obtengo campo alumnoid para todas las tablas*/
				$check_alumno=$this->ejecutarConsulta("SELECT alumno_id FROM sujeto_alumno WHERE alumno_identificacion='$alumno_identificacion'");
		
				if($check_alumno->rowCount()==1){
					$alumno=$check_alumno->fetchAll(); 					
					foreach( $alumno as $rows ){
						$alumnoid = $rows['alumno_id'];
					}
				}

				/*---------------Registro del tab Información Médica del alumno*/
				if($infomedic_tiposangre!="" || $infomedic_peso>0 || $infomedic_talla>0 || $infomedic_enfermedad!=""||
					$infomedic_medicamentos!="" || $infomedic_alergia1!="" || $infomedic_alergia2!="" || $infomedic_cirugias!="" ||
					$infomedic_observacion!=""){

					$infomedic_reg=[
						[
							"campo_nombre"=>"infomedic_alumnoid",
							"campo_marcador"=>":Alumnoid",
							"campo_valor"=>$alumnoid
						],
						[
							"campo_nombre"=>"infomedic_fecha",
							"campo_marcador"=>":Fechacreacion",
							"campo_valor"=>date("Y-m-d H:i:s")
						],
						[
							"campo_nombre"=>"infomedic_tiposangre",
							"campo_marcador"=>":Tiposangre",
							"campo_valor"=>$infomedic_tiposangre
						],
						[
							"campo_nombre"=>"infomedic_peso",
							"campo_marcador"=>":Peso",
							"campo_valor"=>$infomedic_peso
						],
						[
							"campo_nombre"=>"infomedic_talla",
							"campo_marcador"=>":Talla",
							"campo_valor"=>$infomedic_talla
						],
						[
							"campo_nombre"=>"infomedic_enfermedad",
							"campo_marcador"=>":Enfermedad",
							"campo_valor"=>$infomedic_enfermedad
						],
						[
							"campo_nombre"=>"infomedic_medicamentos",
							"campo_marcador"=>":Medicamentos",
							"campo_valor"=>$infomedic_medicamentos
						],
						[
							"campo_nombre"=>"infomedic_alergia1",
							"campo_marcador"=>":AlergiaMedicamentos",
							"campo_valor"=>$infomedic_alergia1
						],
						[
							"campo_nombre"=>"infomedic_alergia2",
							"campo_marcador"=>":AlergiaObjetos",
							"campo_valor"=>$infomedic_alergia2
						],
						[
							"campo_nombre"=>"infomedic_cirugias",
							"campo_marcador"=>":Cirugias",
							"campo_valor"=>$infomedic_cirugias
						],
						[
							"campo_nombre"=>"infomedic_observacion",
							"campo_marcador"=>":Observacion",
							"campo_valor"=>$infomedic_observacion
						],
						[
							"campo_nombre"=>"infomedic_covid",
							"campo_marcador"=>":VacunasCovid",
							"campo_valor"=>$infomedic_covid
						],
						[
							"campo_nombre"=>"infomedic_vacunas",
							"campo_marcador"=>":Vacunas",
							"campo_valor"=>$infomedic_vacunas
						]
					];

					$this->guardarDatos("alumno_infomedic",$infomedic_reg);
				}

			 	/*---------------Fin de registro del tab Información Médica del alumno*/

			 	/*---------------Registro del tab Contacto Emergencia del alumno------------*/
				$cemer_nombre 		= $this->limpiarCadena($_POST['cemer_nombre']);
				$cemer_celular 		= $this->limpiarCadena($_POST['cemer_celular']);
				$cemer_parentesco	= $this->limpiarCadena($_POST['cemer_parentesco']);

				if($cemer_nombre!="" || $cemer_celular!=""){					
					$cemergencia_reg=[
						[
							"campo_nombre"=>"cemer_alumnoid",
							"campo_marcador"=>":Alumnoid",
							"campo_valor"=>$alumnoid
						],						
						[
							"campo_nombre"=>"cemer_nombre",
							"campo_marcador"=>":NombreContactoEmer",
							"campo_valor"=>$cemer_nombre
						],
						[
							"campo_nombre"=>"cemer_celular",
							"campo_marcador"=>":CelularContactoEmer",
							"campo_valor"=>$cemer_celular
						],
						[
							"campo_nombre"=>"cemer_parentesco",
							"campo_marcador"=>":ParentescoContactoEmer",
							"campo_valor"=>$cemer_parentesco
						]
					];

					$this->guardarDatos("alumno_cemergencia",$cemergencia_reg);
				}
			 	/*---------------Fin de registro del tab Contacto Emergencia del alumno------*/
			}else{				
				if(is_file($img_dir.$foto)){
					chmod($img_dir.$foto,0777);
					unlink($img_dir.$foto);
				}

				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No se pudo registrar la información del alumno, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
		}

		/*----------  Matriz de alumnos con opciones Ver, Actualizar, Eliminar  ----------*/
		public function listarAlumnos($identificacion, $apellidopaterno, $primernombre, $ano, $sede){
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
				$consulta_datos = "SELECT * FROM sujeto_alumno WHERE alumno_primernombre = ''";
			}			

			$consulta_datos .= " AND alumno_estado <> 'E'"; 
			
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($rows['alumno_estado']=='A'){
					$estado = "Activo";
					$texto = "Inactivar";
					$boton = "btn-secondary";
				}else{
					$estado = "Inactivo";
					$texto = "Activar";
					$boton = "btn-info";
				}

				$tabla.='
					<tr>
						<td>'.$rows['alumno_identificacion'].'</td>
						<td>'.$rows['alumno_primernombre'].' '.$rows['alumno_segundonombre'].'</td>
						<td>'.$rows['alumno_apellidopaterno'].' '.$rows['alumno_apellidomaterno'].'</td>
						<td>'.$rows['alumno_fechanacimiento'].'</td>
						<td>
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/alumnoAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_alumno" value="eliminar">
								<input type="hidden" name="alumno_id" value="'.$rows['alumno_id'].'">						
								<button type="submit" class="btn float-right btn-danger btn-xs" style="margin-right: 5px;">Eliminar</button>
							</form>
							
							<a href="'.APP_URL.'alumnoUpdate/'.$rows['alumno_id'].'/" target="_blank" class="btn float-right btn-actualizar btn-xs" style="margin-right: 5px;">Actualizar</a>							
							<a href="'.APP_URL.'alumnoProfile/'.$rows['alumno_id'].'/" target="_blank" class="btn float-right btn-ver btn-xs" style="margin-right: 5px;">Ver</a>
							
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/alumnoAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_alumno" value="actualizarestado">
								<input type="hidden" name="alumno_id" value="'.$rows['alumno_id'].'">						
								<button type="submit" class="btn float-right '.$boton.' btn-xs" style="margin-right: 5px;""> '.$texto.' </button>
							</form>
						</td>
					</tr>';	
			}
			return $tabla;			
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

		/*----------  Obtener la nacionalidad guardada  ----------*/
		public function listarOptionNacionalidad($alumno_nacionalidadid){
			$option="";

			$consulta_datos="SELECT C.catalogo_valor, C.catalogo_descripcion 
								FROM general_tabla_catalogo C
								INNER JOIN general_tabla T on T.tabla_id = C.catalogo_tablaid
								WHERE T.tabla_nombre = 'nacionalidad'";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($alumno_nacionalidadid == $rows['catalogo_valor']){
					$option.='<option value='.$rows['catalogo_valor'].' selected="selected">'.$rows['catalogo_descripcion'].'</option>';	
				}else{
					$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';	
				}
			}
			return $option;
		}
				
		/*----------  Obtener la sede guardada  ----------*/
		public function listarSedeAlumno($alumno_sedeid){
			$option="";

			$consulta_datos="SELECT sede_id, sede_nombre FROM general_sede";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($alumno_sedeid == $rows['sede_id']){
					$option.='<option value='.$rows['sede_id'].' selected="selected">'.$rows['sede_nombre'].'</option>';	
				}else{
					$option.='<option value='.$rows['sede_id'].'>'.$rows['sede_nombre'].'</option>';	
				}
			}
			return $option;
		}

		/*----------  Obtener la posición de juego guardada  ----------*/
		public function listarOptionPosicionJuego($alumno_posicionid){
			$option="";

			$consulta_datos="SELECT C.catalogo_valor, C.catalogo_descripcion 
								FROM general_tabla_catalogo C
								INNER JOIN general_tabla T on T.tabla_id = C.catalogo_tablaid
								WHERE T.tabla_nombre = 'posicion_juego'";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($alumno_posicionid == $rows['catalogo_valor']){
					$option.='<option value='.$rows['catalogo_valor'].' selected="selected">'.$rows['catalogo_descripcion'].'</option>';	
				}else{
					$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';	
				}
			}
			return $option;
		}

		public function listarOptionParentesco($cemer_parentesco){
			$option="";

			$consulta_datos="SELECT C.catalogo_valor, C.catalogo_descripcion 
								FROM general_tabla_catalogo C
								INNER JOIN general_tabla T on T.tabla_id = C.catalogo_tablaid
								WHERE T.tabla_nombre = 'parentesco'";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($cemer_parentesco == $rows['catalogo_valor']){
					$option.='<option value='.$rows['catalogo_valor'].' selected="selected">'.$rows['catalogo_descripcion'].'</option>';	
				}else{
					$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';	
				}
			}
			return $option;
		}

		/* Listar todos los alumnos*/
		public function listarAlumnos_Borrar(){
			$tabla="";
			$fechaM = "";
			$consulta_datos="SELECT * FROM sujeto_alumno";	
					
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
							<a href="invoice-print.html" rel="noopener" target="_blank" class="btn float-right btn-danger btn-xs">Eliminar</a>
							<a href="'.APP_URL.'alumnoUpdate/'.$rows['alumno_id'].'/" class="btn float-right btn-success btn-xs" style="margin-right: 5px;">Actualizar</a>
							
							<a href="invoice-print.html" rel="noopener" target="_blank" class="btn float-right btn-secondary btn-xs" style="margin-right: 5px;">Ver</a>
						</td>
					</tr>';	
			}
			return $tabla;
		}

		/*----------  Controlador eliminar alumno  ----------*/
		public function actualizarEstadoAlumnoControlador(){

			$alumno_id=$this->limpiarCadena($_POST['alumno_id']);

			# Verificando usuario #
		    $datos=$this->ejecutarConsulta("SELECT * FROM sujeto_alumno WHERE alumno_id='$alumno_id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"El alumno no se encuentra en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }else{
		    	$datos=$datos->fetch();
		    }
			if($datos['alumno_estado']=='A'){
				$estadoA = 'I';
			}else{
				$estadoA = 'A';
			}
            $alumno_datos_up=[
				[
					"campo_nombre"=>"alumno_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=> $estadoA
				]
			];
			$condicion=[
				"condicion_campo"=>"alumno_id",
				"condicion_marcador"=>":Alumnoid",
				"condicion_valor"=>$alumno_id
			];

			if($this->actualizarDatos("sujeto_alumno",$alumno_datos_up,$condicion)){

				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Estado actualizado correctamente",
					"texto"=>"El estado del alumno ".$datos['alumno_primernombre']." | ".$datos['alumno_apellidopaterno']." fue actualizado correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar el estado del alumno ".$datos['alumno_primernombre']." ".$datos['alumno_apellidopaterno'].", por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
		}

		/*----------  Controlador eliminar alumno  ----------*/
		public function eliminarAlumnoControlador(){

			$alumno_id=$this->limpiarCadena($_POST['alumno_id']);

			# Verificando usuario #
		    $datos=$this->ejecutarConsulta("SELECT * FROM sujeto_alumno WHERE alumno_id='$alumno_id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"El alumno no se encuentra en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }else{
		    	$datos=$datos->fetch();
		    }
			if($datos['alumno_estado']=='A' || $datos['alumno_estado']=='I'){
				$estadoA = 'E';
			}else{
				$estadoA = 'X';
			}
            $alumno_datos_up=[
				[
					"campo_nombre"=>"alumno_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=> $estadoA
				]
			];
			$condicion=[
				"condicion_campo"=>"alumno_id",
				"condicion_marcador"=>":Alumnoid",
				"condicion_valor"=>$alumno_id
			];

			if($this->actualizarDatos("sujeto_alumno",$alumno_datos_up,$condicion)){

				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"El alumno fue eliminado correctamente",
					"texto"=>"El alumno ".$datos['alumno_primernombre']." | ".$datos['alumno_apellidopaterno']." fue eliminado correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido eliminar el alumno ".$datos['alumno_primernombre']." ".$datos['alumno_apellidopaterno'].", por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
		}

		

		/*----------  Controlador actualizar alumno  ----------*/
		public function actualizarAlumnoControlador(){
			
			$alumnoid=$this->limpiarCadena($_POST['alumno_id']);

			# Verificando usuario #
		    $datos=$this->ejecutarConsulta("SELECT * FROM sujeto_alumno WHERE alumno_id ='$alumnoid'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"El alumno no se encuentra en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);		    
		    }else{
		    	$datos=$datos->fetch();
		    }

			/*---------------Variables para el registro del tab del alumno----------------*/
			$alumno_identificacion 		= $this->limpiarCadena($_POST['alumno_identificacion']);
			$alumno_apellidopaterno 	= $this->limpiarCadena($_POST['alumno_apellido1']);
			$alumno_apellidomaterno 	= $this->limpiarCadena($_POST['alumno_apellido2']);
			$alumno_tipoidentificacion 	= $this->limpiarCadena($_POST['alumno_tipoidentificacion']);			
			$alumno_primernombre 		= $this->limpiarCadena($_POST['alumno_nombre1']);
			$alumno_segundonombre 		= $this->limpiarCadena($_POST['alumno_nombre2']);
			$alumno_nacionalidadid		= $this->limpiarCadena($_POST['alumno_nacionalidadid']);
			$alumno_fechanacimiento 	= $this->limpiarCadena($_POST['alumno_fechanacimiento']);
			$alumno_direccion 			= $this->limpiarCadena($_POST['alumno_direccion']);	
			$alumno_fechaingreso		= $this->limpiarCadena($_POST['alumno_fechaingreso']);
			$alumno_sedeid 				= $this->limpiarCadena($_POST['alumno_sedeid']);
			$alumno_nombrecorto 		= ""; //$this->limpiarCadena($_POST['alumno_nombrecorto']);
			$alumno_posicionid			= ""; //$this->limpiarCadena($_POST['alumno_posicionid']);					
			$alumno_numcamiseta 		= $_POST['alumno_numcamiseta'];
			$alumno_estado 				= "A";
			$alumno_genero 				= "";
			$alumno_hermanos 			= "";

			if ($alumno_numcamiseta == ""){$alumno_numcamiseta = 0;}

			if (isset($_POST['alumno_genero']) && isset($_POST['alumno_hermanos'])) {
				$alumno_genero 				= $_POST['alumno_genero'];
				$alumno_hermanos 			= $_POST['alumno_hermanos'];

			}else{
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No ha completado los campos obligatorios del alumno",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}			
			
		    # Verificando campos obligatorios #
		    if($alumno_identificacion=="" || $alumno_primernombre=="" || $alumno_apellidopaterno=="" || $alumno_fechanacimiento==""){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No ha completado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }

		    # Verificando integridad de los datos #
		    if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ]{3,40}",$alumno_primernombre)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"El campo nombre no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }
			
			$alumno_datos_reg=[
				[
					"campo_nombre"=>"alumno_sedeid",
					"campo_marcador"=>":Sedeid",
					"campo_valor"=>$alumno_sedeid
				],
				[
					"campo_nombre"=>"alumno_posicionid",
					"campo_marcador"=>":Posicionid",
					"campo_valor"=>$alumno_posicionid
				],
				[
					"campo_nombre"=>"alumno_nacionalidadid",
					"campo_marcador"=>":Nacionalidadid",
					"campo_valor"=>$alumno_nacionalidadid
				],
				[
					"campo_nombre"=>"alumno_tipoidentificacion",
					"campo_marcador"=>":Tipoidentificacion",
					"campo_valor"=>$alumno_tipoidentificacion
				],
				[
					"campo_nombre"=>"alumno_identificacion",
					"campo_marcador"=>":Identificacion",
					"campo_valor"=>$alumno_identificacion
				],				
				[
					"campo_nombre"=>"alumno_primernombre",
					"campo_marcador"=>":Primernombre",
					"campo_valor"=>$alumno_primernombre
				],
				[
					"campo_nombre"=>"alumno_segundonombre",
					"campo_marcador"=>":Segundonombre",
					"campo_valor"=>$alumno_segundonombre
				],				
				[
					"campo_nombre"=>"alumno_apellidopaterno",
					"campo_marcador"=>":Apellidopaterno",
					"campo_valor"=>$alumno_apellidopaterno
				],
				[
					"campo_nombre"=>"alumno_apellidomaterno",
					"campo_marcador"=>":Apellidomaterno",
					"campo_valor"=>$alumno_apellidomaterno
				],
				[
					"campo_nombre"=>"alumno_nombrecorto",
					"campo_marcador"=>":Nombrecorto",
					"campo_valor"=>$alumno_nombrecorto
				],
				[
					"campo_nombre"=>"alumno_direccion",
					"campo_marcador"=>":Direccion",
					"campo_valor"=>$alumno_direccion
				],
				[
					"campo_nombre"=>"alumno_fechanacimiento",
					"campo_marcador"=>":Fechanacimiento",
					"campo_valor"=>$alumno_fechanacimiento
				],
				[
					"campo_nombre"=>"alumno_fechaingreso",
					"campo_marcador"=>":Fechaingreso",
					"campo_valor"=>$alumno_fechaingreso
				],
				[
					"campo_nombre"=>"alumno_genero",
					"campo_marcador"=>":Genero",
					"campo_valor"=>$alumno_genero
				],
				[
					"campo_nombre"=>"alumno_hermanos",
					"campo_marcador"=>":Hermanos",
					"campo_valor"=>$alumno_hermanos
				],
				[
					"campo_nombre"=>"alumno_estado",
					"campo_marcador"=>":Activo",
					"campo_valor"=>$alumno_estado
				],				
				[
					"campo_nombre"=>"alumno_numcamiseta",
					"campo_marcador"=>":Camiseta",
					"campo_valor"=>$alumno_numcamiseta
				]
			];

			# Directorio de fotos #
			$codigorand=rand(0,100);
			$img_dir="../views/imagenes/fotos/alumno/";

			# Directorio de imagenes cedula#
			$dir_cedula="../views/imagenes/cedulas/";
			
    		# Comprobar si se selecciono una imagen #
    		if($_FILES['alumno_foto']['name']!="" && $_FILES['alumno_foto']['size']>0){
		
				# Creando directorio #
				if(!file_exists($img_dir)){
					if(!mkdir($img_dir,0777)){
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error",
							"texto"=>"No se creó el directorio",
							"icono"=>"error"
						];
						return json_encode($alerta);
						//exit();
					} 
				}

				# Verificando formato de imagenes #
				if(mime_content_type($_FILES['alumno_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['alumno_foto']['tmp_name'])!="image/png"){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"La imagen que ha seleccionado es de un formato no permitido ",
						"icono"=>"error"
					];
					return json_encode($alerta);
					//exit();
				}

				# Verificando peso de imagen #
				if(($_FILES['alumno_foto']['size']/1024)>4000){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"La imagen que ha seleccionado supera el peso permitido 4MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
					//exit();
				}

				#nombre de la foto
				$foto=str_ireplace(" ","_",$alumno_identificacion);
				$foto=$foto."_".$codigorand;
				

				# Extension de la imagen #
				switch(mime_content_type($_FILES['alumno_foto']['tmp_name'])){
					case 'image/jpeg':
						$foto=$foto.".jpg";
					break;
					case 'image/png':
						$foto=$foto.".png";
					break;
				}
				$maxWidth = 800;
    			$maxHeight = 600;

				chmod($img_dir,0777);
				$inputFile = ($_FILES['alumno_foto']['tmp_name']);
       			$outputFile = $img_dir.$foto;

				# Moviendo imagen al directorio #
				//if(!move_uploaded_file($_FILES['alumno_foto']['tmp_name'],$img_dir.$foto)){
				if ($this->resizeImageGD($inputFile, $maxWidth, $maxHeight, $outputFile)) {
					
				}else{
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"No es posible subir la imagen al sistema en este momento",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}
				
				# Eliminando imagen anterior #
				if(is_file($img_dir.$datos['alumno_imagen']) && $datos['alumno_imagen']!=$foto){
					chmod($img_dir.$datos['alumno_imagen'], 0777);
					unlink($img_dir.$datos['alumno_imagen']);
				}				
				
				$alumno_datos_reg[] = [
					"campo_nombre" => "alumno_imagen",
					"campo_marcador" => ":Foto",
					"campo_valor" => $foto
				];
				
			}


			if($_FILES['alumno_cedulaA']['name']!="" && $_FILES['alumno_cedulaA']['size']>0){
		
				# Creando directorio #
				if(!file_exists($dir_cedula)){
					if(!mkdir($dir_cedula,0777)){
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error",
							"texto"=>"No se creó el directorio",
							"icono"=>"error"
						];
						return json_encode($alerta);
						//exit();
					} 
				}

				# Verificando formato de imagenes #
				if(mime_content_type($_FILES['alumno_cedulaA']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['alumno_cedulaA']['tmp_name'])!="image/png"){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"La imagen que ha seleccionado es de un formato no permitido ",
						"icono"=>"error"
					];
					return json_encode($alerta);
					//exit();
				}

				# Verificando peso de imagen #
				if(($_FILES['alumno_cedulaA']['size']/1024)>4000){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"La imagen que ha seleccionado supera el peso permitido 4MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
					//exit();
				}

				#nombre de la imagen cedula
				$CedulaA=str_ireplace(" ","_",$alumno_identificacion);
				$CedulaA=$CedulaA."_A".$codigorand=rand(0,100);
					

				# Extension de la imagen #
				switch(mime_content_type($_FILES['alumno_cedulaA']['tmp_name'])){
					case 'image/jpeg':
						$CedulaA=$CedulaA.".jpg";
					break;
					case 'image/png':
						$CedulaA=$CedulaA.".png";
					break;
				}
				$maxWidth = 800;
    			$maxHeight = 600;

				chmod($img_dir,0777);
				$inputFile = ($_FILES['alumno_cedulaA']['tmp_name']);
       			$outputFile = $dir_cedula.$CedulaA;

				# Moviendo imagen al directorio #
				//if(!move_uploaded_file($_FILES['alumno_foto']['tmp_name'],$img_dir.$foto)){
				if ($this->resizeImageGD($inputFile, $maxWidth, $maxHeight, $outputFile)) {
					
				}else{
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"No es posible subir la imagen de la cedula al sistema en este momento",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}
				
				# Eliminando imagen anterior #
				if(is_file($dir_cedula.$datos['alumno_cedulaA']) && $datos['alumno_cedulaA']!=$CedulaA){
					chmod($dir_cedula.$datos['alumno_cedulaA'], 0777);
					unlink($dir_cedula.$datos['alumno_cedulaA']);
				}				
				
				$alumno_datos_reg[] = [
					"campo_nombre" => "alumno_cedulaA",
					"campo_marcador" => ":CedulaA",
					"campo_valor" => $CedulaA
				];
				
			}

			if($_FILES['alumno_cedulaR']['name']!="" && $_FILES['alumno_cedulaR']['size']>0){
		
				# Creando directorio #
				if(!file_exists($dir_cedula)){
					if(!mkdir($dir_cedula,0777)){
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error",
							"texto"=>"No se creó el directorio",
							"icono"=>"error"
						];
						return json_encode($alerta);
						//exit();
					} 
				}

				# Verificando formato de imagenes #
				if(mime_content_type($_FILES['alumno_cedulaR']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['alumno_cedulaR']['tmp_name'])!="image/png"){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"La imagen que ha seleccionado es de un formato no permitido ",
						"icono"=>"error"
					];
					return json_encode($alerta);
					//exit();
				}

				# Verificando peso de imagen #
				if(($_FILES['alumno_cedulaR']['size']/1024)>4000){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"La imagen que ha seleccionado supera el peso permitido 4MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
					//exit();
				}

				#nombre imagen cedula reverso
				$CedulaR=str_ireplace(" ","_",$alumno_identificacion);
				$CedulaR=$CedulaR."_R".$codigorand;
				

				# Extension de la imagen #
				switch(mime_content_type($_FILES['alumno_cedulaR']['tmp_name'])){
					case 'image/jpeg':
						$CedulaR=$CedulaR.".jpg";
					break;
					case 'image/png':
						$CedulaR=$CedulaR.".png";
					break;
				}
				$maxWidth = 800;
    			$maxHeight = 600;

				chmod($img_dir,0777);
				$inputFile = ($_FILES['alumno_cedulaR']['tmp_name']);
       			$outputFile = $dir_cedula.$CedulaR;

				# Moviendo imagen al directorio #
				//if(!move_uploaded_file($_FILES['alumno_foto']['tmp_name'],$img_dir.$foto)){
				if ($this->resizeImageGD($inputFile, $maxWidth, $maxHeight, $outputFile)) {
					
				}else{
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"No es posible subir la imagen de la cedula al sistema en este momento",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}
				
				# Eliminando imagen anterior #
				if(is_file($dir_cedula.$datos['alumno_cedulaR']) && $datos['alumno_cedulaR']!=$CedulaR){
					chmod($dir_cedula.$datos['alumno_cedulaR'], 0777);
					unlink($dir_cedula.$datos['alumno_cedulaR']);
				}				
				
				$alumno_datos_reg[] = [
					"campo_nombre" => "alumno_cedulaR",
					"campo_marcador" => ":CedulaR",
					"campo_valor" => $CedulaR
				];
				
			}

			$condicion=[
				"condicion_campo"=>"alumno_id",
				"condicion_marcador"=>":Alumnoid",
				"condicion_valor"=>$alumnoid
			];

			if($this->actualizarDatos("sujeto_alumno",$alumno_datos_reg,$condicion)){					
				
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Alumno actualizado",
					"texto"=>"El alumno ".$alumno_identificacion." | ".$alumno_primernombre." ".$alumno_apellidopaterno." se actualizó correctamente",
					"icono"=>"success"
				];

				/*---------------Inicio de registro de Información de los tabs*/
				$infomedic_tiposangre 	= $this->limpiarCadena($_POST['infomedic_tiposangre']);
				$infomedic_peso		  	= $this->limpiarCadena($_POST['infomedic_peso']);
				$infomedic_talla 	  	= $this->limpiarCadena($_POST['infomedic_talla']);
				$infomedic_enfermedad 	= $this->limpiarCadena($_POST['infomedic_enfermedad']);
				$infomedic_medicamentos = $this->limpiarCadena($_POST['infomedic_medicamentos']);
				$infomedic_alergia1 	= $this->limpiarCadena($_POST['infomedic_alergia1']);
				$infomedic_alergia2 	= $this->limpiarCadena($_POST['infomedic_alergia2']);
				$infomedic_cirugias 	= $this->limpiarCadena($_POST['infomedic_cirugias']);
				$infomedic_observacion	= $this->limpiarCadena($_POST['infomedic_observacion']);

				if(isset($_POST['infomedic_covid'])){ $infomedic_covid  = $_POST['infomedic_covid']; }else {$infomedic_covid="";}
				if(isset($_POST['infomedic_vacunas'])){ $infomedic_vacunas  = $_POST['infomedic_vacunas']; }else {$infomedic_vacunas="";}
                
                
            	if ($infomedic_peso ==""){$infomedic_peso = 0;}
				if ($infomedic_talla ==""){$infomedic_talla = 0;}
                
				$infomedic=$this->ejecutarConsulta("SELECT * FROM alumno_infomedic WHERE infomedic_alumnoid='$alumnoid'");
				if($infomedic->rowCount()>0){
				

					$infomedic_reg=[
						[
							"campo_nombre"=>"infomedic_alumnoid",
							"campo_marcador"=>":Alumnoid",
							"campo_valor"=>$alumnoid
						],
						[
							"campo_nombre"=>"infomedic_fecha",
							"campo_marcador"=>":Fechacreacion",
							"campo_valor"=>date("Y-m-d H:i:s")
						],
						[
							"campo_nombre"=>"infomedic_tiposangre",
							"campo_marcador"=>":Tiposangre",
							"campo_valor"=>$infomedic_tiposangre
						],
						[
							"campo_nombre"=>"infomedic_peso",
							"campo_marcador"=>":Peso",
							"campo_valor"=>$infomedic_peso
						],
						[
							"campo_nombre"=>"infomedic_talla",
							"campo_marcador"=>":Talla",
							"campo_valor"=>$infomedic_talla
						],
						[
							"campo_nombre"=>"infomedic_enfermedad",
							"campo_marcador"=>":Enfermedad",
							"campo_valor"=>$infomedic_enfermedad
						],
						[
							"campo_nombre"=>"infomedic_medicamentos",
							"campo_marcador"=>":Medicamentos",
							"campo_valor"=>$infomedic_medicamentos
						],
						[
							"campo_nombre"=>"infomedic_alergia1",
							"campo_marcador"=>":AlergiaMedicamentos",
							"campo_valor"=>$infomedic_alergia1
						],
						[
							"campo_nombre"=>"infomedic_alergia2",
							"campo_marcador"=>":AlergiaObjetos",
							"campo_valor"=>$infomedic_alergia2
						],
						[
							"campo_nombre"=>"infomedic_cirugias",
							"campo_marcador"=>":Cirugias",
							"campo_valor"=>$infomedic_cirugias
						],
						[
							"campo_nombre"=>"infomedic_observacion",
							"campo_marcador"=>":Observacion",
							"campo_valor"=>$infomedic_observacion
						],
						[
							"campo_nombre"=>"infomedic_covid",
							"campo_marcador"=>":VacunasCovid",
							"campo_valor"=>$infomedic_covid
						],
						[
							"campo_nombre"=>"infomedic_vacunas",
							"campo_marcador"=>":Vacunas",
							"campo_valor"=>$infomedic_vacunas
						]
					];
					
					$condicion=[
						"condicion_campo"=>"infomedic_alumnoid",
						"condicion_marcador"=>":Alumnoid",
						"condicion_valor"=>$alumnoid
					];

					$this->actualizarDatos("alumno_infomedic",$infomedic_reg,$condicion);

				}else{
					if($infomedic_tiposangre!="" || $infomedic_peso>0 || $infomedic_talla>0 || $infomedic_enfermedad!=""||
					$infomedic_medicamentos!="" || $infomedic_alergia1!="" || $infomedic_alergia2!="" || $infomedic_cirugias!="" ||
					$infomedic_observacion!=""){
						//if (!is_int($infomedic_peso) && !is_float($infomedic_peso)){$infomedic_peso = 0;}
						//if (!is_int($infomedic_talla) && !is_float($infomedic_talla)){$infomedic_talla = 0;}

						$infomedic_reg=[
							[
								"campo_nombre"=>"infomedic_alumnoid",
								"campo_marcador"=>":Alumnoid",
								"campo_valor"=>$alumnoid
							],
							[
								"campo_nombre"=>"infomedic_fecha",
								"campo_marcador"=>":Fechacreacion",
								"campo_valor"=>date("Y-m-d H:i:s")
							],
							[
								"campo_nombre"=>"infomedic_tiposangre",
								"campo_marcador"=>":Tiposangre",
								"campo_valor"=>$infomedic_tiposangre
							],
							[
								"campo_nombre"=>"infomedic_peso",
								"campo_marcador"=>":Peso",
								"campo_valor"=>$infomedic_peso
							],
							[
								"campo_nombre"=>"infomedic_talla",
								"campo_marcador"=>":Talla",
								"campo_valor"=>$infomedic_talla
							],
							[
								"campo_nombre"=>"infomedic_enfermedad",
								"campo_marcador"=>":Enfermedad",
								"campo_valor"=>$infomedic_enfermedad
							],
							[
								"campo_nombre"=>"infomedic_medicamentos",
								"campo_marcador"=>":Medicamentos",
								"campo_valor"=>$infomedic_medicamentos
							],
							[
								"campo_nombre"=>"infomedic_alergia1",
								"campo_marcador"=>":AlergiaMedicamentos",
								"campo_valor"=>$infomedic_alergia1
							],
							[
								"campo_nombre"=>"infomedic_alergia2",
								"campo_marcador"=>":AlergiaObjetos",
								"campo_valor"=>$infomedic_alergia2
							],
							[
								"campo_nombre"=>"infomedic_cirugias",
								"campo_marcador"=>":Cirugias",
								"campo_valor"=>$infomedic_cirugias
							],
							[
								"campo_nombre"=>"infomedic_observacion",
								"campo_marcador"=>":Observacion",
								"campo_valor"=>$infomedic_observacion
							],
							[
								"campo_nombre"=>"infomedic_covid",
								"campo_marcador"=>":VacunasCovid",
								"campo_valor"=>$infomedic_covid
							],
							[
								"campo_nombre"=>"infomedic_vacunas",
								"campo_marcador"=>":Vacunas",
								"campo_valor"=>$infomedic_vacunas
							]
						];

						$this->guardarDatos("alumno_infomedic",$infomedic_reg);
					}

				}
				/*---------------Fin de registro del tab Información Médica del alumno*/


				/*---------------Registro del tab Contacto Emergencia del alumno------------*/
				$cemer_nombre 		= $this->limpiarCadena($_POST['cemer_nombre']);
				$cemer_celular 		= $this->limpiarCadena($_POST['cemer_celular']);
				$cemer_parentesco	= $this->limpiarCadena($_POST['cemer_parentesco']);				

				$cmer=$this->ejecutarConsulta("SELECT * FROM alumno_cemergencia WHERE cemer_alumnoid='$alumnoid'");
				if($cmer->rowCount()>0){

					$cemergencia_reg=[
						[
							"campo_nombre"=>"cemer_alumnoid",
							"campo_marcador"=>":Alumnoid",
							"campo_valor"=>$alumnoid
						],						
						[
							"campo_nombre"=>"cemer_nombre",
							"campo_marcador"=>":NombreContactoEmer",
							"campo_valor"=>$cemer_nombre
						],
						[
							"campo_nombre"=>"cemer_celular",
							"campo_marcador"=>":CelularContactoEmer",
							"campo_valor"=>$cemer_celular
						],
						[
							"campo_nombre"=>"cemer_parentesco",
							"campo_marcador"=>":ParentescoContactoEmer",
							"campo_valor"=>$cemer_parentesco
						]
					];
	
					$condicion=[
						"condicion_campo"=>"cemer_alumnoid",
						"condicion_marcador"=>":Alumnoid",
						"condicion_valor"=>$alumnoid
					];

					$this->actualizarDatos("alumno_cemergencia",$cemergencia_reg,$condicion);

				}else{
					if($cemer_nombre!="" || $cemer_celular!=""){

						$cemergencia_reg=[
							[
								"campo_nombre"=>"cemer_alumnoid",
								"campo_marcador"=>":Alumnoid",
								"campo_valor"=>$alumnoid
							],						
							[
								"campo_nombre"=>"cemer_nombre",
								"campo_marcador"=>":NombreContactoEmer",
								"campo_valor"=>$cemer_nombre
							],
							[
								"campo_nombre"=>"cemer_celular",
								"campo_marcador"=>":CelularContactoEmer",
								"campo_valor"=>$cemer_celular
							],
							[
								"campo_nombre"=>"cemer_parentesco",
								"campo_marcador"=>":ParentescoContactoEmer",
								"campo_valor"=>$cemer_parentesco
							]
						];
		
						$condicion=[
							"condicion_campo"=>"cemer_alumnoid",
							"condicion_marcador"=>":Alumnoid",
							"condicion_valor"=>$alumnoid
						];
						$this->guardarDatos("alumno_cemergencia",$cemergencia_reg);
					}

				}
				/*---------------Fin de registro del tab Contacto Emergencia del alumno------*/
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Alumno no actualizado",
					"texto"=>"No fue posible actualizar los datos del alumno ".$alumno_identificacion." | ".$alumno_primernombre." ".$alumno_apellidopaterno.", por favor intente nuevamente",
					"icono"=>"success"
				];
			}
			return json_encode($alerta);
		}


		/*----------  Controlador eliminar foto alumno  ----------*/
		public function eliminarFotoAlumnoControlador(){

			$id=$this->limpiarCadena($_POST['usuario_id']);

			# Verificando usuario #
		    $datos=$this->ejecutarConsulta("SELECT * FROM usuario WHERE usuario_id='$id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"El usuario no se encuentra en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        //exit();
		    }else{
		    	$datos=$datos->fetch();
		    }

		    # Directorio de imagenes #
    		$img_dir="../views/imagenes/fotos/";

    		chmod($img_dir,0777);

    		if(is_file($img_dir.$datos['usuario_foto'])){

		        chmod($img_dir.$datos['usuario_foto'],0777);

		        if(!unlink($img_dir.$datos['usuario_foto'])){
		            $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error",
						"texto"=>"Error al intentar eliminar la foto del usuario, por favor intente nuevamente",
						"icono"=>"error"
					];
					return json_encode($alerta);
		        	//exit();
		        }
		    }else{
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No se encuentra la foto del usuario en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        //exit();
		    }

		    $usuario_datos_up=[
				[
					"campo_nombre"=>"usuario_foto",
					"campo_marcador"=>":Foto",
					"campo_valor"=>""
				],
				[
					"campo_nombre"=>"usuario_actualizado",
					"campo_marcador"=>":Actualizado",
					"campo_valor"=>date("Y-m-d H:i:s")
				]
			];

			$condicion=[
				"condicion_campo"=>"usuario_id",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];

			if($this->actualizarDatos("usuario",$usuario_datos_up,$condicion)){

				if($id==$_SESSION['id']){
					$_SESSION['foto']="";
				}

				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Foto eliminada",
					"texto"=>"La foto del usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido']." se elimino correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Foto eliminada",
					"texto"=>"No fue posible actualizar algunos datos del usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido'].", sin embargo la foto ha sido eliminada correctamente",
					"icono"=>"warning"
				];
			}

			return json_encode($alerta);
		}


		/*----------  Controlador actualizar foto alumno  ----------*/
		public function actualizarFotoAlumnoControlador(){

			$id=$this->limpiarCadena($_POST['usuario_id']);

			# Verificando usuario #
		    $datos=$this->ejecutarConsulta("SELECT * FROM usuario WHERE usuario_id='$id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No hemos encontrado el usuario en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        //exit();
		    }else{
		    	$datos=$datos->fetch();
		    }

		    # Directorio de imagenes #
    		$img_dir="../views/imagenes/fotos/";

    		# Comprobar si se selecciono una imagen #
    		if($_FILES['usuario_foto']['name']=="" && $_FILES['usuario_foto']['size']<=0){
    			$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No ha seleccionado una foto para el usuario",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        //exit();
    		}

    		# Creando directorio #
	        if(!file_exists($img_dir)){
	            if(!mkdir($img_dir,0777)){
	                $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"No se creó el directorio",
						"icono"=>"error"
					];
					return json_encode($alerta);
	                //exit();
	            } 
	        }

	        # Verificando formato de imagenes #
	        if(mime_content_type($_FILES['usuario_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['usuario_foto']['tmp_name'])!="image/png"){
	            $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"La imagen que ha seleccionado es de un formato no permitido",
					"icono"=>"error"
				];
				return json_encode($alerta);
	            //exit();
	        }

	        # Verificando peso de imagen #
	        if(($_FILES['usuario_foto']['size']/1024)>250){
	            $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"La imagen que ha seleccionado supera el peso permitido",
					"icono"=>"error"
				];
				return json_encode($alerta);
	            //exit();
	        }

	        # Nombre de la foto #
	        if($datos['usuario_foto']!=""){
		        $foto=explode(".", $datos['usuario_foto']);
		        $foto=$foto[0];
	        }else{
	        	$foto=str_ireplace(" ","_",$datos['usuario_nombre']);
	        	$foto=$foto."_".rand(0,100);
	        }
	        

	        # Extension de la imagen #
	        switch(mime_content_type($_FILES['usuario_foto']['tmp_name'])){
	            case 'image/jpeg':
	                $foto=$foto.".jpg";
	            break;
	            case 'image/png':
	                $foto=$foto.".png";
	            break;
	        }

	        chmod($img_dir,0777);

	        # Moviendo imagen al directorio #
	        if(!move_uploaded_file($_FILES['usuario_foto']['tmp_name'],$img_dir.$foto)){
	            $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No podemos subir la imagen al sistema en este momento",
					"icono"=>"error"
				];
				return json_encode($alerta);
	            //exit();
	        }

	        # Eliminando imagen anterior #
	        if(is_file($img_dir.$datos['usuario_foto']) && $datos['usuario_foto']!=$foto){
		        chmod($img_dir.$datos['usuario_foto'], 0777);
		        unlink($img_dir.$datos['usuario_foto']);
		    }

		    $usuario_datos_up=[
				[
					"campo_nombre"=>"usuario_foto",
					"campo_marcador"=>":Foto",
					"campo_valor"=>$foto
				],
				[
					"campo_nombre"=>"usuario_actualizado",
					"campo_marcador"=>":Actualizado",
					"campo_valor"=>date("Y-m-d H:i:s")
				]
			];

			$condicion=[
				"condicion_campo"=>"usuario_id",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];

			if($this->actualizarDatos("usuario",$usuario_datos_up,$condicion)){

				if($id==$_SESSION['id']){
					$_SESSION['foto']=$foto;
				}

				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Foto actualizada",
					"texto"=>"La foto del usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido']." se actualizo correctamente",
					"icono"=>"success"
				];
			}else{

				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Foto actualizada",
					"texto"=>"No hemos podido actualizar algunos datos del usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido']." , sin embargo la foto ha sido actualizada",
					"icono"=>"warning"
				];
			}

			return json_encode($alerta);
		}

		/* ==================================== Roles ==================================== */

		
		public function listarOptionRol_Borrar(){
			$option="";

			$consulta_datos="SELECT rol_id, rol_nombre FROM seguridad_rol WHERE rol_activo = 1";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){

				$option.='<option value='.$rows['rol_id'].'>'.$rows['rol_nombre'].'</option>';	
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

		public function listarOptionSedeAlumno_Borrar($alumnoid){
			$option="";	
			$array_ = [];
			$i=0;
			$sedeid=$this->seleccionarDatos("Unico","sujeto_alumno","alumno_id",$alumnoid);
			
			if(isset($sedeid)){
				foreach ($sedeid as $key) {		
					$array_[$i] = $key['alumno_sedeid'];
					$i += 1;		
				}				
			}else{
				$array_ = [];
			}

			$consulta_datos="SELECT sede_id, sede_nombre FROM general_sede";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$s=in_array($rows['sede_id'], $array_) ? "selected='selected'" : "";
				$option.='<option value="'.$rows['sede_id'].'" '.$s.'>'.$rows['sede_nombre'].'</option>';	
			}
			return $option;
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
		
		public function listarCatalogoNacionalidad(){
			$option="";

			$consulta_datos="SELECT C.catalogo_valor, C.catalogo_descripcion 
								FROM general_tabla_catalogo C
								INNER JOIN general_tabla T on T.tabla_id = C.catalogo_tablaid
								WHERE T.tabla_nombre = 'nacionalidad'";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';	
			}
			return $option;
		}
		
		public function listarCatalogoPosicionJuego(){
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
		
		public function listarCatalogoParentesco(){
			$option="";

			$consulta_datos="SELECT C.catalogo_valor, C.catalogo_descripcion 
								FROM general_tabla_catalogo C
								INNER JOIN general_tabla T on T.tabla_id = C.catalogo_tablaid
								WHERE T.tabla_nombre = 'parentesco'";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';	
			}
			return $option;
		}		
	}