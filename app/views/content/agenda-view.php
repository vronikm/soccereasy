<?php
	use app\controllers\agendaController;
	$insAgenda = new agendaController();
?>

<html lang="es">
  <head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo APP_NAME; ?>| Eventos</title>
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/fontawesome-free/css/all.min.css">
	  <!-- fullCalendar -->
	  <link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/fullcalendar/main.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/css/adminlte.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/css/sweetalert2.min.css">
    
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
						<h1 class="m-0">Registro de eventos</h1>
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

		<!-- Section listado de alumnos -->
		<section class="content">
			<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-agenda">
				Launch Default Modal
			</button>
			<form action="<?php echo APP_URL."agenda/" ?>" method="POST" autocomplete="off" enctype="multipart/form-data" >			
			<!-- card-body -->                
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<div class="card card-primary card-outline">							
							<div class="card-body p-0">
								<!-- THE CALENDAR -->
								<div id="calendar"></div>
							</div>
							<!-- /.card-body -->
						</div>
					<!-- /.card -->
					</div>
					<!-- /.col -->
				</div>	
			</div>            
			</form>
		</section>

		<div class="modal fade" id="modal-agenda">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Registrar Evento</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>					
					<div class="modal-body">							
						<input type="hidden" name="modulo_agenda" value="registrar">	
						<div class="form-group">
							<label for="agenda_title">Título del Evento:</label>
							<input type="text" id="agenda_title" name="agenda_title" class="form-control" required>
						</div>
						<div class="form-group">
							<label for="agenda_detail">Detalle del Evento:</label>
							<textarea id="agenda_detail" name="agenda_detail" class="form-control"></textarea>
						</div>
						<div class="form-group">
							<label for="agenda_start">Fecha y Hora de Inicio:</label>
							<input type="datetime-local" id="agenda_start" name="agenda_start" class="form-control" required>
						</div>
						<div class="form-group">
							<label for="agenda_end">Fecha y Hora de Fin:</label>
							<input type="datetime-local" id="agenda_end" name="agenda_end" class="form-control" required>
						</div>
						<div class="form-group">
							<label for="agenda_color">Color del Evento:</label>
							<input type="color" id="agenda_color" name="agenda_color" class="form-control">
						</div>						
					</div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>						
						<button type="button" class="btn btn-primary" id="saveEvent">Guardar</button>										
					</div>					
				</div>
			</div>
		</div>
      <!-- /.modal -->
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
	<!-- jQuery UI -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/jquery-ui/jquery-ui.min.js"></script>
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
	<script src="<?php echo APP_URL; ?>app/views/dist/js/sweetalert2.all.min.js" ></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/js/adminlte.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/js/ajax.js" ></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/js/main.js" ></script>	
	<!-- fullCalendar 2.2.5 -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/moment/moment.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/fullcalendar/main.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/fullcalendar/locales/es.js"></script>

     <!-- Page specific script -->
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			var calendarEl = document.getElementById('calendar');
			var calendar = new FullCalendar.Calendar(calendarEl, {
				initialView: 'dayGridMonth',
				locale: 'es',
				aspectRatio: 2.14, // Cambia la relación de aspecto para hacerlo más pequeño
				events: <?php echo $insAgenda->obtenerEventos(); ?>,
				dateClick: function(info) { 
					// Establece la fecha seleccionada en los campos de inicio y fin del modal
					document.getElementById('agenda_start').value = info.dateStr;
					document.getElementById('agenda_end').value = info.dateStr;
					// Abre el modal
					$('#modal-agenda').modal('show');
				}
			});
			calendar.render();
		});
	</script>

	<script>
		document.getElementById('saveEvent').addEventListener('click', function() {
			// Obtén los datos del formulario
			var agendaTitle = document.getElementById('agenda_title').value;
			var agendaDetail= document.getElementById('agenda_detail').value;
			var agendaStart = document.getElementById('agenda_start').value;
			var agendaEnd 	= document.getElementById('agenda_end').value;
			var agendaColor = document.getElementById('agenda_color').value;

			// Validar campos obligatorios
			if (agendaTitle.trim() === '' || agendaStart.trim() === '' || agendaEnd.trim() === '') {
				alert('Por favor, complete los campos obligatorios.');
				return;
			}
			// Enviar los datos usando AJAX
			$.ajax({
				url: '<?php echo APP_URL; ?>app/ajax/agendaAjax.php', // Cambia esta URL por la ruta correspondiente
				method: 'POST',
				data: {
					modulo_agenda:'registrar',
					agenda_title: agendaTitle,
					agenda_detail: agendaDetail,
					agenda_start: agendaStart,
					agenda_end: agendaEnd,
					agenda_color: agendaColor
				},
				success: function(response) {
					alert('Evento guardado con éxito');
					// Recarga los eventos del calendario si es necesario
					calendar.refetchEvents();
					$('#modal-agenda').modal('hide');
				},
				error: function() {
					alert('Hubo un error al guardar el evento.');
				}
			});
		});
	</script>
  </body>
</html>








