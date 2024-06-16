<?php
	namespace app\controllers;
	use app\models\mainModel;

	class dashboardController extends mainModel{

		/*----------  Obtener total alumnos activos  ----------*/
		public function obtenerAlumnosActivosSedeL(){
			$alumnosActivosSedeL=$this->ejecutarConsulta("SELECT count(*) totalActivosSedeL FROM sujeto_alumno WHERE alumno_activo='S' and alumno_sedeid = 1");
		    return $alumnosActivosSedeL;
		}

		public function obtenerAlumnosActivosSedeC(){
			$alumnosActivosSedeC=$this->ejecutarConsulta("SELECT count(*) totalActivosSedeC FROM sujeto_alumno WHERE alumno_activo='S' and alumno_sedeid = 2");
		    return $alumnosActivosSedeC;
		}

		/*----------  Obtener total alumnos inactivos  ----------*/
		public function obtenerAlumnosInactivosSedeL(){
			$alumnosActivosSedeL=$this->ejecutarConsulta("SELECT count(*) totalInactivosSedeL FROM sujeto_alumno WHERE alumno_activo='E' and alumno_sedeid = 1");
		    return $alumnosActivosSedeL;
		}

		public function obtenerAlumnosInactivosSedeC(){
			$alumnosActivosSedeC=$this->ejecutarConsulta("SELECT count(*) totalInactivosSedeC FROM sujeto_alumno WHERE alumno_activo='E' and alumno_sedeid = 2");
		    return $alumnosActivosSedeC;
		}

		/*----------  Obtener total pagos cancelados  ----------*/
		public function obtenerPagosCanceladoSedeL(){
			$pagosCanceladoSedeL=$this->ejecutarConsulta("SELECT COUNT(*) totalCanceladoSedeL FROM alumno_pago, sujeto_alumno WHERE pago_alumnoid = alumno_id AND alumno_sedeid = 1 AND pago_estado='C';");
			return $pagosCanceladoSedeL;
		}

		public function obtenerPagosCanceladoSedeC(){
			$pagosCanceladoSedeC=$this->ejecutarConsulta("SELECT COUNT(*) totalCanceladoSedeC FROM alumno_pago, sujeto_alumno WHERE pago_alumnoid = alumno_id AND alumno_sedeid = 2 AND pago_estado='C';");
			return $pagosCanceladoSedeC;
		}

		/*----------  Obtener total pagos pendientes  ----------*/
		public function obtenerPagosPendienteSedeL(){
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
            WHERE 
                pago_rubroid = 'RPE'
            GROUP BY 
                pago_alumnoid
        ) BASE
    ) PEN ON PEN.pago_alumnoid = A.alumno_id
    WHERE PEN.TOTAL > 0 OR P.SALDO > 0
) AS subconsulta;");
			return $pagosPendienteSedeL;
		}

		public function obtenerPagosPendienteSedeC(){
			$pagosPendienteSedeC=$this->ejecutarConsulta("SELECT COUNT(*) totalPendienteSedeC FROM alumno_pago, sujeto_alumno WHERE pago_alumnoid = alumno_id AND alumno_sedeid = 2 AND pago_estado='P';");
			return $pagosPendienteSedeC;
		}
	}

		