const reservationBookBtn = document.querySelector("#reservationBookBtn");

reservationBookBtn.addEventListener("click", (e) => {
  e.preventDefault();
  window.location.href = "signUpPage.php";
});
