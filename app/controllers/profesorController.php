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
			$profesor_cargoid			= $this->limpiarCadena($_POST['profesor_cargoid']);
			$profesor_fechaingreso		= $this->limpiarCadena($_POST['profesor_fechaingreso']);
			$profesor_fecharegistro		= $this->limpiarCadena($_POST['profesor_fechaingreso']);
			$profesor_estado			= "A";
			$profesor_id				= 1;

			if (isset($_POST['profesor_genero'])) {$profesor_genero = $_POST['profesor_genero'];}

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
				echo $profesor_id;
				echo $profesor_tipoidentificacion." ";
				echo $profesor_identificacion." ";
				echo $profesor_nombre." ";
				echo $profesor_correo." ";
				echo $profesor_celular." ";
				echo $profesor_direccion." ";
				echo $profesor_cargoid." ";
				echo $profesor_fechaingreso." ";
				echo $profesor_genero." ";
				echo $profesor_estado." ";
				echo $foto;
				echo $profesor_fecharegistro;
				

			$profesor_datos_reg=[
				[
					"campo_nombre"=>"profesor_id",
					"campo_marcador"=>":Id",
					"campo_valor"=>$profesor_id
				],
				[
					"campo_nombre"=>"profesor_tipoidentificacion",
					"campo_marcador"=>":TipoIdentificacion",
					"campo_valor"=>$profesor_tipoidentificacion
				],
				[
					"campo_nombre"=>"profesor_identificacion",
					"campo_marcador"=>":Identificación",
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
					"campo_nombre"=>"profesor_cargoid",
					"campo_marcador"=>":Cargo",
					"campo_valor"=>$profesor_cargoid
				],			
				[
					"campo_nombre"=>"profesor_fechaingreso",
					"campo_marcador"=>":Fechaingreso",
					"campo_valor"=>$profesor_fechaingreso
				],
				[
					"campo_nombre"=>"profesor_genero",
					"campo_marcador"=>":Género",
					"campo_valor"=>$profesor_genero
				],
				[
					"campo_nombre"=>"profesor_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>$profesor_estado
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

		public function listarCargos(){
			$option="";

			$consulta_datos="SELECT C.catalogo_valor, C.catalogo_descripcion 
								FROM general_tabla_catalogo C
								INNER JOIN general_tabla T on T.tabla_id = C.catalogo_tablaid
								WHERE T.tabla_nombre = 'cargos'";	
					
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
			$consulta_datos="SELECT U.*, R.rol_nombre 
							 FROM seguridad_usuario U 
							 inner join seguridad_rol R on R.rol_id = U.usuario_rolid
							 WHERE rol_id = 3";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($rows['usuario_estado']=='A'){
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
						<td>'.$rows['usuario_nombre'].'</td>
						<td>'.$estado.'</td>
						<td>
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/usuarioAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_usuario" value="eliminar">
								<input type="hidden" name="usuario_id" value="'.$rows['usuario_id'].'">						
								<button type="submit" class="btn float-right btn-danger btn-xs" style="margin-right: 5px;">Eliminar</button>
							</form>
							
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/usuarioAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_usuario" value="actualizarestado">
								<input type="hidden" name="usuario_id" value="'.$rows['usuario_id'].'">						
								<button type="submit" class="btn float-right '.$boton.' btn-xs" style="margin-right: 5px;""> '.$texto.' </button>
							</form>

							<a href="'.APP_URL.'userUpdate/'.$rows['usuario_id'].'/" class="btn float-right btn-success btn-xs" style="margin-right: 5px;">Actualizar</a>
							<a href="'.APP_URL.'userProfile/'.$rows['usuario_id'].'/" class="btn float-right btn-primary btn-xs" style="margin-right: 5px;">Perfil</a>
						</td>
					</tr>';	
			}
			return $tabla;
		}
    }