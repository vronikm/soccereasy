<?php

	namespace app\controllers;
	use app\models\mainModel;

	class tablasController extends mainModel{

		
		public function registrarTablaControlador(){							
			
			# Almacenando datos#
			$tabla_nombre 	= $this->limpiarCadena($_POST['tabla_nombre']);
			
			# Verificando campos obligatorios #
		    if($tabla_nombre==""){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No ha completado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);       
		    }		

			$tabla_datos_reg=[
				[
					"campo_nombre"=>"tabla_nombre",
					"campo_marcador"=>":Tablanombre",
					"campo_valor"=>$tabla_nombre
				],			
				[
					"campo_nombre"=>"tabla_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>true
				]
			];		

			$registrar_tabla=$this->guardarDatos("general_tabla",$tabla_datos_reg);

			if($registrar_tabla->rowCount()>0){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Registro de tabla",
					"texto"=>"La tabla se registró correctamente",
					"icono"=>"success"
				];				
			
			}else{				

				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No se pudo registrar la tabla, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);

		}

		public function listarTablas(){ 
			
			$tabla="";
		
			$consulta_datos="SELECT tabla_id, 
									tabla_nombre, 
									CASE tabla_estado when 1 then 'Activa' when 0 then 'Inactiva' else tabla_estado end as ESTADO_TABLA
									FROM general_tabla G 
									where G.tabla_estado != 0
									ORDER BY tabla_id ASC";	

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
			
				$tabla.='
					<tr>
						<td>'.$rows['tabla_id'].'</td>
						<td>'.$rows['tabla_nombre'].'</td>
						<td>'.$rows['ESTADO_TABLA'].'</td>
						<td>
							<form class="FormularioAjax" action="'.APP_URL.'app/ajax/tablasAjax.php" method="POST" autocomplete="off" >
								<input type="hidden" name="modulo_tablas" value="eliminar">
								<input type="hidden" name="tabla_id" value="'.$rows['tabla_id'].'">						
								<button type="submit" class="btn float-right btn-danger btn-sm" style="margin-right: 5px;">Eliminar</button>
							</form>							

							<a href="'.APP_URL.'tablasNew/'.$rows['tabla_id'].'/" class="btn float-right btn-success btn-sm" style="margin-right: 5px;" >Editar</a>
							
						</td>
					</tr>';	
			}
			return $tabla;			
		}

		public function actualizarTablaControlador(){
			
			$tablaid=$this->limpiarCadena($_POST['tabla_id']);

			# Verificando pago #

			$datos = $this->ejecutarConsulta("SELECT * FROM general_tabla WHERE tabla_id = '$tablaid'");			
			if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No hemos encontrado la tabla en el sistema: ".$tablaid,
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }else{
		    	$datos=$datos->fetch();				
		    }				

			# Almacenando datos#
			$tabla_nombre 	= $this->limpiarCadena($_POST['tabla_nombre']);
			
			# Verificando campos obligatorios #
		    if($tabla_nombre==""){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No ha completado todos los campos obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        
		    }			

			$tabla_datos_reg=[
				[
					"campo_nombre"=>"tabla_nombre",
					"campo_marcador"=>":Tablanombre",
					"campo_valor"=>$tabla_nombre
				]
			];			
		
			$condicion=[
				"condicion_campo"=>"tabla_id",
				"condicion_marcador"=>":tablaid",
				"condicion_valor"=>$tablaid
			];			

			if($this->actualizarDatos("general_tabla",$tabla_datos_reg,$condicion)){				
				
				$alerta=[
					"tipo"=>"redireccionar",			
					"url"=>APP_URL.'tablasNew/',					
					"titulo"=>"Tabla actualizada",
					"texto"=>"Los datos de la tabla ".$tabla_nombre." se actualizaron correctamente",
					"icono"=>"success"	
				];								

			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No hemos podido actualizar los datos de la tabla ".$tabla_nombre.", por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}

		public function BuscarTabla($tablaid){
		
			$consulta_datos="SELECT G.* 
					FROM general_tabla G										
					WHERE tabla_id = ".$tablaid;	

			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
		}

		public function eliminarTablaControlador(){
			
			$tablaid=$this->limpiarCadena($_POST['tabla_id']);

			$tabla_datos=[
				[
					"campo_nombre"=>"tabla_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=> false
				]
			];

			$condicion=[
				"condicion_campo"=>"tabla_id",
				"condicion_marcador"=>":tablaid",
				"condicion_valor"=>$tablaid
			];

			if($this->actualizarDatos("general_tabla", $tabla_datos, $condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Tabla eliminada",
					"texto"=>"La tabla fue eliminada correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No fue posible eliminar la tabla, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}
	}
			