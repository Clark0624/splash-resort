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
    <!-- NAVPART ASDASD-->
    <div class="container-fluid navDiv fixed-top">
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
   <header id="homepageHeader" >
  <video autoplay muted loop playsinline id="bgVideo">
    <source src="../assets/Hotel.mp4" type="video/mp4">
  </video>
  <div class="header-content mt-5 ">
    <h1>Welcome to Splash Resort</h1>
    <p>Enjoy paradise by the sea</p>
  </div>
</header>

    <!-- MAIN PART -->
    <main class="pb-3 main">
      <h3 class="text-center pt-3 fw-lighter">Amenities</h3>

      <div class="roomsContainer">
        <h2 class="ms-5 fw-light">Rooms</h2>
        <div
          class="card-container p-5 d-md-flex gap-2 d-grid mb-sm-5 p-3 overflow-x-scroll"
        >
          <div class="card card-body overflow-hidden border-secondary">
            <img
              src="../assets/DeluxeWarmEarthSuite.jpg"
              alt="Deluxe Warm Earth Suite"
            />
            <h4 class="pt-3">Deluxe Warm Earth Suite</h4>
            <p>
              Deluxe Warm Earth Suite offers a comfortable and practical stay.
              The room features a plush king-size bed, a cozy sitting area, and
              essential amenities, providing a relaxing and convenient
              environment.
            </p>
          </div>
          <div class="card card-body overflow-hidden border-secondary">
            <img
              src="../assets/PrimaryTaupeSanctuary.jpg"
              alt="Primary Taupe Sanctuary"
            />
            <h4 class="pt-3">Primary Taupe Sanctuary</h4>
            <p>
              Primary Taupe Sanctuary offers a warm and inviting experience. The
              room features a plush king-size bed, a comfortable chaise lounge,
              and elegant decor, creating a relaxing and sophisticated ambiance.
            </p>
          </div>
          <div class="card card-body overflow-hidden border-secondary">
            <img
              src="../assets/PrimaryUrbanQuarters.jpg"
              alt="Primary Urban Quarters"
            />
            <h4 class="pt-3">Primary Urban Quarters</h4>
            <p>
              Primary Urban Quarters offers a relaxing and refined experience.
              The room features a plush king-size bed, comfortable armchairs,
              and elegant decor, creating a tranquil and sophisticated ambiance.
            </p>
          </div>
          <div class="card card-body overflow-hidden border-secondary">
            <img
              src="../assets/SignatureGrandKing.jpg"
              alt="Signature Grand King"
            />
            <h4 class="pt-3">Signature Grand King</h4>
            <p>
              Signature Grand King offers a luxurious urban retreat with
              sophisticated decor. The room features a plush king-size bed, a
              comfortable seating area, and elegant furnishings, creating a
              stylish and relaxing ambiance.
            </p>
          </div>
          <div class="card card-body overflow-hidden border-secondary">
            <img
              src="../assets/SignatureTropicalDesign.jpg"
              alt="Signature Tropical Design"
            />
            <h4 class="pt-3">Exotic Haven</h4>
            <p>
              Exotic Haven Resort offers a luxurious, culturally rich
              experience. The room features a comfortable king-size bed, elegant
              wall art, and native-inspired accents, creating a tranquil and
              sophisticated ambiance.
            </p>
          </div>
        </div>
      </div>

      <!-- Cottages -->
      <div class="cottagesContainer">
        <h2 class="ms-5 fw-light text-end pe-5">Cottages</h2>
        <div
          class="card-container d-md-flex gap-2 d-grid mb-sm-5 p-5 overflow-x-scroll"
        >
          <div class="card card-body overflow-hidden border-secondary">
            <img src="../assets/BambooBeachVilla.jpg" alt="BAMBOOVILLA" />
            <h4 class="pt-3">Bamboo Beach Villa</h4>
            <p>
              Bamboo Beach Villa offers unparalleled luxury with meticulously
              designed suites featuring breathtaking views. Each suite
              comfortably accommodates up to four people, boasting a king-size
              bed, a sophisticated living area, and contemporary bathrooms with
              premium fixtures like a walk-in shower and soaking tub. Guests
              enjoy a private balcony, a fully equipped kitchen, and
              personalized concierge services.
            </p>
          </div>
          <div class="card card-body overflow-hidden border-secondary">
            <img src="../assets/CanopyLagoonSuite.jpg" alt="CANOPYSUIT" />
            <h4 class="pt-3">Canopy Lagoon Suite</h4>
            <p>
              offers a luxurious beachfront escape with stunning ocean views.
              The villa features a four-poster bed draped in elegant white
              curtains, a comfortable seating area, and direct access to a
              private balcony overlooking the turquoise waters. The spacious
              interior is complemented by modern amenities and a serene
              ambiance, perfect for a relaxing getaway.
            </p>
          </div>
          <div class="card card-body overflow-hidden border-secondary">
            <img src="../assets/DeluxeOceanViewRoom.jpg" alt="OCEANROOM" />
            <h4 class="pt-3">Deluxe Ocean View Room</h4>
            <p>
              Deluxe Ocean View Room offers a serene and luxurious escape with
              stunning ocean views. The room features a comfortable king-size
              bed, a cozy seating area, and a private balcony overlooking the
              turquoise waters. The interior is elegantly designed with warm
              wood accents and modern amenities, creating a perfect blend of
              comfort and sophistication for an unforgettable getaway.
            </p>
          </div>
          <div class="card card-body overflow-hidden border-secondary">
            <img src="../assets/OceanfrontOverwaterHaven.jpg" alt="OCEAN" />
            <h4 class="pt-3">Oceanfront Overwater Haven</h4>
            <p>
              Oceanfront Overwater Haven offers a luxurious overwater retreat
              with breathtaking sunset views. The villa features a plush
              king-size bed, a cozy daybed with decorative pillows, and a
              private deck with lounge chairs overlooking the ocean. The warm
              wooden interiors and elegant decor create a serene and
              sophisticated ambiance for an unforgettable stay.
            </p>
          </div>
        </div>
      </div>
      <h2 class="text-center fw-light">Restaurant</h2>
      <div class="restaurantSection row gap-5 g-3 g-lg-5 p-2 p-lg-5 p-4">
        <section
          class="restaurantDesctription order-2 order-md-1 col-12 col-md-4 mb-5 pt-md-3 ps-md-5"
        >
          <h4>Fresh Flavors of the Sea</h4>
          <p>
            Experience signature seafood cuisine at Splash Resort, where every
            dish is crafted with the flavors of the ocean and a view of the
            waves.
          </p>
          <a href="Amenitiespage.php">
          <button class="w-50 p-3 border-1 border-white">Learn More</button>
            </a>
        </section>
        <img
          src="../assets/restaurant.png"
          alt=""
          class="order-1 order-md-1 col-12 col-md-4 h-md-100 h-sm-25 ms-md-5"
        />
      </div>
      <h2 class="text-center fw-light">Swimming Pool</h2>
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
           <a href="Amenitiespage.php">
          <button class="w-50 p-3 border-1 border-white">Learn More</button>
            </a>
        </section>
      </div>
      <h1 class="text-center mt-md-5">GET IN TOUCH</h1>
      <p class="text-center">Have questions? We'd love to hear from you!</p>
      <div
        class="messageform d-flex justify-content-center align-content-center text-center mt-3 p-md-5"
      >
        <form
          action="Homepage.php"
          method="post"
          class="d-md-flex flex-column d-sm-block gap-3 w-50"
        >
          <div class="d-md-flex flex-md-row w-100 gap-2">
            <div class="form-floating w-100">
              <input
                type="text"
                name="full_name"
                id="floatingInput"
                class="form-control"
                placeholder="Full Name"
              />
              <label for="floatingInput">Full Name</label>
            </div>
            <div class="form-floating w-100">
              <input
                type="email"
                name="email"
                id="floatingInputEmail"
                class="form-control"
                placeholder="Email"
              />
              <label for="floatingInputEmail">Email</label>
            </div>
          </div>
          <div class="form-floating w-100">
            <input
              type="text"
              oninput="this.value = this.value.replace(/[^0-9]/g, '')"
              placeholder="Phone Number"
              class="w-100 form-control"
              name="phone_num"
              id="floatingInputPhoneNum"
            />
            <label for="floatingInputPhoneNum">Phone Number</label>
          </div>
          <div class="form-floating w-100">
            <textarea
              name="message"
              id="messagearea"
              style="resize: none"
              placeholder="Your Message"
              class="pt-4 h-100 text-black form-control"
            ></textarea>
            <label for="messagearea">Enter Message</label>
          </div>
          <input
            type="submit"
            value="SEND MESSAGE"
            class="w-sm-50 w-md-25 align-self-center p-2 rounded bg-transparent"
          />
        </form>
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
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "splash_island_data";

$full_name = $_POST["full_name"];
$email = $_POST["email"];
$phone_num = $_POST["phone_num"];
$date = date('Y-m-d H:i:s');
$message = $_POST["message"];

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO `user_inquiries`(`full_name`, `email`, `phone_number`, `date`, `message`) 
        VALUES ('$full_name', '$email', '$phone_num', '$date', '$message')";


$result = $conn->query($sql);

$conn->close();
}
?> 
