<?php
  $isEmailExist = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  session_start();
    $conn = new mysqli("localhost", "root", "", "splash_island_data");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $Userpassword = $_POST["password"];
    $phone_num = $_POST["phone_num"];
    $reservation = null; // No reservation yet
  
    // Use NULL properly in SQL
    $reservation_value = $reservation === null ? "NULL" : "'$reservation'";
    if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // check if email exists
    $isEmailAlreadyExist = "SELECT * FROM user_account WHERE email = ?";
    $stmt = $conn->prepare($isEmailAlreadyExist);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $isEmailExist = "Email already exists";
    } else {
       $sql = "INSERT INTO user_account (full_name, email, password, phone_number, reservation)
            VALUES ('$full_name', '$email', '$Userpassword', '$phone_num', $reservation_value)";
    $result = $conn->query($sql);
     $_SESSION['email'] = $email;
    $_SESSION['login'] = true;
    $conn->close();
      header("Location: Bookingpage.php");
    exit();

    }
}
  
   
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" href="../assets/logo.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Splash Island</title>
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

        <main class="w-100 m-auto justify-content-center align-items-center d-flex p-md-5 p-2 pt-5">
    <form action="signUpPage.php" method="post" class="card p-md-5 p-2 align-middle d-flex flex-column gap-2 w-50  w-sm-100  ">
        <!-- <img src="./User/assets/logo.png" alt=""> -->
        <h1 class="h3 mb-3 fw-normal">Please Sign In</h1>
        <h2><?php echo $isEmailExist;?></h2>
        <div class="form-floating">
            <input type="email" name="email" id="floatingInput" class="form-control" placeholder="name@gmail.com">
            <label for="floatingInput">Email</label>
        </div>
        <div class="form-floating">
            <input type="password" name="password" id="floatinPassword" placeholder="Password" class="form-control">
            <label for="floatinPassword">Password</label>
        </div>
          <div class="form-floating">
            <input type="text" name="full_name" id="floatinName" placeholder="Password" class="form-control">
            <label for="floatinName">Full Name</label>
        </div>
          <div class="form-floating">
            <input type="text" name="phone_num" id="floatinNumber" placeholder="Password" class="form-control">
            <label for="floatinNumber">Phone Number</label>
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit">Create Account</button>
        <p>Already Have an account? <a href="loginPage.php">Log in</a></p>
        <p class="mt-5 mb-3 text-body-secondary text-center">© 2017-2025</p>
    </form>
</main>

    
   <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      crossorigin="anonymous"
    ></script>
  
  </body>
</html>


