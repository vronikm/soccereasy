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
								pago_valor VALOR_PAGADO, 
								pago_saldo VALOR_PENDIENTE,
								case pago_estado when 'P' then 'Pendiente' when 'J' then 'Justificado' when 'E' then 'Eliminado' when 'C' then 'Cancelado' else pago_estado end ESTADO_PAGO
							from alumno_pago P
								inner join general_tabla_catalogo R ON R.catalogo_valor = P.pago_rubroid 
								inner join general_tabla_catalogo F ON F.catalogo_valor = P.pago_formapagoid
								inner join sujeto_alumno A on A.alumno_id = P.pago_alumnoid 
							where pago_estado <> 'E'
								/*and pago_fecharegistro between '2024-05-01' and '2024-06-01'*/
								and pago_fecharegistro between ' ".$fecha_inicio." ' and ' ".$fecha_fin."'
							order by pago_fecharegistro";

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
									GREATEST(0, TIMESTAMPDIFF(MONTH, BASE.FECHA, CURDATE()) + (DAY(CURDATE()) < DAY(BASE.FECHA))) AS PENSIONES,
									GREATEST(0, TIMESTAMPDIFF(MONTH, BASE.FECHA, CURDATE()) + (DAY(CURDATE()) < DAY(BASE.FECHA))) * COALESCE(BASE.descuento_valor, BASE.escuela_pension) AS TOTAL
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
							WHERE PEN.TOTAL > 0 OR P.SALDO > 0;
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
			
											