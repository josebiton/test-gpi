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
          
          <?php $title_page = "Alumnos"; include("template/head.php"); ?>
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
            <?php if($_SESSION['alumnos'] == 1) { ?>

            <!-- Start::app-content -->
            <div class="main-content app-content">
              <div class="container-fluid">

                <!-- Start::page-header -->

                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                  <div>
                    <div class="d-md-flex d-block align-items-center ">
                      <button class="btn btn-primary label-btn btn-agregar m-r-10px" > <i class="ri-edit-line label-btn-icon me-2"></i>Equipos</button>
                      <div>
                        <p class="fw-semibold fs-18 mb-0">Lista de alumnos</p>
                        <span class="fs-semibold text-muted">Gestiona los alunnos del curso</span>
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
                <div class="row">
                  <div class="col-xxl-12 col-xl-12">
                    
                    <div class="card custom-card"> 
                                      
                      <div class="card-body table-responsive">
                        <!-- TABLA - ALUMNOS -->
                        <div class="row" id="div-tabla-alumnos">
                          <table id="tbl-alumnos" class="table table-bordered w-100" style="width: 100%;">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th>Nombre Completo</th>
                                <th>Semestre Académico</th>
                                <th>Cursos Afiliados</th>
                                <th class="text-center">Equipo</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="text-center">#</th>
                                <th>Nombre Completo</th>
                                <th>Semestre Académico</th>
                                <th>Cursos Afiliados</th>
                                <th class="text-center">Equipo</th>
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
                </div>
                <!-- End::row-1 -->

              </div>
            </div>

            <?php } else { $title_submodulo ='perfil'; $descripcion ='Perfil del PI'; $title_modulo = 'proyecto'; include("403_error.php"); }?>   

            <?php include("template/search_modal.php"); ?>
            <?php include("template/footer.php"); ?>

          </div>

          <?php include("template/scripts.php"); ?>

          <?php include("template/custom_switcherjs.php"); ?>

          <script src="scripts/alumnos.js"></script>

        </body>

      </html>
    <?php
  }
  ob_end_flush();
?>
