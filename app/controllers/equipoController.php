<?php
	namespace app\controllers;
	use app\models\mainModel;

	class equipoController extends mainModel{

		/*----------  Controlador registrar usuario  ----------*/
		public function registrarEquipoControlador(){
			# Almacenando datos#
			$equipo_torneoid	= $this->limpiarCadena($_POST['equipo_torneoid']);
			$equipo_nombre		= $this->limpiarCadena($_POST['equipo_nombre']);
			$equipo_categoria	= $this->limpiarCadena($_POST['equipo_categoria']);
			$equipo_estado		= "A";

			# Verificando campos obligatorios #
			if($equipo_nombre=="" || $equipo_categoria==""){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No ha completado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}			

			# Directorio de imagenes #
			$img_dir="../views/imagenes/fotos/equipos/";
			$codigo=rand(0,100);

			# Comprobar si se selecciono una imagen #
			if($_FILES['equipo_foto']['name']!="" && $_FILES['equipo_foto']['size']>0){

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
				if(mime_content_type($_FILES['equipo_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['equipo_foto']['tmp_name'])!="image/png"){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"Imagen tiene un formato no permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Verificando peso de imagen #
				if(($_FILES['equipo_foto']['size']/1024)>4000){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"Imagen seleccionada supera el peso permitido 4MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Nombre de la foto #
				$foto=str_ireplace(" ","_",$equipo_nombre);
				$foto=$foto."_".$codigo;

				# Extension de la imagen #
				switch(mime_content_type($_FILES['equipo_foto']['tmp_name'])){
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
				$inputFile = ($_FILES['equipo_foto']['tmp_name']);
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
			$equipo_datos_reg=[
				[
					"campo_nombre"=>"equipo_torneoid",
					"campo_marcador"=>":TorneoEquipo",
					"campo_valor"=>$equipo_torneoid
				],
				[
					"campo_nombre"=>"equipo_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$equipo_nombre
				],
				[
					"campo_nombre"=>"equipo_categoria",
					"campo_marcador"=>":Categoria",
					"campo_valor"=>$equipo_categoria
				],		
				[
					"campo_nombre"=>"equipo_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>$equipo_estado
				],
				[
					"campo_nombre"=>"equipo_foto",
					"campo_marcador"=>":Foto",
					"campo_valor"=>$foto
				],				[
					"campo_nombre"=>"equipo_fechaactualizado",
					"campo_marcador"=>":Actualizado",
					"campo_valor"=>date("Y-m-d H:i:s")
				]
			];

			$registrar_equipo=$this->guardarDatos("torneo_equipo",$equipo_datos_reg);

			if($registrar_equipo->rowCount()==1){
				$alerta=[
					"tipo"=>"redireccionar",
					"url"=>APP_URL.'equipoList/'.$equipo_torneoid,
					"titulo"=>"Equipo registrado",
					"texto"=>"El equipo ".$equipo_nombre." se registró correctamente",
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
					"texto"=>"No fue posible registrar el equipo, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
        }

		/* Listar los equipos de un torneo*/
		public function listarEquiposTorneo($equipo_torneoid){
			$tabla="";
			$estado = "";
			$texto = "";
			$boton = "";
			$consulta_datos="SELECT equipo_id, equipo_nombre, equipo_torneoid, torneo_nombre, equipo_categoria,
								CASE WHEN equipo_estado='A' THEN 'Activo' 
									 WHEN equipo_estado = 'I' THEN 'Inactivo' 
									 ELSE equipo_estado 
								END AS ESTADO 
							 FROM torneo_equipo, torneo_torneo
							 WHERE equipo_torneoid = ".$equipo_torneoid."
							 	AND torneo_id = equipo_torneoid
							 	AND equipo_estado IN ('A','I')
							 ORDER BY equipo_fechaactualizado ASC";	
					
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
						<td>'.$rows['torneo_nombre'].'</td>
						<td>'.$rows['equipo_nombre'].'</td>
						<td>'.$rows['equipo_categoria'].'</td>
						<td>'.$estado.'</td>
						<td>
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/equipoAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_equipo" value="eliminar">
								<input type="hidden" name="equipo_id" value="'.$rows['equipo_id'].'">						
								<button type="submit" class="btn float-right btn-danger btn-xs" style="margin-right: 3px;">Eliminar</button>
							</form>
							
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/equipoAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_equipo" value="actualizarestado">
								<input type="hidden" name="equipo_id" value="'.$rows['equipo_id'].'">						
								<button type="submit" class="btn float-right '.$boton.' btn-xs" style="margin-right: 3px;""> '.$texto.' </button>
							</form>

							<a href="'.APP_URL.'equipoList/'.$equipo_torneoid.'/'.$rows['equipo_id'].'/" class="btn float-right btn-success btn-xs" style="margin-right: 3px;">Editar</a>
							<a href="'.APP_URL.'jugadorList/'.$rows['equipo_id'].'/" class="btn float-right btn-primary btn-xs" style="margin-right: 3px;">Jugadores</a>
						</td>
					</tr>';	
			}
			return $tabla;
		}

		public function BuscarTorneoEquipo($equipo_torneoid){		
			$consulta_datos=("SELECT torneo_id, torneo_nombre
							 FROM torneo_torneo
							 WHERE torneo_id =".$equipo_torneoid);	

			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
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
		public function actualizarEquipoControlador(){

			$equipoid=$this->limpiarCadena($_POST['equipo_id']);

			# Verificando existencia de equipo #
			$equipo=$this->ejecutarConsulta("SELECT * FROM torneo_equipo WHERE equipo_id='$equipoid'");
			if($equipo->rowCount()<=0){	
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"El equipo no se encuentra en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);		    
			}else{
				$equipo=$equipo->fetch();
				$equipo_estado 		= $equipo['equipo_estado'];
				$equipo_torneoid	= $equipo['equipo_torneoid'];
			}	
			
			# Almacenando datos#
			$equipo_nombre		= $this->limpiarCadena($_POST['equipo_nombre']);			
			$equipo_categoria	= $this->limpiarCadena($_POST['equipo_categoria']);
			
			# Verificando campos obligatorios #
			if($equipo_nombre=="" || $equipo_categoria==""){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No ha completado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}

			$equipo_datos_reg=[
				[
					"campo_nombre"=>"equipo_torneoid",
					"campo_marcador"=>":TorneoEquipo",
					"campo_valor"=>$equipo_torneoid
				],
				[
					"campo_nombre"=>"equipo_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$equipo_nombre
				],
				[
					"campo_nombre"=>"equipo_categoria",
					"campo_marcador"=>":Categoria",
					"campo_valor"=>$equipo_categoria
				],		
				[
					"campo_nombre"=>"equipo_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>$equipo_estado
				],
				[
					"campo_nombre"=>"equipo_fechaactualizado",
					"campo_marcador"=>":Actualizado",
					"campo_valor"=>date("Y-m-d H:i:s")
				]
			];
			
			# Directorio de imagenes #
			$img_dir="../views/imagenes/fotos/equipos/";
			$codigo=rand(0,100);

			# Comprobar si se selecciono una imagen #
			if($_FILES['equipo_foto']['name']!="" && $_FILES['equipo_foto']['size']>0){

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
				if(mime_content_type($_FILES['equipo_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['equipo_foto']['tmp_name'])!="image/png"){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"La imagen que ha seleccionado es de un formato no permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Verificando peso de imagen #
				if(($_FILES['equipo_foto']['size']/1024)>4000){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"Imagen seleccionada supera el peso permitido 4MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
				}

				# Nombre de la foto #
				$foto=str_ireplace(" ","_",$equipo_nombre);
				$foto=$foto."_".$codigo;

				# Extension de la imagen #
				switch(mime_content_type($_FILES['equipo_foto']['tmp_name'])){
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
				$inputFile = ($_FILES['equipo_foto']['tmp_name']);
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
				if(is_file($img_dir.$equipo['equipo_foto']) && $equipo['equipo_foto']!=$foto){
					chmod($img_dir.$equipo['equipo_foto'], 0777);
					unlink($img_dir.$equipo['equipo_foto']);
				}	

				$equipo_datos_reg[] = [
					"campo_nombre"=>"equipo_foto",
					"campo_marcador"=>":Foto",
					"campo_valor"=>$foto
				];
			}

			$condicion=[
				"condicion_campo"=>"equipo_id",
				"condicion_marcador"=>":Equipoid",
				"condicion_valor"=>$equipoid
			];

			if($this->actualizarDatos("torneo_equipo",$equipo_datos_reg,$condicion)){					
				
				$alerta=[
					"tipo"=>"redireccionar",
					"url"=>APP_URL.'equipoList/'.$equipo_torneoid.'/'.$equipoid,
					"titulo"=>"Equipo actualizado",
					"texto"=>"El equipo ".$equipo_nombre." se actualizó correctamente",
					"icono"=>"success"
				];
			}else{				
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No fue posible actualizar el equipo, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
        }

		public function actualizarEstadoEquipoControlador(){
			$equipoid=$this->limpiarCadena($_POST['equipo_id']);

			# Verificando equipo #
			$equipo=$this->ejecutarConsulta("SELECT * FROM torneo_equipo WHERE equipo_id='$equipoid'");
			if($equipo->rowCount()<=0){	
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"El equipo no se encuentra en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }else{
		    	$equipo=$equipo->fetch();
		    }
			if($equipo['equipo_estado']=='A'){
				$estadoA = 'I';
			}else{
				$estadoA = 'A';
			}
            $equipo_datos_up=[
				[
					"campo_nombre"=>"equipo_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=> $estadoA
				]
			];
			$condicion=[
				"condicion_campo"=>"equipo_id",
				"condicion_marcador"=>":Equipoid",
				"condicion_valor"=>$equipoid
			];

			if($this->actualizarDatos("torneo_equipo",$equipo_datos_up,$condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Estado actualizado correctamente",
					"texto"=>"El estado del equipo ".$equipo['equipo_nombre']." fue actualizado correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No hemos podido actualizar el estado del torneo ".$equipo['equipo_nombre']." por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
		}

		/*----------  Controlador eliminar equipo  ----------*/
		public function eliminarEquipoControlador(){

			$equipoid=$this->limpiarCadena($_POST['equipo_id']);
			# Verificando usuario #
			$equipo=$this->ejecutarConsulta("SELECT * FROM torneo_equipo WHERE equipo_id='$equipoid'");
			if($equipo->rowCount()<=0){	
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"El equipo no se encuentra en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}else{
				$equipo=$equipo->fetch();
				$equipo_torneoid	= $equipo['equipo_torneoid'];
			}
			if($equipo['equipo_estado']=='A' || $equipo['equipo_estado']=='I'){
				$estadoA = 'E';
			}else{
				$estadoA = 'X';
			}
			$equipo_datos_up=[
				[
					"campo_nombre"=>"equipo_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=> $estadoA
				]
			];
			$condicion=[
				"condicion_campo"=>"equipo_id",
				"condicion_marcador"=>":Equipoid",
				"condicion_valor"=>$equipoid
			];

			if($this->actualizarDatos("torneo_equipo",$equipo_datos_up,$condicion)){

				$alerta=[
					"tipo"=>"redireccionar",
					"url"=>APP_URL.'equipoList/'.$equipo_torneoid.'/'.$equipoid,
					"titulo"=>"Equipo eliminado",
					"texto"=>"El equipo ".$equipo['equipo_nombre']." se eliminó correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No hemos podido eliminar el equipo ".$equipo['equipo_nombre']." por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			return json_encode($alerta);
		}

		public function BuscarEquipoJugador($equipo_torneoid){		
			$consulta_datos=("SELECT torneo_id, torneo_nombre
							 FROM torneo_torneo
							 WHERE torneo_id =".$equipo_torneoid);	

			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
		}
    }