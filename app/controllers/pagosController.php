<?php

	namespace app\controllers;
	use app\models\mainModel;

	class pagosController extends mainModel{

		public function listarAlumnosPagos($identificacion, $apellidopaterno, $primernombre, $anio, $sede){
					
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
				$consulta_datos = "SELECT * FROM sujeto_alumno WHERE alumno_primernombre = '' ";
			}			

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				
				$consulta_descuento = "SELECT descuento_alumnoid FROM alumno_pago_descuento WHERE descuento_alumnoid = ".$rows['alumno_id'];
				$descuento = $this->ejecutarConsulta($consulta_descuento);
				if($descuento->rowCount()==1){
					$boton = "btn-info";
				}else{
					$boton = "btn-secondary";
				}
				
				$consulta_pagos = "SELECT pago_id FROM alumno_pago WHERE pago_alumnoid = ".$rows['alumno_id'];
				$pagos = $this->ejecutarConsulta($consulta_pagos);
				if($pagos->rowCount()>0){
					$botonpago = "btn-info";
				}else{
					$botonpago = "btn-secondary";
				}
				
				$tabla.='
					<tr>
						<td>'.$rows['alumno_identificacion'].'</td>
						<td>'.$rows['alumno_primernombre'].' '.$rows['alumno_segundonombre'].'</td>
						<td>'.$rows['alumno_apellidopaterno'].' '.$rows['alumno_apellidomaterno'].'</td>
						<td>'.$rows['alumno_fechanacimiento'].'</td>
						<td>
							<a href="'.APP_URL.'pagosNew/'.$rows['alumno_id'].'/" class="btn float-right '.$botonpago.' btn-sm" target="_blank">Registrar pagos</a>
							<a href="'.APP_URL.'pagosDescuento/'.$rows['alumno_id'].'/" class="btn float-right '.$boton.' btn-sm" style="margin-right: 5px;" target="_blank">Descuentos</a>
						</td>
					</tr>';	
			}
			return $tabla;			
		}

		public function listarOptionSede($sedeid){
			$option="";

			$consulta_datos="SELECT sede_id, sede_nombre FROM general_sede";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($sedeid == $rows['sede_id']){	
					$option.='<option value='.$rows['sede_id'].' selected="selected">'.$rows['sede_nombre'].'</option>';
				}else{
					$option.='<option value='.$rows['sede_id'].'>'.$rows['sede_nombre'].'</option>';
				}
					
			}
			return $option;
		}

		/* ---------------------------Freddy----------------------- */
		public function BuscarAlumno($alumnoid){
		
			$consulta_datos="SELECT S.sede_nombre, P.catalogo_descripcion, Year(alumno_fechanacimiento) anio
					,CASE WHEN IFNULL(R.total, 0) > 0 THEN 1 ELSE 0 END pendiente,  IFNULL(R.total, 0) total
					,A.* 
					FROM sujeto_alumno A
					LEFT JOIN general_sede S ON S.sede_id = A.alumno_sedeid
					LEFT JOIN general_tabla_catalogo P ON P.catalogo_valor = A.alumno_posicionid
					LEFT JOIN(
						SELECT COUNT(RP.pago_id) total, RA.alumno_id alumno
						FROM sujeto_alumno RA
						INNER JOIN alumno_pago RP ON RP.pago_alumnoid = RA.alumno_id
						WHERE RP.pago_estado = 'P'
						GROUP BY RA.alumno_id
					)R ON R.alumno = A.alumno_id
				WHERE A.alumno_id = ".$alumnoid;	
			/*
			$consulta_datos="SELECT S.sede_nombre, P.catalogo_descripcion, Year(alumno_fechanacimiento) ann, A.* 
				FROM sujeto_alumno A
					LEFT JOIN general_sede S ON S.sede_id = A.alumno_sedeid
					LEFT JOIN general_tabla_catalogo P ON P.catalogo_valor = A.alumno_posicionid
				WHERE A.alumno_id = ".$alumnoid;	
			*/

			$datos = $this->ejecutarConsulta($consulta_datos);			
			
			return $datos;
		}
		
		public function BuscarAlumnoDescuento($alumnoid){
		
			$consulta_datos="SELECT S.sede_nombre, P.catalogo_descripcion, Year(alumno_fechanacimiento) anio
					,A.*
					FROM sujeto_alumno A
					LEFT JOIN general_sede S ON S.sede_id = A.alumno_sedeid
					LEFT JOIN general_tabla_catalogo P ON P.catalogo_valor = A.alumno_posicionid					
				WHERE A.alumno_id = ".$alumnoid;
				
			$datos = $this->ejecutarConsulta($consulta_datos);			
			
			return $datos;
		}

		public function informacionRepresentante($alumnoid){		
			$consulta_datos="SELECT repre_correo FROM alumno_representante WHERE repre_alumnoid = ".$alumnoid;
			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
		}
		
		public function AlumnoDescuento($alumnoid){
		
			$consulta_datos="SELECT D.* FROM alumno_pago_descuento D					
							 INNER JOIN general_tabla_catalogo RD ON RD.catalogo_valor = D.descuento_rubroid 
							 WHERE D.descuento_alumnoid  = ".$alumnoid ." AND D.descuento_estado = 'S' ";
				
			$datos = $this->ejecutarConsulta($consulta_datos);			
			
			return $datos;
		}
		
		public function BuscarDescuento($alumnoid){
		
			$consulta_datos="SELECT D.* FROM alumno_pago_descuento D 
				WHERE D.descuento_alumnoid = ".$alumnoid;
				
			$datos = $this->ejecutarConsulta($consulta_datos);			
			
			return $datos;
		}
		
		public function pagosPendintes($alumnoid){
			$tabla="";
			$consulta_datos="SELECT C.catalogo_descripcion rubro, P.pago_periodo periodo
							,P.pago_saldo saldo
							FROM sujeto_alumno A
							INNER JOIN alumno_pago P ON P.pago_alumnoid = A.alumno_id
							INNER JOIN general_tabla_catalogo C ON C.catalogo_valor = P.pago_rubroid
						WHERE P.pago_estado = 'P' AND A.alumno_id = ".$alumnoid;	
			
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){		
				$tabla.='
					<tr style="font-size: 14px" class="text-danger">
						<td>'.$rows['rubro'].'</td>
						<td>'.$rows['periodo'].'</td>
						<td>'.$rows['saldo'].'</td>
						</td>
					</tr>';						
			}
			return $tabla;
		}

		public function pensionesPendintes($alumnoid){
			$descuento=$this->AlumnoDescuento($alumnoid);
			if($descuento->rowCount()==1){
				$descuento=$descuento->fetch(); 
				if($descuento["descuento_rubroid"] == 'DDS'){					
					$valor_pension = $descuento["descuento_valor"];
								
				}
			}else{
				$check_pension=$this->ejecutarConsulta("SELECT escuela_pension FROM general_escuela WHERE escuela_id = 1");
				$valor_pension="";
				if($check_pension->rowCount()>0){				
					foreach($check_pension as $rows){	
						$valor_pension = $rows["escuela_pension"]; 					
					}
				}
			}

			$tabla="";
			$ultimafecha = "SELECT MAX(P.pago_fecha) fecha
			FROM sujeto_alumno A
			INNER JOIN alumno_pago P ON P.pago_alumnoid = A.alumno_id
			WHERE P.pago_rubroid = 'RPE' AND pago_estado != 'E' AND A.alumno_id = ".$alumnoid;

			$datos = $this->ejecutarConsulta($ultimafecha);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){	
				$ultimafecha = $rows["fecha"];
			}	

			$fecha_final =  date('Y-m-d');
			// Convertir la fecha a un timestamp
			$timestamp = strtotime($fecha_final);
			// Obtener el �ltimo d�a del mes de la fecha dada
			$ultimo_dia = date('Y-m-t', $timestamp);

			$consulta_datos="SELECT CONCAT(
					CASE DATE_FORMAT(DATE_ADD('".$ultimafecha."', INTERVAL (units.i + tens.i * 10 + 1) MONTH), '%m')
						WHEN '01' THEN 'Enero'
						WHEN '02' THEN 'Febrero'
						WHEN '03' THEN 'Marzo'
						WHEN '04' THEN 'Abril'
						WHEN '05' THEN 'Mayo'
						WHEN '06' THEN 'Junio'
						WHEN '07' THEN 'Julio'
						WHEN '08' THEN 'Agosto'
						WHEN '09' THEN 'Septiembre'
						WHEN '10' THEN 'Octubre'
						WHEN '11' THEN 'Noviembre'
						WHEN '12' THEN 'Diciembre'
					END, ' / ', DATE_FORMAT(DATE_ADD('2024-03-04', INTERVAL (units.i + tens.i * 10 + 1) MONTH), '%Y')
					) AS mes    
				FROM ( SELECT 0 AS i UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) AS units  
				JOIN ( SELECT 0 AS i UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) AS tens 
				WHERE DATE_ADD('".$ultimafecha."', INTERVAL (units.i + tens.i * 10 + 1) MONTH) <= '".$ultimo_dia."' ORDER BY mes;";	
			
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){					
				$tabla.='
					<tr style="font-size: 14px"  class="text-danger">
						<td>Pensión</td>
						<td>'.$rows['mes'].'</td>
						<td>'.$valor_pension.'</td>
						</td>
					</tr>';							
			}
			return $tabla;
		}

		public function listarOptionPago(){
			$option="";

			$consulta_datos="SELECT *FROM general_tabla_catalogo WHERE catalogo_tablaid = 6 AND catalogo_estado = 1";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){			
				$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';					
			}
			return $option;
		}

		public function listarOptionDescuento($descuento_rubroid){
			$option="";

			$consulta_datos="SELECT *FROM general_tabla_catalogo WHERE catalogo_tablaid = 7 AND catalogo_estado = 1";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){			
				if($descuento_rubroid == $rows['catalogo_valor']){
					$option.='<option value='.$rows['catalogo_valor'].' selected="selected">'.$rows['catalogo_descripcion'].'</option>';	
				}else{
					$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';	
				}									
			}
			return $option;
		}

		public function listarOptionRubro(){
			$option="";

			$consulta_datos="SELECT *FROM general_tabla_catalogo WHERE catalogo_tablaid = 5 AND catalogo_estado = 1";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){			
				$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';					
			}
			return $option;
		}

		public function registrarPagoControlador(){
							
			/*
			pago_id, pago_rubroid, pago_formapagoid, pago_alumnoid, pago_valor, pago_saldo, 
			pago_concepto, pago_fecha, pago_fecharegistro, pago_periodo, pago_estado, pago_archivo,
			*/		
			//if(isset($_POST['pago_alumnoid'])){ $pago_alumnoid  = $this->limpiarCadena($_POST['pago_alumnoid']); }else {$pago_alumnoid  = "";}
			

			# Almacenando datos#
			$pago_alumnoid 		= $this->limpiarCadena($_POST['pago_alumnoid']);
			$pago_fecha			= $this->limpiarCadena($_POST['pago_fecha']);
			$pago_fecharegistro	= $this->limpiarCadena($_POST['pago_fecharegistro']);
			$pago_periodo 		= $this->limpiarCadena($_POST['pago_periodo']);
			$pago_valor 		= $_POST['pago_valor'];
			$pago_saldo 		= $_POST['pago_saldo'];
			$pago_formapagoid 	= $this->limpiarCadena($_POST['pago_formapagoid']);
			$pago_concepto 		= $this->limpiarCadena($_POST['pago_concepto']);			
			$pago_rubro			= $this->limpiarCadena($_POST['pago_rubro']);
			$rubroid 			= ""; 
			
			if ($pago_valor =="") {$pago_valor = 0;}
			if ($pago_saldo =="") {$pago_saldo = 0;}

			if($pago_rubro == "pension"){
				$rubroid="RPE";			
			}elseif($pago_rubro == "inscripcion"){
				$rubroid="RIN";				
			} elseif($pago_rubro == "uniforme"){
				$rubroid="RNU";				
			}elseif($pago_rubro == "kit"){
				$rubroid="RKE";				
			}elseif($pago_rubro == "otros"){
				$rubroid="ROT";				
			}	
			
			if($pago_valor < 1 && $pago_saldo < 1){
				$estado = "J";
			}elseif($pago_saldo != "" && $pago_saldo > 0){
				$estado = "P";
			}else{
				$estado = "C";
			}
			

			# Verificando campos obligatorios #
		    if($pago_fecha=="" || $pago_fecharegistro=="" || $pago_periodo=="" || $pago_valor=="" || $pago_formapagoid=="" ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        
		    }

			# Directorio de imagenes #
			$img_dir="../views/imagenes/pagos/";

			# Comprobar si se selecciono una imagen #
    		if($_FILES['pago_archivo']['name']!="" && $_FILES['pago_archivo']['size']>0){

    			# Creando directorio #
		        if(!file_exists($img_dir)){
		            if(!mkdir($img_dir,0777)){
		            	$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error inesperado",
							"texto"=>"Error al crear el directorio",
							"icono"=>"error"
						];
						return json_encode($alerta);
		                //exit();
		            } 
		        }

		        # Verificando formato de imagenes #
		        if(mime_content_type($_FILES['pago_archivo']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['pago_archivo']['tmp_name'])!="image/png"){
		        	$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"La imagen que ha seleccionado es de un formato no permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
		            //exit();
		        }

		        # Verificando peso de imagen #
		        if(($_FILES['pago_archivo']['size']/1024)>3000){
		        	$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"La imagen que ha seleccionado supera el peso permitido 3MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
		            //exit();
		        }

		        # Nombre de la foto #
		        $foto=str_ireplace(" ","_",$pago_alumnoid);
		        $foto=$foto."_".rand(0,100);

		        # Extension de la imagen #
		        switch(mime_content_type($_FILES['pago_archivo']['tmp_name'])){
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
				$inputFile = ($_FILES['pago_archivo']['tmp_name']);
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
			

			$check_recibo=$this->ejecutarConsulta("SELECT escuela_recibo FROM general_escuela WHERE escuela_id = 1");
			//$check_recibo=$this->seleccionarDatos("Unico","general_escuela","escuela_id","1");
			if($check_recibo->rowCount()>0){				
				foreach($check_recibo as $rows){	
					$num_recibo = $rows["escuela_recibo"] + 1; 					
				}
				
				// Establecer la zona horaria de Ecuador
				date_default_timezone_set('America/Guayaquil');

				// Obtener la fecha y hora actual
				$fecha_actual = date('Y-m-d H:i:s');

				// Descomponer la fecha y hora en sus componentes individuales
				$anio = date('y');
				$mes = date('m');
				$dia = date('d');
				$hora = date('H');
				$minuto = date('i');
				$segundo = date('s');

				// Generar n�mero de recibo con fecha y hora al rev�s
				$numero_recibo = $segundo . $anio . $minuto . $mes . $hora . $dia;
				$pago_recibo = strrev($numero_recibo)."".$num_recibo;	
				
			}
			else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No se puede generar el recibo en este momento",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}

			$pago_datos_reg=[
				[
					"campo_nombre"=>"pago_rubroid",
					"campo_marcador"=>":Rubroid",
					"campo_valor"=>$rubroid
				],
				[
					"campo_nombre"=>"pago_formapagoid",
					"campo_marcador"=>":Formapagoid",
					"campo_valor"=>$pago_formapagoid
				],				
				[
					"campo_nombre"=>"pago_alumnoid",
					"campo_marcador"=>":Alumnoid",
					"campo_valor"=>$pago_alumnoid
				],				
				[
					"campo_nombre"=>"pago_valor",
					"campo_marcador"=>":Valor",
					"campo_valor"=>$pago_valor
				],
				[
					"campo_nombre"=>"pago_saldo",
					"campo_marcador"=>":Saldo",
					"campo_valor"=>$pago_saldo
				],		
				[
					"campo_nombre"=>"pago_concepto",
					"campo_marcador"=>":Concepto",
					"campo_valor"=>$pago_concepto
				],
				[
					"campo_nombre"=>"pago_fecha",
					"campo_marcador"=>":Fecha",
					"campo_valor"=>$pago_fecha
				],				
				[
					"campo_nombre"=>"pago_fecharegistro",
					"campo_marcador"=>":Fecharegistro",
					"campo_valor"=>$pago_fecharegistro
				],
				[
					"campo_nombre"=>"pago_periodo",
					"campo_marcador"=>":Periodo",
					"campo_valor"=>$pago_periodo
				],				
				[
					"campo_nombre"=>"pago_recibo",
					"campo_marcador"=>":Recibo",
					"campo_valor"=>$pago_recibo
				],
				[
					"campo_nombre"=>"pago_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>$estado 
				],
				[
					"campo_nombre"=>"pago_archivo",
					"campo_marcador"=>":Imagenpago",
					"campo_valor"=>$foto
				]
			];		

			$registrar_pago=$this->guardarDatos("alumno_pago",$pago_datos_reg);

			if($registrar_pago->rowCount()>0){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Registro de pagos",
					"texto"=>"El pago se registró correctamente",
					"icono"=>"success"
				];

				// Actualizar numero de recibo
				$this->ejecutarConsulta("UPDATE general_escuela SET escuela_recibo = ".$num_recibo." WHERE escuela_id = 1");
			
			}else{
				
				if(is_file($img_dir.$foto)){
		            chmod($img_dir.$foto,0777);
		            unlink($img_dir.$foto);
		        }

				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar el pago, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);

		}

		public function registrarPagoPendiente(){		
							
			/*
			tabla alumno_pago_transaccion

			transaccion_id, transaccion_pagoid, transaccion_valorcalculado, transaccion_valor, transaccion_saldo,
			transaccion_fecha, transaccion_fecharegistro, transaccion_formapagoid, transaccion_concepto,
			transaccion_periodo, transaccion_estado, transaccion_archivo
			
			*/
			
			//if(isset($_POST['pago_alumnoid'])){ $pago_alumnoid  = $this->limpiarCadena($_POST['pago_alumnoid']); }else {$pago_alumnoid  = "";}
			
			# Almacenando datos#
			$transaccion_pagoid			= $this->limpiarCadena($_POST['pago_id']);
			$total						= $this->limpiarCadena($_POST['pago_total']);
			$transaccion_valorcalculado	= $this->limpiarCadena($_POST['pago_saldo']);
			$transaccion_fecha			= $this->limpiarCadena($_POST['pago_fecha']);
			$transaccion_fecharegistro	= $this->limpiarCadena($_POST['pago_fecharegistro']);
			$transaccion_periodo 		= $this->limpiarCadena($_POST['pago_periodo']);			
			$transaccion_valor 			= $_POST['pago_valor'];
			$transaccion_formapagoid 	= $this->limpiarCadena($_POST['pago_formapagoid']);
			$transaccion_concepto 		= $this->limpiarCadena($_POST['pago_concepto']);			
			$pago_rubro					= $this->limpiarCadena($_POST['pago_rubro']);
			$estado 					= "C";

			// Actualizar saldo del rubro				
			$saldo = $transaccion_valorcalculado - $transaccion_valor;
			$total += $transaccion_valor;

			if ($saldo == 0){
				$estado_saldo='C';
			}else{
				$estado_saldo = 'P';
			}
			
			# Verificando campos obligatorios #
		    if($transaccion_fecha=="" || $transaccion_fecharegistro=="" || $transaccion_periodo=="" || $transaccion_valorcalculado=="" || $transaccion_valor=="" ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        
		    }

			if($saldo < 0 ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error en el valor ingresado",
					"texto"=>"El valor ingresado supera el monto del saldo",
					"icono"=>"error"
				];
				return json_encode($alerta);		        
		    }

			# Directorio de imagenes #
			$img_dir="../views/imagenes/pagos/";

			# Comprobar si se selecciono una imagen #
    		if($_FILES['pago_archivo']['name']!="" && $_FILES['pago_archivo']['size']>0){

    			# Creando directorio #
		        if(!file_exists($img_dir)){
		            if(!mkdir($img_dir,0777)){
		            	$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error inesperado",
							"texto"=>"Error al crear el directorio",
							"icono"=>"error"
						];
						return json_encode($alerta);
		                //exit();
		            } 
		        }

		        # Verificando formato de imagenes #
		        if(mime_content_type($_FILES['pago_archivo']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['pago_archivo']['tmp_name'])!="image/png"){
		        	$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"La imagen que ha seleccionado es de un formato no permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
		            //exit();
		        }

		        # Verificando peso de imagen #
		        if(($_FILES['pago_archivo']['size']/1024)>3000){
		        	$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"La imagen que ha seleccionado supera el peso permitido 3MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
		            //exit();
		        }

		        # Nombre de la foto #
		        $foto=str_ireplace(" ","_","pagopendiente".$transaccion_pagoid);
		        $foto=$foto."_".rand(0,100);

		        # Extension de la imagen #
		        switch(mime_content_type($_FILES['pago_archivo']['tmp_name'])){
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
				$inputFile = ($_FILES['pago_archivo']['tmp_name']);
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
			
			$check_recibo=$this->ejecutarConsulta("SELECT escuela_recibo FROM general_escuela WHERE escuela_id = 1");
			//$check_recibo=$this->seleccionarDatos("Unico","general_escuela","escuela_id","1");
			if($check_recibo->rowCount()>0){				
				foreach($check_recibo as $rows){	
					$num_recibo = $rows["escuela_recibo"] + 1; 					
				}
				
				// Establecer la zona horaria de Ecuador
				date_default_timezone_set('America/Guayaquil');

				// Obtener la fecha y hora actual
				$fecha_actual = date('Y-m-d H:i:s');

				// Descomponer la fecha y hora en sus componentes individuales
				$anio = date('y');
				$mes = date('m');
				$dia = date('d');
				$hora = date('H');
				$minuto = date('i');
				$segundo = date('s');

				// Generar número de recibo con fecha y hora al revés
				$numero_recibo = $segundo . $anio . $minuto . $mes . $hora . $dia;
				$pago_recibo = strrev($numero_recibo)."".$num_recibo;	
				
			}
			else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No se puede generar el recibo en este momento",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}
									
			$pago_datos_reg=[
				[
					"campo_nombre"=>"transaccion_pagoid",
					"campo_marcador"=>":Pagoid",
					"campo_valor"=>$transaccion_pagoid
				],							
				[
					"campo_nombre"=>"transaccion_valorcalculado",
					"campo_marcador"=>":Valorcalculado",
					"campo_valor"=>$transaccion_valorcalculado
				],				
				[
					"campo_nombre"=>"transaccion_valor",
					"campo_marcador"=>":Valor",
					"campo_valor"=>$transaccion_valor
				],
				[
					"campo_nombre"=>"transaccion_fecha",
					"campo_marcador"=>":Fecha",
					"campo_valor"=>$transaccion_fecha
				],				
				[
					"campo_nombre"=>"transaccion_fecharegistro",
					"campo_marcador"=>":transaccion_fecharegistro",
					"campo_valor"=>$transaccion_fecharegistro
				],		
				[
					"campo_nombre"=>"transaccion_formapagoid",
					"campo_marcador"=>":Formapagoid",
					"campo_valor"=>$transaccion_formapagoid
				],	
				[
					"campo_nombre"=>"transaccion_concepto",
					"campo_marcador"=>":Concepto",
					"campo_valor"=>$transaccion_concepto
				],
				
				[
					"campo_nombre"=>"transaccion_periodo",
					"campo_marcador"=>":Periodo",
					"campo_valor"=>$transaccion_periodo
				],
				[
					"campo_nombre"=>"transaccion_recibo",
					"campo_marcador"=>":Recibo",
					"campo_valor"=>$pago_recibo
				],
				[
					"campo_nombre"=>"transaccion_estado",
					"campo_marcador"=>":transaccion_estado",
					"campo_valor"=>$estado
				],
				[
					"campo_nombre"=>"transaccion_archivo",
					"campo_marcador"=>":Imagenpago",
					"campo_valor"=>$foto
				]
			];		

			$registrar_pago=$this->guardarDatos("alumno_pago_transaccion",$pago_datos_reg);

			if($registrar_pago->rowCount()>0){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Registro de pago pendiente",
					"texto"=>"El pago se registró correctamente",
					"icono"=>"success"
				];
				
				
				// Actualizar saldo y valor
				$this->ejecutarConsulta("UPDATE alumno_pago SET pago_valor = ".$total.", pago_saldo = ".$saldo.", pago_estado = '".$estado_saldo."' WHERE pago_id = ".$transaccion_pagoid);
			

				// Actualizar numero de recibo
				$this->ejecutarConsulta("UPDATE general_escuela SET escuela_recibo = ".$num_recibo." WHERE escuela_id = 1");
				
			}else{
				
				if(is_file($img_dir.$foto)){
		            chmod($img_dir.$foto,0777);
		            unlink($img_dir.$foto);
		        }

				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar el pago, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);

		}

		public function registrarDescuento(){
							
			/*
			pago_id, pago_rubroid, pago_formapagoid, pago_alumnoid, pago_valor, pago_saldo, 
			pago_concepto, pago_fecha, pago_fecharegistro, pago_periodo, pago_estado, pago_archivo,
			*/		
			//if(isset($_POST['pago_alumnoid'])){ $pago_alumnoid  = $this->limpiarCadena($_POST['pago_alumnoid']); }else {$pago_alumnoid  = "";}
			

			# Almacenando datos#
			$descuento_alumnoid	= $this->limpiarCadena($_POST['descuento_alumnoid']);
			$descuento_rubroid	= $this->limpiarCadena($_POST['descuento_rubroid']);
			$descuento_valor	= $this->limpiarCadena($_POST['descuento_valor']);
			$descuento_fecha 	= $this->limpiarCadena($_POST['descuento_fecha']);
			$descuento_detalle 	= $this->limpiarCadena($_POST['descuento_detalle']);			
			$descuento_estado 	= $this->limpiarCadena($_POST['descuento_estado']);	
		
			
			if ($descuento_valor =="") {$descuento_valor = 0;}
			
			# Verificando campos obligatorios #
		    if($descuento_rubroid=="" || $descuento_valor=="" || $descuento_fecha=="" || $descuento_estado=="" ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        
		    }			

			$descuento_datos_reg=[
				[
					"campo_nombre"=>"descuento_rubroid",
					"campo_marcador"=>":Rubroid",
					"campo_valor"=>$descuento_rubroid
				],
				[
					"campo_nombre"=>"descuento_alumnoid",
					"campo_marcador"=>":Alumnoid",
					"campo_valor"=>$descuento_alumnoid
				],						
				[
					"campo_nombre"=>"descuento_valor",
					"campo_marcador"=>":Valor",
					"campo_valor"=>$descuento_valor
				],		
				[
					"campo_nombre"=>"descuento_detalle",
					"campo_marcador"=>":Detalle",
					"campo_valor"=>$descuento_detalle
				],
				[
					"campo_nombre"=>"descuento_fecha",
					"campo_marcador"=>":Fecha",
					"campo_valor"=>$descuento_fecha
				],				
				[
					"campo_nombre"=>"descuento_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>$descuento_estado
				]
			];		

			$registrar_descuento=$this->guardarDatos("alumno_pago_descuento",$descuento_datos_reg);

			if($registrar_descuento->rowCount()==1){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Registro de descuento",
					"texto"=>"El descuento se registró correctamente",
					"icono"=>"success"
				];

				// Actualizar numero de recibo
				
			}else{
				
				

				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar el pago, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);

		}

		public function listarPagosPendientes($pagoid){
			
			$tabla="";
			$consulta_datos="SELECT ROW_NUMBER() OVER (ORDER BY PT.transaccion_id) AS fila_numero, PT.*, P.* 
				FROM alumno_pago_transaccion PT
				INNER JOIN alumno_pago P ON P.pago_id = PT.transaccion_pagoid  
				WHERE (PT.transaccion_pagoid  = '".$pagoid."' AND PT.transaccion_estado NOT IN ('E')) ORDER BY transaccion_id DESC";		

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
			
			if ($rows['transaccion_estado'] == 'C'){
				$estado = 'Cancelado';
				$class = '';
			}elseif($rows['transaccion_estado'] == 'P'){
				$estado = '<span class="badge bg-danger"> Pendiente';
				$class = 'class="text-danger"';
			}elseif($rows['transaccion_estado'] == 'J'){
				$estado = ' Justificado';
				$class = 'class="text-primary"';
			}
				
			$tabla.='
				<tr '.$class.'>
					<td>'.$rows['fila_numero'].'</td>
					<td>'.$rows['transaccion_fecha'].'</td>
					<td>'.$rows['transaccion_periodo'].'</td>
					<td>'.$rows['transaccion_valorcalculado'].'</td>					
					<td>'.$rows['transaccion_valor'].'</td>			
					<td>'.$rows['transaccion_recibo'].'</td>	
					<td>
						<form class="FormularioAjax" action="'.APP_URL.'app/ajax/pagosAjax.php" method="POST" autocomplete="off" >
							<input type="hidden" name="modulo_pagos" value="eliminarpendiente">
							<input type="hidden" name="transaccion_id" value="'.$rows['transaccion_id'].'">						
							<button type="submit" class="btn float-right btn-danger btn-sm" style="margin-right: 5px;">Eliminar</button>
						</form>							

						<a href="'.APP_URL.'pagospendienteUpdate/'.$rows['transaccion_id'].'/" class="btn float-right btn-success btn-sm" style="margin-right: 5px;">Editar</a>
						<a href="'.APP_URL.'pagospendienteRecibo/'.$rows['transaccion_id'].'/" class="btn float-right btn-secondary btn-sm" style="margin-right: 5px;">Recibo</a>
					</td>
				</tr>';	
			}
			return $tabla;			
		}

		public function listarPagosRubro($alumnoid, $rubro){ //29052024
			
			$tabla="";
			$eliminarpago="";
			$consulta_datos="SELECT ROW_NUMBER() OVER (ORDER BY pago_id) AS fila_numero, IFNULL(P.PAGOS_PENDIENTES, 0)PAGOS_PENDIENTES , A.* 
				FROM alumno_pago A  
				LEFT JOIN (
					SELECT COUNT(1)PAGOS_PENDIENTES, transaccion_pagoid 
					FROM alumno_pago_transaccion
					GROUP BY transaccion_pagoid
				)P ON P.transaccion_pagoid = A.pago_id 
				WHERE (A.pago_alumnoid = '".$alumnoid."' AND A.pago_rubroid = '".$rubro."' AND A.pago_estado NOT IN ('E')) ORDER BY pago_id DESC";		

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
			
			if ($rows['pago_estado'] == 'C'){
				$estado = 'Cancelado';
				$class = '';
			}elseif($rows['pago_estado'] == 'P'){
				$estado = '<span class="badge bg-danger"> Pendiente';
				$class = 'class="text-danger"';
			}elseif($rows['pago_estado'] == 'J'){
				$estado = ' Justificado';
				$class = 'class="text-primary"';
			}

			if($rows['pago_saldo'] > 0 ){
				$btnPagar = '<a href="'.APP_URL.'pagosPendiente/'.$rows['pago_id'].'/" class="btn float-right btn-info btn-sm" style="margin-right: 5px;">Pagar</a>';
			}elseif($rows['pago_saldo'] == 0 && $rows['PAGOS_PENDIENTES']>0){
				$btnPagar = '<a href="'.APP_URL.'pagosPendiente/'.$rows['pago_id'].'/" class="btn float-right btn-dark btn-sm" style="margin-right: 5px;">Pagos</a>';
			}else{				
				$btnPagar ="";
			}

			if($rows['PAGOS_PENDIENTES']>0){
				$eliminarpago="disabled";
			}else{
				$eliminarpago="";
			}
				
			$tabla.='
				<tr '.$class.'>
					<td>'.$rows['fila_numero'].'</td>
					<td>'.$rows['pago_periodo'].'</td>
					<td>'.$rows['pago_valor'].'</td>
					<td>'.$rows['pago_saldo'].'</td>
					<td>'.$rows['pago_recibo'].'</td>
					<td>'.$estado.'</td>
					<td>
						<form class="FormularioAjax" action="'.APP_URL.'app/ajax/pagosAjax.php" method="POST" autocomplete="off" >
							<input type="hidden" name="modulo_pagos" value="eliminar">
							<input type="hidden" name="pago_id" value="'.$rows['pago_id'].'">						
							<button type="submit" class="btn float-right btn-danger btn-sm " style="margin-right: 5px;" '.$eliminarpago.'>Eliminar</button>
						</form>							

						<a href="'.APP_URL.'pagosUpdate/'.$rows['pago_id'].'/" class="btn float-right btn-success btn-sm '.$eliminarpago.'" style="margin-right: 5px;" >Editar</a>
						'.$btnPagar.'
						<a href="'.APP_URL.'pagosRecibo/'.$rows['pago_id'].'/" class="btn float-right btn-secondary btn-sm" style="margin-right: 5px;" >Recibo</a>
					</td>
				</tr>';	
			}
			return $tabla;			
		}

		public function eliminarPagoControlador(){

			
			$pagoid=$this->limpiarCadena($_POST['pago_id']);

			$pago_datos=[
				[
					"campo_nombre"=>"pago_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=> 'E'
				]
			];

			$condicion=[
				"condicion_campo"=>"pago_id",
				"condicion_marcador"=>":Pagoid",
				"condicion_valor"=>$pagoid
			];

			if($this->actualizarDatos("alumno_pago", $pago_datos, $condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Pago actualizado",
					"texto"=>"La pensión fue eliminada correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido eliminar la pensión, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}

		public function eliminarPagoPendiente(){

			
			$transaccion_id=$this->limpiarCadena($_POST['transaccion_id']);

			$pago_datos=[
				[
					"campo_nombre"=>"transaccion_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=> 'E'
				]
			];

			$condicion=[
				"condicion_campo"=>"transaccion_id",
				"condicion_marcador"=>":Transaccion_id",
				"condicion_valor"=>$transaccion_id
			];

			if($this->actualizarDatos("alumno_pago_transaccion", $pago_datos, $condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Usuario actualizado",
					"texto"=>"La pensión fue eliminada correctamente",
					"icono"=>"success"
				];

				

			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido eliminar la pensión, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}

		public function BuscarPago($pagoid){
		
			$consulta_datos="SELECT  R.catalogo_descripcion RUBRO, P.* 
					FROM alumno_pago P	
						INNER JOIN general_tabla_catalogo R ON R.catalogo_valor = P.pago_rubroid 				
					WHERE P.pago_id = ".$pagoid;	

			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
		}

		public function generarRecibo($pagoid){
		
			/*$consulta_datos="SELECT  R.catalogo_descripcion RUBRO, F.catalogo_descripcion FORMAPAGO, P.*, A.* 
				FROM alumno_pago P	
					INNER JOIN sujeto_alumno A ON A.alumno_id = P.pago_alumnoid
					INNER JOIN general_tabla_catalogo R ON R.catalogo_valor = P.pago_rubroid 
					INNER JOIN general_tabla_catalogo F ON F.catalogo_valor = P.pago_formapagoid
					
					-- falta

				WHERE P.pago_id = ".$pagoid;	*/

				$consulta_datos="SELECT  IFNULL(T.total, 0) TOTAL_PP,
						(P.pago_saldo + P.pago_valor) DEUDA_INICIAL, 
						((P.pago_saldo + P.pago_valor) - (IFNULL(PT.transaccion_valorcalculado, P.pago_saldo)))PAGO_INICIAL, 
						IFNULL(PT.transaccion_valorcalculado, P.pago_saldo) SALDO_INICIAL, 
						R.catalogo_descripcion RUBRO, 
						F.catalogo_descripcion FORMAPAGO,
						concat(E.repre_primernombre, ' ', E.repre_segundonombre, ' ', E.repre_apellidopaterno, ' ', E.repre_apellidomaterno) REPRESENTANTE,
						E.repre_correo CORREO_REP,
						P.pago_periodo,
						P.pago_fecharegistro,
						P.pago_recibo,
						P.pago_archivo,
						P.pago_fecha,
						P.pago_concepto,
						A.alumno_apellidomaterno,
						A.alumno_apellidopaterno,
						A.alumno_primernombre,
						A.alumno_segundonombre,
						A.alumno_fechanacimiento,
						P.*,
						A.*
						FROM alumno_pago P	
							INNER JOIN sujeto_alumno A ON A.alumno_id = P.pago_alumnoid
							INNER JOIN general_tabla_catalogo R ON R.catalogo_valor = P.pago_rubroid 
							INNER JOIN general_tabla_catalogo F ON F.catalogo_valor = P.pago_formapagoid
							LEFT JOIN alumno_representante E on E.repre_alumnoid = P.pago_alumnoid 
							LEFT JOIN(SELECT COUNT(1) total, PT.transaccion_pagoid, MIN(PT.transaccion_id) IDT
								FROM alumno_pago_transaccion PT
								WHERE PT.transaccion_estado = 'C'
								GROUP BY PT.transaccion_pagoid)T ON T.transaccion_pagoid = P.pago_id
								LEFT JOIN alumno_pago_transaccion PT ON PT.transaccion_id  = T.IDT
								WHERE P.pago_id = ".$pagoid;

			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
		}

		public function generarReciboPendiente($transaccion_id){
		
			$consulta_datos="SELECT R.catalogo_descripcion RUBRO, F.catalogo_descripcion FORMAPAGO, 
					E.repre_correo CORREO_REP,
					P.*, A.*, PT.* 
				FROM alumno_pago P	
					INNER JOIN sujeto_alumno A ON A.alumno_id = P.pago_alumnoid 
					INNER JOIN alumno_pago_transaccion PT ON PT.transaccion_pagoid = P.pago_id
					LEFT JOIN alumno_representante E on E.repre_alumnoid = P.pago_alumnoid
 					INNER JOIN general_tabla_catalogo R ON R.catalogo_valor = P.pago_rubroid 
					INNER JOIN general_tabla_catalogo F ON F.catalogo_valor = P.pago_formapagoid 				
				WHERE PT.transaccion_id = ".$transaccion_id;	

			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
		}

		public function informacionEscuela(){		
			$consulta_datos="SELECT * FROM general_escuela WHERE escuela_id  = 1";
			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
		}

		public function numeroALetras($numero) {
			$unidades = array(
				'', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve', 'diez', 'once', 'doce', 'trece', 'catorce', 'quince',
				'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve', 'veinte', 'veintiuno', 'veintidós', 'veintitrés', 'veinticuatro', 'veinticinco',
				'veintiséis', 'veintisiete', 'veintiocho', 'veintinueve'
			);
		
			$decenas = array(
				'', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'
			);
		
			$centenas = array(
				'', 'cien', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'
			);
		
			if ($numero == 0) {
				return 'cero';
			}
		
			$numeroEnLetras = '';
		
			if ($numero >= 1000) {
				$miles = floor($numero / 1000);
				$numeroEnLetras .= $miles == 1 ? 'mil' : $unidades[$miles] . ' mil ';
				$numero %= 1000;
			}
		
			if ($numero >= 100) {
				$numeroEnLetras .= $centenas[floor($numero / 100)] . ' ';
				$numero %= 100;
			}
		
			if ($numero >= 30) {
				$numeroEnLetras .= $decenas[floor($numero / 10)];
				$numero %= 10;
				if ($numero > 0) {
					$numeroEnLetras .= ' y ' . $unidades[$numero];
				}
			} else {
				$numeroEnLetras .= $unidades[$numero];
			}
		
			return trim($numeroEnLetras);
		}

		public function textoLetras($numero){
			$partes = explode('.', $numero);
			$entero = intval($partes[0]);
			$decimal = isset($partes[1]) ? intval(str_pad($partes[1], 2, '0', STR_PAD_RIGHT)) : 0;

			$enteroEnLetras = $this->numeroALetras($entero);
			$decimalEnLetras = $decimal > 0 ? $this->numeroALetras($decimal) : '';

			if ($decimalEnLetras) {
				return $enteroEnLetras . ' dólares con ' . $decimalEnLetras . ' centavos';
			} else {
				return $enteroEnLetras ." dólares con cero centavos";
			}
		}

		public function BuscarPagoPendiente($transaccion_id ){
		
			$consulta_datos="SELECT R.catalogo_descripcion RUBRO, T.* 
					FROM alumno_pago_transaccion T 
						INNER JOIN alumno_pago P ON P.pago_id = T.transaccion_pagoid 
						INNER JOIN general_tabla_catalogo R ON R.catalogo_valor = P.pago_rubroid 				
					WHERE T.transaccion_id = ".$transaccion_id;	

			$datos = $this->ejecutarConsulta($consulta_datos);		
			return $datos;
		}

		public function listarOptionPagoid($idformapago){
			$option="";

			$consulta_datos="SELECT *FROM general_tabla_catalogo WHERE catalogo_tablaid = 6 AND catalogo_estado = 1";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){		
				if($idformapago == $rows['catalogo_valor']){
					$option.='<option value='.$rows['catalogo_valor'].' selected="selected">'.$rows['catalogo_descripcion'].'</option>';
				}else{
					$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';	
				}									
			}
			return $option;
		}

		/*----------  Controlador actualizar pago  ----------*/
		public function actualizarPagoControlador(){
			
			$pagoid=$this->limpiarCadena($_POST['pago_id']);

			# Verificando pago #
			$datos = $this->ejecutarConsulta("SELECT * FROM alumno_pago WHERE pago_id = '$pagoid'");			
			if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el pago en el sistema: ".$pagoid,
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }else{
		    	$datos=$datos->fetch();				
		    }				

			# Almacenando datos#
			$pago_fecha			= $this->limpiarCadena($_POST['pago_fecha']);
			$pago_fecharegistro	= $this->limpiarCadena($_POST['pago_fecharegistro']);
			$pago_periodo 		= $this->limpiarCadena($_POST['pago_periodo']);
			$pago_valor 		= $_POST['pago_valor'];
			$pago_saldo 		= $_POST['pago_saldo'];
			$pago_formapagoid 	= $this->limpiarCadena($_POST['pago_formapagoid']);
			$pago_concepto 		= $this->limpiarCadena($_POST['pago_concepto']);
			
			if ($pago_valor =="") {$pago_valor = 0;}
			if ($pago_saldo =="") {$pago_saldo = 0;}

			if($pago_valor < 1 && $pago_saldo < 1){
				$estado = "J";
			}elseif($pago_saldo != "" && $pago_saldo > 0){
				$estado = "P";
			}else{
				$estado = "C";
			}
			
			# Verificando campos obligatorios #
		    if($pago_fecha=="" || $pago_fecharegistro=="" || $pago_periodo=="" || $pago_valor=="" || $pago_formapagoid=="" ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        
		    }			
		
			# Directorio de imagenes #
			$img_dir="../views/imagenes/pagos/";

    		# Comprobar si se selecciono una imagen #
    		if($_FILES['pago_archivo']['name']=="" && $_FILES['pago_archivo']['size']<=0){
					
				$pago_datos_reg=[
					[
						"campo_nombre"=>"pago_formapagoid",
						"campo_marcador"=>":Formapagoid",
						"campo_valor"=>$pago_formapagoid
					],				
					[
						"campo_nombre"=>"pago_valor",
						"campo_marcador"=>":Valor",
						"campo_valor"=>$pago_valor
					],
					[
						"campo_nombre"=>"pago_saldo",
						"campo_marcador"=>":Saldo",
						"campo_valor"=>$pago_saldo
					],		
					[
						"campo_nombre"=>"pago_concepto",
						"campo_marcador"=>":Concepto",
						"campo_valor"=>$pago_concepto
					],
					[
						"campo_nombre"=>"pago_fecha",
						"campo_marcador"=>":Fecha",
						"campo_valor"=>$pago_fecha
					],				
					[
						"campo_nombre"=>"pago_fecharegistro",
						"campo_marcador"=>":Fecharegistro",
						"campo_valor"=>$pago_fecharegistro
					],
					[
						"campo_nombre"=>"pago_periodo",
						"campo_marcador"=>":Periodo",
						"campo_valor"=>$pago_periodo
					],
					[
						"campo_nombre"=>"pago_estado",
						"campo_marcador"=>":Estado",
						"campo_valor"=> $estado
					]
				];
			
    		}ELSE{

				# Creando directorio #
				if(!file_exists($img_dir)){
					if(!mkdir($img_dir,0777)){
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error inesperado",
							"texto"=>"Error al crear el directorio",
							"icono"=>"error"
						];
						return json_encode($alerta);
						//exit();
					} 
				}

				# Verificando formato de imagenes #
				if(mime_content_type($_FILES['pago_archivo']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['pago_archivo']['tmp_name'])!="image/png"){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"La imagen que ha seleccionado es de un formato no permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
					//exit();
				}

				# Verificando peso de imagen #
				if(($_FILES['pago_archivo']['size']/1024)>3000){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"La imagen que ha seleccionado supera el peso permitido 3MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
					//exit();
				}

				# Nombre de la foto #
				/*
				if($datos['usuario_imagen']==""){
					$foto=explode(".", $datos['usuario_imagen']);
					$foto=$foto[0];
				}else{
					$foto=str_ireplace(" ","_",$datos['usuario_usuario']);
					$foto=$foto."_".rand(0,100);
				}
				*/
				$foto=str_ireplace(" ","_",$datos['pago_id']);
				$foto=$foto."_".rand(0,100);
				

				# Extension de la imagen #
				switch(mime_content_type($_FILES['pago_archivo']['tmp_name'])){
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
				$inputFile = ($_FILES['pago_archivo']['tmp_name']);
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
				if(is_file($img_dir.$datos['pago_archivo']) && $datos['pago_archivo']!=$foto){
					chmod($img_dir.$datos['pago_archivo'], 0777);
					unlink($img_dir.$datos['pago_archivo']);
				}				
				
				$pago_datos_reg=[					
					[
						"campo_nombre"=>"pago_formapagoid",
						"campo_marcador"=>":Formapagoid",
						"campo_valor"=>$pago_formapagoid
					],			
								
					[
						"campo_nombre"=>"pago_valor",
						"campo_marcador"=>":Valor",
						"campo_valor"=>$pago_valor
					],
					[
						"campo_nombre"=>"pago_saldo",
						"campo_marcador"=>":Saldo",
						"campo_valor"=>$pago_saldo
					],		
					[
						"campo_nombre"=>"pago_concepto",
						"campo_marcador"=>":Concepto",
						"campo_valor"=>$pago_concepto
					],
					[
						"campo_nombre"=>"pago_fecha",
						"campo_marcador"=>":Fecha",
						"campo_valor"=>$pago_fecha
					],				
					[
						"campo_nombre"=>"pago_fecharegistro",
						"campo_marcador"=>":Fecharegistro",
						"campo_valor"=>$pago_fecharegistro
					],
					[
						"campo_nombre"=>"pago_periodo",
						"campo_marcador"=>":Periodo",
						"campo_valor"=>$pago_periodo
					],
					[
						"campo_nombre"=>"pago_estado",
						"campo_marcador"=>":Estado",
						"campo_valor"=>$estado
					],
					[
						"campo_nombre"=>"pago_archivo",
						"campo_marcador"=>":Imagenpago",
						"campo_valor"=>$foto
					]
				];
			}

			$condicion=[
				"condicion_campo"=>"pago_id",
				"condicion_marcador"=>":Pagoid",
				"condicion_valor"=>$pagoid
			];
			

			if($this->actualizarDatos("alumno_pago",$pago_datos_reg,$condicion)){				
				
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Pago actualizado",
					"texto"=>"Los datos del pago ".$pagoid." se actualizaron correctamente",
					"icono"=>"success"
				];								

			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar los datos del pago ".$pagoid.", por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}

		public function actualizarPagoPendiente(){			
			
			$transaccion_id = $this->limpiarCadena($_POST['transaccion_id']);
			# Verificando pago #
			$datos = $this->ejecutarConsulta("SELECT * FROM alumno_pago_transaccion WHERE transaccion_id = '$transaccion_id'");			
			if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el pago en el sistema: ".$transaccion_id,
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }else{
		    	$datos=$datos->fetch();				
		    }					

			

			# Almacenando datos#			
			$transaccion_pagoid 		= $this->limpiarCadena($_POST['transaccion_pagoid']);
			$transaccion_valorcalculado = $this->limpiarCadena($_POST['transaccion_valorcalculado']);
			$valor						= $this->limpiarCadena($_POST['transaccion_valor']);
			$transaccion_fecha			= $this->limpiarCadena($_POST['pago_fecha']);
			$transaccion_fecharegistro	= $this->limpiarCadena($_POST['pago_fecharegistro']);
			$transaccion_periodo		= $this->limpiarCadena($_POST['pago_periodo']);
			$transaccion_valor 			= $_POST['pago_valor'];
			$transaccion_formapagoid 	= $this->limpiarCadena($_POST['pago_formapagoid']);
			$transaccion_concepto 		= $this->limpiarCadena($_POST['pago_concepto']);
					

			$saldo = $transaccion_valorcalculado - $transaccion_valor;			

			if ($saldo == 0){
				$estado_saldo='C';
			}else{
				$estado_saldo = 'P';
			}
						
			# Verificando campos obligatorios #
		    if($transaccion_fecha=="" || $transaccion_fecharegistro=="" || $transaccion_periodo=="" || $transaccion_valor=="" || $transaccion_formapagoid=="" ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        
		    }	
			
			if($saldo < 0 ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error en el valor ingresado",
					"texto"=>"El valor ingresado supera el monto del saldo",
					"icono"=>"error"
				];
				return json_encode($alerta);		        
		    }
		
			# Directorio de imagenes #
			$img_dir="../views/imagenes/pagos/";

    		# Comprobar si se selecciono una imagen #
    		if($_FILES['pago_archivo']['name']=="" && $_FILES['pago_archivo']['size']<=0){
					
				$pago_datos_reg=[								
					[
						"campo_nombre"=>"transaccion_valor",
						"campo_marcador"=>":Valor",
						"campo_valor"=>$transaccion_valor
					],
					[
						"campo_nombre"=>"transaccion_fecha",
						"campo_marcador"=>":Fecha",
						"campo_valor"=>$transaccion_fecha
					],				
					[
						"campo_nombre"=>"transaccion_fecharegistro",
						"campo_marcador"=>":transaccion_fecharegistro",
						"campo_valor"=>$transaccion_fecharegistro
					],		
					[
						"campo_nombre"=>"transaccion_formapagoid",
						"campo_marcador"=>":Formapagoid",
						"campo_valor"=>$transaccion_formapagoid
					],	
					[
						"campo_nombre"=>"transaccion_concepto",
						"campo_marcador"=>":Concepto",
						"campo_valor"=>$transaccion_concepto
					],
					
					[
						"campo_nombre"=>"transaccion_periodo",
						"campo_marcador"=>":Periodo",
						"campo_valor"=>$transaccion_periodo
					]
				];
			
    		}ELSE{

				# Creando directorio #
				if(!file_exists($img_dir)){
					if(!mkdir($img_dir,0777)){
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error inesperado",
							"texto"=>"Error al crear el directorio",
							"icono"=>"error"
						];
						return json_encode($alerta);
						//exit();
					} 
				}

				# Verificando formato de imagenes #
				if(mime_content_type($_FILES['pago_archivo']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['pago_archivo']['tmp_name'])!="image/png"){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"La imagen que ha seleccionado es de un formato no permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
					//exit();
				}

				# Verificando peso de imagen #
				if(($_FILES['pago_archivo']['size']/1024)>3000){
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"La imagen que ha seleccionado supera el peso permitido 3MB",
						"icono"=>"error"
					];
					return json_encode($alerta);
					//exit();
				}

				$foto=str_ireplace(" ","_","pagopendiente".$transaccion_id);
				$foto=$foto."_".rand(0,100);
				

				# Extension de la imagen #
				switch(mime_content_type($_FILES['pago_archivo']['tmp_name'])){
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
				$inputFile = ($_FILES['pago_archivo']['tmp_name']);
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
				if(is_file($img_dir.$datos['transaccion_archivo']) && $datos['transaccion_archivo']!=$foto){
					chmod($img_dir.$datos['transaccion_archivo'], 0777);
					unlink($img_dir.$datos['transaccion_archivo']);
				}				
				
				$pago_datos_reg=[					
					[
						"campo_nombre"=>"transaccion_valor",
						"campo_marcador"=>":Valor",
						"campo_valor"=>$transaccion_valor
					],
					[
						"campo_nombre"=>"transaccion_fecha",
						"campo_marcador"=>":Fecha",
						"campo_valor"=>$transaccion_fecha
					],				
					[
						"campo_nombre"=>"transaccion_fecharegistro",
						"campo_marcador"=>":transaccion_fecharegistro",
						"campo_valor"=>$transaccion_fecharegistro
					],		
					[
						"campo_nombre"=>"transaccion_formapagoid",
						"campo_marcador"=>":Formapagoid",
						"campo_valor"=>$transaccion_formapagoid
					],	
					[
						"campo_nombre"=>"transaccion_concepto",
						"campo_marcador"=>":Concepto",
						"campo_valor"=>$transaccion_concepto
					],
					
					[
						"campo_nombre"=>"transaccion_periodo",
						"campo_marcador"=>":Periodo",
						"campo_valor"=>$transaccion_periodo
					],
					[
						"campo_nombre"=>"transaccion_archivo",
						"campo_marcador"=>":Imagenpago",
						"campo_valor"=>$foto
					]
				];
			}

			$condicion=[
				"condicion_campo"=>"transaccion_id",
				"condicion_marcador"=>":Pagoid",
				"condicion_valor"=>$transaccion_id 
			];
			

			if($this->actualizarDatos("alumno_pago_transaccion",$pago_datos_reg,$condicion)){				
				
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Pago actualizado",
					"texto"=>"Los datos del pago ".$transaccion_id ." se actualizaron correctamente",
					"icono"=>"success"
				];		

				
				
				$check_pago=$this->ejecutarConsulta("SELECT pago_valor, pago_saldo FROM alumno_pago WHERE pago_id = ".$transaccion_pagoid);
				//$check_pago=$this->seleccionarDatos("Unico","general_escuela","escuela_id","1");
				if($check_pago->rowCount()>0){				
					foreach($check_pago as $rows){	
						$pago_saldo = $rows["pago_saldo"]; 	
						$pago_valor = $rows["pago_valor"];
					}					
				}			

				if($valor > $transaccion_valor){
					$nuevo_valor = $valor - $transaccion_valor;	
					$pago_saldo += $nuevo_valor; 	
					$pago_valor -= $nuevo_valor;									
				}elseif($valor < $transaccion_valor){
					$nuevo_valor = $transaccion_valor - $valor;
					$pago_saldo -= $nuevo_valor; 	
					$pago_valor += $nuevo_valor;
				}				

				// Actualizar saldo y valor
				$this->ejecutarConsulta("UPDATE alumno_pago SET pago_valor = ".$pago_valor.", pago_saldo = ".$pago_saldo.", pago_estado = '".$estado_saldo."' WHERE pago_id = ".$transaccion_pagoid);
			

			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar los datos del pago ".$transaccion_id.", por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}
	
		public function actualizarDescuento(){		
			
			$descuento_alumnoid = $this->limpiarCadena($_POST['descuento_alumnoid']);

			# Verificando pago #
			$datos = $this->ejecutarConsulta("SELECT * FROM alumno_pago_descuento WHERE descuento_alumnoid = '$descuento_alumnoid'");			
			if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el descuento en el sistema: ".$descuento_alumnoid,
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }else{
		    	$datos=$datos->fetch();				
		    }				

			# Almacenando datos#
			$descuento_rubroid	= $this->limpiarCadena($_POST['descuento_rubroid']);
			$descuento_valor	= $this->limpiarCadena($_POST['descuento_valor']);
			$descuento_fecha 	= $this->limpiarCadena($_POST['descuento_fecha']);
			$descuento_detalle 	= $this->limpiarCadena($_POST['descuento_detalle']);			
			$descuento_estado 	= $this->limpiarCadena($_POST['descuento_estado']);			
			
			if ($descuento_valor =="") {$descuento_valor = 0;}
			
			# Verificando campos obligatorios #
		    if($descuento_rubroid=="" || $descuento_valor=="" || $descuento_fecha=="" || $descuento_estado=="" ){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        
		    }			

			$descuento_datos_reg=[
				[
					"campo_nombre"=>"descuento_rubroid",
					"campo_marcador"=>":Rubroid",
					"campo_valor"=>$descuento_rubroid
				],
				[
					"campo_nombre"=>"descuento_alumnoid",
					"campo_marcador"=>":Alumnoid",
					"campo_valor"=>$descuento_alumnoid
				],						
				[
					"campo_nombre"=>"descuento_valor",
					"campo_marcador"=>":Valor",
					"campo_valor"=>$descuento_valor
				],		
				[
					"campo_nombre"=>"descuento_detalle",
					"campo_marcador"=>":Detalle",
					"campo_valor"=>$descuento_detalle
				],
				[
					"campo_nombre"=>"descuento_fecha",
					"campo_marcador"=>":Fecha",
					"campo_valor"=>$descuento_fecha
				],				
				[
					"campo_nombre"=>"descuento_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>$descuento_estado
				]
			];	

			$condicion=[
				"condicion_campo"=>"descuento_alumnoid ",
				"condicion_marcador"=>":Alumnoid ",
				"condicion_valor"=>$descuento_alumnoid 
			];
			

			if($this->actualizarDatos("alumno_pago_descuento",$descuento_datos_reg,$condicion)){				
				
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Descuento actualizado",
					"texto"=>"Los datos del descuento se actualizaron correctamente",
					"icono"=>"success"
				];								

			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar los datos del descuento, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}
		
	}
			