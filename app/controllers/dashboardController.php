<?php
	namespace app\controllers;
	use app\models\mainModel;

	class dashboardController extends mainModel{

		/*----------  Obtener total alumnos activos  ----------*/
		public function obtenerAlumnosActivosSedeL(){
			$alumnosActivosSedeL=$this->ejecutarConsulta("SELECT count(*) totalActivosSedeL FROM sujeto_alumno WHERE alumno_estado='A' and alumno_sedeid = 1");
		    return $alumnosActivosSedeL;
		}

		public function obtenerAlumnosActivosSedeC(){
			$alumnosActivosSedeC=$this->ejecutarConsulta("SELECT count(*) totalActivosSedeC FROM sujeto_alumno WHERE alumno_estado='A' and alumno_sedeid = 2");
		    return $alumnosActivosSedeC;
		}

        public function obtenerAlumnosActivosSedeV(){
			$alumnosActivosSedeV=$this->ejecutarConsulta("SELECT count(*) totalActivosSedeV FROM sujeto_alumno WHERE alumno_estado='A' and alumno_sedeid = 3");
		    return $alumnosActivosSedeV;
		}

		/*----------  Obtener total alumnos inactivos  ----------*/
		public function obtenerAlumnosInactivosSedeL(){
			$alumnosActivosSedeL=$this->ejecutarConsulta("SELECT count(*) totalInactivosSedeL FROM sujeto_alumno WHERE alumno_estado='I' and alumno_sedeid = 1");
		    return $alumnosActivosSedeL;
		}

		public function obtenerAlumnosInactivosSedeC(){
			$alumnosActivosSedeC=$this->ejecutarConsulta("SELECT count(*) totalInactivosSedeC FROM sujeto_alumno WHERE alumno_estado='I' and alumno_sedeid = 2");
		    return $alumnosActivosSedeC;
		}

        public function obtenerAlumnosInactivosSedeV(){
			$alumnosActivosSedeV=$this->ejecutarConsulta("SELECT count(*) totalInactivosSedeV FROM sujeto_alumno WHERE alumno_estado='I' and alumno_sedeid = 3");
		    return $alumnosActivosSedeV;
		}

		/*----------  Obtener total pagos cancelados  ----------*/
		public function obtenerPagosCanceladoSedeL($sede_id){
			$pagosCanceladoSedeL=$this->ejecutarConsulta("SELECT sum(totalCanceladoSedeL) totalCanceladoSedeL from (
                                                            SELECT COUNT(*) totalCanceladoSedeL 
																FROM alumno_pago, sujeto_alumno 
																WHERE pago_alumnoid = alumno_id 
																	AND alumno_sedeid = ".$sede_id." 
																	AND pago_estado <> 'E'
                                                            UNION ALL
                                                            SELECT COUNT(*) totalCanceladoSedeL 
																FROM alumno_pago, alumno_pago_transaccion, sujeto_alumno 
																WHERE pago_alumnoid = alumno_id 
                                                                	AND pago_id = transaccion_pagoid 
																	AND alumno_sedeid = ".$sede_id." 
																	AND transaccion_estado<> 'E') AS DATOS");
			return $pagosCanceladoSedeL;
		}

		public function obtenerPagosCanceladoSedeC(){
			$pagosCanceladoSedeC=$this->ejecutarConsulta("SELECT COUNT(*) totalCanceladoSedeC FROM alumno_pago, sujeto_alumno WHERE pago_alumnoid = alumno_id AND alumno_sedeid = 2 AND pago_estado='C';");
			return $pagosCanceladoSedeC;
		}

        public function obtenerPagosCanceladoSedeV(){
			$pagosCanceladoSedeV=$this->ejecutarConsulta("SELECT COUNT(*) totalCanceladoSedeV FROM alumno_pago, sujeto_alumno WHERE pago_alumnoid = alumno_id AND alumno_sedeid = 3 AND pago_estado='C';");
			return $pagosCanceladoSedeV;
		}

		/*----------  Obtener total pagos pendientes  ----------*/
		public function obtenerPagosPendienteSedeL($sedeid){
			$pagosPendienteSedeL=$this->ejecutarConsulta("SELECT SUM(IFNULL(subconsulta.NUM_SALDO,0)) + SUM(IFNULL(subconsulta.NUM_PENSION,0)) as totalPendienteSedeL 
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
            WHERE 
                pago_rubroid = 'RPE' AND alumno_sedeid = ".$sedeid."
            GROUP BY 
                pago_alumnoid
        ) BASE
    ) PEN ON PEN.pago_alumnoid = A.alumno_id
    WHERE PEN.TOTAL > 0 OR P.SALDO > 0 
) AS subconsulta;");
			return $pagosPendienteSedeL;
		}
	}

		