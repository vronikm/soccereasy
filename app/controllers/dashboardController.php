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
			$consulta_datos="SELECT sede_nombre, lugar_nombre, ALUMNOS_ENTRENAN, IFNULL(PA.VALOR_PAGADO,0) + IFNULL(Abonos.VALOR_PAGADO,0) as TOTALRECAUDADO, IFNULL(PA.Numero,0) + IFNULL(Abonos.Numero,0) as PAGOSRECEPTADOS, TOTALPENSIONES
								FROM(select sede_id, sede_nombre, lugar_id ,lugar_nombre, TA.TotalAlumnos ALUMNOS_ENTRENAN, (sede_pension * TA.TotalAlumnos) as TOTALPENSIONES
										from general_sede
										left join asistencia_lugar on lugar_sedeid = sede_id
										left join (select count(*) as TotalAlumnos, T.detalle_lugarid LugarEntrena
															from (SELECT distinct detalle_lugarid, asignahorario_alumnoid 
																		from asistencia_asignahorario
																		left join asistencia_horario_detalle on detalle_horarioid = asignahorario_horarioid
																		left join sujeto_alumno on alumno_id = asignahorario_alumnoid 
																		where alumno_estado = 'A' and alumno_fechaingreso <= ' ".$fecha_fin."')T
																group by T.detalle_lugarid) TA on TA.LugarEntrena = lugar_id
										where lugar_estado <> 'E'
										union 
										
										select SLE.sede_id, SLE.sede_nombre, 0,'SIN LUGAR DE ENTRENAMIENTO' lugar_nombre, count(1) as TotalAlumnos, sum(SLE.pension_estimada) pension_estimada
												FROM(select sede_id, sede_nombre, 0,'SIN LUGAR DE ENTRENAMIENTO' lugar_nombre, IFNULL(descuento_valor,s.sede_pension) pension_estimada
														from sujeto_alumno a
														left join alumno_pago_descuento d on d.descuento_alumnoid = a.alumno_id and descuento_estado = 'S' and descuento_fecha <= ' ".$fecha_fin."'                
														left join general_sede s on s.sede_id = a.alumno_sedeid
														where a.alumno_id not in (select asignahorario_alumnoid from asistencia_asignahorario)
														and a.alumno_estado = 'A' and a.alumno_fechaingreso <= ' ".$fecha_fin."') SLE
												group by SLE.sede_id, SLE.sede_nombre
								)Base
								left join(select Pagos.sedeid, Pagos.lugarid, sum(IFNULL(Pagos.VALOR_PAGADO,0)) VALOR_PAGADO, count(1) Numero 
												from(select ((P.pago_saldo + P.pago_valor) - (IFNULL(PT.transaccion_valorcalculado, P.pago_saldo)))
														as VALOR_PAGADO, IFNULL(h.detalle_lugarid,0)  AS lugarid, A.alumno_sedeid as sedeid
														from alumno_pago P 
														inner JOIN sujeto_alumno A on A.alumno_id = P.pago_alumnoid 
														LEFT JOIN(SELECT transaccion_pagoid, MIN(transaccion_id) IDT
																	FROM alumno_pago_transaccion
																	WHERE transaccion_estado = 'C'
																			GROUP BY transaccion_pagoid)T ON T.transaccion_pagoid = P.pago_id                  
														LEFT JOIN alumno_pago_transaccion PT ON PT.transaccion_id  = T.IDT     
														left join(SELECT distinct detalle_lugarid, asignahorario_alumnoid 
																		from asistencia_asignahorario
																		left join asistencia_horario_detalle on detalle_horarioid = asignahorario_horarioid)h on h.asignahorario_alumnoid = P.pago_alumnoid
																		where P.pago_rubroid = 'RPE' 
																				and P.pago_estado not in ('E','J') 
																				and P.pago_fecharegistro BETWEEN ' ".$fecha_inicio." ' and ' ".$fecha_fin."') Pagos   
												group by Pagos.sedeid, Pagos.lugarid)PA on PA.sedeid = Base.sede_id AND PA.lugarid = Base.lugar_id
								left join (select A.alumno_sedeid as sedeid, IFNULL(h.detalle_lugarid,0) AS lugarid, sum(IFNULL(T.transaccion_valor,0)) as VALOR_PAGADO  ,Count(1) as Numero
												from alumno_pago_transaccion T
												inner join alumno_pago P on P.pago_id = T.transaccion_pagoid and P.pago_rubroid = 'RPE'
												inner join sujeto_alumno A on A.alumno_id = P.pago_alumnoid                          
												left join(SELECT distinct detalle_lugarid, asignahorario_alumnoid 
																from asistencia_asignahorario
																left join asistencia_horario_detalle on detalle_horarioid = asignahorario_horarioid  
														)h on h.asignahorario_alumnoid = P.pago_alumnoid          
												where transaccion_estado in ('C')        
												and transaccion_fecharegistro BETWEEN ' ".$fecha_inicio." ' and ' ".$fecha_fin."' 
												group by sedeid, lugarid
										)Abonos on Abonos.sedeid = Base.sede_id AND Abonos.lugarid = Base.lugar_id 
							order by Base.sede_id";

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

		