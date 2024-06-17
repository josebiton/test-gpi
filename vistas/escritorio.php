<?php
  //Activamos el almacenamiento en el buffer
  ob_start();
  date_default_timezone_set('America/Lima'); require "../config/funcion_general.php";
  session_start();
  if (!isset($_SESSION["user_nombre"])){
    header("Location: index.php");
  }else {
    ?>
      <!DOCTYPE html>
      <html lang="es" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark" data-toggled="icon-overlay-close">

        <head>
          
          <?php $title_page = "Inicio"; include("template/head.php"); ?>
          <link rel="stylesheet" href="../assets/libs/jsvectormap/css/jsvectormap.min.css">
          <link rel="stylesheet" href="../assets/libs/swiper/swiper-bundle.min.css">

        </head> 

        <body idusuario="<?php echo $_SESSION["idusuario"];?>" >

          <?php include("template/switcher.php"); ?>
          <?php include("template/loader.php"); ?>

          <div class="page">
            <?php include("template/header.php") ?>
            <?php include("template/sidebar.php") ?>
            <?php if($_SESSION['escritorioE'] == 1) { ?>

            <!-- Start::app-content -->
            <div class="main-content app-content">
              <div class="container-fluid">

                <!-- Start::page-header -->

                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                  <div class="col-xl-6">
                    <p class="fw-semibold fs-18 mb-0">Gestiona Tu Proyecto Integrador</p>
                    <span class="fs-semibold text-muted">Tu progreso depende de la administración grupal del proyecto.</span>
                  </div>
                  <div class="col-xl-6 ">
                    <div class="row d-flex align-items-end">
                        <div class="col-xl-3 p-2">
                          <select name="filtro_a" id="filtro_a" class="form-select" onchange="filtro_ub();"> </select>
                        </div>
                        <div class="col-xl-2 p-2">
                          <select name="filtro_b" id="filtro_b" class="form-select" onchange="filtro_uc();"> </select>
                        </div>
                        <div class="col-xl-2 p-2">
                          <select name="filtro_c" id="filtro_c" class="form-select"> </select>
                        </div>
                        <div class="col-xl-2 p-2">
                          <button type="button" class="btn btn-primary btn-wave" onclick="filtrar_pi();">
                            <i class="ri-filter-3-fill me-2 align-middle d-inline-block"></i>Filtrar
                          </button>
                        </div>
                      <div class="col-xl-3 p-2">
                        <button type="button" class="btn btn-outline-secondary btn-wave">
                          <i class="ri-upload-cloud-line me-2 align-middle d-inline-block"></i>Exportar
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- End::page-header -->

                <!-- Start::row-1 -->
                <div class="row">
                  <div class="col-xxl-12 col-xl-12">
                    <div class="card custom-card ">                  
                      <div class="card-body">
                        <input type="text" id="titulo">
                      </div>
                    </div>
                  </div>
                  
                </div>
                <!-- End::row-1 -->

              </div>
            </div>

            <?php } else if($_SESSION['escritorioDOSC'] == 1) { ?>

              <!-- Start::app-content -->
            <div class="main-content app-content">
              <div class="container-fluid">

                <!-- Start::page-header -->

                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                  <div class="col-xl-6">
                    <p class="fw-semibold fs-18 mb-0">GPI - DOCENTE</p>
                    <span class="fs-semibold text-muted">Orienta y califica a tus alunnos de forma ética y eficiente</span>
                  </div>
                  <div class="col-xl-9 ">
                    <div class="row d-flex align-items-end">
                        <div class="col-xl-3 p-2">
                          <select name="filtro_a" id="filtro_a" class="form-select" onchange=""> </select>
                        </div>
                        <div class="col-xl-2 p-2">
                          <select name="filtro_b" id="filtro_b" class="form-select" onchange=""> </select>
                        </div>
                        <div class="col-xl-2 p-2">
                          <select name="filtro_c" id="filtro_c" class="form-select"> </select>
                        </div>
                        <div class="col-xl-2 p-2">
                          <button type="button" class="btn btn-primary btn-wave" onclick="">
                            <i class="ri-filter-3-fill me-2 align-middle d-inline-block"></i>Filtrar
                          </button>
                        </div>
                      
                    </div>
                  </div>
                </div>

                <!-- End::page-header -->

                <!-- Start::row-1 -->
                <div class="row">
                  <div class="col-xxl-12 col-xl-12">
                    <div class="card custom-card ">                  
                      <div class="card-body">
                        <!-- CUERPO DEL ESCRITORIO DECENTE -->
                      </div>
                    </div>
                  </div>
                  
                </div>
                <!-- End::row-1 -->

              </div>
            </div>



            <?php } else {$title_submodulo ='Dashboard'; $descripcion ='Escritorio del personal Encargado'; $title_modulo = 'Administracion'; include("403_error.php"); }
            
            ?>   

            <?php include("template/search_modal.php"); ?>
            <?php include("template/footer.php"); ?>

          </div>

          <?php include("template/scripts.php"); ?>


          <!-- JSVector Maps JS -->
          <script src="../assets/libs/jsvectormap/js/jsvectormap.min.js"></script>

          <!-- JSVector Maps MapsJS -->
          <script src="../assets/libs/jsvectormap/maps/world-merc.js"></script>

          <!-- Apex Charts JS -->
          <script src="../assets/libs/apexcharts/apexcharts.min.js"></script>

          <!-- Chartjs Chart JS -->
          <script src="../assets/libs/chart.js/chart.min.js"></script>

          <!-- CRM-Dashboard -->
          <script src="../assets/js/crm-dashboard.js"></script>

          <!-- Custom JS -->
          <script src="../assets/js/custom.js"></script>

          <script src="scripts/funcion_crud.js"></script>
           
          <script src="scripts/update_local.js"></script>

          <?php if($_SESSION['escritorioE'] == 1) { ?>
          <script src="scripts/home_estudiante.js"></script>
          <?php } else if($_SESSION['escritorioDOSC'] == 1) {?>
            <script src="scripts/home_docente.js"></script>
          <?php }?>

          <?php include("template/custom_switcherjs.php"); ?>

        </body>

      </html>
    <?php
  }
  ob_end_flush();
?>
