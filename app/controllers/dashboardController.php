<?php
	namespace app\controllers;
	use app\models\mainModel;

	class dashboardController extends mainModel{

		/*----------  Obtener total alumnos activos  ----------*/
		public function obtenerAlumnosActivos($sedeid){
			$alumnosActivos=$this->ejecutarConsulta("SELECT count(*) totalActivos FROM sujeto_alumno WHERE alumno_estado='A' and alumno_sedeid = $sedeid");
		    return $alumnosActivos;
		}

		/*----------  Obtener total alumnos inactivos  ----------*/
		public function obtenerAlumnosInactivos($sedeid){
			$alumnosInactivos=$this->ejecutarConsulta("SELECT count(*) totalInactivos FROM sujeto_alumno WHERE alumno_estado='I' and alumno_sedeid = $sedeid");
		    return $alumnosInactivos;
		}

		/*----------  Obtener total pagos cancelados  ----------*/
		public function obtenerPagosCancelados($sede_id){
			// Fechas dinámicas
			$fecha_inicio = date('Y-m-01'); // Primer día del mes actual
			$fecha_fin = date('Y-m-t');     // Último día del mes actual

			$pagosCancelados=$this->ejecutarConsulta("SELECT sum(totalCancelado) totalCancelados from (
																	SELECT COUNT(*) totalCancelado 
																		FROM alumno_pago, sujeto_alumno 
																		WHERE pago_alumnoid = alumno_id 
																			AND alumno_sedeid = ".$sede_id." 
																			AND pago_fecharegistro between '".$fecha_inicio."' and '". $fecha_fin."'
																			AND pago_estado <> 'E'
																	UNION ALL
																	SELECT COUNT(*) totalCancelado
																		FROM alumno_pago, alumno_pago_transaccion, sujeto_alumno 
																		WHERE pago_alumnoid = alumno_id 
																			AND pago_id = transaccion_pagoid 
																			AND alumno_sedeid = ".$sede_id." 
																			AND transaccion_fecharegistro between '".$fecha_inicio."' and '". $fecha_fin."'
																			AND transaccion_estado<> 'E') AS DATOS");
			return $pagosCancelados;
		}

		/*----------  Obtener total pagos pendientes  ----------*/
		public function obtenerPagosPendientes($sedeid){
			$pagosPendientes=$this->ejecutarConsulta("SELECT SUM(IFNULL(subconsulta.NUM_SALDO,0)) + SUM(IFNULL(subconsulta.NUM_PENSION,0)) as totalPendientes
															FROM (
																SELECT 
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
																WHERE A.alumno_estado <> 'E'
																	AND PEN.TOTAL > 0 OR P.SALDO > 0 
															) AS subconsulta;");
			return $pagosPendientes;
		}

		public function ingresosLugarEntr(){
			/*$mes = "";
			$mes = $_POST['mes']; // formato '2025-01'
			// Seguridad: verifica que la clave exista
			if (!isset($_POST['mes'])) {
				echo json_encode([
					'error' => true,
					'mensaje' => 'No se recibio el parametro "mes".'
				]);
				exit;
			}
			//$mes = $_POST['mes']; // formato '2025-01'
			$fecha_inicio = $mes . '-01';
			$fecha_fin = date("Y-m-t", strtotime($fecha_inicio)); // último día del mes*/
			
			// Fechas dinámicas
			$fecha_inicio = date('Y-m-01'); // Primer día del mes actual
			$fecha_fin = date('Y-m-t');     // Último día del mes actual
			$consulta_datos="SELECT SEDE, lugar_nombre, count(Alumno) ALUMNOS_ENTRENAN, sum(Pagos_Realizados) PAGOSRECEPTADOS, SUM(Valor_Recaudado) TOTALRECAUDADO, SUM(PENSION) TOTALPENSIONES
								FROM(SELECT DISTINCT sede_id, sede_nombre SEDE, alumno_id, alumno_identificacion, concat(alumno_primernombre,' ', alumno_segundonombre, ' ', alumno_apellidopaterno) Alumno,
													sum(PAGOS) Pagos_Realizados, sum(VALOR_PAGADO) Valor_Recaudado, 
													horario_detalle, lugar_nombre, IFNULL(descuento_valor,sede_pension) PENSION, descuento_detalle DETALLE, descuento_fecha
										FROM(SELECT sede_id IdSede, sede_nombre SEDEALUMNO, alumno_id IdAlumno, count(distinct alumno_id) ALUMNOS_ENTRENAN, 
													R.catalogo_descripcion RUBRO, 
													count(*) PAGOS, 
													SUM(((P.pago_saldo + P.pago_valor) - (IFNULL(PT.transaccion_valorcalculado, P.pago_saldo))))VALOR_PAGADO
													FROM alumno_pago P
														inner join general_tabla_catalogo R ON R.catalogo_valor = P.pago_rubroid 
														left join sujeto_alumno A on A.alumno_id = P.pago_alumnoid 
														inner join general_sede S on S.sede_id = alumno_sedeid
														LEFT JOIN(SELECT PT.transaccion_pagoid, MIN(PT.transaccion_id) IDT
																	FROM alumno_pago_transaccion PT
																	WHERE PT.transaccion_estado = 'C'
																	GROUP BY PT.transaccion_pagoid)T ON T.transaccion_pagoid = P.pago_id
														LEFT JOIN alumno_pago_transaccion PT ON PT.transaccion_id  = T.IDT
													WHERE pago_estado <> 'E'
														and pago_rubroid = 'RPE'
														and alumno_estado = 'A'
														and pago_fecha between ' ".$fecha_inicio." ' and ' ".$fecha_fin."'
													GROUP BY IdSede, SEDEALUMNO, IdAlumno, RUBRO
																
											UNION 
																		
											SELECT sede_id IdSede, sede_nombre SEDEALUMNO, alumno_id IdAlumno, count(distinct alumno_id) ALUMNOS_ENTRENAN,
												CONCAT_WS(' ', R.catalogo_descripcion, ' - Abono') RUBRO, 
												count(*) PAGOS,
												SUM(transaccion_valor) VALOR_PAGADO
											FROM alumno_pago P
												inner join general_tabla_catalogo R ON R.catalogo_valor = P.pago_rubroid
												left join sujeto_alumno A on A.alumno_id = P.pago_alumnoid 
												left join alumno_pago_transaccion T on T.transaccion_pagoid = P.pago_id 							
												inner join general_sede S on S.sede_id = alumno_sedeid
											WHERE transaccion_estado <> 'E'
												and pago_rubroid = 'RPE'
												and alumno_estado = 'A'
												/*and transaccion_valor > 0*/
												and transaccion_fecha between ' ".$fecha_inicio." ' and ' ".$fecha_fin."'
											GROUP BY IdSede, SEDEALUMNO, IdAlumno, RUBRO) CONSOLIDADA
											RIGHT JOIN sujeto_alumno on alumno_id = IdAlumno
											left join asistencia_asignahorario on asignahorario_alumnoid = alumno_id
											inner join asistencia_horario AH on horario_id = asignahorario_horarioid
											LEFT JOIN(SELECT detalle_horarioid HORARIOID, detalle_lugarid, count(1) TOTAL
														FROM asistencia_horario_detalle
														GROUP BY detalle_horarioid, detalle_lugarid)TOTAL ON TOTAL.HORARIOID = AH.horario_id
											INNER JOIN asistencia_lugar on detalle_lugarid = lugar_id
											LEFT JOIN general_sede on sede_id = lugar_sedeid			
											LEFT JOIN (SELECT descuento_id, descuento_alumnoid, descuento_valor, descuento_detalle, descuento_fecha 
															FROM alumno_pago_descuento WHERE descuento_estado = 'S') AS Descuento ON descuento_alumnoid = alumno_id	
											WHERE AH.horario_estado <> 'E'
												and lugar_estado = 'A'
										GROUP BY sede_id, sede_nombre, alumno_id, alumno_identificacion, alumno_primernombre, alumno_segundonombre, alumno_apellidopaterno, horario_detalle, lugar_nombre, sede_pension,
													descuento_valor, descuento_detalle, descuento_fecha) DETALLEINGRESO
									GROUP BY SEDE, lugar_nombre
									ORDER BY TOTALPENSIONES DESC";

			$datos = $this->ejecutarConsulta($consulta_datos);
			//$datos = $datos->fetchAll();

			/*$labels = [];
			$alumnos = [];
			$pagos = [];
			$recaudado = [];
			$pensiones = [];

			foreach($datos as $rows){
				$labels[] = $rows['lugar_nombre'];
				$alumnos[] = (int)$rows['ALUMNOS_ENTRENAN'];
				$pagos[] = (int)$rows['PAGOSRECEPTADOS'];
				$recaudado[] = (float)$rows['TOTALRECAUDADO'];
				$pensiones[] = (float)$rows['TOTALPENSIONES'];
			}

			echo json_encode([
			'labels' => $labels,
			'alumnos' => $alumnos,
			'pagos' => $pagos,
			'recaudado' => $recaudado,
			'pensiones' => $pensiones
			]);

			foreach($datos as $rows){
				$tabla.='
					<tr>
						<td>'.$rows['SEDE'].'</td>
						<td>'.$rows['lugar_nombre'].'</td>
						<td>'.$rows['ALUMNOS_ENTRENAN'].'</td>
						<td>'.$rows['PAGOSRECEPTADOS'].'</td>
						<td>'.$rows['TOTALRECAUDADO'].'</td>
						<td>'.$rows['TOTALPENSIONES'].'</td>
					</tr>';	
			}
			$tabla.='
				<tr data-widget="expandable-table" aria-expanded="false">
					<td colspan="5">TOTAL</td>
					<td style="text-align: right">'.number_format($VALOR_RECAUDADO, 2, '.',',').'</td>			
				</tr>';	

			return $tabla;	*/
			return $datos;		
		}
	}

		