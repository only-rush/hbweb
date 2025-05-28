<?php
session_start();
require('config.php'); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKR Hotel - HOME</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php require('inc/links.php') ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <style>
        .availability-form{
            margin-top: -50px;
            z-index: 2;
            position: relative;
        }

        @media screen and (max-width: 575px) {
            .availability-form{
                margin-top: 25px;
                padding: 0 35px;
            } 
        }
    </style>
</head>
<body class="bg-light">
    
    <?php require('inc/header.php'); ?>

    <!-- Carousel -->

    <div class="container-fluid px-lg-4 mt-4">
    <div class="swiper swiper-container">
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <img src="images/carousel/1.png" class="w-100 d-block" />
      </div>
      <div class="swiper-slide">
        <img src="images/carousel/2.png" class="w-100 d-block" />
      </div>
      <div class="swiper-slide">
        <img src="images/carousel/3.png" class="w-100 d-block" />
      </div>
      <div class="swiper-slide">
        <img src="images/carousel/4.png" class="w-100 d-block" />
      </div>
      <div class="swiper-slide">
        <img src="images/carousel/5.png" class="w-100 d-block" />
      </div>
      <div class="swiper-slide">
        <img src="images/carousel/6.png" class="w-100 d-block" />
      </div>
    </div>
  </div>
    </div>

    <!-- check availability form -->

    <div class="container availability-form">
    <div class="row">
        <div class="col-lg-12 bg-white shadow p-4 rounded">
            <h5 class="mb-4">Check Booking Availability</h5>
            <form>
                <div class="row align-items-end">
                    <div class="col-lg-3 mb-3">
                    <label class="form-label" style="font-weight: 500;">Check-in</label>
                    <input type="date" class="form-control shadow-none">
                    </div>
                    <div class="col-lg-3 mb-3">
                    <label class="form-label" style="font-weight: 500;">Check-out</label>
                    <input type="date" class="form-control shadow-none">
                    </div>
                    <div class="col-lg-3 mb-3">
                    <label class="form-label" style="font-weight: 500;">Adult</label>
                    <select class="form-select shadow-none">
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="col-lg-2 mb-3">
                    <label class="form-label" style="font-weight: 500;">Children</label>
                    <select class="form-select shadow-none">
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="col-lg-1 mb-lg-3 mt-2">
                        <button type="submit" class="btn text-white shadow-none custom-bg">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>

    <!-- Our Rooms -->

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR ROOMS</h2>

    <div class="container">
        <div class="row">
            <?php
$query = "SELECT id, name, photo_path, price_per_night FROM rooms WHERE status_id = 1";
$result = mysqli_query($conn, $query);

while ($room = mysqli_fetch_assoc($result)) {
    $room_id = $room['id'];

    // Fetch features
    $features = [];
    $feature_query = "SELECT f.name FROM room_features rf 
                      INNER JOIN features f ON rf.feature_id = f.id 
                      WHERE rf.room_id = $room_id";
    $feature_result = mysqli_query($conn, $feature_query);
    while ($feature = mysqli_fetch_assoc($feature_result)) {
        $features[] = $feature['name'];
    }

    // Fetch facilities
    $facilities = [];
    $facility_query = "SELECT fa.name FROM room_facilities rf 
                       INNER JOIN facilities fa ON rf.facilities_id = fa.id 
                       WHERE rf.room_id = $room_id";
    $facility_result = mysqli_query($conn, $facility_query);
    while ($facility = mysqli_fetch_assoc($facility_result)) {
        $facilities[] = $facility['name'];
    }

    echo '
    <div class="col-lg-4 col-md-6 my-3">
        <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
            <img src="' . htmlspecialchars(preg_replace('#^\.\./#', '', $room['photo_path'])) . '" class="card-img-top">

            <div class="card-body">
                <h5>' . htmlspecialchars($room['name']) . '</h5>
                <h6 class="mb-4">₱' . number_format($room['price_per_night']) . ' per night</h6>

                <div class="features mb-4">
                    <h6 class="mb-1">Features</h6>';
                    foreach ($features as $f) {
                        echo '<span class="badge rounded-pill bg-light text-dark text-wrap">' . htmlspecialchars($f) . '</span> ';
                    }
    echo        '</div>

                <div class="facilities mb-4">
                    <h6 class="mb-1">Facilities</h6>';
                    foreach ($facilities as $fc) {
                        echo '<span class="badge rounded-pill bg-light text-dark text-wrap">' . htmlspecialchars($fc) . '</span> ';
                    }
    echo        '</div>


                <div class="d-flex justify-content-evenly mb-2">
                    <a href="rooms.php?id=' . $room_id . '" class="btn btn-sm text-white custom-bg shadow-none">Book Now</a>
                </div>
            </div>
        </div>
    </div>';
}
?>


            <div class="col-lg-12 text-center mt-5">
                <a href="rooms.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Rooms >>></a>
            </div>
        </div>
    </div>

    <!-- Our Facilities -->

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR FACILITIES</h2>

    <div class="container">
        <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
            <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                <img src="images/facilities/Wifi.svg"  width="80px">
                <h5 class="mt-3">Wifi</h5>
            </div>
            <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                <img src="images/facilities/Television.svg"  width="80px">
                <h5 class="mt-3">Television</h5>
            </div>
            <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                <img src="images/facilities/Water Heater.svg"  width="80px">
                <h5 class="mt-3">Water Heater</h5>
            </div>
            <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                <img src="images/facilities/AC.svg"  width="80px">
                <h5 class="mt-3">Air Condition</h5>
            </div>
            <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                <img src="images/facilities/Spa.svg"  width="80px">
                <h5 class="mt-3">Spa</h5>
            </div>
            <div class="col-lg-12 text-center mt-5">
            <a href="facilities.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Facilities >>></a>
            </div>
        </div>
    </div>

    <!-- Testimonials -->

<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">TESTIMONIALS</h2>

   <div class="container mt-5">
     <div class="swiper swiper-testimonials">
        <div class="swiper-wrapper mb-5">

        

        </div>
       <div class="swiper-pagination"></div>
    </div>
    <div class="col-lg-12 text-center mt-5">
            <a href="#" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Know More >>></a>
            </div>
   </div>

    <!-- Reach Us -->
    
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">REACH US</h2>

    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
              <iframe class="w-100 rounded" height="320px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d585.9323928304575!2d125.00444880716721!3d11.238620746173423!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3308772d3ec60a1b%3A0xa31812625d7ca506!2sACLC%20College!5e0!3m2!1sen!2sph!4v1734248862452!5m2!1sen!2sph" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-lg-4 col-md-4">
              <div class="bg-white p-4 rounded mb-4">
                <h5>Call Us</h5>
                <a href="tel: +63111222333" class="d-inline-block mb-2 text-decoration-none text-dark">
                <i class="bi bi-telephone-fill"></i>+63 923 456 7890
                </a>
                <br>
                <a href="tel: +63111222333" class="d-inline-block text-decoration-none text-dark">
                <i class="bi bi-telephone-fill"></i>+63 912 345 6789
                </a>
              </div>
              <div class="bg-white p-4 rounded mb-4">
                <h5>Follow Us</h5>
                <a href="#" class="d-inline-block mb-3">
                <span class="badge bg-light text-dark fs-6 p-2">
                <i class="bi bi-facebook me-1"></i> Facebook
                </span>
                </a>
                <br>
                <a href="#" class="d-inline-block mb-3">
                <span class="badge bg-light text-dark fs-6 p-2">
                <i class="bi bi-instagram"></i> Instagram
                </span>
                </a>
                <br>
                <a href="#" class="d-inline-block">
                <span class="badge bg-light text-dark fs-6 p-2">
                <i class="bi bi-twitter-x"></i> Twitter
                </span>
                </a>
              </div>
            </div>
        </div>
    </div>


    <?php require('inc/footer.php') ?>

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  // Hero or header swiper
  var swiperHeader = new Swiper(".swiper-container", {
    spaceBetween: 30,
    effect: "fade",
    loop: true,
    autoplay: {
      delay: 3500,
      disableOnInteraction: false,
    }
  });

  // Fetch reviews and initialize testimonial swiper
  $(document).ready(function () {
    $.ajax({
      url: 'ajax-request/get-reviews.php',
      type: 'GET',
      dataType: 'json',
      success: function (data) {
        let reviewHTML = '';
        data.forEach(function (review) {
          let stars = '';
          const maxStars = 5;
          for (let i = 1; i <= maxStars; i++) {
            stars += `<i class="bi ${i <= review.rating ? 'bi-star-fill text-warning' : 'bi-star text-muted'}"></i>`;
          }

          reviewHTML += `
            <div class="swiper-slide bg-white p-4">
              <div class="profile d-flex align-items-center mb-3">
                <img src="images/about/customers.svg" width="30px">
                <h6 class="m-0 ms-2">${review.name}</h6>
              </div>
              <p>${review.review}</p>
              <div class="rating">${stars}</div>
            </div>
          `;
        });

        $('.swiper-testimonials .swiper-wrapper').html(reviewHTML);

        new Swiper(".swiper-testimonials", {
          effect: "coverflow",
          grabCursor: true,
          centeredSlides: true,
          loop: true,
          coverflowEffect: {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: false,
          },
          pagination: {
            el: ".swiper-pagination",
          },
          breakpoints: {
            320: { slidesPerView: 1 },
            640: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 },
          }
        });
      },
      error: function () {
        console.error('Failed to fetch reviews.');
      }
    });
  });
</script>



  

</body>
</html>