<?php
	use app\controllers\reporteController;
	$insLEIngresos = new reporteController();

	if(isset($_POST['le_fecha_inicio'])){
		$le_fecha_inicio = $insLEIngresos->limpiarCadena($_POST['le_fecha_inicio']);
	} ELSE{
		$le_fecha_inicio = date('Y-m-01'); // Primer día del mes actual;
	}

	if(isset($_POST['le_fecha_fin'])){
		$le_fecha_fin = $insLEIngresos->limpiarCadena($_POST['le_fecha_fin']);
	} ELSE{
		$le_fecha_fin = date('Y-m-t');     // Último día del mes actual
	}

	$insIngresos=$insLEIngresos->ingresosMoraLugarEntr($le_fecha_inicio, $le_fecha_fin);
	
	foreach($insIngresos as $rows){
		$sede[] = $rows['SEDE'];
		$lugar[] = $rows['LUGARENTRENAMIENTO'];
        $alumno[] = $rows['ALUMNO'];
		$estadoalumno[] = $rows['ESTADOALUMNO'];		
        $fechaultpago[] = $rows['FECHA_ULTPAGO'];
		$situacion[] = $rows['SITUACION'];
		$periodo[] = $rows['PAGO_PERIODO'];  
		$concepto[] = $rows['PAGO_CONCEPTO']; 
		$valor[] = (float)$rows['PAGO_VALOR']; 
		$saldo[] = (float)$rows['PAGO_SALDO']; 
		$estadopago[] = $rows['ESTADOPAGO']; 
	}
?>

<html lang="es">
  <head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo APP_NAME; ?>| Ingresos y mora por lugar entrenamiento</title>
	<link rel="icon" type="image/png" href="<?php echo APP_URL; ?>app/views/dist/img/Logos/1104523691001_2.png">
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
				<h3 class="m-0">Ingresos y mora por lugar de entrenamiento</h3>
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

		<!-- Section listado de lugares de entrenamiento -->
		<section class="content">
			<form action="<?php echo APP_URL."reporteIngresosMorames/" ?>" method="POST" autocomplete="off" enctype="multipart/form-data" >			
			<div class="container-fluid">
				<div class="card card-default">
					<div class="card-header">
						<h3 class="card-title">Periodo </h3>
						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse">
							<i class="fas fa-minus"></i>
							</button>
						</div>
					</div>  
					<!-- card-body -->                
					<div class="card-body">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group campo">
									<label for="le_fecha_inicio">Fecha inicio</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input type="date" class="form-control" id="le_fecha_inicio" name="le_fecha_inicio" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" value=<?php echo $le_fecha_inicio;?> data-mask required>										
									</div>
									<!-- /.input group -->
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group campo">
									<label for="le_fecha_fin">Fecha fin</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input type="date" class="form-control" id="le_fecha_fin" name="le_fecha_fin" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" value=<?php echo $le_fecha_fin;?> data-mask required>										
									</div>
									<!-- /.input group -->
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="le_sedeid">.</label>
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
						<h3 class="card-title" style="text-align: right">Detalle de alumnos con pagos y mora</h3>
						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
						</div>
					</div>
					<div class="card-body">
						<table id="example1" class="table table-bordered table-striped table-sm">
							<thead>
								<tr>
									<th style="width: 90px;">Sede</th>
									<th style="width: 230px;">Lugar de Entrenamiento</th>
									<th style="width: 255px;">Alumno</th>
									<th style="width: 100px;">F. último pago</th>	
									<th>Situación</th>
									<th style="width: 95px;">Periodo Pago</th>
									<th style="width: 190px;">Concepto Pago</th>
									<th style="width: 60px;">Valor ($)</th>									
									<th style="width: 60px;">Saldo ($)</th>
									<th style="width: 60px;">Estado Pago</th>
								</tr>	
							</thead>
							<tbody>								
								<?php
									$totalAlumnos = $totalPagoValor = $totalPagoSaldo = 0;
									for ($i = 0; $i < count($lugar); $i++) {
										echo '<tr>';
											echo '<td>' . $sede[$i] . '</td>';
											echo '<td>' . $lugar[$i] . '</td>';
											echo '<td>' . $alumno[$i] . '</td>';								
											echo '<td style="text-align:center;">' . $fechaultpago[$i] . '</td>';	
											echo '<td style="width: 90px;;">' . $situacion[$i] . '</td>';									
											echo '<td>' . $periodo[$i] . '</td>';		
											echo '<td>' . $concepto[$i] . '</td>';	
											echo '<td style="text-align:center;">$' . number_format($valor[$i], 2) . '</td>';
											echo '<td style="text-align:center;">$' . number_format($saldo[$i], 2) . '</td>';																
											echo '<td style="text-align:center;">' . $estadopago[$i] . '</td>';
										echo '</tr>'; 

										// Acumuladores
										$totalPagoValor += $valor[$i];
										$totalPagoSaldo += $saldo[$i];
									}
								?>
							</tbody>
							<!-- tfoot style="font-weight: bold; background-color: #eef;">
								<tr>
									<td style="text-align: right;"></td>
									<td style="text-align: right;">Totales:</td>
									<td style="text-align:center;"><?php echo $totalAlumnos; ?></td>
									<td style="text-align: right;"></td>
									<td style="text-align: right;"></td>
									<td style="text-align: right;"></td>
									<td style="text-align: right;"></td>
									<td style="text-align:center;">$<?php echo number_format($totalPagoValor, 2); ?></td>
									<td style="text-align:center;">$<?php echo number_format($totalPagoSaldo, 2); ?></td>											
								</tr>
							</tfoot !-->
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
			"paging": false,
			"lengthChange": false,
			"searching": false,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true, 
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
					"colvis": "Visibilidad columnas"
				}
			},
			"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
			}).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
	});
	</script>
  </body>
</html>








