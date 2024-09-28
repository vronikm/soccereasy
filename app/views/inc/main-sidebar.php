<!-- Main Sidebar Container -->
<?php 
    $nombre="IDV Loja";
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="<?php echo APP_URL; ?>app/views/dist/img/Logos/1104523691001_2.png" alt="IDVLoja Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><?php echo $nombre; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
        <?php
          if(is_file("app/views/imagenes/fotos/usuario/".$_SESSION['foto'])){
            echo '<img class="img-circle elevation-2" alt="User Image" src="'.APP_URL.'app/views/imagenes/fotos/usuario/'.$_SESSION['foto'].'">';
          }else{
            echo '<img class="img-circle elevation-2" alt="User Image" src="'.APP_URL.'app/views/dist/img/default.png">';
          }
        ?>

        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo  $_SESSION['usuario'];?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
              with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="<?php echo APP_URL."dashboard/" ?>"" class="nav-link <?php if ($url[0]=='dashboard') echo 'active'; else echo ''; ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?php echo APP_URL."representanteList/" ?>" class="nav-link ">
              <i class="nav-icon far fa-circle text-info"></i>
              <p>Representantes</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?php echo APP_URL."alumnoList/" ?>" class="nav-link ">
              <i class="nav-icon far fa-circle text-info"></i>
              <p>Alumnos</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?php echo APP_URL."pagosList/" ?>" class="nav-link ">
              <i class="nav-icon far fa-circle text-info"></i>
              <p>Pagos</p>
            </a>
          </li>

          <!--li class="nav-item">
            <a href="<?php echo APP_URL."profesorList/" ?>" class="nav-link ">
              <i class="nav-icon far fa-circle text-info"></i>
              <p>Profesores</p>
            </a>
          </li-->

          <li class="nav-item">
            <a href="<?php echo APP_URL."torneoList/" ?>" class="nav-link ">
              <i class="nav-icon far fa-circle text-info"></i>
              <p>Torneos</p>
            </a>
          </li>
  
          <li class="nav-header">Horarios</li>
          <li class="nav-item">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-th"></i>
              <p>Horarios<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">

              <li class="nav-item">
                <a href="<?php echo APP_URL."asistenciaHora/" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Ingreso Horas</p>
                </a>

              </li>
              <li class="nav-item">
                <a href="<?php echo APP_URL."asistenciaLugar/" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Lugar entrenamiento</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo APP_URL."asistenciaListHorario/" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Horarios</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-header">Empleados</li>
          <li class="nav-item">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-th"></i>
              <p>Empleado<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo APP_URL."empleadoList/" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Personal</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-header">Cobranza</li>
          <li class="nav-item">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-th"></i>
              <p>Gestión de cobranza<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo APP_URL."cobranzaPension/" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Pensiones</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo APP_URL."cobranzaUniforme/" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Uniformes</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-header">Reportes</li>
          <li class="nav-item">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-th"></i>
              <p>Reportes<i class="fas fa-angle-left right"></i></p>
            </a>

            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo APP_URL."reportePagos/" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Consolidado pagos</p>
                </a>
              </li>
            </ul>

            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo APP_URL."reporteRubros/" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Alumnos por rubro</p>
                </a>
              </li>
            </ul>

            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo APP_URL."reportePagosReceptadosResumen/" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Resumen pagos</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-header">Seguridad</li>
          <li class="nav-item <?php if ($url[0]=='userList') echo 'menu-open'; else echo ''; ?>">
            <a href="#" class="nav-link <?php if ($url[0]=='userList') echo 'active'; else echo ''; ?>">
              <i class="nav-icon fas fa-th"></i>
              <p>Seguridad<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href=<?php echo APP_URL."userList/";?> class="nav-link <?php if ($url[0]=='userList') echo 'active'; else echo ''; ?>" >
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Usuarios</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo APP_URL."roList/" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Roles</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item <?php if ($url[0]=='escuelaNew') echo 'menu-open'; else echo ''; ?>">
            <a href="#" class="nav-link <?php if ($url[0]=='escuelaList') echo 'active'; else echo ''; ?>">
              <i class="nav-icon fas fa-th"></i>
              <p>Configuración<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo APP_URL."escuelaNew/" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Escuela</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo APP_URL."sedeList/" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Sedes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo APP_URL."roList/" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Roles</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo APP_URL."tablasNew/" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Tablas</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo APP_URL."catalogosNew/" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Catálogos</p>
                </a>
              </li>

            </ul>
          </li>

          <li class="nav-header">Salir</li>
          <li class="nav-item">
            <a href=<?php echo APP_URL."logOut/";?> class="nav-link" id="btn_exit">
              <i class="nav-icon far fa-circle text-danger"></i>
              <p class="text">Salir</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->
</aside>