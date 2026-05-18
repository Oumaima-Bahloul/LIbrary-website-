<?php include '../db.php'; ?>
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
  <link rel="icon" type="image/png" href="../images/icon de site.png" />
  <!--icon in browser tab-->

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
  <!--font awesome : cons library (cart, heart, stars)-->

  <link rel="stylesheet" href="../style/style.css" />
  <!--the  custom style css file : has the styling stuff -->

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    rel="stylesheet" />
  <!--bootstrap link for icons-->

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous" />
  <!--bootstrap link for layout, grid system, components , cards , carousel ...-->
</head>

<body>
  <?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  include_once '../api/db.php';

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
  <!--nav bar start-->
  <!-- Fixed navigation bar at the top -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white py-4 fixed-top">
    <div class="container">
      <!-- Brand logo and website name -->
      <a
        class="navbar-brand d-flex align-items-center order-lg-0"
        href="../index.php"
        target="_blank">
        <img src="../images/icon de site.png" alt="site icon" />
        <span class="fw-lighter ms-2" href="../index.php" target="_blank">La Fleur</span>
      </a>

      <!-- Icons on the right (cart, favorites, search) -->
      <!----dynamic cart -->
      <div class="order-lg-2 nav-btns">
        <a href="../cart.php" class="btn position-relative border-0" style="color: inherit;">
          <i class="fa fa-shopping-cart"></i>
          <span id="cart-badge"
            class="position-absolute top-0 start-100 translate-middle badge bg-primary"><?php echo $cart_count; ?></span>
        </a>
        <!--dynamic wishlist -->
        <a href="../wishlist.php" class="btn position-relative border-0" style="color: inherit;">
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
                  <a class="dropdown-item py-2" href="../profile.php">
                    <i class="bi bi-person-circle me-2"></i> View Profile
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-2" href="../my-orders.php">
                    <i class="bi bi-bag-check me-2"></i> My Orders
                  </a>
                </li>
              <?php endif; ?>

              <li>
                <hr class="dropdown-divider">
              </li>

              <li>
                <a class="dropdown-item py-2 text-danger" href="../logout.php">
                  <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
              </li>
            </ul>
          </div>
        <?php else: ?>
          <a href="../login.php" class="btn border-0 shadow-none" title="Login/Register">
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
              href="../index.php"
              target="_blank">home</a>
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
              <li><a class="dropdown-item" href="#">Books</a></li>
              <li><a class="dropdown-item" href="ebooks.php">EBooks</a></li>
              <li>
                <a class="dropdown-item" href="trending.php">Trending</a>
              </li>
              <li>
                <a class="dropdown-item" href="magazines.php">Magazines</a>
              </li>
              <li>
                <a class="dropdown-item" href="supplies&accessories.php">Supplies & Accessories</a>
              </li>
              <li>
                <a class="dropdown-item" href="stationary.php">Stationery</a>
              </li>
              <li>
                <a class="dropdown-item" href="educational kits.php">Educational Kits</a>
              </li>
              <li>
                <a class="dropdown-item" href="gifts & merch.php">Gifts & Merch</a>
              </li>
            </ul>
          </li>
          <li class="nav-item px-2 py-2">
            <a class="nav-link text-uppercase text-dark" href="../blogs.php">blogs</a>
          </li>
          <li class="nav-item px-2 py-2">
            <a
              class="nav-link text-uppercase text-dark"
              href="../about us.php">about us</a>
          </li>
          <li class="nav-item px-2 py-2">
            <a
              class="nav-link text-uppercase text-dark"
              href="../popular.php">popular</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!--nav bar end-->

  <!--serach button-->

  <section id="search" class="py-5">
    <div class="container catalogue-container">
      <div
        class="d-flex flex-column align-items-center justify-content-center">
        <!-- email input and subscribe button -->
        <div class="input-group mb-3 mt-3">
          <input
            type="email"
            class="form-control"
            placeholder="Search For Your Favorite Book...." />

          <button class="btn btn-primary" type="submit">Search</button>
        </div>
      </div>
    </div>
  </section>
  <!-- Dropdown -->

  <section class="container my-4">
    <div class="d-flex justify-content-end align-items-center gap-3">
      <!-- Text -->
      <span class="text-muted">Results sorted by : </span>
      <select class="selection" aria-label="Default select example">
        <option selected class="text-muted text-white-50">
          Select Your Option
        </option>
        <option value="1">Price, descending</option>
        <option value="2">Price, ascending</option>
        <option value="3">Relevance</option>
        <option value="4">Name, Z → A</option>
        <option value="5">Name, A → Z</option>
        <option value="6">Best sellers</option>
        <option value="7">Newest first</option>
      </select>
      <!-- Filter Button -->
      <button class="btn btn-outline-dark">
        <i class="bi bi-funnel"></i> Filter
      </button>
    </div>
  </section>
  <!--rows-->
  <section class="py-5">
    <div class="container">
      <!-- Row 1 -->
      <div class="row g-4 mb-4">
        <div class="col-md-3">
          <!-- Book card -->
          <div class="card h-100">
            <img
              src="pics/macbeth-conrad-mason-young-reading-series-2.jpg"
              class="card-img-top"
              alt="Book 1" />
            <div class="card-body text-center">
              <h6 class="card-title">
                Macbeth - Conrad Mason - Young Reading Series 2
              </h6>
              <p class="text-muted">Shakespeare</p>
              <p class="text-muted">20,000 TND</p>
              <!-- CART LINK -->
              <a href="../manage_cart.php?action=add&id=39" class="btn btn-cart border-0">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="../toggle_wishlist.php?id=39" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100">
            <img
              src="pics/the-lion-king-disney-movies.jpg"
              class="card-img-top"
              alt="Book 2" />
            <div class="card-body text-center">
              <h6 class="card-title">The Lion King</h6>
              <p class="text-muted">Disney MOVIES</p>
              <p class="text-muted">34,300 TND</p>
              <!-- CART LINK -->
              <a href="../manage_cart.php?action=add&id=40" class="btn btn-cart border-0">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="../toggle_wishlist.php?id=40" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100">
            <img
              src="pics/oxford-wordpower-dictionary-4th-edition.jpg"
              class="card-img-top"
              alt="Book 3" />
            <div class="card-body text-center">
              <h6 class="card-title">
                Oxford Wordpower Dictionary 4th edition
              </h6>
              <p class="text-muted">the 4th edition</p>
              <p class="text-muted">36,000 TND</p>
              <!-- CART LINK -->
              <a href="../manage_cart.php?action=add&id=41" class="btn btn-cart border-0">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="../toggle_wishlist.php?id=41" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100">
            <img
              src="pics/français-et-maths-ce2-nouveau-programme-cahier-de-soutien-avec-des-videos.jpg"
              class="card-img-top"
              alt="Book 4" />
            <div class="card-body text-center">
              <h6 class="card-title">
                Français et Maths CE2 - Nouveau programme - Cahier de soutien
                avec des vidéos
              </h6>
              <p class="text-muted">Maître Lucas</p>
              <p class="text-muted">31,500 TND</p>
              <!-- CART LINK -->
              <a href="../manage_cart.php?action=add&id=42" class="btn btn-cart border-0">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="../toggle_wishlist.php?id=42" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
              </button>
            </div>
          </div>
        </div>
      </div>
      <!--row2 -->
      <div class="row g-4 mb-4">
        <div class="col-md-3">
          <!-- Book card -->
          <div class="card h-100">
            <img
              src="pics/جبران-خليل-جبران-الأعمال-الكاملة.jpg"
              class="card-img-top"
              alt="Book 1" />
            <div class="card-body text-center">
              <h6 class="card-title">الأعمال الكاملة</h6>
              <p class="text-muted">جبران خليل جبران</p>
              <p class="text-muted">89,000 TND</p>
              <!-- CART LINK -->
              <a href="../manage_cart.php?action=add&id=43" class="btn btn-cart border-0">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="../toggle_wishlist.php?id=43" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100">
            <img
              src="pics/anna-karenina-leo-tolstoy.jpg"
              class="card-img-top"
              alt="Book 2" />
            <div class="card-body text-center">
              <h6 class="card-title">Anna Karenina</h6>
              <p class="text-muted">Leo Tolstoy</p>
              <p class="text-muted">17,500 TND</p>
              <!-- CART LINK -->
              <a href="../manage_cart.php?action=add&id=44" class="btn btn-cart border-0">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="../toggle_wishlist.php?id=44" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100">
            <img
              src="pics/grimm-s-fairy-tales.jpg"
              class="card-img-top"
              alt="Book 3" />
            <div class="card-body text-center">
              <h6 class="card-title">Grimm's Fairy Tales</h6>
              <p class="text-muted">the Brothers Grimm</p>
              <p class="text-muted">31,720 TND</p>
              <!-- CART LINK -->
              <a href="../manage_cart.php?action=add&id=45" class="btn btn-cart border-0">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="../toggle_wishlist.php?id=45" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100">
            <img
              src="pics/the-essential-kafka-franz-kafka.jpg"
              class="card-img-top"
              alt="Book 4" />
            <div class="card-body text-center">
              <h6 class="card-title">The Essential Kafka</h6>
              <p class="text-muted">Franz Kafka</p>
              <p class="text-muted">17,500 TND</p>
              <!-- CART LINK -->
              <a href="../manage_cart.php?action=add&id=46" class="btn btn-cart border-0">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="../toggle_wishlist.php?id=46" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      <!--row3 -->
      <div class="row g-4 mb-4">
        <div class="col-md-3">
          <!-- Book card -->
          <div class="card h-100">
            <img
              src="pics/امرأة-مثلها-مارك-ليفي-9786144693391.jpg"
              class="card-img-top"
              alt="Book 1" />
            <div class="card-body text-center">
              <h6 class="card-title">امرأة مثلها</h6>
              <p class="text-muted">مارك ليفي</p>
              <p class="text-muted">46,500 TND</p>
              <!-- CART LINK -->
              <a href="../manage_cart.php?action=add&id=47" class="btn btn-cart border-0">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="../toggle_wishlist.php?id=47" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100">
            <img
              src="pics/alice-s-adventures-in-wonderland-lewis-carroll.jpg"
              class="card-img-top"
              alt="Book 2" />
            <div class="card-body text-center">
              <h6 class="card-title">Alice's Adventures in Wonderland</h6>
              <p class="text-muted">Lewis Carroll</p>
              <p class="text-muted">17,500 TND</p>
              <!-- CART LINK -->
              <a href="../manage_cart.php?action=add&id=48" class="btn btn-cart border-0">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="../toggle_wishlist.php?id=48" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100">
            <img
              src="pics/the-scarlet-letter-nathaniel-hawthorne (1).jpg"
              class="card-img-top"
              alt="Book 3" />
            <div class="card-body text-center">
              <h6 class="card-title">The Scarlet Letter</h6>
              <p class="text-muted">Nathaniel Hawthorne</p>
              <p class="text-muted">17,500 TND</p>
              <!-- CART LINK -->
              <a href="../manage_cart.php?action=add&id=49" class="btn btn-cart border-0">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="../toggle_wishlist.php?id=49" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100">
            <img
              src="pics/harry-potter-the-deathly-hallows-harry-potter-9781408894743.jpg"
              class="card-img-top"
              alt="Book 4" />
            <div class="card-body text-center">
              <h6 class="card-title">Harry Potter & the Deathly Hallows</h6>
              <p class="text-muted">J.K. Rowling</p>
              <p class="text-muted">39,600 TND</p>
              <!-- CART LINK -->
              <a href="../manage_cart.php?action=add&id=50" class="btn btn-cart border-0">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="../toggle_wishlist.php?id=50" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      <!--row4 -->
      <div class="row g-4 mb-4">
        <div class="col-md-3">
          <!-- Book card -->
          <div class="card h-100">
            <img
              src="pics/hamlet-william-shakespeare.jpg"
              class="card-img-top"
              alt="Book 1" />
            <div class="card-body text-center">
              <h6 class="card-title">Hamlet</h6>
              <p class="text-muted">William Shakespeare</p>
              <p class="text-muted">12.00 TND</p>
              <!-- CART LINK -->
              <a href="../manage_cart.php?action=add&id=51" class="btn btn-cart border-0">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="../toggle_wishlist.php?id=51" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100">
            <img
              src="pics/bonjour-tristesse-françoise-sagan.jpg"
              class="card-img-top"
              alt="Book 2" />
            <div class="card-body text-center">
              <h6 class="card-title">Bonjour tristesse</h6>
              <p class="text-muted">Françoise Sagan</p>
              <p class="text-muted">22,150 TND</p>
              <!-- CART LINK -->
              <a href="../manage_cart.php?action=add&id=52" class="btn btn-cart border-0">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="../toggle_wishlist.php?id=52" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100">
            <img
              src="pics/jujutsu-kaisen-tome-15-le-drame-de-shibuya-transformation.jpg"
              class="card-img-top"
              alt="Book 3" />
            <div class="card-body text-center">
              <h6 class="card-title">
                Jujutsu Kaisen Tome 15 - Le drame de Shibuya : transformation
              </h6>
              <p class="text-muted">Gege Akutami</p>
              <p class="text-muted">28,500 TND</p>
              <!-- CART LINK -->
              <a href="../manage_cart.php?action=add&id=53" class="btn btn-cart border-0">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="../toggle_wishlist.php?id=53" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100">
            <img
              src="pics/jujutsu-kaisen-tome-18-la-passion-pack-avec-un-extrait-gratuit-de-valhallian-the-black-iron.jpg"
              class="card-img-top"
              alt="Book 4" />
            <div class="card-body text-center">
              <h6 class="card-title">
                Jujutsu Kaisen Tome 18 - La passion - Pack avec un extrait
                gratuit de Valhallian the Black Iron
              </h6>
              <p class="text-muted">Gege Akutami</p>
              <p class="text-muted">28,500 TND</p>
              <!-- CART LINK -->
              <a href="../manage_cart.php?action=add&id=54" class="btn btn-cart border-0">
                <i class="bi bi-basket"></i>
              </a>

              <!-- WISHLIST LINK -->
              <a href="../toggle_wishlist.php?id=54" class="btn btn-outline-danger border-0">
                <i class="bi bi-heart"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      <!--end of rows -->
    </div>
  </section>

  <!--end rows-->

  <!--spinner :
    <div class="d-flex justify-content-center">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>-->

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
            <img src="../images/books.webp" class="w-100" />
          </div>
          <!-- Category name and button -->
          <div class="text-center">
            <p class="text-capitalize mt-3 mb-1">BOOKS</p>
          </div>
        </div>
        <!-- Category card: Stationery -->
        <div class="col-md-6 col-lg-4 col-xl-3 p-2">
          <div class="special-img position-relative overflow-hidden">
            <img src="../images/stationery.webp" class="w-100" />
          </div>
          <div class="text-center">
            <p class="text-capitalize mt-3 mb-1">Stationery</p>
          </div>
        </div>
        <!-- Category card: Art -->
        <div class="col-md-6 col-lg-4 col-xl-3 p-2">
          <div class="special-img position-relative overflow-hidden">
            <img src="../images/beaux_art.webp" class="w-100" />
          </div>
          <div class="text-center">
            <p class="text-capitalize mt-3 mb-1">Art</p>
          </div>
        </div>
        <!-- Category card: Dictionaries -->
        <div class="col-md-6 col-lg-4 col-xl-3 p-2">
          <div class="special-img position-relative overflow-hidden">
            <img src="../images/dicitionaries.webp" class="w-100" />
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
            <a class="nav-link text-end" href="#" dir="rtl">كتب من المشرق العربي</a>
            <a class="nav-link" href="#">Books in English</a>
            <a class="nav-link fw-bold" href="#">+ More Categories</a>
          </nav>
        </div>
      </div>
    </div>
  </section>
  <!-- end of special products -->

  <!-- footer -->
  <footer class="bg-dark py-5">
    <div class="container">
      <div class="row text-white g-4">
        <div class="col-md-6 col-lg-3">
          <a
            class="text-uppercase text-decoration-none brand text-white"
            href="../index.php"
            target="_blank">La Fleur</a>
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
              <a
                href="../index.php"
                class="text-white-50 text-decoration-none"
                href="../index.php"
                target="_blank">
                <i
                  class="fas fa-chevron-right me-1"
                  href="../index.php"
                  target="_blank"></i>
                Home
              </a>
            </li>

            <li class="my-3">
              <a href="../blogs.php" class="text-white-50 text-decoration-none">
                <i class="fas fa-chevron-right me-1"></i> Blogs
              </a>
            </li>
            <li class="my-3">
              <a href="../about us.php" class="text-white-50 text-decoration-none">
                <i class="fas fa-chevron-right me-1"></i> About Us
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
  <!--jquery library-->
  <script src="../js/jquery-3.7.1.js"></script>
  <!--bootstrap link-->

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>

  <!--isotope js-->
  <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.js"></script>
  <!-- custom js -->

  <script src="../js/script.js"></script>
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