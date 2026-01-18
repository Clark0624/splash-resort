<?php
session_start();
include "../../../backend/databaseconfig.php";

// Initialize modal message variables
$showModal = false;
$modalType = "";
$modalTitle = "";
$modalMessage = "";
$redirectUrl = "";
$showPaymentModal = false;
$paymentDetails = [];

// Login validation function
function requireLogin() {
    if (!isset($_SESSION['login']) || !$_SESSION['login']) {
        header("Location: LoginPage.php");
        exit();
    }
}

// PROCESS PAYMENT
if (isset($_POST["process_payment"])) {
    requireLogin();
    
    $reference_number = $_POST["reference_number"];
    $payment_method = $_POST["payment_method"];
    $deposit_amount = floatval($_POST["deposit_amount"]);
    $total_amount = floatval($_POST["total_amount"]);
    $booking_type = $_POST["booking_type"];
    $email = $_SESSION["email"];
    
    // Generate transaction ID
    $transaction_id = "SPLASH" . time() . rand(1000, 9999);
    $payment_status = "completed";
    
    $conn->begin_transaction();
    
    try {
        // Insert payment record
        $stmt = $conn->prepare("INSERT INTO payments (reference_number, user_email, amount, payment_method, payment_status, transaction_id, booking_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdssss", $reference_number, $email, $deposit_amount, $payment_method, $payment_status, $transaction_id, $booking_type);
        
        if (!$stmt->execute()) {
            throw new Exception("Payment processing failed");
        }
        
        // Get temp booking from session
        if (!isset($_SESSION['temp_booking_' . $reference_number])) {
            throw new Exception("Booking data not found");
        }
        
        $temp_booking = $_SESSION['temp_booking_' . $reference_number];
        
        // Add payment info to booking
        $temp_booking['payment_status'] = 'paid';
        $temp_booking['transaction_id'] = $transaction_id;
        $temp_booking['deposit_paid'] = $deposit_amount;
        $temp_booking['balance_due'] = $total_amount - $deposit_amount;
        
        // Get existing reservations
        $result = $conn->query("SELECT reservation FROM user_account WHERE email = '$email'");
        $row = $result->fetch_assoc();
        $cart = $row['reservation'] ? json_decode($row['reservation'], true) : [];
        
        // Add new reservation
        $cart[] = $temp_booking;
        $update_cart = $conn->real_escape_string(json_encode($cart));
        
        // Update user account
        $updateUser = $conn->query("UPDATE user_account SET reservation = '$update_cart' WHERE email = '$email'");
        
        if (!$updateUser) {
            throw new Exception("Failed to save reservation");
        }
        
        // Deduct availability
        if ($booking_type == "room") {
            $room_name = $conn->real_escape_string($temp_booking['room_type']);
            $quantity = intval($temp_booking['rooms']);
            
            $check = $conn->query("SELECT room_available FROM rooms_available WHERE room_name = '$room_name' FOR UPDATE");
            $data = $check->fetch_assoc();
            
            if (!$data || $data['room_available'] < $quantity) {
                throw new Exception("Room no longer available");
            }
            
            $conn->query("UPDATE rooms_available SET room_available = room_available - $quantity WHERE room_name = '$room_name'");
            
        } else {
            $cottage_name = $conn->real_escape_string($temp_booking['cottage_type']);
            $quantity = intval($temp_booking['cottages']);
            
            $check = $conn->query("SELECT cottage_available FROM cottage_available WHERE cottage_name = '$cottage_name' FOR UPDATE");
            $data = $check->fetch_assoc();
            
            if (!$data || $data['cottage_available'] < $quantity) {
                throw new Exception("Cottage no longer available");
            }
            
            $conn->query("UPDATE cottage_available SET cottage_available = cottage_available - $quantity WHERE cottage_name = '$cottage_name'");
        }
        
        $conn->commit();
        unset($_SESSION['temp_booking_' . $reference_number]);
        
        $showModal = true;
        $modalType = "success";
        $modalTitle = "Payment Successful!";
        $modalMessage = "
            <div class='text-start'>
                <p class='mb-3'><strong>Your payment has been processed successfully!</strong></p>
                <hr>
                <strong>Payment Receipt:</strong><br>
                • Transaction ID: <strong class='text-primary'>$transaction_id</strong><br>
                • Reference Number: <strong>$reference_number</strong><br>
                • Deposit Paid: <strong class='text-success'>₱" . number_format($deposit_amount, 2) . "</strong><br>
                • Balance Due at Check-in: <strong class='text-warning'>₱" . number_format($total_amount - $deposit_amount, 2) . "</strong><br>
                • Payment Method: <strong>" . ucfirst(str_replace('_', ' ', $payment_method)) . "</strong><br>
                • Status: <span class='badge bg-success'>Paid & Confirmed</span>
                <hr>
                <p class='text-muted small mb-0'>Your booking is now pending admin approval.</p>
            </div>";
        $redirectUrl = "Profilepage.php";
        
    } catch (Exception $e) {
        $conn->rollback();
        $showModal = true;
        $modalType = "error";
        $modalTitle = "Payment Failed";
        $modalMessage = "Error: " . htmlspecialchars($e->getMessage());
        $redirectUrl = "Bookingpage.php";
    }
}

// COMBINED BOOKING SUBMISSION
if (isset($_POST["booking_submit"])) {
    requireLogin();
    
    $booking_type = $_POST["booking_type"];
    $arrivalDate = $_POST["arrival"];
    $departureDate = $_POST["departure"];
    $quantity = intval($_POST["quantity"]);
    $adultGuestsQuantity = intval($_POST["adults"]);
    $childrenGuestsQuantity = intval($_POST["children"]);
    $message = $conn->real_escape_string($_POST["message"]);
    $reference_number = "REF" . time() . rand(1000, 9999);
    
    // Validate quantity
    if ($quantity <= 0) {
        $showModal = true;
        $modalType = "error";
        $modalTitle = "Invalid Quantity";
        $modalMessage = "Please select at least 1 room/cottage!";
        $redirectUrl = "Bookingpage.php";
    } else {
        // Calculate days
        $start = new DateTime($arrivalDate);
        $end = new DateTime($departureDate);
        $interval = $start->diff($end);
        $total_days = $interval->days;
        
        if ($total_days <= 0) {
            $showModal = true;
            $modalType = "error";
            $modalTitle = "Invalid Dates";
            $modalMessage = "Departure must be after arrival!";
            $redirectUrl = "Bookingpage.php";
        } else {
            if ($booking_type == "room") {
                // ROOM BOOKING
                $room_type = trim(str_replace('"', '', $_POST["accommodation_type"]));
                
                // Calculate price based on room type
                $room_prices = [
                    'deluxe_warm_earth_suite' => 2000,
                    'primary_taupe_sanctuary' => 3000,
                    'primary_urban_quarters' => 4000,
                    'signature_grand_king' => 5000,
                    'exotic_haven' => 6000
                ];

                $total_price = isset($room_prices[$room_type]) 
                    ? ($room_prices[$room_type] * $quantity) * $total_days 
                    : 0;

                if ($total_price == 0) {
                    $showModal = true;
                    $modalType = "error";
                    $modalTitle = "Invalid Selection";
                    $modalMessage = "Invalid room type selected!";
                    $redirectUrl = "Bookingpage.php";
                } else {
                    $room_name = $conn->real_escape_string($room_type);
                    
                    // Check availability (don't deduct yet)
                    $check = $conn->query("SELECT room_available FROM rooms_available WHERE room_name = '$room_name'");
                    
                    if (!$check) {
                        $showModal = true;
                        $modalType = "error";
                        $modalTitle = "Booking Error";
                        $modalMessage = "Failed to check room availability";
                        $redirectUrl = "Bookingpage.php";
                    } else {
                        $data = $check->fetch_assoc();
                        $available = $data ? intval($data['room_available']) : 0;

                        if ($available < $quantity) {
                            $showModal = true;
                            $modalType = "error";
                            $modalTitle = "Booking Failed";
                            $modalMessage = "<strong>Not enough rooms available!</strong><br><br>
                                            <div class='text-start'>
                                            • Room Type: <strong>$room_type</strong><br>
                                            • Available: <strong class='text-danger'>$available</strong><br>
                                            • Requested: <strong>$quantity</strong>
                                            </div>";
                            $redirectUrl = "Bookingpage.php";
                        } else {
                            // Calculate deposit (30%)
                            $deposit_amount = round($total_price * 0.30, 2);
                            
                            // Store temp booking
                            $_SESSION['temp_booking_' . $reference_number] = [
                                "referenceNum" => $reference_number,
                                "full_name" => $_SESSION['full_name'],
                                "room_type" => $room_type,
                                "arrival" => $arrivalDate,
                                "departure" => $departureDate,
                                "rooms" => $quantity,
                                "adults" => $adultGuestsQuantity,
                                "child" => $childrenGuestsQuantity,
                                "message" => $message,
                                "price" => $total_price,
                                "days_of_stay" => $total_days,
                                "status" => "pending",
                                "type" => "room",
                                "booking_date" => date('Y-m-d H:i:s')
                            ];
                            
                            $showPaymentModal = true;
                            $paymentDetails = [
                                'reference_number' => $reference_number,
                                'total_price' => $total_price,
                                'deposit_amount' => $deposit_amount,
                                'remaining_balance' => $total_price - $deposit_amount,
                                'booking_type' => 'room',
                                'accommodation_name' => ucwords(str_replace('_', ' ', $room_type)),
                                'quantity' => $quantity,
                                'days' => $total_days
                            ];
                        }
                    }
                }
                
            } elseif ($booking_type == "cottage") {
                // COTTAGE BOOKING
                $cottage_type = trim(str_replace('"', '', $_POST["accommodation_type"]));
                
                // Calculate price based on cottage type
                $cottage_prices = [
                    'Bamboo_Beach_Villa' => 2000,
                    'Canopy_Lagoon_Suite' => 3000,
                    'Deluxe_Ocean_View' => 4000,
                    'Oceanfront_Overwater' => 5000
                ];

                $total_price = isset($cottage_prices[$cottage_type]) 
                    ? ($cottage_prices[$cottage_type] * $quantity) * $total_days 
                    : 0;

                if ($total_price == 0) {
                    $showModal = true;
                    $modalType = "error";
                    $modalTitle = "Invalid Selection";
                    $modalMessage = "Invalid cottage type selected!";
                    $redirectUrl = "Bookingpage.php";
                } else {
                    $cottage_name = $conn->real_escape_string($cottage_type);
                    
                    $check = $conn->query("SELECT cottage_available FROM cottage_available WHERE cottage_name = '$cottage_name'");
                    
                    if (!$check) {
                        $showModal = true;
                        $modalType = "error";
                        $modalTitle = "Booking Error";
                        $modalMessage = "Failed to check cottage availability";
                        $redirectUrl = "Bookingpage.php";
                    } else {
                        $data = $check->fetch_assoc();
                        $available = $data ? intval($data['cottage_available']) : 0;

                        if ($available < $quantity) {
                            $showModal = true;
                            $modalType = "error";
                            $modalTitle = "Booking Failed";
                            $modalMessage = "<strong>Not enough cottages available!</strong><br><br>
                                            <div class='text-start'>
                                            • Cottage Type: <strong>$cottage_type</strong><br>
                                            • Available: <strong class='text-danger'>$available</strong><br>
                                            • Requested: <strong>$quantity</strong>
                                            </div>";
                            $redirectUrl = "Bookingpage.php";
                        } else {
                            $deposit_amount = round($total_price * 0.30, 2);
                            
                            $_SESSION['temp_booking_' . $reference_number] = [
                                "referenceNum" => $reference_number,
                                "full_name" => $_SESSION['full_name'],
                                "cottage_type" => $cottage_type,
                                "arrival" => $arrivalDate,
                                "departure" => $departureDate,
                                "cottages" => $quantity,
                                "adults" => $adultGuestsQuantity,
                                "child" => $childrenGuestsQuantity,
                                "message" => $message,
                                "price" => $total_price,
                                "days_of_stay" => $total_days,
                                "status" => "pending",
                                "type" => "cottage",
                                "booking_date" => date('Y-m-d H:i:s')
                            ];
                            
                            $showPaymentModal = true;
                            $paymentDetails = [
                                'reference_number' => $reference_number,
                                'total_price' => $total_price,
                                'deposit_amount' => $deposit_amount,
                                'remaining_balance' => $total_price - $deposit_amount,
                                'booking_type' => 'cottage',
                                'accommodation_name' => str_replace('_', ' ', $cottage_type),
                                'quantity' => $quantity,
                                'days' => $total_days
                            ];
                        }
                    }
                }
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <link rel="icon" href="../assets/logo.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Splash Resort - Book Now</title>
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
    
    <!-- HEADER CAROUSEL -->
    <header class="bookingHeader">
       <div id="carouselExampleCaptions" class="carousel slide">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="../assets/headerBg.png" class="d-block w-100" alt="..." />
            <div class="carousel-caption">
              <h5>Splash Resort Your Beachside Escape</h5>
              <p>Wake up to ocean views from our cozy rooms and private cottages. Relax by the infinity pool, stroll the sandy shore, and book your perfect stay today. <a href="Bookingpage.php">Reserve now</a></p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="../assets/TerracedGreenOverlook.webp" class="d-block w-100" alt="..." />
            <div class="carousel-caption">
              <h5>Rooms • Cottages • Pool • Beach View</h5>
              <p>Choose from seaside rooms or tucked-away cottages with stunning beach vistas. Enjoy a crystal-clear pool, shoreline sunsets, and easy online reservations for a hassle-free getaway.</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="../assets/seaview.jpg" class="d-block w-100" alt="..." />
            <div class="carousel-caption">
              <h5>Book Your Beach Getaway</h5>
              <p>Rooms and cottages with pool access and breathtaking beach views. Limited slots <a href="Bookingpage.php">reserve your dates</a> now!</p>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden z-3">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden z-3">Next</span>
        </button>
      </div>
    </header>

    <!-- COMBINED BOOKING SECTION -->
    <main class="p-5 d-md-flex flex-md-row d-sm-flex flex-sm-column w-100 gap-5 p-md-5">
      <div>
        <h1>Book Your Stay at Splash Resort</h1>
        <p>Experience ultimate comfort at Splash Resort. Choose between our luxurious rooms or spacious cottages, each designed to provide you with an unforgettable beachside experience.</p>
        <b>Our accommodations include:</b>
        <ul>
          <li>Daily breakfast for all guests</li>
          <li>Complimentary access to Relaxation Lounge and herbal tea selection</li>
          <li>Free use of Fitness Center and outdoor swimming pool</li>
          <li>Complimentary parking for one vehicle per night</li>
          <li>24/7 concierge service</li>
          <li>Complimentary WiFi throughout the property</li>
        </ul>
        <p><strong>Rooms:</strong> Perfect for couples or small families, featuring modern amenities and ocean views. Rates from ₱2,000 - ₱6,000 per night.</p>
        <p><strong>Cottages:</strong> Ideal for families and groups, offering more space and privacy surrounded by lush greenery. Rates from ₱2,000 - ₱5,000 per night.</p>
        <div class="alert alert-info">
          <ion-icon name="information-circle-outline" class="fs-5"></ion-icon>
          <strong>Payment Required:</strong> A 30% deposit is required to secure your reservation. Balance payable upon check-in.
        </div>
        <p>Reservation hotline: (02) 2376-3266</p>
      </div>

      <!-- COMBINED BOOKING FORM -->
      <form action="Bookingpage.php" method="post" class="d-flex flex-column gap-3 p-4 border text-center bookingPageForm justify-content-between ms-auto h-100">
        <h5>Book Your Stay</h5>
        <hr>
        
        <!-- BOOKING TYPE SELECTION -->
        <div class="w-100 text-start">
          <label class="form-label fw-bold">Select Accommodation Type</label>
          <div class="d-flex gap-3">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="booking_type" id="bookingRoom" value="room" checked>
              <label class="form-check-label" for="bookingRoom">Room</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="booking_type" id="bookingCottage" value="cottage">
              <label class="form-check-label" for="bookingCottage">Cottage</label>
            </div>
          </div>
        </div>

        <!-- ACCOMMODATION TYPE DROPDOWN -->
        <div class="w-100">
          <label for="accommodationType" class="form-label fw-light">Select Your Accommodation</label>
          
          <select class="form-select border-1 border-secondary" id="roomTypeSelect" name="accommodation_type" required>
            <option value="deluxe_warm_earth_suite">Deluxe Warm Earth Suite - ₱2,000/night</option>
            <option value="primary_taupe_sanctuary">Primary Taupe Sanctuary - ₱3,000/night</option>
            <option value="primary_urban_quarters">Primary Urban Quarters - ₱4,000/night</option>
            <option value="signature_grand_king">Signature Grand King - ₱5,000/night</option>
            <option value="exotic_haven">Exotic Haven - ₱6,000/night</option>
          </select>
          
          <select class="form-select border-1 border-secondary d-none" id="cottageTypeSelect" name="accommodation_type_cottage">
            <option value="Bamboo_Beach_Villa">Bamboo Beach Villa - ₱2,000/night</option>
            <option value="Canopy_Lagoon_Suite">Canopy Lagoon Suite - ₱3,000/night</option>
            <option value="Deluxe_Ocean_View">Deluxe Ocean View - ₱4,000/night</option>
            <option value="Oceanfront_Overwater">Oceanfront Overwater - ₱5,000/night</option>
          </select>
        </div>

        <!-- DATE INPUTS -->
        <div class="d-flex flex-row gap-2 pe-2">
          <div class="w-50">
            <label for="arrival">Check-in Date</label>
            <input type="datetime-local" name="arrival" id="arrival" class="form-control" required>
          </div>
          <div class="w-50">
            <label for="departure">Check-out Date</label>
            <input type="datetime-local" name="departure" id="departure" class="form-control" required>
          </div>
        </div>

        <!-- QUANTITY & GUESTS DROPDOWN -->
        <div class="dropdown">
          <button class="btn w-100 dropdown-toggle border-1 border-secondary" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <span id="accommodationLabel">Rooms</span> & Guests
          </button>
          <ul class="dropdown-menu w-100 p-3" aria-labelledby="dropdownMenuButton">
            <div class="d-flex flex-column gap-2">
              <h6 class="dropdown-item fw-light" id="maxGuestsLabel">Max. 6 guests per room</h6>

              <div class="d-flex flex-row gap-2 ps-3 pe-2">
                <h6 class="fw-light pt-2" id="quantityLabel">Room(s)</h6>
                <div class="d-flex flex-row justify-content-between ms-auto input-group w-50">
                  <button class="btn btn-outline-secondary" type="button" id="quantity-minus">-</button>
                  <input type="number" class="form-control text-center" value="1" min="1" id="quantity-input" name="quantity" required readonly>
                  <button class="btn btn-outline-secondary" type="button" id="quantity-plus">+</button>
                </div>
              </div>

              <div class="d-flex flex-row gap-2 ps-3 pe-2">
                <h6 class="fw-light pt-2">Adult(s)</h6>
                <div class="d-flex flex-row justify-content-between ms-auto input-group w-50">
                  <button class="btn btn-outline-secondary" type="button" id="adult-minus">-</button>
                  <input type="number" class="form-control text-center" value="1" min="1" id="adult-input" name="adults" required readonly>
                  <button class="btn btn-outline-secondary" type="button" id="adult-plus">+</button>
                </div>
              </div>

              <div class="d-flex flex-row gap-2 ps-3 pe-2">
                <h6 class="fw-light pt-2">Children (under 12)</h6>
                <div class="d-flex flex-row justify-content-between ms-auto input-group w-50">
                  <button class="btn btn-outline-secondary" type="button" id="child-minus">-</button>
                  <input type="number" class="form-control text-center" value="0" min="0" id="child-input" name="children" required readonly>
                  <button class="btn btn-outline-secondary" type="button" id="child-plus">+</button>
                </div>
              </div>

              <li><hr class="dropdown-divider"></li>
              <textarea name="message" id="messagearea" style="resize: none" placeholder="Special Requests (Optional)" class="p-1 h-50 text-black" rows="3"></textarea>
            </div>
          </ul>
        </div>

        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmBookingModal">
          Proceed to Payment
        </button>

        <!-- BOOKING CONFIRMATION MODAL -->
        <div class="modal fade" id="confirmBookingModal" tabindex="-1" aria-labelledby="confirmBookingModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
              <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmBookingModalLabel">Confirm Your Booking</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-center">
                <ion-icon name="calendar-outline" class="text-danger" style="font-size: 3rem;"></ion-icon>
                <p class="mt-3">Ready to book your stay?</p>
                <p class="text-muted small">You'll be redirected to payment to secure your reservation with a 30% deposit.</p>
              </div>
              <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <input type="submit" value="Yes, Continue" name="booking_submit" class="btn btn-danger">
              </div>
            </div>
          </div>
        </div>
      </form>
    </main>

    <!-- PAYMENT MODAL -->
    <?php if ($showPaymentModal): ?>
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1" data-bs-backdrop="static">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
              <ion-icon name="card-outline" style="font-size: 1.5rem; vertical-align: middle;"></ion-icon>
              Complete Your Payment
            </h5>
          </div>
          <div class="modal-body p-4">
            <div class="alert alert-info">
              <strong>30% Deposit Required:</strong> Pay now to secure your reservation
            </div>
            
            <div class="card mb-3">
              <div class="card-header bg-light"><strong>Booking Summary</strong></div>
              <div class="card-body">
                <table class="table table-borderless mb-0">
                  <tr>
                    <td><strong>Reference:</strong></td>
                    <td><?php echo $paymentDetails['reference_number']; ?></td>
                  </tr>
                  <tr>
                    <td><strong>Accommodation:</strong></td>
                    <td><?php echo $paymentDetails['accommodation_name']; ?></td>
                  </tr>
                  <tr>
                    <td><strong>Quantity:</strong></td>
                    <td><?php echo $paymentDetails['quantity']; ?></td>
                  </tr>
                  <tr>
                    <td><strong>Days:</strong></td>
                    <td><?php echo $paymentDetails['days']; ?></td>
                  </tr>
                  <tr><td colspan="2"><hr></td></tr>
                  <tr>
                    <td><strong>Total Amount:</strong></td>
                    <td><h5 class="text-primary mb-0">₱<?php echo number_format($paymentDetails['total_price'], 2); ?></h5></td>
                  </tr>
                  <tr>
                    <td><strong>Deposit (30%):</strong></td>
                    <td><h5 class="text-success mb-0">₱<?php echo number_format($paymentDetails['deposit_amount'], 2); ?></h5></td>
                  </tr>
                  <tr>
                    <td><strong>Balance Due:</strong></td>
                    <td><h5 class="text-warning mb-0">₱<?php echo number_format($paymentDetails['remaining_balance'], 2); ?></h5></td>
                  </tr>
                </table>
              </div>
            </div>
            
            <form method="post">
              <input type="hidden" name="reference_number" value="<?php echo $paymentDetails['reference_number']; ?>">
              <input type="hidden" name="deposit_amount" value="<?php echo $paymentDetails['deposit_amount']; ?>">
              <input type="hidden" name="total_amount" value="<?php echo $paymentDetails['total_price']; ?>">
              <input type="hidden" name="booking_type" value="<?php echo $paymentDetails['booking_type']; ?>">
              
              <label class="form-label fw-bold">Payment Method</label>
              <div class="row g-2 mb-3">
                <div class="col-6">
                  <input type="radio" class="btn-check" name="payment_method" id="gcash" value="gcash" checked>
                  <label class="btn btn-outline-primary w-100" for="gcash">
                    <strong>GCash</strong><br><small>Mobile Wallet</small>
                  </label>
                </div>
                <div class="col-6">
                  <input type="radio" class="btn-check" name="payment_method" id="paymaya" value="paymaya">
                  <label class="btn btn-outline-primary w-100" for="paymaya">
                    <strong>PayMaya</strong><br><small>Mobile Wallet</small>
                  </label>
                </div>
                <div class="col-6">
                  <input type="radio" class="btn-check" name="payment_method" id="card" value="credit_card">
                  <label class="btn btn-outline-primary w-100" for="card">
                    <strong>Card</strong><br><small>Visa/Mastercard</small>
                  </label>
                </div>
                <div class="col-6">
                  <input type="radio" class="btn-check" name="payment_method" id="bank" value="bank_transfer">
                  <label class="btn btn-outline-primary w-100" for="bank">
                    <strong>Bank</strong><br><small>Transfer</small>
                  </label>
                </div>
              </div>
              
              <div class="alert alert-warning">
                <small><strong>Note:</strong> Balance of ₱<?php echo number_format($paymentDetails['remaining_balance'], 2); ?> due at check-in</small>
              </div>
              
              <div class="d-grid gap-2">
                <button type="submit" name="process_payment" class="btn btn-success btn-lg">
                  <ion-icon name="card-outline"></ion-icon>
                  Pay ₱<?php echo number_format($paymentDetails['deposit_amount'], 2); ?>
                </button>
                <a href="Bookingpage.php" class="btn btn-outline-secondary">Cancel Booking</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- DYNAMIC SUCCESS/ERROR MODAL -->
    <?php if ($showModal): ?>
    <div class="modal fade show d-block" id="resultModal" style="background: rgba(0,0,0,0.5);" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-header <?php echo $modalType == 'success' ? 'bg-success' : 'bg-danger'; ?> text-white">
            <h5 class="modal-title">
              <?php if ($modalType == 'success'): ?>
                <ion-icon name="checkmark-circle-outline" style="font-size: 1.5rem; vertical-align: middle;"></ion-icon>
              <?php else: ?>
                <ion-icon name="alert-circle-outline" style="font-size: 1.5rem; vertical-align: middle;"></ion-icon>
              <?php endif; ?>
              <?php echo htmlspecialchars($modalTitle); ?>
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center py-4">
            <?php echo $modalMessage; ?>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn <?php echo $modalType == 'success' ? 'btn-success' : 'btn-danger'; ?>" data-bs-dismiss="modal" onclick="window.location.href='<?php echo $redirectUrl; ?>'">
              OK
            </button>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- FOOTER -->
    <footer class="p-3">
      <div class="info d-flex gap-md-5 justify-content-center p-md-5 flex-wrap">
        <img src="../assets/logo.png" alt="" class="align-self-center" />
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
    <h5 class="text-center fw-light w-100 p-3" style="font-size: 14px">
      Privacy Policy | Terms & Conditions | Safety & Security | Supplier Code of Conduct | Cyber Security <br />
      © 2025 Splash Island Co. All Rights Reserved. ICP license: 22007722
    </h5>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      <?php if ($showModal): ?>
      var resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
      resultModal.show();
      <?php endif; ?>

      // Prevent dropdown from closing
      const dropdownMenu = document.querySelector('.dropdown-menu');
      dropdownMenu.addEventListener('click', function(event) {
        event.stopPropagation();
      });

      // Radio button switching
      const roomRadio = document.getElementById('bookingRoom');
      const cottageRadio = document.getElementById('bookingCottage');
      const roomSelect = document.getElementById('roomTypeSelect');
      const cottageSelect = document.getElementById('cottageTypeSelect');
      const accommodationLabel = document.getElementById('accommodationLabel');
      const quantityLabel = document.getElementById('quantityLabel');
      const maxGuestsLabel = document.getElementById('maxGuestsLabel');

      function switchAccommodationType() {
        if (roomRadio.checked) {
          roomSelect.classList.remove('d-none');
          cottageSelect.classList.add('d-none');
          roomSelect.name = 'accommodation_type';
          cottageSelect.name = 'accommodation_type_cottage';
          accommodationLabel.textContent = 'Rooms';
          quantityLabel.textContent = 'Room(s)';
          maxGuestsLabel.textContent = 'Max. 6 guests per room';
        } else {
          cottageSelect.classList.remove('d-none');
          roomSelect.classList.add('d-none');
          cottageSelect.name = 'accommodation_type';
          roomSelect.name = 'accommodation_type_room';
          accommodationLabel.textContent = 'Cottages';
          quantityLabel.textContent = 'Cottage(s)';
          maxGuestsLabel.textContent = 'Max. 8 guests per cottage';
        }
      }

      roomRadio.addEventListener('change', switchAccommodationType);
      cottageRadio.addEventListener('change', switchAccommodationType);

      // Counter setup
      function setupCounter(minusBtn, input, plusBtn, minValue = 0) {
        minusBtn.addEventListener("click", function(e) {
          e.preventDefault();
          let current = parseInt(input.value) || minValue;
          if (current > minValue) {
            input.value = current - 1;
          }
        });

        plusBtn.addEventListener("click", function(e) {
          e.preventDefault();
          let current = parseInt(input.value) || minValue;
          input.value = current + 1;
        });
      }

      const quantityMinus = document.querySelector('#quantity-minus');
      const quantityInput = document.querySelector('#quantity-input');
      const quantityPlus = document.querySelector('#quantity-plus');

      const adultMinus = document.querySelector('#adult-minus');
      const adultInput = document.querySelector('#adult-input');
      const adultPlus = document.querySelector('#adult-plus');

      const childMinus = document.querySelector('#child-minus');
      const childInput = document.querySelector('#child-input');
      const childPlus = document.querySelector('#child-plus');

      setupCounter(quantityMinus, quantityInput, quantityPlus, 1);
      setupCounter(adultMinus, adultInput, adultPlus, 1);
      setupCounter(childMinus, childInput, childPlus, 0);

      // Date validation
      const arrival = document.getElementById("arrival");
      const departure = document.getElementById("departure");

      const today = new Date();
      const minDateTime = today.toISOString().slice(0, 16);
      
      arrival.min = minDateTime;
      departure.min = minDateTime;

      arrival.addEventListener("change", function() {
        departure.min = this.value;
        if (departure.value && departure.value < this.value) {
          departure.value = "";
        }
      });
    });
    </script>
</body>
</html>
