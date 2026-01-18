<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" href="../assets/logo.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Splash Resort</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Bitcount:wght@100..900&family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Edu+AU+VIC+WA+NT+Pre:wght@400..700&family=Lilita+One&family=Montserrat:wght@600&family=MuseoModerno:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="style.css" />
    <script
      type="module"
      src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"
    ></script>
    <script
      nomodule
      src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"
    ></script>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  

     
  </head>
  <body>
    
    <!-- NAVPART -->
     
    <div class="container-fluid navDiv sticky-top">
      <nav class="navbar navbar-expand-md">
        <a href="" class="navbar-brand overflow-hidden d-flex">
          <img src="../assets/logo.png" alt="" class="logo h-50" />
          <h3 class="ps-3 mt-3 fw-lighter">Splash Resort</h3>
        </a>
        <button
          class="navbar-toggler me-2"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#main-navigation"
        >
          <span class="navbar-toggler-icon burger-menu"></span>
        </button>
        <div class="collapse navbar-collapse pe-5" id="main-navigation">
          <ul class="navbar-nav ms-auto ps-5">
            <li class="nav-item nav-li">
              <a href="Homepage.php" class="nav-link">Home</a>
            </li>
            <li class="nav-item nav-li">
              <a href="Amenitiespage.php" class="nav-link">Amenities</a>
            </li>
            <li class="nav-item nav-li">
              <a href="Contactpage.php" class="nav-link">Contact</a>
            </li>
            <li class="nav-item nav-li">
              <a href="Bookingpage.php" class="nav-link">Book Now</a>
            </li>
            <li class="nav-item nav-li">
              <a href="Profilepage.php" class="nav-link d-flex align-items-center">
                <ion-icon name="person-circle-outline" class="fs-4"></ion-icon>
                <span class="d-lg-none ms-2">Profile</span>
              </a>
            </li>
          </ul>
        </div>
      </nav>
    </div>

    <!-- HERO SECTION -->
    <header class="">
      <div id="carouselExampleCaptions" class="carousel slide h-75 ">
        <div class="carousel-indicators">
          <button
            type="button"
            data-bs-target="#carouselExampleCaptions"
            data-bs-slide-to="0"
            aria-current="true"
            aria-label="Slide 1"
          ></button>
          <button
            type="button"
            data-bs-target="#carouselExampleCaptions"
            data-bs-slide-to="1"
            class="active"
            aria-label="Slide 2"
          ></button>
          <button
            type="button"
            data-bs-target="#carouselExampleCaptions"
            data-bs-slide-to="2"
            aria-label="Slide 3"
          ></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item ">
            <img src="../assets/headerBg.png" class="d-block w-100" alt="..." />
            <div class="carousel-caption" >
              <h5>Splash Resort Your Beachside Escape</h5>
              <p>
                Wake up to ocean views from our cozy rooms and private cottages.
                Relax by the infinity pool, stroll the sandy shore, and book
                your perfect stay today.
                <a href="Bookingpage.php">Reserve now</a>
              </p>
            </div>
          </div>
          <div class="carousel-item">
            <img
              src="../assets/TerracedGreenOverlook.webp"
              class="d-block w-100"
              alt="..."
            />
            <div class="carousel-caption">
              <h5>Rooms • Cottages • Pool • Beach View</h5>
              <p>
                Choose from seaside rooms or tucked-away cottages with stunning
                beach vistas. Enjoy a crystal-clear pool, shoreline sunsets, and
                easy online reservations for a hassle-free getaway.
              </p>
            </div>
          </div>
          <div class="carousel-item active">
            <img src="../assets/seaview.jpg" class="d-block w-100" alt="..." />
            <div class="carousel-caption">
              <h5>Book Your Beach Getaway</h5>
              <p>
                Rooms and cottages with pool access and breathtaking beach
                views. Limited slots
                <a href="Bookingpage.php">reserve your dates</a> now!
              </p>
            </div>
          </div>
        </div>
        <button
          class="carousel-control-prev"
          type="button"
          data-bs-target="#carouselExampleCaptions"
          data-bs-slide="prev"
        >
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden z-3">Previous</span>
        </button>
        <button
          class="carousel-control-next"
          type="button"
          data-bs-target="#carouselExampleCaptions"
          data-bs-slide="next"
        >
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden z-3">Next</span>
        </button>
      </div>
    </header>
    <!-- MAIN PART -->
    <main class=" pb-3">
      <h2 class="text-center fw-light mt-3">Amenities</h2>
       <div class="restaurantSection row gap-5 g-3 g-lg-5 p-2 p-lg-5 p-4">
        <section
          class="restaurantDesctription order-2 order-md-1 col-12 col-md-4 mb-5 pt-md-3 ps-md-5"
        >
           <h4>Relaxing Spa</h4>
    <p>
      Unwind and rejuvenate at our serene spa with soothing massages,
      aromatherapy, and wellness treatments designed to melt your stress away.
    </p>
        </section>
        <img
          src="../assets/Spa.png"
          alt=""
          class="order-1 order-md-1 col-12 col-md-4 h-md-100 h-sm-25 ms-md-5"
        />
      </div>
    
      <div class="restaurantSection row gap-5 g-3 g-lg-5 p-2 p-lg-5 p-4">
        <img
          src="../assets/pool.png"
          alt=""
          class="order-1 order-md-1 col-12 col-md-4 h-md-100 h-sm-25 ms-md-5"
        />
        <section
          class="restaurantDesctription order-2 order-md-1 col-12 col-md-4 mb-5 ps-md-5 ps-sm-0"
        >
          <h4>Crystal-Clear Swimming Pool</h4>
          <p>
            Take a refreshing dip in our sparkling pool, perfect for family fun
            or a relaxing swim after a day at the beach.
          </p>
        </section>
      </div>
      <div class="p-5">
        <img src="../assets/headerBg.png" alt="" class="w-100 " style="height: 70vh; object-fit:cover;">
      </div>
      
       <div class="restaurantSection row gap-5 g-3 g-lg-5 p-2 p-lg-5 p-4">
        <section
          class="restaurantDesctription order-2 order-md-1 col-12 col-md-4 mb-5 pt-md-3 ps-md-5"
        >
          <h4>Exciting Billiards Lounge</h4>
    <p>
      Challenge your friends in our modern billiards area, featuring top-quality
      tables and a relaxing atmosphere perfect for friendly matches.
    </p>
        </section>
        <img
          src="../assets/Billiard.png"
          alt=""
          class="order-1 order-md-1 col-12 col-md-4 h-md-100 h-sm-25 ms-md-5"
        />
      </div>

      <div class="restaurantSection row gap-5 g-3 g-lg-5 p-2 p-lg-5 p-4">
        <img
          src="../assets/Sauna.png"
          alt=""
          class="order-1 order-md-1 col-12 col-md-4 h-md-100 h-sm-25 ms-md-5"
        />
        <section
          class="restaurantDesctription order-2 order-md-1 col-12 col-md-4 mb-5 ps-md-5 ps-sm-0"
        >
           <h4>Rejuvenating Sauna</h4>
    <p>
      Experience deep relaxation and detoxification in our sauna, the perfect
      way to soothe your muscles and refresh your body after a long day.
    </p>
        </section>
      </div>
    </main>
    <!-- FOOTER PART -->

   
    <footer class="p-3" >
      <div class="info d-flex gap-md-5 justify-content-center p-md-5 flex-wrap">
        <img src="../assets/logo.png" alt="" class="align-self-center" />
        <div class="d-block d-md-none mb-3 w-100">
          <button
            class="btn btn-none w-100 text-start text-white"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#findBookMobile"
          >
            Find & Book
            <ion-icon name="chevron-down-outline" class="ps-2 pt-1"></ion-icon>
          </button>
          <div class="collapse" id="findBookMobile">
            <ul class="list-unstyled ps-3 mb-0">
              <li>Our Destinations</li>
              <li>Find a Reservation</li>
              <li>Meeting & Events</li>
              <li>Restaurant's</li>
            </ul>
          </div>
        </div>
        <div class="d-block d-md-none mb-3 w-100">
          <button
            class="btn btn-none w-100 text-start text-white"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#circleMobile"
          >
            Splash Island Circle
            <ion-icon name="chevron-down-outline" class="ps-2 pt-1"></ion-icon>
          </button>
          <div class="collapse" id="circleMobile">
            <ul class="list-unstyled ps-3 mb-0">
              <li>Programmer Overview</li>
              <li>Join Splash Island Circle</li>
              <li>Account Overview</li>
              <li>FAQ</li>
              <li>Contact Us</li>
            </ul>
          </div>
        </div>
        <div class="d-block d-md-none mb-3 w-100">
          <button
            class="btn btn-none w-100 text-start text-white"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#aboutMobile"
          >
            About Splash Island
            <ion-icon name="chevron-down-outline" class="ps-2 pt-1"></ion-icon>
          </button>
          <div class="collapse" id="aboutMobile">
            <ul class="list-unstyled ps-3 mb-0">
              <li>About Us</li>
              <li>Our Resorts Brands</li>
              <li>Splash Island Centre</li>
              <li>Residences</li>
              <li>Contact Us</li>
            </ul>
          </div>
        </div>
        <div class="me-5 d-none d-md-block">
          <h4>Find & Book</h4>
          <ul class="list-unstyled">
            <li>Our Destinations</li>
            <li>Find a Reservation</li>
            <li>Meeting & Events</li>
            <li>Restaurant's</li>
          </ul>
        </div>
        <div class="me-5 d-none d-md-block">
          <h4>Splash Island Circle</h4>
          <ul class="list-unstyled">
            <li>Programmer Overview</li>
            <li>Join Splash Island Circle</li>
            <li>Account Overview</li>
            <li>FAQ</li>
            <li>Contact Us</li>
          </ul>
        </div>
        <div class="me-5 d-none d-md-block">
          <h4>About Splash Island</h4>
          <ul class="list-unstyled">
            <li>About Us</li>
            <li>Our Resorts Brands</li>
            <li>Splash Island Centre</li>
            <li>Residences</li>
            <li>Contact Us</li>
          </ul>
        </div>
      </div>
    </footer>
    <h5
      class="text-center fw-light w-100 p-3 "
      style="font-size: 14px"
    >
      Privacy Policy | Terms & Conditions | Safety & Security | Supplier Code of
      Conduct | Cyber Security <br />
      © 2025 Splash Island Co. All Rights Reserved. ICP license: 22007722
    </h5>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
