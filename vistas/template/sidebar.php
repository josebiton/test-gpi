<!-- Start::app-sidebar -->
<aside class="app-sidebar sticky" id="sidebar">

  <!-- Start::main-sidebar-header -->
  <div class="main-sidebar-header">
    <a href="index.php" class="header-logo">
      <img src="../assets/images/brand-logos/desktop-logo.png" alt="logo" class="desktop-logo">
      <img src="../assets/images/brand-logos/toggle-logo.png" alt="logo" class="toggle-logo">
      <img src="../assets/images/brand-logos/desktop-dark.png" alt="logo" class="desktop-dark">
      <img src="../assets/images/brand-logos/toggle-dark.png" alt="logo" class="toggle-dark">
      <img src="../assets/images/brand-logos/desktop-white.png" alt="logo" class="desktop-white">
      <img src="../assets/images/brand-logos/toggle-white.png" alt="logo" class="toggle-white">
    </a>
  </div>
  <!-- End::main-sidebar-header -->

  <!-- Start::main-sidebar -->
  <div class="main-sidebar" id="sidebar-scroll">

    <!-- Start::nav -->
    <nav class="main-menu-container nav nav-pills flex-column sub-open">
      <div class="slide-left" id="slide-left">
        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
          <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
        </svg>
      </div>
      <ul class="main-menu">


        <!-- Start::================== INICIO  ================== -->
        <li class="slide__category"><span class="category-name">I N I C I O</span></li>

        <!-- Escritorio Estudiante -->
        <?php if ($_SESSION['escritorioE'] == '1') { ?>
        <li class="slide">
          <a href="escritorio.php" class="side-menu__item">
            <i class="bx bx-home side-menu__icon"></i><span class="side-menu__label">Escritorio</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::E.Estudiante -->

        <!-- Escritorio Docente -->
        <?php if ($_SESSION['escritorioDOSC'] == '1') { ?>
        <li class="slide">
          <a href="escritorio.php" class="side-menu__item">
            <i class="bx bx-home side-menu__icon"></i><span class="side-menu__label">Escritorio</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::E.Docente -->

        

        <!-- Start::================== PROYECTO  ================== -->
        <li class="slide__category"><span class="category-name">P R O Y E C T O</span></li>
        
        <!-- Perfil de Proyecto -->
        <?php  if ($_SESSION['perfil'] == '1') { ?>
        <li class="slide">
          <a href="perfil_proyecto.php" class="side-menu__item">
            <i class="bx bx-atom side-menu__icon"></i><span class="side-menu__label">Perfil</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::P.Proyecto -->

        <!-- Cronograma -->
        <?php  if ($_SESSION['cronograma'] == '1') { ?>
        <li class="slide">
          <a href="cronograma.php" class="side-menu__item">
            <i class="bx bx-calendar side-menu__icon"></i><span class="side-menu__label">Cronograma</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::P.Proyecto -->
        
        <!-- Alunnos -->
        <?php  if ($_SESSION['alumnos'] == '1') { ?>
        <li class="slide">
          <a href="alumnos.php" class="side-menu__item">
            <i class="bx bx-group side-menu__icon"></i><span class="side-menu__label">Alumnos</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::Alunnos -->
        
        <!-- Equipos -->
        <?php  if ($_SESSION['equipos'] == '1') { ?>
        <li class="slide">
          <a href="equipos.php" class="side-menu__item">
            <i class="bx bx-book side-menu__icon"></i><span class="side-menu__label">Equipos</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::Equipos -->

        <li class="slide__category"><span class="category-name">G E N E R A L</span></li>

        <!-- Equipo -->
        <?php  if ($_SESSION['equipo'] == '1') { ?>
        <li class="slide">
          <a href="equipo.php" class="side-menu__item">
          <i class="bx bx-group side-menu__icon"></i><span class="side-menu__label">Equipo</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::Equipo -->

        <!-- Usuario Estudiante -->
        <?php  if ($_SESSION['usuario estudiante'] == '1') { ?>
        <li class="slide">
          <a href="usuario_e.php" class="side-menu__item">
          <i class="bx bx-user side-menu__icon"></i><span class="side-menu__label">Usuario</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::Usuario-estudiante -->


        
      </ul>
      <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
          height="24" viewBox="0 0 24 24">
          <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
        </svg></div>
    </nav>
    <!-- End::nav -->

  </div>
  <!-- End::main-sidebar -->

</aside>
<!-- End::app-sidebar -->