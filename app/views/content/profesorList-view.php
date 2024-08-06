<?php
	use app\controllers\profesorController;
	$insProfesor = new profesorController();	

	$profesorid		= $insProfesor->limpiarCadena($url[1]);
	$foto 			= APP_URL.'app/views/dist/img/default.png';
	$profesor_sexoM = "";
	$profesor_sexoF	= "";

	$datosprofesor=$insProfesor->seleccionarDatos("Unico","sujeto_profesor","profesor_id",$profesorid);
	if($datosprofesor->rowCount()==1){
		$datosprofesor=$datosprofesor->fetch();
		if ($datosprofesor['profesor_foto']!=""){
			$foto = APP_URL.'app/views/imagenes/fotos/profesor/'.$datosprofesor['profesor_foto'];
		}else{
			$foto = APP_URL.'app/views/dist/img/default.png';
		}
		if ($datosprofesor['profesor_genero']=='M'){
			$profesor_sexoM = "checked";
		}else{
			$profesor_sexoF = "checked";
		}

		$modulo_profesor 				= 'actualizar';	
		$profesor_tipoidentificacion 	= $datosprofesor['profesor_tipoidentificacion'];
		$profesor_identificacion 	  	= $datosprofesor['profesor_identificacion'];
		$profesor_nombre		  		= $datosprofesor['profesor_nombre'];
		$profesor_correo 			  	= $datosprofesor['profesor_correo'];
		$profesor_celular 			  	= $datosprofesor['profesor_celular'];
		$profesor_direccion 		  	= $datosprofesor['profesor_direccion'];
		$profesor_fechaingreso			= $datosprofesor['profesor_fechaingreso'];
		$profesor_sueldo				= $datosprofesor['profesor_sueldo'];
	}else{
		$modulo_profesor 				= 'registrar';	
		$profesor_tipoidentificacion	= '';
		$profesor_identificacion		= '';
		$profesor_nombre				= '';		
		$profesor_correo				= '';
		$profesor_celular				= '';
		$profesor_direccion				= '';
		$profesor_fechaingreso			= '';
		$profesor_sueldo				= '';
	}
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo APP_NAME; ?>| Profesores</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/dist/plugins/fontawesome-free/css/all.min.css">
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
				<h4 class="m-0">Profesores</h4>
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
					<div class="card-header" class="centered" >
						<h4 class="card-title">Ingreso de nuevo profesor</h4>
						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
						</div>
					</div>

					<div class="card-body">
						<div class="row">
							<div class="col-md-12">	
								<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/profesorAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >
								<input type="hidden" name="modulo_profesor" value="<?php echo $modulo_profesor; ?>">
								<input type="hidden" name="profesor_id" value="<?php echo $profesorid; ?>">

								<div class="row" style="font-size: 13px;">						
									<div class="col-md-2">
										<div class="form-group">
											<label for="profesor_foto">Foto</label>		
											<div class="input-group">											
												<div class="fileinput fileinput-new" data-provides="fileinput">
													<div class="fileinput-new thumbnail" style="width: 116px; height: 144px;" data-trigger="fileinput"><img src="<?php echo $foto; ?>"></div>
													<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 116px; max-height: 144px"></div>
													<div>
														<span class="bton bton-white bton-file" style="font-size: 13px;">
															<span class="fileinput-new">Seleccionar Foto</span>
															<span class="fileinput-exists">Cambiar</span>
															<input type="file" name="profesor_foto" id="foto" accept="image/*">
														</span>
														<a href="#" class="bton bton-orange fileinput-exists" style="font-size: 13px;" data-dismiss="fileinput">Remover</a>
													</div>
												</div>
											</div>		
										</div>
										<!-- /.form-group -->								
									</div>
									<!-- /.col -->
									<div class="col-sm-10">
										<div class="row" style="font-size: 13px;">
											<div class="col-sm-2">
												<div class="form-group">
													<label for="profesor_tipoidentificacion">Tipo identificación</label>
													<select id="profesor_tipoidentificacion" class="form-control custom-select2" name="profesor_tipoidentificacion" value="<?php echo $profesor_tipoidentificacion; ?>">
														<?php echo $insProfesor->OptionTipoIdentificacion($profesor_tipoidentificacion); ?>
													</select>
												</div>          
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label for="profesor_identificacion">Identificación</label>                        
													<input type="text" class="form-control" id="profesor_identificacion" name="profesor_identificacion" value="<?php echo $profesor_identificacion; ?>" required>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label for="profesor_nombre">Nombre y Apellido</label>
													<input type="text" class="form-control" id="profesor_nombre" name="profesor_nombre" value="<?php echo $profesor_nombre; ?>" required>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label for="profesor_correo">Correo</label>
													<input type="email" class="form-control" id="profesor_correo" name="profesor_correo" value="<?php echo $profesor_correo; ?>" required>	
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label for="profesor_celular">Celular</label>
													<input type="text" class="form-control" id="profesor_celular" name="profesor_celular" data-inputmask='"mask": "0999999999"' data-mask value="<?php echo $profesor_celular; ?>" required>
												</div> 
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label for="profesor_fechaingreso">Fecha de ingreso</label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
														</div>
														<input type="date" class="form-control" name="profesor_fechaingreso" id="profesor_fechaingreso" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask value="<?php echo $profesor_fechaingreso; ?>">
													</div>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label for="profesor_sueldo">Sueldo USD</label>
													<input type="text" class="form-control" id="profesor_sueldo" name="profesor_sueldo" value="<?php echo $profesor_sueldo; ?>" required>
												</div> 
											</div>											
											<div class="col-md-4">
												<div class="form-group">
													<label for="profesor_direccion">Dirección</label>
													<input type="text" class="form-control" id="profesor_direccion" name="profesor_direccion" value="<?php echo $profesor_direccion; ?>" required>
												</div>	
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label for="profesor_genero">Sexo</label>
													<div class="form-check">
														<input class="col-sm-1 form-check-input" type="radio" id="profesor_generoM" name="profesor_genero" value="M" <?php echo $profesor_sexoM; ?> required>
														<label class="col-sm-5 form-check-label" for="profesor_generoM">Masculino</label>
														<input class="col-sm-1 form-check-input" type="radio" id="profesor_generoF" name="profesor_genero" value="F" <?php echo $profesor_sexoF; ?> >
														<label class="col-sm-4 form-check-label" for="profesor_generoF">Femenino</label>
													</div> 
												</div>
											</div> 
											<div class="col-md-12">						
												<button type="submit" class="btn btn-success btn-xs">Guardar</button>
												<a href="<?php echo APP_URL; ?>profesorList/" class="btn btn-info btn-xs">Cancelar</a>
												<button type="reset" class="btn btn-dark btn-xs">Limpiar</button>						
											</div>
										</div>								
									</div>
									<!-- /.col -->
								</div>
								</form>

								<div class="tab-custom-content">
									<h4 class="card-title">Profesores ingresados</h4>
								</div>
								
								<div class="tab-content" id="custom-content-above-tabContent" style="font-size: 13px;">
									<table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 13px;">
										<thead>
											<tr>
												<th>Identificación</th>
												<th>Nombre y apellido</th>
												<th>Correo</th>
												<th>Celular</th>
												<th>Estado</th>
												<th style="width: 220px;">Opciones</th>
											</tr>
										</thead>
										<tbody>
											<?php 
												echo $insProfesor->listarProfesores(); 
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
		<!-- InputMask -->
		<script src="<?php echo APP_URL; ?>app/views/dist/plugins/moment/moment.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/inputmask/jquery.inputmask.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo APP_URL; ?>app/views/dist/js/adminlte.min.js"></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/js/ajax.js" ></script>
	<script src="<?php echo APP_URL; ?>app/views/dist/js/main.js" ></script>
	<!-- fileinput -->
	<script src="<?php echo APP_URL; ?>app/views/dist/plugins/fileinput/fileinput.js"></script>  
	
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

	<!-- Aplicar la máscara de entrada para el campo sueldo-->
	<script>
        $(document).ready(function(){
            Inputmask({
                alias: "currency",
                prefix: "$ ",  // Prefijo de la moneda
                groupSeparator: ",",
                autoGroup: true,
                digits: 2,
                digitsOptional: false,
                placeholder: "0"
            }).mask("#profesor_sueldo");
        });
    </script>    
  </body>
</html>








