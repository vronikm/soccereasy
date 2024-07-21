<?php
	use app\controllers\profesorController;
	$insProfesor = new profesorController();	


?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo APP_NAME; ?>| Nuevo profesor</title>

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
						<h1 class="m-0">Nuevo profesor</h1>
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
			<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/profesorAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >
			<input type="hidden" name="modulo_profesor" value="registrar">
			<div class="container-fluid">
			<!-- Small boxes (Stat box) -->
				<div class="card card-default">
					<div class="card-header">
						<h3 class="card-title">Información personal</h3>
						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
						</div>
					</div>

					<!-- card-body -->					
					<div class="card-body">						
						<!-- row -->	
						<div class="row">						
							<div class="col-md-2">
								<div class="form-group">
									<label for="profesor_foto">Foto</label>		
									<div class="input-group">											
										<div class="fileinput fileinput-new" data-provides="fileinput">
											<div class="fileinput-new thumbnail" style="width: 116px; height: 144px;" data-trigger="fileinput"><img src=""></div>
											<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 116px; max-height: 144px"></div>
											<div>
												<span class="bton bton-white bton-file">
													<span class="fileinput-new">Seleccionar Foto</span>
													<span class="fileinput-exists">Cambiar</span>
													<input type="file" name="profesor_foto" id="foto" accept="image/*">
												</span>
												<a href="#" class="bton bton-orange fileinput-exists" data-dismiss="fileinput">Remover</a>
											</div>
										</div>
									</div>		
								</div>
								<!-- /.form-group -->								
							</div>
							<!-- /.col -->
							<div class="col-md-10">
								<div class="row">
									<div class="col-sm-3">
										<div class="form-group">
											<label for="profesor_tipoidentificacion">Tipo identificación</label>
											<select id="profesor_tipoidentificacion" class="form-control custom-select2" name="profesor_tipoidentificacion" >
												<?php echo $insProfesor->listarCatalogoTipoDocumento(); ?>
											</select>
										</div>          
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="profesor_identificacion">Identificación</label>                        
											<input type="text" class="form-control" id="profesor_identificacion" name="profesor_identificacion" placeholder="Identificación" required="required">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="profesor_nombre">Nombre y Apellido</label>
											<input type="text" class="form-control" id="profesor_nombre" name="profesor_nombre" placeholder="Nombre profesor">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="profesor_correo">Correo</label>
											<input type="email" class="form-control" id="profesor_correo" name="profesor_correo" placeholder="Correo profesor">	
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="profesor_celular">Celular</label>
											<input type="text" class="form-control" id="profesor_celular" name="profesor_celular" data-inputmask='"mask": "0999999999"' data-mask placeholder="Celular" required>
										</div> 
									</div>								
									<div class="col-sm-3">
										<div class="form-group">
											<label for="profesor_especialidadid">Especialidad</label>
											<select id="profesor_especialidadid" class="form-control custom-select2" name="profesor_especialidadid" >
												<?php echo $insProfesor->listarEspecialidad(); ?>
											</select>
										</div>          
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="profesor_fechaingreso">Fecha de ingreso</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input type="date" class="form-control" name="profesor_fechaingreso" id="profesor_fechaingreso" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="profesor_genero">Sexo</label>
											<div class="form-check">
												<input class="col-sm-1 form-check-input" type="radio" id="profesor_generoM" name="profesor_genero" value="M" required="required">
												<label class="col-sm-5 form-check-label" for="profesor_generoM">Masculino</label>
												<input class="col-sm-1 form-check-input" type="radio" id="profesor_generoF" name="profesor_genero" value="F">
												<label class="col-sm-4 form-check-label" for="profesor_generoF">Femenino</label>
											</div> 
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="profesor_direccion">Dirección</label>
											<textarea class="form-control" id="profesor_direccion" name="profesor_direccion" placeholder="Barrio, Calle principal, #casa, calle secundaria"></textarea>
										</div>	
									</div> 
								</div>								
							</div>
							<!-- /.col -->							
						</div>
						<!-- /.row -->
					</div> 
					<!-- /.card-body -->					
				</div>
			<!-- /.row -->
			</div><!-- /.container-fluid -->

			<div class="card-footer">						
				<button type="submit" class="btn btn-success btn-sm">Guardar</button>
				<button type="reset" class="btn btn-dark btn-sm">Limpiar</button>						
			</div>

			</form>
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

	<!--script src="<?php echo APP_URL; ?>app/views/dist/js/main.js" ></script-->
	
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