<?php
	use app\controllers\torneosController;
	$insTorneo = new torneosController();
	
	$torneoid = ($url[1] != "") ? $url[1] : 0;	

	$foto = APP_URL.'app/views/imagenes/fotos/torneos/torneo_default.jpg';

	if($torneoid != 0){
		$datosTorneo=$insTorneo->BuscarTorneo($torneoid);		
		if($datosTorneo->rowCount()==1){
			$datosTorneo=$datosTorneo->fetch(); 
			if ($datosTorneo['torneo_foto']!=""){
				$foto = APP_URL.'app/views/imagenes/fotos/torneos/'.$datosTorneo['torneo_foto'];
			}else{
				$foto = APP_URL.'app/views/dist/img/torneo_default.jpg';
			}
			$modulo_torneos = 'actualizar';			

			$torneo_nombre = $datosTorneo['torneo_nombre'];
			$torneo_ciudad = $datosTorneo['torneo_ciudad'];
			$torneo_lugar = $datosTorneo['torneo_lugar'];
			$torneo_fechainicio = $datosTorneo['torneo_fechainicio'];
			$torneo_fechafin = $datosTorneo['torneo_fechafin'];
			$torneo_organizador = $datosTorneo['torneo_organizador'];
			$torneo_descripcion = $datosTorneo['torneo_descripcion'];
			$torneo_estado = $datosTorneo['ESTADO'];
			
		}
	}else{
		$modulo_torneos = 'registrar';
		$torneo_nombre = '';
		$torneo_ciudad = '';		
		$torneo_lugar = '';
		$torneo_fechainicio = '';
		$torneo_fechafin = '';
		$torneo_organizador = '';
		$torneo_descripcion = '';
		$torneo_estado = 'A';
	}
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo APP_NAME; ?>| Torneos</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/fontawesome-free/css/all.min.css">
		<!-- daterange picker -->
		<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/daterangepicker/daterangepicker.css">
	<!-- iCheck for checkboxes and radio inputs -->
	 <!-- DataTables -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
	
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
					<h4 class="m-0">Torneos</h4>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Inicio</a></li>
						<li class="breadcrumb-item active"><a href="<?php echo APP_URL."dashboard/" ?>">Dashboard</a></li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->

		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
			<!-- Small boxes (Stat box) -->
				<div class="card card-default">
					<div class="card-header" class="centered" >
						<h4 class="card-title">Ingreso de nuevo torneo</h4>
						<div class="card-tools">
							<!--a href="<?php echo APP_URL; ?>torneosNew/" class="btn btn-primary btn-sm" >Nuevo Torneo</a-->
							<button type="button" class="btn btn-tool" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
						</div>
					</div>

					<div class="card-body">
						<div class="row">
							<div class="col-md-12">	
								<form class="FormularioAjax" id="quickForm" action="<?php echo APP_URL; ?>app/ajax/torneosAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >
									<input type="hidden" name="modulo_torneos" value="<?php echo $modulo_torneos; ?>">
									<input type="hidden" name="torneo_id" value="<?php echo $torneoid; ?>">
									<div class="row" style="font-size: 13px;">
										<div class="col-md-2">
											<div class="form-group">
												<label for="torneo_foto">Foto</label>		
												<div class="input-group">											
													<div class="fileinput fileinput-new" data-provides="fileinput">
														<div class="fileinput-new thumbnail" style="width: 110px; height: 130px;" data-trigger="fileinput"><img src="<?php echo $foto; ?>"> </div>
														<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 116px; max-height: 144px"></div>
														<div>
															<span class="bton bton-white bton-file" style="font-size: 13px;">
																<span class="fileinput-new">Seleccionar Foto</span>
																<span class="fileinput-exists">Cambiar</span>
																<input type="file" name="torneo_foto" id="foto" accept="image/*">
															</span>
															<a href="#" class="bton bton-orange fileinput-exists" data-dismiss="fileinput">Remover</a>
														</div>
													</div>
												</div>		
											</div>
										<!-- /.form-group -->								
										</div>
										<div class="col-sm-10">
											<div class="row" style="font-size: 13px;">
												<div class="col-md-3">
													<div class="form-group">
														<label for="torneo_nombre">Nombre torneo</label>
														<input type="text"  class="form-control select2" id="torneo_nombre" name="torneo_nombre" value="<?php echo $torneo_nombre; ?>">
													</div> 
												</div>
												<div class="col-md-3">										
													<div class="form-group">
														<label for="torneo_ciudad">Ciudad</label>
														<input type="text" class="form-control" id="torneo_ciudad" name="torneo_ciudad" value="<?php echo $torneo_ciudad; ?>">
													</div>
												</div>
												<div class="col-md-3">										
													<div class="form-group">
														<label for="torneo_lugar">Lugar</label>
														<input type="text" class="form-control" id="torneo_lugar" name="torneo_lugar"value="<?php echo $torneo_lugar; ?>">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label for="torneo_organizador">Organizador</label>
														<input type="text" class="form-control" id="torneo_organizador" name="torneo_organizador" value="<?php echo $torneo_organizador; ?>">	
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label for="torneo_fechainicio">Fecha de inicio</label>
														<div class="input-group">
															<div class="input-group-prepend">
																<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
															</div>
															<input type="date" class="form-control" name="torneo_fechainicio" id="torneo_fechainicio" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask value="<?php echo $torneo_fechainicio; ?>">
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label for="torneo_fechafin">Fecha de finalización</label>
														<div class="input-group">
															<div class="input-group-prepend">
																<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
															</div>
															<input type="date" class="form-control" name="torneo_fechafin" id="torneo_fechafin" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask value="<?php echo $torneo_fechafin; ?>">
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="torneo_descripcion">Descripción</label>
														<input type="text" class="form-control" id="torneo_descripcion" name="torneo_descripcion" value="<?php echo $torneo_descripcion; ?>">
													</div>	
												</div>	
												<div class="col-md-12">						
													<button type="submit" class="btn btn-success btn-xs">Guardar</button>
													<a href="<?php echo APP_URL; ?>torneosList/" class="btn btn-info btn-xs">Cancelar</a>
													<button type="reset" class="btn btn-dark btn-xs">Limpiar</button>						
												</div>	
											</div>
										</div>
									</div>									
								</form>		
								
								<div class="tab-custom-content">
									<h4 class="card-title">Torneos ingresados</h4>
								</div>										
								<div class="tab-content" id="custom-content-above-tabContent" style="font-size: 13px;">	
									<table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 13px;">
										<thead>
											<tr>
												<th style="width: 130px;">Nombre</th>
												<th>Ciudad</th>
												<th>Lugar</th>
												<th style="width: 80px;">Fecha inicio</th>
												<th style="width: 60px;">Fecha fin</th>
												<th>Organizador</th>
												<th style="width: 240px;">Descripción</th>
												<th>Estado</th>
												<th style="width: 215px;">Opciones</th>
											</tr>
										</thead>
										<tbody>
											<?php 
												echo $insTorneo->listarTorneos(); 
											?>							
										</tbody>	
									</table>
								</div>
							</div>	
						</div>
					</div>
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
	<!-- DataTables  & Plugins -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/jszip/jszip.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/pdfmake/pdfmake.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/pdfmake/vfs_fonts.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-buttons/js/buttons.print.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo APP_URL; ?>app/views/dist/js/adminlte.min.js"></script>
	<!-- fileinput -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/fileinput/fileinput.js"></script>
    	
	<!-- Page specific script -->
	<script>
		$(function () {
			$("#example1").DataTable({
			"responsive": true, "lengthChange": false, "autoWidth": false,
			}).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');			    
		});
	</script>

	<script src="<?php echo APP_URL; ?>app/views/dist/js/ajax.js" ></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/js/main.js" ></script>
    
  </body>
</html>








