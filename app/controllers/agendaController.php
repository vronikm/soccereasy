<?php

	namespace app\controllers;
	use app\models\mainModel;
	use \DateTime;
	
	class agendaController extends mainModel{		
		public function registrarEvento(){

            $agenda_title   = $this->limpiarCadena($_POST['agenda_title']);
            $agenda_detail  = $this->limpiarCadena($_POST['agenda_detail']);
            $agenda_start   = $this->limpiarCadena($_POST['agenda_start']);
            $agenda_end     = $this->limpiarCadena($_POST['agenda_end']);
            $agenda_color   = $this->limpiarCadena($_POST['agenda_color']);

			if($agenda_title=="" || $agenda_start=="" || $agenda_end==""){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error",
					"texto"=>"No ha completado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		    }

			$agenda_reg=[
                [
                    "campo_nombre"=>"agenda_title",
                    "campo_marcador"=>":Titulo",
                    "campo_valor"=>$agenda_title
                ],
                [
                    "campo_nombre"=>"agenda_detail",
                    "campo_marcador"=>":Detalle",
                    "campo_valor"=> $agenda_detail
                ],
                [
                    "campo_nombre"=>"agenda_start",
                    "campo_marcador"=>":Start",
                    "campo_valor"=> $agenda_start
                ],
                [
                    "campo_nombre"=>"agenda_end",
                    "campo_marcador"=>":End",
                    "campo_valor"=> $agenda_end
                ],
                [
                    "campo_nombre"=>"agenda_color",
                    "campo_marcador"=>":Color",
                    "campo_valor"=> $agenda_color
                ]
            ];		

            $registrar_evento=$this->guardarDatos("general_agenda",$agenda_reg);

            if($registrar_evento->rowCount()>0){
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Evento registrado",
                    "texto"=>"El evento se registró correctamente",
                    "icono"=>"success"
                ];
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo registrar el evento, por favor intente nuevamente",
                    "icono"=>"error"
                ];
            }
            return json_encode($alerta);
		}

        public function obtenerEventos() {
            $consulta_evento="SELECT agenda_title AS title, agenda_start AS start, agenda_end AS end, agenda_color AS color FROM general_agenda";	
            $datos = $this->ejecutarConsulta($consulta_evento);
            $eventos = array();
            if($datos->rowCount()>=0){
				while ($row = $datos->fetch()) {
					$eventos[]=[
						"title"=>$row['title'],
						"start"=>$row['start'],
						"end"=>$row['end'],
						"color"=>$row['color'],
					];		
				}
			}
            // Devuelve los eventos como JSON
            echo json_encode($eventos);
        }
        
        public function BuscarAlumno($alumnoid){		
			$consulta_datos="SELECT S.sede_nombre, CASE WHEN alumno_estado = 'A' THEN 'Activo' WHEN alumno_estado = 'I' THEN 'Inactivo' ELSE 'Sin definir' END estado, Year(alumno_fechanacimiento) anio
					,CASE WHEN IFNULL(R.total, 0) > 0 THEN 1 ELSE 0 END pendiente,  IFNULL(R.total, 0) total
					,A.* 
					FROM sujeto_alumno A
					LEFT JOIN general_sede S ON S.sede_id = A.alumno_sedeid
					LEFT JOIN(
						SELECT COUNT(RP.pago_id) total, RA.alumno_id alumno
						FROM sujeto_alumno RA
						INNER JOIN alumno_pago RP ON RP.pago_alumnoid = RA.alumno_id
						WHERE RP.pago_estado = 'P'
						GROUP BY RA.alumno_id
					)R ON R.alumno = A.alumno_id
				WHERE A.alumno_id = ".$alumnoid;	
			$datos = $this->ejecutarConsulta($consulta_datos);
			return $datos;
		}
        public function CalendarioEventos($alumnoid){
			// Consulta para obtener los eventos
			$consulta_evento = "SELECT      
									asistencia_aniomes AS anio_mes, 
									STR_TO_DATE(CONCAT(asistencia_aniomes, LPAD(SUBSTRING_INDEX(dia, 'D', -1), 2, '0')), '%Y%m%d') AS 'start', 
									STR_TO_DATE(CONCAT(asistencia_aniomes, LPAD(SUBSTRING_INDEX(dia, 'D', -1), 2, '0')), '%Y%m%d') AS 'end', 
									CASE 
										WHEN valor = 'P' THEN 'PRESENTE'
										WHEN valor = 'A' THEN 'ATRASO'
										WHEN valor = 'J' THEN 'JUSTIFICADO'
										WHEN valor = 'F' THEN 'FALTA'
									END AS title,
									CASE 
										WHEN valor = 'P' THEN '#007bff'
										WHEN valor = 'A' THEN '#ffc107'
										WHEN valor = 'J' THEN '#6c757d'
										WHEN valor = 'F' THEN '#dc3545'
									END AS color    
								FROM (
									SELECT asistencia_aniomes, 'D01' AS dia, asistencia_D01 AS valor FROM asistencia_asistencia WHERE asistencia_D01 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D02' AS dia, asistencia_D02 AS valor FROM asistencia_asistencia WHERE asistencia_D02 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D03' AS dia, asistencia_D03 AS valor FROM asistencia_asistencia WHERE asistencia_D03 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D04' AS dia, asistencia_D04 AS valor FROM asistencia_asistencia WHERE asistencia_D04 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D05' AS dia, asistencia_D05 AS valor FROM asistencia_asistencia WHERE asistencia_D05 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D06' AS dia, asistencia_D06 AS valor FROM asistencia_asistencia WHERE asistencia_D06 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D07' AS dia, asistencia_D07 AS valor FROM asistencia_asistencia WHERE asistencia_D07 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D08' AS dia, asistencia_D08 AS valor FROM asistencia_asistencia WHERE asistencia_D08 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D09' AS dia, asistencia_D09 AS valor FROM asistencia_asistencia WHERE asistencia_D09 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D10' AS dia, asistencia_D10 AS valor FROM asistencia_asistencia WHERE asistencia_D10 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D11' AS dia, asistencia_D11 AS valor FROM asistencia_asistencia WHERE asistencia_D11 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D12' AS dia, asistencia_D12 AS valor FROM asistencia_asistencia WHERE asistencia_D12 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D13' AS dia, asistencia_D13 AS valor FROM asistencia_asistencia WHERE asistencia_D13 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D14' AS dia, asistencia_D14 AS valor FROM asistencia_asistencia WHERE asistencia_D14 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D15' AS dia, asistencia_D15 AS valor FROM asistencia_asistencia WHERE asistencia_D15 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D16' AS dia, asistencia_D16 AS valor FROM asistencia_asistencia WHERE asistencia_D16 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D17' AS dia, asistencia_D17 AS valor FROM asistencia_asistencia WHERE asistencia_D17 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D18' AS dia, asistencia_D18 AS valor FROM asistencia_asistencia WHERE asistencia_D18 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D19' AS dia, asistencia_D19 AS valor FROM asistencia_asistencia WHERE asistencia_D19 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D20' AS dia, asistencia_D20 AS valor FROM asistencia_asistencia WHERE asistencia_D20 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid."1 UNION ALL
									SELECT asistencia_aniomes, 'D21' AS dia, asistencia_D21 AS valor FROM asistencia_asistencia WHERE asistencia_D21 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D22' AS dia, asistencia_D22 AS valor FROM asistencia_asistencia WHERE asistencia_D22 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D23' AS dia, asistencia_D23 AS valor FROM asistencia_asistencia WHERE asistencia_D23 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D24' AS dia, asistencia_D24 AS valor FROM asistencia_asistencia WHERE asistencia_D24 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D25' AS dia, asistencia_D25 AS valor FROM asistencia_asistencia WHERE asistencia_D25 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D26' AS dia, asistencia_D26 AS valor FROM asistencia_asistencia WHERE asistencia_D26 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D27' AS dia, asistencia_D27 AS valor FROM asistencia_asistencia WHERE asistencia_D27 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D28' AS dia, asistencia_D28 AS valor FROM asistencia_asistencia WHERE asistencia_D28 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D29' AS dia, asistencia_D29 AS valor FROM asistencia_asistencia WHERE asistencia_D29 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D30' AS dia, asistencia_D30 AS valor FROM asistencia_asistencia WHERE asistencia_D30 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid." UNION ALL
									SELECT asistencia_aniomes, 'D31' AS dia, asistencia_D31 AS valor FROM asistencia_asistencia WHERE asistencia_D31 IS NOT NULL AND asistencia_alumnoid = ".$alumnoid."
								) AS dias";

			$datos = $this->ejecutarConsulta($consulta_evento);

			$eventos = array();

			if($datos->rowCount()>=0){
				while ($row = $datos->fetch()) {
					$eventos[]=[
						"title"=>$row['title'],
						"start"=>$row['start'],
						"end"=>$row['end'],
						"color"=>$row['color'],//"#2324ff",
					];		
				}
			}
			
			return json_encode($eventos);
		}
    }
