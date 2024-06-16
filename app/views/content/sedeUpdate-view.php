<?php
	use app\controllers\escuelaController;
	$insEscuela = new escuelaController();	

	$sede=$insEscuela->limpiarCadena($url[1]);

	$datos=$insEscuela->seleccionarDatos("Unico","general_sede","sede_id",$sede);
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo APP_NAME; ?>| Información de la sede</title>

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
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/css/adminlte.min.css">

	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/css/sweetalert2.min.css">
	<script src="<?php echo APP_URL; ?>app/views/dist/js/sweetalert2.all.min.js" ></script>

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
					<div class="col-sm-6">
						<h1 class="m-0">Actualización de la sede</h1>
					</div><!-- /.col -->
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="#">Nuevo</a></li>
							<li class="breadcrumb-item active">Dashboard v1</li>
						</ol>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->

		<div class="card-body">	
			<?php
				if($datos->rowCount()==1){
					$datos=$datos->fetch();							
			?>
				
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
			<!-- Small boxes (Stat box) -->
				<div class="card card-default">
					<div class="card-header">
						<h3 class="card-title">Sede: <?php echo $datos['sede_nombre']; ?></h3>
						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
						</div>
					</div>

					<!-- card-body -->					
					<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/escuelaAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >
					<input type="hidden" name="modulo_sede" value="actualizar">	
					<input type="hidden" name="sede_id" value="<?php echo $datos['sede_id']; ?>">
					<input type="hidden" name="sede_escuelaid" value="<?php echo $insEscuela->ObtenerEscuelaControlador(); ?>">

						<div class="card-body">	
							<!-- row -->	
							<div class="row">						
								<!-- /.col -->
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-3">
										<div class="form-group">
											<label for="sede_nombre">Nombre</label>
											<input type="text" class="form-control" id="sede_nombre" name="sede_nombre" placeholder="Nombre sede" value="<?php echo $datos['sede_nombre']; ?>">
										</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sede_email">Correo</label>
												<input type="email" class="form-control" id="sede_email" name="sede_email" placeholder="Correo" value="<?php echo $datos['sede_email']; ?>">	
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="sede_telefono">Celular</label>
												<input type="text" class="form-control" id="sede_telefono" name="sede_telefono" placeholder="Teléfono, celular" value="<?php echo $datos['sede_telefono']; ?>">	 	
											</div>
										</div>
										<div class="col-md-8">
											<div class="form-group">
												<label for="sede_direccion">Dirección</label>
												<input type="text" class="form-control" id="sede_direccion" name="sede_direccion" placeholder="Dirección de la sede" value="<?php echo $datos['sede_direccion']; ?>">	
											</div>
										</div>									
									</div>
									<!-- /.form-group -->								
								</div>
								<!-- /.col -->							
							</div>
							<!-- /.row -->							
						</div> 
						<!-- /.card-body -->
						<div class="card-footer">						
							<button type="submit" class="btn btn-success btn-sm">Guardar</button>					
							<a href="#" class="btn btn-dark btn-back btn-sm">Regresar</a>
						</div>
				<?php
					}else{
						include "./app/views/inc/error_alert.php";
					}
				?>
					
				</div>
			<!-- /.row -->
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->
      
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
	<!-- fileinput -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/fileinput/fileinput.js"></script>
   
	
	<?php include "./app/views/inc/btn_back.php";	?>

  </body>
</html>