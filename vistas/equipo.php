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
                      <img src="../assets/modulo/equipo/computer.png" class="avatar avatar-lg m-r-10px" alt="">
                      <div>
                        <p class="fw-semibold fs-18 mb-0" id="titulo_eq">Equipo del Proyecto</p>
                        <span class="fs-semibold text-muted" id="num_eq">Gestiona adecuadamente tu equipo</span>
                      </div>
                    </div>
                  </div>
                  <div class="btn-list mt-md-0 mt-2">              
                    <nav>
                      <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">General</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Equipo</li>
                      </ol>
                    </nav>
                  </div>
                </div>

                <!-- End::page-header -->

                <!-- Start::row-1 -->
                <div class="row">
                  <div class="col-xxl-6 col-xl-8">
                    
                    <div class="card custom-card"> 
                                      
                      <div class="card-body table-responsive">
                        <!-- EQUIPO -->
                        <div class="row" id="div-equi">

                          <table id="tabla-equipo" class="table table-bordered w-100" style="width: 100%;">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="text-center"><i class="bx bx-cog"></i></th>
                                <th>Nombre</th>
                                <th>Rol</th>

                                <th>Nombre Completo</th>
                                <th>Rol</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="text-center"><i class="bx bx-cog"></i></th>
                                <th>Nombre</th>
                                <th>Rol</th>

                                <th>Nombre Completo</th>
                                <th>Rol</th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>

                                            
                      </div>  
                                       
                    </div> <!-- /.card -->              
                  </div> <!-- /.col -->

                  <div class="col-xxl-6 col-xl-4">
                    <div class="card custom-card">
                      <div class="card-header">
                        <div class="card-title">Reunión de equipo</div>
                      </div> 
                      <div class="card-body table-responsive">
                        <!-- NOTA -->
                        <div class="row gy-3" id="div-nota">
                          <div class="col-xl-6 text-center">
                            <a href="http://discord.com" target="_blank" rel="noopener noreferrer">
                              <img src="../assets/images/aplicaciones/discord.svg" class="avatar avatar-lg m-r-10px" alt="">
                            </a>
                          </div>
                          <div class="col-xl-6 text-center">
                            <a href="http://meet.google.com/fhu-cxnz-acx" target="_blank" rel="noopener noreferrer">
                              <img src="../assets/images/aplicaciones/google-meet.svg" class="avatar avatar-lg m-r-10px" alt="">
                            </a>
                          </div>
                          <div class="col-xl-6 text-center">
                            <a href="https://us04web.zoom.us/j/72315079518?pwd=hC3KSvp3k6XVaYmJOODFEbUxsvZ8wV.1" target="_blank" rel="noopener noreferrer">
                              <img src="../assets/images/aplicaciones/zoom.svg" class="avatar avatar-lg m-r-10px" alt="">
                            </a>
                          </div>
                          <div class="col-xl-6 text-center">
                            
                            <a href="http://github.com" target="_blank" rel="noopener noreferrer">
                              <img src="../assets/images/aplicaciones/github.svg" class="avatar avatar-lg m-r-10px" alt="">
                            </a>
                          </div>

                          
                        </div>
       
                      </div>  
                                       
                    </div> <!-- /.card -->              
                  </div> <!-- /.col -->         
                </div>
                <!-- End::row-1 -->

                <!-- MODAL:: REGISTRAR TITULO - charge 1 -->
                <div class="modal fade modal-effect" id="modal-agregar-titulo" role="dialog" tabindex="-1" aria-labelledby="modal-agregar-tituloLabel" aria-hidden="true">
                  <div class="modal-dialog modal-md modal-dialog-scrollabel">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h6 class="modal-title" id="tituloLabel">Estudiante</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body" >
                      <form name="form-agregar-rol" id="form-agregar-titulo" method="POST" class="needs-validation" novalidate>
                        <div class="row" id="cargando-1-fomulario">
                          <input type="hidden" name="idequipo" id="idequipo">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="titulo_p" class="form-label">Editar Título</label>
                              <input type="text" class="form-control" name="titulo_p" id="titulo_p" />
                            </div>
                          </div>
                        </div>
                        <div class="row" id="cargando-2-fomulario" style="display: none;">
                          <div class="col-lg-12 text-center">
                            <div class="spinner-border me-4" style="width: 3rem; height: 3rem;" role="status"></div>
                            <h4 class="bx-flashing">Cargando...</h4>
                          </div>
                        </div>
                        <button type="submit" style="display: none;" id="submit-form-titulo">Submit</button>
                      </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-sm btn-danger" onclick="limpiar_form_t();" data-bs-dismiss="modal"><i class="las la-times"></i> Close</button>
                        <button type="button" class="btn btn-sm btn-primary" id="guardar_registro_titulo"><i class="bx bx-save bx-tada"></i> Guardar</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End::modal-registrar-rol -->

                <!-- MODAL:: REGISTRAR ROL - charge 2 -->
                <div class="modal fade modal-effect" id="modal-agregar-rol" role="dialog" tabindex="-1" aria-labelledby="modal-agregar-rolLabel" aria-hidden="true">
                  <div class="modal-dialog modal-md modal-dialog-scrollabel">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h6 class="modal-title" id="nombres">Estudiante</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body" >
                      <form name="form-agregar-rol" id="form-agregar-rol" method="POST" class="needs-validation" novalidate>
                        <div class="row" id="cargando-3-fomulario">
                          <input type="hidden" name="idestudiante" id="idestudiante">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="rol_e" class="form-label">Rol</label>
                              <input type="text" class="form-control" name="rol_e" id="rol_e" />
                            </div>
                          </div>
                        </div>
                        <div class="row" id="cargando-4-fomulario" style="display: none;">
                          <div class="col-lg-12 text-center">
                            <div class="spinner-border me-4" style="width: 3rem; height: 3rem;" role="status"></div>
                            <h4 class="bx-flashing">Cargando...</h4>
                          </div>
                        </div>
                        <button type="submit" style="display: none;" id="submit-form-rol">Submit</button>
                      </form>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-sm btn-danger" onclick="limpiar_form_rol();" data-bs-dismiss="modal"><i class="las la-times"></i> Close</button>
                        <button type="button" class="btn btn-sm btn-primary" id="guardar_registro_rol"><i class="bx bx-save bx-tada"></i> Guardar</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End::modal-registrar-rol -->

              </div>
            </div>

            <?php } else { $title_submodulo ='Equipo'; $descripcion ='Equipo del Proyecto'; $title_modulo = 'proyecto'; include("403_error.php"); }?>   

            <?php include("template/search_modal.php"); ?>
            <?php include("template/footer.php"); ?>

          </div>

          <?php include("template/scripts.php"); ?>

          <?php include("template/custom_switcherjs.php"); ?>

          <script src="scripts/equipo.js"></script>

        </body>

      </html>
    <?php
  }
  ob_end_flush();
?>
