<?php
	use app\controllers\dashboardController;
	$insDashboard = new dashboardController();
	
	$ingresosLugar=$insDashboard->ingresosLugarEntr();
	
	foreach($ingresosLugar as $rows){
		$sede[] = $rows['sede_nombre'];
		$lugar[] = $rows['lugar_nombre'];
        $alumnos[] = (int)$rows['ALUMNOS_ENTRENAN'];
        $sinregpago[] = (int)$rows['ALUMNOS_SINREGPAGOS'];
		$alumnosad[] = (int)$rows['ALUMNOS_ADEUDAN'];
		$pensiones[] = (float)$rows['TOTALPENSIONES'];
		$recaudado[] = (float)$rows['TOTALRECAUDADO'];        
	}	
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo APP_NAME; ?>| Dashboard</title>
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/fontawesome-free/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Tempusdominus Bootstrap 4 -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- JQVMap -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/jqvmap/jqvmap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/css/adminlte.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/css/apexcharts.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/css/bootstrap-icons.min.css">

	<link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />

  </head>
  <div class="hold-transition sidebar-mini layout-fixed">	
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
			<div class="row mb-1">
				<div class="col-sm-6">
				<h1 class="m-0">Estadísticas</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Inicio</a></li>
					<li class="breadcrumb-item active">Dashboard v1</li>
				</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->

		<!-- Main content -->
		<section class="content">
			<!-- Gráfica -->
			<div class="card card-default">
				<div class="card-header">
					<h3 class="card-title">Por lugar de entrenamiento</h3>
					<div class="card-tools">
						<button type="button" class="btn btn-tool" data-card-widget="collapse">
							<i class="fas fa-minus"></i>
						</button>
					</div>
				</div>
				
				<title>Gráfico de Estadísticas</title>
				<canvas id="grafico" width="700" height="200"></canvas>

				<!-- Contenedor que envuelve botón + tabla -->
				<div style="position: absolute; left: 90%; transform: translateX(-50%); display: flex; gap: 10px; z-index: 1;">
					<!-- Botón Exportar a Excel -->
					<button onclick="exportarTablaAExcel('tablaDatos', 'IDVLoja-EstadisticasEntrenamiento')" 
							  class="boton-icono" 
							  style="background-image: url('<?php echo APP_URL; ?>app/views/imagenes/iconos/Excel.png');" 
							  title="Exportar a Excel"></button>
					
					<!-- Botón Exportar a PDF -->
					<button onclick="exportarTablaAPDF('tablaDatos', 'IDVLoja-EstadisticasEntrenamiento')" 
							class="boton-icono" 
							style="background-image: url('<?php echo APP_URL; ?>app/views/imagenes/iconos/Pdf.png');"
							title="Exportar a PDF"></button>
				</div>
				<div>
					<table id="tablaDatos" table border="1" cellpadding="8" cellspacing="0" style="margin-top: 30px; width: 100%; border-collapse: collapse;">
						<thead style="background-color: #f2f2f2;">
							<tr>
								<th>Sede</th>
								<th>Lugar de Entrenamiento</th>
								<th>Alumnos Entrenando</th>
								<th>Alumnos adeudan mes</th>
								<th>Alumnos sin registro pagos</th>	
								<th>Total Pensiones ($)</th>
								<th>Valor Recaudado en el mes ($)</th>															
							</tr>
						</thead>
						<tbody>
							<?php
							$totalAlumnos = $totalAlumnosAd = $totalAlSinPagos = $totalRecaudado = $totalPensiones = 0;
							for ($i = 0; $i < count($lugar); $i++) {
								echo '<tr>';
								echo '<td>' . $sede[$i] . '</td>';
								echo '<td>' . $lugar[$i] . '</td>';
								echo '<td style="text-align:center;">' . $alumnos[$i] . '</td>';
								echo '<td style="text-align:center;">' . $alumnosad[$i] . '</td>';								
								echo '<td style="text-align:center;">' . $sinregpago[$i] . '</td>';									
								echo '<td style="text-align:center;">$' . number_format($pensiones[$i], 2) . '</td>';
								echo '<td style="text-align:center;">$' . number_format($recaudado[$i], 2) . '</td>';																
								echo '</tr>'; 

								// Acumuladores
								$totalAlumnos += $alumnos[$i];
								$totalAlumnosAd += $alumnosad[$i];	
								$totalAlSinPagos += $sinregpago[$i];
								$totalRecaudado += $recaudado[$i];
								$totalPensiones += $pensiones[$i];
							}
							?>
						</tbody>
						<tfoot style="font-weight: bold; background-color: #eef;">
							<tr>
								<td style="text-align: right;"></td>
								<td style="text-align: right;">Totales:</td>
								<td style="text-align:center;"><?php echo $totalAlumnos; ?></td>
								<td style="text-align:center;"><?php echo $totalAlumnosAd; ?></td>
								<td style="text-align:center;"><?php echo $totalAlSinPagos; ?></td>
								<td style="text-align:center;">$<?php echo number_format($totalPensiones, 2); ?></td>
								<td style="text-align:center;">$<?php echo number_format($totalRecaudado, 2); ?></td>					
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
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
	<!-- jQuery UI 1.11.4 -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

	<!-- Bootstrap 4 -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- ChartJS -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/chart.js/Chart.min.js"></script>
	<!-- Sparkline -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/sparklines/sparkline.js"></script>
	<!-- JQVMap -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/jqvmap/jquery.vmap.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
	<!-- jQuery Knob Chart -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/jquery-knob/jquery.knob.min.js"></script>
	<!-- daterangepicker -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/moment/moment.min.js"></script>
	<!-- Tempusdominus Bootstrap 4 -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo APP_URL; ?>app/views/dist/js/adminlte.js"></script>

	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="<?php echo APP_URL; ?>app/views/dist/js/pages/dashboard.js"></script>

	<script src="<?php echo APP_URL; ?>app/views/js/ajax.js" ></script>
	<script src="<?php echo APP_URL; ?>app/views/js/main.js" ></script>	
	<script src="<?php echo APP_URL; ?>app/views/dist/js/sweetalert2.all.min.js" ></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

	<script>
		const ctx = document.getElementById('grafico').getContext('2d');
		const chart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: <?php echo json_encode($lugar); ?>,
				datasets: [
				{
					label: 'Total Pensiones ($)',
					backgroundColor: '#2ecc71',
					data: <?php echo json_encode($pensiones); ?>
				},
				{
					label: 'Valor Recaudado ($)',
					backgroundColor: '#f1c40f',
					data: <?php echo json_encode($recaudado); ?>
				},
				{
					label: 'Alumnos que adeudan',
					backgroundColor: '#e74c3c',
					data: <?php echo json_encode($alumnosad); ?>
				},
				{
					label: 'Alumnos Entrenando',
					backgroundColor: '#3498db',
					data: <?php echo json_encode($alumnos); ?>
				}
				]
			},
			options: {
				indexAxis: 'y', // Esto cambia el gráfico a horizontal
				responsive: true,
				plugins: {
					datalabels: {
						color: 'black',
						font: {
						weight: 'bold'
						},
						anchor: 'start',
						align: 'right',
						display: function(context) {
						const { chart, dataset, dataIndex, datasetIndex } = context;
						const datasets = chart.data.datasets;

						// Obtener el valor actual
						const currentValue = dataset.data[dataIndex];

						// Obtener todos los valores en la misma pila (misma categoría del eje Y)
						const stackValues = datasets.map(ds => ds.data[dataIndex] || 0);

						// Encontrar el valor máximo de la pila
						const maxValue = Math.max(...stackValues);

						// Mostrar etiqueta solo si este valor es el mayor en la pila
						return currentValue === maxValue;
						},
						formatter: function(value) {
						return value;
						}
					}
					},
				scales: {
					x: {
						stacked: true
					},
					y: {
						stacked: true,
						beginAtZero: true,
						title: { display: true,	text: 'Cantidad / Valor ($)' },
						ticks: { stepSize: 500 }// Ajusta según el rango
					}
				}
			},
			plugins: [ChartDataLabels]
		});

		function exportarTablaAExcel(tablaID, nombreArchivo) {
			const tabla = document.getElementById(tablaID);
			const libro = XLSX.utils.table_to_book(tabla, {sheet: "Datos"});
			XLSX.writeFile(libro, nombreArchivo + ".xlsx");
			}

		async function exportarTablaAPDF(tablaID, nombreArchivo) {
			const tabla = document.getElementById(tablaID);
			const canvas = await html2canvas(tabla);
			const imgData = canvas.toDataURL('image/png');
			const { jsPDF } = window.jspdf;
			const pdf = new jsPDF();
			const imgProps = pdf.getImageProperties(imgData);
			const pdfWidth = pdf.internal.pageSize.getWidth();
			const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
			pdf.addImage(imgData, 'PNG', 0, 20, pdfWidth, pdfHeight);
			pdf.save(nombreArchivo + ".pdf");
		}
	</script>

	<style>
		.boton-icono {
			width: 32px;
			height: 32px;
			background-color: transparent;
			background-size: contain;
			background-repeat: no-repeat;
			background-position: center;
			border: none;
			cursor: pointer;
			transition: transform 0.2s;
		}
		.boton-icono:hover {
			transform: scale(1.1);
		}
	</style>

  </body>
</html>