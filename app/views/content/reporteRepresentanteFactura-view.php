<?php
	use app\controllers\reporteController;
	$insFactura = new reporteController();
	$repre_id 	  = ($url[1] != "") ? $url[1] : 0;

	if(isset($_POST['pago_fecha_inicio'])){
		$fecha_inicio = $insFactura->limpiarCadena($_POST['pago_fecha_inicio']);
	} ELSE{
		$fecha_inicio = $insFactura->fechaPagosCompletos();
		$fecha_inicio = $fecha_inicio->fetch(); 
		$fecha_inicio = $fecha_inicio['FECHA_MAXIMA'];
	}

	if(isset($_POST['pago_fecha_fin'])){
		$fecha_fin = $insFactura->limpiarCadena($_POST['pago_fecha_fin']);
	} ELSE{
		$fecha_fin = $insFactura->fechaPagosCompletos();
		$fecha_fin = $fecha_fin->fetch(); 
		$fecha_fin = $fecha_fin['FECHA_MAXIMA'];
	}	
/*
	$datos=$pago_fecharegistro->seleccionarDatos("Unico","general_sede","sede_id",$sede_id);
	if($datos->rowCount()==1){
		$datos=$datos->fetch();
		$sede_nombre		= $datos['sede_nombre'];
	}else{
		$sede_nombre = "";
	}
		*/
?>

<html lang="es">
  <head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>IDV Loja| Reporte de pagos para facturación</title>

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
				<h1 class="m-0">Búsqueda de pagos para facturación</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Nuevo</a></li>
					<li class="breadcrumb-item active">Dashboard</li>
				</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->

		<!-- Section listado de alumnos -->
		<section class="content">
			<form action="<?php echo APP_URL."reporteRepresentanteFactura/" ?>" method="POST" autocomplete="off" enctype="multipart/form-data" >			
			<div class="container-fluid">
				<div class="card card-default">
					<div class="card-header">
						<h3 class="card-title">Criterios de búsqueda</h3>
						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse">
							<i class="fas fa-minus"></i>
							</button>
						</div>
					</div>  
					<!-- card-body -->                
					<div class="card-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group campo">
									<label for="pago_fecha">Fecha inicio</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input type="date" class="form-control" id="pago_fecha_inicio" name="pago_fecha_inicio" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask required>										
									</div>
									<!-- /.input group -->
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group campo">
									<label for="pago_fecha">Fecha fin</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input type="date" class="form-control" id="pago_fecha_fin" name="pago_fecha_fin" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask required>										
									</div>
									<!-- /.input group -->
								</div>
							</div>	
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="alumno_sedeid">.</label>
									<button type="submit" class="form-control btn btn-info">Buscar</button>
								</div>
							</div>
						</div>					
					</div>
				</div>
            </div>  
			</form>
			<div class="container-fluid">
			<!-- Small boxes (Stat box) -->
				<div class="card card-default">
					<div class="card-header">
						<h3 class="card-title">Resultado de la búsqueda</h3>
						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
						</div>
					</div>
					<div class="card-body">
						<table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 13px;">
							<thead>
								<tr>
									<th>Sede</th>
									<th>Identificación</th>
									<th>Representante</th>
									<th>Dirección</th>
									<th>Correo</th>
									<th>Celular</th>
									<th>Alumno</th>
									<th>Rubro</th>
									<th>V. Pagado</th>
									<th>F. registro pago</th>									
								</tr>
							</thead>
							<tbody>
								<?php 
									echo $insFactura->pagosFacturacion($fecha_inicio, $fecha_fin);
								?>								
							</tbody>
						</table>	
					</div>
				</div>
			<!-- /.row -->
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.section -->      
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
	<script src="<?php echo APP_URL; ?>app/views/dist/js/ajax.js" ></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/js/main.js" ></script>
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
				},
				"buttons": {
					"copy": "Copiar",
					"print": "Imprimir",
					"colvis": "Visibilidad columna"
				}
			},
			"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
		}).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
		$('#example2').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": false,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
		});
	});
	</script>
  </body>
</html>








