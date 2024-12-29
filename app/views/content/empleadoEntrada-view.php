<?php
	date_default_timezone_set("America/Guayaquil");

	use app\controllers\empleadoController;
	$insEmpleado = new empleadoController();	

	$estado_es = '';
	$texto 	   = '';

	$datos			  = $insEmpleado->BuscarUsuario($_SESSION['identificacion']);
	$datos_asistencia = $insEmpleado->BuscarMarcacion($_SESSION['identificacion']);

	if($datos_asistencia->rowCount()==1){
		$datos_asistencia=$datos_asistencia->fetch(); 
		if ($datos_asistencia['asistencia_tipo']!=""){
			if ($datos_asistencia['asistencia_tipo']=="E"){			
				$estado_es = 'S';
				$texto = 'Salida';
			}else{
				$estado_es = 'E';
				$texto = 'Entrada';
			}		
		}else{
			$estado_es = 'E';
			$texto = 'Entrada';
		}					
	}else{
		$estado_es = 'E';
		$texto = 'Entrada';
	}

	
	if($datos->rowCount()==1){
		$datos=$datos->fetch(); 
		$empleadoid=$datos["empleado_id"];

		if ($datos['empleado_foto']!=""){
			$foto = APP_URL.'app/views/imagenes/fotos/empleado/'.$datos['empleado_foto'];
		}else{
			$foto=APP_URL.'app/views/dist/img/default.jpg';
		}	
	}else{
		include "<?php echo APP_URL; ?>/app/views/inc/error_alert.php";
	}
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo APP_NAME; ?> | Registro de asistencia</title>

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
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/css/adminlte.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/css/sweetalert2.min.css">
	<!-- fileinput -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/fileinput/fileinput.css">
    
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
						<div class="col-sm-12">
							<h5 id ="fecha" style="text-align:center"></h5>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">				
				<!-- /.container-fluid información alumno -->
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3">	
							<!-- Profile Image -->
							<div class="card card-primary card-outline">
								<div class="card-body box-profile">
									<div class="text-center">
										<img class="profile-user-img img-fluid img-circle"
											src="<?php echo $foto; ?>"
											alt="User profile picture">
									</div>

									<h3 class="profile-username text-center"><?php echo $datos['empleado_nombre'] ; ?></h3>

									<p class="text-muted text-center"><?php echo $datos['empleado_identificacion']; ?></p>

									<ul class="list-group list-group-unbordered mb-3">
										<li class="list-group-item">
											<b>Entrenador</b> <a class="float-right"><?php echo $datos['Especialidad']; ?></a>
										</li>
										<li class="list-group-item">
											<b>Fecha de ingreso</b> <a class="float-right"><?php echo $datos['empleado_fechaingreso']; ?></a>
										</li>
										<li class="list-group-item">
											<b>Sede empleado</b> <a class="float-right"><?php echo $datos['sede_nombre']; ?></a>
										</li>
										<li class="list-group-item">
											<b>Estado empleado</b> <a class="float-right"><?php echo $datos['Estado']; ?></a>
										</li>
									</ul>
								</div>
								<!-- /.card-body -->
							</div>
							<!-- /.card -->
						</div>
						<div class="col-md-9">
							<div class="card">							
								<div class="card-body">
									<div class="tab-content">
										
										<!-- Mostrar coordenadas capturadas -->
										

										<!-- Formulario para enviar las coordenadas -->
										<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/empleadoAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >
											<input type="hidden" name="modulo_asistencia" value="coordenadas">											
											<input type="hidden" id="latitude" name="latitude">
											<input type="hidden" id="longitude" name="longitude">
											<input type="hidden" id="fechahora" name="fechahora">
											<input type="hidden" id="estado_es" name="estado_es" value="<?php echo $estado_es; ?>" >
											<input type="hidden" id="empleadoid" name="empleadoid" value="<?php echo $empleadoid; ?>" >
																						
											<div class="col-md-2">
												<div class="form-group">
													<label for="alumno_apellido2">Registrar: </label>
													<button type="submit" class="form-control btn btn-info" style="text-align:center"><?php echo $texto; ?></button>
												</div>
											</div>
										</form>

										<div class="tab-custom-content">
											<h5 class="card-title">Marcaciones registradas</h5>
										</div>
										
										<div class="tab-content" id="custom-content-above-tabContent" style="font-size: 13px;">
											<table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 13px;">
												<thead>
													<tr>
														<th>Fecha</th>
														<th>Hora</th>
														<th>Tipo</th>
														<th>Ubicación</th>														
													</tr>
												</thead>
												<tbody>
													<?php 
														echo $insEmpleado->listarMarcaciones($empleadoid, date("d/m/Y")); 
													?>							
												</tbody>	
											</table>
										</div>	

										<h6>Tu Ubicación Actual</h6>
										<!-- Contenedor del mapa -->
										<div id="map" style="width: 100%; height: 500px; border: 1px solid #ccc;">
											<iframe
												id="mapFrame"
												src=""
												width="100%"
												height="100%"
												style="border:0;"
												allowfullscreen=""
												loading="lazy">
											</iframe>
										</div>	
										<p id="coords">Obteniendo coordenadas...</p>									
									</div>
										<!-- /.tab-pane -->									
								</div>
							<!-- /.tab-content -->
							</div><!-- /.card-body -->
						</div>
							<!-- /.card -->
					</div>
				</div>
			</section>
		<!-- /.content --> 
		</div>     
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
	<!-- AdminLTE App -->
	<script src="<?php echo APP_URL; ?>app/views/dist/js/adminlte.min.js"></script>		
	<script src="<?php echo APP_URL; ?>app/views/dist/js/ajax.js" ></script>	
	<!-- fileinput -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/fileinput/fileinput.js"></script>    
	<script src="<?php echo APP_URL; ?>app/views/dist/js/sweetalert2.all.min.js" ></script>

	<script>
        setInterval(() => {
            let fecha=new Date();
            let fechaHora=fecha.toLocaleString();
            document.getElementById("fecha").textContent=fechaHora;
			document.getElementById("fechahora").value=fechaHora;
        }, 1000);

    </script>

<script>
    function obtenerCoordenadas() {
        if (navigator.geolocation) {
            // Opciones para mejorar la precisión de la geolocalización
            const opciones = {
                enableHighAccuracy: true,  // Usar la mayor precisión disponible
                timeout: 15000,  // Tiempo máximo de espera para obtener la ubicación (5 segundos)
                maximumAge: 0  // No usar una posición anterior almacenada
            };

            navigator.geolocation.getCurrentPosition(function (position) {
                // Coordenadas capturadas
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                // Asignar valores a los campos del formulario
                document.getElementById("latitude").value = latitude;
                document.getElementById("longitude").value = longitude;

                // Mostrar coordenadas en la página (opcional)
                document.getElementById("coords").innerText = `Latitud: ${latitude}, Longitud: ${longitude}, Precisión: ${position.coords.accuracy} metros`;
           
				 // Actualizar el iframe del mapa con las coordenadas
				 const mapFrame = document.getElementById("mapFrame");
        		mapFrame.src = `https://www.google.com/maps?q=${latitude},${longitude}&z=15&output=embed`;
				
		    }, function (error) {
                console.error("Error al obtener la ubicación:", error);
                alert("No se pudo obtener tu ubicación. Asegúrate de habilitar la geolocalización.");
            }, opciones);
        } else {
            alert("Tu navegador no soporta geolocalización.");
        }
    }

    // Llama a la función para obtener las coordenadas al cargar la página
    window.onload = obtenerCoordenadas;
</script>
  </body>
</html>