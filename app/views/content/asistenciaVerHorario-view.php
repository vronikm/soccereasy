<?php	

	use app\controllers\asistenciaController;

	include 'app/lib/barcode.php';
	
	$generator = new barcode_generator();
	$symbology="qr";
	$optionsQR=array('sx'=>4,'sy'=>4,'p'=>-10);		

	$insHorario = new asistenciaController();	
	$horario_id = ($url[1] != "") ? $insHorario->limpiarCadena($url[1]) : 0;

	$datoshorario=$insHorario->seleccionarDatos("Unico","asistencia_horario","horario_id",$horario_id);
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

	$escuela=$insHorario->informacionEscuela();
	if($escuela->rowCount()==1){
		$escuela=$escuela->fetch(); 
	}	
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo APP_NAME; ?> | Recibo</title>

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
							<h4 class="m-0">Horario <?php echo $horario_nombre; ?></h4>
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
			
			<section class="content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-1">
						</div>
						<div class="col-10">
							<!-- Main content -->
							<div class="invoice p-3 mb-3">							
								<!-- info row -->
								<div class="row invoice-info">
									<div class="col-sm-6 invoice-col">										
										<address class="text-center">												
											<img src="<?php echo APP_URL.'app/views/dist/img/Logos/logo_recibo.jpg' ?>" style="width: 180px; height: 100px;"/>										
											<br>Dirección: <?php echo $escuela["escuela_direccion"]; ?><br>
											Celular: <?php echo $escuela["escuela_movil"]; ?> - LOJA - ECUADOR										
										</address>
									</div>
									<!-- /.col -->
									<div class="col-sm-6 invoice-col">									
										<address class="text-center">	
											<strong class="profile-username">ESCUELA INDEPENDIENTE DEL VALLE LOJA</strong><br>
											De: Luis Roberto Álvarez Granda<br><br>											
											<div class="row">
												<div class="col-12 table-responsive">
													<div class="row">
														<div class="col-4"></div>														
														<div class="col-4">
															<table class="table table-striped table-sm">
																<thead>
																	<tr style="font-size: 14px">
																		<th>DIA</th>
																		<th>MES</th>
																		<th>AÑO</th>																
																	</tr>
																</thead>
																<tbody>
																	<tr style="font-size: 14px">
																		<td><?php echo  date('d', strtotime(date('Y-m-d'))); ?></td>
																		<td><?php echo date('m', strtotime(date('Y-m-d'))); ?></td>
																		<td><?php echo date('Y', strtotime(date('Y-m-d'))); ?></td>												
																	</tr>														
																</tbody>
															</table>
														</div>
														<div class="col-4"></div>
													</div>	
												</div>
												<!-- /.col -->
											</div>
										</address>
									</div>
									<!-- /.col -->								
								</div>
								<!-- Table row -->
								<div class="row">
									<div class="col-12 table-responsive">
										<table class="table table-striped table-bordered  table-sm">											
											<tbody>
												<tr>													
													<th colspan="8">Horario <?php echo $horario_nombre.", ".$horario_detalle; ?></th>																							
												</tr>
												<tr>		
													<th></th>												
													<th>LUNES</th>	
													<th>MARTES</th>
													<th>MIERCOLES</th>
													<th>JUEVES</th>
													<th>VIERNES</th>
													<th>SABADO</th>
													<th>DOMINGO</th>																						
												</tr>													
													<?php echo $datos=$insHorario->generarHorario($horario_id);	?>
																		
											</tbody>
										</table>
									</div>
									<!-- /.col -->
								</div>
								<!-- /.row -->
								<div class="row">
									<div class="col-4">
																				
									</div>
									<!-- accepted payments column -->
									<div class="col-4">										
									</div>

									<div class="col-4">										
										<?php
											('Content-Type: image/svg+xml');
											$svg = $generator->render_svg($symbology,"Horario ".$horario_nombre." | "."\nIDV Loja\n".$escuela["escuela_movil"]."\n".$escuela["escuela_email"], $optionsQR); 
											echo $svg;  
										?>								
									</div>
									<!-- /.col -->
									
									<!-- /.col -->
								</div>
								<!-- /.row -->
								

								<!-- this row will not appear when printing -->
								<div class="row no-print">
									<div class="col-12">

										<!--button type="button" class="btn btn-success float-right btn-sm" style="margin-right: 60px;"><i class="far fa-credit-card"></i> Enviar recibo
										</button>
										<button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
											<i class="fas fa-download"></i> Descargar recibo
										</button-->
										<a href="<?php echo APP_URL.'pagosReciboEnvio/'.$horario_id.'/'; ?> " class="btn btn-success btn-sm float-right" style="margin-right: 135px;"> <i class="fas fa-credit-card"></i> Enviar horario</a>


										<a href="<?php echo APP_URL.'asistenciaHorarioPDF/'.$horario_id.'/'; ?> " class="btn btn-dark float-right btn-sm" style="margin-right: 10px;" target="_blank"> <i class="fas fa-print"></i> Ver horario</a>

										<!--button class="btn btn-dark float-right" style="margin-right: 5px;" onclick="printPage()" ><i class="fas fa-print"></i>Imprimir recibo</button-->
										
										<?php include "./app/views/inc/btn_back.php";?>

									</div>
								</div>
							</div>
							<!-- /.invoice -->							
						</div><!-- /.col -->
						<div class="col-1">
						</div>
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</section>
      
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

	<!--script src="app/views/dist/js/main.js" ></script-->
	
	<!-- fileinput -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/fileinput/fileinput.js"></script>
    
	<script>
        // Esta función se llama cuando el botón es clickeado
        function printPage() {
            window.addEventListener("load", window.print());
        }
    </script>
	

  </body>
</html>