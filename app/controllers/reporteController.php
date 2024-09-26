<?php

	namespace app\controllers;
	use app\models\mainModel;

	class reporteController extends mainModel{
	
		public function listarPagos($fecha_inicio, $fecha_fin, $sede_id){
			$tabla="";
			$VALOR_PAGADO = 0;
			$VALOR_PENDIENTE = 0;
			$consulta_datos="SELECT sede_nombre SEDE, A.alumno_identificacion IDENTIFICACION,
								concat(A.alumno_primernombre, ' ', A.alumno_segundonombre, ' ', A.alumno_apellidopaterno, ' ', A.alumno_apellidomaterno) ALUMNO,
								pago_fecha FECHA_PAGO, 
								pago_fecharegistro FECHA_REG_SISTEMA, 
								pago_periodo PERIODO,  
								R.catalogo_descripcion RUBRO,  
								F.catalogo_descripcion FORMA_PAGO, 
								((P.pago_saldo + P.pago_valor) - (IFNULL(PT.transaccion_valorcalculado, P.pago_saldo)))VALOR_PAGADO, 
								IFNULL(PT.transaccion_valorcalculado, P.pago_saldo) VALOR_PENDIENTE,
								case IFNULL(PT.transaccion_valorcalculado, P.pago_saldo) when 0 then 'Cancelado' else 'Pendiente' end ESTADO_PAGO
							from alumno_pago P
								inner join general_tabla_catalogo R ON R.catalogo_valor = P.pago_rubroid 
								inner join general_tabla_catalogo F ON F.catalogo_valor = P.pago_formapagoid
								inner join sujeto_alumno A on A.alumno_id = P.pago_alumnoid 
								inner join general_sede S on S.sede_id = alumno_sedeid
								LEFT JOIN(SELECT COUNT(1) total, PT.transaccion_pagoid, MIN(PT.transaccion_id) IDT
								FROM alumno_pago_transaccion PT
								WHERE PT.transaccion_estado = 'C'
								GROUP BY PT.transaccion_pagoid)T ON T.transaccion_pagoid = P.pago_id
								LEFT JOIN alumno_pago_transaccion PT ON PT.transaccion_id  = T.IDT
							where pago_estado <> 'E'
								and alumno_sedeid = ".$sede_id."
								and pago_fecharegistro between ' ".$fecha_inicio." ' and ' ".$fecha_fin."'
							
							union all 
														
							SELECT sede_nombre SEDE, A.alumno_identificacion IDENTIFICACION,
								concat(A.alumno_primernombre, ' ', A.alumno_segundonombre, ' ', A.alumno_apellidopaterno, ' ', A.alumno_apellidomaterno) ALUMNO,
								transaccion_fecha FECHA_PAGO, 
								transaccion_fecharegistro FECHA_REG_SISTEMA, 
								transaccion_periodo PERIODO,  
								R.catalogo_descripcion RUBRO,  
								F.catalogo_descripcion FORMA_PAGO, 
								transaccion_valor VALOR_PAGADO, 
								transaccion_valorcalculado - transaccion_valor VALOR_PENDIENTE,
								case (transaccion_valorcalculado - transaccion_valor) when 0 then 'Cancelado' else 'Pendiente' end ESTADO_PAGO
							from alumno_pago P
								inner join general_tabla_catalogo R ON R.catalogo_valor = P.pago_rubroid
								inner join sujeto_alumno A on A.alumno_id = P.pago_alumnoid 
								inner join alumno_pago_transaccion T on T.transaccion_pagoid = P.pago_id 
								inner join general_tabla_catalogo F ON F.catalogo_valor = T.transaccion_formapagoid 								
								inner join general_sede S on S.sede_id = alumno_sedeid
							where transaccion_estado <> 'E'
								and alumno_sedeid = ".$sede_id."
								and transaccion_fecharegistro between ' ".$fecha_inicio." ' and ' ".$fecha_fin."'";

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$VALOR_PAGADO += $rows['VALOR_PAGADO'];
				$VALOR_PENDIENTE += $rows['VALOR_PENDIENTE'];
				$tabla.='
					<tr>
						<td>'.$rows['SEDE'].'</td>
						<td>'.$rows['IDENTIFICACION'].'</td>
						<td>'.$rows['ALUMNO'].'</td>
						<td>'.$rows['FECHA_PAGO'].'</td>
						<td>'.$rows['FECHA_REG_SISTEMA'].'</td>
						<td>'.$rows['PERIODO'].'</td>
						<td>'.$rows['RUBRO'].'</td>
						<td>'.$rows['FORMA_PAGO'].'</td>
						<td style="text-align: right">'.$rows['VALOR_PAGADO'].'</td>
						<td style="text-align: right">'.$rows['VALOR_PENDIENTE'].'</td>
						<td>'.$rows['ESTADO_PAGO'].'</td>
					</tr>';	
			}
			$tabla.='
				<tr data-widget="expandable-table" aria-expanded="false">
					<td colspan="8">TOTAL</td>
					<td style="text-align: right">'.number_format($VALOR_PAGADO, 2, '.',',').'</td>
					<td style="text-align: right">'.number_format($VALOR_PENDIENTE, 2, '.',',').'</td>
					<td> </td>				
				</tr>';	

			return $tabla;			
		}

		public function listarPagosConsolidado($fecha_inicio, $fecha_fin){
			$tabla="";
			$VALOR_PAGADO = 0;
			$VALOR_PENDIENTE = 0;
			$consulta_datos="SELECT sede_nombre SEDE, A.alumno_identificacion IDENTIFICACION,
								concat(A.alumno_primernombre, ' ', A.alumno_segundonombre, ' ', A.alumno_apellidopaterno, ' ', A.alumno_apellidomaterno) ALUMNO,
								pago_fecha FECHA_PAGO, 
								pago_fecharegistro FECHA_REG_SISTEMA, 
								pago_periodo PERIODO,  
								R.catalogo_descripcion RUBRO,  
								F.catalogo_descripcion FORMA_PAGO, 
								((P.pago_saldo + P.pago_valor) - (IFNULL(PT.transaccion_valorcalculado, P.pago_saldo)))VALOR_PAGADO, 
								IFNULL(PT.transaccion_valorcalculado, P.pago_saldo) VALOR_PENDIENTE,
								case IFNULL(PT.transaccion_valorcalculado, P.pago_saldo) when 0 then 'Cancelado' else 'Pendiente' end ESTADO_PAGO
							from alumno_pago P
								inner join general_tabla_catalogo R ON R.catalogo_valor = P.pago_rubroid 
								inner join general_tabla_catalogo F ON F.catalogo_valor = P.pago_formapagoid
								inner join sujeto_alumno A on A.alumno_id = P.pago_alumnoid
								inner join general_sede S on S.sede_id = alumno_sedeid 
								LEFT JOIN(SELECT COUNT(1) total, PT.transaccion_pagoid, MIN(PT.transaccion_id) IDT
								FROM alumno_pago_transaccion PT
								WHERE PT.transaccion_estado = 'C'
								GROUP BY PT.transaccion_pagoid)T ON T.transaccion_pagoid = P.pago_id
								LEFT JOIN alumno_pago_transaccion PT ON PT.transaccion_id  = T.IDT
							where pago_estado <> 'E'
								and pago_fecharegistro between ' ".$fecha_inicio." ' and ' ".$fecha_fin."'
							
							union all 
														
							SELECT sede_nombre SEDE, A.alumno_identificacion IDENTIFICACION,
								concat(A.alumno_primernombre, ' ', A.alumno_segundonombre, ' ', A.alumno_apellidopaterno, ' ', A.alumno_apellidomaterno) ALUMNO,
								transaccion_fecha FECHA_PAGO, 
								transaccion_fecharegistro FECHA_REG_SISTEMA, 
								transaccion_periodo PERIODO,  
								R.catalogo_descripcion RUBRO,  
								F.catalogo_descripcion FORMA_PAGO, 
								transaccion_valor VALOR_PAGADO, 
								transaccion_valorcalculado - transaccion_valor VALOR_PENDIENTE,
								case (transaccion_valorcalculado - transaccion_valor) when 0 then 'Cancelado' else 'Pendiente' end ESTADO_PAGO
							from alumno_pago P
								inner join general_tabla_catalogo R ON R.catalogo_valor = P.pago_rubroid 							
								inner join sujeto_alumno A on A.alumno_id = P.pago_alumnoid 
								inner join alumno_pago_transaccion T on T.transaccion_pagoid = P.pago_id								
								inner join general_tabla_catalogo F ON F.catalogo_valor = T.transaccion_formapagoid 
								inner join general_sede S on S.sede_id = alumno_sedeid
							where transaccion_estado <> 'E'
								and transaccion_fecharegistro between ' ".$fecha_inicio." ' and ' ".$fecha_fin."'";

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$VALOR_PAGADO += $rows['VALOR_PAGADO'];
				$VALOR_PENDIENTE += $rows['VALOR_PENDIENTE'];
				$tabla.='
					<tr>
						<td>'.$rows['SEDE'].'</td>
						<td>'.$rows['IDENTIFICACION'].'</td>
						<td>'.$rows['ALUMNO'].'</td>
						<td>'.$rows['FECHA_PAGO'].'</td>
						<td>'.$rows['FECHA_REG_SISTEMA'].'</td>
						<td>'.$rows['PERIODO'].'</td>
						<td>'.$rows['RUBRO'].'</td>
						<td>'.$rows['FORMA_PAGO'].'</td>
						<td style="text-align: right">'.$rows['VALOR_PAGADO'].'</td>
						<td style="text-align: right">'.$rows['VALOR_PENDIENTE'].'</td>
						<td>'.$rows['ESTADO_PAGO'].'</td>
					</tr>';	
			}
			$tabla.='
				<tr data-widget="expandable-table" aria-expanded="false">
					<td colspan="8">TOTAL</td>
					<td style="text-align: right">'.number_format($VALOR_PAGADO, 2, '.',',').'</td>
					<td style="text-align: right">'.number_format($VALOR_PENDIENTE, 2, '.',',').'</td>
					<td> </td>				
				</tr>';	
			return $tabla;			
		}

		public function fechaPagosReceptados($sede_id){		
			$consulta_fecham="SELECT max(pago_fecharegistro) AS FECHA_MAXIMA
								FROM alumno_pago, sujeto_alumno
								WHERE pago_alumnoid = alumno_id
									AND alumno_sedeid = ".$sede_id."
								ORDER BY pago_fecharegistro";
			$fecha_maxima = $this->ejecutarConsulta($consulta_fecham);		
			return $fecha_maxima;
		}

		public function valoresPendientes($sedeid){		
			$tabla="";
			$NUM_SALDO = 0;
			$SALDO = 0;
			$NUM_PENSION = 0;
			$PENSION = 0;
			$consulta_datos="SELECT 
									alumno_id, 
									alumno_identificacion, 
									CONCAT_WS(' ', alumno_primernombre, alumno_segundonombre, alumno_apellidopaterno, alumno_apellidomaterno) AS NOMBRES,  
									IFNULL(P.TOTAL,0) AS NUM_SALDO, 
									IFNULL(P.SALDO,0) AS SALDO, 
									IFNULL(PEN.PENSIONES,0) AS NUM_PENSION, 
									IFNULL(PEN.TOTAL,0) AS PENSION, 
									PEN.FECHA
								FROM sujeto_alumno A
								LEFT JOIN (
									SELECT 
									pago_alumnoid, 
									COUNT(pago_saldo) AS TOTAL, 
									SUM(pago_saldo) AS SALDO
									FROM alumno_pago
										INNER JOIN sujeto_alumno ON alumno_id = pago_alumnoid
									WHERE pago_estado = 'P' AND pago_saldo > 0 AND alumno_sedeid = ".$sedeid." 
									GROUP BY pago_alumnoid
								) P ON P.pago_alumnoid = A.alumno_id
								LEFT JOIN (
									SELECT 
									BASE.FECHA,
									BASE.pago_alumnoid,
									CASE WHEN BASE.FECHA > CURDATE() THEN 0 ELSE
										GREATEST(0, TIMESTAMPDIFF(MONTH, BASE.FECHA, CURDATE()) + (DAY(CURDATE()) < DAY(BASE.FECHA))) END AS PENSIONES,
									CASE WHEN BASE.FECHA > CURDATE() THEN 0 ELSE
										GREATEST(0, TIMESTAMPDIFF(MONTH, BASE.FECHA, CURDATE()) + (DAY(CURDATE()) < DAY(BASE.FECHA))) * COALESCE(BASE.descuento_valor, BASE.sede_pension) END AS TOTAL
									FROM (
									SELECT 
										MAX(pago_fecha) AS FECHA, 
										pago_alumnoid, 
										MAX(descuento_valor) AS descuento_valor, 
										MAX(sede_pension) AS sede_pension  
									FROM 
										sujeto_alumno
										LEFT JOIN alumno_pago ON pago_alumnoid = alumno_id 
										LEFT JOIN alumno_pago_descuento ON descuento_alumnoid = alumno_id AND descuento_estado = 'S'
										LEFT JOIN general_sede ON sede_id = alumno_sedeid
									WHERE pago_rubroid = 'RPE' AND alumno_estado <> 'I' AND alumno_sedeid = ".$sedeid." 
									GROUP BY 
										pago_alumnoid
									) BASE
								) PEN ON PEN.pago_alumnoid = A.alumno_id
								WHERE PEN.TOTAL > 0 OR P.SALDO > 0
								ORDER BY PEN.PENSIONES DESC";
			
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){	
				$NUM_SALDO += $rows['NUM_SALDO'];
				$SALDO += $rows['SALDO'];
				$NUM_PENSION += $rows['NUM_PENSION'];
				$PENSION += $rows['PENSION'];

				$tabla.='
					<tr data-widget="expandable-table" aria-expanded="false">
						<td>'.$rows['alumno_identificacion'].'</td>
						<td>'.$rows['NOMBRES'].'</td>
						<td>'.$rows['NUM_SALDO'].'</td>
						<td>'.$rows['SALDO'].'</td>
						<td>'.$rows['NUM_PENSION'].'</td>
						<td>'.$rows['PENSION'].'</td>						
					</tr>';							
			}

			$tabla.='
				<tr data-widget="expandable-table" aria-expanded="false">
					<td colspan="2">SUB TOTAL</td>					
					<td>'.$NUM_SALDO.'</td>
					<td>'.$SALDO.'</td>
					<td>'.$NUM_PENSION.'</td>
					<td>'.$PENSION.'</td>						
				</tr>';	

			$tabla.='
				<tr data-widget="expandable-table" aria-expanded="false">
					<td colspan="4">TOTAL</td>				
					<td>'.$NUM_PENSION + $NUM_SALDO.'</td>
					<td>'.$PENSION + $SALDO.'</td>						
				</tr>';
				
			return $tabla;
		}
		
		public function listarOptionRubro($rubro){
			$option ='<option value=0> Seleccione el rubro</option>';
			$consulta_datos="SELECT C.catalogo_valor, C.catalogo_descripcion 
								FROM general_tabla_catalogo C
								INNER JOIN general_tabla T on T.tabla_id = C.catalogo_tablaid
								WHERE T.tabla_nombre = 'rubros'
									AND T.tabla_estado = 'A'
									AND C.catalogo_estado = 'A'";	
					
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				if($rubro == $rows['catalogo_valor']){	
					$option.='<option value='.$rows['catalogo_valor'].' selected="selected">'.$rows['catalogo_descripcion'].'</option>';
				}else{		
				$option.='<option value='.$rows['catalogo_valor'].'>'.$rows['catalogo_descripcion'].'</option>';	
                }						
			}
			return $option;
		}

		public function listarAlumnosRubro($sede_id, $rubro){
			$tabla="";
			$consulta_datos="SELECT concat_ws(' ', alumno_primernombre, alumno_segundonombre, alumno_apellidopaterno, alumno_apellidomaterno) ALUMNO,
								pago_concepto KIT, 
								year(alumno_fechanacimiento) ANIO_NACIMIENTO, 
								alumno_numcamiseta NUMCAMISETA, 
								pago_talla TALLA,
								sede_nombre SEDE
								FROM sujeto_alumno 
								INNER JOIN alumno_pago ON alumno_id = pago_alumnoid
								INNER JOIN general_sede on alumno_sedeid =".$sede_id."
								WHERE pago_estado <> 'E'
									AND pago_rubroid =".$rubro;

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				$tabla.='
					<tr>
						<td>'.$rows['SEDE'].'</td>
						<td>'.$rows['ALUMNO'].'</td>
						<td>'.$rows['KIT'].'</td>
						<td>'.$rows['ANIO_NACIMIENTO'].'</td>
						<td>'.$rows['NUMCAMISETA'].'</td>
						<td>'.$rows['TALLA'].'</td>
					</tr>';	
			}
			return $tabla;			
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
	}
			
											