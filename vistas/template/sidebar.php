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
        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">I N I C I O</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <?php  if ($_SESSION['dashboard_empresa'] == '1') { ?>
        <li class="slide">
          <a href="escritorio.php" class="side-menu__item">
            <i class="bx bx-home side-menu__icon"></i><span class="side-menu__label"> Dashboards</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::slide -->

        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">L O G I S T I C A</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <?php  if ($_SESSION['empresa'] == '1') { ?>
        <li class="slide">
          <a href="trabajador.php" class="side-menu__item">
            <i class="bx bx-building side-menu__icon"></i><span class="side-menu__label"> Empresa</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::slide -->

        <!-- Start::slide -->
        <?php  if ($_SESSION['nosotros'] == '1') { ?>
        <li class="slide">
          <a href="pos.php" class="side-menu__item">
            <i class="bx bx-extension side-menu__icon"></i><span class="side-menu__label"> Nosotros</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::slide -->



        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">E S T R U C T U R A</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <?php  if ($_SESSION['sucursales'] == '1') { ?>
        <li class="slide">
          <a href="pos.php" class="side-menu__item">
            <i class="bx bx-buildings side-menu__icon"></i><span class="side-menu__label"> sucursales</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::slide -->

        <!-- Start::slide -->
        <?php  if ($_SESSION['departamentos'] == '1') { ?>
        <li class="slide">
          <a href="pos.php" class="side-menu__item">
            <i class="bx bx-sitemap side-menu__icon"></i><span class="side-menu__label"> departamentos</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::slide -->

        <!-- Start::slide -->
        <?php  if ($_SESSION['subdepartamentos'] == '1') { ?>
        <li class="slide">
          <a href="pos.php" class="side-menu__item">
            <i class="bx bx-network-chart side-menu__icon"></i><span class="side-menu__label"> Subdepartamentos</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::slide -->

        <!-- Start::slide -->
        <?php  if ($_SESSION['departamentos_operativos'] == '1') { ?>
        <li class="slide">
          <a href="pos.php" class="side-menu__item">
            <i class="bx bx-home side-menu__icon side-menu__icon"></i><span class="side-menu__label"> departamentos_operativos</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::slide -->

        <!-- Start::slide__category -->
        <li class="slide__category"><span class="category-name">A D M I N I S T R A C I Ó N</span></li>
        <!-- End::slide__category -->

        <!-- Start::slide -->
        <?php  if ($_SESSION['usuario'] == '1') { ?>
        <li class="slide">
          <a href="usuario.php" class="side-menu__item">
            <i class="bx bx-user side-menu__icon"></i><span class="side-menu__label">Usuario</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::slide -->

        <!-- Start::slide -->
        <?php  if ($_SESSION['trabajador'] == '1') { ?>
        <li class="slide">
          <a href="trabajador.php" class="side-menu__item">
            <i class="bx bx-briefcase side-menu__icon"></i><span class="side-menu__label">Trabajador</span>
          </a>
        </li>
        <?php } ?>
        <!-- End::slide -->

        
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