<!-- Main Sidebar Container -->
<?php 
  use app\controllers\menuController;
  $insGenerar = new menuController();	

    $nombre= ($_SESSION['sede'] != "") ? 'IDV '.$_SESSION['sede'] : "IDV admin";
    $rolid= $_SESSION['rol'];
    $usuario=$_SESSION['usuario'];

    if($usuario != ""){
      $GenerarMenu=$insGenerar->ObtenerMenu($usuario);		
      // Generar el menú dinámico
      $menuHTML = $insGenerar->ConstruirMenu($GenerarMenu);
    }else{
      session_destroy();
		  header("Location: ".APP_URL."login/");
    }
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
          if(is_file("app/views/imagenes/fotos/empleado/".$_SESSION['foto'])){
            echo '<img class="img-circle elevation-2" alt="User Image" src="'.APP_URL.'app/views/imagenes/fotos/empleado/'.$_SESSION['foto'].'">';
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
              
                    <?php echo $menuHTML; ?>
        
    

          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->
</aside>