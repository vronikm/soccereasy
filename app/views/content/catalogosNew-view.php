<?php
	date_default_timezone_set("America/Guayaquil");

	use app\controllers\tablasController;
	$insCatalogo = new tablasController();	

	$catalogo_valor = ($url[1] != "") ? $url[1] : 0;	

	if($catalogo_valor != 0){
		$datos=$insCatalogo->BuscarCatalogo($catalogo_valor);		
		if($datos->rowCount()==1){
			$datos=$datos->fetch(); 
			$modulo_catalogos = 'actualizar';
			$catalogo_tabla = $datos['tabla_nombre'];
			$catalogo_descripcion = $datos['catalogo_descripcion'];
			$catalogo_tablaid = $datos['tabla_id'];
		}
	}else{
		$modulo_catalogos = 'registrar';
		$catalogo_valor = '';
		$catalogo_tabla = '';		
		$catalogo_descripcion = '';
		$catalogo_tablaid = 0;
	}
?>


<html lang="es">
  <head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo APP_NAME; ?>| Catalogos</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/fontawesome-free/css/all.min.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/select2/css/select2.min.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
	
	<!-- DataTables -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/css/adminlte.min.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/css/sweetalert2.min.css">
    
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
						<h4 class="m-0">Ingreso Catálogos</h4>
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

		<!-- Section listado de alumnos -->
		<section class="content">	
			<div class="container-fluid">
				<div class="card card-default">
					<div class="card-header" class="centered" >
						<h4 class="card-title">Nuevo catálogo</h4>
						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
						</div>
					</div>

					<div class="card-body">						
						<div class="row">
							<div class="col-md-12">	
											
								<form class="FormularioAjax" id="quickForm" action="<?php echo APP_URL; ?>app/ajax/tablasAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >
									<input type="hidden" name="modulo_catalogos" value="<?php echo $modulo_catalogos; ?>">
									<input type="hidden" name="codigo_catalogo" value="<?php echo $catalogo_valor; ?>">

									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="catalogo_tablaid">Tabla catálogo</label>
												<select class="form-control select2" style="width: 100%;" id="catalogo_tablaid" name="catalogo_tablaid">
													<?php echo $insCatalogo->listarCatalogoTablas($catalogo_tablaid); ?>
												</select>
											</div> 
										</div>
										<div class="col-md-3">										
											<div class="form-group">
												<label for="catalogo_valor">Código catálogo</label>
												<input type="text" class="form-control" id="catalogo_valor" name="catalogo_valor" value="<?php echo $catalogo_valor; ?>">
											</div>
										</div>
										<div class="col-md-3">										
											<div class="form-group">
												<label for="catalogo_descripcion">Descripción catálogo</label>
												<input type="text" class="form-control" id="catalogo_descripcion" name="catalogo_descripcion" value="<?php echo $catalogo_descripcion; ?>">
											</div>
										</div>
										<div class="col-md-12">						
											<button type="submit" class="btn btn-success btn-sm">Guardar</button>
											<a href="<?php echo APP_URL; ?>catalogosNew/" class="btn btn-info btn-sm">Cancelar</a>
											<button type="reset" class="btn btn-dark btn-sm">Limpiar</button>						
										</div>	
									</div>									
								</form>															
								
								<div class="tab-custom-content">
									<h4 class="card-title">Catálogos ingresados</h4>
								</div>										
								<div class="tab-content" id="custom-content-above-tabContent">							
									<table id="example1" class="table table-bordered table-striped table-sm">
										<thead>
											<tr>
												<th>Id Tabla</th>
												<th>Nombre Tabla</th>
												<th>Código Catálogo</th>
												<th>Descripción</th>
												<th>Estado</th>															
												<th style="width: 220px;">Opciones</th>																
											</tr>
										</thead>
										<tbody>
											<?php echo $insCatalogo->listarCatalogos(); ?>								
										</tbody>
									</table>									
								</div>								
							</div>
						<!-- /.row -->
						</div>
					</div>
				</div>
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
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-buttons/js/buttons.print.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo APP_URL; ?>app/views/dist/js/adminlte.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/js/sweetalert2.all.min.js" ></script>
	<!-- Select2 -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/select2/js/select2.full.min.js"></script>
	
	<script src="<?php echo APP_URL; ?>app/views/dist/js/ajax.js" ></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/js/main.js" ></script>
	
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/jquery-validation/jquery.validate.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/jquery-validation/additional-methods.min.js"></script>
	
	<!-- Page specific script -->
	<script>
		$(function () {
			$("#example1").DataTable({
			"responsive": true, "lengthChange": false, "autoWidth": false,
			}).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');			    
		});
	</script>    

	<script>
		$(function () {			
			$('#quickForm').validate({
				rules: {
				tabla_nombre: {
					required: true       
				},
				},
				messages: {
				tabla_nombre: {
					required: "Por favor ingrese un nombre para la nueva tabla !!"
				},				
				},
				errorElement: 'span',
				errorPlacement: function (error, element) {
				error.addClass('invalid-feedback');
				element.closest('.form-group').append(error);
				},
				highlight: function (element, errorClass, validClass) {
				$(element).addClass('is-invalid');
				},
				unhighlight: function (element, errorClass, validClass) {
				$(element).removeClass('is-invalid');
				}
			});
		});
	</script>
  </body>
</html>







