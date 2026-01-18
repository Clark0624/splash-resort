<?php
session_start();
include "../../../backend/databaseconfig.php";

// Initialize modal variables
$showModal = false;
$modalType = "";
$modalTitle = "";
$modalMessage = "";

function logout(){
    session_unset();
    session_destroy();
    header("Location: Homepage.php");
    exit();
}

if(isset($_POST["logout"])){
    logout();
}

if (!isset($_SESSION['email'])) {
    header("Location: loginPage.php");
    exit();
}

// UPDATE PROFILE
if(isset($_POST['update'])){
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number'];
    $updateQuery = $conn->prepare("UPDATE user_account SET full_name = ?, email = ?, phone_number = ? WHERE email = ?");
    $updateQuery->bind_param("ssss", $full_name, $email, $phone_number, $_SESSION["email"]);
    
    if($updateQuery->execute()){
        $_SESSION["email"] = $email;
        $_SESSION["full_name"] = $full_name;
        $showModal = true;
        $modalType = "success";
        $modalTitle = "Profile Updated";
        $modalMessage = "Your profile information has been updated successfully!";
    } else {
        $showModal = true;
        $modalType = "error";
        $modalTitle = "Update Failed";
        $modalMessage = "Failed to update profile. Please try again.";
    }
}

// Get reservation data
$result = $conn->prepare("SELECT reservation FROM user_account WHERE email = ?");
$result->bind_param("s", $_SESSION["email"]);
$result->execute();
$dataResult = $result->get_result();

$data = null;
$emptyCart = null;

if ($dataResult->num_rows > 0) {
    $row = $dataResult->fetch_assoc();
    $json_data = $row["reservation"];
    $data = json_decode($json_data, true);
    
    // CANCEL RESERVATION (with status check and payment refund)
    if (isset($_GET['delete'])) {
        $deleteId = $_GET['delete'];
        
        $conn->begin_transaction();
        
        try {
            $updatedData = [];
            $found = false;
            $item_to_restore = null;
            $quantity_to_restore = 0;
            $is_cottage = false;
            $reservation_status = null;
            $payment_to_refund = 0;
            $transaction_id = null;
            
            // Find the reservation to delete
            foreach ($data as $reservation) {
                if (($reservation["referenceNum"] ?? null) == $deleteId) {
                    // Check status
                    $reservation_status = isset($reservation["status"]) ? $reservation["status"] : "pending";
                    
                    if ($reservation_status !== "pending") {
                        throw new Exception("This reservation has been confirmed and cannot be cancelled!");
                    }
                    
                    $found = true;
                    $payment_to_refund = isset($reservation["deposit_paid"]) ? floatval($reservation["deposit_paid"]) : 0;
                    $transaction_id = isset($reservation["transaction_id"]) ? $reservation["transaction_id"] : null;
                    
                    // Detect if cottage or room
                    if (isset($reservation["cottage_type"])) {
                        $is_cottage = true;
                        $item_to_restore = $reservation["cottage_type"];
                        $quantity_to_restore = $reservation["cottages"] ?? 0;
                    } elseif (isset($reservation["room_type"])) {
                        $is_cottage = false;
                        $item_to_restore = $reservation["room_type"];
                        $quantity_to_restore = $reservation["rooms"] ?? 0;
                    }
                } else {
                    $updatedData[] = $reservation;
                }
            }
            
            if (!$found) {
                throw new Exception("Reservation not found!");
            }
            
            // Update JSON reservations
            $newJson = json_encode($updatedData);
            $updateQuery = $conn->prepare("UPDATE user_account SET reservation = ? WHERE email = ?");
            $updateQuery->bind_param("ss", $newJson, $_SESSION["email"]);
            
            if (!$updateQuery->execute()) {
                throw new Exception("Failed to update reservation data");
            }
            
            // Restore availability
            if ($item_to_restore && $quantity_to_restore > 0) {
                if ($is_cottage) {
                    $restore = $conn->prepare("UPDATE cottage_available 
                                              SET cottage_available = cottage_available + ? 
                                              WHERE cottage_name = ?");
                } else {
                    $restore = $conn->prepare("UPDATE rooms_available 
                                              SET room_available = room_available + ? 
                                              WHERE room_name = ?");
                }
                
                $restore->bind_param("is", $quantity_to_restore, $item_to_restore);
                
                if (!$restore->execute()) {
                    throw new Exception("Failed to restore availability");
                }
            }
            
            // Update payment status to cancelled
            if ($transaction_id) {
                $updatePayment = $conn->prepare("UPDATE payments SET payment_status = 'cancelled' WHERE transaction_id = ?");
                $updatePayment->bind_param("s", $transaction_id);
                $updatePayment->execute();
            }
            
            $conn->commit();
            
            $showModal = true;
            $modalType = "success";
            $modalTitle = "Cancellation Successful";
            $modalMessage = "Your reservation has been cancelled successfully.<br><br>
                            <strong>Cancelled Details:</strong><br>
                            • Reference Number: <strong>$deleteId</strong><br>
                            • Deposit Refund: <strong class='text-success'>₱" . number_format($payment_to_refund, 2) . "</strong><br>
                            • Availability Restored: <strong>$quantity_to_restore</strong><br><br>
                            <small class='text-muted'>Refund will be processed within 3-5 business days.</small>";
            
        } catch (Exception $e) {
            $conn->rollback();
            
            $showModal = true;
            $modalType = "error";
            $modalTitle = "Cancellation Failed";
            $modalMessage = htmlspecialchars($e->getMessage());
        }
        
        // Refresh data after deletion
        $result = $conn->prepare("SELECT reservation FROM user_account WHERE email = ?");
        $result->bind_param("s", $_SESSION["email"]);
        $result->execute();
        $dataResult = $result->get_result();
        
        if ($dataResult->num_rows > 0) {
            $row = $dataResult->fetch_assoc();
            $json_data = $row["reservation"];
            $data = json_decode($json_data, true);
        } else {
            $data = null;
        }
    }
} else {
    $emptyCart = 'No reservations yet';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profile - Splash Resort</title>
  <link rel="icon" href="../assets/logo.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body class="bg-light">
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
    <div class="container-fluid">
      <a href="Homepage.php" class="navbar-brand d-flex align-items-center">
        <img src="../assets/logo.png" height="50" alt="Logo" class="me-2" />
        <span class="fw-light">Splash Resort</span>
      </a>

      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
        <ion-icon name="menu-outline" class="text-dark fs-4"></ion-icon>
      </button>

      <div class="collapse navbar-collapse" id="navbarMenu">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item"><a href="Homepage.php" class="nav-link"><b>Home</b></a></li>
          <li class="nav-item"><a href="Amenitiespage.php" class="nav-link"><b>Amenities</b></a></li>
          <li class="nav-item"><a href="Contactpage.php" class="nav-link"><b>Contact</b></a></li>
          <li class="nav-item"><a href="Bookingpage.php" class="nav-link"><b>Book Now</b></a></li>
          <li class="nav-item dropdown ms-lg-3">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
              <ion-icon name="person-circle-outline" class="fs-4 me-1"></ion-icon>
              <span class="d-lg-none">Profile</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                  <ion-icon name="log-out-outline" class="me-2"></ion-icon>Log Out
                </button>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        
        <!-- Profile Information Card -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><ion-icon name="person-outline" class="me-2"></ion-icon>Profile Information</h5>
          </div>
          <div class="card-body">
            <?php
              $user_info = $conn->prepare("SELECT full_name, email, phone_number FROM user_account WHERE email = ?");
              $user_info->bind_param("s", $_SESSION["email"]);
              $user_info->execute();
              $user_info_result = $user_info->get_result();
              while ($row = $user_info_result->fetch_assoc()) {
            ?>
            <div class="row mb-3">
              <div class="col-sm-4 fw-bold text-secondary">Full Name:</div>
              <div class="col-sm-8"><?php echo htmlspecialchars($row['full_name']); ?></div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-4 fw-bold text-secondary">Email:</div>
              <div class="col-sm-8"><?php echo htmlspecialchars($row['email']); ?></div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-4 fw-bold text-secondary">Phone:</div>
              <div class="col-sm-8"><?php echo htmlspecialchars($row['phone_number']); ?></div>
            </div>
            <?php 
                $profile_name = $row['full_name'];
                $profile_email = $row['email'];
                $profile_phone = $row['phone_number'];
              } 
            ?>
          </div>
          <div class="card-footer bg-white text-end">
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#profileModal">
              <ion-icon name="create-outline" class="me-1"></ion-icon>Edit Profile
            </button>
          </div>
        </div>

        <!-- Reservations Section -->
        <?php if (!empty($data) && is_array($data)): ?>
          <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><ion-icon name="calendar-outline" class="me-2"></ion-icon>Your Reservations</h5>
            </div>
          </div>

          <?php foreach ($data as $value): 
            $refNum = htmlspecialchars($value["referenceNum"] ?? "N/A");
            
            // Get status
            $status = isset($value["status"]) ? $value["status"] : "pending";
            $statusBadge = $status == "confirmed" ? 
                "<span class='badge bg-success'>Confirmed by Admin</span>" : 
                "<span class='badge bg-warning text-dark'>Pending Approval</span>";
            
            // Get payment status
            $payment_status = isset($value["payment_status"]) ? $value["payment_status"] : "unpaid";
            $paymentBadge = $payment_status == "paid" ?
                "<span class='badge bg-success'><ion-icon name='checkmark-circle'></ion-icon> Paid</span>" :
                "<span class='badge bg-danger'><ion-icon name='close-circle'></ion-icon> Unpaid</span>";
            
            $canCancel = ($status == "pending");
          ?>
          <div class="card shadow-sm mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
              <h6 class="mb-0 text-primary">
                <ion-icon name="document-text-outline"></ion-icon>
                Reference No: <strong><?php echo $refNum; ?></strong>
              </h6>
              <div>
                <?php echo $statusBadge; ?>
                <?php echo $paymentBadge; ?>
              </div>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <small class="text-muted d-block"><ion-icon name="bed-outline"></ion-icon> Accommodation</small>
                  <strong><?php echo htmlspecialchars(str_replace("_", " ", ucwords($value["room_type"] ?? $value["cottage_type"] ?? "N/A"))); ?></strong>
                </div>
                <div class="col-md-6">
                  <small class="text-muted d-block"><ion-icon name="cube-outline"></ion-icon> Quantity</small>
                  <strong><?php echo htmlspecialchars($value["rooms"] ?? $value["cottages"] ?? "N/A"); ?></strong>
                </div>
                <div class="col-md-4">
                  <small class="text-muted d-block"><ion-icon name="people-outline"></ion-icon> Adults</small>
                  <strong><?php echo htmlspecialchars($value["adults"] ?? "N/A"); ?></strong>
                </div>
                <div class="col-md-4">
                  <small class="text-muted d-block"><ion-icon name="happy-outline"></ion-icon> Children</small>
                  <strong><?php echo htmlspecialchars($value["child"] ?? "N/A"); ?></strong>
                </div>
                <div class="col-md-4">
                  <small class="text-muted d-block"><ion-icon name="time-outline"></ion-icon> Days of Stay</small>
                  <strong><?php echo htmlspecialchars($value["days_of_stay"] ?? "N/A"); ?> day(s)</strong>
                </div>
                <div class="col-md-6">
                  <small class="text-muted d-block"><ion-icon name="calendar-outline"></ion-icon> Check-in</small>
                  <strong><?php echo htmlspecialchars($value["arrival"] ?? "N/A"); ?></strong>
                </div>
                <div class="col-md-6">
                  <small class="text-muted d-block"><ion-icon name="calendar-outline"></ion-icon> Check-out</small>
                  <strong><?php echo htmlspecialchars($value["departure"] ?? "N/A"); ?></strong>
                </div>
                
                <div class="col-12"><hr class="my-2"></div>
                
                <!-- Payment Information -->
                <div class="col-md-4">
                  <small class="text-muted d-block"><ion-icon name="cash-outline"></ion-icon> Total Amount</small>
                  <h5 class="text-primary mb-0">₱<?php echo number_format($value["price"] ?? 0, 2); ?></h5>
                </div>
                <div class="col-md-4">
                  <small class="text-muted d-block"><ion-icon name="wallet-outline"></ion-icon> Deposit Paid</small>
                  <h5 class="text-success mb-0">₱<?php echo number_format($value["deposit_paid"] ?? 0, 2); ?></h5>
                </div>
                <div class="col-md-4">
                  <small class="text-muted d-block"><ion-icon name="card-outline"></ion-icon> Balance Due</small>
                  <h5 class="text-warning mb-0">₱<?php echo number_format($value["balance_due"] ?? 0, 2); ?></h5>
                </div>
                
                <?php if (isset($value["transaction_id"])): ?>
                <div class="col-md-6">
                  <small class="text-muted d-block"><ion-icon name="receipt-outline"></ion-icon> Transaction ID</small>
                  <strong><?php echo htmlspecialchars($value["transaction_id"]); ?></strong>
                </div>
                <?php endif; ?>
                
                <div class="col-md-6">
                  <small class="text-muted d-block"><ion-icon name="calendar-number-outline"></ion-icon> Booking Date</small>
                  <strong><?php echo htmlspecialchars($value["booking_date"] ?? "N/A"); ?></strong>
                </div>
                
                <div class="col-12">
                  <small class="text-muted d-block"><ion-icon name="chatbox-outline"></ion-icon> Special Requests</small>
                  <p class="mb-0"><?php echo htmlspecialchars($value["message"] ?? "No special requests"); ?></p>
                </div>
              </div>
            </div>
            <div class="card-footer bg-white text-center">
              <?php if ($canCancel): ?>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal<?php echo $refNum; ?>">
                  <ion-icon name="close-circle-outline" class="me-1"></ion-icon>Cancel Reservation & Refund
                </button>
              <?php else: ?>
                <button type="button" class="btn btn-secondary" disabled>
                  <ion-icon name="lock-closed-outline" class="me-1"></ion-icon>Cannot Cancel (Admin Confirmed)
                </button>
              <?php endif; ?>
            </div>
          </div>

          <!-- Cancel Modal -->
          <?php if ($canCancel): ?>
          <div class="modal fade" id="cancelModal<?php echo $refNum; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                  <h5 class="modal-title">Confirm Cancellation</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                  <ion-icon name="warning-outline" class="text-danger" style="font-size: 4rem;"></ion-icon>
                  <p class="mt-3 fs-5">Are you sure you want to cancel this reservation?</p>
                  <div class="alert alert-info text-start">
                    <strong>Refund Information:</strong><br>
                    • Deposit Amount: ₱<?php echo number_format($value["deposit_paid"] ?? 0, 2); ?><br>
                    • Processing Time: 3-5 business days<br>
                    • Availability will be restored
                  </div>
                  <p class="text-muted">Reference No: <strong><?php echo $refNum; ?></strong></p>
                </div>
                <div class="modal-footer justify-content-center">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep It</button>
                  <a href="?delete=<?php echo urlencode($refNum); ?>" class="btn btn-danger">Yes, Cancel & Refund</a>
                </div>
              </div>
            </div>
          </div>
          <?php endif; ?>
          
          <?php endforeach; ?>

        <?php else: ?>
          <div class="card shadow-sm text-center py-5">
            <div class="card-body">
              <ion-icon name="calendar-outline" class="text-muted" style="font-size: 4rem;"></ion-icon>
              <h4 class="mt-3 text-muted">No Reservations Yet</h4>
              <p class="text-secondary">Start planning your perfect getaway!</p>
              <a href="Bookingpage.php" class="btn btn-primary mt-2">
                <ion-icon name="add-circle-outline" class="me-1"></ion-icon>Make a Reservation
              </a>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>

  <!-- Profile Edit Modal -->
  <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form method="post">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">Edit Profile Information</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="editName" class="form-label fw-bold">Full Name</label>
              <input type="text" class="form-control" id="editName" name="full_name" value="<?php echo htmlspecialchars($profile_name ?? ''); ?>" required>
            </div>
            <div class="mb-3">
              <label for="editEmail" class="form-label fw-bold">Email</label>
              <input type="email" class="form-control" id="editEmail" name="email" value="<?php echo htmlspecialchars($profile_email ?? ''); ?>" required>
            </div>
            <div class="mb-3">
              <label for="editPhone" class="form-label fw-bold">Phone</label>
              <input type="text" class="form-control" id="editPhone" name="phone_number" value="<?php echo htmlspecialchars($profile_phone ?? ''); ?>" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success" name="update">
              <ion-icon name="checkmark-circle-outline" class="me-1"></ion-icon>Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Logout Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Confirm Logout</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <ion-icon name="log-out-outline" class="text-danger" style="font-size: 4rem;"></ion-icon>
          <p class="mt-3 fs-5">Are you sure you want to log out?</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <form method="post" class="m-0">
            <button type="submit" name="logout" class="btn btn-danger">Yes, Log Out</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Result Modal -->
  <?php if ($showModal): ?>
  <div class="modal fade show d-block" id="resultModal" style="background: rgba(0,0,0,0.5);" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header <?php echo $modalType == 'success' ? 'bg-success' : 'bg-danger'; ?> text-white">
          <h5 class="modal-title">
            <?php if ($modalType == 'success'): ?>
              <ion-icon name="checkmark-circle-outline" style="font-size: 1.5rem; vertical-align: middle;"></ion-icon>
            <?php else: ?>
              <ion-icon name="alert-circle-outline" style="font-size: 1.5rem; vertical-align: middle;"></ion-icon>
            <?php endif; ?>
            <?php echo htmlspecialchars($modalTitle); ?>
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center py-4">
          <?php echo $modalMessage; ?>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn <?php echo $modalType == 'success' ? 'btn-success' : 'btn-danger'; ?>" data-bs-dismiss="modal" onclick="window.location.href='Profilepage.php'">
            OK
          </button>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-5">
    <p class="mb-0">© 2025 Splash Resort Co. All Rights Reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      <?php if ($showModal): ?>
      var resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
      resultModal.show();
      <?php endif; ?>
    });
  </script>
</body>
</html>
