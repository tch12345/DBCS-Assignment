<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="Plugin/assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="Image/logo.png">
  <title>
    Belial
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="Plugin/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="Plugin/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="Plugin/assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
   <link rel="stylesheet" href="Plugin/sweetalert2.min.css" />
</head>

<body class="g-sidenav-show dark-version bg-gray-600">
  <aside class="sidenav navbar navbar-vertical active navbar-expand-xs border-radius-lg fixed-start ms-2 my-2 bg-transparent" data-class = "bg-transparent" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="#" target="_blank">
        <img src="Image/logo.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-white"><?php echo $_SESSION['name']?></span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?php echo ($page_name=="Dashboard"?"active bg-gradient-dark":"")?> text-white" href="dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($page_name=="Users"?"active bg-gradient-dark":"")?> text-white" href="user.php">
            <i class="material-symbols-rounded opacity-5">group</i>
            <span class="nav-link-text ms-1">Users</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($page_name=="Cards"?"active bg-gradient-dark":"")?> text-white" href="cards.php">
            <i class="material-symbols-rounded opacity-5">credit_card</i>
            <span class="nav-link-text ms-1">Cards</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($page_name=="Transaction"?"active bg-gradient-dark":"")?> text-white" href="trans.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Transactions</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($page_name=="Payment"?"active bg-gradient-dark":"")?> text-white" href="payment.php">
            <i class="material-symbols-rounded opacity-5">payments</i>
            <span class="nav-link-text ms-1">Payments</span>
          </a>
        </li>
        <?php 
        if($_SESSION['id']==md5(1)){
          ?>
         <li class="nav-item">
          <a class="nav-link <?php echo ($page_name=="Logs"?"active bg-gradient-dark":"")?> text-white" href="activity_logs.php">
            <i class="material-symbols-rounded opacity-5">description</i>
            <span class="nav-link-text ms-1">Activity Logs</span>
          </a>
        </li>

          <?php
        }
        
        ?>
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      <div class="mx-3">
        <a class="btn bg-gradient-dark w-100" href="logout.php" type="button">Logout</a>
      </div>
    </div>
  </aside>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page"><?php echo $page_name?></li>
          </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group input-group-outline">
          
            </div>
          </div>
          <ul class="navbar-nav d-flex align-items-center  justify-content-end">
            
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
            
            
            <li class="nav-item d-flex align-items-center">
              
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->