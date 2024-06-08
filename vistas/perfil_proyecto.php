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
          
          <?php $title_page = "Perfil PI"; include("template/head.php"); ?>
          
        </head> 

        <body id="body-usuario" idusuario="<?php echo $_SESSION['idusuario']; ?>"  > 
        <input type="hidden" id="user_e" name="user_e" value="<?php echo $_SESSION['idusuario']; ?>">

          <?php include("template/switcher.php"); ?>
          <?php include("template/loader.php"); ?>

          <div class="page">
            <?php include("template/header.php") ?>
            <?php include("template/sidebar.php") ?>
            <?php if($_SESSION['perfil'] == 1) { ?>

            <!-- Start::app-content -->
            <div class="main-content app-content">
              <div class="container-fluid">

                <!-- Start::page-header -->

                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                  <div>
                    <div class="d-md-flex d-block align-items-center ">
                      <button class="btn-modal-effect btn btn-primary label-btn btn-agregar m-r-10px" onclick="show_hide_form(2);  limpiar_form_perfil(); "  > <i class="ri-edit-line label-btn-icon me-2"></i>Editar </button>
                      <button type="button" class="btn btn-danger btn-cancelar m-r-10px" onclick="show_hide_form(1);" style="display: none;"><i class="ri-arrow-left-line"></i></button>
                      <button class="btn-modal-effect btn btn-success label-btn btn-guardar m-r-10px" style="display: none;"> <i class="ri-save-2-line label-btn-icon me-2" ></i> Guardar </button>
                      <div>
                        <p class="fw-semibold fs-18 mb-0">Perfil del Proyecto Integrador</p>
                        <span class="fs-semibold text-muted">Gestiona adecuadamente tu proyecto &#128521;</span>
                      </div>
                    </div>
                  </div>
                  <div class="btn-list mt-md-0 mt-2">              
                    <nav>
                      <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">PROYECTO</a></li>
                        <li class="breadcrumb-item active" aria-current="page">perfil</li>
                      </ol>
                    </nav>
                  </div>
                </div>

                <!-- End::page-header -->

                <!-- Start::row-1 -->
                <div class="row">
                  <div class="col-xxl-12 col-xl-12">
                    
                    <div class="card custom-card ">                  
                      <div class="card-body">
                        
                        <div class="row" id="div-perfil">
                          <!-- LISTO PARA EL DISEÑO MAS PERROM DE LA HISTORIA DE PI -->
                          <span id="nombre_p" class="d-block p-2 bg-secondary-transparent mb-1 rounded">d-block</span>
                        </div>

                        <div class="row" id="div-form" style="display: none;">
                          <form name="form-perfil-pi" id="form-perfil-pi" method="POST" class="needs-validation" novalidate>

                            <div class="row gy-4" id="cargando-1-fomulario">

                              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="titulo_p" class="form-label">Titulo del Proyecto</label>
                                <input type="text" class="form-control rounded-pill" id="titulo_p" name="titulo_p">
                              </div>

                              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="descripcion_p" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion_p" name="descripcion_p" rows="3"></textarea>
                              </div>

                              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="fecha_i" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="fecha_i" name="fecha_i">
                              </div>

                              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="fecha_e" class="form-label">Fecha de Entrega</label>
                                <input type="date" class="form-control" id="fecha_e" name="fecha_e">
                              </div>

                              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="link_p" class="form-label">Link del Prototipo</label>
                                <input class="form-control" type="url" id="link_p" name="link_p" placeholder="http://example.com">
                              </div>

                            </div>
                            
                            <br><br>

                            <div class="row gy-2" id="cargando-2-fomulario">


                              <label for="hitos">
                                <b class="m-3">Lista de Hitos</b>
                                <button class="btn btn-primary-light btn-sm">Agregar</button>
                              </label>
                              <div class="table-responsive">
                                <table class="table text-nowrap table-bordered" id="tbl-hitos">
                                  <thead>
                                    <tr>
                                      <th scope="col" class="text-center">#</th>
                                      <th scope="col" class="text-center">Acciones</th>
                                      <th scope="col">Hito</th>
                                      <th scope="col">Descripción</th>
                                      <th scope="col">Fecha de Entrega</th>
                                      <th scope="col" class="text-center">estado</th>
                                    </tr>
                                  </thead>
                                  <tbody></tbody>
                                </table>
                              </div>
                            </div>

                            <!-- cargando... -->
                            <div class="row" id="cargando-3-fomulario" style="display: none;">
                              <div class="col-lg-12 text-center">                         
                                <div class="spinner-border me-4" style="width: 3rem; height: 3rem;"role="status"></div>
                                <h4 class="bx-flashing">Cargando...</h4>
                              </div>
                            </div>

                            <!-- Chargue -->
                            <div class="p-l-25px col-lg-12" id="barra_progress_perfil_pi_div" style="display: none;" >
                              <div  class="progress progress-lg custom-progress-3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"> 
                                <div id="barra_progress_perfil_pi" class="progress-bar" style="width: 0%"> <div class="progress-bar-value">0%</div> </div> 
                              </div>
                            </div>
                            <!-- Submit -->
                            <button type="submit" style="display: none;" id="submit-form-perfil-pi">Submit</button>

                          </form>
                        </div>
                          
                                            
                      </div>  
                      <div class="card-footer border-top-0">
                        <button type="button" class="btn btn-danger btn-cancelar" onclick="show_hide_form(1);" style="display: none;"><i class="las la-times fs-lg"></i> Cancelar</button>
                        <button class="btn-modal-effect btn btn-success label-btn btn-guardar m-r-10px" style="display: none;"  > <i class="ri-save-2-line label-btn-icon me-2" ></i> Guardar </button>
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

          <script src="scripts/perfil_proyecto.js"></script>

        </body>

      </html>
    <?php
  }
  ob_end_flush();
?>
