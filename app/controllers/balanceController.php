<?php

	namespace app\controllers;
	use app\models\mainModel;

	class balanceController extends mainModel{
        public function registrarIngreso(){							
			
			# Almacenando datos#
			$ingreso_fechainicio 	= $this->limpiarCadena($_POST['ingreso_fechainicio']);
			$ingreso_fechafin		= $this->limpiarCadena($_POST['ingreso_fechafin']);
			$ingreso_empresa	    = $this->limpiarCadena($_POST['ingreso_empresa']);
            $ingreso_monto	        = $this->limpiarCadena($_POST['ingreso_monto']);
            $ingreso_formaentrega	= $this->limpiarCadena($_POST['ingreso_formaentrega']);
            $ingreso_descripcion	= $this->limpiarCadena($_POST['ingreso_descripcion']);
			$ingreso_estado			= "A";
			
			# Verificando campos obligatorios #
		    if($ingreso_fechainicio=="" || $ingreso_fechafin=="" || $ingreso_empresa=="" || $ingreso_monto=="" ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No ha completado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);       
		    }		

            # Directorio de imagenes #
			$img_dir="../views/imagenes/fotos/ingresos/";
			$codigo=rand(0,100);

			# Comprobar si se selecciono una imagen #
			if($_FILES['ingreso_imagenpago']['name']!="" && $_FILES['ingreso_imagenpago']['size']>0){

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
				if(mime_content_type($_FILES['ingreso_imagenpago']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['ingreso_imagenpago']['tmp_name'])!="image/png"){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"La imagen que ha seleccionado es de un formato no permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Verificando peso de imagen #
				if(($_FILES['ingreso_imagenpago']['size']/1024)>4000){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"Imagen seleccionada supera el peso permitido 4MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Nombre de la foto #
				$foto=str_ireplace(" ","_",$ingreso_empresa);
				$foto=$foto."_".$codigo;

				# Extension de la imagen #
				switch(mime_content_type($_FILES['ingreso_imagenpago']['tmp_name'])){
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
				$inputFile = ($_FILES['ingreso_imagenpago']['tmp_name']);
					$outputFile = $img_dir.$foto;

				# Moviendo imagen al directorio #
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
            }
			$ingreso_datos_reg=[
				[
					"campo_nombre"=>"ingreso_fechainicio",
					"campo_marcador"=>":Fechainicio",
					"campo_valor"=>$ingreso_fechainicio
				],
				[
					"campo_nombre"=>"ingreso_fechafin",
					"campo_marcador"=>":Fechafin",
					"campo_valor"=>$ingreso_fechafin
				],				
				[
					"campo_nombre"=>"ingreso_empresa",
					"campo_marcador"=>":Empresa",
					"campo_valor"=>$ingreso_empresa
				],
                [
					"campo_nombre"=>"ingreso_monto",
					"campo_marcador"=>":Monto",
					"campo_valor"=>$ingreso_monto
				],				
				[
					"campo_nombre"=>"ingreso_formaentrega",
					"campo_marcador"=>":FormaEntrega",
					"campo_valor"=>$ingreso_formaentrega
				],				
				[
					"campo_nombre"=>"ingreso_descripcion",
					"campo_marcador"=>":Descripcion",
					"campo_valor"=>$ingreso_descripcion
				],
				[
					"campo_nombre"=>"ingreso_imagenpago",
					"campo_marcador"=>":Foto",
					"campo_valor"=>$foto
				],
				[
					"campo_nombre"=>"ingreso_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>$ingreso_estado
				],
				[
					"campo_nombre"=>"ingreso_fechaactualizado",
					"campo_marcador"=>":Actualizado",
					"campo_valor"=>date("Y-m-d H:i:s")
				]
			];		

			$registrar_ingreso=$this->guardarDatos("balance_ingreso",$ingreso_datos_reg);

			if($registrar_ingreso->rowCount()>0){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Registro exitoso",
					"texto"=>"El ingreso se registró correctamente",
					"icono"=>"success"
				];				
			
			}else{				

				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No fue posible registrar el ingreso, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
		}

        public function BuscarIngreso($ingresoid){		
			$consulta_datos=("SELECT ingreso_fechainicio, ingreso_fechafin, ingreso_empresa, ingreso_monto, ingreso_formaentrega,
                                    ingreso_descripcion, ingreso_imagenpago 
							 FROM balance_ingreso
							 WHERE ingreso_id =".$ingresoid);	

			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
		}

        public function listarFormaEntregaIngreso($ingreso_formaentrega){
			$option="";

			$consulta_datos="SELECT C.catalogo_valor, C.catalogo_descripcion 
								FROM general_tabla_catalogo C
								INNER JOIN general_tabla T on T.tabla_id = C.catalogo_tablaid
								WHERE T.tabla_nombre = 'forma_entregaingreso'
									AND T.tabla_estado = 'A'
									AND C.catalogo_estado = 'A'";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($ingreso_formaentrega == $rows['catalogo_valor']){
					$option.='<option value='.$rows['catalogo_valor'].' selected="selected">'.$rows['catalogo_descripcion'].'</option>';	
				}else{			
					$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';	
				}				
			}
			return $option;
		}

        public function listarIngresos(){
			$tabla="";
			$consulta_datos="SELECT ingreso_id, ingreso_empresa, ingreso_monto, ingreso_fechainicio, ingreso_fechafin
							 FROM balance_ingreso";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$tabla.='
					<tr>						
						<td>'.$rows['ingreso_empresa'].'</td>
						<td>'.$rows['ingreso_monto'].'</td>
						<td>'.$rows['ingreso_fechainicio'].'</td>
						<td>'.$rows['ingreso_fechafin'].'</td>
						<td>
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/balanceAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_ingreso" value="eliminar">
								<input type="hidden" name="ingreso_id" value="'.$rows['ingreso_id'].'">						
								<button type="submit" class="btn float-right btn-danger btn-xs" style="margin-right: 3px;">Eliminar</button>
							</form>

							<a href="'.APP_URL.'ingresoList/'.$rows['ingreso_id'].'/" class="btn float-right btn-success btn-xs" style="margin-right: 3px;">Editar</a>
						</td>
					</tr>';	
			}
			return $tabla;
		}

        public function actualizarIngreso(){							
			$ingresoid=$this->limpiarCadena($_POST['ingreso_id']);

			# Verificando existencia de torneo #
			$ingreso=$this->ejecutarConsulta("SELECT * FROM balance_ingreso WHERE ingreso_id='$ingresoid'");
			if($ingreso->rowCount()<=0){	
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"El ingreso no se encuentra en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);		    
			}else{
				$ingreso=$ingreso->fetch();
			}	
			
			# Almacenando datos#
			$ingreso_fechainicio 	= $this->limpiarCadena($_POST['ingreso_fechainicio']);
			$ingreso_fechafin		= $this->limpiarCadena($_POST['ingreso_fechafin']);
			$ingreso_empresa	    = $this->limpiarCadena($_POST['ingreso_empresa']);
            $ingreso_monto	        = $this->limpiarCadena($_POST['ingreso_monto']);
            $ingreso_formaentrega	= $this->limpiarCadena($_POST['ingreso_formaentrega']);
            $ingreso_descripcion	= $this->limpiarCadena($_POST['ingreso_descripcion']);

			
			# Verificando campos obligatorios #
		    if($ingreso_fechainicio=="" || $ingreso_fechafin=="" || $ingreso_empresa=="" || $ingreso_monto=="" ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No ha completado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);       
		    }		

			$ingreso_datos_reg=[
				[
					"campo_nombre"=>"ingreso_fechainicio",
					"campo_marcador"=>":Fechainicio",
					"campo_valor"=>$ingreso_fechainicio
				],
				[
					"campo_nombre"=>"ingreso_fechafin",
					"campo_marcador"=>":Fechafin",
					"campo_valor"=>$ingreso_fechafin
				],				
				[
					"campo_nombre"=>"ingreso_empresa",
					"campo_marcador"=>":Empresa",
					"campo_valor"=>$ingreso_empresa
				],
                [
					"campo_nombre"=>"ingreso_monto",
					"campo_marcador"=>":Monto",
					"campo_valor"=>$ingreso_monto
				],				
				[
					"campo_nombre"=>"ingreso_formaentrega",
					"campo_marcador"=>":FormaEntrega",
					"campo_valor"=>$ingreso_formaentrega
				],				
				[
					"campo_nombre"=>"ingreso_descripcion",
					"campo_marcador"=>":Descripcion",
					"campo_valor"=>$ingreso_descripcion
				]
			];		

			# Directorio de imagenes #
			$img_dir="../views/imagenes/fotos/ingresos/";
			$codigo=rand(0,100);

			# Comprobar si se selecciono una imagen #
			if($_FILES['ingreso_imagenpago']['name']!="" && $_FILES['ingreso_imagenpago']['size']>0){

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
				if(mime_content_type($_FILES['ingreso_imagenpago']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['ingreso_imagenpago']['tmp_name'])!="image/png"){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"La imagen que ha seleccionado es de un formato no permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Verificando peso de imagen #
				if(($_FILES['ingreso_imagenpago']['size']/1024)>4000){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"Imagen seleccionada supera el peso permitido 4MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Nombre de la foto #
				$foto=str_ireplace(" ","_",$ingreso_empresa);
				$foto=$foto."_".$codigo;

				# Extension de la imagen #
				switch(mime_content_type($_FILES['ingreso_imagenpago']['tmp_name'])){
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
				$inputFile = ($_FILES['ingreso_imagenpago']['tmp_name']);
					$outputFile = $img_dir.$foto;

				# Moviendo imagen al directorio #
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
				if(is_file($img_dir.$ingreso['ingreso_imagenpago']) && $ingreso['ingreso_imagenpago']!=$foto){
					chmod($img_dir.$ingreso['ingreso_imagenpago'], 0777);
					unlink($img_dir.$ingreso['ingreso_imagenpago']);
				}	

				$ingreso_datos_reg[] = [
					"campo_nombre"=>"ingreso_imagenpago",
					"campo_marcador"=>":Foto",
					"campo_valor"=>$foto
				];
			}

			$condicion=[
				"condicion_campo"=>"ingreso_id",
				"condicion_marcador"=>":Ingresoid",
				"condicion_valor"=>$ingresoid
			];

			if($this->actualizarDatos("balance_ingreso",$ingreso_datos_reg,$condicion)){					
				
				$alerta=[
					"tipo"=>"redireccionar",
					"url"=>APP_URL.'ingresoList/',
					"titulo"=>"Ingreso actualizado",
					"texto"=>"El ingreso ".$ingreso_empresa." se actualizó correctamente",
					"icono"=>"success"
				];
			}else{				
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No fue posible actualizar el ingreso, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
		}
        
		public function eliminarIngreso(){

			$ingresoid=$this->limpiarCadena($_POST['ingreso_id']);

			# Verificando usuario #
		    $ingreso=$this->ejecutarConsulta("SELECT * FROM balance_ingreso WHERE ingreso_id='$ingresoid'");
			if($ingreso->rowCount()<=0){	
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"El ingreso no se encuentra en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}else{
		    	$ingreso=$ingreso->fetch();
		    }
			if($ingreso['ingreso_estado']=='A'){
				$estadoA = 'E';
			}else{
				$estadoA = 'A';
			}
            $ingreso_datos_up=[
				[
					"campo_nombre"=>"ingreso_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=> $estadoA
				]
			];
			$condicion=[
				"condicion_campo"=>"ingreso_id",
				"condicion_marcador"=>":Ingresoid",
				"condicion_valor"=>$ingresoid
			];

			if($this->actualizarDatos("balance_ingreso",$ingreso_datos_up,$condicion)){

				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Ingreso eliminado correctamente",
					"texto"=>"El ingreso fue eliminado correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No fue posible eliminar el ingreso, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
		}
    }