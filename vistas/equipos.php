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
          
          <?php $title_page = "Equipos"; include("template/head.php"); ?>
          <!-- Quill Editor CSS -->
          <link rel="stylesheet" href="../assets/libs/quill/quill.snow.css">
          <link rel="stylesheet" href="../assets/libs/quill/quill.bubble.css">
          
        </head> 

        <body id="body-usuario" idusuario="<?php echo $_SESSION['idusuario']; ?>"  >

          <?php include("template/switcher.php"); ?>
          <?php include("template/loader.php"); ?>

          <div class="page">
            <?php include("template/header.php") ?>
            <?php include("template/sidebar.php") ?>
            <?php if($_SESSION['equipos'] == 1) { ?>

            <!-- Start::app-content -->
            <div class="main-content app-content">
              <div class="container-fluid">

                <!-- Start::page-header -->

                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                  <div>
                    <div class="d-md-flex d-block align-items-center ">
                      <button class="btn-modal-effect btn btn-primary label-btn btn-agregar m-r-10px" onclick="show_hide_rows(2);" > <i class="ri-add-fill label-btn-icon me-2"></i> Agregar </button>
                      <button type="button" class="btn btn-danger btn-cancelar m-r-10px" onclick="show_hide_rows(1);" style="display: none;"><i class="ri-arrow-left-line"></i></button>
                      <div>
                        <p class="fw-semibold fs-18 mb-0">Equipos del Proyecto Integrador</p>
                        <span class="fs-semibold text-muted">Gestiona los equipos de proyecto</span>
                      </div>
                    </div>
                  </div>
                  <div class="btn-list mt-md-0 mt-2">              
                    <nav>
                      <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Alumnos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Proyectos</li>
                      </ol>
                    </nav>
                  </div>
                </div>

                <!-- End::page-header -->

                <!-- Start::row-1 -->
                <div class="row gy-3" id="div-lista-de-equipos">
                  
                </div>
                <!-- End::row-1 -->

                <!-- Start::row-2 -->
                <div class="row" id="div-asignar-equipo" style="display: none;">
                  <div class="col-xxl-6 col-xl-6">
                    <div class="card custom-card">            
                      <div class="card-body table-responsive">
                        <!-- TABLA - ALUMNOS -->
                        <div class="row" id="div-tabla-alumnos">
                          <table id="tbl-alumno" class="table table-bordered w-100" style="width: 100%;">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th>Nombre Completo</th>
                                <th class="text-center"><i class="bx bx-cog"></i></th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="text-center">#</th>
                                <th>Nombre Completo</th>
                                <th class="text-center"><i class="bx bx-cog"></i></th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>   
                      </div>  
                      <div class="card-footer border-top-0">
                        <!-- FOOTER DEL CARD -->
                      </div>                 
                    </div> <!-- /.card -->              
                  </div> <!-- /.col --> 
                  <div class="col-xxl-6 col-xl-6">
                    <div class="card custom-card">
                      <div class="card-header">
                        <div class="col-xl-11">
                          <h6 class="card-title">Asignar Equipo</h6>
                        </div>
                        <div class="">
                          <button type="button" class="btn btn-icon btn-primary btn-wave" id="guardar_registro_equipo"><i class="bx bx-save bx-tada"></i></button>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="row" id="div-nuevo-equipo">
                          <form name="form-agregar-equipo" id="form-agregar-equipo" method="POST" class="needs-validation" novalidate>
                            <h6 class=" text-center" id="num_equipo"></h6>
                            <input type="hidden" name="n_equipo" id="n_equipo">
                            <input type="hidden" id="codigo_equipo" name="codigo_equipo">
                            <input type="hidden" name="estudiantes_seleccionados" id="estudiantes_seleccionados">
                            <h6>Integrantes</h6>
                            <div class="col-xl-12 border border-3" id="list_select_estud" style="height: 400px;">
                              <!-- lista de estudiantes seleccionados -->
                            </div>
                            <button type="submit" style="display: none;" id="submit-form-equipo">Submit</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>  <!-- /.col --> 
                </div>
                <!-- End::row-2 -->

                <!-- Start::row-3 -->
                <div class="row" id="div-ver-equipo" style="display: none;">
                  <div class="col-xxl-12 col-xl-12">
                    <div class="card custom-card">            
                      <div class="card-body">
                           
                      </div>                
                    </div> <!-- /.card -->              
                  </div> <!-- /.col --> 
                            
                </div>
                <!-- End::row-3 -->

              </div>
            </div>

            <?php } else { $title_submodulo ='Equipos'; $descripcion ='Equipos del Proyecto Integrador'; $title_modulo = 'Proyectos'; include("403_error.php"); }?>   

            <?php include("template/search_modal.php"); ?>
            <?php include("template/footer.php"); ?>

          </div>

          <?php include("template/scripts.php"); ?>

          <?php include("template/custom_switcherjs.php"); ?>

          <!-- Apex Charts JS -->
          <script src="../assets/libs/apexcharts/apexcharts.min.js"></script>

          <!-- Chartjs Chart JS -->
          <script src="../assets/libs/chart.js/chart.min.js"></script>

          <script src="scripts/equipos.js"></script>

        </body>

      </html>
    <?php
  }
  ob_end_flush();
?>
