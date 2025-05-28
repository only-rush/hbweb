<?php
require('../config.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SKR Hotel - ROOMS</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .custom-bg {
      background-color: #007BFF;
    }
    .h-font {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
  </style>
  <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php require('inc/links.php') ?>
</head>
<body class="bg-light">
<?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
      <h2 class="fw-bold h-font text-center">OUR ROOMS</h2>
      <div class="h-line bg-dark"></div>
    </div>

<div class="container">
  <div class="row">
    <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 px-lg-0">
      <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
          <div class="container-fluid flex-lg-column align-item-stret #ch" id="room-list">
              <h4 class="mt-2">FILTERS</h4>
              <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterdropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse flex-column align-item-stretch mt-2" id="navbarNav">
              <div class="border bg-light p-3 rounded mb-3">
                  <h5 class="mb-3" style="font-size: 18PX;">GUESTS</h5>
                  <div class="d-flex">
                      <div class="me-3">
                      <label class="form-label">Adults</label>
                      <input type="number" class="form-control shadow-none guest-input" name="adults">
                      </div>
                      <div>
                      <label class="form-label">Children</label>
                      <input type="number" class="form-control shadow-none guest-input" name="children">
                      </div>                    
                  </div>
              </div>
              <div class="border bg-light p-3 rounded mb-3">
                  <h5 class="mb-3" style="font-size: 18PX;">FACILITIES</h5>
                  <div>
                    <input type="checkbox" class="form-check-input shadow-none me-1 facility-filter" value="Wifi">
                    <label class="form-check-label" for="f1">Wifi</label>                      
                  </div>
                  <div>
                    <input type="checkbox" class="form-check-input shadow-none me-1 facility-filter" value="Television">
                    <label class="form-check-label" for="f2">Television</label>                      
                  </div>    
                  <div>
                  <input type="checkbox" class="form-check-input shadow-none me-1 facility-filter" value="Water Heater">
                    <label class="form-check-label" for="f3">Water Heater</label>                      
                  </div>           
                  <div>
                    <input type="checkbox" class="form-check-input shadow-none me-1 facility-filter" value="Spa">
                    <label class="form-check-label" for="f2">Spa</label>                      
                  </div>    
                  <div>
                    <input type="checkbox" class="form-check-input shadow-none me-1 facility-filter" value="Radio">
                    <label class="form-check-label" for="f2">Radio</label>                      
                  </div>             
              </div>
              </div>
          </div>
        </nav>
    </div>
    


    <div class="col-lg-9 col-md-12 px-4">
      <?php
        $room_q = "SELECT * FROM rooms";
        $room_res = mysqli_query($conn, $room_q);

        while ($room = mysqli_fetch_assoc($room_res)) {
          $features = [];
          $facilities = [];

          // Fetch features
          $feature_q = "SELECT f.name FROM features f 
                        INNER JOIN room_features rf ON f.id = rf.feature_id 
                        WHERE rf.room_id = {$room['id']}";
          $feature_res = mysqli_query($conn, $feature_q);
          while ($f = mysqli_fetch_assoc($feature_res)) {
            $features[] = $f['name'];
          }

          // Fetch facilities
          $facility_q = "SELECT fc.name FROM facilities fc 
                         INNER JOIN room_facilities rfc ON fc.id = rfc.facilities_id 
                         WHERE rfc.room_id = {$room['id']}";
          $facility_res = mysqli_query($conn, $facility_q);
          while ($fc = mysqli_fetch_assoc($facility_res)) {
            $facilities[] = $fc['name'];
          }

          $features_str = implode(',', $features);
          $facilities_str = implode(',', $facilities);

          $image_path = '../uploads/' . $room['photo_path'];

          echo <<<HTML
            <div class="card mb-4 border-0 shadow">
              <div class="row g-0 p-3 align-items-center">
                <div class="col-md-5 mb-lg-0 mb-md-0 mb-3">
                  <img src="{$image_path}" class="img-fluid rounded" alt="{$room['name']}">
                </div>
                <div class="col-md-5 px-lg-3 px-md-3 px-0">
                  <h5 class="mb-3">{$room['name']}</h5>
                  <div class="features mb-3">
                    <h6 class="mb-1">Features</h6>
HTML;

          foreach ($features as $feature) {
            echo "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>{$feature}</span>";
          }

          echo <<<HTML
                  </div>
                  <div class="facilities mb-4">
                    <h6 class="mb-1">Facilities</h6>
HTML;

          foreach ($facilities as $facility) {
            echo "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>{$facility}</span>";
          }

          echo <<<HTML
                  </div>
                </div>
                <div class="col-md-2 text-center">
                  <h6 class="mb-4">₱{$room['price_per_night']} per night</h6>
                  <button 
                    type="button" 
                    class="btn btn-sm w-100 text-white custom-bg shadow-none mb-2 book-now-btn" 
                    data-bs-toggle="modal" 
                    data-bs-target="#bookingModal"
                    data-room-id="{$room['id']}" 
                    data-room-name="{$room['name']}"
                    data-price-per-night="{$room['price_per_night']}">
                    Book Now
                  </button>


                  <button 
                    class="btn btn-sm w-100 btn-outline-dark shadow-none more-details-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#detailsModal"
                    data-room-name="{$room['name']}"
                    data-room-description="{$room['description']}"
                    data-room-features="{$features_str}"
                    data-room-facilities="{$facilities_str}">
                    More details
                  </button>


                </div>
              </div>
            </div>
HTML;
        }
      ?>
    </div>
  </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="bookingForm" method="POST" action="process_booking.php">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="bookingModalLabel">Book Room</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="room_id" id="modal_room_id">
          
          <div class="mb-3">
            <label for="modal_room_name" class="form-label">Room</label>
            <input type="text" class="form-control" id="modal_room_name" disabled>
          </div>

          <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method</label>
            <select name="subject_id" id="subject_id" class="form-select shadow-none" required>
                            <option disabled selected value="">--Select Payment Method--</option>
                            <?php

                            $query = "SELECT * FROM payment_method";
                            $result = mysqli_query($conn, $query);

                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                }
                            } else {
                                echo "<option value=''>No subjects found</option>";
                            }

                            mysqli_close($conn);
                            ?>
                        </select>
          </div>

          <div class="mb-3">
            <label for="check_in" class="form-label">Check-in Date</label>
            <input type="date" class="form-control" name="check_in" id="check_in" required>
          </div>

          <div class="mb-3">
            <label for="check_out" class="form-label">Check-out Date</label>
            <input type="date" class="form-control" name="check_out" id="check_out" required>
          </div>
        </div>
        <div class="mb-3">
          <label for="total_price" class="form-label" style="
              margin-left: 20px;
          ">Total Price (₱)</label>
          <input type="text" class="form-control" name="total_price" id="total_price" readonly="" style="
              width: 326px;
              padding-left: 12px;
              margin-left: 20px;
          ">
        </div>

        <div class="modal-footer">
          <div id="messageBox" class="alert" style="display:none; margin-top: 10px;"></div>
          <button type="submit" class="btn custom-bg text-white">Book</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
  <div id="bookingToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="toastMessage">
        Booking successful!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<!-- Room Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailsModalLabel">Room Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4 id="detailRoomName" class="fw-bold mb-3"></h4>
        <p id="detailRoomDescription"></p>

        <h6>Features</h6>
        <div id="detailFeatures" class="mb-3"></div>

        <h6>Facilities</h6>
        <div id="detailFacilities"></div>
      </div>
    </div>
  </div>
</div>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php require('inc/footer.php') ?>
</body>
</html>

<script>
document.addEventListener('DOMContentLoaded', function () {
  let pricePerNight = 0;

  document.querySelectorAll('.book-now-btn').forEach(button => {
    button.addEventListener('click', function () {
      const roomId = this.getAttribute('data-room-id');
      const roomName = this.getAttribute('data-room-name');
      pricePerNight = parseFloat(this.getAttribute('data-price-per-night'));

      document.getElementById('modal_room_id').value = roomId;
      document.getElementById('modal_room_name').value = roomName;
      document.getElementById('total_price').value = '';
      document.getElementById('check_in').value = '';
      document.getElementById('check_out').value = '';
    });
  });

  function calculatePrice() {
    const checkIn = new Date(document.getElementById('check_in').value);
    const checkOut = new Date(document.getElementById('check_out').value);

    if (checkIn && checkOut && checkOut > checkIn) {
      const nights = (checkOut - checkIn) / (1000 * 60 * 60 * 24);
      const totalPrice = nights * pricePerNight;
      document.getElementById('total_price').value = totalPrice.toFixed(2);
    } else {
      document.getElementById('total_price').value = '';
    }
  }

  document.getElementById('check_in').addEventListener('change', calculatePrice);
  document.getElementById('check_out').addEventListener('change', calculatePrice);

  // Handle form submission via AJAX
  document.getElementById('bookingForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = {
      room_id: document.getElementById('modal_room_id').value,
      payment_method_id: document.getElementById('subject_id').value,
      check_in: document.getElementById('check_in').value,
      check_out: document.getElementById('check_out').value,
      price: document.getElementById('total_price').value
    };

    fetch('ajax-request/ajax-bookings.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(formData)
    })
    .then(response => response.json())
.then(data => {
  const messageBox = document.getElementById('messageBox');
  messageBox.classList.remove('alert-success', 'alert-danger');
  
  if (data.success) {
    messageBox.textContent = "Booking successful!";
    messageBox.classList.add('alert-success');
    // Optionally reload after a delay
    setTimeout(() => location.reload(), 1500);
  } else {
    messageBox.textContent = "Booking failed: " + data.message;
    messageBox.classList.add('alert-danger');
  }
  
  messageBox.style.display = 'block'; // make sure it's visible
})

    .catch(error => {
  console.error('Error:', error);

  // Reset the form fields
  document.getElementById('bookingForm').reset();

  // Optionally reset the room name and ID (if needed)
  document.getElementById('modal_room_name').value = '';
  document.getElementById('modal_room_id').value = '';

  // Reset the total price field
  document.getElementById('total_price').value = '';

  // Show error toast
  const toastEl = document.getElementById('bookingToast');
  const toastMessage = document.getElementById('toastMessage');

  toastEl.classList.remove('bg-success');
  toastEl.classList.add('bg-danger');
  toastMessage.innerText = "Please Login first!";

  const toast = new bootstrap.Toast(toastEl);
  toast.show();
});

  });

    document.querySelectorAll('.more-details-btn').forEach(button => {
    button.addEventListener('click', function () {
      const name = this.getAttribute('data-room-name');
      const description = this.getAttribute('data-room-description') || 'No description available.';
      const features = (this.getAttribute('data-room-features') || '').split(',');
      const facilities = (this.getAttribute('data-room-facilities') || '').split(',');

      document.getElementById('detailRoomName').innerText = name;
      document.getElementById('detailRoomDescription').innerText = description;

      const featureContainer = document.getElementById('detailFeatures');
      featureContainer.innerHTML = '';
      features.forEach(f => {
        if (f.trim()) {
          featureContainer.innerHTML += `<span class='badge bg-info text-dark me-1 mb-1'>${f.trim()}</span>`;
        }
      });

      const facilityContainer = document.getElementById('detailFacilities');
      facilityContainer.innerHTML = '';
      facilities.forEach(f => {
        if (f.trim()) {
          facilityContainer.innerHTML += `<span class='badge bg-secondary me-1 mb-1'>${f.trim()}</span>`;
        }
      });
    });
  });

  $(document).ready(function () {
  function filterRooms() {
    const facilities = [];
    $('.facility-filter:checked').each(function () {
      facilities.push($(this).val());
    });

    const adults = $('input[name="adults"]').val();
    const children = $('input[name="children"]').val();

    $.ajax({
      url: 'ajax-request/filter_rooms.php',
      method: 'POST',
      data: {
        facilities: facilities,
        adults: adults,
        children: children
      },
      success: function (data) {
        $('#room-list').html(data);
      }
    });
  }

  // Trigger filter on change
  $('.facility-filter, .guest-input').on('change keyup', filterRooms);
});

  

});
</script>


