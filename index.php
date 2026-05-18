<?php include 'db.php'; ?>
<!doctype html>
<!-- Declares the document type as HTML5 -->
<html>

<head>
  <meta charset="utf-8" />
  <!-- Defines character encoding (supports accents & symbols) : how the web browser is going to read it -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Ensures compatibility with Internet Explorer -->
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <!-- Makes the website responsive on all screen sizes -->
  <title>La Fleur</title>
  <!--website name shown at the tab on top-->
  <link rel="icon" type="image/png" href="images/icon de site.png" />
  <!--icon in browser tab-->

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
  <!--font awesome : cons library (cart, heart, stars)-->

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    rel="stylesheet" />
  <!--bootstrap link for icons-->

  <link rel="stylesheet" href="style/style.css" />
  <!--the  custom style css file : has the styling stuff -->

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous" />
  <!--bootstrap link for layout, grid system, components , cards , carousel ...-->
</head>

<body class="d-flex flex-column min-vh-100">
  <!--nav bar start-->
  <!-- Fixed navigation bar at the top -->
  <?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  include_once 'api/db.php';

  $cart_count = 0;
  $wish_count = 0;

  if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];

    // Count Cart Items (Sum of quantities)
    $c_stmt = $pdo->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
    $c_stmt->execute([$uid]);
    $cart_count = $c_stmt->fetchColumn() ?: 0;

    // Count Wishlist Items
    $w_stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
    $w_stmt->execute([$uid]);
    $wish_count = $w_stmt->fetchColumn() ?: 0;
  }
  ?>
  <nav class="navbar navbar-expand-lg navbar-light bg-white py-4 fixed-top">
    <div class="container">
      <!-- Brand logo and website name -->
      <a
        class="navbar-brand d-flex align-items-center order-lg-0"
        href="index.php">
        <img src="images/icon de site.png" alt="site icon" />
        <span class="fw-lighter ms-2">La Fleur</span>
      </a>

      <!-- Icons on the right (cart, favorites, search) -->
      <!----dynamic cart -->
      <div class="order-lg-2 nav-btns">
        <a href="cart.php" class="btn position-relative border-0" style="color: inherit;">
          <i class="fa fa-shopping-cart"></i>
          <span id="cart-badge"
            class="position-absolute top-0 start-100 translate-middle badge bg-primary"><?php echo $cart_count; ?></span>
        </a>
        <!--dynamic wishlist -->
        <a href="wishlist.php" class="btn position-relative border-0" style="color: inherit;">
          <i class="fa fa-heart"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge bg-pink">
            <?php echo $wish_count; ?>
          </span>
        </a>
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="dropdown d-inline-block">
            <button class="btn dropdown-toggle border-0 shadow-none p-2" type="button" data-bs-toggle="dropdown">
              <i class="fa fa-user"></i>
              <span class="ms-1 small d-none d-md-inline-block fw-bold"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">

              <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <li>
                  <a class="dropdown-item py-2" href="admin/index.php">
                    <i class="bi bi-speedometer2 me-2"></i> Admin Dashboard
                  </a>
                </li>

              <?php else: ?>
                <li>
                  <a class="dropdown-item py-2" href="profile.php">
                    <i class="bi bi-person-circle me-2"></i> View Profile
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-2" href="my-orders.php">
                    <i class="bi bi-bag-check me-2"></i> My Orders
                  </a>
                </li>
              <?php endif; ?>

              <li>
                <hr class="dropdown-divider">
              </li>

              <li>
                <a class="dropdown-item py-2 text-danger" href="logout.php">
                  <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
              </li>
            </ul>
          </div>
        <?php else: ?>
          <a href="login.php" class="btn border-0 shadow-none" title="Login/Register">
            <i class="fa fa-user"></i>
          </a>
        <?php endif; ?>

        <button type="button" class="btn position-relative border-0">
          <i class="fa fa-search"></i>
        </button>
      </div>

      <!-- Hamburger button for small screens -->
      <button
        class="navbar-toggler border-0"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navMenu"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navigation links -->
      <div class="collapse navbar-collapse order-lg-1" id="navMenu">
        <ul class="navbar-nav mx-auto text-center">
          <!-- each link scrolls to a section when i click on it -->
          <li class="nav-item px-2 py-2">
            <a
              class="nav-link text-uppercase text-dark"
              href="index.php"
              target="_self">home</a>
          </li>

          <!-- Products dropdown -->
          <li class="nav-item dropdown px-2 py-2">
            <a
              class="nav-link dropdown-toggle text-dark text-uppercase"
              href="#"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false">Products</a>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item" href="catalogue/books.php">Books</a>
              </li>
              <li>
                <a class="dropdown-item" href="catalogue/ebooks.php">EBooks</a>
              </li>
              <li>
                <a class="dropdown-item" href="catalogue/trending.php">Trending</a>
              </li>
              <li>
                <a class="dropdown-item" href="catalogue/magazines.php">Magazines</a>
              </li>
              <li>
                <a
                  class="dropdown-item"
                  href="catalogue/supplies&accessories.php">Supplies & Accessories</a>
              </li>
              <li>
                <a class="dropdown-item" href="catalogue/stationary.php">Stationery</a>
              </li>
              <li>
                <a
                  class="dropdown-item"
                  href="catalogue/educational kits.php">Educational Kits</a>
              </li>
              <li>
                <a class="dropdown-item" href="catalogue/gifts & merch.php">Gifts & Merch</a>
              </li>
            </ul>
          </li>
          <li class="nav-item px-2 py-2">
            <a class="nav-link text-uppercase text-dark" href="blogs.php">blogs</a>
          </li>
          <li class="nav-item px-2 py-2">
            <a class="nav-link text-uppercase text-dark" href="about us.php">about us</a>
          </li>
          <li class="nav-item px-2 py-2">
            <a class="nav-link text-uppercase text-dark" href="popular.php">popular</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!--nav bar end-->

  <!--header start-->
  <!-- full screen header with sliding images aka carousel with 2 imgs -->
  <header
    id="header"
    class="vh-100 carousel slide"
    data-bs-ride="carousel"
    style="padding-top: 104px">
    <div id="carouselExample" class="carousel slide h-100">
      <!-- Wrapper for carousel slides : defines the boundary for the slides -->
      <div class="carousel-inner h-100">
        <!-- Slide 1 -->
        <div class="carousel-item active h-100 position-relative">
          <img
            src="images/carousel 1.png"
            class="d-block w-100 h-100 object-fit-cover"
            alt="outside" />
          <!-- Text centered on image -->
          <div
            class="position-absolute top-50 start-50 translate-middle text-center">
            <h2 class="text-capitalize text-white">Best collections</h2>
            <h1 class="text-uppercase py-2 fw-bold text-white">
              New arrivals
            </h1>
            <a href="#" class="btn btn-light mt-3 text-uppercase">Shop now</a>
          </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item h-100 position-relative">
          <img
            src="images/carousel 2.jpg"
            class="d-block w-100 h-100 object-fit-cover"
            alt="inside" />

          <div
            class="position-absolute top-50 start-50 translate-middle text-center">
            <h2 class="text-capitalize text-white">Best prices & offers</h2>
            <h1 class="text-uppercase py-2 fw-bold text-white">new season</h1>
            <a href="#" class="btn btn-light mt-3 text-uppercase">buy now</a>
          </div>
        </div>
      </div>
      <!-- Carousel navigation buttons -->
      <button
        class="carousel-control-prev"
        type="button"
        data-bs-target="#carouselExample"
        data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>

      <button
        class="carousel-control-next"
        type="button"
        data-bs-target="#carouselExample"
        data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
  </header>
  <!--header end -->

  <!-- collection -->
  <!-- Displays products with filtering (Isotope.js) the different buttons give different products -->
  <section id="collection" class="py-5">
    <div class="container">
      <!--  Wrapper for the Section title -->
      <div class="title text-center">
        <!-- title, positioned relative for  decorative effects : the | in front of it is pink -->
        <h2 class="position-relative d-inline-block">YEAR END SALE 2025</h2>
      </div>
      <!--button names for the collection sale-->
      <div class="row g-0">
        <!-- Bootstrap row with no gutters -->
        <div
          class="d-flex flex-wrap justify-content-center mt-5 filter-button-group">
          <!-- Button group used by Isotope to filter products -->
          <!-- d-flex + flex-wrap allows buttons to wrap on small screens -->
          <!-- justify-content-center centers buttons horizontally -->
          <button
            type="button"
            class="btn m-2 text-dark active-filter-btn"
            data-filter=".bks">
            Books
          </button>
          <!-- Filter button for BOOKS -->
          <!-- data-filter=".bks" shows only elements with class "bks" -->

          <button type="button" class="btn m-2 text-dark" data-filter=".stat">
            Stationery
          </button>
          <!-- Filter button for STATIONERY -->

          <button type="button" class="btn m-2 text-dark" data-filter=".supp">
            Supplies & Accessories</button><!-- Filter button for SUPPLIES & ACCESSORIES -->

          <button type="button" class="btn m-2 text-dark" data-filter=".edu">
            Educational Kits
          </button>
          <!-- Filter button for EDUCATIONAL KITS -->

          <button type="button" class="btn m-2 text-dark" data-filter=".gifs">
            Gifts & Merch
          </button>
          <!-- Filter button for gifts and merch -->
        </div>

        <!-- products grid -->
        <!-- This is the Isotope container that holds all products books gifts..-->
        <div class="collection-list mt-4 row gx-0 gy-3">
          <!--books-->
          <!-- Bootstrap column responsive on different screen sizes -->
          <!-- Class "bks" is used by Isotope for filtering -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 bks">
            <!-- Product image container -->
            <!-- position-relative allows the SALE badge to be positioned absolutely -->
            <div class="collection-img position-relative">
              <!-- Product image -->
              <!-- w-100 makes image fill its container -->
              <img src="images/des-hommes-sans-femmes.jpg" class="w-100" />
              <!-- Sale badge -->
              <!-- position-absolute places it over the image -->
              <!-- d-flex centers text inside the badge -->
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">sale 25%
              </span>
            </div>
            <!-- Product text area -->
            <div class="text-center">
              <!--  it's just a random  5 star rating -->
              <!-- Rating stars : Font Awesome icons styled with Bootstrap color -->
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <!-- Product name -->
              <!-- text-capitalize capitalizes first letter of each word -->
              <p class="text-capitalize my-1">Des hommes sans femmes</p>
              <a href="manage_cart.php?action=add&id=1" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>
              <a href="toggle_wishlist.php?id=1" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <div class="mt-2">
                <span class="fw-bold"><del style="color: red">84,000 TND</del> 63,000 TND</span>
              </div>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 bks">
            <div class="collection-img position-relative">
              <img
                src="images/l-atlas-des-explorations-les-hommes-et-les-femmes-à-la-découverte-du-monde-et-de-l-univers.jpg"
                class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 25%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                L'atlas des explorations - Les hommes et les femmes à la
                découverte du monde et de l'Univers
              </p>
              <a href="manage_cart.php?action=add&id=2" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=2" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">79,600 TND</del> 59,700 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 bks">
            <div class="collection-img position-relative">
              <img src="images/slammed-colleen-hoover.webp" class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 5%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">Slammed</p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=3" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=3" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">37,000 TND</del> 35,150 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 bks">
            <div class="collection-img position-relative">
              <img
                src="images/different-strokes-by-different-folks.webp"
                class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 5%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                Different Strokes By Different Folks
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=4" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=4" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">35,000 TND</del> 33,250 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 bks">
            <div class="collection-img position-relative">
              <img src="images/atonement-ian-mcewan.webp" class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 5%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">Atonement</p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=5" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=5" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">39,500 TND</del> 37,525 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 bks">
            <div class="collection-img position-relative">
              <img
                src="images/the-mad-women-s-ball-victoria-mas.webp"
                class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 5%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">The Mad Women's Ball</p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=6" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=6" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">35,400 TND</del> 33,630 TND</span>
            </div>
          </div>

          <div class="col-md-6 col-lg-4 col-xl-3 p-2 bks">
            <div class="collection-img position-relative">
              <img src="images/philosophicae-historica.jpg" class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 25%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">Philosophicae Historica</p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=7" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=7" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">125,600 TND</del> 94,200 TND
              </span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 bks">
            <div class="collection-img position-relative">
              <img src="images/The old man and the seaa.jpg" class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 30%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">The old man and the sea</p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=8" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=8" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">39,990 TND</del> 27,990 DT TND</span>
            </div>
          </div>
          <!--end of books-->

          <!--stationary-->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 stat">
            <div class="collection-img position-relative">
              <img src="images/agenda.webp" class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">sale 25%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                Agenda Quotidien – Planner journalier structuré & élégant (21
                × 15 cm)
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=9" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=9" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">24,500 TND</del> 18,500 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 stat">
            <div class="collection-img position-relative">
              <img src="images/fluo.webp" class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 25%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                Coffret 4 Surligneurs Fluorescents
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=10" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=10" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">15,900 TND</del> 12,040 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 stat">
            <div class="collection-img position-relative">
              <img src="images/coffret stylo.webp" class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 55%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                Coffret en bois 60 Crayons fusains pastel - CarbOthello -
                Coloris assortis - Stabilo
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=11" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=11" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">327,500 TND</del> 147,375 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 stat">
            <div class="collection-img position-relative">
              <img src="images/stylos.webp" class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 35%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">STABILO POINT 88 ROLLERSET</p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=12" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=12" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">75,000 TND</del> 48,750 TND</span>
            </div>
          </div>
          <!--statironary end -->
          <!--supplies & accs-->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 supp">
            <div class="collection-img position-relative">
              <img
                src="images/aggra.webp
                "
                class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 10%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                Agrafeuse STD Popular S-80 – Full Strip plastique, chargement
                par le dessus
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=13" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=13" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">10,000 TND</del> 9,000 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 supp">
            <div class="collection-img position-relative">
              <img src="images/cal.webp" class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 30%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                Calculatrice graphique avec Python GRAPH 35+E II - CASIO
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=14" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=14" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">450,000 TND</del> 315,000 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 supp">
            <div class="collection-img position-relative">
              <img src="images/loupe.webp" class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 26%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                LOUPE METAL 100MM G03-06 FOSKA
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=15" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=15" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>

              <span class="fw-bold"><del style="color: red">13,550 TND</del> 10,000 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 supp">
            <div class="collection-img position-relative">
              <img
                src="images/calculatrice-scientifique-casio-fx-991-es-plus-2nd-edition-bureautique.webp"
                class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 30%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                Calculatrice scientifique Casio fx-991 ES PLUS -2nd edition
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=16" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=16" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">93,000 TND</del> 65,000 TND</span>
            </div>
          </div>
          <!--supplies and accs end-->
          <!--education kits-->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 edu">
            <div class="collection-img position-relative">
              <img src="images/tapis 1 9.webp" class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 15%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                Tapis Puzzle en Mousse EVA (6 pièces) – Chiffres 1 à 9 – Jeu
                d’Éveil Éducatif pour Enfants (3+)
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=17" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=17" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">29,900 TND</del> 25,420 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 edu">
            <div class="collection-img position-relative">
              <img src="images/projecy.webp" class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 15%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                Projecteur de Dessin Enfant – Table de Projection + 12 Feutres
                Aquarelles – Tableau Effaçable 3+
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=18" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=18" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">42,900 TND</del> 36,5100 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 edu">
            <div class="collection-img position-relative">
              <img
                src="images/little-cute-duck-magnetic-drawing-board-age-3-jouets.webp"
                class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 15%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                Little Cute Duck🐥👶 Magnetic🧲 Drawing Board🎨🖌️ - Age 3+
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=19" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=19" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">28,900 TND</del> 24,510 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 edu">
            <div class="collection-img position-relative">
              <img src="images/tapis shapes.webp" class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 15%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                Tapis Puzzle en Mousse EVA (6 pièces) – Formes & Couleurs –
                Jeu d’Éveil pour Enfants (3+)
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=20" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=20" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">29,900 TND</del> 25,420 TND</span>
            </div>
          </div>
          <!--educations kit end-->
          <!--gifts-->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 gifs">
            <div class="collection-img position-relative">
              <img
                src="images/pack_kalino_mandalas_12_sea.webp"
                class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 35%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                Pack Mandalas + Crayons Graphic Marker — Mindfulness Kids
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=21" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=21" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">70,000 TND</del> 45,000 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 gifs">
            <div class="collection-img position-relative">
              <img
                src="images/mandakas_wild_life_kalino_blanc_pack.webp"
                class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 26%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                Pack Mandalas + Crayons Graphic Marker — Mindfulness Kids
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=22" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=22" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">175,000 TND</del> 129,000 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 gifs">
            <div class="collection-img position-relative">
              <img
                src="images/pack_kalino_mandalas_120_sea.webp"
                class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 24%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                Pack Mandalas + Crayons Graphic Marker — Mindfulness Kids
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=23" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=23" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">292,500 TND</del> 223,000 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 gifs">
            <div class="collection-img position-relative">
              <img
                src="images/trousse-cool-school-bagagerie-2.webp"
                class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 57%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">TROUSSE-COOL SCHOOL La Fleur</p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=24" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=24" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">23,000 TND</del> 9,950 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 gifs">
            <div class="collection-img position-relative">
              <img src="images/trousse rond.webp" class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 43%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">Trousse rond La Fleur</p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=25" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=25" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">22,500 TND</del> 12,950 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 gifs">
            <div class="collection-img position-relative">
              <img src="images/pack_1.webp" class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 28%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                Pack Sketch & Dessin – Crayons Artistiques 24 Couleurs +
                Carnet Sketch A5 (72 feuilles)
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=26" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=26" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">68,900 TND</del> 50,000 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 gifs">
            <div class="collection-img position-relative">
              <img src="images/80012672_froozen_2.webp" class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 20%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">
                Tente de Jeu Enfant 65×65×90 cm – Frozen
              </p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=27" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=27" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">33,500 TND</del> 26,800 TND</span>
            </div>
          </div>
          <!-- ANOTHER ONE-->
          <!-- Same structure different content -->
          <div class="col-md-6 col-lg-4 col-xl-3 p-2 gifs">
            <div class="collection-img position-relative">
              <img
                src="images/chess-magnetic-game-age-6-jouets.webp"
                class="w-100" />
              <span
                class="position-absolute bg-pink text-white d-flex align-items-center justify-content-center">
                sale 10%
              </span>
            </div>
            <div class="text-center">
              <div class="rating mt-3">
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
                <span class="text-primary"><i class="fas fa-star"></i></span>
              </div>
              <p class="text-capitalize my-1">Chess</p>
              <!-- CART LINK -->
              <a href="manage_cart.php?action=add&id=28" class="btn btn-cart">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="toggle_wishlist.php?id=28" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              <span class="fw-bold"><del style="color: red">69,900 TND</del> 62,900 TND</span>
            </div>
          </div>
          <!--gifts end -->
        </div>
      </div>
    </div>
  </section>
  <!-- end of collection -->

  <!-- special products -->
  <!-- This section highlights main product categories and a sidebar menu -->
  <section id="special" class="py-5">
    <!-- Bootstrap container to center content and add horizontal padding -->
    <div class="container">
      <!-- Section title area -->
      <div class="title text-center py-5">
        <!-- Section heading -->
        <h2 class="position-relative d-inline-block">
          Explore All The Categories
        </h2>
      </div>
      <!-- Grid row for category cards -->
      <div class="special-list row g-0">
        <!-- Category card: Books -->
        <div class="col-md-6 col-lg-4 col-xl-3 p-2">
          <!-- Image container with relative positioning -->
          <div class="special-img position-relative overflow-hidden">
            <!-- Category image -->
            <img src="images/books.webp" class="w-100" />
          </div>
          <!-- Category name and button -->
          <div class="text-center">
            <p class="text-capitalize mt-3 mb-1">BOOKS</p>

          </div>
        </div>
        <!-- Category card: Stationery -->
        <div class="col-md-6 col-lg-4 col-xl-3 p-2">
          <div class="special-img position-relative overflow-hidden">
            <img src="images/stationery.webp" class="w-100" />
          </div>
          <div class="text-center">
            <p class="text-capitalize mt-3 mb-1">Stationery</p>

          </div>
        </div>
        <!-- Category card: Art -->
        <div class="col-md-6 col-lg-4 col-xl-3 p-2">
          <div class="special-img position-relative overflow-hidden">
            <img src="images/beaux_art.webp" class="w-100" />
          </div>
          <div class="text-center">
            <p class="text-capitalize mt-3 mb-1">Art</p>

          </div>
        </div>
        <!-- Category card: Dictionaries -->
        <div class="col-md-6 col-lg-4 col-xl-3 p-2">
          <div class="special-img position-relative overflow-hidden">
            <img src="images/dicitionaries.webp" class="w-100" />
          </div>
          <div class="text-center">
            <p class="text-capitalize mt-3 mb-1">Dictionairies</p>

          </div>
        </div>
      </div>
    </div>

    <br />
    <!-- Space before the explore more button -->
    <!--the explore more centered button shows side bar -->
    <div class="d-flex justify-content-center align-items-center">
      <button
        class="btn btn-pink btn-lg px-5 py-3 fs-4 shadow"
        type="button"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasExample"
        aria-controls="offcanvasExample">
        Explore more products ...
      </button>
    </div>
    <!-- Bootstrap offcanvas sidebar (slides from the left) -->
    <div
      class="offcanvas offcanvas-start"
      tabindex="-1"
      id="offcanvasExample"
      aria-labelledby="offcanvasExampleLabel">
      <!-- Offcanvas header with title and close button -->
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">
          Our Poducts
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="offcanvas"
          aria-label="Close"></button>
      </div>
      <!-- Offcanvas main content -->
      <div class="offcanvas-body">
        <!-- description text -->
        <div class="mb-4 text-muted">
          "La Fleur" bookstore is renowned for its premium selection and the
          exceptional quality of its products, catering to every book lover's
          needs. <br />
          Choosing "La Fleur" means opting for a refined shopping experience
          where quality and care are always the priority.
        </div>
        <!-- Sidebar navigation menu -->
        <div class="bookstore-menu">
          <h6 class="menu-heading">CLICK & DISCOVER</h6>
          <!-- Menu section heading -->
          <nav class="nav flex-column mb-3">
            <!-- Vertical navigation links -->
            <a class="nav-link" href="#">Up-Coming products</a>
            <a class="nav-link" href="#">The Essentials !</a>
            <a class="nav-link d-flex justify-content-between" href="#">
              Award Winning Books <i class="bi bi-chevron-right"></i>
            </a>
          </nav>
          <!-- Second menu section -->
          <h6 class="menu-heading">What are you looking for ?</h6>
          <nav class="nav flex-column">
            <a class="nav-link d-flex justify-content-between" href="#">
              Tunisian Books <i class="bi bi-chevron-right"></i>
            </a>
            <a class="nav-link d-flex justify-content-between" href="#">
              Books in French <i class="bi bi-chevron-right"></i>
            </a>
            <a class="nav-link d-flex justify-content-between" href="#">
              Books in English<i class="bi bi-chevron-right"></i>
            </a>
            <a class="nav-link d-flex justify-content-between" href="#">
              كتب من المشرق العربي <i class="bi bi-chevron-right"></i>
            </a>
          </nav>
        </div>
      </div>
    </div>
  </section>
  <!-- end of special products -->

  <!--offer -->
  <section id="offers" class="py-5">
    <div class="container">
      <div
        class="row d-flex align-items-center justify-content-center text-center justify-content-lg-start text-lg-start">
        <!-- d-flex : bootstrap flexbox  cad display : flex ; = Align items horizontally or vertically , Center content easily , Control spacing between elements -->
        <div class="offers-content">
          <span class="text-white">Discount Up To 60%</span>
          <h2 class="mt-2 mb-4" style="color: #a80f8f">Grand Sale Offer!</h2>
          <a href="#" class="btn">Buy Now</a>
        </div>
      </div>
    </div>
  </section>
  <!--offer end -->

  <!--Our Top Picks-->
  <!--top title-->
  <section id="favs" class="py-5">
    <div class="container">
      <div class="title text-center py-5">
        <h2 class="position-relative d-inline-block">Our Top Picks</h2>
      </div>

      <!-- Carousel wrapper -->
      <div
        id="bookCarousel"
        class="carousel slide"
        data-bs-ride="carousel"
        data-bs-interval="4000">
        <div class="carousel-inner">
          <!-- Slide 1 -->
          <div class="carousel-item active">
            <div
              class="row row-cols-2 row-cols-sm-3 row-cols-md-5 text-center g-3">
              <div class="col">
                <div class="book-card">
                  <img src="images/etrangercamus.jpg" alt="1" />
                  <p class="mt-3">L'Étranger</p>
                  <p class="text-muted">Albert Camus</p>
                  <p class="price">31,920 TND</p>
                  <!-- CART LINK -->
                  <a href="manage_cart.php?action=add&id=29" class="btn btn-cart border-0">
                    <i class="bi bi-basket"> </i>
                  </a>

                  <!-- WISHLIST LINK -->
                  <a href="toggle_wishlist.php?id=29" class="btn btn-outline-danger border-0">
                    <i class="bi bi-heart"></i>
                  </a>
                </div>
              </div>

              <div class="col">
                <div class="book-card">
                  <img src="images/doriangray.jpg" alt="2" />
                  <p class="mt-3">The Picture of Dorian Gray</p>
                  <p class="text-muted">Oscar Wilde</p>
                  <p class="price">32,200 TND</p>
                  <!-- CART LINK -->
                  <a href="manage_cart.php?action=add&id=30" class="btn btn-cart border-0">
                    <i class="bi bi-basket"></i>
                  </a>

                  <!-- WISHLIST LINK -->
                  <a href="toggle_wishlist.php?id=30" class="btn btn-outline-danger border-0">
                    <i class="bi bi-heart"></i>
                  </a>
                </div>
              </div>

              <div class="col">
                <div class="book-card">
                  <img
                    src="images/beyond-good-and-evil-friedrich-nietzsche.jpg"
                    alt="3" />
                  <p class="mt-3">Beyond Good and Evil</p>
                  <p class="text-muted">Friedrich Nietzsche</p>
                  <p class="price">38,700 TND</p>
                  <!-- CART LINK -->
                  <a href="manage_cart.php?action=add&id=31" class="btn btn-cart border-0">
                    <i class="bi bi-basket"></i>
                  </a>

                  <!-- WISHLIST LINK -->
                  <a href="toggle_wishlist.php?id=31" class="btn btn-outline-danger border-0">
                    <i class="bi bi-heart"></i>
                  </a>
                </div>
              </div>

              <div class="col">
                <div class="book-card">
                  <img
                    src="images/mouse-s-night-before-christmas-.jpg"
                    alt="4" />
                  <p class="mt-3">Mouse's Night Before Christmas</p>
                  <p class="text-muted">Tracey Corderoy</p>
                  <p class="price">22,000 TND</p>
                  <!-- CART LINK -->
                  <a href="manage_cart.php?action=add&id=32" class="btn btn-cart border-0">
                    <i class="bi bi-basket"></i>
                  </a>

                  <!-- WISHLIST LINK -->
                  <a href="toggle_wishlist.php?id=32" class="btn btn-outline-danger border-0">
                    <i class="bi bi-heart"></i>
                  </a>
                </div>
              </div>

              <div class="col">
                <div class="book-card">
                  <img src="images/fiesta-ernest-hemingway.jpg" alt="5" />
                  <p class="mt-3">Fiesta</p>
                  <p class="text-muted">Ernest Hemingway</p>
                  <p class="price">35,400 TND</p>
                  <!-- CART LINK -->
                  <a href="manage_cart.php?action=add&id=33" class="btn btn-cart border-0">
                    <i class="bi bi-basket"></i>
                  </a>

                  <!-- WISHLIST LINK -->
                  <a href="toggle_wishlist.php?id=33" class="btn btn-outline-danger border-0">
                    <i class="bi bi-heart"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Slide 2 -->
          <div class="carousel-item">
            <div
              class="row row-cols-2 row-cols-sm-3 row-cols-md-5 text-center g-3">
              <div class="col">
                <div class="book-card">
                  <img
                    src="images/harry-potter-and-the-half-blood-prince-book-6.jpg"
                    alt="6" />
                  <p class="mt-3">Harry Potter and the Half-Blood Prince</p>
                  <p class="text-muted">J. K. Rowling</p>
                  <p class="price">43,500 TND</p>
                  <!-- CART LINK -->
                  <a href="manage_cart.php?action=add&id=34" class="btn btn-cart border-0">
                    <i class="bi bi-basket"></i>
                  </a>

                  <!-- WISHLIST LINK -->
                  <a href="toggle_wishlist.php?id=34" class="btn btn-outline-danger border-0">
                    <i class="bi bi-heart"></i>
                  </a>
                </div>
              </div>

              <div class="col">
                <div class="book-card">
                  <img
                    src="images/first-person-singular-haruki-murakami.jpg"
                    alt="7" />
                  <p class="mt-3">First Person Singular</p>
                  <p class="text-muted">Haruki Murakami</p>
                  <p class="price">38,500 TND</p>
                  <!-- CART LINK -->
                  <a href="manage_cart.php?action=add&id=35" class="btn btn-cart border-0">
                    <i class="bi bi-basket"></i>
                  </a>

                  <!-- WISHLIST LINK -->
                  <a href="toggle_wishlist.php?id=35" class="btn btn-outline-danger border-0">
                    <i class="bi bi-heart"></i>
                  </a>
                </div>
              </div>

              <div class="col">
                <div class="book-card">
                  <img
                    src="images/fire-and-blood-martin-george-rr.jpg"
                    alt="8" />
                  <p class="mt-3">Fire and blood</p>
                  <p class="text-muted">Martin George R.R</p>
                  <p class="price">51,600 TND</p>
                  <!-- CART LINK -->
                  <a href="manage_cart.php?action=add&id=36" class="btn btn-cart border-0">
                    <i class="bi bi-basket"></i>
                  </a>

                  <!-- WISHLIST LINK -->
                  <a href="toggle_wishlist.php?id=36" class="btn btn-outline-danger border-0">
                    <i class="bi bi-heart"></i>
                  </a>
                </div>
              </div>

              <div class="col">
                <div class="book-card">
                  <img
                    src="images/harry-potter-boxed-set-the-complete-collection-7-paperbacks.jpg"
                    alt=" 9" />
                  <p class="mt-3">
                    Harry Potter Box Set: The Complete Collection (Children’s
                    Paperback)
                  </p>
                  <p class="text-muted">J. K. Rowling</p>
                  <p class="price">243,800 TND</p>
                  <!-- CART LINK -->
                  <a href="manage_cart.php?action=add&id=37" class="btn btn-cart border-0">
                    <i class="bi bi-basket"></i>
                  </a>

                  <!-- WISHLIST LINK -->
                  <a href="toggle_wishlist.php?id=37" class="btn btn-outline-danger border-0">
                    <i class="bi bi-heart"></i>
                  </a>
                </div>
              </div>

              <div class="col">
                <div class="book-card">
                  <img
                    src="images/the-architect-s-apprentice-elif-shafak-9780241970942.jpg"
                    alt=" 10" />
                  <p class="mt-3">The Architect's Apprentice</p>
                  <p class="text-muted">Elif Shafak</p>
                  <p class="price">38,500 TND</p>
                  <!-- CART LINK -->
                  <a href="manage_cart.php?action=add&id=38" class="btn btn-cart border-0">
                    <i class="bi bi-basket"></i>
                  </a>

                  <!-- WISHLIST LINK -->
                  <a href="toggle_wishlist.php?id=38" class="btn btn-outline-danger border-0">
                    <i class="bi bi-heart"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Controls -->
        <button
          class="carousel-control-prev"
          type="button"
          data-bs-target="#bookCarousel"
          data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>

        <button
          class="carousel-control-next"
          type="button"
          data-bs-target="#bookCarousel"
          data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>
    </div>
  </section>
  <!--top pick end -->
  <!--blog-->
  <section id="blogs" class="py-5">
    <div class="container">
      <div class="title text-center py-5">
        <h2 class="position-relative d-inline-block">Our Latest Blog</h2>
      </div>

      <div class="row g-4 justify-content-center">
        <div class="col-md-6 col-lg-4">
          <div class="card h-100">
            <img
              src="images/1meet.jpg"
              class="card-img-top"
              alt="Children's Science Lab" />
            <div class="card-body" style="background-color: #ffc0cb">
              <h5 class="card-title">Children’s Science Lab</h5>
              <p class="card-text">
                Hands-on science experiments for kids hosted by Professor Lina
                Youssef. Fun, safe, and educational!
              </p>

            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="card h-100">
            <img
              src="images/localauth.jpg"
              class="card-img-top"
              alt="Storytime with Local Authors" />
            <div class="card-body" style="background-color: #ffc0cb">
              <h5 class="card-title">Storytime with Local Authors</h5>
              <p class="card-text">
                Join us for an afternoon of storytelling with celebrated local
                authors. Perfect for families and book lovers alike!
              </p>

            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="card h-100">
            <img
              src="images/poetry-reading12.jpg"
              class="card-img-top"
              alt="Poetry & Coffee Nights" />
            <div class="card-body" style="background-color: #ffc0cb">
              <h5 class="card-title">Poetry & Coffee Nights</h5>
              <p class="card-text">
                Sip coffee while enjoying live poetry readings by emerging
                poets. Open mic sessions welcome!
              </p>

            </div>
          </div>
        </div>
      </div>
      <div class="text-center mt-5">
        <a href="blogs.php" class="btn btn-outline-dark px-5 py-2">
          View All Blogs

        </a>
      </div>

    </div>
    </div>
  </section>
  <!--blog end-->

  <!-- newsletter -->
  <section id="newsletter" class="py-5">
    <div class="container">
      <div
        class="d-flex flex-column align-items-center justify-content-center">
        <!-- section title -->
        <div class="title text-center pt-3 pb-5">
          <h2 class="position-relative d-inline-block ms-4">
            Newsletter Subscription
          </h2>
        </div>
        <!-- description paragraph -->
        <p class="text-center text-muted">
          Stay updated with the latest arrivals, exclusive offers, and special
          events at "La Fleur" bookstore. Subscribe to our newsletter to
          receive curated recommendations and be the first to know about our
          new collections and promotions.
        </p>
        <!-- email input and subscribe button -->
        <div class="input-group mb-3 mt-3">
          <input
            type="email"
            class="form-control"
            placeholder="Enter Your Email ...name@example.com" />

          <button class="btn btn-primary" type="submit">Subscribe</button>
        </div>
      </div>
    </div>
  </section>
  <!-- end of newsletter -->

  <!-- footer -->
  <footer class="bg-dark py-5 mt-auto">
    <div class="container">
      <div class="row text-white g-4">
        <div class="col-md-6 col-lg-3">
          <a
            class="text-uppercase text-decoration-none brand text-white"
            href="index.php">La Fleur</a>
          <p class="text-white-50 mt-3">
            "La Fleur" bookstore is renowned for its premium selection and the
            exceptional quality of its products, catering to every book
            lover's needs. Choosing "La Fleur" means opting for a refined
            shopping experience where quality and care are always the
            priority.
          </p>
        </div>

        <div class="col-md-6 col-lg-3">
          <h5 class="fw-light">Links</h5>
          <ul class="list-unstyled">
            <li class="my-3">
              <a href="index.php" class="text-white-50 text-decoration-none">
                <i class="fas fa-chevron-right me-1"></i> Home
              </a>
            </li>

            <li class="my-3">
              <a href="blogs.php" class="text-white-50 text-decoration-none">
                <i class="fas fa-chevron-right me-1"></i> Blogs
              </a>
            </li>
            <li class="my-3">
              <a
                href="about us.php"
                class="text-white-50 text-decoration-none">
                <i class="fas fa-chevron-right me-1"></i>
                About Us
              </a>
            </li>
          </ul>
        </div>

        <div class="col-md-6 col-lg-3">
          <h5 class="fw-light mb-3">Contact Us</h5>
          <div
            class="d-flex justify-content-start align-items-start my-2 text-white-50">
            <span class="me-3">
              <i class="fas fa-map-marked-alt"></i>
            </span>
            <span class="fw-light">
              Boulevard du 9 Avril 1938 Bab Souika. 1006 - Tunis
            </span>
          </div>
          <div
            class="d-flex justify-content-start align-items-start my-2 text-white-50">
            <span class="me-3">
              <i class="fas fa-envelope"></i>
            </span>
            <span class="fw-light"> LaFleur.support@gmail.com </span>
          </div>
          <div
            class="d-flex justify-content-start align-items-start my-2 text-white-50">
            <span class="me-3">
              <i class="fas fa-phone-alt"></i>
            </span>
            <span class="fw-light"> +216 58 962 004 </span>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <h5 class="fw-light mb-3">Follow Us</h5>
          <div>
            <ul class="list-unstyled d-flex">
              <!--bootstrap facebook icon-->
              <li>
                <a
                  href="https://www.facebook.com/Ceresbookshop.tunisie/ "
                  target="_blank"
                  class="text-white-50 text-decoration-none fs-4 me-4">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    fill="currentColor"
                    class="bi bi-facebook"
                    viewBox="0 0 16 16">
                    <path
                      d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951" />
                  </svg>
                </a>
              </li>
              <!--bootstrap twitter icon-->
              <li>
                <a
                  href="https://x.com/"
                  target="_blank"
                  class="text-white-50 text-decoration-none fs-4 me-4">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    fill="currentColor"
                    class="bi bi-twitter"
                    viewBox="0 0 15 15">
                    <path
                      d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334q.002-.211-.006-.422A6.7 6.7 0 0 0 16 3.542a6.7 6.7 0 0 1-1.889.518 3.3 3.3 0 0 0 1.447-1.817 6.5 6.5 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.32 9.32 0 0 1-6.767-3.429 3.29 3.29 0 0 0 1.018 4.382A3.3 3.3 0 0 1 .64 6.575v.045a3.29 3.29 0 0 0 2.632 3.218 3.2 3.2 0 0 1-.865.115 3 3 0 0 1-.614-.057 3.28 3.28 0 0 0 3.067 2.277A6.6 6.6 0 0 1 .78 13.58a6 6 0 0 1-.78-.045A9.34 9.34 0 0 0 5.026 15" />
                  </svg>
                </a>
              </li>
              <!--bootstrap ig icon-->
              <li>
                <a
                  href="https://www.instagram.com/ceresbookshop/"
                  target="_blank"
                  class="text-white-50 text-decoration-none fs-4 me-4">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    fill="currentColor"
                    class="bi bi-instagram"
                    viewBox="0 0 16 16">
                    <path
                      d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                  </svg>
                </a>
              </li>
              <!--linkedin icon bootstrap-->
              <li>
                <a
                  href="https://www.linkedin.com/company/ceresbookshop/"
                  target="_blank"
                  class="text-white-50 text-decoration-none fs-4 me-4">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    fill="currentColor"
                    class="bi bi-linkedin"
                    viewBox="0 0 16 16">
                    <path
                      d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z" />
                  </svg>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!--end of footer copyright section -->
    <div class="container text-center py-4 mt-5 text-white-50">
      <p class="mb-0">
        &copy; Copyright 2025 - LaFleur Website All RIGHTS RESERVED TO OUMAIMA
        BAHLOUL
      </p>
    </div>
  </footer>
  <!-- end of footer -->
  <!--modal start -->
  <!-- Modal -->
  <div
    class="modal fade"
    id="exampleModal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1
            class="modal-title fs-5"
            id="exampleModalLabel"
            style="text-align: center">
            MAKE AN ACCOUNT
          </h1>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="nom" class="form-label">Name</label>
              <input
                type="nom"
                class="form-control"
                id="nom"
                aria-describedby="nom"
                placeholder="Name" />
            </div>
            <div class="mb-3">
              <label for="Prenom" class="form-label">Last Name</label>
              <input
                type="prenom"
                class="form-control"
                id="prenom"
                aria-describedby="prenom"
                placeholder="Last Name" />
            </div>
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label">Email address</label>
              <input
                type="email"
                class="form-control"
                id="exampleFormControlInput1"
                placeholder="......@example.com" />
            </div>
            <div class="mb-4">
              <label for="exampleInputPassword1" class="form-label">Password</label>
              <input
                type="password"
                class="form-control"
                id="exampleInputPassword1"
                placeholder="Password" />
            </div>
            <div class="mb-3">
              <label for="exampleFormControlTextarea1" class="form-label">Message</label>
              <textarea
                class="form-control"
                id="exampleFormControlTextarea1"
                rows="3"
                placeholder="”Today a reader, tomorrow a leader.” Margaret Fuller"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Send</button>
          </form>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal">
            Close
          </button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  <!--modal end-->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.js"></script>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous">
  </script>

  <script src="js/script.js"></script>
  <script>
    // 1. Save scroll position before the page reloads
    window.onbeforeunload = function() {
      localStorage.setItem('scrollPos', window.scrollY);
    };

    // 2. Restore scroll position after the page loads
    window.onload = function() {
      if (localStorage.getItem('scrollPos')) {
        window.scrollTo(0, localStorage.getItem('scrollPos'));
        localStorage.removeItem('scrollPos'); // Clean up
      }
    };
  </script>
</body>

</html>