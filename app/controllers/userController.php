<?php

	namespace app\controllers;
	use app\models\mainModel;

	class userController extends mainModel{

		/*----------  Controlador registrar usuario  ----------*/
		public function registrarUsuarioControlador(){

			# Almacenando datos#
			$identificacion	= $this->limpiarCadena($_POST['usuario_identificacion']);
			$usuario		= $this->limpiarCadena($_POST['usuario_usuario']);
			$rolid			= $this->limpiarCadena($_POST['usuario_rolid']);
		    $nombre			= $this->limpiarCadena($_POST['usuario_nombre']);		    		    
		    $email			= $this->limpiarCadena($_POST['usuario_email']);
			$movil			= $this->limpiarCadena($_POST['usuario_movil']);
		    $clave1			= $this->limpiarCadena($_POST['usuario_clave']);
		    $clave2			= $this->limpiarCadena($_POST['usuario_clave2']);


		    # Verificando campos obligatorios #
		    if($identificacion=="" || $nombre=="" || $usuario=="" || $clave1=="" || $clave2=="" || $rolid=="" ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No ha completado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }

		    # Verificando integridad de los datos #
		    if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"El nombre del usuario no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }
		    

		    if($this->verificarDatos("[a-zA-Z0-9]{4,20}",$usuario)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"El USUARIO no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }

		    if($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}",$clave1) || $this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}",$clave2)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"Las CLAVES no coinciden con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }

		    # Verificando email #
		    if($email!=""){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$check_email=$this->ejecutarConsulta("SELECT usuario_email FROM seguridad_usuario WHERE usuario_email='$email'");
					if($check_email->rowCount()>0){
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Error",
							"texto"=>"El EMAIL que acaba de ingresar ya se encuentra registrado en el sistema, por favor verifique e intente nuevamente",
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
			 if($movil!=""){
				$check_movil=$this->ejecutarConsulta("SELECT usuario_movil FROM seguridad_usuario WHERE usuario_movil='$movil'");
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

            # Verificando claves #
            if($clave1!=$clave2){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"Las contraseñas que acaba de ingresar no coinciden, por favor verifique e intente nuevamente",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}else{
				$clave=password_hash($clave1,PASSWORD_BCRYPT,["cost"=>10]);
            }

            # Verificando usuario #
		    $check_usuario=$this->ejecutarConsulta("SELECT usuario_usuario FROM seguridad_usuario WHERE usuario_usuario='$usuario'");
		    if($check_usuario->rowCount()>0){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"El USUARIO ingresado ya se encuentra registrado, por favor elija otro",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }

		    # Directorio de imagenes #
    		$img_dir="../views/imagenes/fotos/usuario/";
			$codigo=rand(0,100);

    		# Comprobar si se selecciono una imagen #
    		if($_FILES['usuario_foto']['name']!="" && $_FILES['usuario_foto']['size']>0){

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
		                //exit();
		            } 
		        }

		        # Verificando formato de imagenes #
		        if(mime_content_type($_FILES['usuario_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['usuario_foto']['tmp_name'])!="image/png"){
		        	$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"Imagen tiene un formato no permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
		            //exit();
		        }

		        # Verificando peso de imagen #
		        if(($_FILES['usuario_foto']['size']/1024)>4000){
		        	$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"Imagen seleccionada supera el peso permitido 4MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
		            //exit();
		        }

		        # Nombre de la foto #
		        $foto=str_ireplace(" ","_",$usuario);
		        $foto=$foto."_".$codigo;

		        # Extension de la imagen #
		        switch(mime_content_type($_FILES['usuario_foto']['tmp_name'])){
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
				$inputFile = ($_FILES['usuario_foto']['tmp_name']);
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

		    $usuario_datos_reg=[
				[
					"campo_nombre"=>"usuario_identificacion",
					"campo_marcador"=>":Identificacion",
					"campo_valor"=>$identificacion
				],
				[
					"campo_nombre"=>"usuario_usuario",
					"campo_marcador"=>":Usuario",
					"campo_valor"=>$usuario
				],
				[
					"campo_nombre"=>"usuario_rolid",
					"campo_marcador"=>":Rolid",
					"campo_valor"=>$rolid
				],
				[
					"campo_nombre"=>"usuario_clave",
					"campo_marcador"=>":Clave",
					"campo_valor"=>$clave
				],
				[
					"campo_nombre"=>"usuario_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$nombre
				],				
				[
					"campo_nombre"=>"usuario_email",
					"campo_marcador"=>":Email",
					"campo_valor"=>$email
				],
				[
					"campo_nombre"=>"usuario_movil",
					"campo_marcador"=>":Movil",
					"campo_valor"=>$movil
				],				
				[
					"campo_nombre"=>"usuario_fechacreacion",
					"campo_marcador"=>":Fechacreacion",
					"campo_valor"=>date("Y-m-d H:i:s")
				],
				[
					"campo_nombre"=>"usuario_fechaactualizado",
					"campo_marcador"=>":Fechaactualizado",
					"campo_valor"=>date("Y-m-d H:i:s")
				],
				[
					"campo_nombre"=>"usuario_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>'A'
				],
				[
					"campo_nombre"=>"usuario_imagen",
					"campo_marcador"=>":Foto",
					"campo_valor"=>$foto
				]
			];

			$registrar_usuario=$this->guardarDatos("seguridad_usuario",$usuario_datos_reg);

			if($registrar_usuario->rowCount()==1){
				$alerta=[
					"tipo"=>"redireccionar",
					"url"=>APP_URL.'userList/',
					"titulo"=>"Usuario registrado",
					"texto"=>"El usuario ".$nombre." | ".$usuario." se registró correctamente",
					"icono"=>"success"
				];

				$check_usuarioid=$this->ejecutarConsulta("SELECT usuario_id FROM seguridad_usuario WHERE usuario_usuario ='$usuario'");
				if($check_usuarioid->rowCount()>0){
					$usuarioid=$check_usuarioid->fetchAll(); 					
					foreach( $usuarioid as $rows ){
						$usuario_id = $rows['usuario_id'];
					}

					if(isset($_POST['usuario_sedeid'])){
						foreach($_POST['usuario_sedeid'] as $rows){
							$usuario_sede_reg=[
								[
									"campo_nombre"=>"usuariosede_usuarioid",
									"campo_marcador"=>":UsuarioId",
									"campo_valor"=>$usuario_id
								],
								[
									"campo_nombre"=>"usuariosede_sedeid",
									"campo_marcador"=>":SedeId",
									"campo_valor"=>$rows
								]
							];
	
							$this->guardarDatos("seguridad_usuario_sede",$usuario_sede_reg);
						}
					}									
				}
			}else{
				
				if(is_file($img_dir.$foto)){
		            chmod($img_dir.$foto,0777);
		            unlink($img_dir.$foto);
		        }

				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No se pudo registrar el usuario, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);

		}

		/* Listar todos los usuarios*/
		public function listarUsuarios(){
			$tabla="";
			$fechaM = "";
			$estado = "";
			$texto = "";
			$boton = "";
			$consulta_datos="SELECT U.*, R.rol_nombre 
							 FROM seguridad_usuario U 
							 inner join seguridad_rol R on R.rol_id = U.usuario_rolid";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($rows['usuario_fechaactualizado']!='0000-00-00 00:00:00'){
					$fechaM = date("d-m-Y  h:i:s A",strtotime($rows['usuario_fechaactualizado']));
				}else{
					$fechaM = "";				}

				if($rows['usuario_estado']=='A'){
					$estado = "Activo";
					$texto = "Inactivar";
					$boton = "btn-secondary";
				}else{
					$estado = "Inactivo";
					$texto = "Activar";
					$boton = "btn-info";				}

				$tabla.='
					<tr>
						<td>'.$rows['usuario_usuario'].'</td>
						<td>'.$rows['usuario_nombre'].'</td>
						<td>'.$rows['rol_nombre'].'</td>
						<td>'.date("d-m-Y  h:i:s A",strtotime($rows['usuario_fechacreacion'])).'</td>
						<td>'.$fechaM.'</td>
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

		/*----------  Controlador eliminar usuario  ----------*/
		public function eliminarUsuarioControlador(){

			$id=$this->limpiarCadena($_POST['usuario_id']);

			if($id==1){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No podemos eliminar el usuario principal del sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        //exit();
			}

			# Verificando usuario #
		    $datos=$this->ejecutarConsulta("SELECT * FROM seguridad_usuario WHERE usuario_id ='$id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No hemos encontrado el usuario en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        //exit();
		    }else{
		    	$datos=$datos->fetch();
		    }

		    $eliminarUsuario=$this->eliminarRegistro("seguridad_usuario","usuario_id",$id);

		    if($eliminarUsuario->rowCount()==1){

		    	/*
				if(is_file("../views/fotos/".$datos['usuario_foto'])){
		            chmod("../views/fotos/".$datos['usuario_foto'],0777);
		            unlink("../views/fotos/".$datos['usuario_foto']);
		        }
				*/

		        $alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Usuario eliminado",
					"texto"=>"El usuario ".$datos['usuario_nombre']." ".$datos['usuario_usuario']." ha sido eliminado del sistema correctamente",
					"icono"=>"success"
				];

		    }else{

		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No hemos podido eliminar el usuario ".$datos['usuario_nombre']." ".$datos['usuario_usuario']." del sistema, por favor intente nuevamente",
					"icono"=>"error"
				];
		    }

		    return json_encode($alerta);
		}

		/*----------  Controlador actualizar usuario  ----------*/
		public function actualizarUsuarioControlador(){

			$usuario=$this->limpiarCadena($_POST['usuario_id']);

			# Verificando usuario #
		    $datos=$this->ejecutarConsulta("SELECT * FROM seguridad_usuario WHERE usuario_id='$usuario'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No hemos encontrado el usuario en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }else{
		    	$datos=$datos->fetch();
		    }
			# Almacenando datos#
			$identificacion	=$this->limpiarCadena($_POST['usuario_identificacion']);
		    $nombre			=$this->limpiarCadena($_POST['usuario_nombre']);
			$rolid			= $this->limpiarCadena($_POST['usuario_rolid']);
		    $email			=$this->limpiarCadena($_POST['usuario_email']);
			$movil			=$this->limpiarCadena($_POST['usuario_movil']);

		    $clave1=$this->limpiarCadena($_POST['usuario_clave']);
		    $clave2=$this->limpiarCadena($_POST['usuario_clave2']);		

		    # Verificando campos obligatorios #
		    if($nombre=="" || $email=="" || $usuario==""){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }

		    # Verificando integridad de los datos #
		    if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"El NOMBRE no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		       // exit();
		    }			

		    # Verificando email #
		    if($email!="" && $datos['usuario_email']!=$email){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$check_email=$this->ejecutarConsulta("SELECT usuario_email FROM seguridad_usuario WHERE usuario_email='$email'");
					if($check_email->rowCount()>0){
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Error",
							"texto"=>"El EMAIL que acaba de ingresar ya se encuentra registrado en el sistema, por favor verifique e intente nuevamente",
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

			 # Directorio de imagenes #
			$img_dir="../views/imagenes/fotos/usuario/";
			$codigorand=rand(0,100);

			$usuario_datos_up=[
				[
					"campo_nombre"=>"usuario_identificacion",
					"campo_marcador"=>":Identificacion",
					"campo_valor"=>$identificacion
				],
				[
					"campo_nombre"=>"usuario_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$nombre
				],
				[
					"campo_nombre"=>"usuario_rolid",
					"campo_marcador"=>":Rolid",
					"campo_valor"=>$rolid
				],
				[
					"campo_nombre"=>"usuario_movil",
					"campo_marcador"=>":Movil",
					"campo_valor"=>$movil
				],
				[
					"campo_nombre"=>"usuario_email",
					"campo_marcador"=>":Email",
					"campo_valor"=>$email
				],
				[
					"campo_nombre"=>"usuario_fechaactualizado",
					"campo_marcador"=>":Actualizado",
					"campo_valor"=>date("Y-m-d H:i:s")
				]
			];		
			
			if($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}",$clave1) || $this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}",$clave2)){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"Las CLAVES no coinciden con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
				//exit();
			}else{
				if($clave1!=$clave2){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"Las contraseñas que acaba de ingresar no coinciden, por favor verifique e intente nuevamente",
						"icono"=>"error"
					];
					return json_encode($alerta);
					//exit();
				}else{

					$clave=password_hash($clave1,PASSWORD_BCRYPT,["cost"=>10]);

					$usuario_datos_up[] = [				
						"campo_nombre"	=> "usuario_clave",
						"campo_marcador"=> ":Clave",
						"campo_valor"	=> $clave					
					];	
				}
			}

    		# Comprobar si se selecciono una imagen #
    		if($_FILES['usuario_foto']['name']!="" && $_FILES['usuario_foto']['size']>0){   		

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
				if(mime_content_type($_FILES['usuario_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['usuario_foto']['tmp_name'])!="image/png"){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"La imagen seleccionada es de un formato no permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Verificando peso de imagen #
				if(($_FILES['usuario_foto']['size']/1024)>4000){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"La imagen seleccionada supera el peso permitido 4MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
					//exit();
				}

				$foto=str_ireplace(" ","_",$datos['usuario_usuario']);
				$foto=$foto."_".$codigorand;			

				# Extension de la imagen #
				switch(mime_content_type($_FILES['usuario_foto']['tmp_name'])){
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
				$inputFile = ($_FILES['usuario_foto']['tmp_name']);
       			$outputFile = $img_dir.$foto;

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
				if(is_file($img_dir.$datos['usuario_imagen']) && $datos['usuario_imagen']!=$foto){
					chmod($img_dir.$datos['usuario_imagen'], 0777);
					unlink($img_dir.$datos['usuario_imagen']);
				}

				$usuario_datos_up[] = [				
					"campo_nombre"	=> "usuario_imagen",
					"campo_marcador"=> ":Foto",
					"campo_valor"	=> $foto					
				];		
			}	

			$condicion=[
				"condicion_campo"=>"usuario_id",
				"condicion_marcador"=>":Usuarioid",
				"condicion_valor"=>$usuario
			];

			if($this->actualizarDatos("seguridad_usuario",$usuario_datos_up,$condicion)){				
				
				$alerta=[
					"tipo"=>"redireccionar",
					"url"=>APP_URL.'userList/',
					"texto"=>"Los datos del usuario ".$datos['usuario_nombre']." | ".$datos['usuario_usuario']." se actualizaron correctamente",
					"icono"=>"success"
				];

				$rolesid=$this->ejecutarConsulta("SELECT * FROM seguridad_usuario_sede WHERE usuariosede_usuarioid='$usuario'");
				if($rolesid->rowCount()>0){
					$this->eliminarRegistro("seguridad_usuario_sede","usuariosede_usuarioid",$usuario);					
				}

				if(isset($_POST['usuario_sedeid'])){
					foreach($_POST['usuario_sedeid'] as $rows){
						$usuario_sede_reg=[
							[
								"campo_nombre"=>"usuariosede_usuarioid",
								"campo_marcador"=>":UsuarioId",
								"campo_valor"=>$usuario
							],
							[
								"campo_nombre"=>"usuariosede_sedeid",
								"campo_marcador"=>":SedeId",
								"campo_valor"=>$rows
							]
						];

						$this->guardarDatos("seguridad_usuario_sede",$usuario_sede_reg);
					}
				}					

			}else{
				if(is_file($img_dir.$foto)){
					chmod($img_dir.$foto,0777);
					unlink($img_dir.$foto);
				}
				
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No hemos podido actualizar los datos del usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido'].", por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}

		/*----------  Controlador actualizar estado usuario  ----------*/
		public function actualizarUsuarioEstadoControlador(){			

			$estadoA = '';
			$usuarioid=$this->limpiarCadena($_POST['usuario_id']);

			if($usuarioid==1){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No podemos inactivar el usuario principal del sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        //exit();
			}

			# Verificando usuario #
		    $datos=$this->ejecutarConsulta("SELECT * FROM seguridad_usuario WHERE usuario_id ='$usuarioid'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No hemos encontrado el usuario en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        //exit();
		    }else{
		    	$datos=$datos->fetch();
		    }
				
			if($datos['usuario_estado']=='A'){
				$estadoA = 'I';
			}else{
				$estadoA = 'A';
			}
            $usuario_datos_up=[
				[
					"campo_nombre"=>"usuario_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=> $estadoA
				]
			];

			$condicion=[
				"condicion_campo"=>"usuario_id",
				"condicion_marcador"=>":Usuarioid",
				"condicion_valor"=>$usuarioid
			];

			if($this->actualizarDatos("seguridad_usuario",$usuario_datos_up,$condicion)){

				$alerta=[
					"tipo"=>"redireccionar",
					"url"=>APP_URL.'userList/',
					"titulo"=>"Usuario actualizado",
					"texto"=>"El estado del usuario ".$datos['usuario_nombre']." | ".$datos['usuario_usuario']." se actualizó correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No hemos podido actualizar los datos del usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido'].", por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}

		/*----------  Controlador eliminar foto usuario  ----------*/
		public function eliminarFotoUsuarioControlador(){

			$id=$this->limpiarCadena($_POST['usuario_id']);

			# Verificando usuario #
		    $datos=$this->ejecutarConsulta("SELECT * FROM usuario WHERE usuario_id='$id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No hemos encontrado el usuario en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        //exit();
		    }else{
		    	$datos=$datos->fetch();
		    }

		    # Directorio de imagenes #
    		$img_dir="../views/fotos/";

    		chmod($img_dir,0777);

    		if(is_file($img_dir.$datos['usuario_foto'])){

		        chmod($img_dir.$datos['usuario_foto'],0777);

		        if(!unlink($img_dir.$datos['usuario_foto'])){
		            $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"Error al intentar eliminar la foto del usuario, por favor intente nuevamente",
						"icono"=>"error"
					];
					return json_encode($alerta);
		        	//exit();
		        }
		    }else{
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No hemos encontrado la foto del usuario en el sistema",
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
					"texto"=>"No hemos podido actualizar algunos datos del usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido'].", sin embargo la foto ha sido eliminada correctamente",
					"icono"=>"warning"
				];
			}

			return json_encode($alerta);
		}


		/*----------  Controlador actualizar foto usuario  ----------*/
		public function actualizarFotoUsuarioControlador(){

			$id=$this->limpiarCadena($_POST['usuario_id']);

			# Verificando usuario #
		    $datos=$this->ejecutarConsulta("SELECT * FROM usuario WHERE usuario_id='$id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No hemos encontrado el usuario en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        //exit();
		    }else{
		    	$datos=$datos->fetch();
		    }

		    # Directorio de imagenes #
    		$img_dir="../views/fotos/";

    		# Comprobar si se selecciono una imagen #
    		if($_FILES['usuario_foto']['name']=="" && $_FILES['usuario_foto']['size']<=0){
    			$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
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
						"titulo"=>"Error",
						"texto"=>"Error al crear el directorio",
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
					"titulo"=>"Error",
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
					"titulo"=>"Error",
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
					"titulo"=>"Error",
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
					"texto"=>"La foto del usuario ".$datos['usuario_nombre']." ".$datos['usuario_apellido']." se actualizó correctamente",
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
		
		public function listarRoles(){
			$tabla="";

			$consulta_datos="SELECT *, 
							CASE WHEN rol_estado = 'A' THEN 'Activo' ELSE 'Inactivo' END AS estado 
							FROM seguridad_rol
							WHERE rol_estado != 'E'";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){

				$tabla.='
					<tr>
						<td>'.$rows['rol_nombre'].'</td>
						<td>'.$rows['rol_detalle'].'</td>
						<td>'.$rows['estado'].'</td>
						<td><a href="invoice-print.html" rel="noopener" target="_blank" class="btn float-right btn-secondary btn-xs">Permisos</a></td>
						<td>
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/usuarioAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_usuario" value="eliminarRol">
								<input type="hidden" name="rol_id" value="'.$rows['rol_id'].'">						
								<button type="submit" class="btn float-right btn-danger btn-xs" style="margin-right: 5px;">Eliminar</button>
							</form>							

							<a href="'.APP_URL.'roList/'.$rows['rol_id'].'/" class="btn float-right btn-success btn-xs" style="margin-right: 5px;" >Editar</a>
							
						</td>
					</tr>';	
			}
			return $tabla;
		}

		public function BuscarRol($rolid){
		
			$consulta_datos="SELECT R.* 
					FROM seguridad_rol R										
					WHERE rol_id = ".$rolid;	

			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
		}

		public function eliminarRol(){
			
			$rolid=$this->limpiarCadena($_POST['rol_id']);

			$rol_datos=[
				[
					"campo_nombre"=>"rol_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=> 'E'
				]
			];

			$condicion=[
				"condicion_campo"=>"rol_id",
				"condicion_marcador"=>":Rolid",
				"condicion_valor"=>$rolid
			];

			if($this->actualizarDatos("seguridad_rol", $rol_datos, $condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Lugar eliminado",
					"texto"=>"El rol fue eliminado correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No hemos podido eliminar el rol, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}

		public function crearRol(){		
			
			# Almacenando datos#
			$rol_nombre  	= $this->limpiarCadena($_POST['rol_nombre']);
			$rol_detalle	= $this->limpiarCadena($_POST['rol_detalle']);
			$rol_estado		= $this->limpiarCadena($_POST['rol_estado']);
			
			# Verificando campos obligatorios #
		    if($rol_nombre=="" ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No has llenado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        
		    }			

			$rol_datos_reg=[
				[
					"campo_nombre"=>"rol_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$rol_nombre
				],
				[
					"campo_nombre"=>"rol_detalle",
					"campo_marcador"=>":Detalle",
					"campo_valor"=>$rol_detalle
				],				
				[
					"campo_nombre"=>"rol_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>'A'
				]
			];		

			$registrar_rol=$this->guardarDatos("seguridad_rol",$rol_datos_reg);

			if($registrar_rol->rowCount()>0){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Registro Rol",
					"texto"=>"Rol registrado correctamente",
					"icono"=>"success"
				];				
			
			}else{				

				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No se pudo registrar el Rol, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}		

		public function actualizarRol(){
			
			$rolid = $this->limpiarCadena($_POST['rol_id']);

			# Verificando pago #
			$datos = $this->ejecutarConsulta("SELECT rol_id FROM seguridad_rol WHERE rol_id = '$rolid '");			
			if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurró un error inesperado",
					"texto"=>"No hemos encontrado el Rol en el sistema: ".$rolid,
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }else{
		    	$datos=$datos->fetch();				
		    }				

			# Almacenando datos#
			$rol_nombre  	= $this->limpiarCadena($_POST['rol_nombre']);
			$rol_detalle	= $this->limpiarCadena($_POST['rol_detalle']);
			$rol_estado		= $this->limpiarCadena($_POST['rol_estado']);
			
			# Verificando campos obligatorios #
		    if($rol_nombre=="" ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No has llenado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        
		    }			

			$rol_datos_reg=[
				[
					"campo_nombre"=>"rol_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$rol_nombre
				],
				[
					"campo_nombre"=>"rol_detalle",
					"campo_marcador"=>":Detalle",
					"campo_valor"=>$rol_detalle
				],				
				[
					"campo_nombre"=>"rol_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>$rol_estado
				]
			];			
		
			$condicion=[
				"condicion_campo"=>"rol_id",
				"condicion_marcador"=>":Rolid",
				"condicion_valor"=>$rolid
			];			

			if($this->actualizarDatos("seguridad_rol",$rol_datos_reg,$condicion)){				
				
				$alerta=[
					"tipo"=>"redireccionar",			
					"url"=>APP_URL.'roList/',					
					"titulo"=>"Lugar actualizado",
					"texto"=>"Los datos del Rol ".$rol_nombre." se actualizaron correctamente",
					"icono"=>"success"	
				];								

			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No hemos podido actualizar los datos del Rol ".$rolid.", por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}
		
		public function listarOptionRol($rolid){
			$option="";
			
			$consulta_datos="SELECT rol_id, rol_nombre FROM seguridad_rol WHERE rol_estado = 'A'";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($rolid == $rows['rol_id']){
					$option.='<option value='.$rows['rol_id'].' selected="selected">'.$rows['rol_nombre'].'</option>';	
				}else{
					$option.='<option value='.$rows['rol_id'].'>'.$rows['rol_nombre'].'</option>';	
				}
				
			}
			return $option;
		}
		
		public function listarOptionSede($usuarioid){
			$option="";	
			$array_ = [];
			$i=0;
			$sedeid=$this->seleccionarDatos("Unico","seguridad_usuario_sede","usuariosede_usuarioid",$usuarioid);
			
			if(isset($sedeid)){
				foreach ($sedeid as $key) {		
					$array_[$i] = $key['usuariosede_sedeid'];
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

		public function listarOptionSedeUsuario($usuarioid){
			$option="";	
			$consulta_datos="SELECT S.sede_id, S.sede_nombre 
			                 FROM general_sede S 
							 INNER JOIN seguridad_usuario_sede US ON US.usuariosede_sedeid = S.sede_id 
							 WHERE US.usuariosede_usuarioid = ".$usuarioid;	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$option.='<option value="'.$rows['sede_id'].'">'.$rows['sede_nombre'].'</option>';	
			}
			return $option;
		}
	}