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
          
          <?php $title_page = "Equipo"; include("template/head.php"); ?>
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
            <?php if($_SESSION['equipo'] == 1) { ?>

            <!-- Start::app-content -->
            <div class="main-content app-content">
              <div class="container-fluid">

                <!-- Start::page-header -->

                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                  <div>
                    <div class="d-md-flex d-block align-items-center ">
                      <div>
                        <p class="fw-semibold fs-18 mb-0" id="titulo_eq">Portafolio</p>
                      </div>
                    </div>
                  </div>
                  <div class="btn-list mt-md-0 mt-2">              
                    <nav>
                      <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">General</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Usuario</li>
                      </ol>
                    </nav>
                  </div>
                </div>

                <!-- End::page-header -->

                <!-- Start::row-1 -->
                <div class="row">
                  <div class="col-xxl-4 col-xl-4">
                    
                    <div class="card custom-card overflow-hidden"> 
                                      
                      <div class="card-body p-0">
                        <!-- PERFIL -->
                        <div class="d-sm-flex align-items-top p-4 border-bottom-0 main-profile-cover">

                          <div>
                            <span class="avatar avatar-xxl avatar-rounded online me-3">
                              <img src="../assets/images/faces/9.jpg" alt="">
                            </span>
                          </div>

                          <div class="flex-fill main-profile-info">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="fw-semibold mb-1 text-fixed-white" id="nombre_e"></h6>
                              <button class="btn btn-md btn-light btn-wave waves-effect waves-light">
                                <i class="ri-account-pin-box-line me-1 align-middle d-inline-block"></i>
                                Foto
                              </button>
                            </div>
                            <p class="mb-1 text-muted text-fixed-white mb-4 op-7" id="doc_identidad"></p>

                            <div class="d-flex mb-0">
                              <div class="me-4">
                                <p class="fw-bold fs-20 text-fixed-white text-shadow mb-0" id="n_pyt"></p>
                                <p class="mb-0 fs-11 op-5 text-fixed-white">Proyectos</p>
                              </div>
                              <div class="me-4">
                                <p class="fw-bold fs-20 text-fixed-white text-shadow mb-0" id="n_grp"></p>
                                <p class="mb-0 fs-11 op-5 text-fixed-white">Grupos</p>
                              </div>
                              <div class="me-4">
                                <p class="fw-bold fs-20 text-fixed-white text-shadow mb-0" id="n_apb"></p>
                                <p class="mb-0 fs-11 op-5 text-fixed-white">Aprobado</p>
                              </div>
                            </div>


                          </div>
                          
                        </div>

                        <div class="p-3 border-bottom border-block-end-dashed">
                          <p class="fs-15 mb-2 me-4 fw-semibold">Información del Estudiante:</p>
                          <div class="text-muted" id="info_est">
                            
                          </div>
                        </div>

                        <div class="p-3 border-bottom border-block-end-dashed">
                          <p class="fs-15 mb-2 me-4 fw-semibold">Información de Contacto:</p>
                          <div class="text-muted" id="info_contac">
                            
                          </div>
                        </div>

                                            
                      </div>  
                                       
                    </div> <!-- /.card -->              
                  </div> <!-- /.col -->

                  <div class="col-xxl-8 col-xl-8">
                    <div class="row">
                      <div class="col-xl-12">
                        <div class="card custom-card">
                          <div class="card-body p-0">
                            <div class="p-3 border-bottom border-block-end-dashed d-flex align-items-center justify-content-between">
                              <div>
                                <p class="fs-15 mb-2 me-4 p-2 fw-semibold">Cronología de Proyectos</p>
                              </div>
                            </div>
                            <div class="p-3" id="crono_pi">
                              
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>         
                </div>
                <!-- End::row-1 -->

                <!-- MODAL:: REGISTRAR ROL - charge 2 -->
                <div class="modal fade modal-effect" id="modal-agregar-" role="dialog" tabindex="-1" aria-labelledby="modal-agregar-Label" aria-hidden="true">
                  <div class="modal-dialog modal-md modal-dialog-scrollabel">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h6 class="modal-title" id="Label">Estudiante</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body" >
                      <form name="form-agregar-" id="form-agregar-" method="POST" class="needs-validation" novalidate>
                        <div class="row" id="cargando-3-fomulario">
                          <input type="hidden" name="ejem" id="ejem">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="emjeplo" class="form-label"> </label>
                              <input type="text" class="form-control" name="emjeplo" id="emjeplo" />
                            </div>
                          </div>
                        </div>
                        <div class="row" id="cargando-4-fomulario" style="display: none;">
                          <div class="col-lg-12 text-center">
                            <div class="spinner-border me-4" style="width: 3rem; height: 3rem;" role="status"></div>
                            <h4 class="bx-flashing">Cargando...</h4>
                          </div>
                        </div>
                        <button type="submit" style="display: none;" id="submit-form-">Submit</button>
                      </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-sm btn-danger" onclick="" data-bs-dismiss="modal"><i class="las la-times"></i> Close</button>
                        <button type="button" class="btn btn-sm btn-primary" id="guardar_registro_"><i class="bx bx-save bx-tada"></i> Guardar</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End::modal-registrar-rol -->

              </div>
            </div>

            <?php } else { $title_submodulo ='Usuario'; $descripcion ='Estudiante'; $title_modulo = 'Usuario'; include("403_error.php"); }?>   

            <?php include("template/search_modal.php"); ?>
            <?php include("template/footer.php"); ?>

          </div>

          <?php include("template/scripts.php"); ?>

          <?php include("template/custom_switcherjs.php"); ?>

          <script src="scripts/usuario_e.js"></script>

        </body>

      </html>
    <?php
  }
  ob_end_flush();
?>
