<?php

	namespace app\controllers;
	use app\models\mainModel;

	class profesorController extends mainModel{

		/*----------  Controlador registrar usuario  ----------*/
		public function registrarProfesorControlador(){
			# Almacenando datos#
			$profesor_tipoidentificacion= $this->limpiarCadena($_POST['profesor_tipoidentificacion']);
			$profesor_identificacion	= $this->limpiarCadena($_POST['profesor_identificacion']);
			$profesor_nombre			= $this->limpiarCadena($_POST['profesor_nombre']);
			$profesor_correo			= $this->limpiarCadena($_POST['profesor_correo']);		    		    
			$profesor_celular			= $this->limpiarCadena($_POST['profesor_celular']);
			$profesor_direccion			= $this->limpiarCadena($_POST['profesor_direccion']);
			$profesor_especialidadid	= $this->limpiarCadena($_POST['profesor_especialidadid']);
			$profesor_fechaingreso		= $this->limpiarCadena($_POST['profesor_fechaingreso']);
			$profesor_genero 			= $this->limpiarCadena($_POST['profesor_genero']);
			$profesor_sueldo			= $this->limpiarCadena($_POST['profesor_sueldo']);			
			$profesor_estado			= "A";

			$profesor_sueldo = str_replace(['$', ',', ' '], '', $profesor_sueldo);

			# Verificando campos obligatorios #
			if($profesor_identificacion=="" || $profesor_nombre=="" || $profesor_celular=="" || $profesor_correo==""){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No ha completado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}
			# Verificando email #
			if($profesor_correo!=""){
				if(filter_var($profesor_correo, FILTER_VALIDATE_EMAIL)){
					$check_email=$this->ejecutarConsulta("SELECT profesor_correo FROM sujeto_profesor WHERE profesor_correo='$profesor_correo'");
					if($check_email->rowCount()>0){
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Error",
							"texto"=>"El EMAIL ya se encuentra registrado en el sistema, por favor verifique e intente nuevamente",
							"icono"=>"error"
						];
						return json_encode($alerta);
					}
				}else{
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"Ha ingresado un correo electrónico no válido",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}
			}

				# Verificando celular #
				if($profesor_celular!=""){
				$check_movil=$this->ejecutarConsulta("SELECT profesor_celular FROM sujeto_profesor WHERE profesor_celular='$profesor_celular'");
				if($check_movil->rowCount()>0){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"Número de celular ya existe",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}
			}

			# Directorio de imagenes #
			$img_dir="../views/imagenes/fotos/profesor/";
			$codigo=rand(0,100);

			# Comprobar si se selecciono una imagen #
			if($_FILES['profesor_foto']['name']!="" && $_FILES['profesor_foto']['size']>0){

				# Creando directorio #
				if(!file_exists($img_dir)){
					if(!mkdir($img_dir,0777)){
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Error",
							"texto"=>"Error al crear el directorio",
							"icono"=>"error"
						];
						return json_encode($alerta);
					} 
				}

				# Verificando formato de imagenes #
				if(mime_content_type($_FILES['profesor_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['profesor_foto']['tmp_name'])!="image/png"){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"Imagen tiene un formato no permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Verificando peso de imagen #
				if(($_FILES['profesor_foto']['size']/1024)>4000){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"Imagen seleccionada supera el peso permitido 4MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Nombre de la foto #
				$foto=str_ireplace(" ","_",$profesor_identificacion);
				$foto=$foto."_".$codigo;

				# Extension de la imagen #
				switch(mime_content_type($_FILES['profesor_foto']['tmp_name'])){
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
				$inputFile = ($_FILES['profesor_foto']['tmp_name']);
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
			$profesor_datos_reg=[
				[
					"campo_nombre"=>"profesor_tipoidentificacion",
					"campo_marcador"=>":TipoIdentificacion",
					"campo_valor"=>$profesor_tipoidentificacion
				],
				[
					"campo_nombre"=>"profesor_identificacion",
					"campo_marcador"=>":Identificacion",
					"campo_valor"=>$profesor_identificacion
				],
				[
					"campo_nombre"=>"profesor_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$profesor_nombre
				],				
				[
					"campo_nombre"=>"profesor_correo",
					"campo_marcador"=>":Correo",
					"campo_valor"=>$profesor_correo
				],				
				[
					"campo_nombre"=>"profesor_celular",
					"campo_marcador"=>":Celular",
					"campo_valor"=>$profesor_celular
				],
				[
					"campo_nombre"=>"profesor_direccion",
					"campo_marcador"=>":Direccion",
					"campo_valor"=>$profesor_direccion
				],
				[
					"campo_nombre"=>"profesor_especialidadid",
					"campo_marcador"=>":Cargo",
					"campo_valor"=>$profesor_especialidadid
				],			
				[
					"campo_nombre"=>"profesor_fechaingreso",
					"campo_marcador"=>":Fechaingreso",
					"campo_valor"=>$profesor_fechaingreso
				],
				[
					"campo_nombre"=>"profesor_genero",
					"campo_marcador"=>":Genero",
					"campo_valor"=>$profesor_genero
				],
				[
					"campo_nombre"=>"profesor_sueldo",
					"campo_marcador"=>":Sueldo",
					"campo_valor"=>$profesor_sueldo
				],
				[
					"campo_nombre"=>"profesor_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>$profesor_estado
				],
				[
					"campo_nombre"=>"profesor_foto",
					"campo_marcador"=>":Foto",
					"campo_valor"=>$foto
				]
			];

			$registrar_profesor=$this->guardarDatos("sujeto_profesor",$profesor_datos_reg);

			if($registrar_profesor->rowCount()==1){
				$alerta=[
					"tipo"=>"redireccionar",
					"url"=>APP_URL.'profesorList/',
					"titulo"=>"Profesor registrado",
					"texto"=>"El profesor ".$profesor_nombre." se registró correctamente",
					"icono"=>"success"
				];
			}else{
				
				if(is_file($img_dir.$foto)){
					chmod($img_dir.$foto,0777);
					unlink($img_dir.$foto);
				}

				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No se pudo registrar el profesor, por favor intente nuevamente",
					"icono"=>"error"
				];
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

		public function listarEspecialidad(){
			$option="";

			$consulta_datos="SELECT C.catalogo_valor, C.catalogo_descripcion 
								FROM general_tabla_catalogo C
								INNER JOIN general_tabla T on T.tabla_id = C.catalogo_tablaid
								WHERE T.tabla_nombre = 'especialidad_profesor'";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';	
			}
			return $option;
		}

		/* Listar todos los profesores*/
		public function listarProfesores(){
			$tabla="";
			$estado = "";
			$texto = "";
			$boton = "";
			$consulta_datos="SELECT profesor_id, profesor_identificacion, profesor_nombre, profesor_correo, profesor_celular,
								CASE WHEN profesor_estado='A' THEN 'Activo' 
									 WHEN profesor_estado = 'I' THEN 'Inactivo' 
									 ELSE profesor_estado 
								END AS ESTADO 
							 FROM sujeto_profesor
							 WHERE profesor_estado IN ('A','I')";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($rows['ESTADO']=='Activo'){
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
						<td>'.$rows['profesor_identificacion'].'</td>
						<td>'.$rows['profesor_nombre'].'</td>
						<td>'.$rows['profesor_correo'].'</td>
						<td>'.$rows['profesor_celular'].'</td>
						<td>'.$estado.'</td>
						<td>
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/profesorAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_profesor" value="eliminar">
								<input type="hidden" name="profesor_id" value="'.$rows['profesor_id'].'">						
								<button type="submit" class="btn float-right btn-danger btn-xs" style="margin-right: 5px;">Eliminar</button>
							</form>
							
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/profesorAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_profesor" value="actualizarestado">
								<input type="hidden" name="profesor_id" value="'.$rows['profesor_id'].'">						
								<button type="submit" class="btn float-right '.$boton.' btn-xs" style="margin-right: 5px;""> '.$texto.' </button>
							</form>

							<a href="'.APP_URL.'profesorUpdate/'.$rows['profesor_id'].'/" class="btn float-right btn-success btn-xs" style="margin-right: 5px;">Actualizar</a>
							<a href="'.APP_URL.'profesorProfile/'.$rows['profesor_id'].'/" class="btn float-right btn-primary btn-xs" style="margin-right: 5px;">Perfil</a>
						</td>
					</tr>';	
			}
			return $tabla;
		}
		
		/*----------  Obtener el tipo de documento guardado  ----------*/
		public function OptionTipoIdentificacion($tipoidentificacion){
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
		
		/*----------  Obtener el tipo de especialidad guardada ----------*/
		public function OptionEspecialidad($especialidadid){
			$option="";

			$consulta_datos="SELECT C.catalogo_valor, C.catalogo_descripcion 
								FROM general_tabla_catalogo C
								INNER JOIN general_tabla T on T.tabla_id = C.catalogo_tablaid
								WHERE T.tabla_nombre = 'especialidad_profesor'";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($especialidadid == $rows['catalogo_valor']){
					$option.='<option value='.$rows['catalogo_valor'].' selected="selected">'.$rows['catalogo_descripcion'].'</option>';	
				}else{
					$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';	
				}
			}
			return $option;
		}

		public function actualizarProfesorControlador(){

			$profesorid=$this->limpiarCadena($_POST['profesor_id']);

			# Verificando existencia de profesor #
			$profesor=$this->ejecutarConsulta("SELECT * FROM sujeto_profesor WHERE profesor_id='$profesorid'");
			if($profesor->rowCount()<=0){	
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"El profesor no se encuentra en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);		    
			}else{
				$profesor=$profesor->fetch();
			}	
			
			# Almacenando datos#
			$profesor_tipoidentificacion= $this->limpiarCadena($_POST['profesor_tipoidentificacion']);
			$profesor_identificacion	= $this->limpiarCadena($_POST['profesor_identificacion']);
			$profesor_nombre			= $this->limpiarCadena($_POST['profesor_nombre']);
			$profesor_correo			= $this->limpiarCadena($_POST['profesor_correo']);		    		    
			$profesor_celular			= $this->limpiarCadena($_POST['profesor_celular']);
			$profesor_direccion			= $this->limpiarCadena($_POST['profesor_direccion']);
			$profesor_especialidadid	= $this->limpiarCadena($_POST['profesor_especialidadid']);
			$profesor_fechaingreso		= $this->limpiarCadena($_POST['profesor_fechaingreso']);
			$profesor_sueldo			= $this->limpiarCadena($_POST['profesor_sueldo']);
			$profesor_genero 			= $this->limpiarCadena($_POST['profesor_genero']);

			$profesor_sueldo = str_replace(['$', ',', ' '], '', $profesor_sueldo);

			# Verificando campos obligatorios #
			if($profesor_identificacion=="" || $profesor_nombre=="" || $profesor_celular=="" || $profesor_correo==""){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No ha completado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}

			$profesor_datos_reg=[
				[
					"campo_nombre"=>"profesor_tipoidentificacion",
					"campo_marcador"=>":TipoIdentificacion",
					"campo_valor"=>$profesor_tipoidentificacion
				],
				[
					"campo_nombre"=>"profesor_identificacion",
					"campo_marcador"=>":Identificacion",
					"campo_valor"=>$profesor_identificacion
				],
				[
					"campo_nombre"=>"profesor_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$profesor_nombre
				],				
				[
					"campo_nombre"=>"profesor_correo",
					"campo_marcador"=>":Correo",
					"campo_valor"=>$profesor_correo
				],				
				[
					"campo_nombre"=>"profesor_celular",
					"campo_marcador"=>":Celular",
					"campo_valor"=>$profesor_celular
				],
				[
					"campo_nombre"=>"profesor_direccion",
					"campo_marcador"=>":Direccion",
					"campo_valor"=>$profesor_direccion
				],
				[
					"campo_nombre"=>"profesor_especialidadid",
					"campo_marcador"=>":Especialidad",
					"campo_valor"=>$profesor_especialidadid
				],			
				[
					"campo_nombre"=>"profesor_fechaingreso",
					"campo_marcador"=>":Fechaingreso",
					"campo_valor"=>$profesor_fechaingreso
				],
				[
					"campo_nombre"=>"profesor_genero",
					"campo_marcador"=>":Genero",
					"campo_valor"=>$profesor_genero
				],
				[
					"campo_nombre"=>"profesor_sueldo",
					"campo_marcador"=>":Sueldo",
					"campo_valor"=>$profesor_sueldo
				]
			];
			
			# Directorio de imagenes #
			$img_dir="../views/imagenes/fotos/profesor/";
			$codigo=rand(0,100);

			# Comprobar si se selecciono una imagen #
			if($_FILES['profesor_foto']['name']!="" && $_FILES['profesor_foto']['size']>0){

				# Creando directorio #
				if(!file_exists($img_dir)){
					if(!mkdir($img_dir,0777)){
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Error",
							"texto"=>"Error al crear el directorio",
							"icono"=>"error"
						];
						return json_encode($alerta);
					} 
				}

				# Verificando formato de imagenes #
				if(mime_content_type($_FILES['profesor_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['profesor_foto']['tmp_name'])!="image/png"){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"La imagen que ha seleccionado es de un formato no permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Verificando peso de imagen #
				if(($_FILES['profesor_foto']['size']/1024)>4000){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"Imagen seleccionada supera el peso permitido 4MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Nombre de la foto #
				$foto=str_ireplace(" ","_",$profesor_identificacion);
				$foto=$foto."_".$codigo;

				# Extension de la imagen #
				switch(mime_content_type($_FILES['profesor_foto']['tmp_name'])){
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
				$inputFile = ($_FILES['profesor_foto']['tmp_name']);
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
				if(is_file($img_dir.$profesor['profesor_foto']) && $profesor['profesor_foto']!=$foto){
					chmod($img_dir.$profesor['profesor_foto'], 0777);
					unlink($img_dir.$profesor['profesor_foto']);
				}	

				$profesor_datos_reg[] = [
					"campo_nombre"=>"profesor_foto",
					"campo_marcador"=>":Foto",
					"campo_valor"=>$foto
				];
			}

			$condicion=[
				"condicion_campo"=>"profesor_id",
				"condicion_marcador"=>":Profesorid",
				"condicion_valor"=>$profesorid
			];

			if($this->actualizarDatos("sujeto_profesor",$profesor_datos_reg,$condicion)){					
				
				$alerta=[
					"tipo"=>"redireccionar",
					"url"=>APP_URL.'profesorList/',
					"titulo"=>"Profesor actualizado",
					"texto"=>"El profesor ".$profesor_nombre." se actualizó correctamente",
					"icono"=>"success"
				];
			}else{				
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No fue posible actualizar el profesor, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
        }

		public function actualizarEstadoProfesorControlador(){

			$profesor_id=$this->limpiarCadena($_POST['profesor_id']);

			# Verificando usuario #
		    $datos=$this->ejecutarConsulta("SELECT * FROM sujeto_profesor WHERE profesor_id='$profesor_id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"El profesor no se encuentra en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }else{
		    	$datos=$datos->fetch();
		    }
			if($datos['profesor_estado']=='A'){
				$estadoA = 'I';
			}else{
				$estadoA = 'A';
			}
            $profesor_datos_up=[
				[
					"campo_nombre"=>"profesor_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=> $estadoA
				]
			];
			$condicion=[
				"condicion_campo"=>"profesor_id",
				"condicion_marcador"=>":Profesorid",
				"condicion_valor"=>$profesor_id
			];

			if($this->actualizarDatos("sujeto_profesor",$profesor_datos_up,$condicion)){

				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Estado actualizado correctamente",
					"texto"=>"El estado del profesor ".$datos['profesor_nombre']." fue actualizado correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar el estado del profesor ".$datos['profesor_nombre']." por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
		}

		/*----------  Controlador eliminar profesor  ----------*/
		public function eliminarProfesorControlador(){

			$profesor_id=$this->limpiarCadena($_POST['profesor_id']);

			# Verificando usuario #
			$datos=$this->ejecutarConsulta("SELECT * FROM sujeto_profesor WHERE profesor_id='$profesor_id'");
			if($datos->rowCount()<=0){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"El profesor no se encuentra en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}else{
				$datos=$datos->fetch();
			}
			if($datos['profesor_estado']=='A' || $datos['profesor_estado']=='I'){
				$estadoA = 'E';
			}else{
				$estadoA = 'X';
			}
			$profesor_datos_up=[
				[
					"campo_nombre"=>"profesor_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=> $estadoA
				]
			];
			$condicion=[
				"condicion_campo"=>"profesor_id",
				"condicion_marcador"=>":Profesorid",
				"condicion_valor"=>$profesor_id
			];

			if($this->actualizarDatos("sujeto_profesor",$profesor_datos_up,$condicion)){

				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"El profesor fue eliminado correctamente",
					"texto"=>"El profesor ".$datos['profesor_nombre']." fue eliminado correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido eliminar el profesor ".$datos['profesor_nombre']." por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
		}
    }