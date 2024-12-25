<?php
	use app\controllers\asistenciaController;
	$insAlumno = new asistenciaController();

	$horario_id = ($url[1] != "") ? $url[1] : 0;

	if($horario_id != 0){
		$horarioSede=$insAlumno->BuscarHorarioSede($horario_id);		
		if($horarioSede->rowCount()==1){
			$horarioSede	=	$horarioSede->fetch();				
		}
	}
	
	if(isset($_POST['fecha'])){
		$fechahoy =  $insAlumno->limpiarCadena($_POST['fecha']);		
	} ELSE{
		$fechahoy = date('Y-m-d');		
	}
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo APP_NAME; ?>| Alumnos </title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/fontawesome-free/css/all.min.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/css/adminlte.css">

	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/css/sweetalert2.min.css">
	<script src="<?php echo APP_URL; ?>app/views/dist/js/sweetalert2.all.min.js" ></script>
    
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
						<!--h4 class="m-0">Lista horario sede:  </h4-->
						<h5 class="m-0">Registro de asistencia <?php echo $horarioSede["horario_nombre"] ." - ". $horarioSede["horario_detalle"]  ." - "."Sede ". $horarioSede["sede_nombre"]; ?></h4>
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

		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
			<!-- Small boxes (Stat box) -->
				<div class="card card-default">
					<div class="card-header">
						<h3 class="card-title">Búsqueda de alumnos</h3>
						<div class="card-tools">											
							<button type="button" class="btn btn-tool" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
						</div>
					</div>

					<div class="card-body card-comments">
						<div class="card-comment">
							<form action="<?php echo APP_URL."asistenciaAlumno/$horario_id" ?>" method="POST" autocomplete="off" enctype="multipart/form-data" >
									
								<div class="row">
									<div class="col-md-3">
										<div class="form-group campo">
											<label for="pago_fecha">Fecha para registro de asistencia</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<?php 
													if($_SESSION['rol']!= 1 && $_SESSION['rol']!= 2){
														echo '<input class="form-control" value="'.$fechahoy.'" disabled>';
														echo '<input type="hidden" name="fecha" value="'.$fechahoy.'">';
													}else{
														echo '<input type="date" class="form-control" id="fecha" name="fecha" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask value="'.$fechahoy.'" required>';
													}
												?>
												
											</div>
											<!-- /.input group -->
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="alumno_sedeid">.</label>
											<button type="submit" class="form-control btn btn-info">Generar lista</button>
										</div>
									</div>									
								</div>
							</form>	
						</div>
					
						<br/>                      
						<table id="example1" class="table table-bordered table-striped table-sm ">
							<thead>
								<tr>
									<th>Nombres</th>
									<th>Apellidos</th> 
									<th>Categoría</th>
									<th>Asistencia</th>	
								</tr>
							</thead>
							<tbody>
								<?php 
									if(isset($_POST['fecha'])){												
										echo $insAlumno->ListadoAlumnos($horarioSede["horario_id"],$fechahoy);		
									}										
								?>								
							</tbody>	
						</table>	
						<div class="card-footer">
							<!--a href="<?php echo APP_URL.'equipoList/'.$horarioSede["horario_id"].'/'; ?>" class="btn btn-dark btn-sm">Regresar</a-->
							<button class="btn btn-dark btn-back btn-sm" onclick="cerrarPestana()">Regresar</button>
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
	
	<!-- Page specific script -->
	<script>
		$(function () {
			$("#example1").DataTable({
			"responsive": true, "lengthChange": false, "autoWidth": false,
			"language": {
				"decimal": "",
				"emptyTable": "No hay datos disponibles en la tabla",
				"info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
				"infoEmpty": "Mostrando 0 a 0 de 0 entradas",
				"infoFiltered": "(filtrado de _MAX_ entradas totales)",
				"infoPostFix": "",
				"thousands": ",",
				"lengthMenu": "Mostrar _MENU_ entradas",
				"loadingRecords": "Cargando...",
				"processing": "Procesando...",
				"search": "Buscar:",
				"zeroRecords": "No se encontraron registros coincidentes",
				"paginate": {
					"first": "Primero",
					"last": "Último",
					"next": "Siguiente",
					"previous": "Anterior"
				},
				"aria": {
					"sortAscending": ": activar para ordenar la columna ascendente",
					"sortDescending": ": activar para ordenar la columna descendente"
				}
			},
			}).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');			    
		});
	</script>

	<script type="text/javascript">
		function cerrarPestana() {
			window.close();
		}
    </script>

	<script src="<?php echo APP_URL; ?>app/views/dist/js/ajax.js" ></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/js/main.js" ></script>
    
  </body>
</html>








