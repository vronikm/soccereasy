<?php

	namespace app\controllers;
	use app\models\mainModel;

	class reporteController extends mainModel{
	
		public function listarPagos($fecha_inicio, $fecha_fin){
			$tabla="";
			$consulta_datos="SELECT A.alumno_identificacion IDENTIFICACION,
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
								LEFT JOIN(SELECT COUNT(1) total, PT.transaccion_pagoid, MIN(PT.transaccion_id) IDT
								FROM alumno_pago_transaccion PT
								WHERE PT.transaccion_estado = 'C'
								GROUP BY PT.transaccion_pagoid)T ON T.transaccion_pagoid = P.pago_id
								LEFT JOIN alumno_pago_transaccion PT ON PT.transaccion_id  = T.IDT
							where pago_estado <> 'E'
								and pago_fecharegistro between ' ".$fecha_inicio." ' and ' ".$fecha_fin."'
							
							union all 
														
							SELECT A.alumno_identificacion IDENTIFICACION,
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
								inner join general_tabla_catalogo F ON F.catalogo_valor = P.pago_formapagoid
								inner join sujeto_alumno A on A.alumno_id = P.pago_alumnoid 
								inner join alumno_pago_transaccion T on T.transaccion_pagoid = P.pago_id
							where transaccion_estado <> 'E'
								and transaccion_fecharegistro between ' ".$fecha_inicio." ' and ' ".$fecha_fin."'";

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
			foreach($datos as $rows){
				
				$tabla.='
					<tr>
						<td>'.$rows['IDENTIFICACION'].'</td>
						<td>'.$rows['ALUMNO'].'</td>
						<td>'.$rows['FECHA_PAGO'].'</td>
						<td>'.$rows['FECHA_REG_SISTEMA'].'</td>
						<td>'.$rows['PERIODO'].'</td>
						<td>'.$rows['RUBRO'].'</td>
						<td>'.$rows['FORMA_PAGO'].'</td>
						<td>'.$rows['VALOR_PAGADO'].'</td>
						<td>'.$rows['VALOR_PENDIENTE'].'</td>
						<td>'.$rows['ESTADO_PAGO'].'</td>
					</tr>';	
			}
			return $tabla;			
		}

		public function fechaPagosReceptados(){		
			$consulta_fecham="SELECT max(pago_fecharegistro) FECHA_MAXIMA FROM alumno_pago";
			$fecha_maxima = $this->ejecutarConsulta($consulta_fecham);		
			return $fecha_maxima;
		}

		public function valoresPendientes(){		
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
									WHERE pago_estado = 'P' AND pago_saldo > 0
									GROUP BY pago_alumnoid
								) P ON P.pago_alumnoid = A.alumno_id
								LEFT JOIN (
									SELECT 
									BASE.FECHA,
									BASE.pago_alumnoid,
									CASE WHEN BASE.FECHA > CURDATE() THEN 0 ELSE
										GREATEST(0, TIMESTAMPDIFF(MONTH, BASE.FECHA, CURDATE()) + (DAY(CURDATE()) < DAY(BASE.FECHA))) END AS PENSIONES,
									CASE WHEN BASE.FECHA > CURDATE() THEN 0 ELSE
										GREATEST(0, TIMESTAMPDIFF(MONTH, BASE.FECHA, CURDATE()) + (DAY(CURDATE()) < DAY(BASE.FECHA))) * COALESCE(BASE.descuento_valor, BASE.escuela_pension) END AS TOTAL
									FROM (
									SELECT 
										MAX(pago_fecha) AS FECHA, 
										pago_alumnoid, 
										MAX(descuento_valor) AS descuento_valor, 
										MAX(escuela_pension) AS escuela_pension  
									FROM 
										sujeto_alumno
										LEFT JOIN alumno_pago ON pago_alumnoid = alumno_id 
										LEFT JOIN alumno_pago_descuento ON descuento_alumnoid = alumno_id AND descuento_estado = 'S'
										LEFT JOIN general_escuela ON escuela_id = 1
									WHERE pago_rubroid = 'RPE'
									GROUP BY 
										pago_alumnoid
									) BASE
								) PEN ON PEN.pago_alumnoid = A.alumno_id
								WHERE PEN.TOTAL > 0 OR P.SALDO > 0
								";
			
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
	}
			
											