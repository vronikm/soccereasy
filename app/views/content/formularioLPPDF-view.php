<?php	
    setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'esp');
    use app\controllers\representanteController;

    include 'app/lib/fpdf.php';

    $insfrmLPD = new representanteController();	
	$repreid = $insfrmLPD->limpiarCadena($url[1]);

	$datos=$insfrmLPD->datosRepresentante($repreid);
	
	if($datos->rowCount()==1){
		$datos=$datos->fetch();
		$repre_sedeid = $datos['SEDE'];	
	}else{
		include "<?php echo APP_URL; ?>/app/views/inc/error_alert.php";
	}

	$sede=$insfrmLPD->informacionSede($repre_sedeid);
	if($sede->rowCount()==1){
		$sede=$sede->fetch(); 
		$sede_nombre  = $sede['sede_nombre'];
		$sede_email   = $sede['sede_email'];
        $sede_logo    = $sede['sede_foto'];
	}
    $logo=APP_URL.'app/views/imagenes/fotos/sedes/'.$sede_logo;

    // Configura el idioma español
    $formatter = new IntlDateFormatter(
        'es_ES', // Idioma y región
        IntlDateFormatter::LONG, // Formato de fecha
        IntlDateFormatter::SHORT // Formato de hora
    );

    // Obtén la fecha actual
    $fechaHora = $formatter->format(new DateTime());

    // Crear una clase personalizada para el PDF
    class PDF extends FPDF {
        public $title;
        public $subtitle;
        public $logoPath;

        // Método para establecer datos dinámicos
        public function __construct($title = '', $logoPath = '') {
            parent::__construct(); // Llamar al constructor de la clase FPDF
            $this->title    = $title;
            $this->logoPath = $logoPath;            
        }

        // Agregar un encabezado
        function Header() {
            if (!empty($this->logoPath)) {
                $this->Image($this->logoPath, 10, 6, 30);
            }       
          
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(0, 5, mb_convert_encoding('FORMULARIO DE CONSENTIMIENTO INFORMADO DE RECOLECCIÓN ', 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
            $this->Cell(0, 5, mb_convert_encoding('Y TRATAMIENTO DE DATOS PERSONALES', 'ISO-8859-1', 'UTF-8'), 0, 1,'R');
            $this->Cell(0, 5, $this->title, 0, 1,'R');
            $this->Ln(5);
        }

        // Agregar un pie de página
        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 10);
            $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
        }
        
        // Método para agregar viñetas
        function AddBullet($text, $indent = 5) {
            $this->Cell($indent); // Indentación
            $this->Cell(5, 5, chr(149), 0, 0, 'L'); // Símbolo de viñeta
            $this->MultiCell(0, 5, $text, 0, 'L');
        }
    }

    $titulo = mb_convert_encoding("ESCUELA INDEPENDIENTE DEL VALLE ".$sede_nombre, 'ISO-8859-1', 'UTF-8');

    $pdf = new PDF($titulo, $logo);
    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 10);

    // Título de la sección
    $pdf->Cell(0, 5, '1. Datos del Titular y Representante del alumno', 0, 2, 'L');

    $pdf->SetFont('Arial', '', 9);
    // Lista de viñetas
    $pdf->AddBullet(mb_convert_encoding('Nombre del representante: '.$datos["REPRESENTANTE"], 'ISO-8859-1', 'UTF-8'),5);
    $pdf->AddBullet(mb_convert_encoding('Identificación del representante: '.$datos["repre_identificacion"], 'ISO-8859-1', 'UTF-8'),5);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 10, '2. Finalidad del Tratamiento de Datos', 0, 2, 'L');
    
    $pdf->SetFont('Arial', '', 9);
    // Agregar contenido del formulario
    $text2 = mb_convert_encoding("En Escuela INDEPENDIENTE DEL VALLE $sede_nombre, recolectamos y tratamos los datos personales de nuestros alumnos y sus representantes legales con las siguientes finalidades:", 'ISO-8859-1', 'UTF-8');
       
    // Escribir el texto en el PDF
    $pdf->MultiCell(0,5, $text2);

    $pdf->AddBullet(mb_convert_encoding('Gestión administrativa y operativa de la inscripción, pagos mensuales y participación del alumno en las actividades de la Escuela IDV '.$sede_nombre.'.', 'ISO-8859-1', 'UTF-8'),5);
    $pdf->AddBullet(mb_convert_encoding('Comunicación de eventos, horarios de entrenamiento, fechas de torneos, actividades y todo tipo de información relevante.', 'ISO-8859-1', 'UTF-8'),5);
    $pdf->AddBullet(mb_convert_encoding('Atención de situaciones de emergencia, incluyendo acceso a información de contacto y datos médicos básicos proporcionados por el representante legal.', 'ISO-8859-1', 'UTF-8'),5);
    $pdf->AddBullet(mb_convert_encoding('Registro de contenido audiovisual (fotos y videos) para la promoción de actividades en redes sociales y material institucional, siempre con el consentimiento del representante del alumno.', 'ISO-8859-1', 'UTF-8'),5);
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 10, '3. Datos a ser recolectados', 0, 2, 'L');
   
    $pdf->SetFont('Arial', '', 9);
    // Agregar contenido del formulario
    $text3 = mb_convert_encoding("Con el fin de llevar a cabo las finalidades descritas, recolectaremos los siguientes datos personales:", 'ISO-8859-1', 'UTF-8');
    $pdf->MultiCell(0,5, $text3);
    $pdf->AddBullet(mb_convert_encoding('Datos del alumno: Tipo de identificación, número de identificación, apellido paterno, apellido materno, primer nombre, segundo nombre, nacionalidad, fecha de nacimiento, dirección, tiene hermanos, sexo, fotografías del documento de identificación (anverso y reverso).', 'ISO-8859-1', 'UTF-8'),5);
    $pdf->AddBullet(mb_convert_encoding('Contacto de emergencia del alumno: Celular, nombre, parentesco.', 'ISO-8859-1', 'UTF-8'),5);
    $pdf->AddBullet(mb_convert_encoding('Datos del representante: Tipo de identificación, número de identificación, apellido paterno, apellido materno, primer nombre, segundo nombre, parentesco, sexo, dirección, correo, celular y si requiere factura.', 'ISO-8859-1', 'UTF-8'),5);
    $pdf->AddBullet(mb_convert_encoding('Información médica: Tipo de sangre, peso, talla, enfermedad diagnosticada, medicamentos, alergia a medicamentos, alergia a objetos, cirugías, dispone de carnet de vacunación COVID y vacunación habitual.', 'ISO-8859-1', 'UTF-8'),5);
    $pdf->AddBullet(mb_convert_encoding('Contenido audiovisual (fotos y videos): Se podrán tomar y usar imágenes de los alumnos en actividades propias de la escuela con fines promocionales o informativos.', 'ISO-8859-1', 'UTF-8'),5);
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 10, '4. Derechos del Titular de los Datos', 0, 2, 'L');
   
    $pdf->SetFont('Arial', '', 9);
    $text4 = mb_convert_encoding("El titular de los datos o su representante tiene derecho a acceder, rectificar, cancelar u oponerse al tratamiento de los datos personales en cualquier momento, de conformidad con la Ley de Protección de Datos Personales en Ecuador. Para ejercer estos derechos, puede contactarse a través del correo: ".$sede_email.'.', 'ISO-8859-1', 'UTF-8');
    $pdf->MultiCell(0,5, $text4);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 10, '5. Seguridad de los Datos', 0, 2, 'L');
   
    $pdf->SetFont('Arial', '', 9);
    $text5 = mb_convert_encoding("La escuela adopta medidas de seguridad razonables y adecuadas para proteger los datos personales contra el acceso no autorizado, pérdida, destrucción, alteración o uso indebido.", 'ISO-8859-1', 'UTF-8');
    $pdf->MultiCell(0,5, $text5);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 10, '6. Consentimiento', 0, 2, 'L');

    $pdf->SetFont('Arial', '', 9);
    $text6 = mb_convert_encoding("Declaro que he leído y comprendido los términos de este consentimiento y autorizo a la Escuela de Fútbol INDEPENDIENTE DEL VALLE $sede_nombre a recolectar y tratar los datos personales mencionados, en los términos señalados en este documento.", 'ISO-8859-1', 'UTF-8');
    $pdf->MultiCell(0,5, $text6);

    $pdf->SetFont('Arial', '', 9);
    $textl ="__________________________________________________________________________________________________________";
    $pdf->MultiCell(0,0, $textl);

    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 5, '', 0, 2, 'L');
    $text7 = mb_convert_encoding("Sí, autorizo el uso de imágenes del alumno (fotos y videos) en las redes sociales y material promocional de la Escuela de Fútbol INDEPENDIENTE DEL VALLE $sede_nombre.\n Sí, consiento el tratamiento de los datos personales del alumno con las finalidades descritas.", 'ISO-8859-1', 'UTF-8');
    $pdf->MultiCell(0,5, $text7);

    $pdf->SetFont('Arial', '', 9);
    $texti ="__________________________________________________________________________________________________________";
    $pdf->MultiCell(0,1, $texti);

    $pdf->SetFont('Arial', '', 9);
    $textf ="Firma del representante legal: ____________________________                     Fecha:".$fechaHora;
    $pdf->MultiCell(0,20, $textf);

    // Salida del PDF
    $pdf->Output('I', 'Formulario_Proteccion_Datos.pdf');