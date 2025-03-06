<?php
	use app\controllers\alumnoController;
	$insAlumno = new alumnoController();	

	$alumnoid=$insAlumno->limpiarCadena($url[1]);

	$alumno_generoM 	= "";
	$alumno_generoF 	= "";
	$alumno_hermanosSi	= "";
	$alumno_hermanosNo	= "";
	$repre_facturaS		= "";
	$repre_facturaN		= "";

	$datos=$insAlumno->seleccionarDatos("Unico","sujeto_alumno","alumno_id",$alumnoid);
	if($datos->rowCount()==1){
		$datos=$datos->fetch();
		if ($datos['alumno_imagen']!=""){
			$foto = APP_URL.'app/views/imagenes/fotos/alumno/'.$datos['alumno_imagen'];
		}else{
			$foto = APP_URL.'app/views/dist/img/foto.jpg';
		}
		
		if ($datos['alumno_genero']=='M'){
			$alumno_generoM = "checked";
		}else{
			$alumno_generoF = "checked";
		}

		if ($datos['alumno_hermanos']=='S'){
			$alumno_hermanosSi = "checked";
		}else{
			$alumno_hermanosNo = "checked";
		}
	
		if ($datos['alumno_cedulaA']!=""){
			$cedulaA = APP_URL.'app/views/imagenes/cedulas/'.$datos['alumno_cedulaA'];
		}else{
			$cedulaA = APP_URL.'app/views/imagenes/cedulas/Sinregistro.jpg';
		}

		if ($datos['alumno_cedulaR']!=""){
			$cedulaR = APP_URL.'app/views/imagenes/cedulas/'.$datos['alumno_cedulaR'];
		}else{
			$cedulaR = APP_URL.'app/views/imagenes/cedulas/Sinregistro.jpg';
		}

	$representante=$insAlumno->datosRepresentante($alumnoid);
	if($representante->rowCount()==1){
		$representante=$representante->fetch();
		$repre_identificacion = $representante['IDENTIFICACION'];
		$repre_nombre 		  = $representante['REPRESENTANTE'];
		$repre_parentesco	  = $representante['PARENTESCO'];
		$repre_direccion	  = $representante['repre_direccion'];
		$repre_correo		  = $representante['repre_correo'];
		$repre_celular	  	  = $representante['repre_celular'];

		if ($representante['repre_factura']=='S'){
			$repre_facturaS = "checked";
		}else{
			$repre_facturaN = "checked";
		}
	}

	$datosmedic=$insAlumno->seleccionarDatos("Unico","alumno_infomedic","infomedic_alumnoid",$alumnoid);
	if($datosmedic->rowCount()==1){
		$datosmedic=$datosmedic->fetch();
		
		$tipo_sangre	=$datosmedic['infomedic_tiposangre'];
		$peso			=$datosmedic['infomedic_peso'];
		$talla			=$datosmedic['infomedic_talla'];
		$enfermedad		=$datosmedic['infomedic_enfermedad'];
		$medicamentos	=$datosmedic['infomedic_medicamentos'];
		$alergia1		=$datosmedic['infomedic_alergia1'];
		$alergia2		=$datosmedic['infomedic_alergia2'];
		$cirugias		=$datosmedic['infomedic_cirugias'];
		$observacion	=$datosmedic['infomedic_observacion'];
		
		if ($datosmedic['infomedic_covid']=='S'){
			$infomedic_covidSi = "checked";
		}else{
			$infomedic_covidSi = "";
		}

		if ($datosmedic['infomedic_covid']=='N'){
			$infomedic_covidNo = "checked";
		}else{
			$infomedic_covidNo = "";
		}
		if ($datosmedic['infomedic_vacunas']=='S'){
			$infomedic_vacunasSi = "checked";
		}else{
			$infomedic_vacunasSi = "";
		}
		if ($datosmedic['infomedic_vacunas']=='N'){
			$infomedic_vacunasNo = "checked";
		}else{
			$infomedic_vacunasNo = "";
		}

	}else{
		$tipo_sangre		="";
		$peso				="";
		$talla				="";
		$enfermedad			="";
		$medicamentos		="";
		$alergia1			="";
		$alergia2			="";
		$cirugias			="";
		$observacion		="";
		$infomedic_covidSi 	= "";
		$infomedic_covidNo	= "";
		$infomedic_vacunasSi = "";
		$infomedic_vacunasNo = "";
	}

	$datoscemer=$insAlumno->seleccionarDatos("Unico","alumno_cemergencia","cemer_alumnoid",$alumnoid);
	if($datoscemer->rowCount()==1){
		$datoscemer=$datoscemer->fetch();
		$cemer_nombre			=$datoscemer['cemer_nombre'];
		$cemer_celular			=$datoscemer['cemer_celular'];
		$cemer_parentesco		=$datoscemer['cemer_parentesco'];	

	}else{
		$cemer_nombre		="";
		$cemer_celular		="";
		$cemer_parentesco	="";
	}

	$horario_id=$insAlumno->HorarioID($alumnoid);
	if($horario_id->rowCount()==1){
		$horario_id=$horario_id->fetch(); 
		$horarioid = $horario_id['asignahorario_horarioid'];
    }else{
		$horarioid = 0;
	}


	$datoshorario=$insAlumno->seleccionarDatos("Unico","asistencia_horario","horario_id",$horarioid);
	if($datoshorario->rowCount()==1){
		$datoshorario=$datoshorario->fetch();
		$lugar_sedeid 		= $datoshorario['horario_sedeid'];
		$horario_nombre 	= $datoshorario['horario_nombre'];
		$horario_detalle	= $datoshorario['horario_detalle'];
		$horario_estado		= $datoshorario['horario_estado'];
	}else{
		$lugar_sedeid = isset($_POST['horario_sedeid']) ? $insHorario->limpiarCadena($_POST['horario_sedeid']) : 0;
		$horario_nombre 	= "";
		$horario_detalle	= "";
		$horario_estado		= "";
	}

?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo APP_NAME; ?> | Ficha alumno</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/fontawesome-free/css/all.min.css">
	
	<!-- daterange picker -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/daterangepicker/daterangepicker.css">
	<!-- iCheck for checkboxes and radio inputs -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- Bootstrap Color Picker -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
	<!-- Tempusdominus Bootstrap 4 -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/select2/css/select2.min.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
	<!-- Bootstrap4 Duallistbox -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
	<!-- BS Stepper -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/bs-stepper/css/bs-stepper.min.css">
	<!-- dropzonejs -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/dropzone/min/dropzone.min.css">
	
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/css/adminlte.css">


	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/css/sweetalert2.min.css">
	<script src="<?php echo APP_URL; ?>app/views/dist/js/sweetalert2.all.min.js" ></script>

	<!-- fileinput -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/fileinput/fileinput.css">
    
	<style>
		input:invalid {
		  box-shadow: 0 0 2px 1px red;
		}
		input:focus:invalid {
		  box-shadow: none;
		}
		textarea:invalid {
		  box-shadow: 0 0 2px 1px red;
		}
		textarea:focus:invalid {
		  box-shadow: none;
		}
	</style>
  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

		<!-- Preloader -->
		<!--?php require_once "app/views/inc/preloader.php"; ?-->
		<!-- /.Preloader -->

		<!-- Navbar -->
		<?php require_once "app/views/inc/navbar.php"; ?>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<?php require_once "app/views/inc/main-sidebar.php"; ?>
		<!-- /.Main Sidebar Container -->  

		<!-- vista -->
		<div class="content-wrapper">

			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0">Ficha Alumno</h1>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="#">Inicio</a></li>
								<li class="breadcrumb-item active">Ficha Alumno</li>
							</ol>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">				
				<!-- /.container-fluid información alumno -->
				<div class="container-fluid">						
					<div class="card">
						<div class="card-header p-2">
							<ul class="nav nav-pills">
								<li class="nav-item"><a class="nav-link active" href="#informacionp" data-toggle="tab">Información Personal</a></li>
								<li class="nav-item"><a class="nav-link" href="#cedula" data-toggle="tab">Cédula</a></li>
								<li class="nav-item"><a class="nav-link" href="#representante" data-toggle="tab">Representante</a></li>
								<li class="nav-item"><a class="nav-link" href="#contactoem" data-toggle="tab">Contacto emergencia</a></li>											
								<li class="nav-item"><a class="nav-link" href="#informacionm" data-toggle="tab">Información Médica</a></li>
								<li class="nav-item"><a class="nav-link" href="#horario" data-toggle="tab">Horario</a></li>
							</ul>
						</div><!-- /.card-header -->
					
						<div class="card-body">
							<div class="tab-content">
								<!-- Tab de información personal del alumno -->
								<div class="active tab-pane" id="informacionp"> 
									<!-- Primera sección foto-->
									<div class="row">
										<div class="col-md-2">
											<label for="alumno_foto">Foto</label>	
											<div class="text-left"> 
												<img class="profile-user-img img-fluid " style="width: 148px; height: 184px;" src="<?php echo $foto; ?>" alt="User profile picture">                        										
											</div>		
										</div>
										<div class="col-md-10"> 
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label for="alumno_identificacion">Identificación</label>                        
														<input type="text" class="form-control" id="alumno_identificacion" name="alumno_identificacion" value="<?php echo $datos['alumno_identificacion']; ?>" disabled="">
													</div>
												</div>											
												<div class="col-md-4">                        
													<div class="form-group">
														<label for="alumno_apellido1">Apellido paterno</label>
														<input type="text" class="form-control" id="alumno_apellido1" name="alumno_apellido1" value="<?php echo $datos['alumno_apellidopaterno']; ?>" disabled="">
													</div>
												</div>
												<div class="col-md-4">
													<label for="alumno_apellido2">Apellido materno</label>
													<input type="text" class="form-control" id="alumno_apellido2" name="alumno_apellido2" value="<?php echo $datos['alumno_apellidomaterno']; ?>" disabled="">
												</div>
												<div class="col-sm-4">
													<div class="form-group">
														<label for="alumno_tipoidentificacion">Tipo identificación</label>
														<select id="alumno_tipoidentificacion" class="form-control select2" name="alumno_tipoidentificacion" disabled="">																					
															<?php echo $insAlumno->listarOptionTipoIdentificacion($datos['alumno_tipoidentificacion']); ?>
														</select>
													</div>          
												</div>
												<div class="col-md-4">                        
													<div class="form-group">
														<label for="alumno_nombre1">Primer nombre</label>
														<input type="text" class="form-control" id="alumno_nombre1" name="alumno_nombre1" value="<?php echo $datos['alumno_primernombre']; ?>" disabled="">
													</div>
												</div>
												<div class="col-md-4">
													<label for="alumno_nombre2">Segundo nombre</label>
													<input type="text" class="form-control" id="alumno_nombre2" name="alumno_nombre2" value="<?php echo $datos['alumno_segundonombre']; ?>" disabled="">
												</div>    
												<div class="col-md-4">
													<div class="form-group">
														<label for="alumno_nacionalidadid">Nacionalidad</label>
														<select class="form-control select2" style="width: 100%;" id="alumno_nacionalidadid" name="alumno_nacionalidadid" disabled="">
															<?php echo $insAlumno->listarOptionNacionalidad($datos['alumno_nacionalidadid']); ?>
														</select>
													</div> 
												</div>
												<div class="col-md-4">									
													<label for="alumno_fechanacimiento">Fecha nacimiento</label>
													<input type="text" class="form-control" name="alumno_fechanacimiento" value="<?php echo $datos['alumno_fechanacimiento']; ?>" disabled="">											
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for="alumno_fechaingreso">Fecha ingreso</label>
														<input type="text" class="form-control" name="alumno_fechaingreso" value="<?php echo $datos['alumno_fechaingreso']; ?>" disabled="">
													<!-- /.input group -->
													</div>								
												</div>
											</div>
											<!-- Fin primera sección foto-->
										</div>
									</div> <!--fin col md 10-->

									<!-- Segunda sección foto-->
									<div class="row">
										<div class="col-md-2">
											<div class="form-group">
												<label for="Numcamiseta">Número de camiseta</label>
												<input type="text" class="form-control" id="alumno_numcamiseta" name="alumno_numcamiseta" value="<?php echo $datos['alumno_numcamiseta']; ?>" disabled=""> 
											</div>
										</div>   
										<div class="col-md-2">
											<div class="form-group">
												<label for="alumno_sedeid">Sede</label>
												<select class="form-control select2" id="alumno_sedeid" name="alumno_sedeid" disabled="">									
													<?php echo $insAlumno->listarSedeAlumno($datos['alumno_sedeid']); ?>
												</select>	
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="alumno_direccion">Dirección</label>
												<input type="text" class="form-control" id="alumno_direccion" name="alumno_direccion" value="<?php echo $datos['alumno_direccion']; ?>" disabled="">
											</div>	
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="alumno_hermanos">Tiene hermanos?</label>
												<!-- radio -->
												<div class="form-check">
													<input class="col-sm-1 form-check-input" type="radio" id="alumno_hermanosSi" name="alumno_hermanos" value="S" <?php echo $alumno_hermanosSi; ?> disabled="">
													<label class="col-sm-6 form-check-label" for="alumno_hermanosSi">Si</label>
													<input class="col-sm-1 form-check-input" type="radio" id="alumno_hermanosNo" name="alumno_hermanos" value="N" <?php echo $alumno_hermanosNo; ?> disabled="">
													<label class="col-sm-4 form-check-label" for="alumno_hermanosNo">No</label>
												</div>
											</div>
										</div>	
										<div class="col-md-3">
											<div class="form-group">
												<label for="alumno_genero">Sexo</label>
												<div class="form-check">
													<input class="col-sm-1 form-check-input" type="radio" id="alumno_generoM" name="alumno_genero" value="M" <?php echo $alumno_generoM; ?> disabled="">
													<label class="col-sm-5 form-check-label" for="alumno_generoM">Masculino</label>
													<input class="col-sm-1 form-check-input" type="radio" id="alumno_generoF" name="alumno_genero" value="F" <?php echo $alumno_generoF; ?> disabled="">
													<label class="col-sm-4 form-check-label" for="alumno_generoF">Femenino</label>
												</div> 
											</div>
										</div>     
									</div>  <!--./row line 874--> 
									<!-- Fin segunda sección foto-->			
								</div>

								<!-- Tab información contacto de emergencia -->
								<div class="tab-pane" id="representante">
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="repre_identificacion">Identificación</label>
												<input type="text" class="form-control" id="repre_identificacion" name="repre_identificacion" value="<?php echo $repre_identificacion;?>" disabled="" >                          
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="repre_nombre">Nombre</label>
												<input type="text" class="form-control" id="repre_nombre" name="repre_nombre" value="<?php echo $repre_nombre;?>" disabled="" >
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="repre_parentesco">Parentesco</label>
												<input type="text" class="form-control" id="repre_parentesco" name="repre_parentesco" value="<?php echo $repre_parentesco;?>" disabled="" >
											</div> 
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="repre_celular">Celular</label>
												<input type="text" class="form-control" id="repre_celular" name="repre_celular" value="<?php echo $repre_celular; ?>" disabled="">
											</div> 
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="repre_direccion">Dirección</label>
												<input type="text" class="form-control" id="repre_direccion" name="repre_direccion" value="<?php echo $repre_direccion; ?>" disabled="">
											</div>
										</div>              
										<div class="col-md-3">
											<div class="form-group">
												<label for="repre_correo">Correo</label>
												<input type="text" class="form-control" id="repre_correo" name="repre_correo" value="<?php echo $repre_correo; ?>" disabled="">
											</div> 
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="repre_factura">Requiere factura</label>
												<div class="form-check">
													<input class="col-sm-1 form-check-input" type="radio" id="repre_facturaS" value="S" name="repre_factura" <?php echo $repre_facturaS;?> disabled="">
													<label class="col-sm-5 form-check-label" for="repre_facturaS">Si</label>
													<input class="col-sm-1 form-check-input" type="radio" id="repre_facturaN" value="N" name="repre_factura" <?php echo $repre_facturaN;?> disabled="">
													<label class="col-sm-4 form-check-label" for="repre_facturaN">No</label>
												</div> 
											</div>
										</div>
									</div>		
								</div>

								<!-- Tab de información médica del alumno -->
								<div class="tab-pane" id="informacionm">
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="infomedic_tiposangre">Tipo de sangre</label>
												<input type="text" class="form-control" id="infomedic_tiposangre" name="infomedic_tiposangre" value="<?php echo $tipo_sangre;?>" disabled="" >                          
											</div>
										</div> 
										<div class="col-md-3">
											<div class="form-group">
												<label for="Peso">Peso (Kg)</label>
												<input type="text" class="form-control" id="infomedic_peso" name="infomedic_peso"  value="<?php echo $peso;?>" disabled="">                          
											</div>
										</div>   
										<div class="col-md-3">
											<div class="form-group">
												<label for="Talla">Talla (cm)</label>
												<input type="text" class="form-control" id="infomedic_talla" name="infomedic_talla"  value="<?php echo $talla;?>" disabled="" >                          
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="Enfermedad">Enfermedad diagnosticada</label>
												<input type="text" class="form-control" id="infomedic_enfermedad" name="infomedic_enfermedad"  value="<?php echo $enfermedad;?>" disabled="" >                          
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="Medicamentos">Medicamentos</label>
												<input type="text" class="form-control" id="infomedic_medicamentos" name="infomedic_medicamentos"  value="<?php echo $medicamentos;?>" disabled="" >                          
											</div>
										</div> 
										<div class="col-md-3">
											<div class="form-group">
												<label for="Alergia1">Alergia a medicamentos</label>
												<input type="text" class="form-control" id="infomedic_alergia1" name="infomedic_alergia1"  value="<?php echo $alergia1;?>" disabled="" >                          
											</div>
										</div> 
										<div class="col-md-3">
											<div class="form-group">
												<label for="Alergia2">Alergia a objetos</label>
												<input type="text" class="form-control" id="infomedic_alergia2" name="infomedic_alergia2"  value="<?php echo $alergia2;?>" disabled="" >                          
											</div>
										</div>  
										<div class="col-md-3">
											<div class="form-group">
												<label for="Cirugias">Cirugías</label>
												<input type="text" class="form-control" id="infomedic_cirugias" name="infomedic_cirugias"  value="<?php echo $cirugias;?>" disabled="" >                          
											</div>
										</div>  
										<div class="col-md-3">
											<div class="form-group">
												<label for="Observacion">Observación</label>
												<input type="text" class="form-control" id="infomedic_observacion" name="infomedic_observacion"  value="<?php echo $observacion;?>" disabled="" >                          
											</div>
										</div>  
										<div class="col-md-3">
											<div class="form-group">
												<label for="Covid">Carnet vacunación Covid</label>
												<div class="form-check">
													<input class="col-sm-1 form-check-input" type="radio" id="infomedic_covidSi" name="infomedic_covid" value="S" <?php echo $infomedic_covidSi;?> disabled="" > 
													<label class="col-sm-6 form-check-label" for="infomedic_covidSi">Si</label>
													<input class="col-sm-1 form-check-input" type="radio" id="infomedic_covidNo" name="infomedic_covid" value="N" <?php echo $infomedic_covidNo;?> disabled="" >
													<label class="col-sm-4 form-check-label" for="infomedic_covidNo">No</label>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="Vacunas">Carnet vacunación habitual</label>
												<div class="form-check">
													<input class="col-sm-1 form-check-input" type="radio" id="infomedic_vacunasSi" name="infomedic_vacunas" value="S" <?php echo $infomedic_vacunasSi;?> disabled="" > 
													<label class="col-sm-6 form-check-label" for="infomedic_vacunasSi">Si</label>
													<input class="col-sm-1 form-check-input" type="radio" id="infomedic_vacunasNo" name="infomedic_vacunas" value="N" <?php echo $infomedic_vacunasNo;?> disabled="" >
													<label class="col-sm-4 form-check-label" for="infomedic_vacunasNo">No</label>
												</div>                         
											</div>
										</div>  
									</div>
								</div>

								<!-- Tab información contacto de emergencia -->
								<div class="tab-pane" id="contactoem">
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="CEmergencia">Celular</label>
												<input type="text" class="form-control" id="cemer_celular" name="cemer_celular" value="<?php echo $cemer_celular;?>" disabled="" >                          
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="Nomcontactoemer">Nombre contacto</label>
												<input type="text" class="form-control" id="cemer_nombre" name="cemer_nombre" value="<?php echo $cemer_nombre;?>" disabled="" >                          
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="cemer_parentesco">Parentesco</label>
												<select class="form-control select2" style="width: 100%;" id="cemer_parentesco" name="cemer_parentesco" disabled="" >
													<?php echo $insAlumno->listarOptionParentesco($cemer_parentesco); ?>
												</select>
											</div> 
										</div>
									</div>		
								</div>

								<!-- Tab cedula del alumno -->
								<div class="tab-pane" id="cedula">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="alumno_cedulaA">Anverso</label>		
												<div class="text-left">
													<img class="profile-user-img img-fluid " style="width: 330px; height: 210px;" src="<?php echo $cedulaA; ?>" alt="Cedula Anverso" disabled=""> 
												</div>
											</div>
										</div>		
											<!-- /.form-group -->	
										<div class="col-md-4">
											<div class="form-group">
												<label for="alumno_cedulaR">Reverso</label>		
												<div class="text-left">
													<img class="profile-user-img img-fluid " style="width: 330px; height: 210px;" src="<?php echo $cedulaR; ?>" alt="Cedula Reverso" disabled=""> 
												</div>
											</div>
										</div>	
									</div>							
								</div>

								<!-- Tab cedula del alumno -->
								<div class="tab-pane" id="horario">
									<div class="container-fluid">
										<div class="row">											
											<div class="col-12">
												<!-- Main content -->
												<div class="invoice p-3 mb-3">							
													
													<!-- Table row -->
													<div class="row">
														<div class="col-12 table-responsive">
															<table class="table table-striped table-bordered  table-sm">											
																<tbody>
																	<tr>													
																		<th colspan="8">Horario <?php echo $horario_nombre.". ".$horario_detalle; ?></th>																							
																	</tr>
																	<tr>		
																		<th></th>												
																		<th>LUNES</th>	
																		<th>MARTES</th>
																		<th>MIERCOLES</th>
																		<th>JUEVES</th>
																		<th>VIERNES</th>																																		
																	</tr>													
																		<?php echo $datos=$insAlumno->generarHorarioProfile($horarioid);	?>																		
																</tbody>
															</table>
														</div>
														<!-- /.col -->
													</div>
													
												</div>
												<!-- /.invoice -->							
											</div><!-- /.col -->
											
										</div><!-- /.row -->
									</div><!-- /.container-fluid -->
								</div>
								<!-- /.tab-pane -->								
							</div>
							<!-- /.tab-content -->
						</div><!-- /.card-body -->
					</div>
					<!-- /.card -->
				</div>
			</section>
			<!-- /.content -->    
			
			<?php
				}else{
					include "./app/views/inc/error_alert.php";
				}
			?>
		</div>
		<!-- /.vista -->

		<?php require_once "app/views/inc/footer.php"; ?>

		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
		<!-- Control sidebar content goes here -->
		</aside>
      <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    
	<!-- jQuery -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	
	<!-- Select2 -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/select2/js/select2.full.min.js"></script>
	<!-- Bootstrap4 Duallistbox -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
	<!-- InputMask -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/moment/moment.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/inputmask/jquery.inputmask.min.js"></script>
	<!-- date-range-picker -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/daterangepicker/daterangepicker.js"></script>
	<!-- bootstrap color picker -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
	<!-- Tempusdominus Bootstrap 4 -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
	<!-- Bootstrap Switch -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
	<!-- BS-Stepper -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/bs-stepper/js/bs-stepper.min.js"></script>
	<!-- dropzonejs -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/dropzone/min/dropzone.min.js"></script>

	<!-- AdminLTE App -->
	<script src="<?php echo APP_URL; ?>app/views/dist/js/adminlte.min.js"></script>
		
	<script src="<?php echo APP_URL; ?>app/views/dist/js/ajax.js" ></script>

	<!--script src="app/views/dist/js/main.js" ></script-->
	
	<!-- fileinput -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/fileinput/fileinput.js"></script>
    
	<script>
		$(function () {
			//Initialize Select2 Elements
			$('.select2').select2()

			//Initialize Select2 Elements
			$('.select2bs4').select2({
			theme: 'bootstrap4'
			})

			//Datemask dd/mm/yyyy
			$('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
			//Datemask2 mm/dd/yyyy
			$('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
			//Money Euro
			$('[data-mask]').inputmask()

			//Date picker
			$('#reservationdate').datetimepicker({
				format: 'L'
			});

			//Date and time picker
			$('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });

			//Date range picker
			$('#reservation').daterangepicker()
			//Date range picker with time picker
			$('#reservationtime').daterangepicker({
			timePicker: true,
			timePickerIncrement: 30,
			locale: {
				format: 'MM/DD/YYYY hh:mm A'
			}
			})
			//Date range as a button
			$('#daterange-btn').daterangepicker(
			{
				ranges   : {
				'Today'       : [moment(), moment()],
				'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month'  : [moment().startOf('month'), moment().endOf('month')],
				'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				},
				startDate: moment().subtract(29, 'days'),
				endDate  : moment()
			},
			function (start, end) {
				$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
			}
			)

			//Timepicker
			$('#timepicker').datetimepicker({
			format: 'LT'
			})

			//Bootstrap Duallistbox
			$('.duallistbox').bootstrapDualListbox()

			//Colorpicker
			$('.my-colorpicker1').colorpicker()
			//color picker with addon
			$('.my-colorpicker2').colorpicker()

			$('.my-colorpicker2').on('colorpickerChange', function(event) {
			$('.my-colorpicker2 .fa-square').css('color', event.color.toString());
			})

			$("input[data-bootstrap-switch]").each(function(){
			$(this).bootstrapSwitch('state', $(this).prop('checked'));
			})

		})
		// BS-Stepper Init
		document.addEventListener('DOMContentLoaded', function () {
			window.stepper = new Stepper(document.querySelector('.bs-stepper'))
		})

		// DropzoneJS Demo Code Start
		Dropzone.autoDiscover = false

		// Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
		var previewNode = document.querySelector("#template")
		previewNode.id = ""
		var previewTemplate = previewNode.parentNode.innerHTML
		previewNode.parentNode.removeChild(previewNode)

		var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
			url: "/target-url", // Set the url
			thumbnailWidth: 80,
			thumbnailHeight: 80,
			parallelUploads: 20,
			previewTemplate: previewTemplate,
			autoQueue: false, // Make sure the files aren't queued until manually added
			previewsContainer: "#previews", // Define the container to display the previews
			clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
		})

		myDropzone.on("addedfile", function(file) {
			// Hookup the start button
			file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
		})

		// Update the total progress bar
		myDropzone.on("totaluploadprogress", function(progress) {
			document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
		})

		myDropzone.on("sending", function(file) {
			// Show the total progress bar when upload starts
			document.querySelector("#total-progress").style.opacity = "1"
			// And disable the start button
			file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
		})

		// Hide the total progress bar when nothing's uploading anymore
		myDropzone.on("queuecomplete", function(progress) {
			document.querySelector("#total-progress").style.opacity = "0"
		})

		// Setup the buttons for all transfers
		// The "add files" button doesn't need to be setup because the config
		// `clickable` has already been specified.
		document.querySelector("#actions .start").onclick = function() {
			myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
		}
		document.querySelector("#actions .cancel").onclick = function() {
			myDropzone.removeAllFiles(true)
		}
		// DropzoneJS Demo Code End
	</script>
  </body>
</html>