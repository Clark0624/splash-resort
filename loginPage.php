<?php
session_start();
$isEmailExist = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $conn = new mysqli("localhost", "root", "", "splash_island_data");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
      if (isset($_POST['email'])) {
   
    $email = $_POST["email"];
    $password = $_POST["password"];

    $isEmailAlreadyExist = "SELECT * FROM user_account WHERE email = ?";
    $stmt = $conn->prepare($isEmailAlreadyExist);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
         $_SESSION['email'] = $email;
         $_SESSION['full_name'] = $row['full_name'];
         $_SESSION['login'] = true;
         $conn->close();
      header("Location: Bookingpage.php");
    exit();
    } else {
      $isEmailExist = "incorrect email or password";
    }
  }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" href="./User/assets/logo.png" />
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
  </head>
<body>
    <main class=" w-100 m-auto justify-content-center align-items-center d-flex p-5" >
    <form action="" method="post" class="card p-5 align-middle d-flex flex-column gap-2  w-25 ">
        <!-- <img src="./User/assets/logo.png" alt=""> -->
        <img src="../assets/logo.png" alt="" class="w-25 h-50 align-middle d-flex justify-content-center text-center">

        <h1 class="h3 mb-3 fw-normal">Please Login</h1>
        <p><?php echo  $isEmailExist; ?></p>
        <div class="form-floating">
            <input type="email" name="email" id="floatingInput" class="form-control" placeholder="name@gmail.com">
            <label for="floatingInput">Email</label>
        </div>
        <div class="form-floating">
            <input type="password" name="password" id="floatinPassword" placeholder="Password" class="form-control">
            <label for="floatingPassword">Password</label>
        </div>
         <p>Don't have an account yet? <a href="signUpPage.php">Sign Up</a></p>
        <button class="btn btn-primary w-100 py-2" type="submit">Login</button>
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

