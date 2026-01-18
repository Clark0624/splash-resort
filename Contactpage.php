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
  <body class="d-flex flex-column min-vh-100">
    <!-- NAVPART -->
     <div class="container-fluid navDiv">
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

   <!-- FORM AREA -->
    <main class=" p-md-5 p-2 d-md-flex justify-content-center align-content-center text-center flex-grow-1 w-100 h-100">
      <div class=" w-100">
      <h1 class="text-center mt-md-5">GET IN TOUCH</h1>
      <p class="text-center">Have questions? We'd love to hear from you!</p>
    <div class="d-flex justify-content-center align-content-center text-center w-100">
  <ul class="d-flex flex-column text-start list-unstyled ps-0 gap-3 fs-5 ms-5">
    <li><b><ion-icon name="location" class="pt-2"></ion-icon>&nbsp Address: &nbsp</b>938 Aurora Boulevard, Cubao, Quezon City, Metro Manila, Philippines</li>
    <li><b><ion-icon name="call" class="pt-2"></ion-icon>&nbsp Phone: &nbsp</b>09126241974</li>
    <li><b><ion-icon name="mail" class="pt-2"></ion-icon>&nbsp Email: </b> splashResort@gamil.com</li>
    <li><b><ion-icon name="logo-whatsapp" class="pt-2"></ion-icon>&nbsp WhatsApp:</b> +63 912 624 1974 </li>
  </ul>
</div>

      </div>
      <div
        class="messageform d-flex justify-content-center align-content-center text-center mt-3 ms-auto  w-100"
      >
        <form action="Contactpage.php" method="post" class="d-flex card flex-column gap-2 h-ms-75 w-50 p-5 ms-auto">
          <div class="d-md-flex flex-md-row w-100 gap-2 d-flex flex-column column-gap-2">
           <div class="form-floating w-100 ">
            <input type="text" name="full_name" id="floatingInput" class="form-control" placeholder="Full Name">
            <label for="floatingInput">Full Name</label>
        </div>
          <div class="form-floating w-100 ">
            <input type="email" name="email" id="floatingInput" class="form-control" placeholder="Email">
            <label for="floatingInput">Email</label>
        </div>
          </div>
          <div class="form-floating w-100 ">
            <input
              type="text"
              oninput="this.value = this.value.replace(/[^0-9]/g, '')"
              placeholder="Phone Number"
              class="w-100 form-control"
              name="phone_num"
            />
            <label for="floatingInput">Phone Number</label>
        </div>
         <div class="form-floating w-100">
          <textarea
            name="message"
            id="messagearea"
            style="resize: none"
            placeholder="Your Message"
            class="pt-4 h-100 text-black form-control "
          ></textarea>
           <label for="floatingInput">Enter Message</label>
          </div>
          <input
            type="submit"
            value="SEND MESSAGE"
            class="w-sm-100 w-md-100 align-self-center p-2 rounded border-1  mt-4 bg-transparent "
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
            Splash Resort Circle
            <ion-icon name="chevron-down-outline" class="ps-2 pt-1"></ion-icon>
          </button>
          <div class="collapse" id="circleMobile">
            <ul class="list-unstyled ps-3 mb-0">
              <li>Programmer Overview</li>
              <li>Join Splash Resort Circle</li>
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
            About Splash Resort
            <ion-icon name="chevron-down-outline" class="ps-2 pt-1"></ion-icon>
          </button>
          <div class="collapse" id="aboutMobile">
            <ul class="list-unstyled ps-3 mb-0">
              <li>About Us</li>
              <li>Our Resorts Brands</li>
              <li>Splash Resort Centre</li>
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
          <h4>Splash Resort Circle</h4>
          <ul class="list-unstyled">
            <li>Programmer Overview</li>
            <li>Join Splash Resort Circle</li>
            <li>Account Overview</li>
            <li>FAQ</li>
            <li>Contact Us</li>
          </ul>
        </div>
        <div class="me-5 d-none d-md-block">
          <h4>About Splash Resort</h4>
          <ul class="list-unstyled">
            <li>About Us</li>
            <li>Our Resorts Brands</li>
            <li>Splash Resort Centre</li>
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
      © 2025 Splash Resort Co. All Rights Reserved. ICP license: 22007722
    </h5>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      crossorigin="anonymous"
    ></script>
    <script src="../../homepageScript.js"></script>
  </body>
</html>

<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "splash_island_data";

$full_name = $_POST["full_name"];
$email = $_POST["email"];
$phone_num = $_POST["phone_num"];
$date = date("Y-m-d");
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
